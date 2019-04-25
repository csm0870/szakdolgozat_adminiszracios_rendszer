<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['action' => 'details', $user->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Felhasználói fiók módosítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->setTemplates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                           'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                
                echo $this->Form->create($user, ['id' => 'userEditForm']);
                echo $this->Form->control('email', ['class' => 'form-control', 'label' => ['text' => __('Felhasználónév (email)')]]);
                echo $this->Form->control('password', ['class' => 'form-control', 'label' => ['text' => __('Jelszó') . ' (' . __('a titkosított jelszó jelenik meg') . ')']]);
                echo $this->Form->control('group_id', ['class' => 'form-control', 'id' => 'group_select', 'label' => ['text' => __('Felhasználói csoport')]]);
                echo $this->Form->control('internal_consultant_id', ['class' => 'form-control', 'label' => ['text' => __('Belső konzulens')], 'value' => $user->has('internal_consultant') ? $user->internal_consultant->id : '',
                                                                     'templates' => ['inputContainer' => '<div class="form-group user_types d-none" id="internal_consultant_select_container">{{content}}</div>',
                                                                                     'inputContainerError' => '<div class="form-group user_types d-none" id="internal_consultant_select_container">{{content}}{{error}}</div>']]);
                echo $this->Form->control('reviewer_id', ['class' => 'form-control', 'label' => ['text' => __('Bíráló')], 'value' => $user->has('reviewer') ? $user->reviewer->id : '',
                                                          'templates' => ['inputContainer' => '<div class="form-group user_types d-none" id="reviewer_select_container">{{content}}</div>',
                                                                                     'inputContainerError' => '<div class="form-group user_types d-none" id="reviewer_select_container">{{content}}{{error}}</div>']]);
                echo $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#users_menu_item').addClass('active');
        $('#user_accounts_index_menu_item').addClass('active');
            
        /**
         * A megfelelő felhasznátípushoz a megfelelő elemet jelenítjük meg
         * 
         * @return {undefined}
         */
        function changeGroup(){
            var group_id = $('#group_select').val();
            $('.user_types').addClass('d-none');
            
            if(group_id == 2) $('#internal_consultant_select_container').removeClass('d-none');
            else if(group_id == 7) $('#reviewer_select_container').removeClass('d-none');
        }
          
        changeGroup();
          
        $('#group_select').on('change', changeGroup);
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#userEditForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#userEditForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Felhasználói fiók mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#userEditForm').trigger('submit');
            });
        });
    });
</script>