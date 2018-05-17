<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$admin_code = $_SESSION['admin_unique_code'];

$all_admin_member = $admin_object->get_all_admin_member();

if(isset($_POST['new_project']))
{
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = htmlspecialchars_decode(stripslashes(trim($_POST['description'])));
    $deadline = $db_handle->sanitizePost(trim($_POST['deadline']));
    $allowed_admin = $_POST["allowed_admin"];

    for ($i = 0; $i < count($allowed_admin); $i++)
    {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }

    $all_allowed_admin = substr_replace($all_allowed_admin, "", 0, 1);

    $supervisor = $obj_project_management->get_super_admin_by_name('Toye Oyeleke').",".$admin_code;
    $new_project = $obj_project_management->create_new_project($title, $description, $deadline, $all_allowed_admin, $supervisor);
    if ($new_project)
    {
        $message_success = "You have successfully created a new project.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}
if(isset($_POST['edit_project']))
{

    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = htmlspecialchars_decode(stripslashes(trim($_POST['description'])));
    $deadline = $db_handle->sanitizePost(trim($_POST['deadline']));
    $allowed_admin = $_POST["allowed_admin"];

    for ($i = 0; $i < count($allowed_admin); $i++)
    {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }
    $supervisor = $obj_project_management->get_super_admin_by_name('Toye Oyeleke').",".$admin_code;
    $update_project = $obj_project_management->update_project($title, $description, $deadline, $all_allowed_admin, $supervisor, $project_code);
    if ($update_project){$message_success = "You have successfully updated a project.";}
    else{$message_error = "The operation was not successful, please try again.";}
}
if(isset($_POST['new_comment']))
{
    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $comment = htmlspecialchars_decode(stripslashes(trim($_POST['comment'])));
    $new_comment = $obj_project_management->create_new_comment($project_code, $comment, $admin_code);
    if ($new_comment)
    {
        $message_success = "You have successfully added a new comment.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

$query = "SELECT * FROM project_management_projects WHERE status = '1' ORDER BY created DESC  ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int) $_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Project Management</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Project Management" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#description",
                height: 500,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "template paste textcolor colorpicker textpattern "
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "../filemanager/",
                filemanager_title: "Instafxng Filemanager",
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
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>PROJECTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-12 well" style="display: inline-flex">
                                    <div id="search" class="col-sm-8 form-group input-group">
                                        <input placeholder="Enter a project title here..." type="text" class="form-control"/>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <div id="add_new_contact" class="col-sm-4 form-group input-group" >
                                        <span class="text-center input-group-btn">
                                            <button class="btn btn-default" data-toggle="modal" data-target="#add_project" type="button"><i class="glyphicon glyphicon-plus-sign"></i>Add New Project</button>
                                        </span>
                                    </div>
                                    <!-- Modal-- Add New Project -->
                                    <div id="add_project" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Modal content-->
                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Create New Project</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <p><strong>Project Title</strong></p>
                                                                <input type="text" name="title" class="form-control" placeholder="Project Title" required/>
                                                                <hr/>

                                                                <p><strong>Project Description</strong></p>
                                                                <textarea id="description" name="description" placeholder="Project Description" class="form-control" rows="9" required></textarea>
                                                                <hr/>

                                                                <p><strong>Executors</strong></p>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <?php foreach($all_admin_member AS $key) { ?>
                                                                            <div class="col-sm-4">
                                                                                <div class="checkbox">
                                                                                    <label for="">
                                                                                        <input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if($key['full_name'] == 'Toye Oyeleke'){echo "disabled";} ?>/>
                                                                                        <?php echo $key['full_name']; ?>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <hr/>

                                                                <p><strong>Deadline</strong></p>
                                                                <div class="">
                                                                    <div class="input-group date" id="datetimepicker">
                                                                        <input name="deadline" type="text" class="form-control" id="datetimepicker2" required>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                                <script type="text/javascript">
                                                                    $(function ()
                                                                    {
                                                                        $('#datetimepicker, #datetimepicker2').datetimepicker(
                                                                            {
                                                                                format: 'YYYY-MM-DD'
                                                                            });
                                                                    });
                                                                </script>
                                                                <hr/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input name="new_project" type="submit" class="btn btn-success" value="Proceed"/>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-responsive    table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Created</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                            <th>Executors</th>
                                            <th>Announcements</th>
                                            <th> </th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($projects) && !empty($projects))
                                    {
                                    foreach ($projects as $row) { ?>
                                        <?php
                                        $executors = explode("," ,$row['executors']);
                                        $supervisor_code = explode("," ,$row['supervisor_code']);
                                        if (in_array($admin_code, $executors, true) || in_array($admin_code, $supervisor_code, true)):
                                            ?>

                                            <tr>
                                                <td><?php echo $row['title']; ?></td>
                                                <td>
                                                    <button type="button" data-toggle="modal" data-target="#view_project<?php echo $row['project_code']; ?>" class="btn btn-default">Project Description</button>
                                                    <!-- Modal-- View Project Details -->
                                                    <div id="view_project<?php echo $row['project_code']; ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Project Details</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <p>
                                                                                    <strong>Project Title: </strong><?php echo $row['title'];?>
                                                                                </p>
                                                                                <hr/>
                                                                                <p class="text-center">
                                                                                    <strong>Description</strong>
                                                                                </p>
                                                                                <p class="text-justify">
                                                                                    <?php echo $row['description'];?>
                                                                                </p>
                                                                                <hr/>

                                                                                <p>
                                                                                    <strong>Project Deadline: </strong>
                                                                                    <?php echo $row['deadline'];?>
                                                                                </p>
                                                                                <hr/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                <td><?php echo datetime_to_text2($row['deadline']); ?></td>
                                                <td><?php echo project_management_status($row['status']); ?></td>

                                                    <?php
                                                    $executors = explode("," ,$row['executors']);
                                                    echo "<td>";
                                                    for ($i = 0; $i < count($executors); $i++)
                                                    {
                                                        echo $admin_object->get_admin_name_by_code($executors[$i])."<br/><br/>";
                                                    }
                                                    echo "</td>";
                                                    ?>
                                                <td>
                                                    <button title="View Announcements" type="button" data-toggle="modal" data-target="#view_comment<?php echo $row['project_code']?>" class="btn btn-info"><i class="glyphicon glyphicon-eye-open"></i></button>
                                                    <br/>
                                                    <br/>
                                                    <?php
                                                    
                                                    if(in_array($admin_code, $supervisor_code, true))
                                                    {
                                                        echo '<button title="Add New Announcement" type="button" data-toggle="modal" data-target="#new_comment'.$row['project_code'].'" class="btn btn-info"><i class="glyphicon glyphicon-comment"></i></button>';
                                                    }
                                                    ?>
                                                    <div id="new_comment<?php echo $row['project_code'] ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Project Announcements</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <p><strong>Project Title:</strong></p>
                                                                                <p><?php echo $row['title']; ?></p>
                                                                                <hr/>

                                                                                <p><strong>New Announcement:</strong></p>
                                                                                <input name="project_code" type="hidden" value="<?php echo $row['project_code'] ?>">
                                                                                <textarea name="comment" rows="5" class="form-control" placeholder="Enter a new announcement here..." required></textarea>
                                                                                <hr/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input name="new_comment" type="submit" class="btn btn-success" value="Post Announcement"/>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div id="view_comment<?php echo $row['project_code']?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Project Announcements</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">

                                                                                <p><strong>Project Title:</strong></p>
                                                                                <p><?php echo $row['title']; ?></p>
                                                                                <hr/>

                                                                                <?php $comments = $obj_project_management->get_project_comments($row['project_code']);
                                                                                //var_dump($comments);
                                                                                if(isset($comments) && !empty($comments))
                                                                                {
                                                                                    foreach ($comments as $row1)
                                                                                    { ?>

                                                                                        <div class="text-left">
                                                                                            <strong>Author:</strong><?php echo $row1['author_name'] ?>
                                                                                            <p class="text-left"><strong>Date:</strong><?php echo datetime_to_text2($row1['created']); ?></p>
                                                                                            <p class="text-justify"><?php echo $row1['comment'] ?></p>
                                                                                        </div>

                                                                                        <hr/>
                                                                                    <?php } }  ?>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php
                                                if(in_array($admin_code, $supervisor_code, true)):?>
                                                    <td>
                                                        <button title="Edit Project" type="button" data-toggle="modal" data-target="#edit_project<?php echo $row['project_code'] ?>" class="btn btn-info"><i class="glyphicon glyphicon-edit"></i></button>
                                                        <div id="edit_project<?php echo $row['project_code'] ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">
                                                                <!-- Modal content-->
                                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Edit Project</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <input type="hidden" name="project_code" value="<?php echo $row['project_code'];?>" />

                                                                                    <p><strong>Project Title</strong></p>
                                                                                    <input value="<?php echo $row['title']; ?>" type="text" name="title" class="form-control" placeholder="Project Title" required/>
                                                                                    <hr/>

                                                                                    <p><strong>Project Description</strong></p>
                                                                                    <textarea id="description" name="description" placeholder="Project Description" class="form-control" rows="9" required><?php echo $row['description']; ?></textarea>
                                                                                    <hr/>

                                                                                    <p><strong>Executors</strong></p>
                                                                                    <div class="form-group row">
                                                                                        <div class="col-sm-10">
                                                                                            <?php $allowed_admin = explode(",", $row['executors']);
                                                                                            foreach($all_admin_member AS $key) { ?>
                                                                                                <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } if($key['full_name'] == 'Toye Oyeleke'){echo "disabled";}?>/> <?php echo $key['full_name']; ?></label></div></div>
                                                                                            <?php } ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr/>

                                                                                    <p><strong>Deadline</strong></p>
                                                                                    <div class="">
                                                                                        <div class="input-group date" id="datetimepicker">
                                                                                            <input value="<?php echo $row['deadline']; ?>" name="deadline" type="text" class="form-control" id="datetimepicker2" required/>
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <script type="text/javascript">
                                                                                        $(function ()
                                                                                        {
                                                                                            $('#datetimepicker, #datetimepicker2').datetimepicker(
                                                                                                {
                                                                                                    format: 'YYYY-MM-DD'
                                                                                                });
                                                                                        });
                                                                                    </script>
                                                                                    <hr/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input name="edit_project" type="submit" class="btn btn-success" value="Proceed"/>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php endif ?>
                                                <td>
                                                    <a target="_blank" href="project_management_project_view.php?x=<?php echo encrypt($row['project_code']);?>">
                                                        <button title="View Complete Details" class="btn btn-sm btn-success">
                                                            <i class="glyphicon glyphicon-arrow-right "></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif;?>
                                    <?php }
                                    } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <?php if(isset($projects) && !empty($projects)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($projects) && !empty($projects)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>