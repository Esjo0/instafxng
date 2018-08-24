<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23/08/2018
 * Time: 3:51 PM
 */
$query = "SELECT content FROM advert_div WHERE status = 1";
$result = $db_handle->runQuery($query);
$notification = $db_handle->fetchAssoc($result);
?>
    <div id="advert" style="background-color: #cb0213; color: white; display: none; position: fixed; bottom: 10px; right: 10px;" class="alert pull-right fade in">
        <?php  foreach($notification AS $row){extract($row)?>
                <?php echo $content;?>
        <?php }?>
    </div>


