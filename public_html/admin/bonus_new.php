<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$all_conditions = $bonus_operations->get_conditions();
if(isset($_POST['process']))
{
    foreach($_POST as $key => $value) {
        if($key != 'condition_id') {$_POST[$key] = $db_handle->sanitizePost(trim($value));}
        else{ foreach ($value as $pin => $pin_value) { $_POST[$key][$value][$pin] = $db_handle->sanitizePost(trim($pin_value));}}
    }
    extract($_POST);
    $condition_id = implode(',', $condition_id);
    $new_package = $bonus_operations->create_new_package($bonus_title, $bonus_desc, $condition_id, $status, $type);
    $new_package ? $message_success = "Operation Successful." : $message_error = "Operation Failed.";
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
                            <h4><strong>CREATE NEW BONUS PACKAGE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="bonus_list.php" class="btn btn-default" title="Manage Bonus Packages"><i class="fa fa-arrow-circle-left"></i> Manage Bonus Packages</a></p>
                                <p>Fill the form below to create a new bonus package.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bonus_title">Package Name:</label>
                                        <div class="col-sm-9">
                                            <textarea id="bonus_title" name="bonus_title" class="form-control" rows="2" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bonus_desc">Package Description:</label>
                                        <div class="col-sm-9">
                                            <textarea id="bonus_desc" name="bonus_desc" class="form-control" rows="7" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Package Conditions:</label>
                                        <div class="col-sm-9">
                                            <?php foreach ($all_conditions as $key => $value){ ?>
                                            <div class="col-sm-12"><div class="checkbox"><label for="<?php echo $key; ?>"><input type="checkbox" name="condition_id[]" value="<?php echo $key; ?>" id="<?php echo $key; ?>" /> <?php echo $value['title']; ?></label></div></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="firstname">Package Type:</label>
                                        <div class="col-sm-9">
                                            <div class="col-sm-12"><div class="radio"><label for="1"><input type="radio" checked name="type" value="1" id="1" required /> Default</label></div></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="firstname">Status:</label>
                                        <div class="col-sm-9">
                                            <div class="col-sm-12"><div class="radio"><label for="1"><input type="radio" name="status" value="1" id="1" required /> Save As Draft</label></div></div>
                                            <div class="col-sm-12"><div class="radio"><label for="2"><input type="radio" name="status" value="2" id="2" required /> Active Package</label></div></div>
                                            <div class="col-sm-12"><div class="radio"><label for="3"><input type="radio" name="status" value="3" id="3" required /> Inactive Package</label></div></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add" data-toggle="modal" class="btn btn-sm btn-success">Process</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-add" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Process Package</h4></div>
                                                <div class="modal-body">Are you sure you want to process this bonus package? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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