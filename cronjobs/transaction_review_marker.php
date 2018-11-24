<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_admin.php';
clear_transactions();

//Set this script for 30 mins interval

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }