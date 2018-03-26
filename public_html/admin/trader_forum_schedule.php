<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];
if(isset($_POST['forum'])){

    $s_form = "";
    if($_POST['options'] == 1){
        $s_form = 1;
    }elseif ($_POST['options'] == 2){
        $s_form = 0;
    }

    $main1 = $_POST['main1'];
    $sub1 = $_POST['sub1'];
    $main2 = $_POST['main2'];
    $sub2 = $_POST['sub2'];
    $linkt = $_POST['linkt'];
    $link = $_POST['link'];
    $s_date = $_POST['s_date'];
    $fileName = $_FILES['Filename'];
    var_dump($fileName);

    $target = "facility/";
    $fileTarget = $target.$fileName;
    $tempFileName = $_FILES["Filename"]["tmp_name"];
    var_dump($tempFileName);
    $result = move_uploaded_file($tempFileName,$fileTarget);
    var_dump($result);
    $img_path = "admin/facility/".$fileName;

    $query = "INSERT into forum_schedule(main1,sub1,main2,sub2,linkt,link,s_form,image_path,s_date,admin,status) 
          VALUES('$main1','$sub1','$main2','$sub2','$linkt','$link','$s_form','$img_path','$s_date','$admin_code','0')";
    var_dump($query);
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Submitted your report";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
    /*$text = "<div  class=\"item super-shadow page-top-section\">
    <div class=\"row \">
        <div class=\"col-sm-".$x."\">
            <h2>.$head.</h2>
            <p>.$body.
                <a href='".$link."'>".$linkt."</a></p>
        </div>
<div class=\"col-sm-".$y."\">
        <a href='".$link."' title=\"click for details\"><img src='admin/facility/".$fileName."' alt=\"\" class=\"img-responsive\" /></a>
</div>
    </div>
</div>";
    $myfile = fopen("../jumbotron.php", "w") or die("Unable to open file!");
    $txt = $text;
    fwrite($myfile, $txt);
    fclose($myfile);*/
}

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
            selector: "textarea#content",
            height: 500,
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
        <div id="main-body-content-area" class="col-md-8 col-lg-9">

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
                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-2 pt-0">Options</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="options" id="gridRadios1" value="1" checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Forum Schedule
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="options" id="gridRadios2" value="2">
                                            <label class="form-check-label" for="gridRadios2">
                                                Page PlaceHolder
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Main Heading 1</label>
                                <div class="col-sm-10">
                                    <input name="main1" type="text" class="form-control" id="inputEmail3" placeholder="Heading">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSubtile3" class="col-sm-2 col-form-label">Subtitle 1</label>
                                <div class="col-sm-10">
                                    <textarea name="sub1" class="form-control" style="height:100px" id="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Link Text</label>
                                <div class="col-sm-10">
                                    <input name="linkt" type="text" class="form-control" id="inputEmail3" placeholder="Link Text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Link</label>
                                <div class="col-sm-10">
                                    <input name="link" type="text" class="form-control" id="inputEmail3" placeholder="Input the link url starting with http:// or https://">
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
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Main Heading 2</label>
                                <div class="col-sm-10">
                                    <input name="main2" type="text" class="form-control" id="inputEmail3" placeholder="Heading">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSubtile3" class="col-sm-2 col-form-label">Subtitle 2</label>
                                <div class="col-sm-10">
                                    <textarea name="sub2" class="form-control" rows="3" id="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputHeading3" class="col-sm-2 col-form-label">Forum Date</label>
                                <div class="date col-sm-5">
                                    <input name="s_date" type="text" class="form-control" id="datetimepicker">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                <h4 class="modal-title">Preview</h4></div>
            <div class="modal-body">

                <div class="super-shadow page-top-section">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 style="margin: 0;">Make 2018 a Memorable One</h3>
                            <p style="margin-top: 0">
                                Join us on Saturday 13th of January, 2018 as we discuss simple winning strategies you need to use in order to make consistent profits this year. Time: 12 - 2pm.<br />
                                <strong>Reserve your seat below to stand a chance to win $20.</strong>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <img src="https://instafxng.com/images/forex-traders-forum-smart-investors.jpg" alt="" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="section-tint super-shadow">
                    <div class="row text-center">
                        <div class="col-sm-12 text-danger">
                            <h3><strong>Share thoughts with other Forex Traders</strong></h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <p>On the second Saturday of every month, Nigerian Forex traders gather at our
                                Lagos office to discuss Forex matters that will help propel their trading
                                success. They share their experiences, learn from other traders, meet new
                                people and go home with lots of exciting prizes such bonus account and Instaforex
                                branded materials. </p>
                            <p>Join us on Saturday, 13th of January, 2018 for another exciting edition of
                                Nigerian Forex traders Forum as we examine how to make 2018 a memorable one.</p>
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
                <input name="process" type="submit" class="btn btn-success" value="Proceed">
                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
            </div>
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>