<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h4><?= __('Titoktartási kérelem szabályzatának beállítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php 
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
            ?>
            <?= $this->Form->create($info, ['id' => 'setEncyptionRegulationForm']) ?>
            <?= $this->Form->control('encryption_requlation', ['class' => 'form-control', 'label' => ['text' => __('Titoktartási kérelem szabályzata')]]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#set_encryption_requlation_menu_item').addClass('active');
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#setEncyptionRegulationForm .submitBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Titoktartási kérelem szabályzat szövegének mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#setEncyptionRegulationForm').trigger('submit');
            });
        });
    });
</script>
