<div class="container internalConsultant-consultations-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Konzultációk') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row consultations-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover topics-table">
                            <tr>
                                <th><?= __('Konzultációs alkalmak csoportja') . '<br/>(' . __('egy lapon szereplő alkalmak') . ')' ?></th>
                                <th><?= __('Alkalmak') ?></th>
                                <th><?= __('Státusz') ?></th>
                                <th><?= __('Műveletek') ?></th>
                                <th><?= __('Létrehozva') ?></th>
                            </tr>
                            <?php foreach($consultations as $i => $consultation){ ?>
                                <tr>
                                    <td><?= $i+1 . '. ' . __('csoport') ?></td>
                                    <td><?= $this->Html->link(__('Alkalmak kezelése'), ['controller' => 'ConsultationOccasions', 'action' => 'index', $consultation->id]) ?></td>
                                    <td><?= $consultation->accepted === null ? '-' : ($consultation->accepted == true ? __('Megfelelt') : __('Nem felelt meg')) ?></td>
                                    <td class="text-center">
                                        <?= $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'style' => 'color: red', 'class' => 'deleteBtn', 'data-id' => $consultation->id]) ?>
                                        <?= $this->Form->postLink('', ['action' => 'delete', $consultation->id], ['style' => 'display: none', 'id' => 'deleteConsultation_' . $consultation->id]) ?>
                                    </td>
                                    <td><?= empty($consultation->created) ? '' : $this->Time->format($consultation->created, 'yyyy-MM-dd HH:mm:ss') ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <?= $this->Html->link(__('Új csoport hozzáadása') . '&nbsp;&nbsp;&nbsp;<span class="circle-btn add-btn">' . $this->Html->image('plus_icon.png') . '</span>', ['action' => 'add', $thesisTopic->id], ['class' => 'add-new-consultation', 'escape' => false]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topic_index_menu_item').addClass('active');
        
        //Törléskor confirmation modal a megerősítésre
        $('.internalConsultant-consultations-index .deleteBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Konzultációs alkalmak csoportjának törlése.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            var id = $(this).data('id');
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteConsultation_' + id).trigger('click');
            });
        });
    });
</script>