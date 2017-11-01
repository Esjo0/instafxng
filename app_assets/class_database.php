<?php
/**
 * DB class
 * @author Lekan Esan <lekan@instafxng.com>
 */
class mysqlDB {
    private $host = "localhost";
    private $user;
    private $password;
    private $database;
    private $conn;

    function __construct($db, $user, $password) {
        $this->database = $db;
        $this->user = $user;
        $this->password = $password;
        $this->conn = $this->connectDB();
    }

    function connectDB() {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $conn;
    }

    function closeDB() {
        if(isset($this->conn)) {
            mysqli_close($this->conn);
            unset($this->conn);
        }
        
    }

    function runQuery($query) {
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    function fetchAssoc($result) {
        while( $row = mysqli_fetch_assoc($result) ) {
            $resultset[] = $row;
        }
        if(!empty($resultset)) {
            return $resultset;
        }
    }

    function fetchArray($result) {
        while( $row = mysqli_fetch_array($result) ) {
            $resultset[] = $row;
        }
        if(!empty($resultset)) {
            return $resultset;
        }
    }

    function fetchRow($result) {
        while( $row = mysqli_fetch_row($result) ) {
            $resultset[] = $row;
        }
        if(!empty($resultset)) {
            return $resultset;
        }
    }

    function numRows($query) {
        $result  = mysqli_query($this->conn, $query);
        $rowcount = mysqli_num_rows($result);
        if(empty($rowcount)) {
            return 0;
        } else {
            return $rowcount;
        }
    }
    
    function numOfRows($result) {
        $rowcount = mysqli_num_rows($result);
        if(empty($rowcount)) {
            return 0;
        } else {
            return $rowcount;
        }
    }
    
    function affectedRows() {
        return mysqli_affected_rows($this->conn);
    }
    
    function insertedId() {
        return mysqli_insert_id($this->conn);
    }
    
    function sanitizePost($string) {
        return mysqli_real_escape_string($this->conn, $string);
    }
}

$db_handle = new mysqlDB(DB_NAME, DB_USER, DB_PASS);