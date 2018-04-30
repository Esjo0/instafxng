<?php
/****************************************************************************************
* LiveZilla index.php
* 
* Copyright 2017 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
***************************************************************************************/

define("IN_LIVEZILLA",true);
if(!defined("LIVEZILLA_PATH"))
	define("LIVEZILLA_PATH","./");
header("Content-Type: text/html; charset=UTF-8");

require(LIVEZILLA_PATH . "language.php");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.index.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.external.inc.php");

LocalizationManager::AutoLoad();

@set_error_handler("handleError");

Server::InitDataProvider();
CacheManager::Flush();
ServerPage::Repair();
Server::DefineURL("index.php");
Server::InitDataBlock(array("FILTERS"));

$scheme = Communication::GetScheme();
$locale = Visitor::$BrowserLanguage == 'de' ? 'de' : 'en';
$html = IOStruct::GetFile(TEMPLATE_HTML_INDEX);
$infoBox = null;
$updateRequired = false;
$installRequired = !file_exists(FILE_CONFIG) && !file_exists(FILE_CONFIG_OLD);
$configFolderWriteable = true;
$lzid="";
$databaseVersion="";
$infos['php_version'] = ServerPage::GetPhpVersion();
$infos['mysql'] = ServerPage::GetMySQLIssues($updateRequired,$databaseVersion);
$infos['file'] = ServerPage::GetFileIssues($configFolderWriteable,$updateRequired);
$infos['disabled'] = ServerPage::GetDisabledFunctions();
$infos['fcounta'] = 0;
$infos['fcountr'] = 0;
$infoBox = "";
$infoBox .= $infos['file'];
$infoBox .= $infos['php_version'];
$infoBox .= $infos['mysql'];
$infoBox .= $infos['disabled'];

if(DBManager::$Connected)
{
    $lzid = Server::$Configuration->File["gl_lzid"];

    if(!file_exists(FILE_INSTALLER))
        $lzid = substr(strtoupper(md5($lzid)),0,5);

    $infos['fcounta'] = count(Server::$Filters->Filters);
    if($infos['fcounta'])
        foreach(Server::$Filters->Filters as $f)
            if($f->Type==1)
                $infos['fcountr']++;
}
else if(file_exists(FILE_INSTALLER))
{
    require_once(FILE_INSTALLER);
    require_once(LIVEZILLA_PATH . "_lib/functions.internal.man.inc.php");
    $wc = false;
    $vars = Installer::ImportConfigFile($wc);
    $lzid = $vars[7];
}

$html = str_replace("<!--widget-->",(!$updateRequired && !$installRequired) ? OverlayChat::GetDefaultScript(false) : "",$html);
$html = str_replace("<!--topMargin-->",0,$html);
$html = str_replace("<!--infos-->",$infoBox,$html);
$html = str_replace("<!--lz_id-->",$lzid,$html);
$html = str_replace("<!--lz_version-->",VERSION,$html);
$html = str_replace("<!--locale-->",$locale,$html);
$html = str_replace("<!--database_version-->",$databaseVersion,$html);
$html = str_replace("<!--time-->",date(DATE_W3C),$html);
$html = str_replace("<!--scheme-->",str_replace("://","",strtoupper(Communication::GetScheme())),$html);
$html = str_replace("<!--filters-->",$infos['fcountr']." / ".$infos['fcounta'],$html);
$html = str_replace("<!--show_body-->",isset($_GET["demo"]) ? "none" : "block",$html);
$html = str_replace("<!--timestamp-->",time(),$html);
$html = str_replace("<!--install_possible-->",To::BoolString($configFolderWriteable),$html);
$html = str_replace("<!--install_mode-->",To::BoolString($updateRequired || !file_exists(FILE_CONFIG)),$html);
$html = str_replace("<!--update_mode-->",To::BoolString($updateRequired || (!file_exists(FILE_CONFIG) && file_exists(FILE_CONFIG_OLD))),$html);
$html = str_replace("<!--title-->",base64_decode($d[array_rand($d=array("TGl2ZVppbGxhIExpdmUgQ2hhdCBTb2Z0d2FyZQ==","TGl2ZVppbGxhIExpdmUgU3VwcG9ydCBTb2Z0d2FyZQ==","TGl2ZVppbGxhIExpdmUgQ2hhdCBTb2Z0d2FyZQ==","TGl2ZVppbGxhIExpdmUgSGVscCBTb2Z0d2FyZQ==","TGl2ZVppbGxhIExpdmUgQ2hhdCBTb2Z0d2FyZQ==","TGl2ZVppbGxhIEN1c3RvbWVyIFN1cHBvcnQ=","TGl2ZVppbGxhIE9ubGluZSBTdXBwb3J0","TGl2ZVppbGxhIExpdmUgQ2hhdCBTb2Z0d2FyZQ=="),1)]),$html);

echo(Server::Replace($html));
?>