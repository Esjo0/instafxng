<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if(isset($_POST['update']))
{
    /*foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }*/

    $message = htmlentities($_POST['content_section']);
    $message = stripslashes($message);
    $message = str_replace('â€™', "'", $message);
    $filepath = "../views/general_pages/advert_div.html";
    if(file_exists($filepath))
    {
        $new_log = fopen($filepath, 'w');
        $update = fwrite($new_log, $message);
        fclose($new_log);
    }
    $update ? $message_success = "Advert Section updated." : $message_error = "Operation Failed. Please Try Again.";
}
$page_contents = file_get_contents("../views/general_pages/advert_div.html");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Front-End Advert Section</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Front-End Advert Section" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content_section",
                height: 500,
                theme: "modern",
                remove_linebreaks : true,
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
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
                            <h4><strong>FRONT-END ADVERT SECTION</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Please edit the advert section of the front end below.</p>
                                <form data-toggle="validator" enctype="multipart/form-data" class="form-horizontal" role="form" method="post" action="">
                                    <textarea rows="7" name="content_section" id="content_section" class="form-control" required><?php echo $page_contents; ?></textarea>
                                    <br/>
                                    <input value="Update" type="submit" name="update" class="btn btn-info" />
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