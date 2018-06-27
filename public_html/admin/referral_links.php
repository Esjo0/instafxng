<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$xml_filename = "external_resource_links.xml";
if(isset($_POST['save'])) {
    $url = $db_handle->sanitizePost($_POST['url']);
    $desc = $db_handle->sanitizePost($_POST['desc']);

    if(!file_exists($xml_filename)) {
        $_file = fopen($xml_filename, "w");
        fwrite($_file, '<resources>');
        fclose($_file);
    }

    $lines = file($xml_filename);
    $last = sizeof(file($xml_filename)) - 1 ;
    unset($lines[$last]);
    $_file = fopen($xml_filename, "w");
    fwrite($_file, implode('', $lines));
    fwrite($_file, '<resource>');
    fwrite($_file, "<url>".$url."</url>");
    fwrite($_file, "<desc>".$desc."</desc>");
    fwrite($_file, "</resource>");
    fwrite($_file, "\n");
    fwrite($_file, "</resources>");
    fclose($_file);
}
if(file_exists($xml_filename)){
    $xml = simplexml_load_file($xml_filename);
    $links = $xml->children();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Foreign Links</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Foreign Links" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        function show_form(div) {
            var x = document.getElementById(div);
            if (x.style.display === 'none') {
                x.style.display = 'block';
                document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-plus"></i>';
            } else {
                x.style.display = 'none';
                document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-plus"></i>';
            }
        }
        function copy_text(btn_id) {
            var btn = document.getElementById(btn_id);
            var clipboard = new ClipboardJS(btn);
            clipboard.on('success', function(e) {
                console.log(e);
            });
            clipboard.on('error', function(e) {
                console.log(e);
            });

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
                        <h4><strong>INSTAFOREX FOREIGN LINKS</strong></h4>
                    </div>
                </div>

                <div class="section-tint super-shadow">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <p class="pull-left">List of all the direct links to instaforex website.</p>
                            <p class="pull-right"><button title="Add a new link" id="trigger" onclick="show_form('filter')" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i>  Click Here To Add A New Link</button></p>
                            <div id="filter">
                                <p>Add a new referral link</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="input-group date">
                                        <input  name="url" type="text" class="form-control" id="link" required>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>
                                    </div>
                                    <div class="input-group date">
                                        <input  name="desc" type="text" class="form-control" id="desc" placeholder="Enter details about this link" required>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></span>
                                    </div>
                                    <input name="save" type="submit" class="btn btn-success" value="SAVE" />
                                </form>
                            </div>
                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($links) && !empty($links)){ $count = 1; ?>
                                    <?php foreach ($links as $link){ ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($link->desc) ?></td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($link->url) ?>" target="_blank"><?php echo htmlspecialchars($link->url) ?></a>
                                                <button id="btn_<?php echo $count?>" onclick="copy_text('btn_<?php echo $count?>')"  data-clipboard-text="<?php echo htmlspecialchars($link->url) ?>" data-clipboard-action="copy" class="pull-right cbtn btn btn-default btn-xs"><i class="glyphicon glyphicon-copy"></i></button>
                                            </td>
                                        </tr>
                                        <?php $count++; } ?>
                                <?php }else{ ?>
                                    <tr><td colspan="2" class="text-center text-danger">No links found!</td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Unique Page Content Ends Here
        ================================================== -->
    </div>
    <?php require_once 'layouts/footer.php'; ?>
</body>
</html>