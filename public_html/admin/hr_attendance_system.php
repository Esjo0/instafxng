<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
function GetFirstThree($ip)
{
    if(strlen($ip) <= 10) { $pos = strpos($ip, '.', strlen($ip)-3);}
    else {$pos = strpos($ip, '.', strlen($ip)-4);}
    $ip = substr($ip, 0, $pos);
    $ip = substr($ip, 0, strlen($ip));
    return $ip;
}

$ip_address = GetFirstThree($db_handle->sanitizePost(gethostbyname(trim(`hostname`))));
//var_dump(getHostByName(getHostName()));
$today = $db_handle->sanitizePost(date("d-m-Y"));
$time = $db_handle->sanitizePost(date("h:i:s"));
$day = date('l', strtotime($today));
if ($day != 'Saturday' || !$day != 'Sunday')
{
    $admin_code = $_SESSION['admin_unique_code'];
    //$location = $db_handle->sanitizePost($_POST['location']);
    $query = "SELECT * FROM hr_attendance_log WHERE hr_attendance_log.admin_code = '$admin_code' AND hr_attendance_log.date = '$today' ";
    $result = $db_handle->numRows($query);
    if($result < 1)
    {
        $query = "SELECT * FROM hr_attendance_locations WHERE ip_address = '$ip_address' ";
        $result = $db_handle->numRows($query);

        if($result > 0)
        {
            $result = $db_handle->runQuery($query);
            $location_details = $db_handle->fetchAssoc($result);
            extract($location_details);
            $location_details = $location_details[0];
            $query = "INSERT INTO hr_attendance_log (admin_code, date, time, location) VALUES ('$admin_code', '$today', '$time', '".$location_details['location']."')";
            $result = $db_handle->runQuery($query);
            if($result):?>
                <!--Modal - confirmation boxes-->
                <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                <h4 class="modal-title">Attendance Log</h4></div>
                            <div class="modal-body">
                                <p class="text-justify">You have successfully signed in.</p>
                                <p class="text-justify">TIME: <?php echo date("h:i:s A", strtotime($time))  ?></p>
                                <p class="text-justify">DATE: <?php echo date("l jS F Y ", strtotime($today));?></p>
                                <p class="text-justify">LOCATION: <?php echo $location_details['location'];?></p>
                                <small class="text-justify text-muted">NB: If these details are not correct, please contact the HR team, immediately.</small>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function()
                    {
                        $('#confirm-add-admin').modal("show");
                    });
                </script>
            <?php endif;
        }
    }
}
?>