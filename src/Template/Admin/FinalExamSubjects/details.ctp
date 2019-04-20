<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'Students', 'action' => 'details', $student->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Záróvizsga-tárgy részletek') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <p class="mb-4">
                        <strong><?= __('Záróvizsga-tárgy kérelem állapota') . ': ' ?></strong>
                        <?php
                            if($student->final_exam_subjects_status === 2) echo __('Hallgató véglegesítette. Ellenőrzésre vár.');
                            elseif($student->final_exam_subjects_status === 3) echo __('Elfogadva.');
                        ?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Hallgató neve') . ': ' ?></strong><?= h($student->name) ?>
                    </p>
                    <p class="mb-0">
                        <strong><?= __('Neptun kód') . ': ' ?></strong><?= $student->neptun ?>
                    </p>
                </div>
                <div class="col-12 mt-4">
                    <?php
                        $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                                'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                        
                        echo $this->Form->create(null, ['id' => 'saveFinalExamSubjectsForm']);
                    ?>
                    <div id="accordion" class="mb-4">
                        <?php
                            $i = 1;
                            foreach($student->final_exam_subjects as $subject){  if($i >= 4) break; ?>
                                <div class="card mb-1">
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
                                            <?= $this->Form->control("final_exam_subjects[{$i}][name]", ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true, 'value' => $subject->name, 'data-id' => $i]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][teachers]", ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true, 'value' => $subject->teachers, 'data-id' => $i]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][year_id]", ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->year_id, 'data-id' => $i]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][semester]", ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->semester, 'data-id' => $i]) ?>
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
                                <div class="card mb-1">
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
                    <div class="text-center">
                        <?php
                            echo $this->Form->input('student_id', ['type' => 'hidden', 'value' => $student->id]);
                            echo $this->Form->button(__('Tárgyak mentése'), ['type' => 'submit', 'class' => 'btn btn-success submitBtn border-radius-45px']);
                            
                            if($student->final_exam_subjects_status == 2) //Elfogadásra várnak
                                echo '<br/>' . $this->Html->link(__('Tárgyak elfogadása'), '#', ['class' => 'btn btn-primary finalizeFinalExamSubjectsBtn border-radius-45px mt-2']);
                            
                            if($student->final_exam_subjects_status == 3) //El vannak fogadva
                                echo '<br/>' . $this->Html->link(__('Záróvizsga-tárgy kérelem letöltése'), ['controller' => 'FinalExamSubjects', 'action' => 'exportDoc', $student->id, 'prefix' => false], ['target' => '_blank', 'class' => 'btn btn-primary border-radius-45px mt-2']);
                        ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#students_index_menu_item').addClass('active');
        
        /**
         * Form mezőinek ellenőrzése
         * 
         * @return {undefined}
         */
        function validateForm(){
            $('#saveFinalExamSubjectsForm input').each(function(){
                var id = $(this).data('id');
                if(this.checkValidity() === false){
                    $('#finalExamSubjectCollapse_' + id).collapse('show');
                    return false;
                }
            });
        }
        
        $('#saveFinalExamSubjectsForm .submitBtn').on('click', function(e){
            e.preventDefault();

            validateForm();

            //Formvalidáció manuális meghívása
            if($('#saveFinalExamSubjectsForm')[0].reportValidity() === false) return;

            $('#confirmationModal .header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .msg').text('<?= __('Záróvizsga-tárgyak mentése. Az adatok mentés után is módosíthatóak.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#saveFinalExamSubjectsForm').trigger('submit');
            });
        });

        <?php if($student->final_exam_subjects_status == 2){ //Ha elfogadásra vár ?>
            //Confirmation modal elfogadás előtt
            $('#saveFinalExamSubjectsForm .finalizeFinalExamSubjectsBtn').on('click', function(e){
                e.preventDefault();

                validateForm();

                //Formvalidáció manuális meghívása
                if($('#saveFinalExamSubjectsForm')[0].reportValidity() === false) return;

                $('#confirmationModal .header').text('<?= __('Biztosan véglegesítése?') ?>');
                $('#confirmationModal .msg').text('<?= __('Záróvizsga-tárgyak elfogadása. Az adatok mentés után is módosíthatóak.') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    //Jelezzük egy inputtal, hogy véglegesítésről van szó
                    $('#saveFinalExamSubjectsForm').append('<input type="hidden" name="is_finalize" value="1"/>');
                    $('#saveFinalExamSubjectsForm').trigger('submit');
                });
            });
        <?php } ?>

        <?php if(isset($final_exam_subject_error_number)){ ?> // Ha van hiba, akkor azt a collapse-ot kinyitjuk, ahol a hiba van
            $('#final_exam_subject_arrow_up_<?= $final_exam_subject_error_number ?>').removeClass('d-none');
            $('#final_exam_subject_arrow_down_<?= $final_exam_subject_error_number ?>').addClass('d-none');
            $('#finalExamSubjectCollapse_<?= $final_exam_subject_error_number ?>').collapse('show');
        <?php } ?>
    });
</script>