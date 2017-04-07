<?php
    require_once("../init/initialize_general.php");

    $ifx_account = $_GET["acct"];
    $type = $_GET["type"];

    $query = "SELECT user_code FROM user_ifxaccount WHERE ifx_acct_no = '$ifx_account' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $client = $db_handle->fetchAssoc($result);
    $user_code = $client[0]['user_code'];

    if($user_code) {
        switch($type) {
            case 'month':
                $query = "SELECT pr.user_code, pr.month_rank AS rank, u.first_name AS full_name
                      FROM point_ranking AS pr
                      INNER JOIN user AS u ON pr.user_code = u.user_code
                      ORDER BY pr.month_rank DESC, full_name ASC";
                break;
            case 'year':
                $query = "SELECT pr.user_code, pr.year_rank AS rank, u.first_name AS full_name
                      FROM point_ranking AS pr
                      INNER JOIN user AS u ON pr.user_code = u.user_code
                      ORDER BY pr.year_rank DESC, full_name ASC";
                break;
        }

        $result = $db_handle->runQuery($query);
        $found_loyalty = $db_handle->fetchAssoc($result);
        $found_position = in_array_r($user_code, $found_loyalty);
    }
?>

<?php if(isset($found_position) && !empty($found_position)) { ?>
    <p>Find your position below:</p>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Position</th>
            <th>Name</th>
            <th>Rank Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $found_position['position']; ?></td>
            <td><?php echo $found_position['full_name']; ?></td>
            <td><?php echo number_format(($found_position['rank']), 2, ".", ","); ?></td>
        </tr>
        </tbody>
    </table>
<?php } else { ?>
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Oops!</strong> Looks like you don't have a rank yet or you are not enrolled.
    </div>
<?php } ?>