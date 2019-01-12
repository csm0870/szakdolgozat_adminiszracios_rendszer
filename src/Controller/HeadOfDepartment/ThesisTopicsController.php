<?php
namespace App\Controller\HeadOfDepartment;

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
     * Témalista
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak azokat a témákat látja, amelyet a belső konzulens már elfogadott
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['accepted_by_internal_consultant IS NOT' => null, 'deleted !=' => true],
                                                          'contain' => ['Students', 'InternalConsultants'], 'order' => ['ThesisTopics.modified' => 'ASC']]);

        $this->set(compact('thesisTopics'));
    }
    
    /**
     * Táma elfogadása vagy elutasítása
     * @return type
     */
    public function accept(){
        if($this->getRequest()->is('post')){
            $thesisTopic_id = $this->getRequest()->getData('thesis_topic_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $thesisTopic_id, 'modifiable' => false,
                                                                              'accepted_by_internal_consultant' => true, //Ha a belső konzulens elfogadta
                                                                              'accepted_by_head_of_department IS' => null, //Tanszékvezető konzulens még nem döntött
                                                                              'accepted_by_external_consultant IS' => null //Külső konzulens még nem döntött
                                                                              ]])->first();

            if(empty($thesisTopic)){
                $this->Flash->error(__('Ezt a témát nem fogadhatja el. Már vagy döntést hozott, vagy nem Önhöz tartozik, vagy még nem véglegesített, vagy már el lett utasítva a téma!'));
                return $this->redirect(['action' => 'index']);
            }

            $thesisTopic->accepted_by_head_of_department = $accepted;
            //Többi resetelése
            $thesisTopic->accepted_by_external_consultant = null;

            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!!'));
            }else{
                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Pdf generálás CakdePdf pluginnal
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function exportPdf($id = null){
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
        }
        
        $thesisTopic = $this->ThesisTopics->get($id, ['contain' => ['Students' => ['Courses', 'CourseLevels', 'CourseTypes'], 'InternalConsultants' => ['Departments'], 'Years']]);
        
        $this->viewBuilder()->setLayout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'title' => "feladatkiiro_lap-" . date("Y-m-d-H-i-s"),
                'margin' => [
                    'bottom' => 14,
                    'left' => 14,
                    'right' => 14,
                    'top' => 14
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
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
        }
        
        $thesisTopic = $this->ThesisTopics->get($id, ['contain' => ['Students'], 'conditions' => ['encrypted' => true]]);
        
        if(empty($thesisTopic)) throw new \Cake\Core\Exception\Exception(__('A téma nem titkos, ezért nem kérhető hozzá titkosítási kérelem.'));
    
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Alapbeállítások
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);
        $phpWord->setDefaultParagraphStyle(['spacing' => 1, 'spaceBefore' => 0, 'spaceAfter' => 0]);
        
        //Szöveg stílusok
        $redTextFont = 'redText';
        $phpWord->addFontStyle($redTextFont, ['color' => '800000']);
        $signatureFont = 'Signature';
        $phpWord->addFontStyle($signatureFont, ['size' => 10]);
        
        //Bekezdés stílusok
        $normalPara = 'NormalParagraph';
        $phpWord->addParagraphStyle($normalPara, ['spacing' => 120, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        
        //Címsorok
        $headingOne = 1;
        $phpWord->addTitleStyle($headingOne, ['bold' => true, 'size' => 16, 'name' => 'Arial'],
                                ['spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12) /* Twip mértékegységben*/, 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        
        //Dokumentum készítése
        
        //Szekció
        $section = $phpWord->addSection(['marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'orientation' => 'portrait', 'footerHeight' => 1.25, 'headerHeight' => 1.25]);
        
        //Cím
        $section->addTitle('Titkosítási kérelem', $headingOne);
        
        $section->addTextBreak(1);
        
        //Első bekezdés
        $textrun = $section->addTextRun($normalPara);
        
        $textrun->addText('Alulírott ');
        $textrun->addText('[cég/társaság/intézmény (cím)]', $redTextFont);
        $textrun->addText(' kérem, hogy ');
        
        if($thesisTopic->has('student')){
            $textrun->addText($thesisTopic->student->name);
        }else{
            $textrun->addText("[hallgató neve]", $redTextFont);
        }
        
        $textrun->addText(' ' . $thesisTopic->title . ' ');
        $textrun->addText(' című diplomamunkájának ');
        $textrun->addText("[maximum 5]", $redTextFont);
        $textrun->addText(' évre történő titkosítását, mert a benne szereplő adatok és információk a cég tulajdonát képezik, ipari, üzleti titoknak minősülnek, és csak belső felhasználásra engedélyezettek.');
    
        $section->addTextBreak(3);
        $section->addText('[angolul]', $redTextFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(3);
        $section->addText('[németül]', $redTextFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(11);
        $section->addText('[hely] [dátum]', $redTextFont);
        $section->addTextBreak(1);
        
        //Táblázat
        $table = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table->addRow(null, ['cantSplit' => false]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top', ])->addText('P.H.', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top', ])->addText('____________________________', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $table->addRow(null, ['cantSplit' => false]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top', ]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top', ])->addText('aláírás', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        //Fájl "letöltése"
        $filename =  'titkositasi_kerelem.docx';
        
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document");
        header('Content-Disposition: attachment; filename='.$filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter( $phpWord, "Word2007" );
        $objWriter->save("php://output");

        //Kilépés, nehogy a cakephp további dolgokat végezzen, mert akkor a fájl nem menne ki
        exit();
    }
}
