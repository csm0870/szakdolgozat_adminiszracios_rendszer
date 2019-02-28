<div class="container internalConsultant-consultations-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
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
                                    <td><?= $i+1 . '. ' . __('csoport') . '<br/>(' .  ($consultation->current === true ? __('Jelenlegi szakdolgozathoz tartozik') : __('Régebbi szakdolgozathoz tartozik')) . ')'?></td>
                                    <td>
                                        <?php
                                            if($consultation->accepted === null && $consultation->current === true) //Jelenlegi szakdolgozathoz tartozik és még nem véglegesített
                                                echo $this->Html->link(__('Alkalmak kezelése'), ['controller' => 'ConsultationOccasions', 'action' => 'index', $consultation->id]);
                                            else
                                                echo '-';
                                        ?>
                                    </td>
                                    <td><?= $consultation->accepted === null ? 'Nem véglegesített' : ($consultation->accepted == true ? __('Megfelelt') : __('Nem felelt meg')) ?></td>
                                    <td class="text-center">
                                        <?php
                                        
                                            if($consultation->accepted === null){ //Ha még nincs véglegesítve
                                                if($consultation->current === true){ //jelenlegi szakdolgozathoz tartozik
                                                    echo $this->Html->link('<i class="fas fa-check-double fa-lg"></i>', '#', ['escape' => false, 'title' => __('Véglegesítés'), 'class' => 'iconBtn finalizeBtn', 'data-id' => $consultation->id]);
                                                    echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $consultation->id]);
                                                }
                                            }else
                                                echo $this->Html->link(__('PDF'), ['controller' => 'Consultations', 'action' => 'exportPdf', $consultation->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px', 'target' => '_blank']);
                                        ?>
                                        <?= $this->Form->postLink('', ['action' => 'delete', $consultation->id], ['style' => 'display: none', 'id' => 'deleteConsultation_' . $consultation->id]) ?>
                                    </td>
                                    <td><?= empty($consultation->created) ? '' : $this->Time->format($consultation->created, 'yyyy-MM-dd HH:mm:ss') ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <?php if($can_add_consultation_group){ ?>
                    <div class="col-12 text-center">
                        <?= $this->Html->link(__('Új csoport hozzáadása') . '&nbsp;&nbsp;&nbsp;<span class="circle-btn add-btn">' . $this->Html->image('plus_icon.png') . '</span>', ['action' => 'add', $thesisTopic->id], ['class' => 'add-new-consultation', 'escape' => false]) ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if(!$can_add_consultation_group){ ?>
    <!-- Konzultációs csoport Véglegesítés modal -->
    <div class="modal fade" id="finalizeConsultationModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="finalize_consultation_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        <?php if(!$can_add_consultation_group){ ?>
            $('.internalConsultant-consultations-index .finalizeBtn').on('click', function(e){
                e.preventDefault();

                var consultation_id = $(this).data('id');

                //Tartalom lekeérése a "diplomakurzus első félévének teljesítésének rögzítése" modalba
                $.ajax({
                    url: '<?= $this->Url->build(['action' => 'finalize'], true) ?>' + '/' + consultation_id,
                    cache: false
                })
                .done(function( response ) {
                    $('#finalize_consultation_container').html(response.content);
                    $('#finalizeConsultationModal').modal('show');
                });
            });
        <?php } ?>
        
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