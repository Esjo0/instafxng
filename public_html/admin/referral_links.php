<?php
/**
 * Created by PhpStorm.
 * User: Joshua
 * Date: 25/06/2018
 * Time: 11:33 AM
 */
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if(isset($_POST['save'])){
    $url = $db_handle->sanitizePost($_POST['url']);
    $desc = $db_handle->sanitizePost($_POST['desc']);
    // load the data and delete the line from the array
    $lines = file('referral_links.xml');
    $last = sizeof($lines) - 1 ;
    unset($lines[$last]);


    $myfile = fopen("referral_links.xml", "w") or die("Unable to open file!");
    fwrite($myfile, implode('', $lines));
    $txt = "\n";
    fwrite($myfile, $txt);
    fwrite($myfile, '<link>');
    $txt = "\n";
    fwrite($myfile, $txt);
    fwrite($myfile, "<url>".$url."</url>");
    $txt = "\n";
    fwrite($myfile, $txt);
    fwrite($myfile, "<desc>".$desc."</desc>");
    $txt = "\n";
    fwrite($myfile, $txt);
    fwrite($myfile, "</link>");
    $txt = "\n";
    fwrite($myfile, $txt);
    fwrite($myfile, "</referral>");
    fclose($myfile);
}

$all_admin_member = $admin_object->get_all_admin_member();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Commissions Report</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Commissions Report" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/js/mdb.min.js"></script>
    <script>
        function show_form(div)
        {
            var x = document.getElementById(div);
            if (x.style.display === 'none')
            {
                x.style.display = 'block';
                document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-plus"></i>';
            }
            else
            {
                x.style.display = 'none';
                document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-plus"></i>';
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
                    <h4><strong>INSTAFOREX REFERRAL LINKS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <h5>List of All instafxng referral links to InstaForex
                            <button title="Add a new link" id="trigger" onclick="show_form('filter')" class="btn btn-sm btn-default pull-right">
                                <i class="glyphicon glyphicon-plus"></i>
                            </button>
                        </h5>
                        <div style="display: none" id="filter">
                            <center>
                                <p>Add a new referral link</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <div class="input-group date">
                                                <input  name="url" type="text" class="form-control" id="link" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="input-group date">
                                                <input  name="desc" type="text" class="form-control" id="desc" placeholder="Enter details about this link" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <input name="save" type="submit" class="btn btn-success" value="SAVE" />
                                        </div>
                                        <div class="col-sm-1"></div>

                                    </div>

                                </form>
                            </center>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <table  class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Referal Link</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                        <?php
                        $xml=simplexml_load_file("referral_links.xml") or die("Error: Cannot create object");
                        foreach($xml->children() as $links) {
                            $i = $i + 1;

                            $copy = $links->url;
                            echo "<tr><td>".$links->url."
                            <span id='".$i."' onclick='copy_text".$i."()' class='pull-right'><i class='glyphicon glyphicon-duplicate'></i></span></td>";
                            echo "<td>".$links->desc."</td></tr>";?>
                            <script>
                                function copy_text<?php echo "$i";?>() {
                                    var copyText = "<?php echo "$copy";?>" ;
                                    copyText.select();
                                    document.execCommand("copy");
                                    document.getElementById('<?php echo "$i"; ?>').innerHTML = '<i class="glyphicon glyphicon-ok"></i>';
                                }
                            </script>
                       <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                </div>
            </div>

                    </div>
                </div>

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