<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23/08/2018
 * Time: 3:51 PM
 */
$query = "SELECT content FROM advert_div WHERE status = 1 LIMIT 1";
$result = $db_handle->runQuery($query);
$notification = $db_handle->fetchAssoc($result);
?>
<?php if(!empty($notification)){ ?>
    <div id="advert" style="z-index: 5; max-width:500px; display: none; position: fixed; bottom: 10px; right: 10px;" class="alert alert-info pull-right fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php  foreach($notification AS $row){extract($row)?>
                <?php echo $content;?>
        <?php }?>
    </div>
<?php }?>

