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
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($thesisTopics as $thesisTopic){ ?>
                                    <tr>
                                        <td>
                                            <?= h($thesisTopic->title) .
                                                (in_array($thesisTopic->thesis_topic_status_id, [12, 13]) ? ('<br/>' . $this->Html->link(__('Diplomamunka/szakdolgozat feltöltése') . ' ->' , ['controller' => 'ThesisTopics', 'action' => 'uploadThesis', $thesisTopic->id])) : '') .
                                                ($thesisTopic->thesis_topic_status_id == 14 ? ('<br/>' . $this->Html->link(__('Részletek') . ' ->' , ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id])) : '')
                                            ?>
                                        </td>
                                        <td>
                                            <?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                if($thesisTopic->modifiable == true && $thesisTopic->thesis_topic_status_id == 1){
                                                    //Ha kitöltési időszak van, csak akkor lehet véglegesíteni
                                                    if(!empty($can_fill_in_topic) && $can_fill_in_topic === true){
                                                        echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary edit-btn border-radius-45px']);
                                                        echo $this->Html->link(__('Téma véglegesítés'), '#', ['class' => 'btn btn-success finalize-thesis-topic-btn border-radius-45px', 'data-id' => $thesisTopic->id]);
                                                    }else{
                                                        echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary edit-btn border-radius-45px']);
                                                    }
                                                    
                                                    echo '<br/>';
                                                }
                                                
                                                echo $this->Html->link(__('PDF'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px', 'target' => '_blank']);
                                                if($thesisTopic->encrypted) echo $this->Html->link(__('Titkosítási kérelem'), ['controller' => 'ThesisTopics', 'action' => 'encyptionRegulationDoc', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info enrcyption-doc-btn', 'target' => '_blank']);
                                                
                                                if($thesisTopic->thesis_topic_status_id == 13){
                                                    echo '<br/>';
                                                    echo $this->Html->link(__('Feltöltés véglegesítése'), '#', ['class' => 'btn btn-success finalizeUpoadedThesisBtn border-radius-45px', 'data-id' => $thesisTopic->id]);
                                                }
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
        * Confirmation modal megnyitása submit előtt
        */
        $('.student-thesisTopics-index .finalize-thesis-topic-btn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').html('<?= __('Téma véglegesítése. Véglegesítés után az adatok nem módosíthatóak.<br/>A véglegesítés után a hallgatói adatok sem módosíthatóak, csak ha elutasíják a témát.') ?>');

            $('#confirmationModal').modal('show');
            
            var thesis_topic_id = $(this).data('id');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'finalizeThesisTopic'], true) ?>' + '/' + thesis_topic_id;
            });
        });
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('.student-thesisTopics-index .finalizeUpoadedThesisBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Feltöltés véglegesítése.') ?>');

            $('#confirmationModal').modal('show');
            
            var thesis_topic_id = $(this).data('id');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'finalizeUploadedThesis'], true) ?>' + '/' + thesis_topic_id;
            });
        });
    });
</script>