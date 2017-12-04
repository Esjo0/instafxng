<?php
/** Display the top earners for this SEASON ***********/
$query = "SELECT start_date, end_date FROM point_season WHERE is_active = '1' AND type = '2' LIMIT 1";
$result = $db_handle->runQuery($query);
$current_point_season = $db_handle->fetchAssoc($result);

$from_date_year = $current_point_season[0]['start_date'];
$to_date_year = $current_point_season[0]['end_date'];

$query = "SELECT pr.year_rank, pr.year_earned_archive, pr.point_claimed, u.last_name, u.first_name AS full_name
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.year_rank DESC, full_name ASC LIMIT 10";

$result = $db_handle->runQuery($query);
$selected_loyalty_year = $db_handle->fetchAssoc($result);
/*****************************************************/
?>
<p>Our <b>Instafxng Point Based Loyalty Program and Reward</b> for year 2017 has ended and a new one kicked off immediately!</p>

<p>Here are the Guaranteed Winners and their Cash Prizes.</p>

<header><h5>Top 10 Rank in current loyalty year</h5></header>
<article>
    <div class="table-responsive mtl">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Rank Value</th>
                <th>Cash Value</th>
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
                <td><?php echo number_format(($row['year_rank']), 2, ".", ","); ?></td>
                <td><?php echo "$".number_format(($row['year_rank']/10), 2, ".", ",") ."  -  N".number_format((($row['year_rank']/10)/WITHDRATE), 2, ".", ","); ?></td>
                <td><?php if($count == 1){echo "N1,000,000";}elseif ($count == 2){echo "N500,000";}elseif ($count == 3){echo "N250,000";}elseif ($count == 4){echo "N150,000";}elseif ($count == 5){echo "N100,000";}elseif (in_array($count, range(6,10))){echo "N50,000";} ?></td>
            </tr>
            <?php $count++; } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
            </tbody>
        </table>
    </div>

<p>It might be just you cashing out with <b>N1, 000 000</b> by this time next year!</p>

<p>There is no luck with this Promo.</p>

<p>Only hardworking traders can benefit from it.</p>

<p>Are you One? It's only your appearance on the rank scale that can tell!</p>

<p>Don't wait till the promo is almost rounding off to start.</p>

<p>It's better to start early!</p>

<p>Fund your account below and start trading to be a winner in the new <a href="loyalty.php" title="Find More Information Here"><b>Loyalty Points Program and Reward</b></a>.</p>

<br />
