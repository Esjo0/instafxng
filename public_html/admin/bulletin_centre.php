<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM admin_bulletin WHERE status = '1' ORDER BY created DESC ";
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
        <title>Instaforex Nigeria | Admin - Bulletin Centre</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Bulletin Centre" />
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
                            <h4><strong>BULLETIN CENTRE</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>If you have the permission to view any bulletin in the system, you will find it below.</p>
                            </div>
                        </div>

                        <div class="row">
                            <?php if(isset($all_bulletin) && !empty($all_bulletin)) { foreach ($all_bulletin as $row) {
                                $allowed_admin = explode(",", $row['allowed_admin']);
                                if (!in_array($_SESSION['admin_unique_code'], $allowed_admin)) { continue; }
                            ?>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><a href="bulletin_read.php?id=<?php echo encrypt($row['admin_bulletin_id']); ?>" title="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></a></p>
                                        <em><strong>Author:</strong> <?php echo $admin_object->get_admin_name_by_code($row['admin_code']); ?></em><br/>
                                        <em><strong>Posted:</strong> <?php echo datetime_to_text($row['created']); ?></em><br/><br/>
                                        <hr/>
                                    </div>
                                </div>
                            </div>
                            <?php } } else { ?>
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