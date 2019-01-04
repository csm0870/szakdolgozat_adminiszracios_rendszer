<div class="col-12 flash-error" style="margin: 10px 0">
    <div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <p><i class="icon fa fa-ban"></i> <?= h($message) ?></p>
    </div>
</div>
<script>
    $(function(){
        setTimeout(function(){
            $('.flash-error').fadeOut(1000);
        }, 4000);
    });
</script>