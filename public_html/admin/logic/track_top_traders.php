<?php
require_once("../../init/initialize_admin.php");

$ramtpaid = $_GET["q"];
$ramtpaid = str_replace(",", "", $ramtpaid);
$fund_rate = $_GET["r"];

$deposit_origin = $_GET["do"];

if($deposit_origin != '1') {
    $realDolVal = number_format(($ramtpaid / $fund_rate), 2);
} else {
    $ramtpaid = $ramtpaid - CBN_STAMP_DUTY;

    $denom = DVAT * DSERVCHARGE + DSERVCHARGE + 1;	
    $realDolVal = number_format(($ramtpaid / ($fund_rate * $denom)), 2);
}


?>
<input type="text" class="form-control" id="realDolVal" name="realDolVal" value="<?php echo $realDolVal; ?>" readonly>