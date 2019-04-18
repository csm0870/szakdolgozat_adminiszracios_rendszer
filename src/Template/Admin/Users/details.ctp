<div class="container admin-users-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Felhasználói fiók részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Felhasználói fiók adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Felhasználónév') . ': ' ?></strong><?= h($user->email) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Jelszó') . ': ' ?></strong><?= $user->has('raw_password') ? $user->raw_password->password : '' ?>
                        </p>
                        <p class="mb-0">
                            <strong><?= __('Csoport') . ': ' ?></strong><?= $user->has('group') ? $user->group->name : '' ?>
                        </p>
                    </fieldset>
                </div>
                <?php if(in_array($user->group_id, [2, 7])){ //Belső konzulens, bíráló?>
                    <div class="col-12">
                        <fieldset class="border-1-grey p-3 mb-3">
                            <legend class="w-auto"><?= __('Felhasználói fiókhoz tartozó felhasználó') ?></legend>
                            <p class="mb-1">
                                <strong><?= __('Név') . ': ' ?></strong>
                                <?php
                                    if($user->group_id == 2 && $user->has('internal_consultant')){
                                        echo h($user->internal_consultant->name);
                                    }elseif($user->group_id == 7 && $user->has('reviewer')){
                                        echo h($user->reviewer->name);
                                    }
                                ?>
                            </p>
                            <p class="mb-0">
                                <?php
                                    if($user->group_id == 2 && $user->has('internal_consultant')){
                                        echo $this->Html->link(__('További részletek') . ' ->', ['controller' => 'InternalConsultants', 'action' => 'details', $user->internal_consultant->id]);
                                    }elseif($user->group_id == 7 && $user->has('reviewer')){
                                        echo $this->Html->link(__('További részletek') . ' ->', ['controller' => 'Reviewers', 'action' => 'details', $user->reviewer->id]);
                                    }
                                ?>
                            </p>
                        </fieldset>
                    </div>
                <?php } ?>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            echo $this->Html->link(__('Adatok szerkesztése'), ['action' => 'edit', $user->id], ['class' => 'btn btn-secondary border-radius-45px mb-2']) . '<br/>';
                            echo $this->Html->link(__('Felhasználói fiók törlése'), '#', ['class' => 'btn btn-danger border-radius-45px delete-btn']);
                            echo $this->Form->postLink('', ['action' => 'delete', $user->id], ['style' => 'display: none', 'id' => 'deleteUser']);
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
        $('#user_accounts_index_menu_item').addClass('active');
        
        //Törléskor confirmation modal a megerősítésre
        $('.admin-users-details .delete-btn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Felhasználói fiók végleges törlése. A felhasználói fiók törlés után nem lesz visszaállítható.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteUser').trigger('click');
            });
        });
    });
</script>