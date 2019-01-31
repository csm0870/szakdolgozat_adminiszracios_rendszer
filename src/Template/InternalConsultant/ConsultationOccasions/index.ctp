<div class="container internalConsultant-consultationOccasions-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Konzultációs alkalmak') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row consultationOccasions-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover topics-table">
                            <tr>
                                <th><?= __('Alkalom időpontja') ?></th>
                                <th><?= __('Műveletek') ?></th>
                                <th><?= __('Létrehozva') ?></th>
                            </tr>
                            <?php foreach($consultationOccasions as $consultationOccasion){ ?>
                                <tr>
                                    <td><?= empty($consultationOccasion->date) ? '' : $this->Time->format($consultationOccasion->date, 'yyyy-MM-dd') ?></td>
                                    <td class="text-center">
                                        <?php
                                            if($consultation->accepted === null){
                                                echo $this->Html->link('<i class="fas fa-edit fa-lg"></i>', '#', ['class' => 'iconBtn editBtn', 'data-id' => $consultationOccasion->id, 'escape' => false, 'title' => __('Szerkesztés')]);
                                                echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $consultationOccasion->id]);
                                                echo $this->Form->postLink('', ['action' => 'delete', $consultationOccasion->id], ['style' => 'display: none', 'id' => 'deleteConsultationOccasion_' . $consultationOccasion->id]);
                                            }
                                        ?>
                                    </td>
                                    <td><?= empty($consultationOccasion->created) ? '' : $this->Time->format($consultationOccasion->created, 'yyyy-MM-dd HH:mm:ss') ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <?php if($consultation->accepted === null && $consultation->current === true){ //Még nincs véglegesítve a konzultációs csoport és a jelenlegi szakdolgozathoz tartozik ?>
                    <div class="col-12 text-center">
                        <?= $this->Html->link(__('Új alkalom hozzáadása') . '&nbsp;&nbsp;&nbsp;<span class="circle-btn add-btn">' . $this->Html->image('plus_icon.png') . '</span>', ['action' => 'add', $consultation->id], ['class' => 'add-new-consultationOccasion', 'escape' => false]) ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if($consultation->accepted === null){ ?> <!-- Még nincs véglegesítve a konzultációs csoport -->
    <!-- Konzultációs alkalom hozzáadása modal -->
    <div class="modal fade" id="consultationOccasionAddModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="consultationOccasion-add">

                    </div>
                </div>
            </div>
      </div>
    </div>
    <!-- Konzultációs alkalom szerkesztése modal -->
    <div class="modal fade" id="consultationOccasionEditModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="consultationOccasion-edit">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('#thesis_topic_index_menu_item').addClass('active');
        <?php if($consultation->accepted === null && $consultation->current === true){ //Még nincs véglegesítve a konzultációs csoport és a jelenlegi szakdolgozathoz tartozik ?>
        //Tartalom lekeérése a hozzáadáshoz
        $.ajax({
                url: '<?= $this->Url->build(['controller' => 'ConsultationOccasions', 'action' => 'add', $consultation->id]) ?>',
                cache: false
        })
        .done(function( response ) {
            $('#consultationOccasion-add').html( response.content );
        });
        
        //Konzultációs alkalom hozzáadása popup megnyitása
        $('.add-new-consultationOccasion').on('click', function(e){
            e.preventDefault();
            $('#consultationOccasionAddModal').modal('show');
        });
        
        //Konzultációs alkalom szerkesztése
        $('.editBtn').on('click', function(e){
            e.preventDefault();
            
            var id = $(this).data('id');
            
            //Szerkesztési oldal lekérése
            $.ajax({
                    url: '<?= $this->Url->build(['action' => 'edit'], true) ?>' + '/' + id,
                    cache: false
            })
            .done(function( response ) {
                    $('#consultationOccasion-edit').html(response.content);
                    $('#consultationOccasionEditModal').modal('show');
            });
        });
        
        
        //Törléskor confirmation modal a megerősítésre
        $('.internalConsultant-consultationOccasions-index .deleteBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Konzultációs alkalom törlése.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            var id = $(this).data('id');
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteConsultationOccasion_' + id).trigger('click');
            });
        });
        <?php } ?>
    });
</script>