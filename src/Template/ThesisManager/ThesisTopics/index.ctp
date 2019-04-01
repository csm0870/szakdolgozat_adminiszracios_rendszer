<div class="container thesisTopics-index topicManager-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Szakdolgozatok kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-index-body">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover topics-table" id="data_table">
                                <thead>
                                    <tr>
                                        <th><?= __('Téma címe') ?></th>
                                        <th><?= __('Belső konzulens') ?></th>
                                        <th><?= __('Hallgató') ?></th>
                                        <th><?= __('Adatok felvitele a Neptunba') ?></th>
                                        <th><?= __('Állapot') ?></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th><?= $this->Form->control('title_search_text', ['id' => 'title_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('internal_consultant_search_text', ['id' => 'internal_consultant_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('student_search_text', ['id' => 'student_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('data_applyed_search_text', ['id' => 'data_applyed_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('status_search_text', ['id' => 'status_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($thesisTopics as $thesisTopic){ ?>
                                        <tr class="thesisTopics" data-id="<?= $thesisTopic->id ?>" style="cursor: pointer">
                                            <td>
                                                <?= '<searchable-text>' . h($thesisTopic->title) . '</searchable-text>' ?>
                                            </td>
                                            <td><?= $thesisTopic->has('internal_consultant') ? '<searchable-text>' . h($thesisTopic->internal_consultant->name) . '</searchable-text>' : '' ?></td>
                                            <td><?= $thesisTopic->has('student') ? ('<searchable-text>' . h($thesisTopic->student->name) . (empty($thesisTopic->student->neptun) ? '' : ('<br/>(' . h($thesisTopic->student->neptun) . ')')) . '</searchable-text>') : '' ?></td>
                                            <td><?= '<searchable-text>' . ($thesisTopic->accepted_thesis_data_applyed_to_neptun === true ? __('Az adatok felvitele megtörtént.') : __('Az adatok felvitele még nem történt meg.')) . '</searchable-text>' ?></td>
                                            <td>
                                                <?= $thesisTopic->has('thesis_topic_status') ? '<searchable-text>' . h($thesisTopic->thesis_topic_status->name) . '</searchable-text>' : '' ?>
                                            </td>
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
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        //Táblázat sorára kattintáskor az adott téma részleteire ugrás
        $('.thesisTopics').on('click', function(){
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
        $('#title_search_text, #internal_consultant_search_text, #student_search_text, #status_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var title_search_text = $('#title_search_text').val().toLowerCase();
                var internal_consultant_search_text = $('#internal_consultant_search_text').val().toLowerCase();
                var student_search_text = $('#student_search_text').val().toLowerCase();
                var data_applyed_search_text = $('#student_search_text').val().toLowerCase();
                var status_search_text = $('#status_search_text').val().toLowerCase();
                
                if(title_search_text == '' && internal_consultant_search_text == '' && student_search_text == '' && data_applyed_search_text == '' && status_search_text == '') return true;
                
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
                    var internal_consultant_searchable_text = rowData[1].substring(first_index_of_internal_consultant_search_text + '<searchable-text>'.length, last_index_of_internal_consultant_search_text);
                    if(internal_consultant_searchable_text.toLowerCase().indexOf(internal_consultant_search_text) == -1) ok = false;
                }
                
                var first_index_of_student_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_student_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_student_search_text != -1 && last_index_of_title_search_text != -1){
                    var student_searchable_text = rowData[2].substring(first_index_of_student_search_text + '<searchable-text>'.length, last_index_of_student_search_text);
                    if(student_searchable_text.toLowerCase().indexOf(student_search_text) == -1) ok = false;
                }
                
                var first_index_of_data_applyed_search_text = rowData[3].indexOf('<searchable-text>');
                var last_index_of_data_applyed_search_text = rowData[3].indexOf('</searchable-text>');
                if(first_index_of_data_applyed_search_text != -1 && last_index_of_data_applyed_search_text != -1){
                    var data_applyed_searchable_text = rowData[3].substring(first_index_of_data_applyed_search_text + '<searchable-text>'.length, last_index_of_data_applyed_search_text);
                    if(data_applyed_searchable_text.toLowerCase().indexOf(data_applyed_search_text) == -1) ok = false;
                }
                
                var first_index_of_status_search_text = rowData[4].indexOf('<searchable-text>');
                var last_index_of_status_search_text = rowData[4].indexOf('</searchable-text>');
                if(first_index_of_status_search_text != -1 && last_index_of_status_search_text != -1){
                    var status_searchable_text = rowData[4].substring(first_index_of_status_search_text + '<searchable-text>'.length, last_index_of_status_search_text);
                    if(status_searchable_text.toLowerCase().indexOf(status_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>