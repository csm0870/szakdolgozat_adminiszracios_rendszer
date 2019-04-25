<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4>
                <?= __('Kiírt témák') ?><br/>
                <?php if(!empty($information) && !empty($information->filling_in_topic_form_begin_date) && !empty($information->filling_in_topic_form_end_date)){ ?>
                    <small class="text-muted">
                        <?= __('Témaleadási időszak') . ': ' .
                            $this->Time->format($information->filling_in_topic_form_begin_date, 'yyyy.MM.dd.') . ' - '  .
                            $this->Time->format($information->filling_in_topic_form_end_date, 'yyyy.MM.dd.')
                        ?>
                    </small>
                <?php } ?>
            </h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data_table">
                            <thead>
                                <tr>
                                    <th><?= __('Belső konzulens') ?></th>
                                    <th><?= __('Téma címe') ?></th>
                                    <th><?= __('Állapot') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('internal_consultant_search_text', ['id' => 'internal_consultant_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('title_search_text', ['id' => 'title_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('status_search_text', ['id' => 'status_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($offeredTopics as $offeredTopic){ ?>
                                    <tr  class="offeredTopics" data-id="<?= $offeredTopic->id ?>" style="cursor: pointer">
                                        <td><?= $offeredTopic->has('internal_consultant') ? '<searchable-text>' . h($offeredTopic->internal_consultant->name) . '</searchable-text>' : '' ?></td>
                                        <td><?= '<searchable-text>' . h($offeredTopic->title) . '</searchable-text>' ?></td>
                                        <td>
                                            <?php
                                                echo '<searchable-text>';
                                                    if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->has('student')) echo __('A témára jelentkeztek.');
                                                    else echo __('Nincs jelentkezett hallgató');
                                                echo '</searchable-text>';
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
                    <?= $this->Html->link(__('Új téma hozzáadása'), ['action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#offered_topics_index_menu_item').addClass('active');
        
        //Táblázat sorára kattintáskor az adott téma részleteire ugrás
        $('.offeredTopics').on('click', function(){
            var id = $(this).data('id');
            location.href = '<?= $this->Url->build(['action' => 'details'], true) ?>' + '/' + id;
        });
        
        //Törléskor confirmation modal a megerősítésre
        $('.deleteBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan törlöd?') ?>');
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
        $('#internal_consultant_search_text, #title_search_text, #status_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var internal_consultant_search_text = $('#internal_consultant_search_text').val().toLowerCase();
                var title_search_text = $('#title_search_text').val().toLowerCase();
                var status_search_text = $('#status_search_text').val().toLowerCase();
                
                if(internal_consultant_search_text == '' && title_search_text  == '' && status_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_internal_consultant_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_internal_consultant_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_internal_consultant_search_text != -1 && last_index_of_internal_consultant_search_text != -1){
                    var internal_consultant_searchable_text = rowData[0].substring(first_index_of_internal_consultant_search_text + '<searchable-text>'.length, last_index_of_internal_consultant_search_text);
                    if(internal_consultant_searchable_text.toLowerCase().indexOf(internal_consultant_search_text) == -1) ok = false;
                }
                
                var first_index_of_title_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_title_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_title_search_text != -1 && last_index_of_title_search_text != -1){
                    var title_searchable_text = rowData[1].substring(first_index_of_title_search_text + '<searchable-text>'.length, last_index_of_title_search_text);
                    if(title_searchable_text.toLowerCase().indexOf(title_search_text) == -1) ok = false;
                }
                
                var first_index_of_status_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_status_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_status_search_text != -1 && last_index_of_status_search_text != -1){
                    var status_searchable_text = rowData[2].substring(first_index_of_status_search_text + '<searchable-text>'.length, last_index_of_status_search_text);
                    if(status_searchable_text.toLowerCase().indexOf(status_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>