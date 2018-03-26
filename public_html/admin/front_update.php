<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if(isset($_POST['update'])){
    $head = $_POST['head'];
    $body = $_POST['body'];
    $linkt = $_POST['linktext'];
    $link = $_POST['link'];
    $x = $_POST['textper'];
    $y = $_POST['imgper'];
    $fileName = $_FILES['Filename'];
    var_dump($fileName);

    $target = "facility/";
    $fileTarget = $target.$fileName;
    $tempFileName = $_FILES["Filename"]["tmp_name"];
    var_dump($tempFileName);
    $result = move_uploaded_file($tempFileName,$fileTarget);
    var_dump($result);

    $text = "<div  class=\"item super-shadow page-top-section\">
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
    fclose($myfile);
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
    <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
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
                    <h4><strong>FACILITY ADMIN</strong></h4>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <?php require_once 'layouts/feedback_message.php'; ?>
                    <div class="section-tint super-shadow">
                        <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">

                            <p>Kindly fill out your desired fields</p>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="inventoryid">Ratio:</label>
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                        <select  type="text" name="location" class="form-control " id="location" >
                                            <option value="" selected>0%</option>
                                            <option value="" selected>50%</option>
                                            <option value="" selected>100%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="inventoryid">Heading:</label>
                                    <div class="col-sm-12 col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                            <input name="head" type="text" id="" value="" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="comment">Content:</label>
                                    <div class="col-sm-12 col-lg-8">
                                        <div class="input-group">
                                            <textarea name="body" class="form-control" rows="3" id="comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div >
                                            <label class="control-label col-sm-3" for="inventoryid">linktext:</label>
                                            <div class="col-sm-12 col-lg-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                    <input name="linktext" type="text" id="" value="" class="form-control"/>
                                                </div>
                                            </div>
                                        </div></div>
                                    <div class="col-6">
                                        <div >
                                            <label class="control-label col-sm-3" for="inventoryid">link:</label>
                                            <div class="col-sm-12 col-lg-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                    <input name="link" type="text" id="" value="" class="form-control" />
                                                </div>
                                            </div>
                                        </div></div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Select File</label>
                                    <input name="Filename" type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                                    <small id="fileHelp" class="form-text text-muted">Select image file from your document</small>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="update"  class="btn btn-success"><i class="fa fa-send fa-fw"></i>Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
</div>
<?php require_once 'layouts/footer.php'; ?>

</body>
</html>