<div class="col-12 flash-default" style="margin: 10px 0">
    <div class="alert alert-info alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <p><i class="icon fa fa-info"></i> <?= h($message) ?></p>
        
    </div>
</div>
<script>
    $(function(){
        setTimeout(function(){
            $('.flash-default').slideUp(1000);
        }, 4000);
    });
</script>