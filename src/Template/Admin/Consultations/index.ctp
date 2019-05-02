<div class="container admin-consultations-index">
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
                        <table class="table table-bordered table-hover topics-table" id="data_table">
                            <thead>
                                <tr>
                                    <th><?= __('Konzultációs alkalmak csoportja') . '<br/>(' . __('egy lapon szereplő alkalmak') . ')' ?></th>
                                    <th><?= __('Alkalmak') ?></th>
                                    <th><?= __('Státusz') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                    <th><?= __('Létrehozva') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('group_search_text', ['id' => 'group_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th></th>
                                    <th><?= $this->Form->control('status_search_text', ['id' => 'status_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th></th>
                                    <th><?= $this->Form->control('created_search_text', ['id' => 'created_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($consultations as $i => $consultation){ ?>
                                    <tr>
                                        <td><?= '<searchable-text>' . ($i+1) . '. ' . __('csoport') . '<br/>(' .  ($consultation->current === true ? __('A jelenlegi szakdolgozat verzióhoz tartozik') : __('Régebbi szakdolgozat verzióhoz tartozik')) . ')' . '</searchable-text>' ?></td>
                                        <td>
                                            <?= $this->Html->link(__('Alkalmak kezelése'), ['controller' => 'ConsultationOccasions', 'action' => 'index', $consultation->id]) ?>
                                        </td>
                                        <td><?= $consultation->accepted === null ? ('<searchable-text>' .  __('Nem véglegesített') . '</searchable-text>') : ('<searchable-text>' . ($consultation->accepted == true ? __('Megfelelt') : __('Nem felelt meg')) . '</searchable-text>') ?></td>
                                        <td class="text-center">
                                            <?php

                                                if($consultation->accepted === null){ //Ha még nincs véglegesítve
                                                    if($consultation->current === true) //jelenlegi szakdolgozathoz tartozik
                                                        echo $this->Html->link('<i class="fas fa-check-double fa-lg"></i>', '#', ['escape' => false, 'title' => __('Véglegesítés'), 'class' => 'iconBtn finalizeBtn', 'data-id' => $consultation->id]);
                                                }else
                                                    echo $this->Html->link(__('PDF'), ['controller' => 'Consultations', 'action' => 'exportPdf', $consultation->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px', 'target' => '_blank']);
                                                
                                                    echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $consultation->id]);
                                            ?>
                                            <?= $this->Form->postLink('', ['action' => 'delete', $consultation->id], ['style' => 'display: none', 'id' => 'deleteConsultation_' . $consultation->id]) ?>
                                        </td>
                                        <td><?= empty($consultation->created) ? '' : ('<searchable-text>' . $this->Time->format($consultation->created, 'yyyy-MM-dd HH:mm:ss') . '</searchable-text>') ?></td>
                                    </tr>
                                <?php } ?>
                            <tbody>
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
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
        $('.admin-consultations-index .finalizeBtn').on('click', function(e){
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
        
        //Törléskor confirmation modal a megerősítésre
        $('.admin-consultations-index .deleteBtn').on('click', function(e){
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
        
        // DataTable
        var table = $('#data_table').DataTable({
                        pageLength : 10,
                        "dom" : 'tp',
                        "oLanguage": {
                          "oPaginate": {
                            "sNext": "<?= __('Következő') ?>",
                            "sPrevious" : "<?= _('Előző') ?>",
                          },
                          "sEmptyTable": "<?= __('Nincs megjeleníthető tartalom') ?>",
                          "sInfoEmpty": "<?= __('Nincs megjeleníthető tartalom') ?>",
                          "sLengthMenu": "_MENU_ <?= __('rekord megjelenítése') ?>",
                          "sZeroRecords" : "<?= __('Nem található a keresésnek megfelelő elem') ?>"
                        },
                        drawCallback: function(settings){
                            //Pagination elrejtése, ha nincs rá szükség
                            var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                            pagination.toggle(this.api().page.info().pages > 1);
                        }
                      });
        
        //Ha a kereső mezőkbe írunk, akkor újra "rajzoljuk" a tálbázatot
        $('#group_search_text, #status_search_text, #created_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter){
                var group_search_text = $('#group_search_text').val().toLowerCase();
                var status_search_text = $('#status_search_text').val().toLowerCase();
                var created_search_text = $('#created_search_text').val().toLowerCase();
                
                if(group_search_text == '' && status_search_text == '' && created_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_group_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_group_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_group_search_text != -1 && last_index_of_group_search_text != -1){
                    var group_searchable_text = rowData[0].substring(first_index_of_group_search_text + '<searchable-text>'.length, last_index_of_group_search_text);
                    if(group_searchable_text.toLowerCase().indexOf(group_search_text) == -1) ok = false;
                }
                
                var first_index_of_status_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_status_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_status_search_text != -1 && last_index_of_status_search_text != -1){
                    var status_searchable_text = rowData[2].substring(first_index_of_status_search_text + '<searchable-text>'.length, last_index_of_status_search_text);
                    if(status_searchable_text.toLowerCase().indexOf(status_search_text) == -1) ok = false;
                }
                
                var first_index_of_created_search_text = rowData[4].indexOf('<searchable-text>');
                var last_index_of_created_search_text = rowData[4].indexOf('</searchable-text>');
                if(first_index_of_created_search_text != -1 && last_index_of_created_search_text != -1){
                    var created_searchable_text = rowData[4].substring(first_index_of_created_search_text + '<searchable-text>'.length, last_index_of_created_search_text);
                    if(created_searchable_text.toLowerCase().indexOf(created_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>