<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * FinalExamSubjects Controller
 *
 * @property \App\Model\Table\FinalExamSubjectsTable $FinalExamSubjects
 *
 * @method \App\Model\Entity\FinalExamSubject[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FinalExamSubjectsController extends AppController
{
    /**
     * A hallgató (mérnökinformatikus) három záróvizsgatárgyának a megjelölő lapja Word dokumentumban (.doc)
     * 
     * @param type $student_id Hallgató azonosítója
     */
    public function exportDoc($student_id = null){
        $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $student_id],
                                                                    'contain' => ['FinalExamSubjects', 'FinalExamSubjectsInternalConsultants',
                                                                                  'CourseTypes', 'CourseLevels']])->first();
    
        $ok = true;
        if(empty($student)){ //Nem létező hallgató
            $ok = false;
            $this->Flash->error(__('Záróvizsga tárgy jelölő lap nem kérhető.') . ' ' . __('Nem létező hallgató.'));
        }elseif($student->course_id != 1){ //Nem mérnökinformatikus
            $ok = false;
            $this->Flash->error(__('Záróvizsga tárgy jelölő lap nem kérhető.') . ' ' . __('Csak mérnökinformatikus hallgatónak kérhető.'));
        }elseif($student->final_exam_subjects_status != 3){ //A ZV-tárgyak nem elfogadottak
            $ok = false;
            $this->Flash->error(__('Záróvizsga tárgy jelölő lap nem kérhető.') . ' ' . __('A záróvizsga-tárgyak nincsenek elfogadva.'));
        }
        
        //Még azt is meg lehetne nézni, hogy van-e olyan téma, amely már abban az állapotban van, hogy egyáltalán meg lehetett adni a ZV tárgyakat,
        // itt érdekes lehet, hogy ha esetleg egy téma visszaesik a korábbi állapotba, akkor mi legyen, így még egyenlőre nem szűrünk erre
        
        if(!$ok) return;
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Alapbeállítások
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);
        $phpWord->setDefaultParagraphStyle(['spacing' => 1, 'spaceBefore' => 0, 'spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        
        //Szöveg stílusok
        $redTextFont = 'RedText';
        $phpWord->addFontStyle($redTextFont, ['color' => '800000']);
        $titleFont = 'TitleFont';
        $phpWord->addFontStyle($titleFont, ['size' => 14, 'bold' => true]);
        
        //Bekezdés stílusok
        $titlePara = 'TitleParagraph';
        $phpWord->addParagraphStyle($titlePara, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        //Lista stílus
        $filledBulletedListStyle = ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED];
        
        
        //Dokumentum készítése
        
        //Szekció
        $section = $phpWord->addSection(['marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'orientation' => 'portrait', 'footerHeight' => 1.25, 'headerHeight' => 1.25]);
        
        //Táblázat (képekhez)
        $table = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table->addRow(null, ['cantSplit' => false]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(9.35), ['valign' => 'top'])
               ->addImage(WWW_ROOT . 'img' . DS . 'sze_gyor.jpg', ['wrappingStyle' => 'inline',
                                                                   'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(7.43),
                                                                   'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(2.4),
                                                                   'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START]);
        $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.5), ['valign' => 'top'])
               ->addImage(WWW_ROOT . 'img' . DS . 'informatika_tanszek.jpg', ['wrappingStyle' => 'inline', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END,
                                                                              'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(2),
                                                                              'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(2.22)]);
        $section->addTextBreak(1, $titleFont);
        $section->addText('________________________________________________________________', ['size' => 14], $titlePara);
        $section->addTextBreak(1, $titleFont);
        $section->addText('Záróvizsga-tárgyak', $titleFont, $titlePara);
        $section->addTextBreak(2, $titleFont);
        $section->addText("Hallgató neve:\t" . (empty($student->name) ? '' : $student->name), null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(4.25))]]);
        $section->addTextBreak(1);
        $section->addText("Neptun kódja:\t" . (empty($student->neptun) ? '' : $student->neptun), null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(4.25))]]);
        $section->addTextBreak(1);
        $section->addText("Belső konzulens neve:\t" . ($student->has('final_exam_subjects_internal_consultant') ? $student->final_exam_subjects_internal_consultant->name : ''), null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(4.25))]]);
        $section->addTextBreak(1);
        $section->addText("Záróvizsga-tantárgyak:\t", null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(4.25))]]);
        $section->addTextBreak(1);
        
        //Záróvizsga tárgyak lista
        foreach($student->final_exam_subjects as $subject){
            $section->addListItem($subject->name, 0, ['size' => 12], $filledBulletedListStyle);
        }
        
        $section->addTextBreak(1);
        $section->addText('Képzési szint:' . ($student->has('course_level') ? ' ' . $student->course_level->name : '' ));
        $section->addText('Tagozat:' . ($student->has('course_type') ? ' ' . $student->course_type->name : '' ));
        $section->addTextBreak(1);
        $section->addText('Záróvizsga időpontja: ……………./……………. tanév ……. félév');
        $section->addTextBreak(5);
        
        $hun_months = ["január", "február", "március", "április", "május", "június",
                             "július", "augusztus", "szeptember", "október", "november", "december"];
        $section->addText('Győr, ' . date('Y') . '. ' . $hun_months[intval(date('n')) - 1] . ' ' . date('j') . '.');
        $section->addTextBreak(9);
        
        //Táblázat (aláíráshoz)
        $table3 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table3->addRow(null, ['cantSplit' => false]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(9.2), ['valign' => 'top'])->addText('____________________________', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(9.2), ['valign' => 'top'])->addText('____________________________', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $table3->addRow(null, ['cantSplit' => false]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(9.2), ['valign' => 'top'])->addText('Belső konzulens', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table3->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(9.2), ['valign' => 'top'])->addText('Hallgató', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        //Fájl "letöltése"
        $filename = 'zarovizsga_targyak.docx';
        
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document");
        header('Content-Disposition: attachment; filename='.$filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
        $objWriter->save("php://output");

        //Kilépés, nehogy a cakephp további dolgokat végezzen, mert akkor a fájl nem menne ki
        exit();
    }
}
