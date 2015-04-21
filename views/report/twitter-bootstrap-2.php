<?php $types = array('error', 'success', 'warning', 'info'); ?>

<div class="alert alert-<?php echo $types[$code]; ?> alert-block fade in">

    <button type="button" class="close" data-dismiss="alert">×</button>
    
    <?php if(isset($time)){ ?>
    <strong><?php echo $time.' : '; ?></strong>
    <?php } 

    if(isset($message)) echo $message; ?>

</div>