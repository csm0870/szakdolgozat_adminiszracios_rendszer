<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Diplomamunka/szakdolgozat feltöltése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <?php  
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                echo $this->Form->create($thesisTopic, ['id' => 'uploadThesisForm', 'type' => 'file', 'class' => 'row'])
            ?>
            <div class="col-12">
                <fieldset class="border-1-grey p-3 mb-2">
                    <legend class="w-auto"><?= __('Mellékletek feltöltése') ?></legend>
                    <?= $this->Form->control('thesis_supplements[]', ['type' => 'file', 'label' => ['text' => __('Fájl feltöltése (Szakdolgozat/Diplomamunka PDF-ben és a mellékletek)')],
                                                                      'class' => 'form-control', 'multiple' => true]) ?>
                    <?php if(count($thesisTopic->thesis_supplements) > 0){ ?>
                        <p><?= __('Jelenlegi fájlok') . '' ?></p>
                    <?php } ?>
                    <?php 
                        foreach($thesisTopic->thesis_supplements as $supplement){
                            if(!empty($supplement->file)){ 
                                echo '<p>' .
                                        $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id], ['target' => '_blank']) .
                                        '&nbsp;&nbsp;' . $this->Html->link(__('<i class="fas fa-trash"></i>'), '#', ['class' => 'deleteThesisSupplement', 'style' => 'color: red', 'escape' => false, 'data-id' => $supplement->id, 'title' => __('Törlés')]) .
                                     '</p>';
                            }
                        }
                     ?>
                </fieldset>
            </div>
            <?php if($student->course_id == 1){ //Ha mérnökinformatikus ?>
                <div class="col-12 text-center page-title">
                    <h4>
                        <?= __('Záróvizsga tárgyak megadása') ?>
                    </h4>
                </div>
                <div class="col-12">
                    <?php $i = 0;
                        foreach($student->final_exam_subjects as $subject){ $i++; if($i >= 4) break; ?>
                            <fieldset class="border-1-grey p-3 mb-2">
                                <legend class="w-auto">
                                    <?php
                                        if($i == 1) echo __('Első') . ' ' . __('záróvizsga tárgy');
                                        elseif($i == 2) echo __('Második') . ' ' . __('záróvizsga tárgy');
                                        elseif($i == 3) echo __('Harmadik') . ' ' . __('záróvizsga tárgy');
                                    ?>
                                </legend>
                                <?= $this->Form->control("final_exam_subjects[{$i}][id]", ['type' => 'hidden', 'value' => $subject->id]) ?>
                                <?= $this->Form->control("final_exam_subjects[{$i}][name]", ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true, 'value' => $subject->name]) ?>
                                <?= $this->Form->control("final_exam_subjects[{$i}][teachers]", ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true, 'value' => $subject->teachers]) ?>
                                <?= $this->Form->control("final_exam_subjects[{$i}][year_id]", ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->year_id]) ?>
                                <?= $this->Form->control("final_exam_subjects[{$i}][semester]", ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true, 'value' => $subject->semester]) ?>
                            </fieldset>
                    <?php } ?>
                    <?php if($i == 0){?>
                        <fieldset class="border-1-grey p-3 mb-2">
                            <legend class="w-auto"><?= __('Első') . ' ' . __('záróvizsga tárgy') ?></legend>
                            <?= $this->Form->control('final_exam_subjects[1][name]', ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[1][teachers]', ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[1][year_id]', ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[1][semester]', ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true]) ?>
                        </fieldset>
                    <?php } ?>
                    <?php if($i == 0 || $i == 1){?>
                        <fieldset class="border-1-grey p-3 mb-2">
                            <legend class="w-auto"><?= __('Második') . ' ' . __('záróvizsga tárgy') ?></legend>
                            <?= $this->Form->control('final_exam_subjects[2][name]', ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[2][teachers]', ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[2][year_id]', ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[2][semester]', ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true]) ?>
                        </fieldset>
                    <?php } ?>
                    <?php if($i == 0 || $i == 1 || $i == 2){?>
                        <fieldset class="border-1-grey p-3 mb-2">
                            <legend class="w-auto"><?= __('Harmadik') . ' ' . __('záróvizsga tárgy') ?></legend>
                            <?= $this->Form->control('final_exam_subjects[3][name]', ['label' => ['text' => __('Tárgy neve')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[3][teachers]', ['label' => ['text' => __('Tanár(ok)')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[3][year_id]', ['options' => $years, 'label' => ['text' => __('Tanév, amikor tanulta')], 'class' => 'form-control', 'required' => true]) ?>
                            <?= $this->Form->control('final_exam_subjects[3][semester]', ['options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Félév, amikor tanulta')], 'class' => 'form-control', 'required' => true]) ?>
                        </fieldset>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="col-12">
                <?= $this->Form->button(__('Mentés'), ['type' => 'submit', 'class' => 'btn btn-primary border-radius-45px submitBtn']) ?>
            </div>
            <?= $this->Form->end() ?>
            <?php
                foreach($thesisTopic->thesis_supplements as $supplement){
                    if(!empty($supplement->file)){
                        echo $this->Form->postLink('', ['controller' => 'ThesisSupplements', 'action' => 'delete', $supplement->id], ['id' => 'deleteSupplement_' . $supplement->id, 'style' => 'display: none']);
                    }
                    
                }
            ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        /**
         * Melléklet törlése
         */
        $('.deleteThesisSupplement').on('click', function(e){
            e.preventDefault();
            
            var thesis_supplement_id = $(this).data('id');
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Melléklet törlése.')?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteSupplement_' + thesis_supplement_id).trigger('click');
            });
        });
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('.submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#uploadThesisForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Mellékletek') . ($student->course_id == 1 ? __(' és záróvizsga tárgyak') : '') . __(' mentése')?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#uploadThesisForm').trigger('submit');
            });
        });
    });
</script>