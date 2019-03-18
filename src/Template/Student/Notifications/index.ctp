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
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th><?= __('Tárgy') ?></th>
                                    <th style="width: 100px"><?= __('Érkezés') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($notifications as $notification){ ?>
                                    <tr class="<?= $notification->unread === true ? 'unread-message' : '' ?> notification" data-id="<?= $notification->id ?>">
                                        <td style="vertical-align: middle">
                                            <?= ($notification->unread === true ? '<sup class="unread">' . __('Új') . '&nbsp;</sup>' : '') . h($notification->subject) ?>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <?= empty($notification->created) ? '' : $this->Time->format($notification->created, 'yyyy.MM.dd HH:mm:ss') ?>
                                        </td>
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
<!-- Értesítés részletei modal -->
<div class="modal fade" id="notificationDetailsModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="notification-container">
                    <div class="notification-header">
                        <?= __('Értesítés') ?>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
        $('#notifications_menu_item').addClass('active');
        
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
                console.log(response);
                $('#notificationDetailsModal .notification-subject').text(response.subject);
                $('#notificationDetailsModal .notification-message-text').html(response.message);
                $('#notificationDetailsModal .notification-date').text(response.date);

                $this.removeClass('unread-message');
                $this.find('.unread').remove();
                $this.data('unread', 0);
                $('#notificationDetailsModal').modal('show');
            });
        });
    });
</script>