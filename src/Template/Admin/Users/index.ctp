<div class="container admin-internalConsultants-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Felhasználói fiókok') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data_table">
                            <thead>
                                <tr>
                                    <th><?= __('Felhasználónév') ?></th>
                                    <th><?= __('Email') ?></th>
                                    <th><?= __('Felhasználó típusa') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= $this->Form->control('username_search_text', ['id' => 'username_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('email_search_text', ['id' => 'email_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('group_search_text', ['id' => 'group_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user){ ?>
                                    <tr class="users" data-id="<?= $user->id ?>" style="cursor: pointer">
                                        <td><?= '<searchable-text>' . h($user->username) . '</searchable-text>' ?></td>
                                        <td><?= '<searchable-text>' . h($user->email) . '</searchable-text>' ?></td>
                                        <td><?= $user->has('group') ? '<searchable-text>' . h($user->group->name) . '</searchable-text>' : '' ?></td>
                                    </tr>
                                <?php } ?>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <?= $this->Html->link(__('Új felhasználói fiók hozzáadása'), ['action' => 'add'], ['class' => 'btn btn-outline-secondary btn-block border-radius-45px']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#users_menu_item').addClass('active');
        $('#user_accounts_index_menu_item').addClass('active');
        
        //Táblázat sorára kattintáskor az adott téma részleteire ugrás
        $('.users').on('click', function(){
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
        $('#username_search_text, #email_search_text, #group_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var username_search_text = $('#username_search_text').val().toLowerCase();
                var email_search_text = $('#email_search_text').val().toLowerCase();
                var group_search_text = $('#group_search_text').val().toLowerCase();
                
                if(username_search_text == '' && email_search_text == '' && group_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_email_search_text = rowData[0].indexOf('<searchable-text>');
                var last_index_of_email_search_text = rowData[0].indexOf('</searchable-text>');
                if(first_index_of_email_search_text != -1 && last_index_of_username_search_text != -1){
                    var email_searchable_text = rowData[0].substring(first_index_of_email_search_text + '<searchable-text>'.length, last_index_of_email_search_text);
                    if(email_searchable_text.toLowerCase().indexOf(email_search_text) == -1) ok = false;
                }
                
                var first_index_of_username_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_username_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_username_search_text != -1 && last_index_of_username_search_text != -1){
                    var username_searchable_text = rowData[1].substring(first_index_of_username_search_text + '<searchable-text>'.length, last_index_of_username_search_text);
                    if(username_searchable_text.toLowerCase().indexOf(username_search_text) == -1) ok = false;
                }
                
                var first_index_of_group_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_group_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_group_search_text != -1 && last_index_of_group_search_text != -1){
                    var group_searchable_text = rowData[2].substring(first_index_of_group_search_text + '<searchable-text>'.length, last_index_of_group_search_text);
                    if(group_searchable_text.toLowerCase().indexOf(group_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    });
</script>