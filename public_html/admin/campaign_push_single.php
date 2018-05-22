<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send']))
{
    foreach($_POST as $key => $value) {$_POST[$key] = $db_handle->sanitizePost(trim($value));}
    extract($_POST);
    if(empty($content) || empty($phone_num)) {$message_error = "All fields must be filled, please try again";}

    else
    {
        $phone_num = explode(',',$phone_num);
        foreach ($phone_num as $row)
        {
            $new_sms = $system_object->send_sms($row, $content);
            if($new_sms) {$message_success = "You have successfully sent the sms.";} else {$message_error = "Looks like something went wrong or you didn't make any change.";}
        }
    }
}

// Confirm that campaign category exist before a new sms campaign is saved
$all_campaign_category = $system_object->get_all_campaign_category();

if(!$all_campaign_category) {
    $message_error = "No campaign category created, you must create a category before any campaign. <a href=\"campaign_new_category.php\" title=\"Create new category\">Click here</a> to create one.";
}

$token = array('fJfzNVQiDxo:APA91bHgB1NIlohlDalkIZP3QtmLTkyrFhJ27NDlYwySeQcCH9erfyvw-2w-NBrreMUpqE0G3_96nTJsPM0od7V9KQDCdJio6zjPPA81V8cXI-MNA3N38k1PsvkY69yQLcgI0oU8ebnP', 
'dulBaifNd_g:APA91bHAdgQmvmxzlGRZhHp8HkWp588nkgjnwSXMDlKLn8pqmDKUEaA6b6ocOUBCp2LUCAfjSbJe_wB1JePXX5E5vbDZR7j_KuaFVTAR4jpnc_jjjH8SZIJgvdQl9Iyn5rN4o0RJ4cBZ'
);
$message = date('d-m-Y h:i:s A');
$new_push = $system_object->send_push($token, $message);
var_dump(json_decode($new_push));
?>