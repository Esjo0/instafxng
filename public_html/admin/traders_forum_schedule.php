<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];
if(isset($_POST['delete'])) {
    $id = $db_handle->sanitizePost($_POST['id']);
    $query = "DELETE FROM forum_schedule WHERE id = $id";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Deleted your Schedule";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}
if(isset($_POST['forum'])){
    $main1 = $db_handle->sanitizePost($_POST['main1']);
    $sub1 = $db_handle->sanitizePost($_POST['sub1']);
    $main2 = $db_handle->sanitizePost($_POST['main2']);
    $sub2 = $db_handle->sanitizePost($_POST['sub2']);
    $linkt = $db_handle->sanitizePost($_POST['linkt']);
    $link = $db_handle->sanitizePost($_POST['link']);
    $s_date = $db_handle->sanitizePost($_POST['s_date']);
    $fileName = $_FILES['Filename'];
    $target = "../images/";
    $fileTarget = $target.$fileName;
    $tempFileName = $_FILES["Filename"]["tmp_name"];
    $result = move_uploaded_file($tempFileName,$fileTarget);
    $img_path = "images/".$fileName;

    $query = "INSERT into forum_schedule(main1,sub1,main2,sub2,linkt,link,image_path,s_date,admin,status) 
          VALUES('$main1','$sub1','$main2','$sub2','$linkt','$link','$img_path','$s_date','$admin_code','0')";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Submitted your report";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['update'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $main1 = $db_handle->sanitizePost( $_POST['main1']);
    $sub1 = $db_handle->sanitizePost($_POST['sub1']);
    $main2 = $db_handle->sanitizePost($_POST['main2']);
    $sub2 = $db_handle->sanitizePost($_POST['sub2']);
    $linkt = $db_handle->sanitizePost($_POST['linkt']);
    $link = $db_handle->sanitizePost($_POST['link']);
    $s_date = $db_handle->sanitizePost($_POST['s_date']);
    $fileName = $_FILES['Filename'];
    $formal_img = $db_handle->sanitizePost($_POST['formal_image']);


    $target = "../images/";
    $fileTarget = $target.$fileName;
    $tempFileName = $_FILES["Filename"]["tmp_name"];
    $result = move_uploaded_file($tempFileName,$fileTarget);
    $img_path = "images/".$fileName;
    if(empty($fileName)){
$img_path = $formal_img;
    }

    $query = "UPDATE forum_schedule SET main1 = '$main1',sub1 = '$sub1', main2 = '$main2',sub2 = '$sub2', link = '$link',linkt = '$linkt',image_path = '$img_path',s_date = '$s_date',admin = '$admin_code',status = '1' WHERE id = '$id'";
var_dump($query);
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Updates your Schedule";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}


$query = "SELECT * FROM forum_schedule";
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
            height: 300,
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
                    <h4><strong>Trader Forum Schedule</strong></h4>
                </div>
            </div>
            <?php require_once 'layouts/feedback_message.php'; ?>


            <div class="row">
                <div class="col-lg-12">
                    <?php require_once 'layouts/feedback_message.php'; ?>
                    <div class="section-tint super-shadow">
                        <div class="row">
                        <div class="col-md-8">
                        <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Traders forum Title</label>
                                <div class="col-sm-10">
                                    <input name="main1" type="text" class="form-control" id="forum_title" placeholder="Heading">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSubtile3" class="col-sm-2 col-form-label">Date and time information</label>
                                <div class="col-sm-10">
                                    <textarea name="sub1" class="form-control" style="height:100px" id="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Seat Reservation form Link Text</label>
                                <div class="col-sm-10">
                                    <input name="linkt" type="text" class="form-control" id="link_text" placeholder="Link Text">
                                </div>
                            </div>
                            <p><center>Default link should be <strong>"https://instafxng.com/traders_forum.php#more"</strong> except otherwise required</center></p>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Link</label>
                                <div class="col-sm-10">
                                    <input name="link" type="text" class="form-control" id="link" placeholder="Input the link url starting with http:// or https://">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0">Forum Image</legend>
                                    <div class="col-sm-10">
                                <label for="exampleInputFile">Select File</label>
                                <input name="Filename" type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Select image file from your document</small>
                                </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Share thoughts Heading</label>
                                <div class="col-sm-10">
                                    <input name="main2" type="text" class="form-control" id="thoughts_header" placeholder="Heading">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSubtile3" class="col-sm-2 col-form-label">Share thoughts Body</label>
                                <div class="col-sm-10">
                                    <textarea name="sub2" class="form-control" rows="3" id="content2"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Traders Forum Date</label>
                                <div class="date col-sm-5">
                                    <input name="s_date" type="text" class="form-control" id="datetimepicker"/>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker, #datetimepicker2').datetimepicker({
                                        format: 'YYYY-MM-DD'
                                    });
                                });
                            </script>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <button name="forum" type="submit" class="btn btn-primary">Schedule</button>
                                </div>
                            </div>
                        </form>
                    </div>
                            <div class="col-md-4">
                                <div class="row">
                            <div class="col-md-12" data-target="#preview" data-toggle="modal" >
                                <h5><strong>Click on Image For Description </strong></h5>
                                <img src="../images/forum_schedule.png" alt="" class="img-responsive"/>
                            </div>

                                    <div class="col-md-12">
                                        <h5><strong>Update Previous Schedules</strong></h5>
                                        <?php if(isset($updates) && !empty($updates)):?>
                                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Scheduled Date</th>
                                                <th></th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                        foreach ($updates as $row) {
                                            ?>
<tr>
                                            <td><?php echo datetime_to_text2($row['s_date']); ?></td>
                                            <td><center>
                                                <button data-target="#update<?php echo $row['id']; ?>" data-toggle="modal" class="btn btn-success">
                                                    Update</button>
                                            </center></td>
                                            <td><form data-toggle="validator"
                                                      class="form-vertical" role="form"
                                                      method="post" action="">
                                                    <input name="id"
                                                           class="form-control"
                                                           id="forum_title" type="hidden"
                                                           value="<?php echo $row['id']; ?>" >
                                                    <button type="submit" name="delete" class="btn btn-success" >
                                                        <span class="glyphicon glyphicon-trash"></span></button>
                                                    </form>
                                            </td>

                                            <!--Modal - confirmation boxes-->
                                            <div id="update<?php echo $row['id']; ?>" tabindex="-1" role="dialog"
                                                 aria-hidden="true" class="modal fade ">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal"
                                                                    aria-hidden="true"
                                                                    class="close">&times;
                                                            </button>
                                                            <h4 class="modal-title">Update
                                                                For <?php echo datetime_to_text2($row['created']); ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div>

                                                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                <div class="section-tint super-shadow">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form data-toggle="validator"
                                                                                  class="form-vertical" role="form"
                                                                                  method="post" action=""
                                                                                  enctype="multipart/form-data">
                                                                                <input name="id"
                                                                                       class="form-control"
                                                                                       id="forum_title"
                                                                                       value="<?php echo $row['id']; ?>" type="hidden">
                                                                                <input name="formal_image"
                                                                                       class="form-control"
                                                                                       id="forum_title"
                                                                                       value="<?php echo $row['image_path']; ?>" type="hidden">
                                                                                <div class="form-group row">
                                                                                    <label for="inputHeading3"
                                                                                           class="col-sm-2 col-form-label">Traders
                                                                                        forum Title</label>
                                                                                    <div class="col-sm-10">
                                                                                        <input name="main1" type="text"
                                                                                               class="form-control"
                                                                                               id="forum_title"
                                                                                               value="<?php echo $row['main1']; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label for="inputSubtile3"
                                                                                           class="col-sm-2 col-form-label">Date
                                                                                        and time information</label>
                                                                                    <div class="col-sm-10">
                                                                                        <textarea name="sub1"
                                                                                                  class="form-control"
                                                                                                  id="content"
                                                                                                  style="height:100px"><?php echo $row['sub1']; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label for="inputHeading3"
                                                                                           class="col-sm-2 col-form-label">Seat
                                                                                        Reservation form Link
                                                                                        Text</label>
                                                                                    <div class="col-sm-10">
                                                                                        <input name="linkt" type="text"
                                                                                               class="form-control"
                                                                                               id="link_text"
                                                                                            value="<?php echo $row['linkt']; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <p>
                                                                                <center>Default link should be <strong>"https://instafxng.com/traders_forum.php#more"</strong>
                                                                                    except otherwise required
                                                                                </center>
                                                                                </p>
                                                                                <div class="form-group row">
                                                                                    <label for="inputHeading3"
                                                                                           class="col-sm-2 col-form-label">Link</label>
                                                                                    <div class="col-sm-10">
                                                                                        <input name="link" type="text"
                                                                                               class="form-control"
                                                                                               id="link"
                                                                                            value="<?php echo $row['link']; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <legend class="col-form-label col-sm-2 pt-0">
                                                                                            Forum Image
                                                                                        </legend>
                                                                                        <div class="col-sm-10">
                                                                                            <label for="exampleInputFile">Select
                                                                                                File</label>
                                                                                            <input name="Filename"
                                                                                                   type="file"
                                                                                                   class="form-control-file"
                                                                                                   id="exampleInputFile"
                                                                                                   aria-describedby="fileHelp"
                                                                                               ><img src="../<?php echo $row['image_path']; ?>" alt="" class="img-responsive"/>
                                                                                            <small id="fileHelp"
                                                                                                   class="form-text text-muted">
                                                                                                Select image file from
                                                                                                your document
                                                                                            </small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label for="inputHeading3"
                                                                                           class="col-sm-2 col-form-label">Share
                                                                                        thoughts Heading</label>
                                                                                    <div class="col-sm-10">
                                                                                        <input name="main2" type="text"
                                                                                               class="form-control"
                                                                                               id="thoughts_header"
                                                                                               value="<?php echo $row['main2']; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label for="inputSubtile3"
                                                                                           class="col-sm-2 col-form-label">Share
                                                                                        thoughts Body</label>
                                                                                    <div class="col-sm-10">
                                                                                        <textarea name="sub2"
                                                                                                  class="form-control"
                                                                                                  rows="3"
                                                                                                  id="content2"><?php echo $row['sub2']; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label for="inputHeading3"
                                                                                           class="col-sm-2 col-form-label">Traders
                                                                                        Forum Date</label>
                                                                                    <div class="date col-sm-5">
                                                                                        <input name="s_date" type="text"
                                                                                               class="form-control"
                                                                                               id="datetimepicker"
                                                                                               value="<?php echo $row['s_date']; ?>"/>
                                                                                    </div>
                                                                                </div>
                                                                                <script type="text/javascript">
                                                                                    $(function () {
                                                                                        $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                                                            format: 'YYYY-MM-DD'
                                                                                        });
                                                                                    });
                                                                                </script>
                                                                                <div class="form-group row">
                                                                                    <div class="col-sm-4">
                                                                                        <button name="update"
                                                                                                type="submit"
                                                                                                class="btn btn-primary">
                                                                                            Update
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" name="close"
                                                                                onClick="window.close();"
                                                                                data-dismiss="modal"
                                                                                class="btn btn-danger">Close!
                                                                        </button>
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
                                        <?php if(isset($updates) && !empty($updates)) { ?>
                                        <div class="tool-footer text-right">
                                            <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                        </div>
                                        <?php } ?>
                                        <?php if(isset($reps) && !empty($reps)) { require_once 'layouts/pagination_links.php'; } ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Unique Page Content Ends Here
        ================================================== -->

    </div>
</div>
<div id="preview" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                <h4 class="modal-title">Preview</h4></div>

<script>
    function add()
    {
        document.getElementById("p_title").innerHTML = document.getElementById("forum_title").value;
        console.log(document.getElementById("content").value);
        document.getElementById("p_date_time").innerHTML = document.getElementById("content").value;
        document.getElementById("p_link_text").innerHTML = document.getElementById("link_text").value;
        document.getElementById("p_thoughts_header").innerHTML = document.getElementById("thoughts_header").value;
        document.getElementById("p_thoughts_body").innerHTML = document.getElementById("content2").value;
    }
</script>
            <div class="modal-body">
                <p><center><button class="btn btn-success" onclick="add()">Preview You Current Input</button></center></p>
                <div class="super-shadow page-top-section">
                    <div class="row">
                        <div class="col-sm-6">
                            <p style="color: red">Traders Forum Title <span class="glyphicon glyphicon-arrow-down"></span></p>

                            <h3 id="p_title" style="margin: 0;">Make 2018 a Memorable One</h3>
                            <p  id="p_date_time" style="margin-top: 0">
                                Join us on Saturday 13th of January, 2018 as we discuss simple winning strategies you need to use in order to make consistent profits this year. Time: 12 - 2pm.<br />
                            </p>
                            <p style="color: red">Date and time description <span class="glyphicon glyphicon-arrow-up"></span></p>
                            <p id="p_link_text"><strong>Reserve your seat below to stand a chance to win $20.</strong></p>
                            <p style="color: red">link and linktext <span class="glyphicon glyphicon-arrow-up"></span></p>

                        </div>
                        <div class="col-sm-6">
                            <img src="https://instafxng.com/images/forex-traders-forum-smart-investors.jpg" alt="" class="img-responsive" />
                            <p style="color: red">Traders Forum Image<span class="glyphicon glyphicon-arrow-up"></span></p>
                        </div>
                    </div>
                </div>

                <div class="section-tint super-shadow">
                    <div class="row text-center">
                        <div class="col-sm-12 text-danger">
                            <p style="color: red">Share Thoughts Heading<span class="glyphicon glyphicon-arrow-down"></span></p>
                            <h3 id="p_thoughts_header"><strong>Share thoughts with other Forex Traders</strong></h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12" >
                            <p id="p_thoughts_body" >On the second Saturday of every month, Nigerian Forex traders gather at our
                                Lagos office to discuss Forex matters that will help propel their trading
                                success. They share their experiences, learn from other traders, meet new
                                people and go home with lots of exciting prizes such bonus account and Instaforex
                                branded materials.
                            Join us on Saturday, 13th of January, 2018 for another exciting edition of
                                Nigerian Forex traders Forum as we examine how to make 2018 a memorable one.</p>
                            <p style="color: red">Share Thoughts Body<span class="glyphicon glyphicon-arrow-up"></span></p>
                        </div>
                    </div>

                    <div class="row" id="signup-section">

                        <div class="row">
                            <div class="col-sm-12">
                                <?php if(isset($message_success)) { ?>
                                    <div class="alert alert-success">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Success!</strong> <?php echo $message_success; ?>
                                    </div>
                                <?php } ?>

                                <?php if(isset($message_error)) { ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Oops!</strong> <?php echo $message_error; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <span id="opt"></span>

                        <div class="row">
                            <div class="col-sm-12">
                                <form data-toggle="validator" id="signup-form" role="form"  method="post" action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>">
                                    <h3 class="text-uppercase text-center signup-header">RESERVE A SEAT NOW</h3>
                                    <br />

                                    <div class="form-group has-feedback">
                                        <label for="name" class="control-label">Your Full Name</label>
                                        <div class="input-group margin-bottom-sm">
                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" data-minlength="5" required>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="email" class="control-label">Your Email Address</label>
                                        <div class="input-group margin-bottom-sm">
                                            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                                            <input type="email" class="form-control" id="email" name="email_add" placeholder="Your Email" data-error="Invalid Email" required>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="phone" class="control-label">Your Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Your Phone" data-minlength="11" maxlength="11" required>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block">Example - 08031234567</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="venue" class="control-label">Choose your venue</label>
                                        <div class="radio">
                                            <label><input id="venue" type="radio" name="venue" value="Diamond Estate" checked required>Block 1A, Plot 8, Diamond Estate, LASU/Isheri road, Isheri Olofin, Lagos.</label>
                                        </div>
                                        <div class="radio">
                                            <label><input id="venue" type="radio" name="venue" value="Ajah Office" required>Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</label>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="reserve_seat" class="btn btn-default btn-lg">Reserve Your Seat&nbsp;<i class="fa fa-chevron-circle-right"></i></button>
                                    </div>
                                    <small>All fields are required</small>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="row text-center">
                        <h2 class="color-fancy">For further enquiries, please call 08182045184, 07081036115</h2>
                    </div>
                </div>

                <!-- Unique Page Content Ends Here
                ================================================== -->

            </div>

            </div>
            <div class="modal-footer">
                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
            </div>
        </div>
    </div>
</div>
    </div>
<?php require_once 'layouts/footer.php'; ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>