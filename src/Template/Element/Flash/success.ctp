<div class="col-12 flash-success" style="margin: 10px 0">
    <div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <p><i class="icon fa fa-check"></i> <?= h($message) ?></p>
    </div>
</div>
<script>
    $(function(){
        setTimeout(function(){
            $('.flash-success').fadeOut(1000);
        }, 4000);
    });
</script>