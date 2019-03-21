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
                        <table class="table table-hover notifications-table">
                            <thead>
                                <tr>
                                    <th style="width: 37px"><?= $this->Form->control('check_all', ['type' => 'checkbox', 'label' => false, 'id' => 'check_all']) ?></th>
                                    <th><?= __('Tárgy') ?></th>
                                    <th style="width: 100px"><?= __('Érkezés') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($notifications as $i => $notification){ ?>
                                    <tr class="<?= $notification->unread === true ? 'unread-message' : '' ?> notification" data-id="<?= $notification->id ?>">
                                        <td style="vertical-align: middle;"><?= $this->Form->control("notification[$i]", ['type' => 'checkbox', 'label' => false, 'class' => 'notification-checkbox', 'data-id' => $notification->id]) ?></td>
                                        <td style="vertical-align: middle">
                                            <?= ($notification->unread === true ? '<sup class="unread-sup">' . __('Új') . '&nbsp;</sup>' : '') . h($notification->subject) ?>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <?= empty($notification->created) ? '' : $this->Time->format($notification->created, 'yyyy.MM.dd HH:mm:ss') ?>
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
            });
        });
        
        /**
         * Összes kijelölése, illetve összes "nem kijelölése"
         */
        $('#check_all').on('change', function(e){
            var checked = $(this).is(':checked');
        
            $('.notification-checkbox').each(function(){
                $(this).prop('checked', checked);
                var id = $(this).data('id');
                //Először eltávolítjuk a form-ból
                $('#notification_input_' + id).remove();
                //Beletesszük a formba, ha összes kijelölés van
                if(checked) $('#deleteNotificationsForm').append('<input type="hidden" name="notifications_ids[]" value="' + id + '" id="notification_input_' + id + '">');
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
    });
</script>