<div class="container ">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témaajánlatok') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th><?= __('Téma címe') ?></th>
                                <th><?= __('Belső konzulens') ?></th>
                                <th><?= __('Műveletek') ?></th>
                            </tr>
                            <?php foreach($offeredTopics as $offeredTopic){ ?>
                                <tr>
                                    <td><?= h($offeredTopic->title) ?></td>
                                    <td><?= $offeredTopic->has('internal_consultant') ? h($offeredTopic->internal_consultant->name) : '-' ?></td>
                                    <td class="text-center">
                                        <?= $this->Html->link(__('Részletek'), ['controller' => 'OfferedTopics', 'action' => 'details', $offeredTopic->id], ['class' => 'btn btn-info border-radius-45px']) ?>
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
        $('#offered_topics_index_menu_item').addClass('active');
        $('#topics_menu_item').addClass('active');
        
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