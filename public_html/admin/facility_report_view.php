<?php
/**
 * Created by PhpStorm.
 * User: JOSHUA
 * Date: 2/27/2018
 * Time: 9:54 PM
 */
$admin_code = $_SESSION['admin_unique_code'];

$query5 = "SELECT 
facility_inventory.invent_id AS id,
facility_inventory.users AS user 
FROM facility_inventory";


$result = $db_handle->runQuery($query5);
$check = $db_handle->fetchAssoc($result);
foreach ($check as $row) {
    $all_allowed_admin = $row['user'];
    $all_allowed_admin = explode(",", $all_allowed_admin);

    for ($i = 0; $i < count($all_allowed_admin); $i++) {
        if ($admin_code == $all_allowed_admin[$i]) {
            $id = $row['id'];
            $query = "SELECT 
          facility_inventory.invent_id AS inventoryid,
          facility_inventory.name AS name, 
          facility_inventory.cost AS cost, 
          facility_inventory.date AS purchase_date, 
          facility_inventory.admin AS admin,
          facility_inventory.users AS user
          FROM facility_inventory WHERE invent_id = '$id'
          ORDER BY facility_inventory.created DESC ";


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
                                                <td></td>
                                                <td>" . $row2['inventoryid'] . "</td>
                                                <td>" . $row2['name'] . "</td>
                                                <td>
                                                   <form id=\"requisition_form\" data-toggle=\"validator\" class=\"form-vertical\" role=\"form\" method=\"post\" action=\"\">
                                                        <input name=\"id\" type=\"text\"  value=\"" . $row2['inventoryid'] . "\" hidden>
                                                        <button name='ok' type='submit' class='btn btn-primary'>In Good Condition</button></form>
                                                        
                                                        <button type='button' data-target='#faulty" . $row2['inventoryid'] . "' data-toggle='modal'
                                                                class='btn btn-success'>Faulty
                                                        </button>
                                                         <div id=\"faulty" . $row2['inventoryid'] . "\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" class=\"modal fade\">
                                        <div class=\"modal-dialog\">
                                            <div class=\"modal-content\">
                                                <div class=\"modal-header\">
                                                    <button type=\"button\" data-dismiss=\"modal\" aria-hidden=\"true\"
                                                            class=\"close\">&times;</button>
                                                    <h4 class=\"modal-title\">Faulty Equipment</h4></div>
                                                <div class=\"modal-body\"><p>Kindly type in the details of the fault</p>
                                                                                    <form id=\"requisition_form\" data-toggle=\"validator\" class=\"form-vertical\" role=\"form\" method=\"post\" action=\"\">

                                                <div class=\"form-group\">
                                <label for=\"comment\">If Faulty:</label>
                                <textarea name=\"report\" class=\"form-control\" rows=\"3\" id=\"comment\" required></textarea>
                            </div></div>
                                                <div class=\"modal-footer\">
                                                <input name=\"id\" type=\"text\"  value=\"" . $row2['inventoryid'] . "\" hidden>
                                                    <input name=\"process\" type=\"submit\" class=\"btn btn-success\" value=\"Proceed\">
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