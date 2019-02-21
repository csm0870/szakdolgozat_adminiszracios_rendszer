<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'FinalExamSubjects', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Záróvizsga-tárgy részletek') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <p class="mb-4">
                        <strong><?= __('Záróvizsga-tárgy kérelem állapota') . ': ' ?></strong>
                        <?php
                            if($student->final_exam_subjects_status === 2) echo __('Véglegesítve. Ellenőrzésre vár.');
                            elseif($student->final_exam_subjects_status === 3) echo __('Elfogadva.');
                            elseif($student->final_exam_subjects_status === 4) echo __('Elutasítva.');
                        ?>
                    </p>
                    <p>
                        <strong><?= __('Hallgató neve') . ': ' ?></strong><?= h($student->name) ?>
                    </p>
                    <p>
                        <strong><?= __('Neptun kód') . ': ' ?></strong><?= $student->neptun ?>
                    </p>
                    <p>
                        <strong><?= __('Szak') . ': ' ?></strong><?= $student->has('course') ? h($student->course->name) : ''?>
                    </p>
                    <p>
                        <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $student->has('course_level') ? h($student->course_level->name) : ''?>
                    </p>
                    <p>
                        <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $student->has('course_type') ? h($student->course_type->name) : ''?>
                    </p>
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
                                            <?= $this->Form->control("final_exam_subjects[{$i}][name]", ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'value' => $subject->name, 'data-id' => $i, 'readonly' => true]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][teachers]", ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'value' => $subject->teachers, 'data-id' => $i, 'readonly' => true]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][year_id]", ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'value' => $subject->year_id, 'data-id' => $i, 'disabled' => true]) ?>
                                            <?= $this->Form->control("final_exam_subjects[{$i}][semester]", ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->semester, 'data-id' => $i, 'disabled' => true]) ?>
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
                </div>
                </div>
                <?php if($student->final_exam_subjects_status == 2){ ?>
                    <div class="col-12 col-sm-6 text-center">
                        <?php
                            echo $this->Form->create(null, ['id' => 'acceptFinalExamSubjects', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                            echo $this->Form->button(__('Tárgyak elfogadása'), ['type' => 'submit', 'class' => 'btn btn-success acceptBtn border-radius-45px']);
                            echo $this->Form->input('student_id', ['type' => 'hidden', 'value' => $student->id]);
                            echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                            echo $this->Form->end();
                        ?>
                    </div>
                    <div class=" col-12 col-sm-6 text-center">
                        <?php
                            echo $this->Form->create(null, ['id' => 'rejectFinalExamSubjects', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                            echo $this->Form->button(__('Tárgyak elutasítása'), ['type' => 'submit', 'class' => 'btn btn-danger rejectBtn border-radius-45px']);
                            echo $this->Form->input('student_id', ['type' => 'hidden', 'value' => $student->id]);
                            echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                            echo $this->Form->end();
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#final_exam_subjects_index_menu_item').addClass('active');
        
        //Confirmation modal elfogadás előtt
        $('.acceptBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
            $('#confirmationModal .msg').text('<?= __('Záróvizsga-tárgyak elfogadása.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#acceptFinalExamSubjects').trigger('submit');
            });
        });
        
        //Confirmation modal elutasítás előtt
        $('.rejectBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
            $('#confirmationModal .msg').text('<?= __('Záróvizsga-tárgyak elutasítása.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#rejectFinalExamSubjects').trigger('submit');
            });
        });
    });
</script>