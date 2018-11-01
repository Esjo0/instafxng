<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$cat = " ALL";
$point = "2000, 1000, 500, 200, 100";
if(isset($_POST['filter'])){
    $filter = $db_handle->sanitizePost(trim($_POST['filt_val']));

        $query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, u.user_code, bf.total_points, bf.tire
    FROM black_friday_2018 AS bf
    INNER JOIN user AS u ON bf.user_code = u.user_code
    WHERE bf.tire = '$filter'
    ORDER BY bf.total_points DESC ";
    $_SESSION['query'] = $query;
  switch ($filter) {
      case '1':
          $cat = "PLATINUM";
          $point = "2000";
          break;
      case '2':
          $cat = "GOLD";
          $point = "1000";
          break;
      case '3':
          $cat = "SILVER";
          $point = "500";
          break;
      case '4':
          $cat = "BRONZE PRO";
          $point = "200";
          break;
      case '5':
          $cat = "BRONZE LITE";
          $point = "100";
          break;

      default:
          $cat = "";
          $point = "";
          break;
  }
}elseif(empty($_SESSION['query']) || isset($_POST['all'])){

$query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, u.user_code, bf.total_points, bf.tire
    FROM black_friday_2018 AS bf
    INNER JOIN user AS u ON bf.user_code = u.user_code
    WHERE bf.tire IS NOT NULL
    ORDER BY bf.total_points DESC ";
    $_SESSION['query'] = $query;
}
$query = $_SESSION['query'];

$numrows = $db_handle->numRows($query);

$rowsperpage = 40;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
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
$black_friday_splurge_promo = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Black Friday Splurge 2018</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Black Friday Splurge 2018"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once 'layouts/sidebar.php'; ?>
        </div>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-lg-9">
            <!-- Unique Page Content Starts Here
            ================================================== -->

            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>Black Friday 2018 - <?php echo $cat?> CATEGORY</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
            <form action="" method="post" class="form horizontal row">
                <div class="col-md-6">
                    <select name="filt_val" class="form-control" id="filter" placeholder="Filter by" required>
                        <option value="1" >Platinum</option>
                        <option value="2" >Gold</option>
                        <option value="3">Silver</option>
                        <option value="4">Bronze Pro</option>
                        <option value="5">Bronze Lite</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success" type="submit" name="filter"> Submit</button>
                </div>
                <div class="col-md-3 pull-right">
                    <button class="btn btn-outline-primary" type="submit" name="all">View All</button>
                </div>
            </form>
                        <p>Below is the list of clients that have opted in for the <?php echo $cat?> Category To Make <b><?php echo $point?>
                                points</b> in the 2018 Black Friday Promotion.</p>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Total Points</th>
                                <th>Target Reached</th>
                                <th>Amount to be Claimed</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($black_friday_splurge_promo) && !empty($black_friday_splurge_promo)) {
                                foreach ($black_friday_splurge_promo as $row) {

                                    $points_to_target = black_friday_tire_target($row['tire']) - ($row['total_points'] % black_friday_tire_target($row['tire']));
                                    $target_reached = round($row['total_points'] / black_friday_tire_target($row['tire']), 0, PHP_ROUND_HALF_DOWN);
                                    $dollar_value = ((($target_reached * black_friday_tire_target($row['tire'])) * 10) / 100) * ((150/100) * $target_reached);
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['total_points']; ?></td>
                                        <td><?php if ($target_reached > 1) {
                                                echo $target_reached . " Times";
                                            } elseif ($target_reached == 1) {
                                                echo "Once";
                                            } else {
                                                echo "Not Yet.";
                                            } ?></td>
                                        <td><?php echo "$". number_format($dollar_value); ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td nowrap="nowrap">
                                            <a target="_blank" title="View" class="btn btn-info"
                                               href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i
                                                    class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            <a class="btn btn-primary" title="Send Email"
                                               href="campaign_email_single.php?name=<?php $name = $row['full_name'];
                                               echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i
                                                    class="glyphicon glyphicon-envelope"></i></a>
                                            <a class="btn btn-success" title="Send SMS"
                                               href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i
                                                    class="glyphicon glyphicon-phone-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                        <?php if (isset($black_friday_splurge_promo) && !empty($black_friday_splurge_promo)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">
                                    Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?>
                                    entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($black_friday_splurge_promo) && !empty($black_friday_splurge_promo)) {
                    require_once 'layouts/pagination_links.php';
                } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>