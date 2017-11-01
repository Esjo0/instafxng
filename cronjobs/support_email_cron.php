<?php
require_once("../public_html/init/initialize_admin.php");
function extract_emails_from($string)
{
    preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
    return $matches[0];
}


$authhost="{mail.instafxng.com:993/imap/ssl/novalidate-cert}INBOX";
$username="support@instafxng.com";
// Add the password
$password="";

ini_set('max_execution_time', 0);

/* try to connect */
$inbox = imap_open($authhost,$username,$password) or die('Cannot connect to Mail Server: ' . imap_last_error());
$count = imap_num_msg($inbox);

/* grab emails */
$emails = imap_search($inbox, 'ALL UNSEEN SINCE "25 October 2017"');

/* if emails are returned, cycle through each... */
if($emails) {

    /* begin output var */
    $output = '';

    foreach($emails as $email_number)
    {
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);
        $message = imap_body($inbox,$email_number,2);
        $subject = $overview[0]->subject;
        $email = array();
        $email[0] = $overview[0]->from;
        $email = explode(' ', $email[0]);
        $email = implode('*', $email);
        $email = extract_emails_from($email);
        $email = $email[0];
        $date_time = $overview[0]->date;
        $status = imap_setflag_full($inbox, $email_number, "\\Seen", 2);

        $subject = $db_handle->sanitizePost(trim($subject));
        $email = $db_handle->sanitizePost(trim($email));
        $date_time = $db_handle->sanitizePost(trim($date_time));
        $message = $db_handle->sanitizePost(trim($message));

        $new_mail = $obj_support_emails->set_inbox($email, $message, $subject, $date_time);
    }



}