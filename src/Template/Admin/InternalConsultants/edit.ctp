<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['action' => 'details', $internalConsultant->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Belső konzulensi adatok módosítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->setTemplates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                           'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                
                echo $this->Form->create($internalConsultant, ['id' => 'internalConsultantEditForm']);
                echo $this->Form->control('name', ['class' => 'form-control', 'label' => ['text' => __('Név')]]);
                echo $this->Form->control('internal_consultant_position_id', ['options' => $internalConsultantPositions, 'class' => 'form-control', 'label' => ['text' => __('Pozíció')]]);
                echo $this->Form->control('department_id', ['options' => $departments, 'class' => 'form-control', 'label' => ['text' => __('Tanszék')]]);
                echo $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#users_menu_item').addClass('active');
        $('#internal_consultants_index_menu_item').addClass('active');
            
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#internalConsultantEditForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#internalConsultantEditForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Belső konzulensi adatok mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#internalConsultantEditForm').trigger('submit');
            });
        });
    });
</script>