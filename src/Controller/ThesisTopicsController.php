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
     * Hallgatói témalista
     * 
     * @return type
     */
    public function studentIndex(){
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            $can_fill_in_topic = false;
            $this->loadModel('Information');
            $info = $this->Information->find('all')->first();

            //Kitöltési időszak ellenőrzése
            if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
                $today = date('Y-m-d');

                $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
                $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

                if($today >= $start_date && $today <= $end_date){
                    $can_fill_in_topic = true;
                }
            }
            
            $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $data['student_id'], 'deleted' => false], 'order' => ['created' => 'ASC']]);
            
            $can_add_topic = $this->ThesisTopics->Students->canAddTopic($data['student_id']);
                        
            $this->set(compact('can_fill_in_topic', 'can_add_topic', 'thesisTopics'));
        }
    }
    
    /**
     * Hallgatói hozzáadás
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function studentAdd()
    {
        
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            if(!$this->ThesisTopics->Students->canAddTopic($data['student_id'])){
                $this->Flash->error(__('Nem adhat hozzá új témát!'));
                return $this->redirect(['action' => 'studentIndex']);
            }
            
            $can_fill_in_topic = false;
            $this->loadModel('Information');
            $info = $this->Information->find('all')->first();

            if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
                $today = date('Y-m-d');

                $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
                $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

                if($today >= $start_date && $today <= $end_date){
                    $can_fill_in_topic = true;
                }
            }

            if($can_fill_in_topic === true){
                $thesisTopic = $this->ThesisTopics->newEntity();
                if ($this->request->is('post')) {
                    $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
                    $thesisTopic->modifiable = true;
                    $thesisTopic->student_id = $data['student_id'];
                    $has_external_consultant = $this->getRequest()->getData('has_external_consultant');

                    //Külső konzulensi mezők beállítása
                    if(empty($has_external_consultant) || $has_external_consultant != 1){
                        $thesisTopic->external_consultant_name = null;
                        $thesisTopic->external_consultant_position = null;
                        $thesisTopic->external_consultant_workplace = null;
                    }else{
                        $thesisTopic->cause_of_no_external_consultant = null;
                    }

                    if ($this->ThesisTopics->save($thesisTopic)) {
                        $this->Flash->success(__('Mentés sikeres.'));

                        return $this->redirect(['action' => 'studentIndex']);
                    }
                    
                    $this->Flash->error(__('Hiba történt. Próbálja újra!'));
                }

                $years = $this->ThesisTopics->Years->find('list', ['order' => ['year' => 'ASC']]);
                $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
                $this->set(compact('thesisTopic', 'internalConsultants', 'years'));
            }
        
            $this->set(compact('can_fill_in_topic'));
        }
    }

    /**
     * Hallgatói szerkesztés
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function studentEdit($id = null)
    {
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            $thesisTopic = $this->ThesisTopics->get($id);
            
            if(!$thesisTopic->modifiable){
                $this->Flash->error(__('A téma nem módosítható!!'));
                return $this->redirect(['action' => 'studentIndex']);
            }
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
                $thesisTopic->student_id = $data['student_id'];
                $has_external_consultant = $this->getRequest()->getData('has_external_consultant');

                //Külső konzulensi mezők beállítása
                if(empty($has_external_consultant) || $has_external_consultant != 1){
                    $thesisTopic->external_consultant_name = null;
                    $thesisTopic->external_consultant_position = null;
                    $thesisTopic->external_consultant_workplace = null;
                }else{
                    $thesisTopic->cause_of_no_external_consultant = null;
                }

                if ($this->ThesisTopics->save($thesisTopic)) {
                    $this->Flash->success(__('Mentés sikeres.'));

                    return $this->redirect(['action' => 'studentIndex']);
                }

                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }

            $years = $this->ThesisTopics->Years->find('list', ['order' => ['year' => 'ASC']]);
            $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
            $this->set(compact('thesisTopic', 'internalConsultants', 'years'));
        }
    }
    
    /**
     * Hallgatói véglegesítés
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function studentFinalize($id = null){
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            $thesisTopic = $this->ThesisTopics->get($id);
            $thesisTopic->modifiable = false;

            if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
            else $this->Flash->error(__('Hiba történt. Próbálja újra!'));

            return $this->redirect(['action' => 'studentIndex']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->get($id);
        if ($this->ThesisTopics->delete($thesisTopic)) {
            $this->Flash->success(__('The thesis topic has been deleted.'));
        } else {
            $this->Flash->error(__('The thesis topic could not be deleted. Please, try again.'));
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
