<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$project_code = $_GET['project_code'];
$chat_box = $obj_project_management->get_project_messages($project_code);
foreach ($chat_box as $row2)
{
    ?>
    <div id="messageDiv<?php echo $row['project_code']; ?>">
        <li class=" <?php if($row2['author_code'] == $_SESSION['admin_unique_code']){echo 'right';}else{echo 'left';} ?>  clearfix">
            <span class="chat-img pull-left">
                <img src="http://placehold.it/50/55C1E7/fff&text=<?php $string = $admin_object->get_admin_name_by_code($row2['author_code']); echo $string[0]  ?>" alt="User Avatar" class="img-circle" />
            </span>
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font"><?php echo $admin_object->get_admin_name_by_code($row2['author_code']); ?></strong>
                    <small class="pull-right text-muted">
                        <span class="glyphicon glyphicon-time"></span><?php echo datetime_to_text2($row2['created']); ?>
                    </small>
                </div>
                <p>
                    <?php echo $row2['message']; ?>
                </p>
            </div>
        </li>
    </div>
<?php } ?>

