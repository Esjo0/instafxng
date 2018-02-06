<?php

class Push_Notification_System
{
    public function add_new_notification($title, $message, $recipients, $author, $notification_type, $source_url)
    {
        global $db_handle;
        $query = "INSERT INTO push_notifications (title, message, recipients, author, notification_type, source_url) VALUES ('$title', '$message', '$recipients','$author', '$notification_type', '$source_url')";
        $result = $db_handle->runQuery($query);
        return $result ? true : false;
    }

    public function get_notifications()
    {
        global $db_handle;
        $query = "SELECT * FROM push_notifications_01 ORDER BY created DESC ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
            return $result;
    }

    public function update_notification_as_old($notification_id)
    {
        global $db_handle;
        $query = "UPDATE push_notifications_01 SET status = '1' WHERE notification_id = '$notification_id' ";
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

    public function remove_array_item( $array, $item )
    {
        $index = array_search($item, $array);
        if ( $index !== false ) {unset( $array[$index] );}
        return $array;
    }
}

$obj_push_notification = new Push_Notification_System();