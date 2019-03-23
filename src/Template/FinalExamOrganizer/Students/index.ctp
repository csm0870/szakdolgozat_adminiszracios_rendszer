<div class="container finalExamOrganizer-students-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Hallgatók') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="data_table">
                                <thead>
                                    <tr>
                                        <th><?= __('Hallgató neve') ?></th>
                                        <th><?= __('Neptun kód') ?></th>
                                        <th><?= __('Szak') ?></th>
                                        <th><?= __('Tagozat') ?></th>
                                        <th><?= __('Képzési szint') ?></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th><?= $this->Form->control('student_name_search_text', ['id' => 'student_name_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('neptun_search_text', ['id' => 'neptun_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('course_search_text', ['id' => 'course_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('course_type_search_text', ['id' => 'course_type_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                        <th><?= $this->Form->control('course_level_search_text', ['id' => 'course_level_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($students as $student){ ?>
                                        <tr>
                                            <td><?= '<searchable-text>' . h($student->name) . '</searchable-text>' ?></td>
                                            <td><?= '<searchable-text>' . h($student->neptun) . '</searchable-text>' ?></td>
                                            <td><?= $student->has('course') ? '<searchable-text>' . h($student->course->name) . '</searchable-text>' : '' ?></td>
                                            <td><?= $student->has('course_type') ? '<searchable-text>' . h($student->course_type->name) . '</searchable-text>' : '' ?></td>
                                            <td><?= $student->has('course_level') ? '<searchable-text>' . h($student->course_level->name) . '</searchable-text>' : '' ?></td>
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
        $('#students_menu_item').addClass('active');
        $('#students_index_menu_item').addClass('active');
        
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
        $('#student_name_search_text, #neptun_search_text, #course_search_text, #course_type_search_text, #course_level_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var student_name_search_text = $('#student_name_search_text').val().toLowerCase();
                var neptun_search_text = $('#neptun_search_text').val().toLowerCase();
                var course_search_text = $('#course_search_text').val().toLowerCase();
                var course_type_search_text = $('#course_type_search_text').val().toLowerCase();
                var course_level_search_text = $('#course_level_search_text').val().toLowerCase();
                
                if(student_name_search_text == '' && neptun_search_text == '' && course_search_text == '' && course_type_search_text == '' && course_level_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_student_name_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_student_name_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_student_name_search_text != -1 && last_index_of_student_name_search_text != -1){
                    var student_name_searchable_text = rowData[0].substring(first_index_of_student_name_search_text + '<searchable-text>'.length, last_index_of_student_name_search_text);
                    if(student_name_searchable_text.toLowerCase().indexOf(student_name_search_text) == -1) ok = false;
                }
                
                var first_index_of_neptun_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_neptun_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_neptun_search_text != -1 && last_index_of_neptun_search_text != -1){
                    var neptun_searchable_text = rowData[1].substring(first_index_of_neptun_search_text + '<searchable-text>'.length, last_index_of_neptun_search_text);
                    if(neptun_searchable_text.toLowerCase().indexOf(neptun_search_text) == -1) ok = false;
                }
                
                var first_index_of_course_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_course_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_course_search_text != -1 && last_index_of_course_search_text != -1){
                    var course_searchable_text = rowData[2].substring(first_index_of_course_search_text + '<searchable-text>'.length, last_index_of_course_search_text);
                    if(course_searchable_text.toLowerCase().indexOf(course_search_text) == -1) ok = false;
                }
                
                var first_index_of_course_type_search_text = rowData[3].indexOf('<searchable-text>');
                var last_index_of_course_type_search_text = rowData[3].indexOf('</searchable-text>');
                if(first_index_of_course_type_search_text != -1 && last_index_of_course_type_search_text != -1){
                    var course_type_searchable_text = rowData[3].substring(first_index_of_course_type_search_text + '<searchable-text>'.length, last_index_of_course_type_search_text);
                    if(course_type_searchable_text.toLowerCase().indexOf(course_type_search_text) == -1) ok = false;
                }
                
                var first_index_of_course_level_search_text = rowData[4].indexOf('<searchable-text>');
                var last_index_of_course_level_search_text = rowData[4].indexOf('</searchable-text>');
                if(first_index_of_course_level_search_text != -1 && last_index_of_course_level_search_text != -1){
                    var course_level_searchable_text = rowData[4].substring(first_index_of_course_level_search_text + '<searchable-text>'.length, last_index_of_course_level_search_text);
                    if(course_level_searchable_text.toLowerCase().indexOf(course_level_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>