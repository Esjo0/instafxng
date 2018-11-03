<?php
    require_once("../init/initialize_general.php");

    $email = $_GET["email"];

if (!empty($email)) {
    $query = "SELECT bf.total_points, CONCAT(u.last_name, SPACE(1), u.first_name) AS name, bf.tire
            FROM user AS u
            INNER JOIN black_friday_2018 AS bf ON u.user_code = bf.user_code
            WHERE u.email = '$email' AND bf.tire IS NOT NULL";
    $result = $db_handle->runQuery($query);
    $details = $db_handle->fetchAssoc($result);


    if ($details) {
        foreach ($details AS $row) {
            extract($row);
            if (empty($total_points)) {
                $total_points = 0;
            }
            $points_to_target = black_friday_tire_target($tire) - ($total_points % black_friday_tire_target($tire));
            $target_reached = round($total_points / black_friday_tire_target($tire), 0, PHP_ROUND_HALF_DOWN);
        }
    } else {
        $message_error = "You are not enrolled for the black friday Splurge <a data-target=\"#contest-register\" data-toggle=\"modal\"> Click Here to Join</a>";
    }
} else {
    $message_error = "Looks like you entered an invalid email, please try again.";
}
?>
<?php if ($details) { ?>
    <div class="row">
        <div class="col-sm-12"><p style="color:black; !important;"
                                  class="text-center"><?php echo $name ?> , You are in
                the <?php echo black_friday_tire($tire) ?> Category With a target
                of <?php echo black_friday_tire_target($tire); ?> loyalty points</p>
        </div>
        <div class="col-sm-4">
            <li class="list-group-item d-flex justify-content-between lh-condensed text-center"
                style="display:block">
                <h6><b>Total Points Gained</b></h6>
                <h5><?php echo $total_points ?> Points</h5>
            </li>
        </div>
        <div class="col-sm-4">
            <li class="list-group-item d-flex justify-content-between lh-condensed text-center"
                style="display:block">
                <h6><b>Total Points to Target</b></h6>
                <h5><?php echo $points_to_target ?> Points</h5>
            </li>
        </div>

        <div class="col-sm-4">
            <li class="list-group-item d-flex justify-content-between lh-condensed text-center"
                style="display:block">
                <h6><b>Target Reached</b></h6>
                <h5><?php if ($target_reached > 1) {
                        echo $target_reached . " Times";
                    } elseif ($target_reached == 1) {
                        echo "Once";
                    } else {
                        echo "Not Yet.";
                    } ?></h5>
            </li>
        </div>
    </div>
<?php }  else { ?>
    <div class="alert alert-danger">
        <br><hr>
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Oops!</strong>You are not enrolled for the black friday Splurge <a data-target="#contest-register" data-toggle="modal"> Click Here to Join</a>
    </div>
<?php } ?>
