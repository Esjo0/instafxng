<?php

/**
 * Checks if an email is valid or not
 * @param type $email
 * @return boolean
 */
function check_email($email) {
    #characters allowed on name: 0-9a-Z-._ on host: 0-9a-Z-. on between: @
    if (!preg_match('/^[0-9a-zA-Z\.\-\_]+\@[0-9a-zA-Z\.\-]+$/', $email))
        return false;

    #must start or end with alpha or num
    if ( preg_match('/^[^0-9a-zA-Z]|[^0-9a-zA-Z]$/', $email))
        return false;

    #name must end with alpha or num
    if (!preg_match('/([0-9a-zA-Z_]{1})\@./',$email) )
        return false;

    #host must start with alpha or num
    if (!preg_match('/.\@([0-9a-zA-Z_]{1})/',$email) )
        return false;

    #pair .- or -. or -- or .. not allowed
    if ( preg_match('/.\.\-.|.\-\..|.\.\..|.\-\-./',$email) )
        return false;

    #pair ._ or -_ or _. or _- or __ not allowed
    if ( preg_match('/.\.\_.|.\-\_.|.\_\..|.\_\-.|.\_\_./',$email) )
        return false;

    #host must end with '.' plus 2-5 alpha for TopLevelDomain
    if (!preg_match('/\.([a-zA-Z]{2,5})$/',$email) )
        return false;

    return true;
}

/**
 * Validate Nigerian phone numbers
 * @param type $number
 * @return int
 * 1 - input is empty
 * 2 - number is not numeric
 * 3 - number does not start a Nigerian Phone Network Prefix
 * 4 - length of the number is not eleven
 * 5 - success
 */
function check_number($number) {
    //All Nigerian Phone Network Prefixes and Number Ranges
    $nigerian_phone_number_prefixes = array('0701', '0702', '0703', '0704', '0705', '0706', '0707', '0708', '0709', '0802', '0803', '0804', '0805', '0806', '0807', '0808', '0809', '0810', '0811', '0812', '0813', '0814', '0815', '0816', '0817', '0818', '0819', '0901', '0902', '0903', '0904', '0905', '0906', '0907', '0908', '0909');

    //Strip whitespace (or other characters) from the beginning and end of a string
    $number = trim($number);

    //Ensure number is not empty
    if(empty($number)) {
        return 1;
    }

    //Ensure number is numeric
    if(!is_numeric($number)) {
        return 2;
    }

    //Ensure number starts with any of the phone prefixes
    $number_prefix = substr($number, 0, 4);
    if (!in_array($number_prefix, $nigerian_phone_number_prefixes)) {
        return 3;
    }

    //Ensure the length of the number is 11 digits
    if(strlen($number)!== 11) {
        return 4;
    }

    return 5;
}

// Get random number from a range
function getRandomNumbers($min, $max, $count) {
    if ($count > (($max - $min)+1)) { return false; }
    $values = range($min, $max);
    shuffle($values);
    return array_slice($values, 0, $count);
}

/**
 * Generates a random string of characters of any specified length
 * Allowed characters are A - Z, a - z, 0 - 9
 * @param mixed $length The length of the string to generate
 * @return string
 */
function rand_string($length){
  $s = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  $rand = '';
  srand((double)microtime()*1000000);  
  for($i = 0; $i < $length; $i++) {  
      $rand.= $s[rand()%strlen($s)];  
  }  
  return $rand;  
}


/**
 * Generates a random string of characters of any specified length
 * Allowed characters are A - Z
 * @param mixed $length The length of the string to generate
 * @return string
 */
function rand_string_caps($length){
  $s = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $rand = '';
  srand((double)microtime()*1000000);  
  for($i = 0; $i < $length; $i++) {  
      $rand.= $s[rand()%strlen($s)];  
  }  
  return $rand;  
}

/**
 * Generates random string for partner code
 * Allowed characters are A - Z
 * @param mixed $length The length of the string to generate
 * @return string
 */
function partner_rand_string($length){
  $s = "ABCDEFGHJKLMNPQRSTUVWXYZ";
  $rand = '';
  srand((double)microtime()*1000000);  
  for($i = 0; $i < $length; $i++) {  
      $rand.= $s[rand()%strlen($s)];  
  }  
  return $rand;  
}

/**
 * Interpret error numbers
 * @param type $errno
 * @return string
 */
function error_report_levels($errno) {
    switch ($errno) {
        case 1: 
            $level = "E_ERROR";
            return $level;
        case 2: 
            $level = "E_WARNING";
            return $level;
        case 4: 
            $level = "E_PARSE";
            return $level;
        case 8: 
            $level = "E_NOTICE";
            return $level;
        case 16: 
            $level = "E_CORE_ERROR";
            return $level;
        case 32: 
            $level = "E_CORE_WARNING";
            return $level;
        case 64: 
            $level = "E_COMPILE_ERROR";
            return $level;
        case 128: 
            $level = "E_COMPILE_WARNING";
            return $level;
        case 256: 
            $level = "E_USER_ERROR";
            return $level;
        case 512: 
            $level = "E_USER_WARNING";
            return $level;
        case 1024: 
            $level = "E_USER_NOTICE";
            return $level;
        case 2048: 
            $level = "E_STRICT";
            return $level;
        case 4096: 
            $level = "E_RECOVERABLE_ERROR";
            return $level;
        case 8192: 
            $level = "E_DEPRECATED";
            return $level;
        case 16384: 
            $level = "E_USER_DEPRECATED";
            return $level;
        case 6143: 
            $level = "E_ALL";
            return $level;
        default:
            $level = "E_LEVEL_NOT_KNOWN";
            return $level;
    }
}