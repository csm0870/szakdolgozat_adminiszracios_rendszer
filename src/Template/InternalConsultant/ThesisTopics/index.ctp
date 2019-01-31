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
                                    <th><?= __('Hallgató') ?></th>
                                    <th><?= __('Státusz') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($thesisTopics as $thesisTopic){ ?>
                                    <tr>
                                        <td><?= h($thesisTopic->title) . (in_array($thesisTopic->thesis_topic_status_id, [8, 9, 10, 11]) ? ('<br/>' . $this->Html->link(__('Részletek') . ' ->' , ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id])) : '') ?></td>
                                        <td><?= $thesisTopic->has('student') ? (h($thesisTopic->student->name) . (empty($thesisTopic->student->neptun) ? '' : ('<br/>(' . h($thesisTopic->student->neptun) . ')'))) : '' ?></td>
                                        <td>
                                            <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                echo $this->Html->link(__('PDF'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info btn-pdf border-radius-45px', 'target' => '_blank']);
                                                
                                                //Akkor törölheti, ha már nincs bírálati folyamatban
                                                if(in_array($thesisTopic->thesis_topic_status_id, [3, 5, 7, 8, 10, 11])){
                                                    echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $thesisTopic->id]);
                                                    echo $this->Form->postLink('', ['action' => 'delete', $thesisTopic->id], ['style' => 'display: none', 'id' => 'deleteThesisTopic_' . $thesisTopic->id]);
                                                }
                                                
                                                //Belső konzulensi döntésre vár
                                                if($thesisTopic->thesis_topic_status_id == 2){
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
        
        //Törléskor confirmation modal a megerősítésre
        $('.internalConsultant-thesisTopics-index .deleteBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Téma törlése.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            var id = $(this).data('id');
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteThesisTopic_' + id).trigger('click');
            });
        });
    });
</script>