<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

//delete notification schedule
if(isset($_POST['delete'])) {
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $query = "DELETE FROM advert_div WHERE advert_id = $advert_id";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Deleted this notification";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Add new notification schedule
if(isset($_POST['create'])){
    $title = $db_handle->sanitizePost($_POST['title']);
    $content = $db_handle->sanitizePost($_POST['content']);

    $query = "INSERT into advert_div(title,content,status,created_by) VALUES('$title','$content',2,'$admin_code')";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Created a new Notification";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update notification
if(isset($_POST['update'])){
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $content = $db_handle->sanitizePost($_POST['content']);

    $query = "UPDATE advert_div SET content = '$content' WHERE advert_id = '$advert_id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Updates this notification";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update notification status to display
if(isset($_POST['status_display'])){
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $query = "UPDATE advert_div SET status = 1 WHERE advert_id = '$advert_id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Updates this notification view status";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update notification status to hidden
if(isset($_POST['status_hide'])){
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $query = "UPDATE advert_div SET status = 2 WHERE advert_id = '$advert_id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Updates this notification view status";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//select previous notification schedules
$query = "SELECT * FROM advert_div";
$numrows = $db_handle->numRows($query);
$rowsperpage = 10;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int)$_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) {
    $currentpage = $totalpages;
}
if ($currentpage < 1) {
    $currentpage = 1;
}

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {
    $prespagehigh = $numrows;
}

$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$updates = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin</title>
    <meta name="title" content="Instaforex Nigeria | Admin" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <?php require_once 'hr_attendance_system.php'; ?>
    <script src="tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinyMCE.init({
            selector: "textarea#content,textarea#content2",
            height: 50,
            theme: "modern",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
            image_advtab: true,
            external_filemanager_path: "../filemanager/",
            filemanager_title: "Instafxng Filemanager",
            browser_spellcheck: true,
//                external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}

        });
    </script>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once 'layouts/sidebar.php'; ?>
        </div>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-9 col-lg-9">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>Notification Schedule</strong></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-md-8">

                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="inputHeading3" class="col-sm-2 col-form-label">Notification Title</label>
                                        <div class="col-sm-10">
                                            <input name="title" type="text" class="form-control" id="forum_title" placeholder="Enter Forum title">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSubtile3" class="col-sm-2 col-form-label">Content</label>
                                        <div class="col-sm-10">
                                            <textarea name="content" class="form-control" id="content"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <center><button name="create" type="submit" class="btn btn-primary">Create</button></center>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><strong>Previous Notifications</strong></h5>
                                        <?php if(isset($updates) && !empty($updates)):?>
                                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>View Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($updates as $row) {
                                                ?>
                                                <tr>
                                                    <td data-target="#update<?php echo $row['advert_id']; ?>" data-toggle="modal"><?php echo $row['title']; ?></td>
                                                    <td>
                                                        <form data-toggle="validator"
                                                              class="form-vertical" role="form"
                                                              method="post" action="">
                                                            <input name="advert_id"
                                                                   class="form-control" type="hidden"
                                                                   value="<?php echo $row['advert_id']; ?>" >
                                                            <?php if($row['status'] == 2){?>
                                                            <button type="submit" name="status_display" class="btn btn-success" >
                                                                <span class="glyphicon glyphicon-eye-open"></span></button>
                                                        <?php }elseif($row['status'] == 1){?>
                                                            <button type="submit" name="status_hide" class="btn btn-success" >
                                                                <span class="glyphicon glyphicon-eye-close"></span></button>
                                                        <?php }?></form>
                                                    </td>

                                                    <!--Modal - confirmation boxes-->
                                                    <div id="update<?php echo $row['advert_id']; ?>" tabindex="-1" role="dialog"
                                                         aria-hidden="true" class="modal fade ">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal"
                                                                            aria-hidden="true"
                                                                            class="close">&times;
                                                                    </button>
                                                                    <h4 class="modal-title">Update notification <?php echo ($row['title']); ?></h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div>
                                                                        <div class="section-tint super-shadow">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                   <center> <p style="align: center;">Created on <?php echo datetime_to_text($row['created']); ?> by <?php echo $admin_object->get_admin_name_by_code($row['created_by']); ?> </p></center>
                                                                                    </div>
                                                                                <div class="col-md-12">
                                                                                    <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                                                                        <input name="advert_id"
                                                                                               class="form-control" type="hidden"
                                                                                               value="<?php echo $row['advert_id']; ?>" >
                                                                                        <div class="form-group row">
                                                                                            <label for="inputSubtile3" class="col-sm-2 col-form-label">Content</label>
                                                                                            <div class="col-sm-10">
                                                                                                <textarea name="content" id="content2" class="form-control"><?php echo ($row['content']); ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <div class="form-group row">
                                                                                <div class="col-sm-4">
                                                                                    <button name="update" type="submit" class="btn btn-primary">Update</button>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <button name="delete" type="submit" class="btn btn-primary">Delete</button>
                                                                                </div>

                                                                                <button type="submit" name="close"
                                                                                        onClick="window.close();"
                                                                                        data-dismiss="modal"
                                                                                        class="btn btn-danger">Close!
                                                                                </button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    }
                                                    ?></tr>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                        <?php if(empty($updates)){echo "No Previous Notification";}else{ require 'layouts/pagination_links.php'; }?>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>
            </div>
        </div>
    </div>
    <?php require_once 'layouts/footer.php'; ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>