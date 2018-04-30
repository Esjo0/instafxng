<?php
class facility{

    public function inventory($invent_id, $iname, $cost, $idate, $all_allowed_admin,$location,$cart) {
        global $db_handle;

        $query = "INSERT INTO facility_inventory (invent_id,name,cost,date,admin,location,category) VALUES ('$invent_id','$iname','$cost','$idate','$all_allowed_admin','$location','$cart')";
        return $db_handle->runQuery($query) ? true : false;
    }
    public function servicing($invent_id, $cost, $executor, $type ,$next, $details) {
        global $db_handle;

        $query2 = "INSERT INTO facility_servicing (invent_id,cost,executor,type,next,details) VALUES ('$invent_id','$cost','$executor','$type','$next','$details')";
        return $db_handle->runQuery($query2) ? true : false;
    }
    public function get_admin_detail_by_code($admin_code) {
        global $db_handle;

        $query = "SELECT last_name, first_name, email, status FROM admin WHERE admin_code = '$admin_code' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            return $fetched_data[0];
        } else {
            return false;
        }

    }
    public function get_order_lista($order_code,$options)
    {
        global $db_handle;
        $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$order_code' AND item_app = '$options'";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }
    public function get_service_list($invent_id)
    {
        global $db_handle;
        $query = "SELECT 
          facility_servicing.date AS date,
          facility_servicing.details AS details, 
          facility_servicing.cost AS cost, 
          facility_servicing.type AS type,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, facility_servicing 
          WHERE invent_id = '$invent_id' 
          AND facility_servicing.executor = admin.admin_code";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

}
$obj_facility = new facility();
