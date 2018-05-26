<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$email = $_GET['data'];
if(empty($email))
{
    redirect_to("../list_of_visitors.php");
}
$query = "SELECT * FROM article_visitors WHERE email = '".$email."'";

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg']))
{
    $currentpage = (int) $_GET['pg'];
}
else
{
    $currentpage = 1;
}
if ($currentpage > $totalpages)
{
    $currentpage = $totalpages;
}
if ($currentpage < 1)
{
    $currentpage = 1;
}

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows)
{
    $prespagehigh = $numrows;
}

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_comments_items = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - View Recent Comments</title>
    <meta name="title" content="Instaforex Nigeria | Admin - View Recent Comments" />
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
                    <h4><strong>VIEW LIST OF VISITORS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">

                        <p><a href="list_of_visitors.php" class="btn btn-default" title="List Of All Visitors"><i class="fa fa-arrow-circle-left"></i> List Of All Visitors</a></p>

                        <p>Below are the search results</p>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Visitor's Name</th>
                                <th>Visitor's Email</th>
                                <th>Block/Unblock Visitor</th>
                                <th>Send Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($all_comments_items) && !empty($all_comments_items))
                            {

                                foreach ($all_comments_items as $row)
                                { ?>
                                    <tr>

                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                            <a href="block_visitor.php?data=<?php echo $row['visitor_id'] ?>&type=block"><button name="block_visitor" class="btn btn-danger glyphicon glyphicon-stop"<?php if ($row['block_status'] == 'ON'){echo 'disabled';} ?>></button></a>
                                            <a href="block_visitor.php?data=<?php echo $row['visitor_id'] ?>&type=unblock"><button name="block_visitor" class="btn btn-success glyphicon glyphicon-play"<?php if ($row['block_status'] == 'OFF'){echo 'disabled';} ?>></button></a>
                                            </td>
                                        <td><a href="campaign_email_single.php?name=<?php echo encrypt_ssl($row['visitor_name']).'&email='.encrypt_ssl($row['visitor_email']);?>" ><button name="send_email" class="btn btn-success glyphicon glyphicon-envelope"></button></a></td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                                echo "<tr><td colspan='6' class='text-danger'><em>No such visitor on this platform.</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                        <?php if(isset($all_comments_items) && !empty($all_comments_items))
                        { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                            <?php
                        } ?>

                    </div>
                </div>

                <?php if(isset($all_comments_items) && !empty($all_comments_items))
                {
                    require_once 'layouts/pagination_links.php';
                } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>