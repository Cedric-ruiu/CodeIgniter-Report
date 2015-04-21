<?php $types = array('danger', 'success', 'warning', 'info'); ?>

<div class="alert alert-<?php echo $types[$code]; ?> fade in">
    
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    
    <?php if(isset($time)){ ?>
    <strong><?php echo $time.' : '; ?></strong>
    <?php } 
    
    if(isset($message)) echo $message; ?>

</div>