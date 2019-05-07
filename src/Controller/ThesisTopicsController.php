<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ThesisTopics Controller
 *
 * @property \App\Model\Table\ThesisTopicsTable $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisTopicsController extends AppController
{
    /**
     * Pdf generálás CakdePdf pluginnal
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function exportPdf($id = null){
        $group_id = $this->Auth->user('group_id');
        
        $prefix = '';
        if($group_id == 1){
            $prefix = 'admin';
        }elseif($group_id == 2){
            $prefix = 'internal_consultant';
        }elseif($group_id == 3){
            $prefix = 'head_of_department';
        }elseif($group_id == 4){
            $prefix = 'topic_manager';
        }elseif($group_id == 5){
            $prefix = 'thesis_manager';
        }elseif($group_id == 6){
            $prefix = 'student';
        }elseif($group_id == 7){
            $prefix = 'reviewer';
        }elseif($group_id == 8){
            $prefix = 'final_exam_organizer';
        }
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true],
                                                         'contain' => ['Students' => ['Courses', 'CourseLevels', 'CourseTypes'],
                                                                       'InternalConsultants' => ['Departments', 'InternalConsultantPositions'],
                                                                       'StartingYears', 'ExpectedEndingYears', 'Languages']])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma nem létezik.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')])){
            $this->Flash->error(__('A PDF nem elérhető.') . ' ' . __('A téma még nem lett leadva.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
        $this->viewBuilder()->setLayout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'title' => "feladatkiiro_lap-" . date("Y-m-d-H-i-s"),
                'margin' => [
                    'bottom' => 12,
                    'left' => 12,
                    'right' => 12,
                    'top' => 12
                ]
            ]
        ]);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Word dokumentum (docx) generálás phpWord-del
     * 
     * @param type $id Téma ID-ja
     * @return type
     * @throws \Cake\Core\Exception\Exception
     */
    public function encyptionRegulationDoc($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['contain' => ['Students' => ['Courses', 'CourseTypes'], 'Languages'],
                                                                       'conditions' => ['ThesisTopics.id' => $id]])->first();
        $group_id = $this->Auth->user('group_id');
        
        $prefix = '';
        if($group_id == 1){
            $prefix = 'admin';
        }elseif($group_id == 2){
            $prefix = 'internal_consultant';
        }elseif($group_id == 3){
            $prefix = 'head_of_department';
        }elseif($group_id == 4){
            $prefix = 'topic_manager';
        }elseif($group_id == 5){
            $prefix = 'thesis_manager';
        }elseif($group_id == 6){
            $prefix = 'student';
        }elseif($group_id == 7){
            $prefix = 'reviewer';
        }elseif($group_id == 8){
            $prefix = 'final_exam_organizer';
        }
        
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id'], 'prefix' => 'student']);
            }
            
            if($thesisTopic->student_id != $data['student_id']){
                 $this->Flash->error(__('A titkosítási kérelem nem elérhető.') . ' ' . __('A téma nem Önhöz tartozik.'));
                 return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => 'student']);
            }
        }
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A titoksítási kérelem nem elérhető.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->confidential !== true){
            $this->Flash->error(__('A titkosítási kérelem nem elérhető.') . ' ' . __('A téma nem titkos.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
        $hun_months = ["január", "február", "március", "április", "május", "június",
                       "július", "augusztus", "szeptember", "október", "november", "december"];
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Alapbeállítások
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);
        $phpWord->setDefaultParagraphStyle(['spacing' => 1, 'spaceBefore' => 0, 'spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        
        //Szöveg stílusok
        $redTextFont = 'RedText';
        $phpWord->addFontStyle($redTextFont, ['color' => '800000']);
        $subTitleFont = 'SubTitleFont';
        $phpWord->addFontStyle($subTitleFont, ['size' => 14, 'bold' => true]);
        
        //Bekezdés stílusok
        $subTitlePara = 'SubTitleParagraph';
        $phpWord->addParagraphStyle($subTitlePara, ['spacing' => 120, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                                                    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        
        //Címsorok
        $headingOne = 1;
        $phpWord->addTitleStyle($headingOne, ['bold' => true, 'size' => 16],
                                             ['spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                                              'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(3),
                                              'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        
        //Dokumentum készítése
        
        //Szekció
        $section = $phpWord->addSection(['marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'orientation' => 'portrait', 'footerHeight' => 1.25, 'headerHeight' => 1.25]);
        
        //Első oldal
        
        //Cím
        $section->addTitle(($thesisTopic->is_thesis === true ? 'Szakdolgozat' : 'Diplomamunka') . ' téma titkos kezelésének kezdeményezése', $headingOne);
        
        $section->addTextBreak(1);
        
        //Adatok
        $section->addText('Adatok', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1);
        //Hallgató adatai
        $section->addText('Hallgató adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]); //Tab-nál kettős idézőjel!!!!!
        $section->addText('   Név: ' . ($thesisTopic->has('student') ? $thesisTopic->student->name : '') .  "\tNeptun-kód: " . ($thesisTopic->has('student') ? $thesisTopic->student->neptun : ''), null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(11))]]);
        $section->addText('   Szak: ' . ($thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? $thesisTopic->student->course->name : '') : '' ));
        $section->addTextBreak(1);
        $section->addText('   Tagozat: ' . ($thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? $thesisTopic->student->course_type->name : '') : '' ));
        $section->addTextBreak(1);
        //Szakdolgozat adatai
        $section->addText('A ' . ($thesisTopic->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Cím: ' . $thesisTopic->title);
        $section->addText('   Nyelv: ' . ($thesisTopic->has('language') ? $thesisTopic->language->name : ''));
        $section->addTextBreak(1);
        //Cég adatai
        $section->addText('Partner-intézmény (cég, gazdasági társaság, intézmény) adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Név:');
        $section->addText('   Cím:');
        $section->addText('   Képviselő:');
        $section->addTextBreak(3);
        //Kezdeményezés
        $section->addText('Kezdeményezés', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1);
        //Kezdeményezés szövege
        $textrun1 = $section->addTextRun();
        $textrun1->addText(($thesisTopic->is_thesis === true ? 'Szakdolgozat' : 'Diplomamunka') . ' kidolgozása során ');
        $textrun1->addText('[cégünk/társaságunk/intézményünk]', $redTextFont);
        $textrun1->addText(' a fent említett Hallgató számára bizalmas információkba való betekintést is enged, és ezek egy része a készülő dolgozatba is belekerül. Ezek ipari, üzleti titoknak minősülnek, ezért bizalmas kezelésüket garantálni kell.');
        $section->addTextBreak(1);
        $section->addText('[További rövid indoklás: .....]', $redTextFont);
        $section->addTextBreak(1);
        $textrun2 = $section->addTextRun();
        $textrun2->addText('A titkosítás időtartama ');
        $textrun2->addText('[max. 5]', $redTextFont);
        $textrun2->addText(' év.');
        $section->addTextBreak(1);
        $section->addText('Kezdeményezem, hogy az elkészült dolgozatot a Széchenyi Egyetem Gépészmérnöki, Informatikai és Villamosmérnöki Kara kezelje a kari Záróvizsga Szabályzatnak megfelelően titkos dokumentumként.');
        $section->addTextBreak(4);
        
        $section->addText('Győr, ' . date('Y') . '. ' . $hun_months[intval(date('n')) - 1] . ' ' . date('j') . '.');
        $section->addTextBreak(1);
        
        //Táblázat (aláíráshoz)
        $table1 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top'])->addText('P.H.', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.75), ['valign' => 'top'])->addText('____________________________', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top']);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.75), ['valign' => 'top'])->addText('cégszerű aláírás', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $section->addPageBreak();
        
        //Második oldal
        
        //Jóváhagyás
        $section->addText('Jóváhagyás', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1);
        $section->addText('A Széchenyi István Egyetem Gépészmérnöki, Informatikai és Villamosmérnöki Kara és a ' . ($thesisTopic->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') .  ' témájában illetékes tanszék nevében nyilatkozunk arról, hogy a fenti „Adatok” részben meghatározott ' . ($thesisTopic->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a Kar Záróvizsga Szabályzatának megfelelően titkosan kezeljük. (A Szabályzat idevágó részét lentebb idézzük.)');
        $section->addTextBreak(1);
        $textrun3 = $section->addTextRun();
        $textrun3->addText('A titkosítási időszak vége: ');
        $textrun3->addText('[év. hónap nap.]', $redTextFont);
        $section->addTextBreak(1);
        $section->addText('Győr, ' . date('Y') . '. ' . $hun_months[intval(date('n')) - 1] . ' ' . date('j') . '.');
        $section->addTextBreak(1);
        
        //Táblázat (aláíráshoz)
        $table2 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table2->addRow(null, ['cantSplit' => false]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(8.5), ['valign' => 'top'])->addText('____________________________', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.5), ['valign' => 'top'])->addText('P.H.', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.2), ['valign' => 'top'])->addText('____________________________', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.41), ['valign' => 'top']);
        
        $table2->addRow(null, ['cantSplit' => false]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(8.5), ['valign' => 'top'])->addText('dékán', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.5), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.2), ['valign' => 'top'])->addText('tanszékvezető', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.41), ['valign' => 'top']);
        
        $section->addTextBreak(1);
        //Tudomásul vétel
        $section->addText('Tudomásul vétel', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1);
        $section->addText('Alulírott hallgató tudomásul veszem, hogy ' . ($thesisTopic->is_thesis === true ? 'szakdolgozatom' : 'diplomamunkám') .  ' kidolgozása során olyan információkhoz (gyakorlati tapasztalat, know-how, gyártástechnikai, szállítási és szolgáltatásokkal kapcsolatos információ, marketing, ügyfelekre vonatkozó és személyzeti adat) jutok, melyeket bizalmasan kell kezelnem. Vállalom, hogy a munka során megismert adatokat, tényeket, információkat csak azután adhatom át belső konzulensemnek és csak azután adhatom le dolgozatomat, miután a Partner-intézmény erre feljogosított képviselője írásban engedélyt ad. A titkosságot a fenti dátumig megőrzöm.');
        $section->addTextBreak(1);
        $section->addText('Győr, ' . date('Y') . '. ' . $hun_months[intval(date('n')) - 1] . ' ' . date('j') . '.');
        $section->addTextBreak(1);
        
        //Táblázat (aláíráshoz)
        $table3 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table3->addRow(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.84), ['cantSplit' => false]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.78), ['valign' => 'top'])->addText('P.H.', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.94), ['valign' => 'top'])->addText('____________________________', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $table3->addRow(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.84), ['cantSplit' => false]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.78), ['valign' => 'top']);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.94), ['valign' => 'top'])->addText($thesisTopic->has('student') ? $thesisTopic->student->name : 'hallgató', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $section->addTextBreak(2);
        //Kivonat a kari Záróvizsga Szabályzatból
        $section->addText('Kivonat a kari Záróvizsga Szabályzatból', $subTitleFont, $subTitlePara);
        
        
        //Szabályzat beszúrása
        $this->loadModel('Information');
        $info = $this->Information->find('all')->first();
        
        if(!empty($info) && !empty($info->encryption_requlation)){
            $section->addTextBreak(1);
            
            //A "\n"-eket nem ismeri fel a phpWord
            //Így megmaradnak a soremelések (az addText és bekezdést ad hozzá, vagyis egy soremelés van a végén)
            $textlines = explode("\n", $info->encryption_requlation);
            for ($i = 0; $i < sizeof($textlines); $i++) {
                $section->addText($textlines[$i]);
            }
        }else{
            $section->addTextBreak(2);
            $section->addText('[ide kell beidézni az elfogadott részeket a titkosításról]', $redTextFont);
        }
        
        //Fájl "letöltése"
        $filename =  'titkositasi_kerelem.docx';
        
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document");
        header('Content-Disposition: attachment; filename='.$filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
        $objWriter->save("php://output");

        //Kilépés, nehogy a cakephp további dolgokat végezzen, mert akkor a fájl nem menne ki
        exit();
    }
}
