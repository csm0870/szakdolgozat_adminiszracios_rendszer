<div class="container ">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Kiírt témák kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th><?= __('Téma címe') ?></th>
                                <th><?= __('Állapot') ?></th>
                                <th><?= __('Műveletek') ?></th>
                            </tr>
                            <?php foreach($offeredTopics as $offeredTopic){ ?>
                                <tr>
                                    <td><?= h($offeredTopic->title) ?></td>
                                    <td>
                                        <?php
                                            if($offeredTopic->has('thesis_topic')){
                                                echo __('Jelentkezett halgató') . ': ' . h($offeredTopic->thesis_topic->student->name);
                                                
                                                if($offeredTopic->thesis_topic->thesis_topic_status_id == 2){
                                                    echo '<br/>' . $this->Html->link(__('Foglalás kezelése') . '&nbsp;->', ['controller' => 'OfferedTopics', 'action' => 'acceptBooking', $offeredTopic->id], ['escape' => false]);
                                                }
                                            }else echo __('Nincs jelentkezett hallgató');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $this->Html->link(__('Szerkesztés'), ['controller' => 'OfferedTopics', 'action' => 'edit', $offeredTopic->id], ['class' => 'btn btn-primary border-radius-45px']) ?>
                                        <?= $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $offeredTopic->id]) ?>
                                        <?= $this->Form->postLink('', ['action' => 'delete', $offeredTopic->id], ['style' => 'display: none', 'id' => 'deleteOfferedTopic_' . $offeredTopic->id]) ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= $this->Html->link(__('Új téma hozzáadása'), ['controller' => 'OfferedTopics', 'action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#offered_topics_index_menu_item').addClass('active');
        
        //Törléskor confirmation modal a megerősítésre
        $('.deleteBtn').on('click', function(e){
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
                $('#deleteOfferedTopic_' + id).trigger('click');
            });
        });
    });
</script>