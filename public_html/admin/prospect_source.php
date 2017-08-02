<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// Add New Prospect Source - Process submitted form
if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    
    if(empty($source_title) || empty($source_description)) {
        $message_error = "All fields are compulsory, please try again.";
    } else {
        $new_prospect_source = $admin_object->add_new_prospect_source($source_title, $source_description);

        if($new_prospect_source) {
            $message_success = "You have successfully created a new prospect source.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}


$query = "SELECT * FROM prospect_source ORDER BY created DESC ";
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
$all_prospect_source = $db_handle->fetchAssoc($result);

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
                            <h4><strong>MANAGE PROSPECTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Add a new prospect source below. The list of available prospect source is also available on the
                                table below.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="source_title">Source Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="source_title" type="text" id="source_title" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="source_description">Description:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <textarea name="source_description" id="source_description" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add-prospect-source" data-toggle="modal" class="btn btn-success">Add Prospect Source</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-add-prospect-source" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Add Prospect Source</h4></div>
                                                <div class="modal-body">Are you sure you want to add a prospect source?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <hr />

                                <p>List of prospect source that have been created</p>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($all_prospect_source) && !empty($all_prospect_source)) {
                                        foreach ($all_prospect_source as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['source_name']; ?></td>
                                                <td><?php echo $row['source_description']; ?></td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='2' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

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