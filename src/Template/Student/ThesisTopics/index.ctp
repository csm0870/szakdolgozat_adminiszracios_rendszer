<div class="container thesisTopics-index student-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témaengedélyezők kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopic-list-container">
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
                                            <?php
                                                //Ha van külső konzulens, és már kiderült, hogy elfogadta-e vagy sem
                                                if($thesisTopic->cause_of_no_external_consultant === null && $thesisTopic->accepted_by_external_consultant !== null){
                                                    if($thesisTopic->accepted_by_external_consultant == true){
                                                        echo __('Elfogadva');
                                                    }else{
                                                        echo __('Elutasítva') . ' (' . __('külső konzulens') . ')';
                                                    }
                                                }elseif($thesisTopic->accepted_by_head_of_department !== null){//Ha már a tanszékvezető döntött
                                                    if($thesisTopic->accepted_by_head_of_department == true){
                                                        //Ha van külső konzulens
                                                        if($thesisTopic->cause_of_no_external_consultant === null){
                                                            echo __('Külső konzulensi aláírás ellenőrzésére vár');
                                                        }else{
                                                            echo __('Elfogadva');
                                                        }
                                                    }else{
                                                        echo __('Elutasítva') . ' (' . __('tanszékvezető') . ')';
                                                    }
                                                }elseif($thesisTopic->accepted_by_internal_consultant !== null){//Ha már a tanszékvezető döntött
                                                    if($thesisTopic->accepted_by_internal_consultant == true){
                                                        echo __('Tanszékvezetői döntésre vár');
                                                    }else{
                                                        echo __('Elutasítva'). ' (' . __('belső konzulens') . ')';
                                                    }
                                                }elseif($thesisTopic->modifiable == false){
                                                    echo __('Belső konzulensi döntésre vár');
                                                }else{
                                                    echo __('Véglegesítésre vár');
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                if($thesisTopic->modifiable == true){
                                                    //Ha kitöltési időszak van, csak akkor lehet véglegesíteni
                                                    if(!empty($can_fill_in_topic) && $can_fill_in_topic === true){
                                                        echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'studentEdit', $thesisTopic->id], ['class' => 'btn btn-primary edit-btn']);
                                                        echo $this->Html->link(__('Véglegesítés'), ['controller' => 'ThesisTopics', 'action' => 'studentFinalize', $thesisTopic->id], ['class' => 'btn btn-success finalize-btn']);
                                                    }else{
                                                        echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'studentEdit', $thesisTopic->id], ['class' => 'btn btn-primary edit-btn']);
                                                    }
                                                    
                                                    echo '<br/>';
                                                }
                                                
                                                echo $this->Html->link(__('PDF'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id], ['class' => 'btn btn-info', 'target' => '_blank']);
                                                if($thesisTopic->encrypted) echo $this->Html->link(__('Titkosítási kérelem'), ['controller' => 'ThesisTopics', 'action' => 'encyptionRegulationDoc', $thesisTopic->id], ['class' => 'btn btn-info enrcyption-doc-btn', 'target' => '_blank']);
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
        
        /**
         * Véglegesítés megerősítése
         */
        $('.finalize-btn').on('click', function(e){
            if(!confirm('<?= __('Bitosan véglegesíted?') ?>')) return false;
        });
    });
</script>