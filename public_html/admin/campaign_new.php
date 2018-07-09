<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$form_fields = Loyalty_Training::DYNAMIC_LANDING_PAGE_FORM_FIELDS;
$dlp_template = Loyalty_Training::DYNAMIC_LANDING_PAGE_TEMPLATE;
$campaign_code = $obj_loyalty_training->new_campaign_code();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InstaFxNg | Create New Leads Campaign</title>
        <meta name="title" content="InstaFxNg | Create New Leads Campaign" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#dlp_content",
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
                content_css : "../css/instafx_admin.css, ../css/bootstrap_3.3.5.min.css, ../css/font-awesome_4.6.3.min.css, ../css/bootstrap-datetimepicker.css"
            });
            function copy_text(btn_id){
                var btn = document.getElementById(btn_id);
                var clipboard = new ClipboardJS(btn);
                clipboard.on('success', function(e) {
                    console.log(e);
                });
                clipboard.on('error', function(e) {
                    console.log(e);
                });
            }
            function ValidateExtension() {
                var allowedFiles = [".png", ".jpg"];
                var fileUpload = document.getElementById("blah");
                var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
                if (!regex.test(fileUpload.value.toLowerCase())) {
                    window.alert("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
                    return false;
                }
                return true;
            }
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            function slugify(input_id, output_id){
                var string = document.getElementById(input_id).value;
                string = string
                    .toString()
                    .trim()
                    .toLowerCase()
                    .replace(/\s+/g, "-")
                    .replace(/[^\w\-]+/g, "")
                    .replace(/\-\-+/g, "-")
                    .replace(/^-+/, "")
                    .replace(/-+$/, "");
                console.log(string+'/');
                document.getElementById(output_id).value += string+'/';
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
                            <h4><strong>CREATE NEW LEADS CAMPAIGN</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaigns.php" class="btn btn-default" title="Manage Leads Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage Leads Campaigns</a></p>
                                <p>Fill the form below to create a new leads campaign.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="campaign_title">Campaign Title:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <textarea id="campaign_title" onchange="slugify('campaign_title', 'dynamic_url')" required name="campaign_title" maxlength="255" class="form-control" rows="2" placeholder="Enter the flagship title of this leads campaign."></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="campaign_desc">Campaign Description:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <textarea placeholder="" required id="campaign_desc" name="campaign_desc" class="form-control" rows="2" ></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Landing Page:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <div class="form-group row">
                                                <div class="col-sm-4"><div class="radio"><label for="landing_type_1"><input required type="radio" name="landing_type" value="1" id="landing_type_1" /> Custom Built Landing Page</label></div></div>
                                                <div class="col-sm-4"><div class="radio"><label data-toggle="modal"  for="landing_type_2"><input required type="radio" name="landing_type" value="2" id="landing_type_2" /> Dynamic Landing Page</label></div></div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <br/><button class="btn btn-default btn-sm" data-target="#dynamic_landing_page" data-toggle="modal" id="dlp_trigger" type="button">Add Landing Page Contents</button>
                                                    <br/><br/><span class="text-muted"><b>NB: </b>This is the link to the lead form <a onclick="copy_text('btn_<?php echo $count?>')"  data-clipboard-text="l_e_a_d_f_o_r_m_l_i_n_k" data-clipboard-action="copy" title="Click here to copy this link" href="javascript:void(0);">l_e_a_d_f_o_r_m_l_i_n_k</a> </span><br/><br/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <b>Form Fields: </b><br/>
                                                    <?php foreach($form_fields as $key => $value){ ?>
                                                        <div class="col-sm-4"><div class="checkbox"><label title="<?php echo $value['desc'] ?>" for="field_<?php echo $key ?>"><input title="<?php echo $value['desc'] ?>" type="checkbox" name="fields[]" value="<?php echo $key ?>" id="field_<?php echo $key ?>" /> <?php echo $value['name'] ?></label></div></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                        <input name="dynamic_url" type="text" id="dynamic_url" value="https://instafxng.com/campaign/id/<?php echo $campaign_code?>/" class="form-control" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                        <input name="custom_url" type="text" id="custom_url" value="" class="form-control" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="display_picture">Lead Image:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <label class="btn btn-sm btn-default" for="img"><img width="200px" height="100px" class="img-thumbnail" id="blah" src="<?php if(isset($selected_article['display_image']) && !empty($selected_article['display_image'])) {echo "https://instafxng.com/images/blog/".$selected_article['display_image'];} else{ echo '../images/placeholder.jpg';} ?>" alt="your image" />
                                                <br/>
                                                <input name="lead_image" style="display: none" id="img" class="btn btn-default" type='file' onchange="readURL(this);"  accept="['jpg', 'gif', 'png']" />
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="article_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="status_1" type="radio" name="status" value="1" required>Active</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="status_2" type="radio" name="status" value="2" required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dynamic_landing_page" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title"><center>Dynamic Landing Page</center></h4></div>
                                                <div class="modal-body">
                                                    <textarea id="dlp_content"><?php echo $dlp_template ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#process_form" data-toggle="modal" class="btn btn-success">Process</button>
                                        </div>
                                    </div>
                                    
                                    <div id="process_form" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Process Form</h4></div>
                                                <div class="modal-body">Are you sure you want to create a new leads campaign? </div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
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