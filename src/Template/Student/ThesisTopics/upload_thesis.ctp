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
                    <p class="<?= $thesisTopic->thesis_topic_status_id == 19 || ($thesisTopic->thesis_topic_status_id == 17 && $thesisTopic->cause_of_rejecting_thesis_supplements !== null) ? 'mb-1' : 'mb-4' ?>">
                        <strong><?= __('Állapot') . ': ' ?></strong><?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : ''?>
                    </p>
                    <?php if($thesisTopic->thesis_topic_status_id == 19 || ($thesisTopic->thesis_topic_status_id == 17 && $thesisTopic->cause_of_rejecting_thesis_supplements !== null)){ ?>
                        <p class="mb-3">
                            <strong><?= __('Elutasítás oka') . ': ' ?></strong><?= h($thesisTopic->cause_of_rejecting_thesis_supplements) ?>
                        </p>
                    <?php } ?>
                </div>
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
            <div class="col-12">
                <div class="row">
                    <div class="col-12 <?= $thesisTopic->thesis_topic_status_id == 17 ? 'col-sm-6' : '' ?> text-center">
                        <?= $this->Form->button(__('Mentés'), ['type' => 'submit', 'class' => 'btn btn-primary border-radius-45px submitBtn']) ?>
                    </div>
                    <div class=" col-12 col-sm-6 text-center">
                        <?= $thesisTopic->thesis_topic_status_id == 17 ? $this->Html->link(__('Feltöltés véglegesítése'), '#', ['class' => 'btn btn-success finalizeUpoadedThesisBtn border-radius-45px']) : ''?>
                    </div>
                </div>
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
        $('#topics_menu_item').addClass('active');
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
            $('#confirmationModal .msg').text('<?= __('Záróvizsga-tárgyak mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#uploadThesisForm').trigger('submit');
            });
        });
        
        <?php if($thesisTopic->thesis_topic_status_id == 17 ){ ?>
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('.finalizeUpoadedThesisBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Feltöltés véglegesítése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'finalizeUploadedThesis', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
    });
</script>