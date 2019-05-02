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
                        <table class="table table-bordered table-hover" id="data_table">
                            <thead>
                                <th><?= __('Téma címe') ?></th>
                                <th><?= __('Belső konzulens') ?></th>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('title_search_text', ['id' => 'title_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('internal_consultant_search_text', ['id' => 'internal_consultant_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($offeredTopics as $offeredTopic){ ?>
                                    <tr class="offeredTopics" data-id="<?= $offeredTopic->id ?>" style="cursor: pointer">
                                        <td style="vertical-align: middle"><?= '<searchable-text>' . h($offeredTopic->title) . '</searchable-text>' ?></td>
                                        <td style="vertical-align: middle"><?= $offeredTopic->has('internal_consultant') ? '<searchable-text>' . h($offeredTopic->internal_consultant->name) . '</searchable-text>' : '-' ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
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
        
        //Táblázat sorára kattintáskor az adott téma részleteire ugrás
        $('.offeredTopics').on('click', function(){
            var id = $(this).data('id');
            location.href = '<?= $this->Url->build(['action' => 'details'], true) ?>' + '/' + id;
        });
        
        //Törléskor confirmation modal a megerősítésre
        $('.deleteBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
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
        $('#title_search_text, #internal_consultant_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var title_search_text = $('#title_search_text').val().toLowerCase();
                var internal_consultant_search_text = $('#internal_consultant_search_text').val().toLowerCase();
                
                if(title_search_text == '' && internal_consultant_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_title_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_title_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_title_search_text != -1 && last_index_of_title_search_text != -1){
                    var title_searchable_text = rowData[0].substring(first_index_of_title_search_text + '<searchable-text>'.length, last_index_of_title_search_text);
                    if(title_searchable_text.toLowerCase().indexOf(title_search_text) == -1) ok = false;
                }
                
                var first_index_of_internal_consultant_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_internal_consultant_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_internal_consultant_search_text != -1 && last_index_of_internal_consultant_search_text != -1){
                    var status_searchable_text = rowData[1].substring(first_index_of_internal_consultant_search_text + '<searchable-text>'.length, last_index_of_internal_consultant_search_text);
                    if(status_searchable_text.toLowerCase().indexOf(internal_consultant_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>