<?php if(isset($message_success)): ?>
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?php echo $message_success; ?>
</div>
<?php endif ?>
<?php if(isset($message_error)): ?>
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Oops!</strong> <?php echo $message_error; ?>
</div>
<?php endif ?>
<?php if(isset($message_info)): ?>
    <div class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $message_info; ?>
    </div>
<?php endif;
