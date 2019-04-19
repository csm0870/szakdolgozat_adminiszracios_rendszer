<div class="container admin-documents-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Dokumentumok') ?></h4>
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
                                    <th><?= __('Módosítva') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('name_search_text', ['id' => 'name_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('modified_search_text', ['id' => 'modified_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                 </tr>
                            </thead>
                            <tbody>
                                <?php foreach($documents as $document){ ?>
                                    <tr class="documents" data-id="<?= $document->id ?>" style="cursor: pointer">
                                        <td><?= '<searchable-text>' . h($document->name) . '</searchable-text>' ?></td>
                                        <td><?= empty($document->modified) ? '' : ('<searchable-text>' . $this->Time->format($document->modified, 'yyyy-MM-dd HH:mm:ss') . '</searchable-text>') ?></td>
                                    </tr>
                                <?php } ?>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dokumentum szerkesztése modal -->
<div class="modal fade" id="documentEditModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="document_edit_container">

                </div>
            </div>
        </div>
  </div>
</div>
<script>
    $(function(){
        $('#others_menu_item').addClass('active');
        $('#documents_index_menu_item').addClass('active');
        
        //Táblázat sorára kattintáskor az adott téma részleteire ugrás
        $('.documents').on('click', function(){
            var id = $(this).data('id');
            
            //Szerkesztési oldal lekérése
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'edit'], true) ?>' + '/' + id,
                cache: false
            })
            .done(function(response){
                $('#document_edit_container').html(response.content);
                $('#documentEditModal').modal('show');
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
        $('#name_search_text, #modified_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var name_search_text = $('#name_search_text').val().toLowerCase();
                var modified_search_text = $('#modified_search_text').val().toLowerCase();
                
                if(name_search_text == '' && modified_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_name_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_name_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_name_search_text != -1 && last_index_of_name_search_text != -1){
                    var name_searchable_text = rowData[0].substring(first_index_of_name_search_text + '<searchable-text>'.length, last_index_of_name_search_text);
                    if(name_searchable_text.toLowerCase().indexOf(name_search_text) == -1) ok = false;
                }
                
                var first_index_of_modified_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_modified_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_modified_search_text != -1 && last_index_of_modified_search_text != -1){
                    var modified_searchable_text = rowData[1].substring(first_index_of_modified_search_text + '<searchable-text>'.length, last_index_of_modified_search_text);
                    if(modified_searchable_text.toLowerCase().indexOf(modified_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>