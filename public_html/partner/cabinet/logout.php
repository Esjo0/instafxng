<?php
require_once '../../init/initialize_partner.php';
$session_partner->logout();
redirect_to("../login.php?logout=1");