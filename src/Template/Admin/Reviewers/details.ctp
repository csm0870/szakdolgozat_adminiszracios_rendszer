<div class="container admin-reviewers-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Bíráló részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Bíráló adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Név') . ': ' ?></strong><?= h($reviewer->name) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Email') . ': ' ?></strong><?= h($reviewer->email) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Munkahely') . ': ' ?></strong><?= h($reviewer->workplace) ?>
                        </p>
                        <p class="mb-0">
                            <strong><?= __('Pozíció') . ': ' ?></strong><?= h($reviewer->position) ?>
                        </p>
                    </fieldset>
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Bírálóhoz tartozó felhasználó fiók') ?></legend>
                        <?php if($reviewer->has('user')){ ?>
                            <p class="mb-1">
                                <strong><?= __('Felhasználónév') . ': ' ?></strong><?= $reviewer->has('user') ? h($reviewer->user->email) : '' ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Jelszó') . ': ' ?></strong><?= $reviewer->has('user') ? ($reviewer->user->has('raw_password') ? $reviewer->user->raw_password->password : '') : '' ?>
                            </p>
                            <p class="mb-0">
                                <?= $this->Html->link(__('További részletek') . ' ->', ['controller' => 'Users', 'action' => 'details', $reviewer->user->id]) ?>
                            </p>
                        <?php }else{ ?>
                            <p class="mb-0">
                                <?= $this->Html->link(__('Felhasználó fiók hozzáadása') . ' ->', ['controller' => 'Users', 'action' => 'add', 7, $reviewer->id]) ?>
                            </p>
                        <?php } ?>
                    </fieldset>
                </div>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            echo $this->Html->link(__('Adatok szerkesztése'), ['action' => 'edit', $reviewer->id], ['class' => 'btn btn-secondary border-radius-45px mb-2']) . '<br/>';
                            echo $this->Html->link(__('Bíráló törlése'), '#', ['class' => 'btn btn-danger border-radius-45px delete-btn']);
                            echo $this->Form->postLink('', ['action' => 'delete', $reviewer->id], ['style' => 'display: none', 'id' => 'deleteReviewer']);
                        ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#users_menu_item').addClass('active');
        $('#reviewers_index_menu_item').addClass('active');
        
        //Törléskor confirmation modal a megerősítésre
        $('.admin-reviewers-details .delete-btn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Bíráló végleges törlése. A bíráló törlés után nem lesz visszaállítható.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteReviewer').trigger('click');
            });
        });
    });
</script>