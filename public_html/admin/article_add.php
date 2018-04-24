<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$article_id_encrypted = $get_params['id'];
$article_id = decrypt(str_replace(" ", "+", $article_id_encrypted));
$article_id = preg_replace("/[^A-Za-z0-9 ]/", '', $article_id);

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['content_tags'];
    $content = str_replace('â€™', "'", $_POST['content']);
    $article_no = $_POST['article_no'];
    $article_status = $_POST['article_status'];
    $url = generate_content_url($title);

    if($_FILES["display_picture"]["error"] == UPLOAD_ERR_OK) {
        if(isset($_FILES["display_picture"]["name"])) {
            $tmp_name = $_FILES["display_picture"]["tmp_name"];
            $name = strtolower($_FILES["display_picture"]["name"]);

            // Get file extension of original uploaded file and create a new file name
            $extension = explode(".", $name);

            new_name:
            $name_string = rand_string(25);
            $newfilename = $name_string . '.' . end($extension);
            $display_picture = strtolower($newfilename);

            if(file_exists("../images/blog/$display_picture")) {
                goto new_name;
            }

            move_uploaded_file($tmp_name, "../images/blog/$display_picture");
        }
    }

    if(empty($title) || empty($description) || empty($content) || empty($tags)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $new_article = $admin_object->add_new_article($article_no, $title, $description, $tags, $display_picture, $url, $content, $article_status, $_SESSION['admin_unique_code']);

        if($new_article) {
            $message_success = "You have successfully saved the article";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if($get_params['x'] == 'edit') {
    if(!empty($article_id)) {
        $selected_article = $system_object->get_article_by_id($article_id);
        $selected_article = $selected_article[0];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Add Article</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Add Article" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content",
                height: 500,
                theme: "modern",
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
        <script>
            function ValidateExtension()
            {
                var allowedFiles = [".png", ".jpg"];
                var fileUpload = document.getElementById("blah");
                //var lblError = document.getElementById("lblError");
                var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
                if (!regex.test(fileUpload.value.toLowerCase()))
                {
                    window.alert("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
                    return false;
                }
                //lblError.innerHTML = "";
                return true;
            }
            function readURL(input)
            {
                //ValidateExtension();
                if (input.files && input.files[0])
                {
                    var reader = new FileReader();
                    reader.onload = function (e)
                    {
                        $('#blah').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
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
                            <h4><strong>ADD ARTICLE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="article_manage.php" class="btn btn-default" title="Manage Article"><i class="fa fa-arrow-circle-left"></i> Manage Article</a></p>
                                
                                <p>Create a news article. Note: Tags are search engine keywords and should be comma separated.
                                Description is a summary of the article and may be used at certain parts of the website before user
                                gets access to the full news content.</p>
                                <p>Note: Insert image URL for display image, this is important for social media sharing, use the file manager.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
                                    <input type="hidden" name="POST_MAX_SIZE" value="500000" />
                                    <input type="hidden" name="article_no" value="<?php if(isset($selected_article['article_id'])) { echo $selected_article['article_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="title">Title:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="title" class="form-control" id="title" value="<?php if(isset($selected_article['title'])) { echo $selected_article['title']; } ?>" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="description">Description/Email Excerpt:</label>
                                        <div class="col-sm-10"><textarea name="description" id="description" rows="3" class="form-control" required><?php if(isset($selected_article['description'])) { echo $selected_article['description']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content_tags">Tags:</label>
                                        <div class="col-sm-10"><input type="text" name="content_tags" class="form-control" id="content_tags" value="<?php if(isset($selected_article['keyword'])) { echo $selected_article['keyword']; } ?>" required/></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="display_picture">Display Image:</label>
                                        <div class="col-sm-10">
                                            <center>
                                            <img width="200px" height="150px" class="img-thumbnail" id="blah" src="<?php if(isset($selected_article['display_image']) && !empty($selected_article['display_image'])) {echo "https://instafxng.com/images/blog/".$selected_article['display_image'];} else{ echo '../images/placeholder.jpg';} ?>" alt="your image" />
                                            <br/>
                                            <input name="display_picture" style="display: none" id="img" class="btn btn-default" type='file' onchange="readURL(this);"  accept="['jpg', 'gif', 'png']" />
                                            <label class="btn btn-sm btn-default" for="img">Select Image</label>
                                            </center>
                                        </div>
                                    </div>








                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content">News Content:</label>
                                        <div class="col-sm-10"><textarea name="content" id="content" rows="3" class="form-control" required><?php if(isset($selected_article['content'])) { echo $selected_article['content']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="article_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="article_status" value="1" <?php if($selected_article['status'] == '1') { echo "checked"; } ?> required>Publish</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="article_status" value="2" <?php if($selected_article['status'] == '2') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="article_status" value="3" <?php if($selected_article['status'] == '3') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#add-article-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="add-article-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Add Article Confirmation</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to save this information?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Save">
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
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