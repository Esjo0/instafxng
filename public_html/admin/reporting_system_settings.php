<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

$admin_members = $admin_object->get_all_admin_member();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin Reporting System</title>
        <meta name="title" content="Instaforex Nigeria | Admin Reporting System" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
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
                            <h4><strong>REPORTING SYSTEM SETTINGS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="panel-group" id="accordion">
                                <?php foreach ($admin_members as $row) {  extract($row); ?>
                                <div class="col-sm-6">
                                    <!--Panel-->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $admin_code;?>">
                                                    <?php echo $full_name;?>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse_<?php echo $admin_code;?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="table-responsive mtl">
                                                    <form>
                                                        <span class="text-justify">Tick the names of the individuals that would review <?php echo $full_name;?>'s reports.</span>
                                                        <div class="form-group">
                                                            <?php foreach ($admin_members as $row2){ ?>
                                                            <!--Reviewer-->
                                                            <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="supervisor[]" value="<?php echo $row2['admin_code']?>" <?php if (in_array(1, $my_pages)) { echo 'checked="checked"'; } ?>/><?php echo $row2['full_name']?></label></div></div>
                                                            <!--Reviewer-->
                                                            <?php } ?>
                                                        </div>
                                                        <hr/>
                                                        <button type="submit" class="btn btn-sm btn-success btn-group-justified">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Panel End-->
                                    <br/>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>