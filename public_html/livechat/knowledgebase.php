<?php
/****************************************************************************************
 * LiveZilla knowledgebase.php
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 * Improper changes to this file may cause critical errors.
 ***************************************************************************************/

define("IN_LIVEZILLA",true);
header('Content-Type: text/html; charset=utf-8');
if(!defined("LIVEZILLA_PATH"))
    define("LIVEZILLA_PATH","./");
@set_error_handler("handleError");

require(LIVEZILLA_PATH . "_definitions/definitions.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.global.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.external.inc.php");
require(LIVEZILLA_PATH . "_lib/objects.external.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");

Server::InitDataProvider();
Server::DefineURL("knowledgebase.php");
LocalizationManager::AutoLoad();

$color = ExternalChat::ReadTextColor();
$html = "";

if(isset($_GET["id"]))
{
    $entry = KnowledgeBaseEntry::GetById(Communication::ReadParameter("id",""),true);

    if(empty($entry) && !empty($_GET["id"]))
        $entry = KnowledgeBaseEntry::GetById($_GET["id"],true);

    if(!empty($entry))
    {
        $html = IOStruct::GetFile(PATH_TEMPLATES . "kb_entry.tpl");
        if(!empty(Server::$Configuration->File["gl_kcss"]))
            $html = str_replace("<!--custom_css-->","<link rel=\"stylesheet\" type=\"text/css\" href=\"".Server::$Configuration->File["gl_kcss"]."\">",$html);

        if(Server::$Configuration->File["gl_knbr"])
        {
            $rresult = $entry->GetRateResult();
            if(($rate=Communication::ReadParameter("h",-1))!= -1)
            {
                $html = str_replace("<!--rate_text-->","<br><div id=\"lz_chat_dialog_kb_rate\"><span>".$LZLANG["client_feedback_success"]."</span></div>",$html);
                $entry->SaveRateResult($rate);
            }
            else
            {
                $entry->RegisterView();
                $bhtml = "<a href=\"./knowledgebase.php?id=<!--id-->&h=MQ__\"><!--lang_client_yes--></a>&nbsp;<a href=\"./knowledgebase.php?id=<!--id-->&h=MA__\"><!--lang_client_no--></a>";
                $fhtml = ($rresult[0] > 0) ? str_replace("<!--users-->",$rresult[0],str_replace("<!--total-->",$rresult[1],$LZLANG["client_found_helpful"]))." " : "";
                $html = str_replace("<!--rate_text-->","<br><br><div><span>" . $fhtml . $LZLANG["client_was_helpful"]."</span>&nbsp;".$bhtml."&nbsp;</div>",$html);
            }
        }
        else
            $html = str_replace("<!--rate_text-->","",$html);

        if($entry->Type == 1)
            $html = str_replace("<!--html-->",$entry->Value,$html);
        else if($entry->Type == 2)
            $html = str_replace("<!--html-->","<script>window.location.replace('".$entry->Value."');</script>",$html);
        else if($entry->Type == 3)
        {
            $url = LIVEZILLA_URL . "getfile.php?id=" . $entry->Id . "&file=" . $entry->Title;
            header("Location: " . $url);
            die();
        }
        $html = str_replace("<!--color-->",$color,$html);
        $html = str_replace(array("<!--title-->","<!--header-->"),$entry->Title,$html);
        $html = str_replace("<!--id-->",$_GET["id"],$html);
    }
}
else
{
    $html = IOStruct::GetFile(PATH_TEMPLATES . "kb_main.tpl");

    if(!isset($_GET["search-for"]))
        $_GET["search-for"] = "";

    $eq = Communication::ReadParameter("eq","");


    if(empty($_GET["search-for"]) && !empty($eq))
        $_GET["search-for"] = $eq;

    $html = str_replace("<!--query-->",htmlentities($_GET["search-for"],ENT_QUOTES,"UTF-8"),$html);

    if(!isset($_GET["no-logo"]) && !empty(Server::$Configuration->File["gl_knbh"]))
        $html = str_replace("<!--logo-->","<img src=\"". Server::$Configuration->File["gl_cali"]."\" border=\"0\">",$html);

    if(!empty(Server::$Configuration->File["gl_kcss"]))
        $html = str_replace("<!--custom_css-->","<link rel=\"stylesheet\" type=\"text/css\" href=\"".Server::$Configuration->File["gl_kcss"]."\">",$html);

    if(empty($_GET["article"]))
    {
        $html = str_replace("<!--results-->",getSearchContent(),$html);
        $html = str_replace("<!--navigation-->",getNavigation(null),$html);
    }
    else
    {
        $entry = KnowledgeBaseEntry::GetById(Communication::ReadParameter("article",""),true);

        if($entry == null)
            $entry = KnowledgeBaseEntry::GetById($_GET["article"],true);

        $html = str_replace("<!--results-->",getArticleContent($entry),$html);
        $html = str_replace("<!--navigation-->",getNavigation($entry),$html);
    }
    $html = str_replace("<!--color-->",$color,$html);
    $html = str_replace("<!--params-->",KnowledgeBase::GetAllowedParameters("form",array(0=>"search-for")),$html);
}
exit(Server::Replace($html));

function getLink($_getParams,$_title,$_exclude=null)
{
    $params = KnowledgeBase::GetAllowedParameters("get",$_exclude);
    $url = "knowledgebase.php?s=MQ__";

    if(!empty($params))
        $url .= "&" . $params;

    if(!empty($_getParams))
        $url .= $_getParams;
    $link = "<a style=\"color:<!--color-->;\" href=\"".$url."\">".$_title."</a>";

    return $link;
}

function getNavigation($entry){

    $content ="";
    if(isset($_GET["tops"]) && $entry !=null)
    {
        $content = getLink("",LocalizationManager::$TranslationStrings["client_most_popular"],array(0=>"search-for"));
        $content .= "<span class=\"lz_kb_all\"><a target=\"_blank\" href=\"./knowledgebase.php?s=MQ__&" . KnowledgeBase::GetAllowedParameters("get",array(0=>"no-logo",1=>"tops"))."\">" . LocalizationManager::$TranslationStrings["client_kb_browse_all"] . "</a></span>";
    }
    else if($entry!=null)
    {
        $link = getLink("&article=<!--a-->","<!--t-->",array(0=>"search-for"));
        $navline = "";
        $currentNode = $entry;
        if($entry->IsPublic && $entry->Type == 0)
            $navline .= $entry->Title;
        while($currentNode->ParentId != "" && $currentNode->ParentId != 1)
        {
            $parent = new KnowledgeBaseEntry();
            $parent->Load($currentNode->ParentId);

            if($parent->IsPublic && $parent->Type == 0)
                $navline = str_replace("<!--a-->",Encoding::Base64UrlEncode($parent->Id),str_replace("<!--t-->",$parent->Title,$link)) . (!empty($navline) ? " / " : "") . $navline;
            $currentNode = $parent;
        }
        $content = getLink("","<!--lang_client_tab_knowledgebase-->",array(0=>"search-for")) . ((!empty($navline)) ? " / " . $navline : "");
    }
    return "<div id=\"lz_kb_navigation\">" . $content . "</div>";
}

function getArticleContent($entry){
    $html = "";
    if(!empty($entry))
    {
        if($entry->Type != 0)
        {
            $html = IOStruct::GetFile(PATH_TEMPLATES . "kb_entry_v2.tpl");
            if(Server::$Configuration->File["gl_knbr"])
                $html = str_replace("<!--footer-->",getFooterBox($entry),$html);
            else
                $html = str_replace("<!--rate_text-->","",$html);

            if($entry->Type == 1)
                $html = str_replace("<!--html-->",$entry->Value,$html);
            else if($entry->Type == 2)
                $html = str_replace("<!--html-->","<script>window.location.replace('".$entry->Value."');</script>",$html);
            else if($entry->Type == 3)
            {
                $url = LIVEZILLA_URL . "getfile.php?id=" . $entry->Id . "&file=" . $entry->Title;
                header("Location: " . $url);
                die();
            }
        }
        else
        {
            $html = getFolderContent($entry->Id,false);
        }
        $html = str_replace(array("<!--title-->","<!--header-->"),$entry->Title,$html);
        $html = str_replace("<!--id-->",$_GET["article"],$html);
    }
    return $html;
}

function getFolderContent($root,$_fullView){

    $result=$content ="";
    $matches=array();
    $tops=false;
    if($_fullView)
    {
        if(isset($_GET["tops"]))
        {
            $matches = KnowledgeBase::GetTOPEntries($root,Visitor::$BrowserLanguage);
            $tops = count($matches)>0;
        }

        if(count($matches)==0)
            $matches = KnowledgeBase::GetEntries($root,Visitor::$BrowserLanguage);

        if(count($matches)==0)
            $matches = KnowledgeBase::GetEntries($root);



        if(count($matches)>0)
        {
            foreach($matches as $match)
            {
                $res = IOStruct::GetFile(PATH_TEMPLATES . "kb_result_category_v2.tpl");

                if($match->Id == 1)
                    $title = htmlentities($match->Title,ENT_NOQUOTES,"UTF-8");
                else
                {
                    $title = getLink("&article=" . Encoding::Base64UrlEncode($match->Id),htmlentities($match->Title,ENT_NOQUOTES,"UTF-8"),array(0=>"search-for"));
                    $title = str_replace("<!--color-->","",$title);
                }

                if($tops)
                    $title .= "<span class=\"lz_kb_all\"><a target=\"_blank\" href=\"./knowledgebase.php?s=MQ__&" . KnowledgeBase::GetAllowedParameters("get",array(0=>"no-logo",1=>"tops"))."\">" . LocalizationManager::$TranslationStrings["client_kb_browse_all"] . "</a></span>";

                $res = str_replace("<!--title-->",$title,$res);
                $res = str_replace("<!--id-->",$match->Id,$res);

                $entries = "";
                $childcount = 0;
                if(!empty($match->ChildNodes))
                {
                    foreach($match->ChildNodes as $child)
                    {
                        $entries .= $child->GetHTML(null,false,true,"v2");
                        $childcount++;
                    }
                }
                $res = str_replace("<!--search-->","false",$res);
                $res = str_replace("<!--entries-->",$entries,$res);

                if($childcount>0)
                    $result .= $res;
            }
            $main = $result;
        }
    }
    else
    {
        $rootEntry = new KnowledgeBaseEntry();
        $rootEntry->Load($root);
        $rootEntry->LoadChildNodes("",true,Visitor::$BrowserLanguage);

        if(count($rootEntry->ChildNodes)==0)
            $rootEntry->LoadChildNodes("",true);

        $matches = $rootEntry->ChildNodes;

        $main = IOStruct::GetFile(PATH_TEMPLATES . "kb_result_category_v2.tpl");
        $main = str_replace("<!--id-->",$rootEntry->Id,$main);
        $main = str_replace("<!--title-->",htmlentities($rootEntry->Title,ENT_NOQUOTES,"UTF-8"),$main);

        $entries = "";
        if(count($matches)>0)
            foreach($matches as $match)
                $entries .= $match->GetHTML(null,false,true,"v2");

        $main = str_replace("<!--search-->","false",$main);
        $main = str_replace("<!--entries-->",$entries,$main);
    }

    if(!empty($main))
        $content .= Server::Replace($main,true,false,false,false);
    else
        $content .= "<div class=\"lz_kb_result_info\">".LocalizationManager::$TranslationStrings["client_kb_no_entries"]."</div>" . $result;

    return $content;
}

function doOpenExternal()
{
    $kbonly = isset($_REQUEST["p_kbo"]);
    return !empty(Server::$Configuration->File["gl_kbin"]) && !$kbonly;
}

function getSearchContent(){

    $query = !empty($_GET["search-for"]) ? $_GET["search-for"] : '%ALL%';
    $root = Communication::ReadParameter("ckf","");

    $content = "";

    $result = $navcats = "";

    if($query == "%ALL%")
    {
        $content = getFolderContent($root,true);
    }
    else if(strlen($query)>=Server::$Configuration->File["gl_kbml"])
    {
        $matches = KnowledgeBase::GetMatches($root,$query,Visitor::$BrowserLanguage,false,true);
        KnowledgeBase::ReqisterQuery($query,new VisitorBrowser("",""));

        if(count($matches)>0)
        {
            foreach($matches as $match)
                $result .= $match->GetHTML(null,false,true,"v2");

            $res = IOStruct::GetFile(PATH_TEMPLATES . "kb_result_category_v2.tpl");
            $res = str_replace("<!--title-->","\"".Str::Cut(htmlentities($query,ENT_NOQUOTES,"UTF-8"),50,true)."\"",$res);
            $res = str_replace("<!--entries-->",$result,$res);
            $res = str_replace("<!--search-->","true",$res);
            $res = str_replace("<!--id-->","sr",$res);
            $content .= "<div class=\"lz_kb_result_info\">".str_replace("<!--count-->",count($matches),LocalizationManager::$TranslationStrings["client_kb_results_found"]) . "</div>" . $res;
        }
        else
            $content .= "<div class=\"lz_kb_result_info\">".LocalizationManager::$TranslationStrings["client_search_no_result"]."</div>";
    }
    else
        $content .= "<div class=\"lz_kb_result_info\">".LocalizationManager::$TranslationStrings["client_search_no_result"]."</div>";

    $content = str_replace("_chat_","_",$content);
    return $content;
}

function getFooterBox($_entry)
{
    global $LZLANG;
    Server::InitDataBlock(array("INTERNAL","GROUPS"));
    if(($rate=Communication::ReadParameter("h",-1))!= -1)
    {
        $bhtml = "<div>".$LZLANG["client_feedback_success"]."</div>";
        $_entry->SaveRateResult($rate);
    }
    else
    {
        $bhtml = "<div style=\"white-space:nowrap\">" . getLink("&article=<!--id-->&h=MQ__#bottom","<!--icon_yes-->") . getLink("&article=<!--id-->&h=MA__#bottom","<!--icon_no-->") ."</div>";
        $_entry->RegisterView();
    }
    $result = $_entry->GetRateResult();
    $html = IOStruct::GetFile(PATH_TEMPLATES . "kb_footer.tpl");

    if(isset(Server::$Operators[$_entry->EditorId]))
        $html = str_replace("<!--name-->",Server::$Operators[$_entry->EditorId]->Fullname,$html);
    else
        $html = str_replace("<!--name-->","<br>",$html);

    $html = str_replace("<!--editor_id-->",urlencode($_entry->EditorId),$html);
    $html = str_replace("<!--date-->",$_entry->Edited,$html);
    $html = str_replace("<!--show_avatar-->",(isset(Server::$Configuration->File["gl_knsa"]) && empty(Server::$Configuration->File["gl_knsa"])) ? "hidden" : "visible",$html);

    $fhtml = ($result[0] > 0) ? str_replace("<!--users-->",$result[0],str_replace("<!--total-->",$result[1],$LZLANG["client_found_helpful"]))." " : "";
    $html = str_replace("<!--rate-->",$fhtml . $bhtml."",$html);

    return $html;
}

?>