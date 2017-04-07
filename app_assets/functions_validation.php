<?php

// Validates allow parameters in the GET super global
function allowed_get_params($allowed_params=[]) {
    $allowed_array = [];
    foreach($allowed_params as $param) {
        if(isset($_GET[$param])) {
            $allowed_array[$param] = $_GET[$param];
        } else {
            $allowed_array[$param] = NULL;
        }
    }
    return $allowed_array;
}
