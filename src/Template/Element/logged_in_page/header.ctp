<div class="row header">
    <div class="col-12 col-sm-6 col-md-4 offset-md-1">
        <?= $this->Html->link($this->Html->image('szechenyi-istvan-egyetem.png', ['class' => 'img-fluid szechenyi-img']),
                              'https://uni.sze.hu/kezdolap', ['target' => '_blank', 'escape' => false]) ?>
    </div>
    <div class="col-12 col-sm-6 col-md-7 title-container">
        <div class="row">
            <div class="col-12 text-center">
                <span class="title"><?= __('Szakdolgozat adminisztrációs rendszer') ?></span>
            </div>
            <div class="col-12 text-right">
                <?= __('Bejelentkezve, mint') . ' ' . h($logged_in_user->username) ?>
            </div>
        </div>
    </div>
    <div class="logout-container">
        <?= $this->Html->link('<i class="fas fa-sign-out-alt fa-3x"></i>', '#', ['escape' => false, 'class' => 'logoutBtn']) ?>
    </div>
</div>
<script>
    $(function(){
        <?php if($logged_in_user['group_id'] == 1){ ?>
            $('.header .title').on('click', function(){
                location.href = '<?= $this->Url->build(['controller' => 'Pages', 'action' => 'dashboard'], true) ?>';
            });
        <?php }else{ ?>
            $('.header .title').on('click', function(){
                location.href = '<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'index'], true) ?>';
            });
        <?php } ?>
        

       /**
        * Kijelentkezés popup
        */
       $('.logout-container .logoutBtn').on('click', function(e){
           e.preventDefault();

           $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan kijelentkezel?') ?>');
           $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Igen') ?>').css('background-color', '#71D0BD');
           //Save gomb eventjeinek resetelése cserével
           $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
           $('#confirmationModal .msg').text('<?= __('Kijelentkezés megerősítése.') ?>');

           $('#confirmationModal').modal('show');

           $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
               e.preventDefault();
               location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout', 'prefix' => false]) ?>';
           });
       });
    });
</script>

