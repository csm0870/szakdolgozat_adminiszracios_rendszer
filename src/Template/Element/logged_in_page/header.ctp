<div class="row header">
    <div class="col-12 col-sm-6 col-md-4 offset-md-1">
        <?= $this->Html->link($this->Html->image('szechenyi-istvan-egyetem.png', ['class' => 'img-fluid szechenyi-img']),
                              'https://uni.sze.hu/kezdolap', ['target' => '_blank', 'escape' => false]) ?>
    </div>
    <div class="col-12 col-sm-6 col-md-7 text-center title-container">
        <span class="title"><?= __('Szakdolgozat adminisztrációs rendszer') ?></span>
    </div>
    <div class="logout-container">
        <?= $this->Html->link('<i class="fas fa-sign-out-alt fa-3x"></i>', '#', ['escape' => false, 'class' => 'logoutBtn']) ?>
    </div>
</div>
<script>
    $(function(){
        $('.header .title').on('click', function(){
            location.href = '<?= $this->Url->build('/', true) ?>';
        });

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

