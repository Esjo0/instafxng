<?php
/****************************************************************************************
* LiveZilla functions.index.inc.php
* 
* Copyright 2017 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors.
***************************************************************************************/ 

if(!defined("IN_LIVEZILLA"))
	die();

class ServerPage
{
    static function GetFileIssues(&$_configFolderWriteable,$_updateRequired)
    {
        global $LZLANG;
        $message = "";

        if(!file_exists(FILE_CONFIG))
            return '';

        $directories = array(PATH_CONFIG,PATH_LOCALIZATION,PATH_UPLOADS,PATH_LOG,PATH_STATS,PATH_STATS."day/",PATH_STATS."month/",PATH_STATS."year/");
        if(!$_updateRequired)
            foreach($directories as $dir)
            {
                $result = IOStruct::IsWriteable($dir);
                if(!$result)
                {
                    if($dir == PATH_CONFIG)
                    {
                        //$_configFolderWriteable = false;
                        //$message .= '<span class="text-red">No write access ' . $dir . '</span><br>';
                    }
                    else
                        $message .= "No write access " . $dir . "<br>";
                }
            }

        if(!empty($message))
            $message = '<tr><td><i class="fa fa-warning icon-'.(($_configFolderWriteable) ? 'red' : 'red').' icon-large"></i></td><td><b>Write Access:</b>' . $message . '<br><a class="index-button index-button-red" href="'.CONFIG_LIVEZILLA_FAQ.'en/?fid=changepermissions#changepermissions" target="_blank">'.$LZLANG["index_fix_problem"].'</a></td></tr>';

        if(!$_updateRequired && file_exists(FOLDER_INSTALLER))
            $message .= '<tr><td><i class="fa fa-warning icon-red icon-large"></i></td><td><b>Installer Folder:</b> For security reasons, please delete <i>install</i> folder.</td></tr>';

        if(empty($message))
            $message = '<tr><td><i class="fa fa-check-square icon-green icon-large"></i></td><td><b>Write Access</b></td></tr>';

        return $message;
    }

    static function GetMySQLIssues(&$_updateRequired,&$_databaseVersion)
    {
        $error="";
        if(!empty(Server::$Configuration->File["gl_db_host"]))
        {
            require(LIVEZILLA_PATH . "_lib/functions.internal.man.inc.php");
            ServerManager::ValidateDatabase(true,$_updateRequired,$_databaseVersion);
        }

        if(!function_exists("mysqli_real_escape_string"))
            $error = "mysqli PHP extension is not available.";

        if(empty($error))
            return '<tr><td><i class="fa fa-check-square icon-green icon-large"></i></td><td><b>MySQL</b></td></tr>';
        else
            return '<tr><td><i class="fa fa-warning icon-red icon-large"></i></td><td><b>MySQL:</b>' . $error .'</td></tr>';
    }

    static function GetPhpVersion()
    {
        $message = '<tr><td><i class="fa fa-check-square icon-green icon-large"></i></td><td><b>PHP '.@phpversion().'</b></td></tr>';
        if(!Server::CheckPhpVersion(PHP_NEEDED_MAJOR,PHP_NEEDED_MINOR,PHP_NEEDED_BUILD))
            $message = '<tr><td><i class="fa fa-warning icon-orange icon-large"></i></td><td><b>PHP-Version:</b><span>' . str_replace("<!--version-->",PHP_NEEDED_MAJOR . "." . PHP_NEEDED_MINOR . "." . PHP_NEEDED_BUILD,"LiveZilla requires PHP <!--version--> or greater.<br>Installed version is " . @phpversion()) . '.</span></td></tr>';
        return $message;
    }

    static function GetDisabledFunctions()
    {
        Server::InitDataBlock(array("INTERNAL","GROUPS"));
        $message = "";

        if(!function_exists("file_get_contents") && ini_get('allow_url_fopen'))
            $message .= "<span>Disabled function: file_get_contents<br></span> <span>LiveZilla requires the PHP function file_get_contents to be activated.</span><br><br>";
        if(!function_exists("fsockopen"))
            $message .= "<span>Disabled function: fsockopen<br></span> <span>LiveZilla requires the PHP function fsockopen to be activated in order to send and receive emails and to send PUSH Messages to APPs.</span><br><br>";
        if(!function_exists("iconv_mime_decode"))
            $message .= "<span>Missing PHP extension: ICONV<br></span> <span>LiveZilla requires the PHP extension ICONV to parse incoming emails. Please add the ICONV package to your PHP configuration.</span><br><br>";
        if(!function_exists("gd_info"))
            $message .= "<span>Missing PHP extension: GD Image. LiveZilla requires the PHP extension GD Image to create dynamic graphics. Please add the GD package to your PHP configuration.</span><br><br>";
        if(!ini_get('allow_url_fopen'))
            $message .= "<span>Disabled wrapper: allow_url_fopen<br></span> <span>LiveZilla requires allow_url_fopen to be activated in order to send PUSH Messages to APPs and to send/receive Social Media updates.</span><br><br>";
        if(!empty(Server::$Configuration->File["gl_ldap"]) && !function_exists("ldap_connect"))
            $message .= "<span>Missing PHP extension: LDAP<br></span> <span>LiveZilla requires the PHP extension LDAP to authenticate against directories. Please add the LDAP package (extension=php_ldap.dll) to your PHP configuration.</span><br><br>";


        $ml = @ini_get('memory_limit');
        if($ml != null && $ml !== false)
        {
            $ml = IOStruct::ToBytes($ml);
            if($ml != -1 && is_numeric($ml) && $ml > 1000000)
            {
                if($ml < 100000000)
                    $message .= "<span>Possibly not enough memory: ".ini_get('memory_limit')."</span>. <span>In order to process emails with larger attachments, LiveZilla may require more memory. Please increase the PHP memory_limit on your webserver to 96M or 256M. Your webhosting company will assist you.</span><br><br>";
            }
        }

        if(ini_get('allow_url_fopen') && function_exists("fsockopen"))
        {
            if(!empty(Server::$Configuration->File["gl_mpm"]))
            {
                $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded','content' => http_build_query(array())));
                $context  = stream_context_create($opts);
                $result = file_get_contents(CONFIG_LIVEZILLA_PUSH . "validate.php", false, $context);
                if($result != 'CONNECTION_SUCCESSFUL')
                    $message .= "<span>Can't connect to push server ".CONFIG_LIVEZILLA_PUSH." on Port 443. Blocked by firewall or missing Open SSL package (extension=php_openssl.dll). Outgoing connection to push server is required in order to send PUSH Messages to APPs.</span><br><br>";
            }
        }

        if(empty($message))
            $message = '<tr><td><i class="fa fa-check-square icon-green icon-large"></i></td><td><b>Configuration</b></td></tr>';
        else
            $message = '<tr><td><i class="fa fa-warning icon-orange icon-large"></i></td><td><b>Configuration:</b><span>' . $message .'</span></td></tr>';

        $message .= '<tr id="warning-filter" style="display:none;"><td><i class="fa fa-warning icon-red icon-large"></i></td><td><b>Filter:</b><div><span>Ups, you are banned. There\'s a filter that locks you out so you can\'t see the Chat Widget. You can remove the filter under: LiveZilla Operator Console -> Filters (Shield Icon)</span></div></td></tr>';
        $message .= '<tr id="warning-cf" style="display:none;"><td><i class="fa fa-warning icon-red icon-large"></i></td><td><b>Cloudflare:</b><div><span>It seems that you are using Cloudflare. Some Cloudflare functions are not compatible with real-time apps like LiveZilla. Please exclude LiveZilla from Cloudflare if you face problems.</span></div></td></tr>';

        $message .= '<tr id="header_frame_options" style="display:none;"><td><i class="fa fa-warning icon-red icon-large"></i></td><td><b>Header conflict:</b><span>Server side header \'X-Frame-Options: SAMEORIGIN\' prevents the use of mobile apps. Please deactivate header to use LiveZilla APPs.</span></td></tr>';

        $fu = (!Is::Null(@ini_get("upload_max_filesize")))?ini_get("upload_max_filesize"):'??';
        $pu = (!Is::Null(@ini_get("post_max_size")))?ini_get("post_max_size"):'??';

        $message .= '<tr><td><i class="fa fa-check-square icon-green icon-large"></i></td><td><b>File Uploads</b>post_max_size: '.$pu.'<br>upload_max_filesize: '.$fu.'</td></tr>';

        return $message;
    }

    static function Repair()
    {
        if(DBManager::$Connected)
        {
            DBManager::Execute(true, "DELETE FROM `" . DB_PREFIX . DATABASE_INFO . "` WHERE `version` NOT LIKE '%.%.%.%'");
            $versions = array();
            $result = DBManager::Execute(true, "SELECT * FROM `" . DB_PREFIX . DATABASE_INFO . "` ORDER BY `version` DESC");
            if($result)
            {
                while($row = DBManager::FetchArray($result))
                {
                    $versions[] = $row["version"];
                }
                if(count($versions)>1)
                {
                    Logging::DebugLog("INVALID VERSIONS FOUND: " . serialize($versions));
                    for($i=1;$i<count($versions);$i++)
                    {
                        Logging::DebugLog("REMOVE " . $versions[$i]);
                        DBManager::Execute(true, "DELETE FROM `" . DB_PREFIX . DATABASE_INFO . "` WHERE `version` = '" . DBManager::RealEscape($versions[$i]) . "' LIMIT 1");
                    }
                }
            }
        }
    }
}
?>
