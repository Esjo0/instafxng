<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$project_code = $_GET['project_code'];
$chat_box = $obj_project_management->get_project_messages($project_code);
foreach ($chat_box as $row)
{
    ?>
    <div style="word-break:break-all;" id="messageDiv<?php echo $row['project_code']; ?>">
        <div class="row">
            <div class="col-sm-12">
                <div class="transaction-remarks">
                    <span id="trans_remark_author"><?php echo $admin_object->get_admin_name_by_code($row['author_code']); ?></span>
                    <span id="trans_remark"><?php echo $row['message']; ?></span>
                    <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

