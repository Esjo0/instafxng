<?php
require_once 'init/initialize_general.php';

$dinner_emails = array("joshua@instafxng.com", "binaryvs@yahoo.com", "amazuci@yahoo.com", "bouqi4life2009@yahoo.com", "ighosam@yahoo.com", "semmymails@yahoo.com", "tochukwuo@yahoo.com");

if(isset($_POST['submit3'])){
    $email = $db_handle->sanitizePost($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
var_dump($email);
    if(in_array($email, $dinner_emails) ) {
$query  = "SELECT user_code FROM user WHERE email = '$email'";
        $result = $db_handle->runQuery($query);
        $user_code = $db_handle->fetchArray($result);
        foreach($user_code AS $row){
            extract($row);
            $encrypted_user_code = encrypt_ssl($user_code);
            header('Location: dinner.php?r=' . $encrypted_user_code);
        }
    }else{
        $message_error = "You entered a Wrong email address";
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
                <label class="text-center">Kindly Input you email address to get access to reserve your seat.</label>
                <div class="form-group col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                        <input name="email" type="text" id="" value="<?php echo $client_email; ?>" class="form-control" placeholder="Your email address" required/>
                    </div>
                </div>
                <div class="text-center" id="submit">
                    <hr>
                    <button name="submit3" type="submit" class="btn btn-success">SUBMIT</button>
                    </hr>
                </div>
                <div class="col-sm-4"></div>
            </div>
            </form>
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