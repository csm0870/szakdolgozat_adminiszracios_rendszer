<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h4><?= __('Titoktartási kérelem szabályzatának szövegét állíthatja be.') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php 
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
            ?>
            <?= $this->Form->create($info, []) ?>
            <?= $this->Form->control('encryption_requlation', ['class' => 'form-control', 'label' => ['text' => __('Titoktartási kérelem szabályzata')]]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#topic_manager_set_encryption_requlation_menu_item').addClass('active');
    });
</script>
