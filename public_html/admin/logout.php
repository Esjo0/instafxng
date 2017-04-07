<?php
require_once("../init/initialize_admin.php");
$session_admin->logout();
redirect_to("login.php?logout=1");