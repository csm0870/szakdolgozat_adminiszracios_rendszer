<div class="container admin-years-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Tanévek') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data_table">
                            <thead>
                                <tr>
                                    <th><?= __('Tanév') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('year_search_text', ['id' => 'year_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($years as $year){ ?>
                                    <tr>
                                        <td><?= '<searchable-text>' . h($year->year) . '</searchable-text>' ?></td>
                                        <td class="text-center">
                                            <?php
                                                echo $this->Html->link('<i class="fas fa-edit fa-lg"></i>', '#', ['class' => 'iconBtn editBtn', 'data-id' => $year->id, 'escape' => false, 'title' => __('Szerkesztés')]);
                                                echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $year->id]);
                                                echo $this->Form->postLink('', ['action' => 'delete', $year->id], ['style' => 'display: none', 'id' => 'deleteYear_' . $year->id]);
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <?= $this->Html->link(__('Új tanév hozzáadása'), ['action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px add-new-year']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tanév hozzáadása modal -->
<div class="modal fade" id="yearAddModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <div id="year_add_container">

                  </div>
              </div>
          </div>
    </div>
</div>
<!-- Tanév szerkesztése modal -->
<div class="modal fade" id="yearEditModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <div id="year_edit_container">

                  </div>
              </div>
          </div>
    </div>
</div>
<script>
    $(function(){
        $('#others_menu_item').addClass('active');
        $('#years_index_menu_item').addClass('active');
        
        //Tartalom lekeérése a hozzáadáshoz
        $.ajax({
            url: '<?= $this->Url->build(['action' => 'add'], true) ?>',
            cache: false
        })
        .done(function(response){
            $('#year_add_container').html(response.content);
        });

        //Képzésszint hozzáadása popup megnyitása
        $('.add-new-year').on('click', function(e){
            e.preventDefault();
            $('#yearAddModal').modal('show');
        });

        //Képzéstípus szerkesztése
        $('.editBtn').on('click', function(e){
            e.preventDefault();

            var id = $(this).data('id');

            //Szerkesztési oldal lekérése
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'edit'], true) ?>' + '/' + id,
                cache: false
            })
            .done(function(response){
                $('#year_edit_container').html(response.content);
                $('#yearEditModal').modal('show');
            });
        });


        //Törléskor confirmation modal a megerősítésre
        $('.admin-years-index .deleteBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Tanév törlése.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            var id = $(this).data('id');
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteYear_' + id).trigger('click');
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
        $('#year_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter){
                var year_search_text = $('#year_search_text').val().toLowerCase();
                
                if(year_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_year_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_year_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_year_search_text != -1 && last_index_of_year_search_text != -1){
                    var year_searchable_text = rowData[0].substring(first_index_of_year_search_text + '<searchable-text>'.length, last_index_of_year_search_text);
                    if(year_searchable_text.toLowerCase().indexOf(year_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>