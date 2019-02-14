<div class="container dashboard">
    <div class="row">
        <div class="col-12 welcome-text">
            <?= __('Üdvözöl a Szakdolgozatkezelő rendszer,') . '&nbsp;' . h($logged_in_user->email) ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#dashboard_menu_item').addClass('active');
    });
</script>