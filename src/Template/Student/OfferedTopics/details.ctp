<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'OfferedTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <?php
                echo $this->Form->control('title', ['class' => 'form-control', 'label' => ['text' => __('Cím')], 'value' => $offeredTopic->title, 'readonly' => true]);
                echo $this->Form->control('description', ['type' => 'textarea' ,'class' => 'form-control', 'label' => ['text' => __('Leírás')], 'value' => $offeredTopic->description, 'readonly' => true]);
                if($offeredTopic->has_external_consultant === false) echo $this->Form->control('has_external_consultant', ['class' => 'form-control', 'options' => [__('Nincs'), __('Van')] ,'label' => ['text' => __('Van-e külső konzulens')], 'value' => $offeredTopic->has_external_consultant, 'disabled' => true]);
                elseif($offeredTopic->has_external_consultant === true){
                    echo $this->Form->control('external_consultant_name', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens neve')], 'value' => $offeredTopic->external_consultant_name, 'readonly' => true]);
                    echo $this->Form->control('external_consultant_workplace', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens munkahelye')], 'value' => $offeredTopic->external_consultant_workplace, 'readonly' => true]);
                    echo $this->Form->control('external_consultant_position', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens poziciója')], 'value' => $offeredTopic->external_consultant_position, 'readonly' => true]);
                    echo $this->Form->control('external_consultant_email', ['type' => 'email', 'class' => 'form-control', 'label' => ['text' => __('Külső konzulens email címe')], 'value' => $offeredTopic->external_consultant_email, 'readonly' => true]);
                    echo $this->Form->control('external_consultant_phone_number', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens telefonszáma')], 'value' => $offeredTopic->external_consultant_phone_number, 'readonly' => true]);
                    echo $this->Form->control('external_consultant_address', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens címe')], 'value' => $offeredTopic->external_consultant_address, 'readonly' => true]);
                }
            ?>
        </div>
        <?php if($can_add_topic === true){?>
            <div class="col-12 mt-3 text-center">
                <?= $this->Html->link(__('Lefoglalás'), '#', ['class' => 'btn btn-primary book-btn border-radius-45px']) ?>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#offered_topics_index_menu_item').addClass('active');
        
        /**
        * Confirmation modal megnyitása foglalás előtt
        */
        $('.book-btn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan lefoglalod a témát?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Lefoglalás') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').html('<?= __('Téma lefoglalása.') ?>');

            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                location.href = '<?= $this->Url->build(['controller' => 'OfferedTopics', 'action' => 'book', $offeredTopic->id], true) ?>';
            });
        });
    });
</script>
