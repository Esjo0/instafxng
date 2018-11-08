<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

$get_params = allowed_get_params(['x']);

$id = $get_params['x'];

//Ensure only those that have an initiated refund can access this page
if (!empty($id)) {
    //since GET values are set, we will confirm if its a true refund transaction
    $query = "SELECT * FROM admin_targets WHERE id = '$id' LIMIT 1";
    $num_rows = $db_handle->numRows($query);

    if($num_rows != 1) {
        // No record found. Redirect to the home page.
        redirect_to("./");
        exit;
    }

} else {
    //Redirect to homepage - user trying to access page directly without been sent a link
    redirect_to("./");
    exit;
}
//Update target
if (isset($_POST['update'])) {
    $name = $db_handle->sanitizePost($_POST['name']);
    $details = $db_handle->sanitizePost($_POST['details']);
    $value = $db_handle->sanitizePost($_POST['value']);
    $id = $db_handle->sanitizePost($_POST['id']);

    $query = "UPDATE admin_targets SET name = '$name', details = '$details', value = '$value', updated = now() WHERE id = '$id'";
    $result = $db_handle->runQuery($query);
    if ($result) {
        $message_success = "You have successfully updated your target";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update target status to active
//if (isset($_POST['status_display'])) {
//    $target_id = $db_handle->sanitizePost($_POST['target_id']);
//    $query = "UPDATE admin_targets SET status = 1 WHERE id = '$target_id'";
//    $result = $db_handle->runQuery($query);
//    if ($result) {
//        $message_success = "You have successfully updated your target view status to ACTIVE";
//    } else {
//        $message_error = "Something went wrong. Please try again.";
//    }
//}

//Update target status to inactive
//if (isset($_POST['status_hide'])) {
//    $target_id = $db_handle->sanitizePost($_POST['target_id']);
//    $query = "UPDATE admin_targets SET status = 2 WHERE id = '$target_id'";
//    $result = $db_handle->runQuery($query);
//    if ($result) {
//        $message_success = "You have successfully updated your target view status to INACTIVE";
//    } else {
//        $message_error = "Something went wrong. Please try again.";
//    }
//}

//select previous targets
$query = "SELECT * FROM admin_targets WHERE id = '$id' ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;

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
$targets = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin</title>
    <meta name="title" content="Instaforex Nigeria | Admin"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php require_once 'layouts/head_meta.php'; ?>
    <script src="tinymce/tinymce.min.js"></script>
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
            <div class="section-tint super-shadow">
            <?php require_once 'layouts/feedback_message.php'; ?>
            <!-- Unique Page Content Starts Here
            ================================================== -->
                                        <?php if (isset($targets) && !empty($targets)){?>
                                                <?php
                                                foreach ($targets as $row) {
                                                extract($row);
                                                ?>
                                                    <!--Modal - confirmation boxes-->
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Update
                                                                        Target <?php echo($row['name']); ?> </h4>

                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="text-center">
<!--                                                                        <span><b>STATUS</b></span>-->
<!--                                                                        <form data-toggle="validator"-->
<!--                                                                              class="form-vertical" role="form"-->
<!--                                                                              method="post" action="">-->
<!--                                                                            <input name="target_id"-->
<!--                                                                                   class="form-control" type="hidden"-->
<!--                                                                                   value="--><?php //echo $row['id']; ?><!--">-->
<!--                                                                            --><?php //if ($row['status'] == 2) { ?>
<!--                                                                                <button type="submit"-->
<!--                                                                                        name="status_display"-->
<!--                                                                                        class="btn btn-success">-->
<!--                                                                                    <span-->
<!--                                                                                        class="glyphicon glyphicon-eye-open"></span><b>Click-->
<!--                                                                                        here to make target Active</b>-->
<!--                                                                                </button>-->
<!--                                                                            --><?php //} elseif ($row['status'] == 1) { ?>
<!--                                                                                <button type="submit" name="status_hide"-->
<!--                                                                                        class="btn btn-success">-->
<!--                                                                                    <span-->
<!--                                                                                        class="glyphicon glyphicon-eye-close"></span><b>Click-->
<!--                                                                                        Here to make target Inactive</b>-->
<!--                                                                                </button>-->
<!--                                                                            --><?php //} ?>
<!--                                                                        </form>-->
                                                                        <a href="admin_targets.php"><button class="btn btn-success">GO BACK</button></a>
                                                                    </div>

                                                                    <form class="form-vertical" role="form"
                                                                          method="post" action="">
                                                                        <input name="id"
                                                                               class="form-control" type="hidden"
                                                                               value="<?php echo $row['id']; ?>"
                                                                               required>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-12">
                                                                                <label for="inputHeading3"
                                                                                       class="col-form-label">Target
                                                                                    Title/Name:</label>
                                                                                <input name="name"
                                                                                       value="<?php echo $row['name'] ?>"
                                                                                       type="text" class="form-control"
                                                                                       id="forum_title"
                                                                                       placeholder="Enter Target Name or title"
                                                                                       required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-12">
                                                                                <label for="inputHeading3"
                                                                                       class="col-form-label">Description</label>
                                                                                <textarea rows="3" name="details"
                                                                                          type="text"
                                                                                          class="form-control"
                                                                                          id="forum_title"
                                                                                          placeholder="Enter Detailed Description of the target"
                                                                                          required><?php echo $row['details'] ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <label>Target Type</label>
                                                                                <select type="text" name="type"
                                                                                        class="form-control " disabled>
                                                                                    <option
                                                                                        value="1" <?php if ($row['type'] == 1) {
                                                                                        echo "selected";
                                                                                    } ?>>On Boarding
                                                                                    </option>
                                                                                    <option
                                                                                        value="2" <?php if ($row['type'] == 2) {
                                                                                        echo "selected";
                                                                                    } ?>>Retention
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-12">Select
                                                                                Duration</label>
                                                                            <div class="col-sm-6">
                                                                                <div class="input-group date">
                                                                                    <input placeholder="Select Year"
                                                                                           value="<?php echo $row['year'] ?>"
                                                                                           name="year" type="text"
                                                                                           class="form-control"
                                                                                           id="datetimepicker" disabled>
                                                                                    <span
                                                                                        class="input-group-addon"><span
                                                                                            class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="input-group date">
                                                                                    <select
                                                                                        value="<?php echo $period; ?>"
                                                                                        type="text" name="period"
                                                                                        class="form-control" disabled>
                                                                                        <option
                                                                                            value="1" <?php if ($row['period'] == 1) {
                                                                                            echo "selected";
                                                                                        } ?>>January
                                                                                        </option>
                                                                                        <option
                                                                                            value="2" <?php if ($row['period'] == 2) {
                                                                                            echo "selected";
                                                                                        } ?>>February
                                                                                        </option>
                                                                                        <option
                                                                                            value="3" <?php if ($row['period'] == 3) {
                                                                                            echo "selected";
                                                                                        } ?>>March
                                                                                        </option>
                                                                                        <option
                                                                                            value="4" <?php if ($row['period'] == 4) {
                                                                                            echo "selected";
                                                                                        } ?>>April
                                                                                        </option>
                                                                                        <option
                                                                                            value="5" <?php if ($row['period'] == 5) {
                                                                                            echo "selected";
                                                                                        } ?>>May
                                                                                        </option>
                                                                                        <option
                                                                                            value="6" <?php if ($row['period'] == 6) {
                                                                                            echo "selected";
                                                                                        } ?>>June
                                                                                        </option>
                                                                                        <option
                                                                                            value="7" <?php if ($row['period'] == 7) {
                                                                                            echo "selected";
                                                                                        } ?>>July
                                                                                        </option>
                                                                                        <option
                                                                                            value="8" <?php if ($row['period'] == 8) {
                                                                                            echo "selected";
                                                                                        } ?>>August
                                                                                        </option>
                                                                                        <option
                                                                                            value="9" <?php if ($row['period'] == 9) {
                                                                                            echo "selected";
                                                                                        } ?>>September
                                                                                        </option>
                                                                                        <option
                                                                                            value="10" <?php if ($row['period'] == 10) {
                                                                                            echo "selected";
                                                                                        } ?>>October
                                                                                        </option>
                                                                                        <option
                                                                                            value="11" <?php if ($row['period'] == 11) {
                                                                                            echo "selected";
                                                                                        } ?>>November
                                                                                        </option>
                                                                                        <option
                                                                                            value="12" <?php if ($row['period'] == 12) {
                                                                                            echo "selected";
                                                                                        } ?>>December
                                                                                        </option>
                                                                                        <option
                                                                                            value="1-12" <?php if ($row['period'] == '1-12') {
                                                                                            echo "selected";
                                                                                        } ?>>Annual
                                                                                        </option>
                                                                                        <option
                                                                                            value="1-6" <?php if ($row['period'] == '1-6') {
                                                                                            echo "selected";
                                                                                        } ?>>First Half
                                                                                        </option>
                                                                                        <option
                                                                                            value="6-12" <?php if ($row['period'] == '6-12') {
                                                                                            echo "selected";
                                                                                        } ?>>Second Half
                                                                                        </option>
                                                                                        <option
                                                                                            value="1-3" <?php if ($row['period'] == '1-3') {
                                                                                            echo "selected";
                                                                                        } ?>>First Quarter
                                                                                        </option>
                                                                                        <option
                                                                                            value="4-6" <?php if ($row['period'] == '4-6') {
                                                                                            echo "selected";
                                                                                        } ?>>Second Quarter
                                                                                        </option>
                                                                                        <option
                                                                                            value="7-9" <?php if ($row['period'] == '7-9') {
                                                                                            echo "selected";
                                                                                        } ?>>Third Quarter
                                                                                        </option>
                                                                                        <option
                                                                                            value="10-12" <?php if ($row['period'] == '10-12') {
                                                                                            echo "selected";
                                                                                        } ?>>Fourth Quarter
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <script type="text/javascript">
                                                                            $(function () {
                                                                                $('#datetimepicker2').datetimepicker({format: 'YYYY'});
                                                                            });
                                                                        </script>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-5">
                                                                                <label for="inputHeading3"
                                                                                       class="col-form-label">Value</label>
                                                                                <input name="value"
                                                                                       value="<?php echo $row['value'] ?>"
                                                                                       type="number"
                                                                                       class="form-control"
                                                                                       placeholder="Enter Target Value"
                                                                                       required>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button name="update" type="submit"
                                                                            class="btn btn-success"> UPDATE
                                                                    </button>
                                                                    </form>
                                                                    <button type="submit" name="close"
                                                                            onClick="window.close();"
                                                                            data-dismiss="modal" class="btn btn-danger">
                                                                        Close!
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                        }
                                        ?>
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
        </div>
    </div>
    <?php require_once 'layouts/footer.php'; ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script
        src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>