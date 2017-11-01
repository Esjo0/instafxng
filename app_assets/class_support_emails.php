<?php

class support_emails
{

    public function save_email($admin_code, $message, $subject, $recipient)
    {
        global $db_handle;
        $query = "INSERT INTO support_email_sent_box (email_sender, email_body, email_subject, email_recipient) VALUES ('$admin_code', '$message', '$subject', '$recipient')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function send_email($admin_code, $message, $subject, $recipient)
    {
        global $db_handle;
        $query = "INSERT INTO support_email_sent_box (email_sender, email_status,  email_body, email_subject, email_recipient) VALUES ('$admin_code', 'SENT', '$message', '$subject', '$recipient')";
        $db_handle->runQuery($query);
        $name = "";
        $system_object = new InstafxngSystem();
        $email = $system_object->send_email($subject, $message, $recipient, $name);
        return  $email;
    }

    public function get_inbox_last_update()
    {
        global $db_handle;
        $query = "SELECT last_update FROM support_email_settings WHERE support_email_settings.type = 'INBOX UPDATE' LIMIT 1";
        $query = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($query);
        return $result;
    }

    public function read_email($email_id)
    {
        global $db_handle;
        $query = "SELECT * FROM support_email_inbox WHERE support_email_inbox.email_id = '$email_id' LIMIT 1";
        $query = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($query);
        return $result;
    }

    public function read_sent_email($email_id)
    {
        global $db_handle;
        $query = "SELECT * FROM support_email_sent_box WHERE support_email_sent_box.email_id = '$email_id' LIMIT 1";
        $query = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($query);
        return $result;
    }

    public function set_inbox_last_update($current_date)
    {
        global $db_handle;
        $query = "UPDATE support_email_settings SET last_update = '$current_date' WHERE support_email_settings.type = 'INBOX UPDATE'";
        $query = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($query);
        return $result;
    }

    public function set_as_assigned($email_id, $admin_code)
    {
        global $db_handle;
        $query = "UPDATE support_email_inbox SET email_admin_assigned = '$admin_code' WHERE support_email_inbox.email_id = '$email_id'";
        $query = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($query);
        return $result;
    }

    public function check_assigned($email_id, $admin_code)
    {
        global $db_handle;
        $query = "SELECT email_admin_assigned FROM support_email_inbox WHERE support_email_inbox.email_id = '$email_id' AND support_email_inbox.email_admin_assigned = '$admin_code' LIMIT 1";
        $query = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($query);
        return $result;
    }

    public function set_inbox($from_address, $message, $subject, $date)
    {
        global $db_handle;
        $query = "INSERT INTO support_email_inbox (email_sender, email_body, email_subject, email_created) VALUES ('$from_address', '$message', '$subject', '$date')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

}

$obj_support_emails = new support_emails();