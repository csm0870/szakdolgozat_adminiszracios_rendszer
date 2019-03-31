<div class="container headOfDepartment-thesisTopics-details">
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
                        <p class="mb-4">
                            <strong><?= __('Állapot') . ': ' ?></strong>
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
                                                                             \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])){ ?>
                        <fieldset class="border-1-grey p-3 mb-3">
                            <legend class="w-auto"><?= __('Dolgozat értékelése') ?></legend>
                            <p class="mb-2">
                                <strong><?= __('Belső konzulens értékelése') . ': ' ?></strong><?= $thesisTopic->internal_consultant_grade === null ? __('még nincs értékelve') : h($thesisTopic->internal_consultant_grade) ?>
                            </p>
                            <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')]) && $thesisTopic->has('review') && $thesisTopic->review->has('reviewer')){ ?>
                                <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])  && in_array($thesisTopic->review->review_status, [4, 5, 6]))
                                        echo $this->Html->link(__('Bírálat megtekintése') . ' ->', ['controller' => 'Reviews', 'action' => 'checkReview', $thesisTopic->id], ['class' => 'mb-2', 'style' => 'display: inline-block']); ?>
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
                                    <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->has('review') && $thesisTopic->review->has('reviewer') && $thesisTopic->review->reviewer->has('user')){ ?>
                                        <p class="mb-1 mt-4">
                                            <strong><?= __('Belépési email') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->user->email) ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><?= __('Belépési jelszó') . ': ' ?></strong><?= $thesisTopic->review->reviewer->user->has('raw_password') ? h($thesisTopic->review->reviewer->user->raw_password->password) : __('nincs jelszó, újra kell menteni') ?>
                                        </p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </fieldset>
                    <?php } ?>
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Hallgató adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?>
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
                    <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ ?> <!-- Első diplomakurzus sikertelen, tanszékvezető döntése a folytatásról -->
                        <p>
                            <strong><?= __('Diplomakurzus első félévét nem teljesítette') . ': ' ?></strong>
                            <?= $this->Html->link(__('Döntés a folytatásról'), '#', ['class' => 'decideToContinueAfterFailedFirstThesisSubjectBtn']) ?>
                        </p>
                    <?php } ?>
                </div>
                <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                         \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])){ ?>
                    <div class="col-12">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#supplementCollapse" aria-expanded="true" aria-controls="collapseOne">
                                            <?= ($thesisTopic->is_thesis === null ? __('Szakdolgozat') : ($thesisTopic->is_thesis === true) ? __('Szakdolgozat') : __('Diplomamunka')) . '&nbsp;' .  __('mellékletek') ?>
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
                            //Tanaszékvezetői döntésre vár
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){
                                echo $this->Form->create(null, ['id' => 'acceptThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                echo $this->Form->button(__('Téma elfogadás'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px mb-2']);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                echo $this->Form->end();
                                echo "&nbsp;&nbsp;";
                                echo $this->Form->create(null, ['id' => 'rejectThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                echo $this->Form->button(__('Téma elutasítás'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px mb-2']);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                echo $this->Form->end();
                                echo '<br/>';
                                
                                echo $this->Html->link(__('Módosítási javaslat a témához'), '#', ['class' => 'btn btn-warning proposalForAmendmentBtn border-radius-45px mb-2']) . '<br/>';
                            }
                            
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 2)
                                echo $this->Html->link(__('Bíráló titoktartási szerződésének ellenőrzése'), '#', ['class' => 'btn btn-info checkConfidentialityContractBtn border-radius-45px mb-2']) . '<br/>';
                            
                            if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                               \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')]) && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 4)
                                echo $this->Html->link(__('Bíráló titoktartási szerződésének letöltése'), ['controller' => 'Reviews', 'action' => 'getUploadedConfidentialityContract', $thesisTopic->id], ['class' => 'btn btn-info border-radius-45px mb-2', 'target' => '__blank']) . '<br/>';
                            
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'))
                                echo $this->Html->link(__('Bíráló kijelölése'), '#', ['class' => 'btn btn-info setReviewerForThesisTopicBtn border-radius-45px mb-2']) . '<br/>';
                            
                            if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'))
                                echo $this->Html->link(__('Bírálatra küldés'), '#', ['class' => 'btn btn-info sendToReviewBtn border-radius-45px mb-2']) . '<br/>';
                            
                            echo $this->Html->link(__('Témaengedélyező PDF letöltése'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px mb-2', 'target' => '_blank']);
                        ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){ ?>
    <!--Téma módosítási javaslat modal -->
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
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ ?>
    <!-- Diplomakurzus első félévének teljesítésének rögzítése modal -->
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
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment')){ ?>
    <!-- Bíráló kijelölése modal -->
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
    <!-- Bírálatra küldés modal -->
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
<?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 2){ ?>
    <!-- Bíráló titoktartási szerződésének ellenőrzése modal -->
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
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ ?>
            //Tartalom lekeérése a "diplomakurzus első félévének teljesítésének rögzítése" modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'decideToContinueAfterFailedFirstThesisSubject', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#decide_to_continue_after_failed_first_thesis_subject_container').html(response.content);
            });

            $('.headOfDepartment-thesisTopics-details .decideToContinueAfterFailedFirstThesisSubjectBtn').on('click', function(e){
                e.preventDefault();
                $('#decideToContinueAfterFailedFirstThesisSubjectModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment')){ ?>
            //Tartalom lekeérése a "bíráló személyének kijelölése" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'setReviewerForThesisTopic', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#set_reviewer_for_thesis_topic_container').html(response.content);
            });

            $('.headOfDepartment-thesisTopics-details .setReviewerForThesisTopicBtn').on('click', function(e){
                e.preventDefault();
                $('#setReviewerForThesisTopicModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview')){ ?>
            //Tartalom lekeérése a "bírálatra küldés" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'sendToReview', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function(response){
                $('#send_to_review_container').html(response.content);
            });

            $('.headOfDepartment-thesisTopics-details .sendToReviewBtn').on('click', function(e){
                e.preventDefault();
                $('#sendToReviewModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 2){ ?>
            //Tartalom lekeérése a "bírálatra küldés" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'checkConfidentialityContract', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#check_confidentiality_contract_container').html(response.content);
            });

            $('.headOfDepartment-thesisTopics-details .checkConfidentialityContractBtn').on('click', function(e){
                e.preventDefault();
                $('#checkConfidentialityContractModal').modal('show');
            });
        <?php } ?>
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])){ ?>
            /**
             * Accordion megjelenítésekor nyíl cseréje
             */
            $('#supplementCollapse').on('show.bs.collapse', function () {
                $('#supplement_arrow_up').removeClass('d-none');
                $('#supplement_arrow_down').addClass('d-none');
            });

            /**
             * Accordion eltüntetésekor nyíl cseréje
             */
            $('#supplementCollapse').on('hide.bs.collapse', function () {
                $('#supplement_arrow_down').removeClass('d-none');
                $('#supplement_arrow_up').addClass('d-none');
            });
        <?php } ?>
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')]) && $thesisTopic->has('review') && $thesisTopic->review->has('reviewer')){ ?>
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
        
        <?php if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){ ?>
            //Confirmation modal elfogadás előtt
            $('.headOfDepartment-thesisTopics-details .btn-accept').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
                $('#confirmationModal .msg').text('<?= __('Téma elfogadása.') ?>');
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
            $('.headOfDepartment-thesisTopics-details .btn-reject').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
                $('#confirmationModal .msg').text('<?= __('Téma elutasítása.') ?>');
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
            
            //Tartalom lekeérése a "téma módosítási javaslat" modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'proposalForAmendment', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#proposal_for_amendment_container').html(response.content);
            });

            $('.headOfDepartment-thesisTopics-details .proposalForAmendmentBtn').on('click', function(e){
                e.preventDefault();
                $('#proposalForAmendmentModal').modal('show');
            });
        <?php } ?>
    });
</script>