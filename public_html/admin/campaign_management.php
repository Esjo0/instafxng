<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if(isset($_POST['new_campaign']))
{
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = htmlspecialchars_decode(stripslashes(trim($_POST['description'])));
    $new_campaign = $obj_campaign_management->add_campaign($title, $description, $_SESSION['admin_unique_code']);
    if ($new_campaign){ $message_success = "You have successfully created a new campaign.";}
    else{$message_error = "The operation was not successful, please try again.";}
}

if(isset($_POST['new_channel']))
{
    $campaign_id = $db_handle->sanitizePost(trim($_POST['campaign_id']));
    $title = $db_handle->sanitizePost(trim($_POST['channel_title']));
    $description = htmlspecialchars_decode(stripslashes(trim($_POST['channel_description'])));
    $new_channel = $obj_campaign_management->add_channel($title, $description, $_SESSION['admin_unique_code'], $campaign_id);
    if ($new_channel){ $message_success = "You have successfully created a new campaign.";}
    else{$message_error = "The operation was not successful, please try again.";}
}

$query = "SELECT * FROM campaign_management_campaigns ORDER BY created DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];}
else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$campaigns = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Campaign Management</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Campaign Management" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#description",
                height: 250,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "template paste textcolor colorpicker textpattern "
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| print preview media | forecolor backcolor emoticons",
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
                            <h4><strong>CAMPAIGN MANAGEMENT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-12 well" style="display: inline-flex">
                                    <div id="search" class="col-sm-8 form-group input-group">
                                        <input placeholder="Enter a project title here..." type="text" class="form-control"/>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <div id="add_new_contact" class="col-sm-4 form-group input-group" >
                                        <span class="text-center input-group-btn">
                                            <button class="btn btn-default" data-toggle="modal" data-target="#add_project" type="button"><i class="glyphicon glyphicon-plus-sign"></i> New Campaign</button>
                                        </span>
                                    </div>
                                    <!-- Modal-- Add New Project -->
                                    <div id="add_project" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Modal content-->
                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Create New Campaign</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <p><strong>Campaign Title</strong></p>
                                                                <input type="text" name="title" class="form-control" placeholder="Campaign Title" required/>
                                                                <hr/>
                                                                <p><strong>Campaign Description</strong></p>
                                                                <textarea id="description" name="description" placeholder="Campaign Description" class="form-control" rows="9" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input name="new_campaign" type="submit" class="btn btn-sm btn-success" value="Proceed"/>
                                                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Channels</th>
                                            <th>Leads</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($campaigns) && !empty($campaigns)){foreach ($campaigns as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['title']; ?></td>
                                            <td>(<?php echo number_format($row['channels']); ?>) Channels</td>
                                            <td>(<?php echo number_format($row['leads']) ?>) Leads</td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <center><button data-toggle="modal" data-target="#x_<?php echo $row['campaign_id']; ?>" type="button" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-signal"></i></button></center>
                                                <div id="x_<?php echo $row['campaign_id']; ?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">CAMPAIGN DETAILS</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-5">
                                                                            <p class="text-justify"><strong>Title: </strong><?php echo $row['title']; ?></p>
                                                                            <p class="text-justify"><strong>Description: </strong><?php echo $row['description']; ?></p>
                                                                            <p class="text-justify"><strong>Total Leads Generated; </strong>(<?php echo number_format($row['leads']) ?>) Leads</p>
                                                                            <p class="text-justify"><strong>Total Channels: </strong>(<?php echo number_format($row['channels']); ?>) Channels</p>
                                                                            <p class="text-justify"><strong>Created: </strong><?php echo datetime_to_text($row['created']); ?></p>
                                                                            <p class="text-justify"><strong>URL: </strong><a href="<?php echo $row['url']; ?>"><?php echo $row['url']; ?></a></p>
                                                                        </div>
                                                                        <div class="col-sm-7" style="">
                                                                            <table class="table table-striped table-bordered table-hover">
                                                                                <thead><tr><th colspan="4" class="text-center">CHANNELS</th></tr></thead>
                                                                                <tbody>
                                                                                <?php
                                                                                $channels = $obj_campaign_management->get_channels($row['campaign_id']);
                                                                                foreach ($channels as $key)
                                                                                {?>
                                                                                    <tr>
                                                                                        <th style="font-size: smaller"><?php echo $key['title']; ?></th>
                                                                                        <td style="font-size: smaller">(<?php echo number_format(0); ?>) Leads</td>
                                                                                        <td style="font-size: smaller"><?php echo $obj_campaign_management->channel_status($key['status']); ?> Channel</td>
                                                                                        <td style="font-size: smaller">Created: <?php echo datetime_to_text($key['created']); ?></td>
                                                                                    </tr>
                                                                                    <?php } ?>
                                                                                    <tr>
                                                                                        <td colspan="4">
                                                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                                                <input type="hidden" name="campaign_id" class="form-control input-sm" value="<?php echo $row['campaign_id']; ?>" required/>
                                                                                                <input type="text" name="channel_title" class="form-control input-sm" placeholder="Channel Title" required/>
                                                                                                <br/>
                                                                                                <textarea name="channel_description" placeholder="Channel Description" class="form-control input-sm" rows="2" required></textarea>
                                                                                                <br/>
                                                                                                <input name="new_channel" type="submit" class="btn btn-group-justified btn-sm btn-success" value="Create New Channel"/>
                                                                                            </form>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <?php if(isset($campaigns) && !empty($campaigns)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($campaigns) && !empty($campaigns)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>