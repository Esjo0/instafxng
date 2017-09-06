<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM article, visitors, comments 
            WHERE article.article_id = comments.article_id 
            AND visitors.visitor_id = comments.visitor_id 
            AND visitors.block_status = 'OFF' 
            AND comments.status = 'OFF' 
            ORDER BY comment_id DESC ";
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
$all_comments_items = $db_handle->fetchAssoc($result);


if (isset($_POST['delete_comment']))
{
    $query = "DELETE FROM comments 
                  WHERE comment_id = '".$_POST['comment_id']."'
                  AND visitor_id = '".$_POST['visitor_id']."';";
    $result = $db_handle->runQuery($query);
}

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
                    <h4><strong>VIEW RECENT COMMENTS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Below is the list of all the recent comments and articles.</p>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Article Title</th>
                                <th>Article Description</th>
                                <th>Author's Name</th>
                                <th>Author's Email</th>
                                <th>Comment</th>
                                <th>Created</th>
                                <th>Approve</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($all_comments_items) && !empty($all_comments_items)) {
                                foreach ($all_comments_items as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['comment']; ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                        <td>
                                            <button type="button" data-target="#confirm-approve-comment<?php echo $row['comment_id']; ?>" data-toggle="modal" class="btn btn-success"><i class="glyphicon glyphicon-play icon-white"></i></button>
                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-approve-comment<?php echo $row['comment_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Add Admin</h4></div>
                                                        <div class="modal-body">
                                                            Are you sure you want to approve this comment?
                                                            This action cannot be reversed.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a title="Approve Comment" class="btn btn-success" href="approve_comment.php?data=<?php echo $row['comment_id']; ?>">Proceed</a>
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" data-target="#confirm-delete-comment<?php echo $row['comment_id']; ?>" data-toggle="modal" class="btn btn-danger"><i class="glyphicon glyphicon-remove icon-white"></i> </button>
                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-delete-comment" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Add Admin</h4></div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this comment?
                                                            This action cannot be reversed.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a title="Delete Comment" class="btn btn-danger" href="delete_comment.php?data=<?php echo $row['comment_id']; ?>">Proceed</a>
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No new comments on this platform.</em></td></tr>"; } ?>
                            </tbody>
                        </table>


                        <?php if(isset($all_comments_items) && !empty($all_comments_items)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <?php if(isset($all_comments_items) && !empty($all_comments_items)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>