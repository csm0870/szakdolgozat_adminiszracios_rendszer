<?php
namespace App\Controller\Reviewer;

use App\Controller\AppController;

/**
 * Reviews Controller
 *
 * @property \App\Model\Table\ReviewsTable $Reviews
 *
 * @method \App\Model\Entity\Review[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewsController extends AppController
{
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['uploadConfidentialityContract'])) $this->viewBuilder()->setLayout(false);
    }
        
    /**
     * Titoktartási szerződés feltöltése
     * 
     * @param type $id Téma aonzosítója
     */
    public function uploadConfidentialityContract($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');       
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A titoktartási nyilatkozat nem tölthető fel.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [23])){ //Nem "A téma nem elfogadott", nem "Diplomakurzus sikertelen, tanaszékvezető döntésére vár", nem "Első diplomakurzus teljesítve", vagy nem "Elutsítva (első diplomakurzus sikertelen)" státuszban van
            $error_msg = __('A titoktartási nyilatkozat nem tölthető fel.') . ' ' . ' ' . __('A téma nem bírálható állapotban van.');
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $error_msg = __('A dolgozat részletei nem elérhetők.') . ' ' . __('A dolgozatnak nem Ön a bírálója.');
                $ok = false;
            }
        }
        
        if($ok === true && $thesisTopic->review->confidentiality_contract_status == 4){ //Ha már el van fogadva a titoktartási szerződés
            $error_msg = __('A dolgozat részletei nem elérhetők.') . ' ' . __('A titoktartási szerződés már el van fogadva.');
            $ok = false;
        }
        
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $thesisTopic->review = $this->Reviews->patchEntity($thesisTopic->review, $this->getRequest()->getData());
            
            if(empty($thesisTopic->review->confidentiality_contract['name'])){
                $thesisTopic->setError('confidentiality_contract', __('Fájl feltöltése kötelező.'));
            }else{
                $thesisTopic->review->confidentiality_contract['name'] = $this->addFileName($thesisTopic->review->confidentiality_contract['name'], ROOT . DS . 'files' . DS . 'confidentiality_contracts');
            }
            $thesisTopic->review->confidentiality_contract_status = 1;
            
            if($this->Reviews->save($thesisTopic->review)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $thesisTopic->review->getErrors();
                if(!empty($errors)){
                    foreach($errors as $error){
                        if(is_array($error)){
                            foreach($error as $err){
                                $error_ajax.= '<br/>' . $err;
                            }
                        }else{
                            $error_ajax.= '<br/>' . $error;
                        }
                    }
                }
            }
        }
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Feltöltött titoktartási szerződés letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function finalizeConfidentialityContractUpload($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [23, 24])){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('A téma nincs abban az állapotban, hogy a titoktartási szerződés véglegesíthető lenne.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if($ok === true && empty($thesisTopic->review->confidentiality_contract)){ //Ha nincs titoktartási szerződés feltöltve
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('Nincs feltöltve titoktartási szerződés.'));
            $ok = false;
        }
        if($ok === true && $thesisTopic->review->confidentiality_contract_status == 4){ //Ha a titoktartási szerződés már el van fogadva
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('A titoktartási szerződés már el van fogadva.'));
            $ok = false;
        }
        
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $thesisTopic->review->confidentiality_contract_status = 2; //Véglegesítve, tanszékvezető ellenőrzésére vár
        
        if($this->Reviews->save($thesisTopic->review)){
            $this->Flash->success(__('Véglegesítés sikeres.'));
        }else{
            $this->Flash->error(__('Véglegesítés sikertelen. Próbálja újra!'));
        }
        
        return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]);
    }
    
    /**
     * Feltöltött titoktartási szerződés letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getUploadedConfidentialityContract($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [23, 24])){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('A téma nincs abban az állapotban, hogy a titoktartási szerződés letölthető lehetne.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if($ok === true && empty($thesisTopic->review->confidentiality_contract)){ //Ha nincs titoktartási szerződés feltöltve
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('Nincs feltöltve titoktartási szerződés.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'confidentiality_contracts' . DS . $thesisTopic->review->confidentiality_contract,
                                                   ['download' => true, 'name' => $thesisTopic->review->confidentiality_contract]);

        return $response;
    }
    
    /**
     * Titoktartási nyilatkozat letöltése
     * 
     * @param type $id Téma azonosítója
     * @return type
     */
    public function confidentialityContractDoc($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [23])){ //Nem "A téma nem elfogadott", nem "Diplomakurzus sikertelen, tanaszékvezető döntésére vár", nem "Első diplomakurzus teljesítve", vagy nem "Elutsítva (első diplomakurzus sikertelen)" státuszban van
            $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('A téma nem bírálható állapotban van.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); })
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews', 'Students' => ['Courses', 'CourseLevels', 'CourseTypes']])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('A dolgozatnak nem Ön a bírálója'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
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
        
        //Bekezdés stílusok                                  'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12)]);
        $subTitlePara = 'SubTitleParagraph';
        $phpWord->addParagraphStyle($subTitlePara, ['spacing' => 1, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                                                    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        
        //Címsorok
        $headingOne = 1;
        $phpWord->addTitleStyle($headingOne, ['bold' => true, 'size' => 16],
                                             ['spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                                              'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
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
        $section->addTitle('Titoktartási nyilatkozat', $headingOne);
        //Adatok
        $section->addText('Adatok', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1);
        //Hallgató adatai
        $section->addText('Hallgató adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]); //Tab-nál kettős idézőjel!!!!!
        $section->addText('   Név: ' . ($thesisTopic->has('student') ? $thesisTopic->student->name : '') .  "\tNeptun-kód: " . ($thesisTopic->has('student') ? $thesisTopic->student->neptun : ''), null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(11.2))]]);
        $section->addText('   Szak: ' . ($thesisTopic->has('student') ? (($thesisTopic->student->has('course') ? $thesisTopic->student->course->name : '') . ($thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? (' ' . $thesisTopic->student->course_level->name) : '') : '' )) : '' ));
        $section->addText('   Specializáció: ' . ($thesisTopic->has('student') ? $thesisTopic->student->specialisation : ''));
        $section->addText('   Tagozat: ' . ($thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? $thesisTopic->student->course_type->name : '') : '' ));
        $section->addTextBreak(2);
        //Szakdolgozat adatai
        $section->addText('A ' . ($thesisTopic->is_thesis == 0 ? 'diplomamunka' : 'szakdolgozat') . ' adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Cím: ' . $thesisTopic->title);
        $section->addText('   Nyelv: ' . ($thesisTopic->has('language') ? $thesisTopic->language->name : ''));
        $section->addTextBreak(1);
        //Cég adatai
        $section->addText('Partner-intézmény (cég, gazdasági társaság, intézmény) adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Név:');
        $section->addText('   Cím:');
        $section->addTextBreak(1);
        //A titoktartási nyilatkozatot adó személy adatai
        $section->addText('A titoktartási nyilatkozatot adó személy adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Név:');
        $section->addText('   Intézmény:');
        $section->addText('   A megbízás jellege: bíráló');
        $section->addText('   A titoktartási időszak vége:');
        $section->addTextBreak(2);
        
        //Nyilatkozat
        
        //Adatok
        $section->addText('Nyilatkozat', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1, $subTitleFont, $subTitlePara);
        $section->addText('Alulírott tudomásul veszem, hogy a fent említett Hallgató ' . 
                           ($thesisTopic->is_thesis == 0 ? 'diplomamunkájának' : 'szakdolgozatának') . 
                            ' bírálata során olyan információk birtokába jutok, melyek a fenti Partner-intézmény szellemi tulajdonát képezik, így bizalmasan kezelendők.',
                            ['size' => 11], ['spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        $section->addText('A dolgozatról illetve annak részeiről másolatot nem készítek, annak példányát munkám végeztével visszaadom vagy visszaküldöm annak (Partner-intézmény, bírálatot kérő tanszék, záróvizsga-bizottság),' .
                          ' akitől kaptam. A dolgozattal kapcsolatos megbízásom körén kívül szóban és írásban sem adok információt át más személyeknek, intézménynek a titoktartási időszak végéig.',
                          ['size' => 11], ['spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        $section->addTextBreak(1, $subTitleFont, $subTitlePara);
        $section->addText('[hely][dátum]', $redTextFont);
        $section->addTextBreak(1);
        
        //Táblázat (aláíráshoz)
        $table1 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top']);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.75), ['valign' => 'top'])->addText('____________________________', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top']);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.75), ['valign' => 'top'])->addText('aláírás', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        //Fájl "letöltése"
        $filename =  'titkositasi_nyilatkozat.docx';
        
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document");
        header('Content-Disposition: attachment; filename='.$filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
        $objWriter->save("php://output");

        //Kilépés, nehogy a cakephp további dolgokat végezzen, mert akkor a fájl nem menne ki
        exit();
    }
}
