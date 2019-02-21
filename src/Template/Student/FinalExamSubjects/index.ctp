<div class="container">
    <div class="row">
        <?php if($ok === true){ ?>
            <div class="col-12 text-center page-title">
                <h4><?= __('Záróvizsga-tárgyak kezelése') ?></h4>
            </div>
            <?= $this->Flash->render() ?>
            <div class="col-12">
                <?php  
                    $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                            'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                    echo in_array($student->final_exam_subjects_status, [2, 3]) ? '' : $this->Form->create(null, ['id' => 'saveFinalExamSubjectsForm', 'type' => 'file', 'class' => 'row']);
                ?>
                <div class="col-12 mb-4">
                    <?php
                        echo '<strong>' . __('Állapot') . '</strong>:&nbsp;';
                        
                        if($student->final_exam_subjects_status === null) echo __('Még nincsenek kiválasztva.');
                        elseif($student->final_exam_subjects_status === 1) echo __('Kiválasztva. Véglegesíthető.');
                        elseif($student->final_exam_subjects_status === 2) echo __('Véglegesítve. Ellenőrzésre vár.');
                        elseif($student->final_exam_subjects_status === 3) echo __('Elfogadva.');
                        elseif($student->final_exam_subjects_status === 4) echo __('Elutasítva.')
                    ?>
                </div>
                <div class="col-12 mb-4">
                    <?= $this->Form->control('internal_consultant_id', ['options' => $internalConsultants, 'label' => ['text' => __('Belső konzulens') . ':'], 'class' => 'form-control', 'required' => true, 'value' => $student->final_exam_subjects_internal_consultant_id,  'disabled' => in_array($student->final_exam_subjects_status, [2, 3])]) ?>
                </div>
                <div class="col-12">
                    <div id="accordion">
                        <?php 
                            $i = 1;
                            foreach($student->final_exam_subjects as $subject){  if($i >= 4) break; ?>
                                <div class="card mb-4">
                                    <div class="card-header" id="heading_<?= $i ?>">
                                        <h5 class="mb-0">
                                            <button type="button" role="button" class="btn btn-link" data-toggle="collapse" data-target="#finalExamSubjectCollapse_<?= $i ?>" aria-expanded="true" aria-controls="collapse_<?= $i ?>">
                                                <?php
                                                    if($i == 1) echo __('Első') . ' ' . __('záróvizsga-tárgy');
                                                    elseif($i == 2) echo __('Második') . ' ' . __('záróvizsga tárgy');
                                                    elseif($i == 3) echo __('Harmadik') . ' ' . __('záróvizsga tárgy');
                                                ?>
                                                <i class="fas fa-angle-down fa-lg" id="final_exam_subject_arrow_down_<?= $i ?>"></i>
                                                <i class="fas fa-angle-up fa-lg d-none" id="final_exam_subject_arrow_up_<?= $i ?>"></i>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="finalExamSubjectCollapse_<?= $i ?>" class="collapse" aria-labelledby="heading_<?= $i ?>" data-parent="#accordion">
                                        <div class="card-body">
                                            <?= $this->Form->control("final_exam_subjects[{$i}][id]", ['type' => 'hidden', 'value' => $subject->id]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][name]", ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true, 'value' => $subject->name, 'data-id' => $i, 'readonly' => in_array($student->final_exam_subjects_status, [2, 3])]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][teachers]", ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true, 'value' => $subject->teachers, 'data-id' => $i, 'readonly' => in_array($student->final_exam_subjects_status, [2, 3])]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][year_id]", ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->year_id, 'data-id' => $i, 'disabled' => in_array($student->final_exam_subjects_status, [2, 3])]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][semester]", ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->semester, 'data-id' => $i, 'disabled' => in_array($student->final_exam_subjects_status, [2, 3])]) ?>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(function(){
                                        /**
                                         * Accordion megjelenítésekor nyíl cseréje
                                         */
                                        $('#finalExamSubjectCollapse_<?= $i ?>').on('show.bs.collapse', function () {
                                            $('#final_exam_subject_arrow_up_<?= $i ?>').removeClass('d-none');
                                            $('#final_exam_subject_arrow_down_<?= $i ?>').addClass('d-none');
                                        });

                                        /**
                                         * Accordion eltüntetésekor nyíl cseréje
                                         */
                                        $('#finalExamSubjectCollapse_<?= $i ?>').on('hide.bs.collapse', function () {
                                            $('#final_exam_subject_arrow_down_<?= $i ?>').removeClass('d-none');
                                            $('#final_exam_subject_arrow_up_<?= $i ?>').addClass('d-none');
                                        });
                                    });
                                </script>
                        <?php $i++; } ?>
                        <?php 
                            for($i = $i; $i <= 3; $i++){ ?>
                                <div class="card mb-4">
                                    <div class="card-header" id="heading_<?= $i ?>">
                                        <h5 class="mb-0">
                                            <button type="button" role="button" class="btn btn-link" data-toggle="collapse" data-target="#finalExamSubjectCollapse_<?= $i ?>" aria-expanded="true" aria-controls="collapse_<?= $i ?>">
                                                <?php
                                                    if($i == 1) echo __('Első') . ' ' . __('záróvizsga-tárgy');
                                                    elseif($i == 2) echo __('Második') . ' ' . __('záróvizsga tárgy');
                                                    elseif($i == 3) echo __('Harmadik') . ' ' . __('záróvizsga tárgy');
                                                ?>
                                                <i class="fas fa-angle-down fa-lg" id="final_exam_subject_arrow_down_<?= $i ?>"></i>
                                                <i class="fas fa-angle-up fa-lg d-none" id="final_exam_subject_arrow_up_<?= $i ?>"></i>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="finalExamSubjectCollapse_<?= $i ?>" class="collapse" aria-labelledby="heading_<?= $i ?>" data-parent="#accordion">
                                        <div class="card-body">
                                            <?= $this->Form->control("final_exam_subjects[{$i}][name]", ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true, 'data-id' => $i]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][teachers]", ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true, 'data-id' => $i]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][year_id]", ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'data-id' => $i]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][semester]", ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'data-id' => $i]) ?>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(function(){
                                        /**
                                         * Accordion megjelenítésekor nyíl cseréje
                                         */
                                        $('#finalExamSubjectCollapse_<?= $i ?>').on('show.bs.collapse', function () {
                                            $('#final_exam_subject_arrow_up_<?= $i ?>').removeClass('d-none');
                                            $('#final_exam_subject_arrow_down_<?= $i ?>').addClass('d-none');
                                        });

                                        /**
                                         * Accordion eltüntetésekor nyíl cseréje
                                         */
                                        $('#finalExamSubjectCollapse_<?= $i ?>').on('hide.bs.collapse', function () {
                                            $('#final_exam_subject_arrow_down_<?= $i ?>').removeClass('d-none');
                                            $('#final_exam_subject_arrow_up_<?= $i ?>').addClass('d-none');
                                        });
                                    });
                                </script>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-12 col-sm-6 text-center">
                    <?= in_array($student->final_exam_subjects_status, [2, 3]) ? '' : $this->Form->button(__('Mentés'), ['type' => 'submit', 'class' => 'btn btn-primary border-radius-45px submitBtn']) ?>
                </div>
                <div class="col-12 col-sm-6  text-center">
                    <?= in_array($student->final_exam_subjects_status, [1, 4]) ? $this->Form->button(__('Tárgyak véglegesítése'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success border-radius-45px finalizeBtn']) : '' ?>
                </div>
                <div class="col-12 text-center">
                    <?= $student->final_exam_subjects_status == 3 ? $this->Html->link(__('Záróvizsga-tárgy kérelem letöltése'), ['controller' => 'FinalExamSubjects', 'action' => 'exportDoc', $student->id, 'prefix' => false], ['target' => '_blank', 'class' => 'btn btn-primary border-radius-45px']) : '' ?>
                </div>
                <?= in_array($student->final_exam_subjects_status, [2, 3]) ? '' : $this->Form->end() ?>
            </div>
        <?php }else{ ?>
            <div class="col-12">
                <h4 class="text-center" style="color: red">
                    <?= $error_msg ?>
                </h4>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    $(function(){
        $('#final_exam_subjects_index_menu_item').addClass('active');
        <?php if($ok === true){ ?>
            function validateForm(){
                $('#saveFinalExamSubjectsForm input').each(function(){
                    var id = $(this).data('id');
                    if(this.checkValidity() === false){
                        $('#finalExamSubjectCollapse_' + id).collapse('show');
                        return;
                    }
                });
            }

            <?php if(isset($final_exam_subject_error_number)){ ?> // Ha van hiba, akkor azt a collapse-ot kinyitjuk, ahol a hiba van
                $('#final_exam_subject_arrow_up_<?= $final_exam_subject_error_number ?>').removeClass('d-none');
                $('#final_exam_subject_arrow_down_<?= $final_exam_subject_error_number ?>').addClass('d-none');
                $('#finalExamSubjectCollapse_<?= $final_exam_subject_error_number ?>').collapse('show');
            <?php } ?>

            <?php if(!in_array($student->final_exam_subjects_status, [2, 3])){ ?>
                /**
                * Confirmation modal megnyitása submit előtt
                */
                $('#saveFinalExamSubjectsForm .submitBtn').on('click', function(e){
                    e.preventDefault();

                    validateForm();
                    //Formvalidáció manuális meghívása
                    if($('#saveFinalExamSubjectsForm')[0].reportValidity() === false) return;

                    $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                    $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                    //Save gomb eventjeinek resetelése cserével
                    $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                    $('#confirmationModal .msg').html('<?= __('Záróvizsga-tárgyak mentése.') ?>');

                    $('#confirmationModal').modal('show');

                    var thesis_topic_id = $(this).data('id');

                    $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                        e.preventDefault();
                        $('#confirmationModal').modal('hide');
                        $('#saveFinalExamSubjectsForm').trigger('submit');
                    });
                });
            <?php } ?>

            <?php if(in_array($student->final_exam_subjects_status, [1, 4])){ ?>
                /**
                * Confirmation modal megnyitása submit előtt
                */
                $('.finalizeBtn').on('click', function(e){
                    e.preventDefault();

                    $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                    $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                    //Save gomb eventjeinek resetelése cserével
                    $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                    $('#confirmationModal .msg').text('<?= __('Záróvizsga-tárgyak véglegesítése.') ?>');

                    $('#confirmationModal').modal('show');

                    var thesis_topic_id = $(this).data('id');

                    $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                        e.preventDefault();
                        $('#confirmationModal').modal('hide');
                        location.href = '<?= $this->Url->build(['controller' => 'FinalExamSubjects', 'action' => 'finalize'], true) ?>' + '/' + thesis_topic_id;
                    });
                });
            <?php } ?>
        <?php } ?>
    });
</script>


