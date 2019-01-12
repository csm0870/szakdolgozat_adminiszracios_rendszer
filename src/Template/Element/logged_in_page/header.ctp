<div class="row header">
    <div class="col-12 col-sm-6 col-md-4 offset-md-1">
        <?= $this->Html->link($this->Html->image('szechenyi-istvan-egyetem.png', ['class' => 'img-fluid szechenyi-img']),
                              'https://uni.sze.hu/kezdolap', ['target' => '_blank', 'escape' => false]) ?>
    </div>
    <div class="col-12 col-sm-6 col-md-7 text-center title-container">
        <span class="title"><?= __('Szakdolgozat adminisztrációs rendszer') ?></span>
    </div>
    <div class="logout-container">
        <?= $this->Html->link('<i class="fas fa-sign-out-alt fa-3x"></i>', ['controller' => 'Users', 'action' => 'logout', 'prefix' => false], ['escape' => false, 'confirm' => __('Biztosan kijelentkezel?')]) ?>
    </div>
</div>
<script>
    $(function(){
        $('.header .title').on('click', function(){
            location.href = '<?= $this->Url->build('/', true) ?>';
        });
    });
</script>

