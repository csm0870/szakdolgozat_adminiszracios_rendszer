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
                                    <th><?= __('Státusz') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($thesisTopics as $thesisTopic){ ?>
                                    <tr>
                                        <td><?= h($thesisTopic->title) ?></td>
                                        <td>
                                            <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                if($thesisTopic->modifiable == true && $thesisTopic->thesis_topic_status_id == 1){
                                                    //Ha kitöltési időszak van, csak akkor lehet véglegesíteni
                                                    if(!empty($can_fill_in_topic) && $can_fill_in_topic === true){
                                                        echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary edit-btn']);
                                                        echo $this->Html->link(__('Véglegesítés'), ['controller' => 'ThesisTopics', 'action' => 'finalize', $thesisTopic->id], ['class' => 'btn btn-success finalize-btn', 'confirm' => __('Bitosan véglegesíted?')]);
                                                    }else{
                                                        echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary edit-btn']);
                                                    }
                                                    
                                                    echo '<br/>';
                                                }
                                                
                                                echo $this->Html->link(__('PDF'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info', 'target' => '_blank']);
                                                if($thesisTopic->encrypted) echo $this->Html->link(__('Titkosítási kérelem'), ['controller' => 'ThesisTopics', 'action' => 'encyptionRegulationDoc', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info enrcyption-doc-btn', 'target' => '_blank']);
                                            ?>
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
                                echo $this->Html->link(__('Új téma hozzáadása'), ['controller' => 'ThesisTopics', 'action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block']);
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
        $('#thesis_topics_index_menu_item').addClass('active');
    });
</script>