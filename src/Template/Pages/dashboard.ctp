<div class="container dashboard">
    <div class="row">
        <div class="col-12 welcome-text">
            <?= __('Üdvözöl a Szakdolgozatkezelő rendszer,') . '&nbsp;' . h($user['name']) ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topic_manager_dashboard_menu_item').addClass('active');
    });
</script>