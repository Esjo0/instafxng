<?php
require_once("../init/initialize_admin.php");
if ($session_admin->is_logged_in()) {
    redirect_to("index.php");
}

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $username = strip_tags(trim($_POST['username']));
    $password = strip_tags(trim($_POST['password']));
    
    // Check database to see if username/password exist.
    $found_user = $admin_object->authenticate($username, $password);
    
    if($found_user) {
        if($admin_object->admin_is_active($username)) {
            $found_user = $found_user[0];
            $session_admin->login($found_user);
            redirect_to("index.php");
        } else {
            $message_error = "Your profile has certain issues, please contact support.";
        }
    } else {
        // username/password combo was not found in the database
        $message_error = "Username and password combination do not match.";
    }
} else { // Form has not been submitted.
    $username = "";
    $password = "";
}

if(isset($_GET['logout'])) {
    $logout_code = $_GET['logout'];
    switch ($logout_code) {
        case 1:
            $message_success = "You have logged out";
            break;
        case 2:
            $message_success = "You have been auto-logged out due to inactivity";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instafxng Admin | Log in</title>
        <meta name="author" content="Lekan Esan <lekan@instafxng.com>">
        <link rel="stylesheet" href="../css/instafx.css">
        <link rel="stylesheet" href="../css/instafx_admin.css">
        <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
        <link rel="stylesheet" href="../css/bootstrap_4.0.0.min.css">
        <link rel="stylesheet" href="../css/font-awesome_4.3.0.min.css">
        <script src="../js/jquery_2.1.1.min.js"></script>
        <script src="../js/bootstrap_4.0.0.min.js"></script>
        <script src="../js/ie10-viewport-bug-workaround.js"></script>
    </head>
    <body>
        <div class="container-fluid" style="margin-top: 50px; max-width: 400px !important;">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <img src="../images/ifxlogo.png" alt="Instaforex Nigeria">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php if(isset($message_success)): ?>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo $message_success; ?>
                    </div>
                    <?php endif ?>
                    <?php if(isset($message_error)): ?>
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo $message_error; ?>
                    </div>
                    <?php endif ?>
                    
                    <h4 class="text-center">Instafxng Admin</h4>
                    <p class="text-center">Log in to access the Admin area!</p>
                    <form role="form" method="post" action="login.php">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <input type="text" placeholder="Username" name="username" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                                <input type="password" placeholder="Password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <input type="submit" name="submit" class="btn btn-success" value="Login">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>