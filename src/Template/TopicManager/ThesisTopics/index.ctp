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
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($thesisTopics as $thesisTopic){ ?>
                                    <tr>
                                        <td><?= h($thesisTopic->title) ?></td>
                                        <td><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?></td>
                                        <td><?= $thesisTopic->has('student') ? (h($thesisTopic->student->name) . (empty($thesisTopic->student->neptun) ? '' : ('<br/>(' . h($thesisTopic->student->neptun) . ')'))) : '' ?></td>
                                        <td>
                                            <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                echo $this->Html->link(__('PDF'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info btn-pdf border-radius-45px', 'target' => '_blank']);
                                                
                                                //Ha van külső konzulens, akkor elfogadhatja annak aláírását
                                                if($thesisTopic->cause_of_no_external_consultant === null && $thesisTopic->thesis_topic_status_id == 6){
                                                    echo '<br/>';
                                                    echo $this->Form->create(null, ['id' => 'acceptThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                                    echo $this->Form->button(__('Elfogadás'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px']);
                                                    echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                                    echo $this->Form->end();
                                                    echo $this->Form->create(null, ['id' => 'rejectThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                                    echo $this->Form->button(__('Elutasítás'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px']);
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
        
        //Confirmation modal elfogadás előtt
        $('.topicManager-thesisTopics-index .btn-accept').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
            $('#confirmationModal .msg').text('<?= __('Téma elfogadása.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#acceptThesisTopicForm').trigger('submit');
            });
        });
        
        //Confirmation modal elutasítás előtt
        $('.topicManager-thesisTopics-index .btn-reject').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
            $('#confirmationModal .msg').text('<?= __('Téma elutasítása.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#rejectThesisTopicForm').trigger('submit');
            });
        });
    });
</script>