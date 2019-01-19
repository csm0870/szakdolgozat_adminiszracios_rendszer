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
                                    <td><?= $i . '. ' . __('csoport') ?></td>
                                    <td><?= $this->Html->link(__('Alkalmak kezelése'), ['controller' => 'ConsultationOccasions', 'action' => 'index', $consultation->id]) ?></td>
                                    <td><?= $accepted === null ? '-' : ($accepted == true ? __('Megfelelt') : __('Nem felelt meg')) ?></td>
                                    <td><?= $this->Html->link(__('Törlés'), '#', ['class' => 'btn btn-danger']) ?></td>
                                    <td><?= empty($consultation->created) ? '' : $this->Time->format($consultation->created, 'yyyy.MM.dd.') ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <?= $this->Html->link(__('Új csoport hozzáadása') . '&nbsp;&nbsp;&nbsp;<span class="circle-btn add-btn">' . $this->Html->image('plus_icon.png') . '</span>', '#', ['class' => 'add-new-consultation', 'escape' => false]) ?>
                </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topic_index_menu_item').addClass('active');
    });
</script>