<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Q2 Promotion</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Q2 Promotion" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#description",
                height: 500,
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
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>WINNERS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-responsive  table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Account Number</th>
                                        <th>Phone Number</th>
                                        <th>Promo Points</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($all_participants) && !empty($all_participants)) {
                                        foreach ($all_participants as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['participant_full_name']?></td>
                                                <td><?php echo $row['participant_email']?></td>
                                                <td><?php echo $row['total_time']?> second(s)</td>
                                                <td><?php echo $row['average_time']?> second(s)</td>
                                                <td><?php echo $row['total_score']?>%</td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <?php if(isset($all_participants) && !empty($all_participants)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($all_participants) && !empty($all_participants)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>TOP 20 ENTRIES AS AT <?php echo date_to_text(date('d-m-Y')).'   '.date('h:m A');?></strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-responsive  table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Account Number</th>
                                        <th>Phone Number</th>
                                        <th>Promo Points</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($top_participants) && !empty($top_participants)) {
                                        foreach ($top_participants as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['participant_full_name']?></td>
                                                <td><?php echo $row['participant_email']?></td>
                                                <td><?php echo $row['total_time']?> second(s)</td>
                                                <td><?php echo $row['average_time']?> second(s)</td>
                                                <td><?php echo $row['total_score']?>%</td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <?php /*if(isset($top_participants) && !empty($top_participants)) { */?><!--
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php /*echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; */?> entries</p>
                                    </div>
                                --><?php /*} */?>
                            </div>
                        </div>
                        <?php ?>
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