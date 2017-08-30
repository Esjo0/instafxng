<?php

class reminders
{

    public function add_new_reminder($admin_code, $description, $effect_date)
    {
        global $db_handle;
        $query = "INSERT INTO reminders (admin_code, description, effect_date) VALUES ('$admin_code','$description','$effect_date')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_pending_reminders() {
        global $db_handle;
        $query = "SELECT * FROM reminders WHERE status = 'ON'";
        $result = $db_handle->runQuery($query);
        $all_open_reminders = $db_handle->fetchAssoc($result);
        return $all_open_reminders;
    }

    public function get_expired_reminders() {
        global $db_handle;
        $query = "SELECT * FROM reminders WHERE status = 'OFF'";
        $result = $db_handle->runQuery($query);
        $all_closed_reminders = $db_handle->fetchAssoc($result);
        return $all_closed_reminders;
    }

    public function get_all_reminders() {
        global $db_handle;
        $query = "SELECT * FROM reminders";
        $result = $db_handle->runQuery($query);
        $all_reminders = $db_handle->fetchAssoc($result);
        return $all_reminders;
    }

    public function delete_reminder($reminder_id)
    {
        global $db_handle;
        $query = "DELETE FROM reminders WHERE reminder.reminder_id = '.$reminder_id.'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function update_reminders($reminder_id, $description, $effect_date) {
        global $db_handle;
        $db_handle->runQuery("UPDATE reminder SET  description = '$description', effect_date = '$effect_date' WHERE reminder_id = '".$reminder_id."';");
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function treated_reminders($reminder_id)
    {
        global $db_handle;
        $db_handle->runQuery("UPDATE reminders SET status = 'OFF' WHERE reminder_id = '".$reminder_id."';");
        return $db_handle->affectedRows() > 0 ? true : false;
    }

}

$obj_reminders = new reminders();