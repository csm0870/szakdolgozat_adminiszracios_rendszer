<div class="container notifications-index">
    <div class="row">
        <div class="col-12 page-title">
            <h4><?= __('Értesítések') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover notifications-table" id="data_table">
                            <thead>
                                <tr>
                                    <th style="width: 37px"><?= $this->Form->control('check_all', ['type' => 'checkbox', 'label' => false, 'id' => 'check_all']) ?></th>
                                    <th><?= __('Tárgy') ?></th>
                                    <th style="width: 100px"><?= __('Érkezés') ?></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?= $this->Form->control('subject_search_text', ['id' => 'subject_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                    <th><?= $this->Form->control('date_search_text', ['id' => 'date_search_text', 'type' => 'text', 'placeholder' => __('Keresés...'), 'label' => false]) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($notifications as $i => $notification){ ?>
                                    <tr class="<?= $notification->unread === true ? 'unread-message' : '' ?> notification" data-id="<?= $notification->id ?>">
                                        <td style="vertical-align: middle;"><?= $this->Form->control("notification[$i]", ['type' => 'checkbox', 'label' => false, 'id' => 'notification_checkbox_' . $notification->id,  'hiddenField' => false, 'class' => 'notification-checkbox', 'data-id' => $notification->id,
                                                                                                                          'templates' => ['inputContainer' => '{{content}}']]) ?></td>
                                        <td style="vertical-align: middle">
                                            <?= ($notification->unread === true ? '<sup class="unread-sup">' . __('Új') . '&nbsp;</sup>' : '') . '<searchable-text>' . h($notification->subject) . '</searchable-text>'?>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <?= empty($notification->created) ? '' : '<searchable-text>' . $this->Time->format($notification->created, 'yyyy.MM.dd. HH:mm:ss') . '</searchable-text>' ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($notifications->count() > 0){ ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="3"><?= $this->Html->link(__('Kijelöltek törlése'), '#',['class' => 'btn btn-outline-danger btn-sm', 'id' => 'deleteCheckedNotifications']) ?></th>
                                    </tr>
                                </tfoot>
                            <?php } ?>
                        </table>
                    </div>
                    <?php
                        if($notifications->count() > 0){
                            echo $this->Form->create(null, ['id' => 'deleteNotificationsForm', 'class' => 'd-none', 'url' => ['action' => 'delete']]);
                            echo $this->Form->end();
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Értesítés részletei modal -->
<div class="modal fade" id="notificationDetailsModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="notification-container">
                    <div class="notification-header">
                        <?= __('Értesítés') ?>
                        <button type="button" class=""  data-dismiss="modal">&nbsp;<i class="fas fa-times"></i></button>
                        <button type="button" class="d-none" id="minimize_modal"><i class="fas fa-compress-arrows-alt"></i></button>
                        <button type="button" class="" id="fullscreen_modal"><i class="fas fa-arrows-alt"></i></button>
                    </div>
                    <div class="notification-body">
                        <div class="notification-info mb-2">
                            <span class="label"><?= __('Tárgy') . ':' ?></span>&nbsp;<span class="notification-subject"></span><br/>
                            <span class="label"><?= __('Érkezés ideje') . ':' ?></span>&nbsp;<span class="notification-date"></span>
                        </div>
                        <div class="notification-message">
                            <span class="label"><?= __('Üzenet') . ':' ?></span><br/>
                            <span class="notification-message-text"></span>
                        </div>
                    </div>
                    <div class="notification-footer text-right">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"><?= __('Bezárás') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#notifications_index_menu_item').addClass('active');
        
        /**
         * Értesítés részleteinek betöltése a modalba, illetve, ha olvasatlan, akkor annak olvasottá tétele
         */
        $('.notification').on('click', function(){
            var $this = $(this);
            var id = $this.data('id');
            
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'getNotification'], true) ?>' + "/" + id + '.json',
                cache: false
            }).done(function(response){
                $('#notificationDetailsModal .notification-subject').text(response.subject);
                $('#notificationDetailsModal .notification-message-text').html(response.message);
                $('#notificationDetailsModal .notification-date').text(response.date);

                $this.removeClass('unread-message');
                $this.find('.unread-sup').remove();
                $this.data('unread', 0);
                $('#notificationDetailsModal').modal('show');
                
                if(response.has_unread == false){ //Ha már nincs több olvasatlan értesítés
                    $('#notifications_index_menu_item').find('.unread-sup').remove();
                }
            });
        });
        
        /**
         * Értesítés checkboxra kattintáskor, ha kipipáljuk, akkor a törlendő értesítések közé kerül, ha kivesszük a pipát, akkor kikerül a törlendők közül
         */
        $('.notification-checkbox').on('change', function(e){
            var id = $(this).data('id');
            
            if($(this).is(':checked')) $('#deleteNotificationsForm').append('<input type="hidden" name="notifications_ids[]" value="' + id + '" id="notification_input_' + id + '">');
            else $('#notification_input_' + id).remove();
        });
        
        //Checkboxra kattintáskor ne nyiljon meg az értesítés részletek popup
        $('.notification-checkbox').on('click', function(e){
            e.stopPropagation();
        });
        
        //Teljes képernyős modal
        $('#fullscreen_modal').on('click', function(){
            $('#notificationDetailsModal').addClass('fullscreen-modal');
            $('#minimize_modal').removeClass('d-none');
            $('#fullscreen_modal').addClass('d-none');
        });
        
        //Modal eredetire
        $('#minimize_modal').on('click', function(){
            $('#notificationDetailsModal').removeClass('fullscreen-modal');
            $('#minimize_modal').addClass('d-none');
            $('#fullscreen_modal').removeClass('d-none');
        });
        
        //Modal zárásakor a modal az eredetire állítása
        $('#notificationDetailsModal').on('hidden.bs.modal', function (e) {
            $('#notificationDetailsModal').removeClass('fullscreen-modal');
            $('#minimize_modal').addClass('d-none');
            $('#fullscreen_modal').removeClass('d-none');
        });
        
        <?php if($notifications->count() > 0){ ?>
            /**
            * Confirmation modal megnyitása törlés előtt
            */
            $('#deleteCheckedNotifications').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Értesítések törlése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#deleteNotificationsForm').trigger('submit');
                });
            });
        <?php } ?>
        
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
                         "order": [[ 2, "desc" ]],
                        drawCallback: function(settings){
                            //Pagination elrejtése, ha nincs rá szükség
                            var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                            pagination.toggle(this.api().page.info().pages > 1);
                        },
                         "columnDefs": [ { "orderable": false, "targets": 0 } ]
                      });
        
        //Ha a kereső mezőkbe írunk, akkor újra "rajzoljuk" a tálbázatot
        $('#subject_search_text, #date_search_text').on('keyup', function(){
            table.draw();
        });
        
        //Táblázat sorainak szűrése a keresendő szövegek alapján
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                var subject_search_text = $('#subject_search_text').val().toLowerCase();
                var date_search_text = $('#date_search_text').val().toLowerCase();
                
                if(subject_search_text == '' && date_search_text == '') return true;
                
                var ok = true;
                
                var first_index_of_subject_search_text = rowData[1].indexOf('<searchable-text>');
                var last_index_of_subject_search_text = rowData[1].indexOf('</searchable-text>');
                if(first_index_of_subject_search_text != -1 && last_index_of_subject_search_text != -1){
                    var title_searchable_text = rowData[1].substring(first_index_of_subject_search_text + '<searchable-text>'.length, last_index_of_subject_search_text);
                    if(title_searchable_text.toLowerCase().indexOf(subject_search_text) == -1) ok = false;
                }
                
                var first_index_of_date_search_text = rowData[2].indexOf('<searchable-text>');
                var last_index_of_date_search_text = rowData[2].indexOf('</searchable-text>');
                if(first_index_of_date_search_text != -1 && last_index_of_date_search_text != -1){
                    var date_searchable_text = rowData[2].substring(first_index_of_date_search_text + '<searchable-text>'.length, last_index_of_date_search_text);
                    if(date_searchable_text.toLowerCase().indexOf(date_search_text) == -1) ok = false;
                }
                
                return ok;
            }
        );
    
         /**
         * Összes kijelölése, illetve összes "nem kijelölése", csak azoknak a kijelölése, amelyek a megjelenített oldalon vannak
         */
        $('#check_all').on('change', function(e){
            var checked = $(this).is(':checked');
            var displayed_rows = table.rows( {page:'current'} ).data();
            
            for(var i = 0; i< displayed_rows.length; i++){
                var id = $(displayed_rows[i][0]).data('id');
                $('#notification_checkbox_' + id).prop('checked', checked);
                //Először eltávolítjuk a form-ból
                $('#notification_input_' + id).remove();
                //Beletesszük a formba, ha összes kijelölés van
                if(checked) $('#deleteNotificationsForm').append('<input type="hidden" name="notifications_ids[]" value="' + id + '" id="notification_input_' + id + '">');
            }
        });
        
        /**
         * Táblázat chekcboxait reseteljük és űrítjuk a törlendő értesítéseket a form-ból
         *
         * @return {undefined}         */
        function resetNotificationCheckboxes(){
            $('#check_all').prop('checked', false);
            $('.notification-checkbox').each(function(){
                var $this = $(this);
                $this.prop('checked', false);
                var id = $this.data('id');
                //Először eltávolítjuk a form-ból
                $('#notification_input_' + id).remove()
            });
        }
        
        //Táblázat oldalváltásakor, adat keresésekor, sorbarendezésekor a chekcboxokat reseteljük és űrítjuk a törlendő értesítéseket a form-ból
        $('#data_table').on({'page.dt' : resetNotificationCheckboxes,
                             'order.dt' : resetNotificationCheckboxes,
                             'search.dt' : resetNotificationCheckboxes});
    });
</script>