<?php
/**
 * Created by PhpStorm.
 * User: JOSHUA
 * Date: 2/27/2018
 * Time: 9:54 PM
 */
$admin_code = $_SESSION['admin_unique_code'];

$query5 = "SELECT 
facility_work.id AS id,
facility_work.assign AS assign 
FROM facility_work";


$result = $db_handle->runQuery($query5);
$check = $db_handle->fetchAssoc($result);
foreach ($check as $row) {
    $all_allowed_admin = $row['assign'];
    $all_allowed_admin = explode(",", $all_allowed_admin);

    for ($i = 0; $i < count($all_allowed_admin); $i++) {
        if ($admin_code == $all_allowed_admin[$i]) {
            $id = $row['id'];
            $query = "SELECT 
          facility_work.id AS work_id,
          facility_work.title AS subject, 
          facility_work.details AS details, 
          facility_work.created AS w_date,
          facility_work.location AS wlocation,
          accounting_system_office_locations.location AS location, 
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS name 
          FROM admin,facility_work,accounting_system_office_locations WHERE id = '$id' AND facility_work.created_by = admin.admin_code AND facility_work.location = accounting_system_office_locations.location_id
          ORDER BY facility_work.created DESC ";


            $numrows = $db_handle->numRows($query);
            $rowsperpage = 20;
            $totalpages = ceil($numrows / $rowsperpage);
            if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
                $currentpage = (int)$_GET['pg'];
            } else {
                $currentpage = 1;
            }
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            }
            if ($currentpage < 1) {
                $currentpage = 1;
            }
            $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
            $prespagehigh = $currentpage * $rowsperpage;
            if ($prespagehigh > $numrows) {
                $prespagehigh = $numrows;
            }
            $offset = ($currentpage - 1) * $rowsperpage;
            $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
            $result = $db_handle->runQuery($query);
            $equipments = $db_handle->fetchAssoc($result);
            foreach ($equipments as $row2) {
                echo "
<tr>
                                                <td>" . $row2['work_id'] . "</td>
                                                <td>" . $row2['w_date'] . "</td>
                                                <td>" . $row2['subject'] . "</td>
                                                <td>" . $row2['details'] . "</td>
                                                <td>" . $row2['name'] . "</td>
                                                <td>" . $row2['location'] . "</td>
                                                <td>
                                                   <form id=\"requisition_form\" data-toggle=\"validator\" class=\"form-vertical\" role=\"form\" method=\"post\" action=\"\">
                                                        <input name=\"id\" type=\"text\"  value=\"" . $row2['work_id'] . "\" hidden>
                                                        <button name='ok' type='submit' class='btn btn-primary'>Completed</button></form>
                                                        
                                                        <button type='button' data-target='#faulty" . $row2['work_id'] . "' data-toggle='modal'
                                                                class='btn btn-success'>Decline
                                                        </button>
                                                         <div id=\"faulty" . $row2['work_id'] . "\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" class=\"modal fade\">
                                        <div class=\"modal-dialog\">
                                            <div class=\"modal-content\">
                                                <div class=\"modal-header\">
                                                    <button type=\"button\" data-dismiss=\"modal\" aria-hidden=\"true\"
                                                            class=\"close\">&times;</button>
                                                    <h4 class=\"modal-title\">Decline Work Request</h4></div>
                                                <div class=\"modal-body\">
                                                                                    <form id=\"requisition_form\" data-toggle=\"validator\" class=\"form-vertical\" role=\"form\" method=\"post\" action=\"\">

                                                <div class=\"form-group\">
                                <label for=\"comment\">Kindly type in the reason for decline</label>
                                <textarea name=\"report\" class=\"form-control\" rows=\"3\" id=\"comment\" required></textarea>
                            </div></div>
                                                <div class=\"modal-footer\">
                                                <input name=\"id\" type=\"text\"  value=\"" . $row2['work_id'] . "\" hidden>
                                                    <input name=\"decline\" type=\"submit\" class=\"btn btn-success\" value=\"Proceed\">
                                                    <button type=\"submit\" name=\"close\" onClick=\"window.close();\" data-dismiss=\"modal\" class=\"btn btn-danger\">Close!</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                                   
                                                </td></tr>";
            }
        }
    }
}
?>