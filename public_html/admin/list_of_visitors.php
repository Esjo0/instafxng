<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

if(isset($_POST['email_search']))
{
    $_POST['email_search'] = $db_handle->sanitizePost(trim($_POST['email_search']));
    redirect_to("visitors_results.php?data=".$_POST['email_search']);
}
if(isset($_POST['filter'])){
    $filter = $db_handle->sanitizePost($_POST['filter']);
    $query = "SELECT * FROM article_visitors WHERE entry_point = '$filter'";
}else{
    $query = "SELECT * FROM article_visitors ";
}


$query_article = $query. "WHERE entry_point = '1'";
$all_article = $db_handle->numRows($query_article);
$query_calendar = $query. "WHERE entry_point = '2'";
$all_calendar = $db_handle->numRows($query_calendar);
$query_extras = $query. "WHERE entry_point = '3'";
$all_extras = $db_handle->numRows($query_extras);

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
    <title>Instaforex Nigeria | Admin - View Visitor List</title>
    <meta name="title" content="Instaforex Nigeria | Admin - View Visitor List" />
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

            <div class="search-section">
                <div class="row">
                    <div class="col-xs-6">
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                            <div class="input-group">
                                <select name="filter" class="form-control" required>
                                    <option value=""></option>
                                    <option value="1" >Article</option>
                                    <option value="2" >News Calendar</option>
                                    <option value="3" >Extras</option>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" name="filter" type="submit">FILTER</button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-6">
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" name="email_search" value="" placeholder="Search By Email" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>VIEW LIST OF VISITORS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover table-responsive table-bordered">
                            </tr>
                            <tr><td>Total Visitors</td> <td><?php echo $numrows?></td></tr>
                            <tr><td>Total Visitors Article</td> <td><?php echo $all_article?></td></tr>
                            <tr><td>Total Visitors Calendar</td> <td><?php echo $all_calendar?></td></tr>
                            <tr><td>Total Visitors Extras</td> <td><?php echo $all_extras?></td></tr>
                        </table>
                        <p>Below is the list of all the visitors that have commented on your articles.</p>
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
                                {
                                    //var_dump($row);
                                    ?>
                                    <tr>

                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                            <a title="Block Visitor" href="block_visitor.php?data=<?php echo $row['visitor_id'] ?>&type=block"><button name="block_visitor" class="btn btn-danger glyphicon glyphicon-stop"<?php if ($row['block_status'] == 'ON'){echo 'disabled';} ?>></button></a>
                                            <a title="Unblock Visitor" href="block_visitor.php?data=<?php echo $row['visitor_id'] ?>&type=unblock"><button name="block_visitor" class="btn btn-success glyphicon glyphicon-play"<?php if ($row['block_status'] == 'OFF'){echo 'disabled';} ?>></button></a>
                                        </td>
                                            <td><a href="campaign_email_single.php?name=<?php echo encrypt_ssl($row['full_name']).'&email='.encrypt_ssl($row['email']);?>" ><button name="send_email" class="btn btn-success glyphicon glyphicon-envelope"></button></a></td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                                echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>";
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