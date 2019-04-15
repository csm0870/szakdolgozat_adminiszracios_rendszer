<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'Students', 'action' => 'details', $student->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Hallgatói adatok módosítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->setTemplates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                           'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                
                echo $this->Form->create($student, ['id' => 'studentEditForm']);
                echo $this->Form->control('name', ['class' => 'form-control', 'label' => ['text' => __('Név')]]);
                echo $this->Form->control('address', ['class' => 'form-control', 'label' => ['text' => __('Cím')]]);
                echo $this->Form->control('neptun', ['class' => 'form-control', 'label' => ['text' => __('Neptun kód')]]);
                echo $this->Form->control('email', ['class' => 'form-control', 'label' => ['text' => __('Email cím')]]);
                echo $this->Form->control('phone_number', ['class' => 'form-control', 'label' => ['text' => __('Telefonszám')], 'placeholder' => __('+36701234567 formátumban.')]);
                echo $this->Form->control('specialisation', ['class' => 'form-control', 'label' => ['text' => __('Specializáció')]]);
                echo $this->Form->control('course_id', ['options' => $courses, 'class' => 'form-control', 'label' => ['text' => __('Szak')]]);
                echo $this->Form->control('course_level_id', ['options' => $courseLevels, 'class' => 'form-control', 'label' => ['text' => __('Képzés szintje')]]);
                echo $this->Form->control('course_type_id', ['options' => $courseTypes, 'class' => 'form-control', 'label' => ['text' => __('Tagozat')]]);
                echo $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#students_index_menu_item').addClass('active');
            
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#studentEditForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#studentEditForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Hallgatói adatok mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#studentEditForm').trigger('submit');
            });
        });
    });
</script>
