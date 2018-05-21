<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x']);
$project_code_encrypted = $get_params['x'];
$project_code = decrypt(str_replace(" ", "+", $project_code_encrypted));
$project_code = preg_replace("/[^A-Za-z0-9 ]/", '', $project_code);

$admin_code = $_SESSION['admin_unique_code'];

$all_admin_member = $admin_object->get_all_admin_member();

if(isset($_POST['edit_project']))
{

    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = $db_handle->sanitizePost(trim(str_replace('â€™', "'", $_POST['description'])));
    $deadline = $db_handle->sanitizePost(trim($_POST['deadline']));
    $allowed_admin = $_POST["allowed_admin"];

    for ($i = 0; $i < count($allowed_admin); $i++)
    {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }
    //$all_allowed_admin = substr_replace($all_allowed_admin, "", 0, 1);
    //var_dump($all_allowed_admin);
    $update_project = $obj_project_management->update_project($title, $description, $deadline, $all_allowed_admin, $admin_code, $project_code);
    if ($update_project)
    {
        $message_success = "You have successfully updated a project.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

if(isset($_POST['new_comment']))
{
    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $comment = $db_handle->sanitizePost(trim($_POST['comment']));

    //$query = "UPDATE project_management_projects SET last_comment = '$comment' WHERE project_code = '$project_code' LIMIT 1";
    $query = "INSERT INTO project_management_project_comments (comment, author_code, project_code) VALUES ('$comment', '$admin_code', '$project_code')";
    $new_comment = $db_handle->runQuery($query);
    if ($new_comment)
    {
        $message_success = "You have successfully added a new comment.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}


if(isset($_POST['suspended']))
{
    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));

    $query = "UPDATE project_management_projects SET status = '0', completion_stamp = CURRENT_TIMESTAMP WHERE project_code = '$project_code' LIMIT 1";
    $update_project = $db_handle->runQuery($query);
    if ($update_project)
    {
        $message_success = "You have successfully updated a project.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

if(isset($_POST['completed']))
{
    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $query = "UPDATE project_management_projects SET status = '2', completion_stamp = CURRENT_TIMESTAMP WHERE project_code = '$project_code' LIMIT 1";
    $update_project = $db_handle->runQuery($query);
    if ($update_project)
    {
        $message_success = "You have successfully updated a project.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

if(isset($_POST['reopen']))
{
    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $query = "UPDATE project_management_projects SET status = '1', completion_stamp = CURRENT_TIMESTAMP WHERE project_code = '$project_code' LIMIT 1";
    $update_project = $db_handle->runQuery($query);
    if ($update_project)
    {
        $message_success = "You have successfully updated a project.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

if(isset($_POST['resume']))
{
    $project_code = $db_handle->sanitizePost(trim($_POST['project_code']));
    $query = "UPDATE project_management_projects SET status = '1', completion_stamp = CURRENT_TIMESTAMP WHERE project_code = '$project_code' LIMIT 1";
    $update_project = $db_handle->runQuery($query);
    if ($update_project)
    {
        $message_success = "You have successfully updated a project.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

$query = "SELECT * FROM project_management_projects WHERE project_code = '$project_code' LIMIT 1 ";
$project_details = $db_handle->runQuery($query);
$project_details = $db_handle->fetchAssoc($project_details);
$project_details = $project_details[0];




$query = "SELECT 
            project_management_projects.title AS project_title,
            project_management_reports.created AS report_submission_date,
            project_management_reports.comment AS report_comments, 
            project_management_reports.status AS report_status, 
            project_management_reports.report_code AS report_code
            FROM project_management_reports, project_management_projects
            WHERE project_management_projects.project_code = project_management_reports.project_code
            AND project_management_projects.project_code = '$project_code'
            AND project_management_reports.author_code = '$admin_code'
            ORDER BY project_management_reports.created ASC  ";

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
$project_reports = $db_handle->fetchAssoc($result);


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
    <script>
        function show_chat(div)
        {
            var x = document.getElementById(div);
            if (x.style.display === 'none') {
                x.style.display = 'block';
            } else {
                x.style.display = 'none';
            }
        }
        var val = 'Type your message here...';
        function fillField(input,val)
        {
            if(input.value == "")
                input.value=val;
        };

        function clearField(input,val)
        {
            if(input.value == val)
                input.value="";
        };

    </script>
    <script>

        var XMLHttpRequestObject = false;

        if (window.XMLHttpRequest)
        {
            XMLHttpRequestObject = new XMLHttpRequest();
        }
        else if (window.ActiveXObject)
        {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        function send_message(message_id, project_code, messageDiv)
        {
            if(XMLHttpRequestObject)
            {
                XMLHttpRequestObject.open("POST", "project_management_messages_server.php");
                XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                XMLHttpRequestObject.onreadystatechange = function()
                {
                    if (XMLHttpRequestObject.readyState == 4 &&
                        XMLHttpRequestObject.status == 200)
                    {
                        var returnedData = XMLHttpRequestObject.responseText;
                        var messageDiv = document.getElementById(messageDiv);
                        messageDiv.innerHTML = returnedData;
                    }
                }
                var message = document.getElementById(message_id).value;
                var project_code = document.getElementById(project_code).value;
                //window.alert("message=" + message + "&project_code=" + project_code);


                XMLHttpRequestObject.send("message=" + message + "&project_code=" + project_code);
                document.getElementById(message_id).value = "";
                var chatHistory = document.getElementById("messageBody");
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }

            return false;

        }

    </script>
    <script>
        function get_messages(chatDiv, project_code)
        {
            var req = new XMLHttpRequest();
            req.onreadystatechange = function()
            {
                if(req.readyState == 4 && req.status == 200)
                {
                    document.getElementById(chatDiv).innerHTML = req.responseText;
                    var chatHistory = document.getElementById("messageBody");
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                    //window.alert(req.responseText);
                }
            }
            req.open('GET', 'project_management_messages_server2.php?project_code=' + project_code, true);
            req.send();
        }
        setInterval(function(){get_messages()}, 3000)
    </script>
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
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>PROJECT TITLE  - <?php echo strtoupper($project_details['title']); ?></strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <div class="col-sm-6">
                                    <!-- PROJECT DESCRIPTION-->
                                    <div class="col-sm-12" >
                                        <h5 class="text-center">PROJECT DETAILS</h5>
                                        <hr/>
                                        <div style="height: 600px; overflow-y: scroll;">
                                            <p class="text-center">
                                                <strong>Description</strong>
                                            </p>
                                            <p class="text-justify">
                                                <?php echo $project_details['description'];?>
                                            </p>
                                            <hr/>

                                            <p>
                                                <strong>Project Creation Date: </strong>
                                                <?php echo datetime_to_text2($project_details['created']);?>
                                            </p>
                                            <hr/>

                                            <p>
                                                <strong>Project Deadline: </strong>
                                                <?php echo $project_details['deadline'];?>
                                            </p>
                                            <hr/>

                                            <p>
                                                <strong>Project Supervisor: </strong>
                                                <?php
                                                $project_supervisor_code = explode(',',$project_details['supervisor_code']);
                                                foreach($project_supervisor_code as $key)
                                                {
                                                    echo $admin_object->get_admin_name_by_code($key)."<br/>";
                                                }?>
                                            </p>
                                            <hr/>

                                            <p>
                                                <strong>Project Executors:</strong>
                                                <br/>
                                                <?php
                                                $executors = explode("," ,$project_details['executors']);
                                                for ($i = 0; $i < count($executors); $i++)
                                                {
                                                    echo $admin_object->get_admin_name_by_code($executors[$i])."<br/>";
                                                }
                                                ?>
                                            </p>
                                            <hr/>

                                            <p>
                                                <strong>Project Status: </strong>
                                                <?php echo project_management_status($project_details['status']);?>
                                            </p>
                                            <hr/>
                                            <a href="project_management_report_add.php?x=<?php echo encrypt($project_details['project_code']); ?>">
                                                <button class="btn btn-success btn-sm">Report</button>
                                            </a>
                                            <?php
                                                $project_supervisor_code = explode(',',$project_details['supervisor_code']);
                                                if(in_array($admin_code, $project_supervisor_code)):?>
                                                <button title="Edit Project" type="button" data-toggle="modal" data-target="#edit_project<?php echo $project_details['project_code'] ?>" class="btn btn-info btn-sm"><i class='glyphicon glyphicon-edit'></i></button>
                                                <div id="edit_project<?php echo $project_details['project_code'] ?>" class="modal fade" role="dialog">
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
                                                                            <input type="hidden" name="project_code" value="<?php echo $project_details['project_code'];?>" />

                                                                            <p><strong>Project Title</strong></p>
                                                                            <input value="<?php echo $project_details['title']; ?>" type="text" name="title" class="form-control" placeholder="Project Title" required/>
                                                                            <hr/>

                                                                            <p><strong>Project Description</strong></p>
                                                                            <textarea id="description" name="description" placeholder="Project Description" class="form-control" rows="9" required><?php echo $project_details['description']; ?></textarea>
                                                                            <hr/>

                                                                            <p><strong>Executors</strong></p>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-10">
                                                                                    <?php $allowed_admin = explode(",", $project_details['executors']);
                                                                                    foreach($all_admin_member AS $key) { ?>
                                                                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } ?>/> <?php echo $key['full_name']; ?></label></div></div>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                            <hr/>

                                                                            <p><strong>Deadline</strong></p>
                                                                            <div class="">
                                                                                <div class="input-group date" id="datetimepicker">
                                                                                    <input value="<?php echo $project_details['deadline']; ?>" name="deadline" type="text" class="form-control" id="datetimepicker2" required/>
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

                                                    </div>
                                                </div>
                                            <?php endif ?>
                                            <?php
                                            $project_supervisor_code = explode(',',$project_details['supervisor_code']);
                                            if(in_array($admin_code, $project_supervisor_code)):?>
                                                <!--<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">-->
                                                <input type="hidden" name="project_code" value="<?php echo $project_details['project_code'];?>" />
                                                <button title="<?php if($project_details['status'] == '1'){echo 'Suspend Project';}else{echo 'Resume Project';}?>" name="<?php if($project_details['status'] == '1'){echo 'suspended';}else{echo 'resume';}?>" type="submit" class="btn btn-warning btn-sm" ><?php if($project_details['status'] == '1'){echo "<i class='glyphicon glyphicon-pause'></i>";}else{echo "<i class='glyphicon glyphicon-play'></i>";}?></button>

                                            <?php endif ?>
                                            <?php
                                            $project_supervisor_code = explode(',',$project_details['supervisor_code']);
                                            if(in_array($admin_code, $project_supervisor_code)):?>
                                                <!--<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">-->
                                                <input type="hidden" name="project_code" value="<?php echo $project_details['project_code'];?>" />
                                                <button title="<?php if($project_details['status'] == '1' || $project_details['status'] == '0'){echo 'Project Completed';}else{echo 'Re-open Completed';}?>" name="<?php if($project_details['status'] == '1' || $project_details['status'] == '0'){echo 'completed';}else{echo 're-open';}?>" type="submit" class="btn btn-success btn-sm"><i class='glyphicon glyphicon-check'></i></button>
                                            <?php endif ?>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- PROJECT DESCRIPTION -- end -->
                                </div>

                                <div class="col-sm-6">
                                    <!-- PROJECT ANNOUNCEMENTS-->
                                    <h5 class="text-center">ANNOUNCEMENTS</h5>
                                    <hr/>
                                    <div style="height: 300px; overflow-y: scroll;">
                                        <div class="col-sm-12" >
                                        <?php $comments = $obj_project_management->get_project_comments($project_details['project_code']);
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
                                        <?php if($project_details['supervisor_code'] == $admin_code)
                                        {
                                            echo '<p class="text-center"><button title="Add New Announcement" type="button" data-toggle="modal" data-target="#new_comment'.$project_details['project_code'].'" class="btn btn-info"><i class="glyphicon glyphicon-comment"></i> Add New Announcement</button></p>';
                                        }
                                        ?>
                                        <div id="new_comment<?php echo $project_details['project_code'] ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Project Announcement</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <p><strong>New Announcement:</strong></p>
                                                                    <input name="project_code" type="hidden" value="<?php echo $project_details['project_code'] ?>">
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
                                        </div>
                                    </div>
                                    <!-- PROJECT ANNOUNCEMENTS -- end -->
                                </div>




                                <div class="col-sm-6">
                                    <!-- PROJECT MESSAGING BOARD-->
                                    <h5 class="text-center">PROJECT MESSAGES</h5>
                                    <div style="position : relative; bottom:0;" onmouseover="get_messages('chat<?php echo $project_details['project_code']; ?>', '<?php echo $project_details['project_code']; ?>')">
                                        <div class="panel-collapse " id="collapseOne">
                                            <div id="messageBody" class="panel-body">
                                                <ul  id="chat<?php echo $project_details['project_code']; ?>" class="chat">
                                                    <script>
                                                        get_messages('chat<?php echo $project_details['project_code']; ?>', '<?php echo $project_details['project_code']; ?>')
                                                    </script>
                                                </ul>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="input-group">
                                                    <input type="hidden" id="project_code<?php echo $project_details['project_code']; ?>" value="<?php echo $project_details['project_code']; ?>">
                                                    <textarea rows="1" id="message<?php echo $project_details['project_code']; ?>" type="text" class="form-control input-sm"  onblur="fillField(this,'Enter a message here...');" onfocus="clearField(this,'Enter a message here...');" ></textarea>
                                                    <span class="input-group-btn">
                                                        <button onclick="send_message('message<?php echo $project_details['project_code']; ?>', 'project_code<?php echo $project_details['project_code']; ?>', 'messageDiv<?php echo $project_details['project_code']; ?>')" class="btn btn-lg btn-info" id="btn-chat">Send</button>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- PROJECT MESSAGING BOARD -- end -->
                                </div>

                                <div class="col-sm-12">
                                    <!-- SUBMITTED REPORTS-->
                                    <div class="col-sm-12">
                                        <h5 class="text-center">SUBMITTED REPORTS</h5>
                                        <hr/>
                                        <div style="height: 300px; overflow-y: scroll;">
                                            <div class="col-sm-12">
                                                <table class="table table-responsive  table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Report Submission Date</th>
                                                        <th>Report Comments</th>
                                                        <th>Report Status</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(isset($project_reports) && !empty($project_reports)) {
                                                        foreach ($project_reports as $row) { ?>
                                                            <tr>
                                                                <td><?php echo $row['report_submission_date']; ?></td>
                                                                <td>
                                                                    <?php echo $row['report_comments']; ?>
                                                                </td>
                                                                <td><?php echo $row['report_status']; ?></td>
                                                                <td><a href="project_management_report_view.php?x=<?php echo encrypt($row['report_code']); ?>"><button class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i></button></a></td>
                                                            </tr>
                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>
                                                <?php if(isset($project_reports) && !empty($project_reports)) { ?>
                                                    <div class="tool-footer text-right">
                                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SUBMITTED REPORTS -- end -->
                                </div>

                            </div>
                        </div>
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