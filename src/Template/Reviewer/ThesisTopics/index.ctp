<div class="container thesisTopics-index internalConsultant-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témák') ?></h4>
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
                                    <td><?= h($thesisTopic->title) . (in_array($thesisTopic->thesis_topic_status_id, [23]) ? ('<br/>' . $this->Html->link(__('Részletek') . ' ->' , ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id])) : '') ?></td>
                                    <td>
                                        <?php
                                            if($thesisTopic->thesis_topic_status_id == 23){
                                                if($thesisTopic->confidential === true && $thesisTopic->has('review')){
                                                    if($thesisTopic->review->confidentiality_contract_status == null) echo __('A titoktartási szerződés feltöltésére vár.');
                                                    elseif($thesisTopic->review->confidentiality_contract_status == 1) echo __('A titoktartási szerződés feltölve, véglegesítésre vár.');
                                                    elseif($thesisTopic->review->confidentiality_contract_status == 2) echo __('A titoktartási szerződés véglegesítve, tanszékvezető ellenőrzésére vár.');
                                                    elseif($thesisTopic->review->confidentiality_contract_status == 3) echo __('A titoktartási szerződés elutasítva, újra feltölthető.');
                                                    elseif($thesisTopic->review->confidentiality_contract_status == 4) echo __('A dolgozat bírálatra vár.');
                                                }
                                                
                                                if($thesisTopic->confidential == false) echo __('A dolgozat bírálatra vár.');
                                            }elseif($thesisTopic->thesis_topic_status_id == 24){
                                                echo __('A dolgozat bírálva.');
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
        $('#thesis_topics_index_menu_item').addClass('active');
    });
</script>