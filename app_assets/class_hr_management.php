<?php

class Hr_Management
{
    function dept_code($dept)
    {
        switch ($dept)
        {
            case '1': $code = "IT"; break;
            case '2': $code = "FI"; break;
            case '3': $code = "OP"; break;
            case '4': $code = "AD"; break;
            case '5': $code = "HR"; break;
            case '6': $code = "CC"; break;
            case '7': $code = "MA"; break;
            case '8': $code = "TA"; break;
            default: $code = "XYZ"; break;
        }
        return $code;
    }
    function employee_code($length = 3)
    {
        $s = "0123456789";
        $rand = '';
        srand((double)microtime()*1000000);
        for($i = 0; $i < $length; $i++) {
            $rand.= $s[rand()%strlen($s)];
        }
        return $rand;
    }
    function add_leading_zero($value, $threshold = 3)
    {
        return sprintf('%0' . $threshold . 's', $value);
    }
    public function add_new_employee_record( $employee_records, $target_file)
    {
        global $db_handle;
        extract($employee_records);
        $query = "INSERT INTO 
        hr_employee_records (station, dept, e_type, e_cat, j_title, title, f_name, l_name, m_name, d_o_b, gender, b_group, 
        nationality, s_of_origin, l_g_a, tribe, religion, m_stat, img_url, emergency_contact_name, emergency_contact_phone, 
        emergency_contact_rel, j_date, tin, id_num, id_ex_date, address, city, state, country, p_phone, o_phone, p_email, 
        o_email)
        VALUES ('$station', '$dept', '$e_type', '$e_cat', '$j_title', '$title', '$f_name', '$l_name', '$m_name', '$d_o_b', 
        '$gender', '$b_group', '$nationality', '$s_origin', '$l_g_a','$tribe','$religion','$m_stat','$target_file',
        '$e_name','$e_pnone','$e_rel','$j_date','$tin','$id_num','$id_ex_date','$address','$city','$state','$country',
        '$p_phone','$o_phone','$p_email','$o_email')";
        $result = $db_handle->runQuery($query);

        $record_id = $db_handle->fetchAssoc($db_handle->runQuery("SELECT record_id FROM hr_employee_records WHERE f_name = '$f_name' AND l_name = '$l_name' AND p_email = '$p_email' "))[0]['record_id'];
        $record_id = $this->add_leading_zero($record_id);
        $dept_code = $this->dept_code($dept);
        $employee_code = $dept_code.$record_id;
        $record = $db_handle->runQuery("UPDATE hr_employee_records SET employee_code = '$employee_code' WHERE record_id = '$record_id' ");
        if(isset($notes)&& !empty($notes))
        {
            $this->add_employee_note($notes, $record_id);
        }
        return $result;
    }

    public function add_employee_note($notes, $record_id)
    {
        global $db_handle;
        $query = "INSERT INTO hr_employee_records_notes (admin_code, notes, record_id) VALUES ('".$_SESSION['admin_unique_code']."', '$notes', '$record_id')";
        $result = $db_handle->runQuery($query);
        return $result;
    }

    public function check_duplicate($f_name, $l_name, $p_email)
    {
        global $db_handle;
        $result = $db_handle->numRows("SELECT * FROM hr_employee_records WHERE f_name = '$f_name' AND l_name = '$l_name' AND p_email = '$p_email' ");
        if($result > 0){return true;}else{return false;}
    }

    public function get_record_details($employee_code)
    {
        global $db_handle;
        $details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM hr_employee_records WHERE employee_code = '$employee_code' LIMIT 1"));
        return $details[0];
    }

    public function update_employee_record( $employee_records, $target_file, $employee_code)
    {
        global $db_handle;
        extract($employee_records);
        $query = "UPDATE  hr_employee_records 
         SET station = , dept = , e_type = , e_cat = , j_title = , title = , f_name = , l_name = , m_name = , d_o_b = , gender = , b_group  = , 
        nationality = , s_of_origin = , l_g_a = , tribe = , religion = , m_stat = , img_url = , emergency_contact_name = , emergency_contact_phone = , 
        emergency_contact_rel = , j_date = , tin = , id_num = , id_ex_date = , address = , city = , state = , country = , p_phone = , o_phone = , p_email = , 
        o_email)
        VALUES ('$station', '$dept', '$e_type', '$e_cat', '$j_title', '$title', '$f_name', '$l_name', '$m_name', '$d_o_b', 
        '$gender', '$b_group', '$nationality', '$s_origin', '$l_g_a','$tribe','$religion','$m_stat','$target_file',
        '$e_name','$e_pnone','$e_rel','$j_date','$tin','$id_num','$id_ex_date','$address','$city','$state','$country',
        '$p_phone','$o_phone','$p_email','$o_email')";
        $result = $db_handle->runQuery($query);

        $record_id = $db_handle->fetchAssoc($db_handle->runQuery("SELECT record_id FROM hr_employee_records WHERE f_name = '$f_name' AND l_name = '$l_name' AND p_email = '$p_email' "))[0]['record_id'];
        $record_id = $this->add_leading_zero($record_id);
        $dept_code = $this->dept_code($dept);
        $employee_code = $dept_code.$record_id;
        $record = $db_handle->runQuery("UPDATE hr_employee_records SET employee_code = '$employee_code' WHERE record_id = '$record_id' ");
        if(isset($notes)&& !empty($notes))
        {
            $this->add_employee_note($notes, $record_id);
        }
        return $result;
    }
}

$obj_hr_management = new Hr_Management();