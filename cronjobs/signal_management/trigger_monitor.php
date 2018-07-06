<?php
set_include_path('../../public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();
$date_time = date('Y-m-d h:m');
$query = "SELECT signal_id, price, CONCAT(trigger) FROM signal_daily ";