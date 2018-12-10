<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25/10/2018
 * Time: 7:06 AM
 */
require_once 'init/initialize_client.php';
$thisPage = "";

$date_now = datetime_to_text(date('Y-m-d H:i:s'));


if(isset($_POST['invite'])){
    $email = $db_handle->sanitizePost($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $query = "SELECT invite_code,title,town,name FROM dinner_2018 WHERE email = '$email' AND invite_code IS NOT NULL LIMIT 1";
    $result = $db_handle->runQuery($query);
    $numrows = $db_handle->numRows($query);
    $result = $db_handle->fetchAssoc($result);
    if($numrows == 1){
        foreach($result AS $row) {
            extract($row);
            $name = split_name($name);
            extract($name);
            $message_final = <<<MAIL
   <body style="background-image: url(images/invite.jpg);
                background-repeat: no-repeat;
                 background-size: cover;">
                    <center><div style="color:#645e44; font-size: 25px; font-family:'Times New Roman' cursive;margin-left: 172px; padding-top:365px;" id="name"><i>$title $first_name of $town</i></div></center>
                                                         <div style="font-size: 20px; font-family: 'Parisienne', cursive; margin-left: 250px; margin-top:315px;"><b>$invite_code</b></div>
    </body>
MAIL;

            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
                'orientation' => 'P'
            ]);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($message_final);
            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle("2018 Dinner Invite - Instafxng.com");
            $mpdf->SetAuthor("Instant Web-Net Technologies Ltd");
            $mpdf->SetWatermarkText("InstaFxNg Royal Ball - INVITE");
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetFooter("Date Generated: " . $date_now);

            $mpdf->Output('InstaFxNg Royal Invite.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        }
    }else{
        $message_error = "You Have Not Reserved a seat earlier";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="title" content="InstaFxNg Royal Ball" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InstaFxNg Royal Ball</title>
    <meta name="keywords" content=" ">
    <meta name="description" content=" ">
    <link rel="stylesheet" href="css/free_seminar.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Pacifico|Patua+One" rel="stylesheet">
    <?php require_once 'layouts/head_meta.php'; ?>
    <link rel="stylesheet" href="css/prettyPhoto.css">
    <style>
        .photo_g > ul, .photo_g > li {
            display: inline;
        }
    </style>
    <style>
        body {
            background: url("images/full-bloom.png") repeat;
        }
    </style>
</head>

<body>
<!-- Header Section: Logo and Live Chat  -->
<header id="header">
    <div class="container-fluid no-gutter masthead">
        <div class="row">
            <div id="main-logo" class="col-sm-12 col-md-5">
                <a href="./" title="Home Page"><img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></a>
            </div>

        </div>
    </div>
    <hr />
</header>

<section class="container-fluid">
    <div class="row text-center">
        <?php include 'layouts/feedback_message.php'; ?>

        <div class="col-sm-12 text-center">
            <h1 style="color:#db281f; font-size: 45px !important;"><span style="font-family: 'Patua One', cursive;">InstaFxNg </span><span style="font-family: 'Pacifico', cursive;">Royal Ball</span></h1>
        </div>
    </div>
</section>
<section class="container-fluid">
    <div class="row">
        <div class="col-sm-4"></div>
        <form class="col-sm-4" action="" method="post">
            <label class="text-center">Kindly Input you email address to download your invite.</label>
            <div class="form-group col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                    <input name="email" type="text" id="" value="<?php echo $client_email; ?>" class="form-control" placeholder="Your email address" required/>
                </div>
            </div>
            <div class="text-center" id="submit">
                <hr>
                <button name="invite" type="submit" class="btn btn-success">DOWNLOAD INVITE</button>
                </hr>
            </div>
            <div class="col-sm-4"></div>
    </div>
    </form>
</section>

<section class="container-fluid no-gutter" style="background-color: white; margin-top: 15px;">
    <div class="row">
        <div class="col-sm-6">
            <h3>About the Royal Ball event</h3>
            <p>The InstaFxNg 2018 dinner event is themed ‘Royal Ball' in celebration of your loyalty and
                support to our brand.</p>
            <p>This year's dinner is set to celebrate your royalty and loyalty in crown, wine and great
                splendor!</p>
            <p>We're set to bring you a wonderful and an unforgettable evening at the ball as we host you to
                an amazing experience of royalty that you are.</p>
            <p>Your throne is set and the dinner is royal!</p>
            <p>Come and be a part of our extravagant experience.</p>
        </div>

        <div class="col-sm-6" style="padding: 0 !important;">
            <h3 style="padding: 15px !important;">Moments from Ethnic Impression 2017</h3>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/6H2j_4cwnYE?rel=0"></iframe>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section: Copyright, Site Map  -->
<footer id="footer" class="super-shadow" style="fixed: 'bottom';">
    <div class="container-fluid no-gutter copyright">
        <div class="col-sm-12">
            <p class="text-center">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
        </div>
    </div>
</footer>

</body>
</html>