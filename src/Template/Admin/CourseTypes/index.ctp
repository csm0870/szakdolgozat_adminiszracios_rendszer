<div class="container admin-courseTypes-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Képzéstípusok') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data_table">
                            <thead>
                                <tr>
                                    <th><?= __('Név') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('name_search_text', ['id' => 'name_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($courseTypes as $courseType){ ?>
                                    <tr>
                                        <td><?= '<searchable-text>' . h($courseType->name) . '</searchable-text>' ?></td>
                                        <td class="text-center">
                                            <?php
                                                echo $this->Html->link('<i class="fas fa-edit fa-lg"></i>', '#', ['class' => 'iconBtn editBtn', 'data-id' => $courseType->id, 'escape' => false, 'title' => __('Szerkesztés')]);
                                                echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $courseType->id]);
                                                echo $this->Form->postLink('', ['action' => 'delete', $courseType->id], ['style' => 'display: none', 'id' => 'deleteCourseType_' . $courseType->id]);
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
                    <?= $this->Html->link(__('Új képzéstípus hozzáadása'), ['action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px add-new-courseType']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Képzéstípus hozzáadása modal -->
<div class="modal fade" id="courseTypeAddModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <div id="courseType_add_container">

                  </div>
              </div>
          </div>
    </div>
</div>
<!-- Képzéstípus szerkesztése modal -->
<div class="modal fade" id="courseTypeEditModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <div id="courseType_edit_container">

                  </div>
              </div>
          </div>
    </div>
</div>
<script>
    $(function(){
        $('#others_menu_item').addClass('active');
        $('#course_types_index_menu_item').addClass('active');
        
        //Tartalom lekeérése a hozzáadáshoz
        $.ajax({
            url: '<?= $this->Url->build(['action' => 'add'], true) ?>',
            cache: false
        })
        .done(function(response){
            $('#courseType_add_container').html(response.content);
        });

        //Képzéstípus hozzáadása popup megnyitása
        $('.add-new-courseType').on('click', function(e){
            e.preventDefault();
            $('#courseTypeAddModal').modal('show');
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
                $('#courseType_edit_container').html(response.content);
                $('#courseTypeEditModal').modal('show');
            });
        });


        //Törléskor confirmation modal a megerősítésre
        $('.admin-courseTypes-index .deleteBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Kézpéstípus törlése.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

            $('#confirmationModal').modal('show');

            var id = $(this).data('id');
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteCourseType_' + id).trigger('click');
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
        $('#name_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter){
                var name_search_text = $('#name_search_text').val().toLowerCase();
                
                if(name_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_name_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_name_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_name_search_text != -1 && last_index_of_name_search_text != -1){
                    var name_searchable_text = rowData[0].substring(first_index_of_name_search_text + '<searchable-text>'.length, last_index_of_name_search_text);
                    if(name_searchable_text.toLowerCase().indexOf(name_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>