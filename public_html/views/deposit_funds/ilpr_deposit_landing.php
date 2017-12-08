<?php
/** Display the top earners for this SEASON ***********/
// Select all the archived years
$query = "SELECT start_date FROM point_ranking_log WHERE type = '2' GROUP BY start_date DESC";
$result = $db_handle->runQuery($query);
$selected_years = $db_handle->fetchAssoc($result);
$start_date = $selected_years[0]['start_date'];
////$from_date_year = $current_point_season[0]['start_date'];
////$to_date_year = $current_point_season[0]['end_date'];

$query = "SELECT prl.position, prl.point_earned, u.last_name, u.first_name AS full_name
                                                              FROM point_ranking_log AS prl
                                                              INNER JOIN user AS u ON prl.user_code = u.user_code
                                                              WHERE prl.start_date = '$start_date' AND prl.type = '2'
                                                              ORDER BY prl.point_earned DESC LIMIT 10";

$result = $db_handle->runQuery($query);
$selected_loyalty_year = $db_handle->fetchAssoc($result);
/*****************************************************/
?>



<header><h5>Here are the Guaranteed Winners and their Cash Prizes.</h5></header>
<article>
    <div class="table-responsive mtl">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Points Earned</th>
                <th>Prize Awarded</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $count = 1;
                if(isset($selected_loyalty_year) && !empty($selected_loyalty_year))
                {
                    foreach ($selected_loyalty_year as $row)
                {
                ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php if($row['full_name'] == 'Management') { echo $row['last_name']; } else { echo $row['full_name']; }; ?></td>
                <td><?php echo number_format(($row['point_earned']), 2, ".", ","); ?></td>
                <td><?php if($count == 1){echo "N1,000,000 + "."$".number_format(($row['point_earned']/10), 2, ".", ",")." OR "."N".number_format((($row['point_earned']/10)* WITHDRATE), 2, ".", ",") ;}elseif ($count == 2){echo "N500,000 + "."$".number_format(($row['point_earned']/10), 2, ".", ",")." OR "."N".number_format((($row['point_earned']/10)* WITHDRATE), 2, ".", ",");}elseif ($count == 3){echo "N250,000 + "."$".number_format(($row['point_earned']/10), 2, ".", ",")." OR "."N".number_format((($row['point_earned']/10)* WITHDRATE), 2, ".", ",");}elseif ($count == 4){echo "N150,000 + "."$".number_format(($row['point_earned']/10), 2, ".", ",")." OR "."N".number_format((($row['point_earned']/10)* WITHDRATE), 2, ".", ",");}elseif ($count == 5){echo "N100,000 + "."$".number_format(($row['point_earned']/10), 2, ".", ",")." OR "."N".number_format((($row['point_earned']/10)* WITHDRATE), 2, ".", ",");}elseif (in_array($count, range(6,10))){echo "N50,000 + "."$".number_format(($row['point_earned']/10), 2, ".", ",")." OR "."N".number_format((($row['point_earned']/10)* WITHDRATE), 2, ".", ",");} ?></td>
            </tr>
            <?php $count++; } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
            </tbody>
        </table>
    </div>

<p>It might be just you cashing out with <b>N1, 000 000</b> by this time next year!</p>

<p>There is no luck with this Promo.</p>

<p>Only active traders can benefit from it.</p>

<p>Are you One? </p>

<p>Don't wait till the promo is almost rounding off to start.</p>

<p>It's better to start early!</p>

<p>Fund your account below and start trading to be a winner in the new <a href="loyalty.php" title="Find More Information Here"><b>Loyalty Points Program and Reward</b></a>.</p>

<br />
