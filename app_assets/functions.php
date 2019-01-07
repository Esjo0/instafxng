<?php

require_once 'functions_validation.php';
require_once 'functions_status_messages.php';

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
error_reporting( E_ERROR | E_WARNING | E_PARSE );

// Set the custom Error handler
set_error_handler("customError");

/**
 * String zeros from date
 * @param type $marked_string
 * @return type mixed $clean_string
 */

function get_ticket_id($email){
    global $db_handle;
    $query = "SELECT id from dinner_2018 WHERE email = '$email' AND choice = '1' AND invite_code IS NOT NULL";
    $result = $db_handle->runQuery($query);
    $id = $db_handle->fetchArray($result);
    foreach($id AS $row){
        extract($row);
        return $id;
    }
}
function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

/**
 * Redirects to a specified location
 * @param type $location
 */
function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

/**
 * 
 * @param type $message
 * @return string
 */
function output_message($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

/**
 * 
 * @param type $class_name
 */
function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $path = LIB_PATH.DS."{$class_name}.php";
    if(file_exists($path)) {
    require_once($path);
  } else { 
      die("The file {$class_name}.php could not be found."); 
      }
}

/**
 * 
 * @param type $template
 */
function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

/**
 * 
 * @param type $action
 * @param type $message
 */
function log_action($action, $message="") {
    $logfile = SITE_ROOT.DS.'admin-logs'.DS.'log.txt';
    $new = file_exists($logfile) ? false : true;
    if($handle = fopen($logfile, 'a')) { // append
        $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
        $content = "{$timestamp} | {$action}: {$message}\n";
        fwrite($handle, $content);
        fclose($handle);
        if($new) { chmod($logfile, 0755); }
    } else {
        echo "Could not open log file for writing.";
    }
}

/**
 * 
 * @param type $datetime
 * @return type
 */

function datetime_to_text3($datetime="") {
    $unixdatetime = strtotime($datetime);
    return strftime("%I:%M %p", $unixdatetime);
}


function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%b %d, %Y at %I:%M %p", $unixdatetime);
}

function datetime_to_text2($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%b %d, %Y", $unixdatetime);
}

function date_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%b %d, %Y", $unixdatetime);
}

function datetime_to_textday($datetime="") {
    $timestamp = strtotime($datetime);
    return date("D", $timestamp);
}

function datetime_to_texthour($datetime="") {
    $timestamp = strtotime($datetime);
    return date("G", $timestamp);
}
// calculate time since an action occured
function time_since($since) {
    $since = time() - strtotime($since);
    $chunks = array(
        array(31536000, 'year'), //60 * 60 * 24 * 365
        array(2592000, 'month'), //60 * 60 * 24 * 30
        array(604800, 'week'), //60 * 60 * 24 * 7
        array(86400, 'day'), //60 * 60 * 24
        array(3600, 'hour'), //60 * 60
        array(60, 'minute'),
        array(1, 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    $print = $print . " ago";
    return $print;
}

function feedback_message($action_outcome) {
    if(is_bool($action_outcome) && $action_outcome === true) {
        $feedback = "<p class=\"form_msg\">";
        $feedback .= "<img src=\"../../public/images/okay.png\" />";
        $feedback .= " Good. The performed operation was successful.";
        $feedback .= "</p>";
        return $feedback;
    } elseif (is_array($action_outcome)) {
        $feedback = "<p class=\"form_err\">";
        $feedback .= "<img src=\"../../public/images/error.png\" />";
        $feedback .= " Oops! The performed operation failed. Details below.";
        require_once ('interpret_error.php');
        
        foreach($action_outcome as $values) {
            if(array_key_exists($values, $error_list)) {
                $feedback .= " <br /> -- " . $error_list[$values];
            }
        }
        
        $feedback .= "</p>";
        return $feedback;
    }
}

/**
 * This function prepares a custome error message
 * It has been set with the set_error_handler function in the
 * initialize.php file.
 * 
 * @param type $errno
 * @param type $errstr
 * @param type $errfile
 * @param type $errline
 */
function customError($errno, $errstr, $errfile, $errline) {
    $errno = error_report_levels($errno);
    $log = "Error: [$errno] $errstr \n [$errline] $errfile";
    //error_log($log, 1, "John Azuka<onyxdatasystems@gmail.com>", "From: Instafxng <support@instafxng.com>");
} 

// This function adds some user friendlyness
// Gives the previous call date a green color if the date is today's
// date, i.e. the client has been called today, it makes it easy to
// know where the User stopped if by chance he leaves his desk, this
// makes it easy to quickly locate where he stopped.
function color_code($date) {
    $today = date('Y-m-d');
    if ($date == $today) {
        $css = "style=\"color: #007A29; font-weight: bold;\"";
    } else {
        $css = "style=\"color: #990000;\"";
    }
    return $css;
}

function encrypt_ssl($data)
{
    $encryptionMethod = "AES-256-CBC";
    $secretHash = "25c6c7ff35b9979b151f2136cd13b0ff";
    return openssl_encrypt($data, $encryptionMethod, $secretHash);
}

function decrypt_ssl($data)
{
    $encryptionMethod = "AES-256-CBC";
    $secretHash = "25c6c7ff35b9979b151f2136cd13b0ff";
    return openssl_decrypt($data, $encryptionMethod, $secretHash);
}

function encrypt($data){
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, KEY, $data, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"));
}

function decrypt($data){
    $decode = base64_decode($data);
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, KEY, $decode, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
}

function dec_enc($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = '35b9979b151f225c6c7ff136cd13b0ff';
    $secret_iv = '1234567890123456';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

/// check the below functions much later

function is_odd($n){
    return (boolean) ($n % 2);
}
//echo is_odd(5);
function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
    }
	
function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    //return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
	return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes');
	
}
	

function madSafety($string) {
    $string = stripslashes($string);
    $string = strip_tags($string);
    $string = mysql_real_escape_string($string);
    return $string;
}

function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
        return true; // or use booleans here
    } else {
        return false;
    }
}

function truncate_str($string, $limit) {
	if (strlen($string) <= $limit)
	{
		$string = $string; // Do nothing
	}
 	else
	{
		$string = wordwrap($string, $limit);
		$string = substr($string, 0, strpos($string, "\n"));
		$string .= "...";
	}
	return $string;
}	

function formatMoney($number, $fractional=false) {
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
}

function generate_sms_code() {
    $template   = '999999';
    $k = strlen($template);
    $sernum = '';
    for ($i = 0; $i < $k; $i++) {
        switch($template[$i]) {
            case 'X': $sernum .= strtolower(chr(rand(65, 90))); break;
            case '9': $sernum .= rand(0, 9); break;
        }
    }
    return $sernum;
}

function duplicate_event_registration($email) {
    global $db_handle;
    $query = "SELECT * FROM event_reg WHERE email_address = '$email' LIMIT 1";
    if($db_handle->numRows($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function invalid_ifxacct($ifx_acct) {
    global $db_handle;
    $query = "SELECT * FROM members WHERE mem_ifxaccount = $ifx_acct LIMIT 1";
    if($db_handle->numRows($query) == 0) {
        return true;
    } else {
        return false;
    }
}

function duplicate_seminar_registration($email) {
    global $db_handle;
    $query = "SELECT * FROM education_registrations WHERE email = '$email' LIMIT 1";
    if($db_handle->numRows($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function duplicate_forum_registration($email) {
    global $db_handle;
    $query = "SELECT * FROM forum_registrations WHERE email = '$email' LIMIT 1";
    if($db_handle->numRows($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function generate_content_url($content_title) {
    // Convert to lower case
    $content_title = strtolower($content_title);
    
    // Allow only alphanumeric characters
    $content_title = preg_replace("/[^A-Za-z0-9 ]/", '', $content_title);
    
    // List of filtered words
    $wordlist = array('or', 'and', 'where', 'of', 'at', 'a', 'the', 'in', 'for', 'with', 'to');
    foreach ($wordlist as &$word) {
        $word = '/\b' . preg_quote($word, '/') . '\b/';
    }
    
    // Remove filtered words
    $content_title = preg_replace($wordlist, '', $content_title);
    
    // Remove multiple spaces
    $content_title = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content_title);
        
    $limit = 7; // Limit to seven words
    if (str_word_count($content_title, 0) > $limit) {
        $words = str_word_count($content_title, 2);
        $pos = array_keys($words);
        $content_title = substr($content_title, 0, $pos[$limit]);
    }
    
    $content_title = str_replace(' ', '-', trim($content_title));
    
    return $content_title;
}

function toDateTime($unixTimestamp) {
    return date("Y-m-d H:i:s", $unixTimestamp);
}

function in_array_r($needle, $haystack, $strict = false) {
    $count = 1;
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            $item["position"] = $count;
            return $item;
        }
        $count++;
    }
    return false;
}

function array_sort_by_column_asc(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}

function array_sort_by_column_desc(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}

function modes_of_array($arr)
{
    $values = array();
    foreach ($arr as $v)
    {
        if (isset($values[$v]))
        {
            $values[$v] ++;
        }
        else
        {
            $values[$v] = 1;  // counter of appearance
        }
    }
    arsort($values);  // sort the array by values, in non-ascending order.
    $modes = array();
    $x = $values[key($values)]; // get the most appeared counter
    reset($values);
    foreach ($values as $key => $v)
    {
        if ($v == $x)
        {   // if there are multiple 'most'
            $modes[] = $key;  // push to the modes array
        }
        else
        {
            break;
        }
    }
    return $modes;
}

function getCurrentURL()
{
    $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $currentURL .= $_SERVER["SERVER_NAME"];

    if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
    {
        $currentURL .= ":".$_SERVER["SERVER_PORT"];
    }

    $currentURL .= $_SERVER["REQUEST_URI"];
    return $currentURL;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function add_activity_log()
{
    global $admin_object;
    $admin_code = $_SESSION['admin_unique_code'];
    $name = $admin_object->get_admin_name_by_code($admin_code);
    $ip = gethostbyname(gethostname());
    $date = date('d-m-Y');
    $time = date('h:i:s A');
    $url = getCurrentURL();
    $filepath = "logs".DIRECTORY_SEPARATOR.$admin_code.".txt";
    if(!file_exists($filepath)){mkdir("logs");}
    $new_log = fopen($filepath, 'a');
    $log = "Name: ".$name."\n"
        ."Ip Address: ".$ip."\n"
        ."Date: ".$date."\n"
        ."Time: ".$time."\n"
        ."Url: ".$url."\n\n";
    fwrite($new_log, $log);
    fclose($new_log);
}

function get_all_mail_templates()
{
    $path = '../mail_templates';
    $files = scandir($path);
    $count = 0;
    $count_image = 0;
    $count_html = 0;
    $templates = array();
    $images = array();
    $htmls = array();
    foreach ($files as $row)
    {
        $row = (string)$row;
        if(strlen($row) > 2 && (endsWith($row, 'JPG') || endsWith($row, 'jpg') || endsWith($row, 'PNG') || endsWith($row, 'png')))
        {
            $images[$count_image] = $row;
            $count_image++;
        }
    }
    foreach ($files as $row)
    {
        $row = (string)$row;
        if(strlen($row) > 2 && (endsWith($row, 'HTML') || endsWith($row, 'html')))
        {
            $htmls[$count_html] = $row;
            $count_html++;
        }
    }
    for($i=0; $i<count($htmls); $i++)
    {
        $templates[$count] = array('image'=>$images[$i], 'html'=>$htmls[$i]);
        $count++;
    }
    return $templates;
}

function searchForId($id, $array, $key_identifier)
{
    foreach ($array as $key => $val)
    {
        if ($val[$key_identifier] === $id)
        {
            return $key;
        }
    }
    return null;
}

/**
 * Creating date collection between two dates
 *
 * <code>
 * <?php
 * # Example 1
 * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
 *
 * # Example 2. you can use even time
 * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
 * </code>
 *
 * @author Audu Emmanuel <Emmanuel.Audu.Developer@gmail.com>
 * @param string since any date, time or datetime format
 * @param string until any date, time or datetime format
 * @param string step
 * @param string date of output format
 * @return array
 */
function date_range($from, $to, $format = 'd/m/Y' )
{
    $day = 86400;
    $startTime = strtotime($from);
    $endTime = strtotime($to);
    $numDays = round(($endTime - $startTime) / $day) + 1;
    $days = array();
    for ($i = 0; $i < $numDays; $i++) {
        $days[] = date($format, ($startTime + ($i * $day)));
    }
    return $days;
}

function split_name($full_name)
{
    $_name = array();
    $full_name = str_replace(".", "", $full_name);
    $full_name = ucwords(strtolower(trim($full_name)));
    $full_name = explode(" ", $full_name);
    if (count($full_name) == 3) {
        $_name['last_name'] = trim($full_name[0]);
        if (strlen($full_name[2]) < 3) {
            $_name['middle_name'] = trim($full_name[2]);
            $_name['first_name'] = trim($full_name[1]);
        } else {
            $_name['middle_name'] = trim($full_name[1]);
            $_name['first_name'] = trim($full_name[2]);
        }
    } else {
        $_name['last_name'] = trim($full_name[0]);
        $_name['middle_name'] = "";
        $_name['first_name'] = trim($full_name[1]);
    }
    if (empty($_name['first_name'])) {
        $_name['first_name'] = $_name['last_name'];
        $_name['last_name'] = "";
    }
    return $_name;
}

function campaign_recipients_log($name, $email, $campaign_id)
{
    $date = date('d-m-Y');
    $time = date('h:i:s A');
    $filepath = "campaign_mails".DIRECTORY_SEPARATOR.$campaign_id.".txt";
    if(!file_exists($filepath)){mkdir("campaign_mails");}
    $new_log = fopen($filepath, 'a');
    $log = encrypt_ssl($name)."/***/".encrypt_ssl($email)."/***/".encrypt_ssl($date." ".$time);
    $log = $log."\n";
    fwrite($new_log, $log);
    fclose($new_log);
}

function paginate_array($offset, $array, $benchmark)
{
    $count = 0;
    $result = array();
    for ($i = $offset; $i < count($array); $i++)
    {
        $result[] = $array[$i];
        $count++;
        if($count == $benchmark) { break; }
    }
    return $result;
}

function random_password( $length = 7 ) {
    $chars = "abcdefghijkmnpqrtwyz123456789";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

function str_replace_nth($search, $replace, $subject, $nth) {
    $found = preg_match_all('/'.preg_quote($search).'/', $subject, $matches, PREG_OFFSET_CAPTURE);

    if (false !== $found && $found > $nth) {
        return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
    }
    return $subject;
}

/*
 * $input_name = (string) Name of the markup form field
 * $upload_path = (string) Directory path for the uploaded file.
 * $desired_file_name = (string) Desired name of the uploaded file.
 *  $max_file_size = (int) Maximum file size
 * */
function upload_file($input_name, $upload_path, $desired_file_name, $max_file_size = 5 * 1024 * 1024){
    $allowed_file_types = array(
        'application/javascript',
        'application/json',
        'application/x-www-form-urlencoded',
        'application/xml',
        'application/zip',
        'application/pdf',
        'application/sql',
        'application/graphql',
        'application/ld+json',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.oasis.opendocument.text',
        'audio/mpeg',
        'audio/vorbis',
        'multipart/form-data',
        'text/css',
        'text/html',
        'text/csv',
        'text/plain',
        'image/png',
        'image/jpeg',
        'image/gif'
    );

    $feedback = array();
    if(isset($_FILES[$input_name]) && $_FILES[$input_name]["error"] == UPLOAD_ERR_OK){

        $feedback['file_properties'] = array(
            'filename' => $_FILES[$input_name]["name"],
            'filetype' => $_FILES[$input_name]["type"],
            'filesize' => $_FILES[$input_name]["size"]
        );
        extract($feedback['file_properties']);

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if($filesize > $max_file_size){
            $feedback['status'] = false;
            $feedback['status_msg'] = "Error: File size is larger than the allowed limit.";
            //exit();
        }

        if(in_array($filetype, $allowed_file_types)){
            if(!file_exists($upload_path)){
                mkdir($upload_path);
            }

            if(file_exists($upload_path.$desired_file_name)){
                $feedback['status'] = false;
                $feedback['status_msg'] = "Error: $desired_file_name already exists in $upload_path";
                //exit();
            } else{
                move_uploaded_file($_FILES[$input_name]["tmp_name"], $upload_path.$desired_file_name);
                $feedback['file_properties']['filename'] = $desired_file_name;
                $feedback['status'] = true;
                $feedback['status_msg'] = "Success: Upload successful.";
            }
        } else{
            $feedback['status'] = false;
            $feedback['status_msg'] = "Error: There was a problem uploading your file. Please try again.";
        }
    }
    else{
        $feedback['status'] = false;
        $feedback['status_msg'] = "Error: ".$_FILES[$input_name]["error"];
    }
    return $feedback;
}






/*
*Transaction Review Functions
*/
function hold_transaction($transaction_ID, $admin_code){
    global $db_handle;
    $feedback_msg = array();
    $query = "SELECT transaction_id FROM active_transactions WHERE transaction_id = '$transaction_ID' ";
    if($db_handle->numRows($query) <= 0){
        $query = "INSERT INTO active_transactions (transaction_id, admin_code) VALUES ('$transaction_ID', '$admin_code') ";
        $db_handle->runQuery($query);
        $feedback_msg['status'] = true;
        $feedback_msg['msg'] = 'This request is valid and successful.';
        return $feedback_msg;
    }else{
        $feedback_msg['status'] = false;
        $feedback_msg['msg'] = 'This request is invalid and unsuccessful.';
        return $feedback_msg;
    }
}

function release_transaction($transaction_ID, $admin_code){
    global $db_handle;
    $query = "DELETE FROM active_transactions WHERE transaction_id = '$transaction_ID' AND admin_code = '$admin_code' ";
    $db_handle->runQuery($query);
}

function allow_transaction_review($transaction_ID, $admin_code){
    global $db_handle;
    global $admin_object;
    $feedback_msg = array();
    $query = "SELECT transaction_id, admin_code FROM active_transactions WHERE transaction_id = '$transaction_ID' ";
    if($db_handle->numRows($query) > 0){
        $holder_details = $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
        //if this is true, it means the holder of this transaction is the one making the current request
        if($holder_details['admin_code'] == $admin_code){
            $feedback_msg['status'] = true;
            //$feedback_msg['holder'] = $admin_object->get_admin_name_by_code($holder_details['admin_code']);
            $feedback_msg['msg'] = 'This request is valid and successful.';
            return $feedback_msg;
        }
        //this is false, this transaction is being managed by another person
        else{
            $feedback_msg['status'] = false;
            $feedback_msg['holder'] = $admin_object->get_admin_name_by_code($holder_details['admin_code']);
            $feedback_msg['msg'] = 'This request is valid but it failed.';
            return $feedback_msg;
        }
    }else{return hold_transaction($transaction_ID, $admin_code);}
}

function clear_transactions(){
    global $db_handle;
    $max_time_diff = 0.50;  #Maximum of 1 hour
    $query = "SELECT transaction_id, admin_code, created FROM active_transactions ";
    $transactions = $db_handle->fetchAssoc($db_handle->runQuery($query));
    foreach($transactions as $row){
        $time1 = strtotime(date('Y-m-d h:i:s'));
        $time2 = strtotime($row['created']);
        $difference = (abs($time1 - $time2)) / 3600;
        if($difference >= $max_time_diff){
            $db_handle->runQuery("DELETE FROM active_transactions WHERE transaction_id = '{$row['transaction_id']}' ");
        }
    }
}
/*
*Transaction Review Functions
*/



#Edu_Sales_Tracker Functions
/*$category = array('cat_0','cat_1','cat_2','cat_3','cat_4'); */
function edu_sale_track($user_code, $category){
    global $db_handle;
    $query = "INSERT INTO edu_sales_tracker (user_code, sale_stat, sale_cat) VALUES ('$user_code', '1', '$category') ;";
    return $db_handle->runQuery($query);
}

function edu_sales_filter($query_result, $filter_criteria){
    global $db_handle;
    switch($filter_criteria){
        //all
        case '1': return $query_result;  break;

        //contacted
        case '2':
            $feedback_array = $query_result;
            foreach ($query_result as $key => $value){
                $query = "SELECT sale_stat FROM edu_sales_tracker WHERE user_code = '{$value['user_code']}'";
                $sale_stat = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['sale_stat'];
                if($sale_stat == '0' || empty($sale_stat)){
                    unset($feedback_array[$key]);
                }
            }
            return $feedback_array;
            break;

        //not contacted
        case '3':
            $feedback_array = $query_result;
            foreach ($query_result as $key => $value){
                $query = "SELECT sale_stat FROM edu_sales_tracker WHERE user_code = '{$value['user_code']}'";
                $sale_stat = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['sale_stat'];
                if($sale_stat == '1'){ unset($feedback_array[$key]); }
            }
            return $feedback_array;
            break;
    }
}

function edu_sale_untrack($category, $user_code){
    global $db_handle;
    $query = "UPDATE edu_sales_tracker SET sale_stat = '0' WHERE user_code = '$user_code' AND sale_cat = '$category' ;";
    return $db_handle->runQuery($query);
}

function edu_sale_track_reset($category){
    global $db_handle;
    $query = "DELETE FROM edu_sales_tracker WHERE sale_cat = '$category' ";
    return $db_handle->runQuery($query);
}

function UI_sale_status($user_code, $category){
    global $db_handle;
    $query = "SELECT sale_stat, created FROM edu_sales_tracker WHERE sale_cat = '$category' AND user_code = '$user_code' ";
    $sale_stat = (int) $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['sale_stat'];
    $created =  datetime_to_text($db_handle->fetchAssoc($db_handle->runQuery($query))[0]['created']);
    if($sale_stat == 0 || empty($sale_stat)){
        $button = '<button title="Flag this client as contacted" name="edu_sale_track" type="submit" class="btn btn-group-justified btn-xs btn-info"><i class="fa fa-check"></i></buttonFlag>';
    }elseif($sale_stat == 1){
        $button = '<button title="This client has been contacted" disabled name="edu_sale_track" type="submit" class="btn btn-group-justified btn-xs btn-default"><i class="fa fa-check"></i></button>';
    }
    $markup = <<<MAIL
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
<div class="input-group">
<input type="hidden" name="user_code" value="{$user_code}" >
<input type="hidden" name="category" value="{$category}" >
{$button}
</div>
</form>
MAIL;
    echo $markup;
}
#Edu_Sales_Tracker Functions

/*
 * Clients Call Log
 * Track Clients that have been contacted and thosde that need follow up
 */
function call_log_status($user_code, $source){
    global $db_handle;

    $query = "SELECT * FROM call_log WHERE user_code = '$user_code' AND source = '$source' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $result = $db_handle->fetchArray($result);
    $numrows = $db_handle->numRows($query);
    if($numrows == 1){
    foreach($result AS $row){
        extract($row);
        $date = datetime_to_text($created);
        if($status == 1){
            $display = <<<CONTACT
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
<div class="input-group">
<input type="hidden" name="user_code" value="{$user_code}" >
<i data-toggle="tooltip" data-placement="top" title="Contacted on {$date}">Contacted</i>
<button  class="btn btn-secondary" title="Click to follow client up or call back" type="button" data-toggle="modal" data-target="#{$user_code}" class="btn btn-sm">
<i class="glyphicon glyphicon-phone icon-white"></i>
</button>
</div>
<!-- Modal -->
<div class="modal fade" id="{$user_code}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add A comment to log client for a follow up call or a call back</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <textarea rows="3" class="form-control" name="comment" placeholder="Enter Commment"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button name="follow_up" type="submit" class="btn btn-primary">SAVE</button>
      </div>
    </div>
  </div>
</div>
<!--Modal End -->
</form>
CONTACT;

        }elseif($status == 2){
            $display = <<<CONTACT
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
<div class="input-group">
<input type="hidden" name="user_code" value="{$user_code}" >
<button name="called" title="Flag As Contacted" type="submit" class="btn btn-secondary">
<i class="glyphicon glyphicon-check icon-white"></i>
</button>
<i data-toggle="tooltip" data-placement="top" title="{$follow_up_comment}">
Follow Up
</i>
</div>
</form>
CONTACT;
        }
    }

    }elseif($numrows == 0){
        $display = <<<CONTACT
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
<div class="input-group">
<input type="hidden" name="user_code" value="{$user_code}" >
<button name="called" title="Flag As Contacted" type="submit" class="btn btn-secondary">
<i class="glyphicon glyphicon-check icon-white"></i>
</button>
<button class="btn btn-secondary" title="Click to follow client up or call back" type="button" data-toggle="modal" data-target="#{$user_code}" class="btn btn-sm">
<i class="glyphicon glyphicon-phone icon-white"></i>
</button>
</div>
<!-- Modal -->
<div class="modal fade" id="{$user_code}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add A comment to log client for a follow up call or a call back</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <textarea rows="3" class="form-control" name="comment" placeholder="Enter Commment"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button name="follow_up" type="submit" class="btn btn-primary">SAVE</button>
      </div>
    </div>
  </div>
</div>
<!--Modal End -->
</form>
CONTACT;
    }
    echo $display;
}