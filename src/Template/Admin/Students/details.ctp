<div class="container admin-students-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'Students', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Hallgató részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Hallgató adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Név') . ': ' ?></strong><?= h($student->name) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Neptun kód') . ': ' ?></strong><?= h($student->neptun)?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Szak') . ': ' ?></strong><?= $student->has('course') ? h($student->course->name) : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $student->has('course_level') ? h($student->course_level->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $student->has('course_type') ? h($student->course_type->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Cím') . ': ' ?></strong><?= h($student->address) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Email cím') . ': ' ?></strong><?= h($student->email) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Telefonszám') . ': ' ?></strong><?= h($student->phone_number) ?>
                        </p>
                        <?php if($student->course_id == 1){ //Ha mérnökinformatikus ?>
                            <p class="mb-1">
                                <strong><?= __('Záróvizsga-tárgyak állapota') . ': ' ?></strong>
                                <?php // null, 0 - még nincsenek tárgyak, 1 - már ki vannak választva a tárgyak, de még módosíthatja a hallgató, 2 - a hallgató véglegesítette a tárgyakat, 3 - belső konzulens elfogadta a tárgyakat
                                    if($student->final_exam_subjects_status == null)
                                        echo __('Még nincsenek kiválasztva tárgyak');
                                    elseif($student->final_exam_subjects_status == 1)
                                        echo __('A hallgató már megtette a javaslatát, de még nem véglegeítette');
                                    elseif($student->final_exam_subjects_status == 2)
                                        echo __('A hallgatói javaslat végelgesítve, a belső konzulens elenőrzésére vár');
                                    elseif($student->final_exam_subjects_status == 3)
                                        echo __('A tárgyak kiválasztva és véglegesítve vannak');
                                    
                                ?>
                            </p>
                        <?php } ?>
                        <p class="mb-0">
                            <strong><?= __('Záróvizsgázott') . ': ' ?></strong><?= $student->passed_final_exam === true ? __('Igen') : __('Nem') ?>
                        </p>
                    </fieldset>
                </div>
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Hallgató diplomatémái') ?></legend>
                        <?php if(count($student->thesis_topics) > 0){ ?>
                            <div id="accordion2">
                                <?php 
                                    $i = 1;
                                    foreach($student->thesis_topics as $thesisTopic){ ?>
                                        <div class="card mb-1">
                                            <div class="card-header" id="heading2_<?= $i ?>">
                                                <h5 class="mb-0">
                                                    <button type="button" role="button" class="btn btn-link" data-toggle="collapse" data-target="#thesisTopicCollapse_<?= $i ?>" aria-expanded="true" aria-controls="collapse_<?= $i ?>">
                                                        <?= $i . '. ' . __('téma') ?>
                                                        <i class="fas fa-angle-down fa-lg" id="thesis_topic_arrow_down_<?= $i ?>"></i>
                                                        <i class="fas fa-angle-up fa-lg d-none" id="thesis_topic_arrow_up_<?= $i ?>"></i>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="thesisTopicCollapse_<?= $i ?>" class="collapse" aria-labelledby="heading2_<?= $i ?>" data-parent="#accordion2">
                                                <div class="card-body">
                                                    <p class="mb-0">
                                                        <strong><?= __('Állapot') . ': ' ?></strong><?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name): '' ?>
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong><?= __('Cím') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                                                    </p>
                                                    <p class="mb-0">
                                                        <?= $this->Html->link(__('Részletek') . ' ->', ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(function(){
                                                /**
                                                 * Accordion megjelenítésekor nyíl cseréje
                                                 */
                                                $('#thesisTopicCollapse_<?= $i ?>').on('show.bs.collapse', function () {
                                                    $('#thesis_topic_arrow_up_<?= $i ?>').removeClass('d-none');
                                                    $('#thesis_topic_arrow_down_<?= $i ?>').addClass('d-none');
                                                });

                                                /**
                                                 * Accordion eltüntetésekor nyíl cseréje
                                                 */
                                                $('#thesisTopicCollapse_<?= $i ?>').on('hide.bs.collapse', function () {
                                                    $('#thesis_topic_arrow_down_<?= $i ?>').removeClass('d-none');
                                                    $('#thesis_topic_arrow_up_<?= $i ?>').addClass('d-none');
                                                });
                                            });
                                        </script>
                                <?php $i++; } ?>
                            </div>
                        <?php }else{ ?>
                            <p class="mb-0">
                                <strong><?= __('Még nincs diplomatémája') ?></strong>
                            </p>
                        <?php } ?>
                    </fieldset>
                </div>
                <?php if($student->course_id == 1){ //Ha mérnökinformatikus ?>
                    <div class="col-12">
                        <fieldset class="border-1-grey p-3 mb-3">
                            <legend class="w-auto"><?= __('Záróvizsga-tárgyak') ?></legend>
                            <?php if($student->final_exam_subjects_status == 2 || $student->final_exam_subjects_status == 3){ ?>
                                <div id="accordion">
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
                                </div>
                            <?php }else{ ?>
                                <p class="mb-0">
                                    <strong><?= __('Még nincs záróvizsga-tárgyai') ?></strong>
                                </p>
                            <?php } ?>
                        </fieldset>
                    </div>
                <?php } ?>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            echo $this->Html->link($student->passed_final_exam === true ? __('A hallgató még nem teljesítette a záróvizsgát') : __('A hallgató teljesítette a záróvizsgát'), '#', ['class' => 'btn btn-secondary border-radius-45px setPassedFinalExamBtn mb-2']) . '<br/>';
                            
                            echo $this->Html->link(__('Hallgató adatainak módosítása'), ['action' => 'edit', $student->id], ['class' => 'btn btn-secondary border-radius-45px mb-2']) . '<br/>';
                            
                            //Ha mérnökinformatikus és már vannak válaszhatott záróvizsga-tárgyai vagy javaslata tárgyai
                            if($student->course_id == 1 && ($student->final_exam_subjects_status == 2 || $student->final_exam_subjects_status == 3))
                                echo $this->Html->link(__('Záróvizsga-tárgyak kezelése'), ['controller' => 'FinalExamSubjects', 'action' => 'details', $student->id], ['class' => 'btn btn-secondary border-radius-45px']);
                        ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#students_index_menu_item').addClass('active');
        
        //Confirmation modal megnyitása "hallgató átment a ZV-n" gombra kattintáskor
        $('.admin-students-details .setPassedFinalExamBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .header').text('<?= __('A hallgató teljesítette a záróvizsgát?') ?>');
            $('#confirmationModal .msg').text('<?= $student->passed_final_exam === true ?  __('A hallgató nem teljesítette a záróvizsgát. Rögzítés után visszavonható.') : __('A hallgató teljesítette a záróvizsgát. Rögzítés után visszavonható.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Igen') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                location.href = '<?= $this->Url->build(['action' => 'setPassedFinalExam', $student->id], true) ?>';
            });
        });
    });
</script>