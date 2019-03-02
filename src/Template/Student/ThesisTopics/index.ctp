<div class="container thesisTopics-index student-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témaengedélyezők kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-index-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover topics-table">
                            <tr>
                                <th><?= __('Téma címe') ?></th>
                                <th><?= __('Állapot') ?></th>
                            </tr>
                            <?php foreach($thesisTopics as $thesisTopic){ ?>
                                <tr>
                                    <td>
                                        <?= h($thesisTopic->title) . '<br/>' . $this->Html->link(__('Részletek') . ' ->' , ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]) ?>
                                    </td>
                                    <td>
                                        <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '' ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?php
                        if(!empty($can_fill_in_topic) && $can_fill_in_topic === true){
                            if(!empty($can_add_topic) && $can_add_topic === true)
                                echo $this->Html->link(__('Új téma hozzáadása'), ['controller' => 'ThesisTopics', 'action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px']);
                        }else{ ?>
                            <h5 style="color: red"><?= __('Nincs kitöltési időszak!') ?></h5>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
    });
</script>