<div class="container admin-internalConsultants-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Belső konzulensek') ?></h4>
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
                                    <th><?= __('Beosztás') ?></th>
                                    <th><?= __('Tanszék') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('name_search_text', ['id' => 'name_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('position_search_text', ['id' => 'position_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('department_search_text', ['id' => 'department_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($internalConsultants as $internalConsultant){ ?>
                                    <tr class="internalConsultants" data-id="<?= $internalConsultant->id ?>" style="cursor: pointer">
                                        <td><?= '<searchable-text>' . h($internalConsultant->name) . '</searchable-text>' ?></td>
                                        <td><?= $internalConsultant->has('internal_consultant_position') ? '<searchable-text>' . h($internalConsultant->internal_consultant_position->name) . '</searchable-text>' : '' ?></td>
                                        <td><?= $internalConsultant->has('department') ? '<searchable-text>' . h($internalConsultant->department->name) . '</searchable-text>' : '' ?></td>
                                    </tr>
                                <?php } ?>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <?= $this->Html->link(__('Új belső konzulens hozzáadása'), ['action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#users_menu_item').addClass('active');
        $('#internal_consultants_index_menu_item').addClass('active');
        
        //Táblázat sorára kattintáskor az adott téma részleteire ugrás
        $('.internalConsultants').on('click', function(){
            var id = $(this).data('id');
            location.href = '<?= $this->Url->build(['action' => 'details'], true) ?>' + '/' + id;
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
        $('#name_search_text, #position_search_text, #department_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var name_search_text = $('#name_search_text').val().toLowerCase();
                var position_search_text = $('#position_search_text').val().toLowerCase();
                var department_search_text = $('#department_search_text').val().toLowerCase();
                
                if(name_search_text == '' && position_search_text == '' && department_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_name_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_name_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_name_search_text != -1 && last_index_of_name_search_text != -1){
                    var name_searchable_text = rowData[0].substring(first_index_of_name_search_text + '<searchable-text>'.length, last_index_of_name_search_text);
                    if(name_searchable_text.toLowerCase().indexOf(name_search_text) == -1) ok = false;
                }
                
                var first_index_of_position_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_position_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_position_search_text != -1 && last_index_of_position_search_text != -1){
                    var position_searchable_text = rowData[1].substring(first_index_of_position_search_text + '<searchable-text>'.length, last_index_of_position_search_text);
                    if(position_searchable_text.toLowerCase().indexOf(position_search_text) == -1) ok = false;
                }
                
                var first_index_of_department_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_department_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_department_search_text != -1 && last_index_of_department_search_text != -1){
                    var department_searchable_text = rowData[2].substring(first_index_of_department_search_text + '<searchable-text>'.length, last_index_of_department_search_text);
                    if(department_searchable_text.toLowerCase().indexOf(department_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>