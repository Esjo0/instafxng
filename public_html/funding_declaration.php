<?php
require_once 'init/initialize_general.php';
$thisPage = "";

$page_requested = '';

if($_POST['verify_account_ifx_acct']) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $password_submitted = $db_handle->sanitizePost($_POST['pass_code']);
    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    if($user_ifx_details) {
        extract($user_ifx_details);
        if(!$client_operation->authenticate_user_password($password_submitted, $client_pass_salt, $client_password)) {
            $message_error = "Account and Pass Code combination do not match, please try again or <a href='contact_info.php'>contact support</a>.";
        }else {
            $query = "SELECT UC.passport, CONCAT(U.first_name, SPACE(1), U.last_name) AS client_name, U.middle_name, UM.address, UC.signature, U.user_code 
        FROM user_ifxaccount AS UI
        INNER JOIN user AS U ON UI.user_code = U.user_code
        INNER JOIN user_meta AS UM ON UI.user_code = UM.user_code
        INNER JOIN user_credential AS UC ON UI.user_code = UC.user_code
         WHERE UI.ifx_acct_no = '$account_no' LIMIT 1";
            $result = $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
            extract($result);
            $client_address = $client_operation->get_user_address_by_code($user_code);
            $_client_address = $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state'];
        $page_requested = "verify_account_declare_php";
        }
    } else {
        $message_error = "The account number you entered does not exist in our records.";
    }
}

if(isset($_POST['verify_account_info'])) {
    $page_requested = 'verify_account_info_php';

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    extract($user_ifx_details);

    if($_FILES["pictures_id_card"]["error"] == UPLOAD_ERR_OK) {
        if(isset($_FILES["pictures_id_card"]["name"])) {
            $tmp_name = $_FILES["pictures_id_card"]["tmp_name"];
            $name = strtolower($_FILES["pictures_id_card"]["name"]);

            // Get file extension of original uploaded file and create a new file name
            $extension = explode(".", $name);

            new_name:
            $name_string = rand_string(25);
            $newfilename = $name_string . '.' . end($extension);
            $idcard = strtolower($newfilename);

            if(file_exists("userfiles/$idcard")) {
                goto new_name;
            }

            move_uploaded_file($tmp_name, "userfiles/$idcard");
        }
    }

    if($_FILES["pictures_passport"]["error"] == UPLOAD_ERR_OK) {
        if(isset($_FILES["pictures_passport"]["name"])) {
            $tmp_name = $_FILES["pictures_passport"]["tmp_name"];
            $name = strtolower($_FILES["pictures_passport"]["name"]);

            // Get file extension of original uploaded file and create a new file name
            $extension = explode(".", $name);

            new_name2:
            $name_string = rand_string(25);
            $newfilename = $name_string . '.' . end($extension);
            $passport = strtolower($newfilename);

            if(file_exists("userfiles/$passport")) {
                goto new_name2;
            }

            move_uploaded_file($tmp_name, "userfiles/$passport");
        }
    }

    if($_FILES["pictures_signature"]["error"] == UPLOAD_ERR_OK) {
        if(isset($_FILES["pictures_signature"]["name"])) {
            $tmp_name = $_FILES["pictures_signature"]["tmp_name"];
            $name = strtolower($_FILES["pictures_signature"]["name"]);

            // Get file extension of original uploaded file and create a new file name
            $extension = explode(".", $name);

            new_name3:
            $name_string = rand_string(25);
            $newfilename = $name_string . '.' . end($extension);
            $signature = strtolower($newfilename);

            if(file_exists("userfiles/$signature")) {
                goto new_name3;
            }

            move_uploaded_file($tmp_name, "userfiles/$signature");
        }
    }

    $client_operation->set_user_meta_data($client_user_code, $address, $city, $state);
    $client_operation->set_user_credential($client_user_code, $idcard, $passport, $signature);

    $client_name = $client_first_name . " " . $client_last_name;
    $client_address = $address . " " . $city;

    if(isset($doc_update)) {
        $page_requested = 'verify_account_completed_php';
    } else {
        $page_requested = 'verify_account_declare_php';
    }

}

if(isset($_POST['verify_account_declare'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    extract($user_ifx_details);

    $client_operation->send_document_verify_email($client_first_name, $client_email);
    $page_requested = 'verify_account_completed_php';
}

switch($page_requested) {
    case '': $verify_account_ifx_acct_php = true; break;
    case 'verify_account_declare_php': $verify_account_declare_php = true; break;
    case 'verify_account_completed_php': $verify_account_completed_php = true; break;
    default: $verify_account_ifx_acct_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Client Funding Declaration</title>
        <meta name="title" content="Instaforex Nigeria | Client Document Verification" />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function print_report(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                ajax_request(printContents);
                window.print();
                document.body.innerHTML = originalContents;
                document.getElementById('old').style.display = 'none';
                document.getElementById('tnks').style.display = 'block';
            }
            function ajax_request(query) {
                var XMLHttpRequestObject = false;
                if (window.XMLHttpRequest) {XMLHttpRequestObject = new XMLHttpRequest();}
                else if (window.ActiveXObject) {XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");}
                if(XMLHttpRequestObject)
                {
                    XMLHttpRequestObject.open('POST', "views/funding_declaration/save_pdf.php");
                    XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                    XMLHttpRequestObject.setRequestHeader('charset','UTF-8');
                    XMLHttpRequestObject.send("file_content="+query);
                    XMLHttpRequestObject.onreadystatechange = function()
                    {
                        if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
                        {
                            console.log(XMLHttpRequestObject.responseText);
                        }
                    };

                }
                else {   return false;    }
            }
        </script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
               <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Instafxng Client Funding Declaration</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <?php 
                                    if($verify_account_info_php) { include_once 'views/funding_declaration/verify_account_info.php'; }
                                    if($verify_account_ifx_acct_php) { include_once 'views/funding_declaration/verify_account_ifx_acct.php'; }
                                    if($verify_account_declare_php) { include_once 'views/funding_declaration/verify_account_declare.php'; }
                                    if($verify_account_completed_php) { include_once 'views/funding_declaration/verify_account_completed.php'; }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>