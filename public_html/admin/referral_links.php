<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$links = array(
    0 => array('url' => 'https://secure.instaforex.com/en/partner_open_account.aspx?x=BBLR', 'desc' => 'Live Instaforex Account Opening'),
    1 => array('url' => 'https://instaforex.com/forexcopy_system.php?x=BBLR', 'desc' => 'Instaforex Forex Copy System'),
    2 => array('url' => 'https://instaforex.com/forex_options.php?x=BBLR', 'desc' => 'Instaforex Trading Options'),
    3 => array('url' => 'https://instaforex.com/pamm_system.php?x=BBLR', 'desc' => 'Instaforex Pamm System'),
    4 => array('url' => 'https://instaforex.com/trading_conditions.php?x=BBLR', 'desc' => 'Instaforex Trading Conditions'),
    5 => array('url' => 'https://www.instaforex.com/nodeposit_bonus.php?x=BBLR', 'desc' => 'Instaforex No Deposit Bonus'),
    6 => array('url' => 'https://www.instaforex.com/55bonus.php?x=BBLR', 'desc' => 'Instaforex 55% Bonus'),
    7 => array('url' => 'https://instaforex.com/downloads.php?x=BBLR', 'desc' => 'Instaforex Trading Terminal'),
    8 => array('url' => 'https://cabinet.instaforex.com/client/login?x=BBLR', 'desc' => 'Instaforex Client Cabinet')
    );
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
            /*function show_form(div) {
                var x = document.getElementById(div);
                if (x.style.display === 'none') {
                    x.style.display = 'block';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-plus"></i>';
                } else {
                    x.style.display = 'none';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-plus"></i>';
                }
            }*/
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
                                <table  class="table table-responsive table-striped table-bordered table-hover">
                                    <thead><tr><th>Description</th><th>Link</th></tr></thead>
                                    <tbody>
                                    <?php if(isset($links) && !empty($links)){ $count = 1; ?>
                                        <?php foreach ($links as $link){ ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($link['desc']) ?></td>
                                                <td>
                                                    <a href="<?php echo htmlspecialchars($link['url']) ?>" target="_blank"><?php echo htmlspecialchars($link['url']) ?></a>
                                                    <button title="Click To Copy" id="btn_<?php echo $count?>" onclick="copy_text('btn_<?php echo $count?>')"  data-clipboard-text="<?php echo htmlspecialchars($link['url']) ?>" data-clipboard-action="copy" class="pull-right cbtn btn btn-default btn-xs"><i class="glyphicon glyphicon-copy"></i></button>
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