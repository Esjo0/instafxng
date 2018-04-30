<?php
/****************************************************************************************
* LiveZilla functions.tracking.inc.php
* 
* Copyright 2017 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors.
***************************************************************************************/ 

if(!defined("IN_LIVEZILLA"))
	die();

class VisitorMonitoring
{
    public static $Visitor;
    public static $Browser;
    public static $Response;
    public static $CreateUserObject;
    public static $IsMobile;
    public static $IsTablet;
    public static $HideElement;
    public static $WasInChat = false;
    public static $IsChatAvailable = false;

    static function Abort($_code)
    {
        exit("lz_tracking_stop_tracking(".$_code.");");
    }

    static function CodeBaseAbort($_code)
    {
        exit("window.name='';lz_tracking_stop_tracking(".intval($_code).");");
    }

    static function GetPollFrequency($_chat)
    {
        if($_chat || !empty($_GET["tth"]))
            return Server::$Configuration->File["poll_frequency_clients"];

        if(Event::$RetryIn > 0)
            return min(Event::$RetryIn,Server::$Configuration->File["poll_frequency_tracking"]);

        return Server::$Configuration->File["poll_frequency_tracking"];
    }

    static function LoadOverlayChat()
    {
        global $OVERLAY; //++
        if(!empty($_GET["ovlc"]) && !VisitorMonitoring::$HideElement)
        {
            require_once(TEMPLATE_SCRIPT_OVERLAY_CHAT."ovl.php");
            Visitor::$IsActiveOverlayChat = false;
            VisitorMonitoring::$Response .= OverlayChat::$Response;
        }
    }

    static function TriggerEvents($_url,$actionData = "")
    {
        if(!(!empty(Server::$Events) && count(Server::$Events)>0))
            return $actionData = "";

        $url = $_url;
        $previous = (count(VisitorMonitoring::$Browser->History) > 1) ? VisitorMonitoring::$Browser->History[count(VisitorMonitoring::$Browser->History)-2]->Url->GetAbsoluteUrl() : "";

        foreach(Server::$Events->Events as $event)
        {
            if(!$event->IsActive || empty($url))
                continue;

            $urlor = (count($event->FunnelUrls) == 0 && $event->MatchesURLCriterias($url->Url->GetAbsoluteUrl(),$url->Referrer->GetAbsoluteUrl(),$previous,time()-($url->Entrance)));
            $urlfunnel = (count($event->FunnelUrls) > 0 && $event->MatchesURLFunnelCriterias(VisitorMonitoring::$Browser->History));
            $global = $event->MatchVisitorConditions(count(VisitorMonitoring::$Browser->History),(time()-VisitorMonitoring::$Visitor->GetEntranceTime()),VisitorMonitoring::$Visitor->HasAcceptedChatRequest,VisitorMonitoring::$Visitor->HasDeclinedChatRequest,VisitorMonitoring::$Visitor->WasInChat(),VisitorMonitoring::$Browser->Query,(VisitorMonitoring::$IsMobile||VisitorMonitoring::$IsTablet),VisitorMonitoring::$Visitor->GeoCountryISO2);
            $dataCondition = $event->MatchDataConditions(VisitorMonitoring::$Visitor);
            if($global && $dataCondition && ($urlfunnel || $urlor) || (Communication::ReadParameter("fe","")==$event->Id))
            {
                foreach(array($event->Goals,$event->Actions) as $elements)
                    foreach($elements as $action)
                    {
                        $EventTrigger = new EventTrigger(CALLER_USER_ID,CALLER_BROWSER_ID,$action->Id,time(),1,$event->Id);
                        $EventTrigger->Load();
                        $aexists = $action->Exists(CALLER_USER_ID,CALLER_BROWSER_ID);
                        if(!$EventTrigger->Exists || ($EventTrigger->Exists && $event->MatchesTriggerCriterias($EventTrigger,Visitor::$PollCount==1)))
                        {
                            if(!$aexists)
                            {
                                $actionExecuted = false;
                                if($event->SaveInCookie)
                                {
                                    if(!Is::Null(Cookie::Get("ea_" . $action->Id)))
                                        continue;
                                }

                                if($action->Type < 2)
                                {
                                    foreach($action->GetInternalReceivers() as $user_id)
                                    {
                                        $intaction = new EventActionInternal($user_id, $EventTrigger->Id);
                                        $intaction->Save();
                                        $actionExecuted = true;
                                    }
                                }
                                else if($action->Type == 2 && !defined("EVENT_INVITATION"))
                                {
                                    if(isset($_GET["group"]) && ($senderGroup=$action->Invitation->GetGroupSender()) != "" && $_GET["group"] != $senderGroup)
                                        continue;

                                    $sender = VisitorMonitoring::GetActionSender($action->Invitation->GetSenders(),true);
                                    if(!empty($sender) && !empty(Server::$Groups[$sender->GroupId]) && Server::$Groups[$sender->GroupId]->IsHumanAvailable(false) && !(VisitorMonitoring::$Browser->ChatRequest != null && !VisitorMonitoring::$Browser->ChatRequest->Closed) && !VisitorMonitoring::$Visitor->IsInChat(false,null,true))
                                    {
                                        define("EVENT_INVITATION",true);
                                        $chatrequest = new ChatRequest($sender->UserSystemId,$sender->GroupId,CALLER_USER_ID,CALLER_BROWSER_ID,VisitorMonitoring::GetActionText($sender,$action));
                                        $chatrequest->EventActionId = $action->Id;
                                        $chatrequest->Save();
                                        Event::$RetryIn = 1;
                                        VisitorMonitoring::$Browser->ChatRequest = $chatrequest;
                                        $actionExecuted = true;
                                    }
                                }
                                else if($action->Type == 6)
                                {
                                    VisitorMonitoring::$Response .= "lz_tracking_add_tag('" . base64_encode($action->Value) . "');";
                                    $actionExecuted = true;
                                }
                                else if($action->Type == 7)
                                {
                                    $action->Execute("startchat",VisitorMonitoring::$Visitor);
                                    $actionExecuted = true;
                                }
                                else if($action->Type == 9 && STATS_ACTIVE)
                                {
                                    Server::$Statistic->ProcessAction(ST_ACTION_GOAL,array(CALLER_USER_ID,$action->Id,((VisitorMonitoring::$Visitor->Visits==1)?1:0),VisitorMonitoring::$Browser->GetQueryId(Cookie::Get("sp"),null,255,true)));
                                    $actionExecuted = true;
                                }

                                if($actionExecuted)
                                {
                                    $EventTrigger->Save($event->Id);
                                    if($event->SaveInCookie)
                                    {
                                        if(Is::Null(Cookie::Get("ea_" . $action->Id)))
                                            Cookie::Set("ea_" . $action->Id,time());
                                    }
                                }
                            }
                        }
                        if($EventTrigger->Exists && $aexists)
                            $EventTrigger->Update();
                    }
            }
        }
        return $actionData;
    }

    static function GetFloatingButtonSelector()
    {
        return "";
    }

    static function GetAllowedParameters()
    {
        $allowed = array("code"=>true,"ofc"=>true,/*"el"=>true,*/"fe"=>true,"epc"=>true,"rgs"=>true,"esc"=>true,"etc"=>true,"grot"=>true,"deactr"=>true,"hinv"=>true,"ckf"=>true,"prv"=>true,"ecsgs"=>true,"hots"=>true,"oets"=>true,"hott"=>true,"oett"=>true,"hcgs"=>true,"htgs"=>true,"ecsge"=>true,"ecsc"=>true,"ecsy"=>true,"ecsx"=>true,"ecsb"=>true,"ecsa"=>true,"ecslw"=>true,"echc"=>true,"ecfs"=>true,"ecfe"=>true,"echt"=>true,"echst"=>true,"ecoht"=>true,"ecohst"=>true,"ovlv"=>true,"ovlto"=>true,"ovlt"=>true,"ovlp"=>true,"ovloe"=>true,"ovlml"=>true,"ovlmr"=>true,"ovlhm"=>true,"ovlmt"=>true,"ovlmb"=>true,"ovls"=>true,"ovloo"=>true,"ovlio"=>true,"ovlc"=>true,"ovlch"=>true,"ovlts"=>true,"ovlapo"=>true,"ovlct"=>true,"ovltwo"=>true,"ovlw"=>true,"ovlh"=>true,"group"=>true,"intgroup"=>true,"operator"=>true,"intid"=>true,"pref"=>true,"cboo"=>true,"hg"=>true,"fbpos"=>false,"fbw"=>false,"fbh"=>false,"fbshx"=>true,"fbshy"=>true,"fbshb"=>true,"fbshc"=>true,"fbmt"=>false,"fbmr"=>false,"fbmb"=>false,"fbml"=>false,"fboo"=>false,"eca"=>true,"ecw"=>true,"ech"=>true,"echm"=>true,"ecmb"=>true,"ecmr"=>true,"ecfi"=>true,"ecfo"=>true,"ecml"=>true,"ecsp"=>true/*,"cf0"=>true,"cf1"=>true,"cf2"=>true,"cf3"=>true,"cf4"=>true,"cf5"=>true,"cf6"=>true,"cf7"=>true,"cf8"=>true,"cf9"=>true*/);
        return Communication::GetTargetParameterString("",$allowed);
    }

    static function GetActionText($_sender,$_action,$break=false,$sel_message=null,$def_message=null)
    {
        if(empty($_action->Value))
        {
            $available = array(Server::$Operators[$_sender->UserSystemId]->PredefinedMessages,Server::$Groups[$_sender->GroupId]->PredefinedMessages);
            foreach($available as $list)
            {
                foreach($list as $message)
                {
                    if(LocalizationManager::ReadParams() != "" && strtoupper(LocalizationManager::ReadParams()) == strtoupper($message->LangISO))
                    {
                        $sel_message = $message;
                        $break = true;
                        break;
                    }
                    else if(strtoupper(VisitorMonitoring::$Visitor->Language) == strtoupper($message->LangISO))
                    {
                        $sel_message = $message;
                        $break = true;
                    }

                    if($message->IsDefault && empty($_action->Value))
                    {
                        $def_message = $message;
                    }
                }
                if($break)
                    break;
            }

            if($sel_message == null && $def_message != null)
                $sel_message = $def_message;

            if($_action->Type == 2 && $sel_message != null)
                $_action->Value = $sel_message->InvitationAuto;
        }

        $_action->Value = Server::$Groups[$_sender->GroupId]->TextReplace($_action->Value,($sel_message != null) ? $sel_message->LangISO : Server::$Configuration->File["gl_default_language"]);
        $_action->Value = VisitorMonitoring::$Visitor->TextReplace($_action->Value);
        $_action->Value = VisitorMonitoring::$Browser->TextReplace($_action->Value);
        $_action->Value = Server::$Operators[$_sender->UserSystemId]->TextReplace($_action->Value);
        $_action->Value = Configuration::Replace($_action->Value);
        return $_action->Value;
    }

    static function GetActionSender($_senders,$_checkOnline,$maxPriority=0,$minChats=100)
    {
        Server::InitDataBlock(array("INTERNAL","GROUPS"));
        foreach($_senders as $sender)
            if(isset(Server::$Operators[$sender->UserSystemId]) && (!$_checkOnline || ((Server::$Operators[$sender->UserSystemId]->LastActive > (time()-Server::$Configuration->File["timeout_clients"])) && Server::$Operators[$sender->UserSystemId]->Status == USER_STATUS_ONLINE)))
            {
                $maxPriority = max($maxPriority,$sender->Priority);
                if($maxPriority == $sender->Priority)
                {
                    Server::$Operators[$sender->UserSystemId]->GetExternalObjects();
                    $minChats = min($minChats, count(Server::$Operators[$sender->UserSystemId]->ExternalChats));
                    $asenders[] = $sender;
                }
            }
        if(!empty($asenders) && count($asenders)==1)
            return $asenders[0];
        else if(empty($asenders))
            return null;
        foreach($asenders as $sender)
            if($minChats == count(Server::$Operators[$sender->UserSystemId]->ExternalChats))
                $fsenders[] = $sender;
        return $fsenders[array_rand($fsenders,1)];
    }

    static function Replace($_toReplace)
    {
        $host = @$_SERVER["HTTP_HOST"];
        if(!empty($host))
            $_toReplace = str_replace("%domain%",$host,$_toReplace);
        else
            $_toReplace = str_replace("%domain%",Server::$Configuration->File["gl_site_name"],$_toReplace);
        return $_toReplace;
    }

    static function ProcessActions($actionData = "", $_chatsExternal)
    {
        if(!empty(VisitorMonitoring::$Browser->ChatRequest) && !isset($_GET["hinv"]))
        {
            Server::InitDataBlock(array("INTERNAL","GROUPS"));
            if((Server::$Operators[VisitorMonitoring::$Browser->ChatRequest->SenderSystemId]->LastActive < (time()-Server::$Configuration->File["timeout_clients"])) || Server::$Operators[VisitorMonitoring::$Browser->ChatRequest->SenderSystemId]->Status > USER_STATUS_BUSY || !Server::$Operators[VisitorMonitoring::$Browser->ChatRequest->SenderSystemId]->IsExternal(Server::$Groups, null, null) || VisitorMonitoring::$Browser->ChatRequest->Closed || !empty(VisitorMonitoring::$Browser->ChatRequest->Canceled))
            {
                if(!VisitorMonitoring::$Browser->ChatRequest->Closed)
                {
                    VisitorMonitoring::$Browser->ChatRequest->SetStatus(false,false,empty(VisitorMonitoring::$Browser->ChatRequest->Canceled),true);
                    VisitorMonitoring::$Visitor->ForceUpdate();
                }
                $actionData .= "lz_tracking_close_request('".VisitorMonitoring::$Browser->ChatRequest->Id."');";
            }
            else if(isset($_GET["decreq"]) || isset($_GET["accreq"]))
            {
                if(isset($_GET["decreq"]))
                    VisitorMonitoring::$Browser->ChatRequest->SetStatus(false,false,true,true);
                if(isset($_GET["accreq"]))
                    VisitorMonitoring::$Browser->ChatRequest->SetStatus(false,true,false,true);
                if(isset($_GET["clreq"]))
                    $actionData .= "lz_tracking_close_request();";
                if(!VisitorMonitoring::$Browser->ChatRequest->Closed)
                    VisitorMonitoring::$Visitor->ForceUpdate();
            }
            else if(!VisitorMonitoring::$Browser->ChatRequest->Accepted && !VisitorMonitoring::$Browser->ChatRequest->Declined)
            {
                if(!empty($_GET["ovlc"]) && !$_chatsExternal)
                {

                }
                else
                {
                    $invitationSettings = @unserialize(base64_decode(Server::$Configuration->File["gl_invi"]));
                    array_walk($invitationSettings,"b64dcode");
                    $invitationHTML = Server::Replace(VisitorMonitoring::$Browser->ChatRequest->CreateInvitationTemplate($invitationSettings[13],Server::$Configuration->File["gl_site_name"],Server::$Configuration->File["wcl_window_width"],Server::$Configuration->File["wcl_window_height"],LIVEZILLA_URL,Server::$Operators[VisitorMonitoring::$Browser->ChatRequest->SenderSystemId],$invitationSettings[0]));
                    VisitorMonitoring::$Browser->ChatRequest->Invitation = new Invitation($invitationHTML,VisitorMonitoring::$Browser->ChatRequest->Text,$invitationSettings);
                }

                if(!Is::Null($action = Server::$Events->GetActionById(VisitorMonitoring::$Browser->ChatRequest->EventActionId)) && !empty(VisitorMonitoring::$Browser->ChatRequest->Invitation))
                {
                    VisitorMonitoring::$Browser->ChatRequest->Invitation->Text = VisitorMonitoring::$Browser->ChatRequest->Text;
                }

                if(!VisitorMonitoring::$Browser->ChatRequest->Displayed)
                {
                    VisitorMonitoring::$Browser->ChatRequest->SetStatus(true,false,false,false);
                }

                if(!empty(VisitorMonitoring::$Browser->ChatRequest->Invitation) && (!VisitorMonitoring::$Browser->OverlayContainer || $_chatsExternal))
                {
                    $actionData .= VisitorMonitoring::$Browser->ChatRequest->Invitation->GetCommand(VisitorMonitoring::$Browser->ChatRequest->Id);
                }
            }
        }
        return $actionData;
    }

    static function GetJSCustomArray($_historyCustoms=null,$getCustomParams="")
    {
        Server::InitDataBlock(array("INPUTS"));
        $valArray=array();
        foreach(Server::$Inputs as $index => $input)
        {
            if($input->Active && $input->Custom)
            {
                if(isset($_GET["cf".$input->Index]))
                    $valArray[$index] = "'" . getParam("cf".$input->Index) . "'";
                else if(isset($_GET["ptcf".$input->Index]))
                    $valArray[$index] = "'" . urlencode($_GET["ptcf".$input->Index]) . "'";
                else if(is_array($_historyCustoms) && isset($_historyCustoms[$input->Index]) && !empty($_historyCustoms[$input->Index]))
                    $valArray[$index] = "'" . Encoding::Base64UrlEncode($_historyCustoms[$input->Index]) . "'";
                else
                    $valArray[$index] = "''";
            }
            else if($input->Custom)
                $valArray[$index] = "''";
        }
        ksort($valArray);
        foreach($valArray as $param)
        {
            if(!empty($getCustomParams))
                $getCustomParams .= ",";
            $getCustomParams .= $param;
        }
        return $getCustomParams;
    }
}
?>