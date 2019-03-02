<div class="container thesisTopics-index topicManager-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témák kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-index-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover topics-table">
                            <tr>
                                <th><?= __('Téma címe') ?></th>
                                <th><?= __('Belső konzulens') ?></th>
                                <th><?= __('Hallgató') ?></th>
                                <th><?= __('Státusz') ?></th>
                            </tr>
                            <?php foreach($thesisTopics as $thesisTopic){ ?>
                                <tr>
                                    <td><?= h($thesisTopic->title) . (in_array($thesisTopic->thesis_topic_status_id, [1, 2, 3, 4, 5]) ? '' : ('<br/>' . $this->Html->link(__('Részletek') . ' ->' , ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]))) ?></td>
                                    <td><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?></td>
                                    <td><?= $thesisTopic->has('student') ? (h($thesisTopic->student->name) . (empty($thesisTopic->student->neptun) ? '' : ('<br/>(' . h($thesisTopic->student->neptun) . ')'))) : '' ?></td>
                                    <td>
                                        <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '' ?>
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
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
    });
</script>