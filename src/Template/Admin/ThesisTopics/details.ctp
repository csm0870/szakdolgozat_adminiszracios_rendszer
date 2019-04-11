<div class="container admin-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-details-body">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('A téma adatai') ?></legend>
                        <?php if($thesisTopic->has('offered_topic')){ ?>
                            <p class="mb-4 lead">
                                <strong><?= __('Információ') . ': ' ?></strong><?= __('A téma a tanszéken kiírt témák egyike.') ?>
                            </p>
                        <?php } ?>
                        <p class="mb-2">
                            <strong><?= __('Törölve (belső konzulens által)') . ': ' ?></strong><?= $thesisTopic->deleted === true ? __('Igen') : __('Nem') ?>
                        </p>
                        <p class="mb-2">
                            <strong><?= __('Téma állapota') . ': ' ?></strong>
                            <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name): '' ?>
                            <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')){ ?>
                                <br/><strong><?= __('Módosítási javaslat') . ': ' ?></strong><br/>
                                <?= h($thesisTopic->proposal_for_amendment) ?>
                            <?php }elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic') && $thesisTopic->proposal_for_amendment !== null){ ?>
                                <br/><strong><?= __('Tanszékvezetői módosítási javaslat') . ' (' . __('a téma a hallgató módosítása után van') . ')' . ':' ?></strong><br/>
                                <?= h($thesisTopic->proposal_for_amendment) ?>
                            <?php }elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->has('review')){
                                    if($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){
                                        if($thesisTopic->review->confidentiality_contract_status == null || $thesisTopic->review->confidentiality_contract_status == 1) echo '(' . __('A titoktartási szerződés feltöltésére vár.') . ')';
                                        elseif($thesisTopic->review->confidentiality_contract_status == 2) echo '(' . __('A titoktartási szerződés feltöltve, tanszékvezető ellenőrzésére vár.') . ')';
                                        elseif($thesisTopic->review->confidentiality_contract_status == 3) echo '(' . __('A titoktartási szerződés elutasítva, új feltöltésre vár.') . ')';
                                    }else{
                                        if($thesisTopic->review->review_status == null || $thesisTopic->review->review_status == 1) echo '(' . __('A dolgozat bírálatra vár.') . ')';
                                        elseif($thesisTopic->review->review_status == 2) echo '(' . __('A bírálat véglegesítve, bírálati lap feltöltésére vár.') . ')';
                                        elseif($thesisTopic->review->review_status == 3) echo '(' . __('A bírálati lap feltöltve, véglegesítésre vár.') . ')';
                                        elseif($thesisTopic->review->review_status == 4) echo '(' . __('A bírálati lap feltöltés véglegesítve. Tanszékvezető ellenőrzésére vár.') . ')';
                                        elseif($thesisTopic->review->review_status == 5) echo '(' . __('A bírálat elutasítva, a dolgozat ismét bírálható.') . ')';
                                    }

                                    if($thesisTopic->review->confidentiality_contract_status == 3){ ?>
                                        <br/>
                                        <strong><?= __('Elutasítás oka') . ': ' ?></strong><?= h($thesisTopic->review->cause_of_rejecting_confidentiality_contract) ?>
                                    <?php }

                                    if(in_array($thesisTopic->review->confidentiality_contract_status, [1, 2]) && $thesisTopic->review->cause_of_rejecting_confidentiality_contract !== null){ ?>
                                        <br/>
                                        <strong><?= __('Előző feltöltés elutasításának oka') . ': ' ?></strong><?= h($thesisTopic->review->cause_of_rejecting_confidentiality_contract) ?>
                                    <?php }
                                }
                            ?>
                        <!-- Mellékletek elutasításának okával kapcsolatos infók -->
                        <?php
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements') &&
                               $thesisTopic->cause_of_rejecting_thesis_supplements !== null){
                                echo ' (' . __('a mellékletek a módosítás utáni állapotban vannak') . ')';
                            }
                        ?>
                        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected')){ ?>
                            <br/>
                            <strong><?= __('Elutasítás oka') . ': ' ?></strong><?= h($thesisTopic->cause_of_rejecting_thesis_supplements) ?>
                        <?php } ?>
                        <?php if(($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement') || $thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements')) &&
                                  $thesisTopic->cause_of_rejecting_thesis_supplements !== null){ ?>
                            <br/>
                            <strong><?= __('A mellékletek elutasításának oka') . ': ' ?></strong><?= h($thesisTopic->cause_of_rejecting_thesis_supplements) ?>
                        <?php } ?>
                            
                        <?php //Adatok felvitele a Neptun rendszerbe
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){
                                if($thesisTopic->accepted_thesis_data_applyed_to_neptun !== true){
                                    echo '(' . __('Az elfogadott dolgozat adatait nincsenek rögzítve a Neptun rendszerbe.') . ')';
                                    echo '<br/>';
                                    echo $this->Html->link(__('Adatok felvitele') . ' ->', '#', ['id' => 'applyAcceptedThesisDataBtn', 'style' => 'display: inline-block']);
                                }else{
                                    echo '(' . __('Az elfogadott dolgozat adatai rögzítve vannak a Neptun rendszerbe.') . ')';
                                    echo '<br/>';
                                    echo $this->Html->link(__('Az adatokat mégsem vitték fel') . ' ->', '#', ['id' => 'applyAcceptedThesisDataBtn', 'style' => 'display: inline-block']);
                                }
                            }
                        ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Belső konzulens') . ': ' ?></strong><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                        </p>
                        <div class="mb-1">
                            <strong><?= __('Téma leírása') . ':' ?></strong><br/>
                            <?= $thesisTopic->description ?>
                        </div>
                        <p class="mb-1">
                            <strong><?= __('Nyelv') . ': ' ?></strong><?= $thesisTopic->has('language') ? h($thesisTopic->language->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Titkos') . ': ' ?></strong><?= $thesisTopic->confidential === true ? __('Igen') : __('Nem') ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Téma típusa') . ': ' ?></strong><?= $thesisTopic->is_thesis === true ? __('Szakdolgozat') : __('Diplomamunka')  ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Kezdési tanév') . ': ' ?></strong><?= $thesisTopic->has('starting_year') ? h($thesisTopic->starting_year->year) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Kezdési félév') . ': ' ?></strong><?= $thesisTopic->starting_semester === null ? '' : ($thesisTopic->starting_semester === true ? __('Tavasz') : __('Ősz') ) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Várható leadási tanév') . ': ' ?></strong><?= $thesisTopic->has('expected_ending_year') ? h($thesisTopic->expected_ending_year->year) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Várható leadási félév') . ': ' ?></strong><?= $thesisTopic->expected_ending_semester === null ? '' : ($thesisTopic->expected_ending_semester === true ? __('Tavasz') : __('Ősz') ) ?>
                        </p>
                        <?php if($thesisTopic->cause_of_no_external_consultant === null){ ?> <!-- Van külső konzulens -->
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens neve') . ': ' ?></strong><?= h($thesisTopic->external_consultant_name) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens munkahelye') . ': ' ?></strong><?= h($thesisTopic->external_consultant_workplace) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens poziciója') . ': ' ?></strong><?= h($thesisTopic->external_consultant_position) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens email címe') . ': ' ?></strong><?= h($thesisTopic->external_consultant_email) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens telefonszáma') . ': ' ?></strong><?= h($thesisTopic->external_consultant_phone_number) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens címe') . ': ' ?></strong><?= h($thesisTopic->external_consultant_address) ?>
                            </p>
                        <?php }else{ ?>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulenstól való eltekintés indoklása') . ': ' ?></strong><?= h($thesisTopic->cause_of_no_external_consultant) ?>
                            </p>
                        <?php } ?>
                        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ ?> <!-- Első diplomakurzus sikertelen, tanszékvezető döntése a folytatásról -->
                            <p class="mb-1">
                                <strong><?= __('A hallgató a diplomakurzus első félévét nem teljesítette') . ': ' ?></strong>
                                <?= $this->Html->link(__('Döntés a folytatásról'), '#', ['class' => 'decideToContinueAfterFailedFirstThesisSubjectBtn', 'id' => 'decideToContinueAfterFailedFirstThesisSubjectBtn']) ?>
                            </p>
                        <?php } ?>
                    </fieldset>
                    <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ ?>
                        <fieldset class="border-1-grey p-3 mb-3">
                            <legend class="w-auto"><?= __('Dolgozat értékelése') ?></legend>
                            <p class="mb-2">
                                <strong><?= __('Belső konzulens értékelése') . ': ' ?></strong><?= $thesisTopic->internal_consultant_grade === null ? __('még nincs értékelve') : h($thesisTopic->internal_consultant_grade) ?>
                            </p>
                            <?php
                                if($thesisTopic->has('review')){
                                    if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant')]) &&
                                           $thesisTopic->review->review_status == 6)
                                        echo $this->Html->link(__('A dolgozat előző verziójának bírálatának megtekintése') . ' ->', ['controller' => 'Reviews', 'action' => 'checkReview', $thesisTopic->id], ['class' => 'mb-2', 'style' => 'display: inline-block']);
                                    elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                                           \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                                           \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])  && in_array($thesisTopic->review->review_status, [4, 5, 6]))
                                        echo $this->Html->link(__('Bírálat megtekintése') . ' ->', ['controller' => 'Reviews', 'action' => 'checkReview', $thesisTopic->id], ['class' => 'mb-2', 'style' => 'display: inline-block']);
                                    
                                    if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                                       \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]) && $thesisTopic->review->has('reviewer')){ ?>
                                    
                                        <p class="mb-1">
                                        <?= $this->Html->link(__('Dolgozat bírálója') . '&nbsp;' . '<i class="fas fa-angle-down fa-lg" id="reviewer_details_arrow_down"></i>' . '<i class="fas fa-angle-up fa-lg d-none" id="reviewer_details_arrow_up"></i>',
                                                          '#', ['id' => 'reviewer_details_link', 'escape' => false]) ?>
                                        </p>
                                        <div id="reviewer_details_container" style="display: none">
                                            <p class="mb-1">
                                                <strong><?= __('Név') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->name) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong><?= __('Email') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->email) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong><?= __('Munkahely') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->workplace) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong><?= __('Pozició') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->position) ?>
                                            </p>
                                            <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') &&
                                                     $thesisTopic->review->has('reviewer') &&
                                                     $thesisTopic->review->reviewer->has('user')){ ?>
                                                <p class="mb-1 mt-4">
                                                    <strong><?= __('Belépési email') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->user->email) ?>
                                                </p>
                                                <p class="mb-1">
                                                    <strong><?= __('Belépési jelszó') . ': ' ?></strong><?= $thesisTopic->review->reviewer->user->has('raw_password') ? h($thesisTopic->review->reviewer->user->raw_password->password) : __('nincs jelszó, újra kell menteni') ?>
                                                </p>
                                            <?php } ?>
                                        </div>
                             <?php }} ?>
                        </fieldset>
                    <?php } ?>
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Hallgató adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $thesisTopic->has('student') ? $this->Html->link($thesisTopic->student->name, ['controller' => 'Students', 'action' => 'edit', $thesisTopic->student->id]) : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Neptun kód') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Szak') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? h($thesisTopic->student->course->name) : '') : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? h($thesisTopic->student->course_type->name) : '') : ''?>
                        </p>
                    </fieldset>
                </div>
                <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ ?>
                    <div class="col-12">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#supplementCollapse" aria-expanded="true" aria-controls="collapseOne">
                                            <?= ($thesisTopic->is_thesis === true ? __('Szakdolgozat') : __('Diplomamunka')) . '&nbsp;' .  __('mellékletek') ?>
                                            <i class="fas fa-angle-down fa-lg" id="supplement_arrow_down"></i>
                                            <i class="fas fa-angle-up fa-lg d-none" id="supplement_arrow_up"></i>
                                        </button>
                                    </h5>
                                </div>

                                <div id="supplementCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                         <ul>
                                            <?php
                                                foreach($thesisTopic->thesis_supplements as $supplement){
                                                    if(!empty($supplement->file)){
                                                        echo '<li>' .
                                                                $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id, 'prefix' => false], ['target' => '_blank']) .
                                                             '</li>';
                                                    }
                                                }
                                            ?>
                                        </ul>
                                        <div>
                                            <?= $this->Html->link(__('Mellékletek letöltése ZIP-ben'), ['controller' => 'ThesisSupplements', 'action' => 'downloadSupplementInZip', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px' ,'target' => '_blank']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            //Téma véglegesítése/leadása (hallgató saját témája)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'))
                                echo $this->Html->link(__('Téma véglegesítése'), '#', ['class' => 'btn btn-success finalize-thesis-topic-btn border-radius-45px mb-2']) . '<br/>';

                            //Téma véglegesítése/leadása (kiírt téma)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'))
                                echo $this->Html->link(__('Téma véglegesítése'), '#', ['class' => 'btn btn-success finalize-thesis-topic-btn border-radius-45px mb-2']) . '<br/>';

                            //Témafoglalás elfogadása (belső konzulensi művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){
                                echo $this->Form->create(null, ['id' => 'acceptBookingForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptBooking']]);
                                echo $this->Form->button(__('Hallgatói foglalás elfogadása'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px mb-2']);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                echo $this->Form->end();

                                echo $this->Form->create(null, ['id' => 'rejectBookingForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptBooking']]);
                                echo $this->Form->button(__('Hallgatói foglalás elutasítása'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px mb-2']);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                echo $this->Form->end();
                                echo '<br/>';
                            }
                            
                            //Téma véglegesítése/leadása (tanszékvezető javaslata utáni téma)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'))
                                echo $this->Html->link(__('Téma véglegesítése'), '#', ['class' => 'btn btn-success finalize-thesis-topic-btn border-radius-45px mb-2']) . '<br/>';
                            
                            //Témafoglalás visszanása (hallgatói művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'))
                                echo $this->Html->link(__('Hallgatói foglalás visszavonása'), '#', ['class' => 'btn btn-danger cancel-booking-btn border-radius-45px mb-2']) . '<br/>';
                            
                            //Belső konzulensi döntése: téma elfogadása (belső konzulensi/tanszékvezetői/témakezelői művelet)
                            if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic')])){
                                //User típus kiválasztása a téma állapota alapján
                                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'))
                                    $value = 1;
                                elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'))
                                    $value = 2;
                                elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'))
                                    $value = 3;
                                
                                echo $this->Form->create(null, ['id' => 'acceptThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptThesisTopic']]);
                                echo $this->Form->button(__('Téma elfogadása'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px mb-2']);
                                echo $this->Form->input('user_type', ['type' => 'hidden', 'value' => $value]);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                echo $this->Form->end();
                                
                                echo $this->Form->create(null, ['id' => 'rejectThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptThesisTopic']]);
                                echo $this->Form->button(__('Téma elutasítása'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px mb-2']);
                                echo $this->Form->input('user_type', ['type' => 'hidden', 'value' => $value]);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                echo $this->Form->end();
                                echo '<br/>';
                            }
                            
                            //Dolgozat értékelése (belső konzulensi művelet)
                            if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]))
                                echo $this->Html->link(__('Dolgozat értékelése'), '#', ['class' => 'btn btn-secondary border-radius-45px mb-2', 'id' => 'setThesisGradeBtn']). '<br/>';
                            
                            //Bíráló titoktartási szerződésének ellenőrzése (tanszékvezetői művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 2)
                                echo $this->Html->link(__('Bíráló titoktartási szerződésének ellenőrzése'), '#', ['class' => 'btn btn-info border-radius-45px mb-2', 'id' => 'checkConfidentialityContractBtn']) . '<br/>';
                            
                            if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]) && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 4)
                                echo $this->Html->link(__('Bíráló titoktartási szerződésének letöltése'), ['controller' => 'Reviews', 'action' => 'getUploadedConfidentialityContract', $thesisTopic->id], ['class' => 'btn btn-info border-radius-45px mb-2', 'target' => '__blank']) . '<br/>';
                            
                            //Újboli bírálatra küldés bíráló változtatási lehetőséggel
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'))
                                echo $this->Html->link(__('Újbóli bírálatra küldés'), '#', ['class' => 'btn btn-info border-radius-45px mb-2', 'id' => 'sendToReviewAgainBtn']) . '<br/>';
                            
                            //Bíráló kijelölése (tanszékvezetői művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'))
                                echo $this->Html->link(__('Bíráló kijelölése'), '#', ['class' => 'btn btn-info border-radius-45px mb-2', 'id' => 'setReviewerForThesisTopicBtn']) . '<br/>';
                            
                            //Bírálatra küldés (tanszékvezetői művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'))
                                echo $this->Html->link(__('Bírálatra küldés'), '#', ['class' => 'btn btn-info border-radius-45px mb-2', 'id' => 'sendToReviewBtn']) . '<br/>';
                            
                            //Bíráló személyének javaslata (belső konzulensi művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'))
                                echo $this->Html->link(__('Bíráló személyének kijelölése'), '#', ['class' => 'btn btn-secondary border-radius-45px mb-2', 'id' => 'setReviewerSuggestionBtn']). '<br/>';
                            
                            //Diplomakurzus első félévének teljesítésének rögzítése (belső konzulensi művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'))
                                echo $this->Html->link(__('Diplomakurzus első félévének teljesítésének rögzítése'), '#', ['class' => 'btn btn-secondary border-radius-45px mb-2', 'id' => 'setFirstThesisSubjectCompletedBtn']). '<br/>';
                            
                            //Mellékletek elfogadása (szakdolgozatkezelői művelet)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'))
                                echo $this->Form->button(__('Mellékletek elfogadása'), ['class' => 'btn btn-primary border-radius-45px mb-2', 'id' => 'acceptThesisSupplementsBtn']) . '<br/>';
                            
                            //Dolgozat mellékleteinek feltöltése (hallgatói művelet)
                            if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]))
                                echo $this->Html->link(__('Dolgozat mellékleteinek feltöltése'), ['controller' => 'ThesisTopics', 'action' => 'uploadThesisSupplements', $thesisTopic->id], ['class' => 'btn btn-info border-radius-45px mb-2']) . '<br/>';
                            
                            //Tanszékvezetői módosítási javaslat adása (tanszékvezetői döntés)
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'))
                                echo $this->Html->link(__('Módosítási javaslat a témához'), '#', ['class' => 'btn btn-warning border-radius-45px mb-2', 'id' => 'proposalForAmendmentBtn']) . '<br/>';
                            
                            //Konzultációk kezelése
                            if(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'),
                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant')]))
                                echo $this->Html->link(__('Konzultációk kezelése'), ['controller' => 'Consultations', 'action' => 'index', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px mb-2']) . '<br/>';
                            
                            echo $this->Html->link(__('Téma módosítása'), ['action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary border-radius-45px mb-2']) . '<br/>';
                            echo $this->Html->link(__('Téma törlése'), '#', ['class' => 'btn btn-danger border-radius-45px delete-btn']);
                            echo $this->Form->postLink('', ['action' => 'delete', $thesisTopic->id], ['style' => 'display: none', 'id' => 'deleteThesisTopic']);
                        ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){ ?>
    <!--Téma módosítási javaslat (belső konzulensi művelet) modal -->
    <div class="modal fade" id="proposalForAmendmentModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="proposal_for_amendment_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){ ?>
    <!-- Diplomakurzus első félévének teljesítésének rögzítése (belső konzulensi művelet) modal -->
    <div class="modal fade" id="setFirstThesisSubjectCompletedModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="set_first_thesis_subject_completed_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ ?>
    <!-- Diplomakurzus első félévének teljesítésének rögzítése (tanszékvezetői művelet) modal -->
    <div class="modal fade" id="decideToContinueAfterFailedFirstThesisSubjectModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="decide_to_continue_after_failed_first_thesis_subject_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements')){ ?>
    <!-- Dolgozat mellékleteinek elfogadása (szakdolgozatkezelői művelet) modal -->
    <div class="modal fade" id="acceptThesisSupplementsModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="accept_thesis_supplements_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant')){ ?>
    <!-- Bíráló személyének belső konzulensi javaslata (belső konzulensi művelet) modal -->
    <div class="modal fade" id="setReviewerSuggestionModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="set_reviewer_suggestion_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment')){ ?>
    <!-- Bíráló kijelölése (tanszékvezetői művelet) modal -->
    <div class="modal fade" id="setReviewerForThesisTopicModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="set_reviewer_for_thesis_topic_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview')){ ?>
    <!-- Bírálatra küldés (tanszékvezetői művelet) modal -->
    <div class="modal fade" id="sendToReviewModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="send_to_review_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ ?>
    <!-- Újboli bírálatra küldés bíráló változtatási lehetőséggel modal -->
    <div class="modal fade" id="sendToReviewAgainModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="send_to_review_again_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 2){ ?>
    <!-- Bíráló titoktartási szerződésének ellenőrzése (tanszékvezetői művelet) modal -->
    <div class="modal fade" id="checkConfidentialityContractModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="check_confidentiality_contract_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ ?>
<!-- Dolgozat értékelése modal -->
<div class="modal fade" id="setThesisGradeModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <div id="set_thesis_grade_container">

                  </div>
              </div>
          </div>
    </div>
</div>
<?php } ?>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){ ?>
    <!-- Elfogadott dolgozat adatainak felvitele modal -->
    <div class="modal fade" id="applyAcceptedThesisDataModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="apply_accepted_thesis_data_container">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')])){ ?>
            //Téma véglegesításe (hallgatói művelet)
            
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('.finalize-thesis-topic-btn').on('click', function(e){
                e.preventDefault();

                <?php
                    $msg = '';
                    if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize')){
                        $msg = __('Hallgató saját témájának véglegesítése/leadása első alkalommal.');
                    }elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){
                        $msg = __('Hallgató kiírt témák közüli választott témájának véglegesítése/leadása.');
                    }elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')){
                        $msg = __('Hallgató tanszékvezető módosítási javaslata utáni témájának véglegesítése/leadása.');
                    }
                ?>

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Téma véglegesítése/leadása.' . ' ' . $msg) . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('hallgató') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'finalizeThesisTopic', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){ ?>
            //Témafoglalás elfogadása (belső konzulensi művelet)
    
            //Confirmation modal elfogadás előtt
            $('#acceptBookingForm .btn-accept').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
                $('#confirmationModal .msg').html('<?= __('Foglalás elfogadása.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('belső konzulens') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#acceptBookingForm').trigger('submit');
                });
            });

            //Confirmation modal elutasítás előtt
            $('#rejectBookingForm .btn-reject').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
                $('#confirmationModal .msg').html('<?= __('Foglalás elutasítása. Elutasítás után a téma újra foglalható lesz a hallgatóknak.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('belső konzulens') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#rejectBookingForm').trigger('submit');
                });
            });
        <?php } ?>
        
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){ ?>
            //Témafoglalás visszanása (hallgatói művelet)
    
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('.cancel-booking-btn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan visszautasítod a foglalást?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Igen') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Foglalás visszautasítása. A kiírt témára ismét jelentkezhetnek hallgatók.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('hallgató') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'cancelBooking', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
        
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic')])){ ?>
            //Téma elfogadása (belső konzulensi/tanszékvezető/témakezelői művelet)
            <?php
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'))
                    $user_type = __('belső konzulens');
                elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'))
                    $user_type = __('tanszékvezető');
                elseif($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'))
                    $user_type = __('témakezelő');
            ?>
            
            //Confirmation modal elfogadás előtt
            $('#acceptThesisTopicForm .btn-accept').on('click', function(e){
                e.preventDefault();
                
                $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
                $('#confirmationModal .msg').html('<?= __('Téma elfogadása.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . $user_type ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#acceptThesisTopicForm').trigger('submit');
                });
            });

            //Confirmation modal elutasítás előtt
            $('#rejectThesisTopicForm .btn-reject').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
                $('#confirmationModal .msg').html('<?= __('Téma elutasítása.') .
                                                       ($thesisTopic->has('offered_topic') && $thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic') ? ' ' . __('Visszautasítás után a kiírt témák listájához visszakerül a téma, amire a hallgatók ismét jelentkezhetnek.') : '') .
                                                       '<br/><br/>' .__('Művelet végrehajtója') . ': ' . $user_type ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#rejectThesisTopicForm').trigger('submit');
                });
            });
            
            <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){ ?>
                //Tartalom lekeérése a "téma módosítási javaslat" (tanszékvezető) modalba
                $.ajax({
                    url: '<?= $this->Url->build(['action' => 'proposalForAmendment', $thesisTopic->id], true) ?>',
                    cache: false
                })
                .done(function(response){
                    $('#proposal_for_amendment_container').html(response.content);
                });

                $('#proposalForAmendmentBtn').on('click', function(e){
                    e.preventDefault();
                    $('#proposalForAmendmentModal').modal('show');
                });
            <?php } ?>
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){?>
            //Tartalom lekeérése a "diplomakurzus első félévének teljesítésének rögzítése" (belső konzulensi művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'setFirstThesisSubjectCompleted', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#set_first_thesis_subject_completed_container').html(response.content);
            });

            $('#setFirstThesisSubjectCompletedBtn').on('click', function(e){
                e.preventDefault();
                $('#setFirstThesisSubjectCompletedModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ ?>
            //Tartalom lekeérése a "diplomakurzus első félévének sikertelenségének eseténi folytatásról" (tanszékvezetői művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'decideToContinueAfterFailedFirstThesisSubject', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#decide_to_continue_after_failed_first_thesis_subject_container').html(response.content);
            });

            $('#decideToContinueAfterFailedFirstThesisSubjectBtn').on('click', function(e){
                e.preventDefault();
                $('#decideToContinueAfterFailedFirstThesisSubjectModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements')){ ?>
            //Tartalom lekeérése a "dolgozat mellékleteinek elfogadása" (szakdolgozatkezelői művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'acceptThesisSupplements', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#accept_thesis_supplements_container').html(response.content);
            });

            $('#acceptThesisSupplementsBtn').on('click', function(e){
                e.preventDefault();
                $('#acceptThesisSupplementsModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant')){?>
            //Tartalom lekeérése a "bíráló személyének javaslata" (belső konzulensi művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'setReviewerSuggestion', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#set_reviewer_suggestion_container').html(response.content);
            });

            $('#setReviewerSuggestionBtn').on('click', function(e){
                e.preventDefault();
                $('#setReviewerSuggestionModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment')){ ?>
            //Tartalom lekeérése a "bíráló személyének kijelölése" (tanszékvezetői művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'setReviewerForThesisTopic', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#set_reviewer_for_thesis_topic_container').html(response.content);
            });

            $('#setReviewerForThesisTopicBtn').on('click', function(e){
                e.preventDefault();
                $('#setReviewerForThesisTopicModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview')){ ?>
            //Tartalom lekeérése a "bírálatra küldés" (tanszékvezetői művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'sendToReview', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#send_to_review_container').html(response.content);
            });

            $('#sendToReviewBtn').on('click', function(e){
                e.preventDefault();
                $('#sendToReviewModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ ?>
            //Tartalom lekeérése a "újboli bírálatra küldés bíráló változtatási lehetőséggel" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'sendToReviewAgain', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                console.log(response);
                $('#send_to_review_again_container').html(response.content);
            });

            $('#sendToReviewAgainBtn').on('click', function(e){
                e.preventDefault();
                $('#sendToReviewAgainModal').modal('show');
            });
        <?php } ?>
         
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 2){ ?>
            //Tartalom lekeérése a "feltöltött titoktartási szerződés ellenőrzése" (tanszékvezetői művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'checkConfidentialityContract', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#check_confidentiality_contract_container').html(response.content);
            });

            $('#checkConfidentialityContractBtn').on('click', function(e){
                e.preventDefault();
                $('#checkConfidentialityContractModal').modal('show');
            });
        <?php } ?>
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ ?>
            //Tartalom lekeérése a "dolgozat értékelése" (belső konzulensi művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'setThesisGrade', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#set_thesis_grade_container').html(response.content);
            });

            $('#setThesisGradeBtn').on('click', function(e){
                e.preventDefault();
                $('#setThesisGradeModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){ ?>
            //Tartalom lekeérése a "adatok felvitele a Neptun rendszerbe" (szakdolgozatkezelői művelet) modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'applyAcceptedThesisData', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#apply_accepted_thesis_data_container').html(response.content);
            });

            $('#applyAcceptedThesisDataBtn').on('click', function(e){
                e.preventDefault();
                $('#applyAcceptedThesisDataModal').modal('show');
            });
        <?php } ?>
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ ?>
            /**
             * Accordion megjelenítésekor nyíl cseréje
             */
            $('#supplementCollapse').on('show.bs.collapse', function(){
                $('#supplement_arrow_up').removeClass('d-none');
                $('#supplement_arrow_down').addClass('d-none');
            });

            /**
             * Accordion eltüntetésekor nyíl cseréje
             */
            $('#supplementCollapse').on('hide.bs.collapse', function(){
                $('#supplement_arrow_down').removeClass('d-none');
                $('#supplement_arrow_up').addClass('d-none');
            });
        <?php } ?>
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]) && $thesisTopic->has('review') && $thesisTopic->review->has('reviewer')){ ?>
            $('#reviewer_details_link').on('click', function(e){
                e.preventDefault();
                if($('#reviewer_details_container').css('display') == 'none'){
                    $('#reviewer_details_container').slideDown(500);
                    $('#reviewer_details_arrow_down').addClass('d-none');
                    $('#reviewer_details_arrow_up').removeClass('d-none');
                }else{
                    $('#reviewer_details_container').slideUp(500);
                    $('#reviewer_details_arrow_down').removeClass('d-none');
                    $('#reviewer_details_arrow_up').addClass('d-none');
                }
            });
        <?php } ?>
            
        //Törléskor confirmation modal a megerősítésre
        $('.admin-thesisTopics-details .delete-btn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Téma végleges törlése. A téma törlés után nem lesz visszaállítható.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteThesisTopic').trigger('click');
            });
        });
    });
</script>