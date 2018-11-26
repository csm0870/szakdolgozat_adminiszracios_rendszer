<div class="container">
    <div class="row header">
        <div class="col-12 col-sm-6 col-md-4">
            <?= $this->Html->link($this->Html->image('szechenyi-istvan-egyetem.png', ['class' => 'img-fluid']),
                                  'https://uni.sze.hu/kezdolap', ['target' => '_blank', 'escape' => false]) ?>
        </div>
        <div class="col-12 col-sm-6 col-md-8 text-center title-container">
            <span class="title"><?= __('Szakdolgozat adminisztrációs rendszer') ?></span>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.header .title').on('click', function(){
            location.href = '<?= $this->Url->build('/', true) ?>';
        });
    });
</script>
