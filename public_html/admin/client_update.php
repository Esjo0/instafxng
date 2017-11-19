<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$get_params = allowed_get_params(['id', 'p']);
$user_code_encrypted = $get_params['id'];
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

$page_url = "client_update.php?id=" . $user_code_encrypted;

$page_requested = $get_params['p'];

/**
 * Process contact update i.e. email and phone number
 */
if (isset($_POST['contact_update'])) {

    $user_code_encrypted = $_POST["client_unique"];
    $user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
    $user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

    $email_address = $db_handle->sanitizePost($_POST["email_address"]);
    $phone_number = $db_handle->sanitizePost($_POST["phone_number"]);
    $state_id = $db_handle->sanitizePost($_POST["state"]);
    $city = $db_handle->sanitizePost($_POST["city"]);
    $address = $db_handle->sanitizePost($_POST["address"]);

    $query = "UPDATE user SET email = '$email_address', phone = '$phone_number' WHERE user_code = '$user_code' LIMIT 1";
    $update1 = $db_handle->runQuery($query);

    $query = "UPDATE user_meta SET address = '$address', city = '$city', state_id = $state_id WHERE user_code = '$user_code' LIMIT 1";
    $update2 = $db_handle->runQuery($query);

    if($update1 || $update2) {
        $message_success = "You have successfully made an update";
    } else {
        $message_error = "Something went wrong, the modifications were not saved or you did not make any change.";
    }

}

/**
 * Process bank account update
 */
if (isset($_POST['account_update'])) {

    $user_code_encrypted = $_POST["client_unique"];
    $user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
    $user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

    $bank_id = $_POST["bank_name"];
    $bank_acct_name = $_POST["bank_acct_name"];
    $bank_acct_number = $_POST["bank_acct_number"];

    if($db_handle->numRows("SELECT user_code FROM user_bank WHERE user_code = '$user_code' LIMIT 1") > 0) {
        $query = "UPDATE user_bank SET status = '2', bank_acct_name = '$bank_acct_name', bank_acct_no = '$bank_acct_number', bank_id = $bank_id WHERE user_code = '$user_code' LIMIT 1";
        $db_handle->runQuery($query);
    } else {
        $query = "INSERT INTO user_bank (user_code, bank_acct_name, bank_acct_no, bank_id, status) VALUES
              ('$user_code', '$bank_acct_name', '$bank_acct_number', $bank_id, '2')";
        $db_handle->runQuery($query);
    }

    if($db_handle->affectedRows() > 0) {
        $message_success = "You have successfully made an update";
    } else {
        $message_error = "Something went wrong, the modifications were not saved or you did not make any change.";
    }

}


/**
 * Process Document Update
 */
if(isset($_POST['document_update'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $user_code_value = decrypt(str_replace(" ", "+", $user_no));
    $user_code_value = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code_value);

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

            if(file_exists("../userfiles/$idcard")) {
                goto new_name;
            }

            move_uploaded_file($tmp_name, "../userfiles/$idcard");

            $query = "UPDATE user_credential SET idcard = '$idcard', status = '1' WHERE user_code = '$user_code_value' LIMIT 1";
            $db_handle->runQuery($query);
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

            if(file_exists("../userfiles/$passport")) {
                goto new_name2;
            }

            move_uploaded_file($tmp_name, "../userfiles/$passport");

            $query = "UPDATE user_credential SET passport = '$passport', status = '1' WHERE user_code = '$user_code_value' LIMIT 1";
            $db_handle->runQuery($query);
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

            if(file_exists("../userfiles/$signature")) {
                goto new_name3;
            }

            move_uploaded_file($tmp_name, "../userfiles/$signature");

            $query = "UPDATE user_credential SET signature = '$signature', status = '1' WHERE user_code = '$user_code_value' LIMIT 1";
            $db_handle->runQuery($query);
        }
    }

}


/**
 * Ensure you are making update for a real client and that the URL is not tampered
 */
if(is_null($user_code_encrypted) || empty($user_code_encrypted)) {
    redirect_to("./"); // page cannot display anything without the id
} else {
    $user_code = $db_handle->sanitizePost($user_code);
    $user_detail = $client_operation->get_user_by_user_code($user_code);

    if($user_detail) {
        extract($user_detail);

        if($middle_name) {
            $full_name = $last_name . ' ' . $middle_name . ' ' . $first_name;
        } else {
            $full_name = $last_name . ' ' . $first_name;
        }

        $total_client_account = $db_handle->numRows("SELECT ifx_acct_no FROM user_ifxaccount WHERE user_code = '$user_code'");
        $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($user_code);
        $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($user_code);
        $client_address = $client_operation->get_user_address_by_code($user_code);
        $client_verification = $client_operation->get_client_verification_status($user_code);
        $client_credential = $client_operation->get_user_credential($user_code);
        $client_bank_account = $client_operation->get_user_bank_account($user_code);
        $client_phone_code = $client_operation->get_user_phonecode($user_code);
        $all_banks = $system_object->get_all_banks();
        $all_states = $system_object->get_all_states();
        $selected_user_docs = $client_operation->get_user_docs_by_code($user_code);

        switch($client_verification) {
            case '0': $verification_level = "Not Verified"; break;
            case '1': $verification_level = "Level 1 Verified"; break;
            case '2': $verification_level = "Level 2 Verified"; break;
            case '3': $verification_level = "Level 3 Verified"; break;
        }
    } else {
        // No record for that client, it is possible that URL is tampered
        redirect_to("./");
    }
}

switch($page_requested) {
    case 'con': $client_update_contact_php = true; break;
    case 'acc': $client_update_bank_php = true; break;
    case 'ver': $client_update_credential_php = true; break;
    default: $client_update_contact_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Detail</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Client Update" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
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
                            <h4><strong>UPDATE CLIENT DETAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row text-center">
                                    <div class="col-sm-12">
                                        <div class="btn-group btn-breadcrumb">
                                            <a href="<?php echo $page_url . '&p=con'; ?>" class="btn btn-default" title="Contact Details">Contact Details</a>
                                            <a href="<?php echo $page_url . '&p=acc'; ?>" class="btn btn-default" title="Bank Account">Bank Account</a>
                                            <a href="<?php echo $page_url . '&p=ver'; ?>" class="btn btn-default" title="Verification Document">Verification Document</a>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <hr />

                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="text-center"><strong><?php echo $full_name; ?></strong></p>

                                <?php
                                    if($client_update_contact_php) { include_once 'views/client_update/update_contact.php'; }
                                    if($client_update_bank_php) { include_once 'views/client_update/update_bank.php'; }
                                    if($client_update_credential_php) { include_once 'views/client_update/update_credential.php'; }
                                ?>
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