<div class="container admin-internalConsultants-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Belső konzulens részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Belső konzulens adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Név') . ': ' ?></strong><?= h($internalConsultant->name) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Pozició') . ': ' ?></strong><?= $internalConsultant->has('internal_consultant_position') ? h($internalConsultant->internal_consultant_position->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Tanszék') . ': ' ?></strong><?= $internalConsultant->has('department') ? h($internalConsultant->department->name) : __('nincs tanszékhez rendelve') ?>
                        </p>
                    </fieldset>
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Belső konzulenshez tartozó felhasználó fiók') ?></legend>
                        <?php if($internalConsultant->has('user')){ ?>
                            <p class="mb-1">
                                <strong><?= __('Felhasználónév') . ': ' ?></strong><?= $internalConsultant->has('user') ? h($internalConsultant->user->email) : '' ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Jelszó') . ': ' ?></strong><?= $internalConsultant->has('user') ? ($internalConsultant->user->has('raw_password') ? $internalConsultant->user->raw_password->password : '') : '' ?>
                            </p>
                            <p class="mb-0">
                                <?= $this->Html->link(__('További részletek') . ' ->', ['controller' => 'Users', 'action' => 'details', $internalConsultant->user->id]) ?>
                            </p>
                        <?php }else{ ?>
                            <p class="mb-0">
                                <?= $this->Html->link(__('Felhasználó fiók hozzáadása') . ' ->', ['controller' => 'Users', 'action' => 'add', 2, $internalConsultant->id]) ?>
                            </p>
                        <?php } ?>
                    </fieldset>
                </div>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            echo $this->Html->link(__('Adatok szerkesztése'), ['action' => 'edit', $internalConsultant->id], ['class' => 'btn btn-secondary border-radius-45px mb-2']) . '<br/>';
                            echo $this->Html->link(__('Belső konzulens törlése'), '#', ['class' => 'btn btn-danger border-radius-45px delete-btn']);
                            echo $this->Form->postLink('', ['action' => 'delete', $internalConsultant->id], ['style' => 'display: none', 'id' => 'deleteInternalConsultant']);
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
        $('#internal_consultants_index_menu_item').addClass('active');
        
        //Törléskor confirmation modal a megerősítésre
        $('.admin-internalConsultants-details .delete-btn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Belső konzulens végleges törlése. A belső konzulens  törlés után nem lesz visszaállítható.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteInternalConsultant').trigger('click');
            });
        });
    });
</script>