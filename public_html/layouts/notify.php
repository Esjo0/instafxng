<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23/08/2018
 * Time: 3:51 PM
 */
$query = "SELECT content FROM advert_div WHERE status = 1 ";
$result = $db_handle->runQuery($query);
$notification = $db_handle->fetchAssoc($result);
?>
<?php if(!empty($notification)){
foreach($notification AS $row){extract($row)?>
    <div id="advert" style="z-index: 5; max-width:500px; display: none; position: fixed; bottom: 10px; right: 10px;" class="alert alert-info pull-right fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $content;?>
    </div>
    <script>
        //poopup after 5sec
        setTimeout(advert, 5000);
        function advert() { document.getElementById('advert').style.display = 'block'; }
        //closes after 2mins
        setTimeout(advert_hide, 150000);
        function advert_hide() { document.getElementById('advert').style.display = 'none'; }
    </script>
<?php  }}?>

