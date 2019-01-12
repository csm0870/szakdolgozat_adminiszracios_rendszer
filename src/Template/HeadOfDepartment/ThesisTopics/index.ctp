<div class="container thesisTopics-index headOfDepartment-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témák') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopic-list-container">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover topics-table">
                                <tr>
                                    <th><?= __('Téma címe') ?></th>
                                    <th><?= __('Belső konzulens') ?></th>
                                    <th><?= __('Hallgató') ?></th>
                                    <th><?= __('Státusz') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($thesisTopics as $thesisTopic){ ?>
                                    <tr>
                                        <td><?= h($thesisTopic->title) ?></td>
                                        <td><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?></td>
                                        <td><?= $thesisTopic->has('student') ? (h($thesisTopic->student->name) . (empty($thesisTopic->student->neptun) ? '' : ('<br/>(' . h($thesisTopic->student->neptun) . ')'))) : '' ?></td>
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
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                echo $this->Html->link(__('PDF'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id], ['class' => 'btn btn-info btn-pdf', 'target' => '_blank']);
                                                
                                                if($thesisTopic->accepted_by_head_of_department === null){
                                                    echo '<br/>';
                                                    echo $this->Form->create(null, ['style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                                    echo $this->Form->button(__('Elfogadás'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept']);
                                                    echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                                    echo $this->Form->end();
                                                    echo $this->Form->create(null, ['style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                                    echo $this->Form->button(__('Elutasítás'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject']);
                                                    echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                                    echo $this->Form->end();
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topic_index_menu_item').addClass('active');
    });
</script>