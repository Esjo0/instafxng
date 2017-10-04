<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM project_management_projects ORDER BY created DESC ";
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
$all_bulletin = $db_handle->fetchAssoc($result);
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
        <style>
            .chat
            {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .chat li
            {
                margin-bottom: 10px;
                padding-bottom: 5px;
                border-bottom: 1px dotted #B3A9A9;
            }

            .chat li.left .chat-body
            {
                margin-left: 60px;
            }

            .chat li.right .chat-body
            {
                margin-right: 60px;
            }


            .chat li .chat-body p
            {
                margin: 0;
                color: #777777;
            }

            .panel .slidedown .glyphicon, .chat .glyphicon
            {
                margin-right: 5px;
            }

            .panel-body
            {
                overflow-y: scroll;
                height: 400px;
            }

            ::-webkit-scrollbar-track
            {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar
            {
                width: 12px;
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar-thumb
            {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
                background-color: #555;
            }

        </style>
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
                        //window.alert(req.responseText);
                    }
                }
                req.open('GET', 'project_management_messages_server2.php?project_code=' + project_code, true);
                req.send();
            }
            setInterval(function(){get_messages()}, 1000)
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
                            <h4><strong>PROJECT MESSAGE BOARD</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <!--<div class="col-sm-12 well" style="display: inline-flex">
                                    <div id="search" class="col-sm-8 form-group input-group">
                                        <input placeholder="Enter a project title here..." type="text" class="form-control"/>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <!--<div id="new_thread" class="col-sm-4 form-group input-group" >
                                        <span class="text-center input-group-btn">
                                            <button class="btn btn-default" data-toggle="modal" data-target="#add_new_thread" type="button">
                                                <i class="glyphicon glyphicon-plus-sign"></i> Start New Message Thread</button>
                                        </span>
                                    </div>
                                    <!-- Modal-- Start New Thread
                                    <div id="add_new_thread" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content
                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Start New Message Thread</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <p><strong>Project Title</strong></p>
                                                                <select name="project_title"  required class="form-control">
                                                                    <option></option>
                                                                    <option value="Project Title">Project Title</option>
                                                                </select>
                                                                <hr/>

                                                                <p><strong>Task Title</strong></p>
                                                                <select name="task_title"  required class="form-control">
                                                                    <option></option>
                                                                    <option value="Task Title">Task Title</option>
                                                                </select>
                                                                <hr/>

                                                                <p><strong>Message</strong></p>
                                                                <textarea class="form-control" name="message" cols="5"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input name="new_project" type="submit" class="btn btn-success" value="Post"/>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            if(isset($all_bulletin) && !empty($all_bulletin))
                            {
                                foreach ($all_bulletin as $row)
                                {
                            ?>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><b>PROJECT TITLE: </b><?php echo strtoupper($row['title']); ?></p>
                                        <em><strong>SUPERVISOR:</strong> <?php echo $admin_object->get_admin_name_by_code($row['author_code']); ?></em><br/>
                                        <button onclick="show_chat('<?php echo $row['project_code']; ?>')" class="btn btn-info">View Thread</button>
                                        <br/>
                                        <br/>
                                        <hr/>
                                    </div>
                                </div>
                            </div>
                                    <div id="<?php echo $row['project_code']; ?>" style="display: none;" class="row">

                                            <div class="col-sm-12" >
                                                <div class="container">
                                                    <div class="row ">
                                                        <div class="col-sm-9">
                                                            <div onmouseover="get_messages('chat<?php echo $row['project_code']; ?>', '<?php echo $row['project_code']; ?>')"  class="panel panel-primary" >
                                                                <div class="panel-heading" id="accordion">
                                                                    <b>PROJECT TITLE: </b><?php echo strtoupper($row['title']); ?>
                                                                    <br/>
                                                                    <em><strong>SUPERVISOR:</strong> <?php echo $admin_object->get_admin_name_by_code($row['author_code']); ?></em><br/>
                                                                    <div class="btn-group pull-right">
                                                                    </div>
                                                                </div>
                                                                <div class="panel-collapse " id="collapseOne">
                                                                    <div class="panel-body">
                                                                        <ul  id="chat<?php echo $row['project_code']; ?>" class="chat">
                                                                            <script>
                                                                                get_messages('chat<?php echo $row['project_code']; ?>', '<?php echo $row['project_code']; ?>')
                                                                            </script>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="panel-footer">
                                                                                <div class="input-group">
                                                                            <input type="hidden" id="project_code<?php echo $row['project_code']; ?>" value="<?php echo $row['project_code']; ?>">
                                                                            <input id="message<?php echo $row['project_code']; ?>" type="text" class="form-control input-sm"  onblur="fillField(this,'Enter a message here...');" onfocus="clearField(this,'Enter a message here...');" />
                                                                            <span class="input-group-btn">
                                                                                <button onclick="send_message('message<?php echo $row['project_code']; ?>', 'project_code<?php echo $row['project_code']; ?>', 'messageDiv<?php echo $row['project_code']; ?>')" class="btn btn-warning btn-sm" id="btn-chat">Send</button>
                                                                            </span>
                                                                                </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                            <?php }
                            }
                            else
                                { ?>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="text-danger"><em>There is no result to display</em></p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                        <?php if(isset($all_bulletin) && !empty($all_bulletin)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <?php if(isset($all_bulletin) && !empty($all_bulletin)) { require_once 'layouts/pagination_links.php'; } ?>




                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>