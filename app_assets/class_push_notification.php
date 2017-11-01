<?php

class Push_Notification_System
{
    public function add_new_notification($message, $recipients, $type)
    {
        global $db_handle;
        $query = "INSERT INTO push_notifications (message, recipients, notification_type) VALUES ('$message', '$recipients', '$type')";
        //var_dump($query);
        $result = $db_handle->runQuery($query);
        if ($result)
        {
            return true;
        }
        else
        {
           return false;
        }
    }

    public function get_notifications()
    {
        global $db_handle;
        $query = "SELECT * FROM push_notifications ORDER BY created DESC ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
            return $result;
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
        if ($result)
        {
            return true;
        }
        else{return false;}
    }

    public function remove_array_item( $array, $item )
    {
        $index = array_search($item, $array);
        if ( $index !== false ) {
            unset( $array[$index] );
        }

        return $array;
    }
}

$obj_push_notification = new Push_Notification_System();