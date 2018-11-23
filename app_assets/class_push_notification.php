<?php

class Push_Notification_System
{
    public function add_new_notification($title, $message, $recipients, $author, $source_url)
    {
        global $db_handle;
        $query = "INSERT INTO push_notifications (title, message, recipients, author, source_url) VALUES ('$title', '$message', '$recipients','$author', '$source_url')";
        $result = $db_handle->runQuery($query);
       return $result ? true : false;
    }

    public function get_notifications()
    {
        global $db_handle;
        $query = "SELECT * FROM push_notifications ORDER BY created DESC ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        return $result;

    }

    public function update_notification_as_old($notification_id)
    {
        global $db_handle;
        $query = "UPDATE push_notifications SET status = '1' WHERE notification_id = '$notification_id' ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        return $result ? true : false;
    }

    public function dismiss_notification($admin_code, $notification_id)
    {
        global $db_handle;
        $query = "SELECT * FROM push_notifications WHERE notification_id = '$notification_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        $recipients = $result[0]['recipients'];
        $recipients = explode("," ,$recipients);
        $recipients = $this->remove_array_item($recipients, $admin_code);
        $recipients = implode("," ,$recipients);
        $query = "UPDATE push_notifications SET recipients = '$recipients' WHERE notification_id = '$notification_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        return $result ? true : false;
    }
    public function dismiss_all_notifications($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM push_notifications ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        foreach ($result as $row)
        {
            $recipients = explode("," ,$row['recipients']);
            if(in_array($admin_code, $recipients))
            {
                $recipients = $this->remove_array_item($recipients, $admin_code);
            }
            $recipients = implode("," ,$recipients);
            $query = "UPDATE push_notifications SET recipients = '$recipients' WHERE notification_id = '".$row['notification_id']."' ";
            $result = $db_handle->runQuery($query);
        }
        return $result ? true : false;
    }

    public function remove_array_item( $array, $item )
    {
        $index = array_search($item, $array);
        if ( $index !== false ) {unset( $array[$index] );}
        return $array;
    }

    public function get_recipients_by_access($access_id)
    {
        global $db_handle;
        $query = "SELECT * FROM admin_privilege ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        $recipients = '';
        foreach ($result as $row)
        {
            $access = explode(',', $row['allowed_pages']);

            // Remove PERCY UTOMUDO FROM NOTIFICATION
            if(in_array($access_id, $access) && $row['admin_code'] != 'nyAEh' && $row['admin_code'] != 'V2Uu9')
            {
                $recipients.= $row['admin_code'].',';
            }
        }
        return $recipients;
    }
}

$obj_push_notification = new Push_Notification_System();