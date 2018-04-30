<?php

/****************************************************************************************
*
* API version 2.0
*
* Copyright 2017 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
***************************************************************************************/

if(!defined("IN_LIVEZILLA"))
    die();

class ApiV2
{
    public $ErrorField = "";
    public $ErrorFilter = "";
    public $ActionPerformed = "";
    public $JSONOutput = "";
    public $JSONParams;

    function __construct($_prettyPrint=false)
    {
        if($_prettyPrint && defined("JSON_PRETTY_PRINT"))
            $this->JSONParams = JSON_PRETTY_PRINT;
    }

    function RunActions()
    {
        if(!empty($_POST["p_operator_create"]))
        {
            $this->OperatorCreate();
            return true;
        }
        else if(!empty($_POST["p_operator_delete"]))
        {
            $this->OperatorDelete();
            return true;
        }
        else if(!empty($_POST["p_operators_list"]))
        {
            $this->OperatorsList();
            return true;
        }
        else if(!empty($_POST["p_chats_list"]))
        {
            $this->ChatsList();
            return true;
        }
        else if(!empty($_POST["p_tickets_list"]))
        {
            $this->TicketsList();
            return true;
        }
        else if(!empty($_POST["p_ticket_create"]))
        {
            $this->TicketCreate();
            return true;
        }
        else if(!empty($_POST["p_ticketmessage_create"]))
        {
            $this->TicketMessageCreate();
            return true;
        }
        else if(!empty($_POST["p_ticketeditor_assign"]))
        {
            $this->TicketEditorAssign();
            return true;
        }
        else if(!empty($_POST["p_knowledgebase_entries_list"]))
        {
            $this->KnowledgebaseEntriesList();
            return true;
        }
        else if(!empty($_POST["p_knowledgebase_entry_create"]))
        {
            $this->KnowledgebaseEntryCreate();
            return true;
        }
        else if(!empty($_POST["p_cronjob_execute"]))
        {
            $mt = isset($_POST["p_maintenance"]) && $_POST["p_maintenance"]=="1";
            $sct = isset($_POST["p_send_chat_transcripts"]) && $_POST["p_send_chat_transcripts"]=="1";
            $rm = isset($_POST["p_receive_emails"]) && $_POST["p_receive_emails"]=="1";
            $sm = isset($_POST["p_social_media"]) && $_POST["p_social_media"]=="1";

            Server::RunCronJobs(true,$mt,$sct,$rm,$sm);
            $this->JSONOutput = "SUCCESS";
            return true;
        }
        return false;
    }

    function GetErrorCodes()
    {
        if(!empty($this->ErrorField) && !empty($this->ErrorFilter))
            return " (".$this->ErrorField.",".$this->ErrorFilter.")";
        else if(!empty($this->ErrorField) || !empty($this->ErrorFilter))
            return " (".$this->ErrorField.$this->ErrorFilter.")";
        else
            return "";
    }

    function OperatorCreate()
    {
        $op = new Operator(getId(10),getId(10));

        $params = ApiV2::GetObjectFields("Operator");
        if($op = $this->CreateFromJSON($params,json_decode($_POST["p_data"]),$op,"Operator"))
        {
            Logging::SecurityLog("Operator->Create (API)",$op->UserId,Is::Defined("CALLER_SYSTEM_ID"));
            foreach(Server::$Operators as $operator)
                if($operator->UserId == $op->UserId)
                {
                    $this->ErrorField = "UserId";
                    return;
                }
            $op->Created = time();
            array_walk($op->Groups,"b64ecode");
            $op->Password = hash("sha256",$op->Password);

            $op->Save(true);
            array_walk($op->Groups,"b64dcode");
            $this->JSONOutput = APIV2::Encode(array("Operator"=>ApiV2::ClearObject($params,$op)), $this->JSONParams);
            CacheManager::FlushKey(DATA_CACHE_KEY_OPERATORS);
        }
    }

    function OperatorsList()
    {
        $output = array("Operators"=>array());
        foreach(Server::$Operators as $operator)
        {
            if(!empty($_POST["p_userid"]) && $operator->UserId != $_POST["p_userid"])
                continue;
            if(!empty($_POST["p_status"]) && $operator->Status != $_POST["p_status"])
                continue;
            if(!empty($_POST["p_group"]) && !in_array($_POST["p_group"],$operator->Groups))
                continue;

            $operator->GetExternalChatAmount();

            if(!empty($_POST["p_full_chats"]))
                $operator->GetExternalObjects();

            $output["Operators"][] = array("Operator"=>ApiV2::ClearObject(ApiV2::GetObjectFields("Operator"),$operator));
        }
        $this->JSONOutput = APIV2::Encode($output, $this->JSONParams);
    }

    function OperatorDelete()
    {
        $op = new Operator(getId(10),getId(10));
        Logging::SecurityLog("Operator->Delete (API)",$op->UserId,Is::Defined("CALLER_SYSTEM_ID"));
        $params = array("UserId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Required"=>true));
        if($op = $this->CreateFromJSON($params,json_decode($_POST["p_data"]),$op,"Operator"))
        {
            foreach(Server::$Operators as $operator)
            {
                if(strtolower($operator->UserId) == $op->UserId)
                {
                    $operator->Delete();
                    $this->JSONOutput = APIV2::Encode(ApiV2::ClearObject(ApiV2::GetObjectFields("Operator"),$operator), $this->JSONParams);
                    CacheManager::FlushKey(DATA_CACHE_KEY_OPERATORS);
                    return;
                }
            }
        }
        $this->ErrorFilter = "UserId";
    }

    function ChatsList()
    {
        $sql_limit="";
        if(!empty($_POST["p_limit"]))
        {
            if(is_numeric($_POST["p_limit"]))
                $sql_limit = " LIMIT ". $_POST["p_limit"];
            else
            {
                $this->ErrorFilter = "Limit";
                return;
            }
        }
        $sql_where = "WHERE `closed`>0";
        if(!empty($_POST["p_chatid"]))
        {
            $sql_where .= " AND `chat_id`='".DBManager::RealEscape($_POST["p_chatid"])."'";
        }
		if(!empty($_POST["p_group"]))
		{
			$sql_where .= " AND `group_id`='".DBManager::RealEscape($_POST["p_group"])."'";
		}
		if(!empty($_POST["p_operator"]))
		{
			$opsid = Operator::GetSystemId($_POST["p_operator"]);
			if($opsid!=null)
				$sql_where .= " AND `internal_id`='".DBManager::RealEscape($opsid)."'";
			else
			{
				$this->ErrorFilter = "Operator";
				return;
			}
		}
		if(!empty($_POST["p_start_after"]))
		{
			if(is_numeric($_POST["p_start_after"]) && !empty($_POST["p_start_after"]))
				$sql_where .= " AND `time` > ". $_POST["p_start_after"];
			else
			{
				$this->ErrorFilter = "Start After";
				return;
			}
		}
		if(!empty($_POST["p_start_before"]))
		{
			if(is_numeric($_POST["p_start_before"]) && !empty($_POST["p_start_before"]))
				$sql_where .= " AND `time` < ". $_POST["p_start_before"];
			else
			{
				$this->ErrorFilter = "Start Before";
				return;
			}
		}

        $results = array("Chats"=>array());
        $result = DBManager::Execute(true, "SELECT * FROM `" . DB_PREFIX . DATABASE_CHAT_ARCHIVE . "` " . $sql_where . " ORDER BY `chat_id` ASC " . $sql_limit . ";");
        while($row = DBManager::FetchArray($result))
        {
            $chat = new Chat($row["chat_id"]);
			$chat->SetValues($row,true);
			
			if(isset($_POST["p_output"]))
			{
				if($_POST["p_output"]=="HTML")
					unset($chat->Plaintext);
				if($_POST["p_output"]=="Plaintext")
					unset($chat->HTML);
			}
            
            $results["Chats"][] = array("Chat"=>ApiV2::ClearObject(ApiV2::GetObjectFields("Chat"),$chat));
        }
        $this->JSONOutput = APIV2::Encode($results, $this->JSONParams);
    }

    function TicketsList()
    {
        $sql_limit="";
        if(!empty($_POST["p_limit"]))
        {
            if(is_numeric($_POST["p_limit"]))
                $sql_limit = " LIMIT ". $_POST["p_limit"];
            else
            {
                $this->ErrorFilter = "Limit";
                return;
            }
        }
        $sql_where = "WHERE `t1`.`deleted`=0 AND `t1`.`id`>0 ";
        if(!empty($_POST["p_id"]))
        {
            $sql_where .= " AND `t1`.`id`='".DBManager::RealEscape($_POST["p_id"])."'";
        }
        else
        {
            $joinEditor = false;
            if(!empty($_POST["p_group"]))
            {
                $joinEditor = true;
                $sql_where .= " AND `t3`.`group_id`='".DBManager::RealEscape($_POST["p_group"])."'";
            }
            if(!empty($_POST["p_operator"]))
            {
                $joinEditor = true;
                $opsid = Operator::GetSystemId($_POST["p_operator"]);
                if($opsid!=null)
                    $sql_where .= " AND `t3`.`editor_id`='".DBManager::RealEscape($opsid)."'";
                else
                {
                    $this->ErrorFilter = "Operator";
                    return;
                }
            }
            if(!empty($_POST["p_created_after"]))
            {
                if(is_numeric($_POST["p_created_after"]) && !empty($_POST["p_created_after"]))
                    $sql_where .= " AND `t2`.`created` > ". intval($_POST["p_created_after"]);
                else
                {
                    $this->ErrorFilter = "Created After";
                    return;
                }
            }
            if(!empty($_POST["p_created_before"]))
            {
                if(is_numeric($_POST["p_created_before"]) && !empty($_POST["p_created_before"]))
                    $sql_where .= " AND `t2`.`created` < ". intval($_POST["p_created_before"]);
                else
                {
                    $this->ErrorFilter = "Created Before";
                    return;
                }
            }
            if(isset($_POST["p_status"]) && strlen($_POST["p_status"]) > 0)
            {
                $joinEditor = true;
                $sql_status = "";
                $statuses = explode(',',$_POST["p_status"]);
                if(!empty($statuses))
                    foreach($statuses as $status)
                        $sql_status .= ((!empty($sql_status)) ? " or " : "") . "`t3`.`status` = " . intval($status);

                if(!empty($sql_status))
                    $sql_where .= " AND (".$sql_status.")";
                else
                {
                    $this->ErrorFilter = "Status";
                    return;
                }
            }
        }
        $results = array("Tickets"=>array());

        $joinEditor = ($joinEditor)? " INNER JOIN `".DB_PREFIX.DATABASE_TICKET_EDITORS."` as `t3` ON `t1`.`id`=`t3`.`ticket_id` " : " ";
        $result = DBManager::Execute(true, $d = "SELECT t1.*,t2.* FROM `" . DB_PREFIX . DATABASE_TICKETS . "` as `t1` INNER JOIN `" . DB_PREFIX . DATABASE_TICKET_MESSAGES . "` as `t2` ON `t1`.`id`=`t2`.`id`" . $joinEditor . $sql_where . " ORDER BY `t1`.`id` ASC " . $sql_limit . ";");
	
		while($row = DBManager::FetchArray($result))
        {
            $ticket = new Ticket($row,true,true);
            $results["Tickets"][] = array("Ticket"=>ApiV2::ClearObject(ApiV2::GetObjectFields("Ticket"),$ticket));
        }

        $this->JSONOutput = APIV2::Encode($results, $this->JSONParams);
    }

    function TicketCreate()
    {
        $ticket = new Ticket();
        $params = ApiV2::GetObjectFields("Ticket");
        if($ticket = $this->CreateFromJSON($params,json_decode($_POST["p_data"]),$ticket,"Ticket"))
        {
            $ticket->Id = CacheManager::GetObjectId("ticket_id",DATABASE_TICKETS);
            if(!isset(Server::$Groups[$ticket->Group]))
                $this->ErrorField = "Group";
            else
            {
                $ticket->Language = strtoupper($ticket->Language);
                $ticket->Save();
                $this->JSONOutput = APIV2::Encode(array("Ticket"=>ApiV2::ClearObject($params,$ticket)), $this->JSONParams);
            }
        }
        CacheManager::SetDataUpdateTime(DATA_UPDATE_KEY_TICKETS);
    }

    function TicketMessageCreate()
    {
        Server::InitCacheManager();
        Server::InitDataBlock(array("INTERNAL","GROUPS"));
        $message = new TicketMessage();
        $params = ApiV2::GetObjectFields("TicketMessage");

        if($message = $this->CreateFromJSON($params,json_decode($_POST["p_data"]),$message,"TicketMessage"))
        {
            $ticket = new Ticket($message->TicketId,true);
            $ticket->LoadMessages();

            if(count($ticket->Messages)==0)
                $message->Id = $ticket->Id;
            else
                $message->Id = getid(32);

            if(!empty($message->SenderId))
               if(Operator::GetSystemId($message->SenderId)!=null)
                   $message->SenderUserId = Operator::GetSystemId($message->SenderId);

            if(is_array($message->Customs))
            {
                $message->Customs = ApiV2::ToNameBasedArray($message->Customs);
                $message->Customs = DataInput::ToIndexBased($message->Customs);
            }

            if(is_array($message->Comments))
                foreach($message->Comments as $comar)
                    $message->AddComment($comar[0],$ticket->Id,$comar[1]);

            if(empty($message->ChannelId))
                $message->ChannelId = getId(32);

            $message->Hash = $ticket->GetHash();
            $message->Save($ticket->Id);

            if(!empty($_POST["p_sendemailreply"]))
            {
                $ticket->Load();
                $ticket->SendOperatorReply($message->Id,$message->Email,(!empty($_POST["p_quotemessageid"]) ? $_POST["p_quotemessageid"] : ""));
            }
            if(!empty($_POST["p_sendemailresponder"]))
            {
                $ticket->Load();
                $ticket->SendAutoresponder(null,null,$message);
            }
            $ticket->SetLastUpdate(time());
            $this->JSONOutput = APIV2::Encode(array("TicketMessage"=>ApiV2::ClearObject($params,$message)), $this->JSONParams);
        }
        CacheManager::SetDataUpdateTime(DATA_UPDATE_KEY_TICKETS);
    }

    function TicketEditorAssign()
    {
        $editor = new TicketEditor();
        $params = ApiV2::GetObjectFields("TicketEditor");
        if($editor = $this->CreateFromJSON($params,json_decode($_POST["p_data"]),$editor,"TicketEditor"))
        {
            $Ticket = new Ticket();
            $Ticket->Id = $editor->Id;
            if($Ticket->Load())
            {
                $editor->Editor = Operator::GetSystemId($editor->Editor);
                if(isset(Server::$Operators[$editor->Editor]))
                {
                    $editor->Save();
                    $Ticket->Editor = $editor;
                    $Ticket->LoadMessages();
                    $Ticket->SetLastUpdate(time());
                    $this->JSONOutput = APIV2::Encode(array("TicketEditor"=>ApiV2::ClearObject($params,$editor)), $this->JSONParams);
                    CacheManager::SetDataUpdateTime(DATA_UPDATE_KEY_TICKETS);
                }
                else
                    $this->ErrorField = "Editor";
            }
            else
                $this->ErrorField = "Id";
        }
        CacheManager::SetDataUpdateTime(DATA_UPDATE_KEY_TICKETS);
    }

    function KnowledgebaseEntriesList()
    {
        $sql_limit="";
        if(!empty($_POST["p_limit"]))
        {
            if(is_int($_POST["p_limit"]))
                $sql_limit = " LIMIT ". intval($_POST["p_limit"]);
            else
            {
                $this->ErrorFilter = "Limit";
                return;
            }

            if(!empty($_POST["p_offset"]))
            {
                if(is_int($_POST["p_offset"]))
                    $sql_limit .= " OFFSET ". intval($_POST["p_offset"]);
                else
                {
                    $this->ErrorFilter = "Offset";
                    return;
                }
            }
        }
        $sql_where = "WHERE `discarded`=0 AND `parentid`<>100";

        if(!empty($_POST["p_id"]))
            $sql_where .= " AND `id`='".DBManager::RealEscape($_POST["p_id"])."'";
        if(empty($_POST["p_show_private"]))
            $sql_where .= " AND `kb_public`=1";
        if(!empty($_POST["p_parent_id"]))
            $sql_where .= " AND `parentid`='".DBManager::RealEscape($_POST["p_parent_id"])."'";

        $results = array("KnowledgeBaseEntries"=>array());
        $result = DBManager::Execute(true, "SELECT * FROM `" . DB_PREFIX . DATABASE_RESOURCES . "` " . $sql_where . " ORDER BY `title` ASC " . $sql_limit . ";");

        while($row = DBManager::FetchArray($result))
        {
            $kbe = new KnowledgeBaseEntry($row);
            $results["KnowledgeBaseEntries"][] = array("KnowledgeBaseEntry"=>ApiV2::ClearObject(ApiV2::GetObjectFields("KnowledgeBaseEntry"),$kbe));
        }

        $this->JSONOutput = APIV2::Encode($results, $this->JSONParams);
    }

    function KnowledgebaseEntryCreate()
    {
        $kbEntry = new KnowledgeBaseEntry();
        $params = ApiV2::GetObjectFields("KnowledgeBaseEntry");
        if($kbEntry = $this->CreateFromJSON($params,json_decode($_POST["p_data"]),$kbEntry,"KnowledgeBaseEntry"))
        {
            $parent = KnowledgeBaseEntry::GetById($kbEntry->ParentId,false);
            if(!($parent != null && $parent["type"] == 0))
                $this->ErrorFilter = "ParentId";
            else
            {
                $kbEntry->CalculateRank();
                $kbEntry->EditorId = CALLER_SYSTEM_ID;

                if(empty($kbEntry->OwnerId))
                    $kbEntry->OwnerId = CALLER_SYSTEM_ID;

                $kbEntry->Edited =
                $kbEntry->Created = time()+1;
                if(empty($kbEntry->Id))
                    $kbEntry->Id = getId(32);
                $kbEntry->Save();
                $this->JSONOutput = APIV2::Encode(array("KnowledgeBaseEntry"=>ApiV2::ClearObject($params,$kbEntry)), $this->JSONParams);
            }
        }
    }

    function CreateFromJSON($_params,$_jobject,$_object,$_objname)
    {
        if(!isset($_jobject->{$_objname}))
        {
            $this->ErrorField = $_objname;
            return false;
        }
        foreach ($_params as $fid => $field)
        {
            if(!$field["Input"])
                continue;
            $error = false;
            if(isset($_jobject->{$_objname}->{$fid}))
            {
                $value = $_jobject->{$_objname}->{$fid};
                if($field["Type"]=="int" && intval($value) != $value)
                    $error = true;
                else if($field["Type"]=="array" && !(is_array($_jobject->{$_objname}->{$fid}) && !empty($_jobject->{$_objname}->{$fid})))
                    $error = true;
                else if($field["Type"]=="string" && $field["Required"] && empty($value))
                    $error = true;
            }
            else if($field["Required"])
                $error = true;
            if($error)
            {
                $this->ErrorField = $fid;
                return false;
            }

            if(isset($_jobject->{$_objname}->{$fid}))
                $_object->{$fid} = $_jobject->{$_objname}->{$fid};
        }
        return $_object;
    }

    static function Encode($_toEncode,$_params)
    {
        if(Server::CheckPhpVersion(5,3,0))
            return json_encode($_toEncode, $_params);
        else
            return json_encode($_toEncode);
    }

    static function ToNameBasedArray($_indexBased)
    {
        $nameBased = array();
        foreach($_indexBased as $array)
        {

            $nameBased[$array[0]] = $array[1];
        }
        return $nameBased;
    }

    static function ClearObject($_params,$_object,$onlyOutput=false)
    {
        $stObjectTypes = array("int","string","array","boolean");

        if($_object==null)
            return;

        $cobject = clone $_object;
        $reflection = new ReflectionClass($cobject);
        foreach ($reflection->getProperties() as $property)
        {
            if($property->isStatic())
                continue;

            if(!isset($_params[$property->getName()]) || ($onlyOutput && isset($_params[$property->getName()]) && !$_params[$property->getName()]["Input"]) || (!$onlyOutput && isset($_params[$property->getName()]) && !$_params[$property->getName()]["Output"]))
            {
                unset($cobject->{$property->getName()});
            }
            else
            {
                $type = $_params[$property->getName()]["Type"];
                $obj = $property->getValue($cobject);
                if(!empty($obj) && !in_array($type,$stObjectTypes))
                {
                    if(strpos($type,"array<")===0)
                    {
                        $subtype = str_replace(array("array<",">"),"",$type);
                        foreach($obj as $ind => $subj)
                        {
                            if(class_exists($subtype))
                            {
                                $obj[$ind] = array($subtype=>ApiV2::ClearObject(ApiV2::GetObjectFields($subtype),$subj[$subtype],$onlyOutput));
                            }
                        }
                        $property->setValue($cobject,$obj);
                    }
                    else if(class_exists($type) && !empty($obj[$type]))
                    {
                        $property->SetValue($cobject,ApiV2::ClearObject(ApiV2::GetObjectFields($type),$obj[$type],$onlyOutput));
                    }
                }
            }

        }
        return $cobject;
    }

    static function GetGeneralDefinitions()
    {
        $general["Authentication"]["User"] = array("Type"=>"string","Required"=>true,"Description"=>"API Authentication User","Example"=>"administrator");
        $general["Authentication"]["Password"] = array("Type"=>"string","Required"=>true,"Description"=>"API Authentication Password (md5 encoded)","Example"=>"md5('password')");
        $general["Parameters"]["JSON_Pretty"] = array("Type"=>"int","Required"=>false,"Description"=>"Activates PHP JSON Pretty Print output","Example"=>"1");

        return $general;
    }

    static function GetObjectDefinitions($_operatorA,$_chatA,$_ticketA,$_voucherA,$_ticketMessageA,$_ticketEditorA,$_kbEntry)
    {
        $objects["Operator"]["Fields"] = ApiV2::GetObjectFields("Operator");

        foreach($objects["Operator"]["Fields"] as $fid => $field)
            if(isset($field["Code"]))
                $_operatorA->{$fid} = $field["Code"];
            else
                $_operatorA->{$fid} = $field["Example"];

        $_operatorOut = ApiV2::ClearObject($objects["Operator"]["Fields"],$_operatorA,true);

        $objects["Operator"]["Functions"]["List"]["Version"] = "5.2.5.0";
        $objects["Operator"]["Functions"]["List"]["Title"] = "List Operators";
        $objects["Operator"]["Functions"]["List"]["Call"] = "POST /api/v2/api.php";
        $objects["Operator"]["Functions"]["List"]["Param"] = "POST /api/v2/api.php p_operators_list=1";
        $objects["Operator"]["Functions"]["List"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_operators_list=1";
        $objects["Operator"]["Functions"]["List"]["Response"] = "JSON code of operator(s)";
        $objects["Operator"]["Functions"]["List"]["Fields"] = array("true");
        $objects["Operator"]["Functions"]["List"]["Filters"] = array("UserId"=>array("Type"=>"string","Required"=>false,"Comment"=>"Response will be the Operator matching this login Id.","Example"=>"john_doe"),"Status"=>array("Type"=>"int","Required"=>false,"Comment"=>"Returns all operators having this status.","Example"=>"1"),"Group"=>array("Type"=>"string","Required"=>false,"Comment"=>"Returns all operators that are member of this group.","Example"=>"groupid1"),"Full Chats"=>array("Type"=>"bool","Required"=>false,"Comment"=>"Return full list of external chat objects (LiveZilla 5.4.0.1).","Example"=>"1"));
        $objects["Operator"]["Functions"]["List"]["OutputObject"] = array("Operators"=>array(array("Operator"=>$_operatorOut),array("Operator"=>$_operatorOut)));

        $objects["Operator"]["Functions"]["Create"]["Version"] = "5.2.5.0";
        $objects["Operator"]["Functions"]["Create"]["Title"] = "Create Operator";
        $objects["Operator"]["Functions"]["Create"]["Call"] = "POST /api/v2/api.php";
        $objects["Operator"]["Functions"]["Create"]["Param"] = "POST /api/v2/api.php p_operator_create=1";
        $objects["Operator"]["Functions"]["Create"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_operator_create=1";
        $objects["Operator"]["Functions"]["Create"]["Response"] = "JSON code of created operator";
        $objects["Operator"]["Functions"]["Create"]["Fields"] = array("true");
        $objects["Operator"]["Functions"]["Create"]["Filters"] = array();
        $objects["Operator"]["Functions"]["Create"]["OutputObject"] = array("Operator"=>$_operatorOut);
        $objects["Operator"]["Functions"]["Create"]["InputObject"] = $objects["Operator"]["Fields"];

        $objects["Operator"]["Functions"]["Delete"]["Version"] = "5.2.5.0";
        $objects["Operator"]["Functions"]["Delete"]["Title"] = "Delete Operator";
        $objects["Operator"]["Functions"]["Delete"]["Call"] = "POST /api/v2/api.php";
        $objects["Operator"]["Functions"]["Delete"]["Param"] = "POST /api/v2/api.php p_operator_delete=1";
        $objects["Operator"]["Functions"]["Delete"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_operator_delete=1";
        $objects["Operator"]["Functions"]["Delete"]["Response"] = "JSON code of deleted operator";
        $objects["Operator"]["Functions"]["Delete"]["Fields"] = array("UserId");
        $objects["Operator"]["Functions"]["Delete"]["Filters"] = array();
        $objects["Operator"]["Functions"]["Delete"]["OutputObject"] = array("Operator"=>$_operatorOut);
        $objects["Operator"]["Functions"]["Delete"]["InputObject"] = array("UserId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"The alphanumeric login ID of the operators.","Required"=>true,"Example"=>"john_doe"));

        $objects["Chat"]["Fields"] = ApiV2::GetObjectFields("Chat");

        foreach($objects["Chat"]["Fields"] as $fid => $field)
            if(isset($field["Code"]))
                $_chatA->{$fid} = $field["Code"];
            else
                $_chatA->{$fid} = $field["Example"];

        $_chatOut = ApiV2::ClearObject($objects["Chat"]["Fields"],$_chatA);

        $objects["Chat"]["Functions"]["List"]["Version"] = "5.2.5.0";
        $objects["Chat"]["Functions"]["List"]["Title"] = "List Chat Transcripts";
        $objects["Chat"]["Functions"]["List"]["Call"] = "POST /api/v2/api.php";
        $objects["Chat"]["Functions"]["List"]["Param"] = "POST /api/v2/api.php p_chats_list=1";
        $objects["Chat"]["Functions"]["List"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_chats_list=1<br>-d p_limit=10";
        $objects["Chat"]["Functions"]["List"]["Response"] = "JSON code of chat transcript(s)";
        $objects["Chat"]["Functions"]["List"]["Fields"] = array("true");
        $objects["Chat"]["Functions"]["List"]["Filters"] = array("ChatId"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return the chat matching this chat Id.","Example"=>"11123"),"Group"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all chats of this group.","Example"=>"groupid1"),"Operator"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all chats of this operator.","Example"=>"john_doe"),"Start After"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all chats with start time later than<br>UNIX Timestamp","Example"=>"1505167200"),"Start Before"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all chats with start time earlier than<br>UNIX Timestamp","Example"=>"1505167200"),"Output"=>array("Type"=>"string","Required"=>false,"Comment"=>"HTML | Plaintext","Example"=>"Plaintext"),"Limit"=>array("Type"=>"int","Required"=>false,"Comment"=>"Maximum number of chats to return.","Example"=>"100"));
        $objects["Chat"]["Functions"]["List"]["OutputObject"] = array("Chats"=>array(array("Chat"=>$_chatOut)));

        $objects["Ticket"]["Fields"] = ApiV2::GetObjectFields("Ticket");

        foreach($objects["Ticket"]["Fields"] as $fid => $field)
            if(isset($field["Code"]))
                $_ticketA->{$fid} = $field["Code"];
            else
                $_ticketA->{$fid} = $field["Example"];

        $_ticketA = ApiV2::ClearObject($objects["Ticket"]["Fields"],$_ticketA);

        $objects["Ticket"]["Functions"]["List"]["Version"] = "5.2.5.0";
        $objects["Ticket"]["Functions"]["List"]["Title"] = "List Tickets";
        $objects["Ticket"]["Functions"]["List"]["Call"] = "POST /api/v2/api.php";
        $objects["Ticket"]["Functions"]["List"]["Param"] = "POST /api/v2/api.php p_tickets_list=1";
        $objects["Ticket"]["Functions"]["List"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_tickets_list=1<br>-d p_limit=10";
        $objects["Ticket"]["Functions"]["List"]["Response"] = "JSON code of ticket(s)";
        $objects["Ticket"]["Functions"]["List"]["Fields"] = array("true");
        $objects["Ticket"]["Functions"]["List"]["Filters"] = array("Id"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return the ticket matching this Id.","Example"=>"11123"),"Group"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all tickets of this group.","Example"=>"groupid1"),"Operator"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all tickets of this operator.","Example"=>"john_doe"),"Created After"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all tickets created later than<br>UNIX Timestamp","Example"=>"1505167200"),"Created Before"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all tickets created earlier than<br>UNIX Timestamp","Example"=>"1505167200"),"Limit"=>array("Type"=>"int","Required"=>false,"Comment"=>"Maximum number of tickets to return.","Example"=>"100"),"Status"=>array("Type"=>"string","Required"=>false,"Comment"=>"Return all tickets with specific status. Comma-separated list of statuses <br><br>[0] = Open<br>[1] = In Progress<br>[2] = Closed<br>[3] = Deleted<br>[4] = Pending","Example"=>"0,2,3"));
        $objects["Ticket"]["Functions"]["List"]["OutputObject"] = array("Tickets"=>array(array("Ticket"=>$_ticketA)));

        $objects["Ticket"]["Functions"]["Create"]["Version"] = "5.2.5.0";
        $objects["Ticket"]["Functions"]["Create"]["Title"] = "Create Ticket";
        $objects["Ticket"]["Functions"]["Create"]["Call"] = "POST /api/v2/api.php";
        $objects["Ticket"]["Functions"]["Create"]["Param"] = "POST /api/v2/api.php p_ticket_create=1";
        $objects["Ticket"]["Functions"]["Create"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_ticket_create=1";
        $objects["Ticket"]["Functions"]["Create"]["Response"] = "JSON code of created ticket";
        $objects["Ticket"]["Functions"]["Create"]["Fields"] = array("true");
        $objects["Ticket"]["Functions"]["Create"]["Filters"] = array();
        $objects["Ticket"]["Functions"]["Create"]["OutputObject"] = array("Ticket"=>$_ticketA);
        $objects["Ticket"]["Functions"]["Create"]["InputObject"] = $objects["Ticket"]["Fields"];

        $objects["TicketMessage"]["Fields"] = ApiV2::GetObjectFields("TicketMessage");
        $objects["TicketMessage"]["Parent"] = "Ticket";

        foreach($objects["TicketMessage"]["Fields"] as $fid => $field)
            if(isset($field["Code"]))
                $_ticketMessageA->{$fid} = $field["Code"];
            else
                $_ticketMessageA->{$fid} = $field["Example"];

        $_ticketMessageA = ApiV2::ClearObject($objects["TicketMessage"]["Fields"],$_ticketMessageA);

        $objects["TicketMessage"]["Functions"]["Create"]["Version"] = "5.2.5.0";
        $objects["TicketMessage"]["Functions"]["Create"]["Title"] = "Create Ticket Message";
        $objects["TicketMessage"]["Functions"]["Create"]["Call"] = "POST /api/v2/api.php";
        $objects["TicketMessage"]["Functions"]["Create"]["Param"] = "POST /api/v2/api.php p_ticketmessage_create=1";
        $objects["TicketMessage"]["Functions"]["Create"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_ticketmessage_create=1";
        $objects["TicketMessage"]["Functions"]["Create"]["Response"] = "JSON code of created ticket message";
        $objects["TicketMessage"]["Functions"]["Create"]["Fields"] = array("true");
        $objects["TicketMessage"]["Functions"]["Create"]["Filters"] = array("SendEmailResponder"=>array("Input"=>true,"Output"=>false,"Type"=>"bool","Comment"=>"Send autoresponder email to sender of message","Required"=>false,"Example"=>"1"),"SendEmailReply"=>array("Input"=>true,"Output"=>false,"Type"=>"bool","Comment"=>"Send operator reply email to receiver of message","Required"=>false,"Example"=>"1"),"QuoteMessageId"=>array("Input"=>true,"Output"=>false,"Type"=>"string","Comment"=>"The Ticket Message ID of the Message the Operator is replying to.","Required"=>false,"Example"=>"90f9cf..."));
        $objects["TicketMessage"]["Functions"]["Create"]["OutputObject"] = array("TicketMessage"=>$_ticketMessageA);
        $objects["TicketMessage"]["Functions"]["Create"]["InputObject"] = $objects["TicketMessage"]["Fields"];

        $objects["TicketEditor"]["Fields"] = ApiV2::GetObjectFields("TicketEditor");
        $objects["TicketEditor"]["Parent"] = "Ticket";

        foreach($objects["TicketEditor"]["Fields"] as $fid => $field)
            if(isset($field["Code"]))
                $_ticketEditorA->{$fid} = $field["Code"];
            else
                $_ticketEditorA->{$fid} = $field["Example"];

        $_ticketEditorA = ApiV2::ClearObject($objects["TicketEditor"]["Fields"],$_ticketEditorA);

        $objects["TicketEditor"]["Functions"]["Assign"]["Version"] = "5.2.5.0";
        $objects["TicketEditor"]["Functions"]["Assign"]["Title"] = "Assign Ticket Editor";
        $objects["TicketEditor"]["Functions"]["Assign"]["Call"] = "POST /api/v2/api.php";
        $objects["TicketEditor"]["Functions"]["Assign"]["Param"] = "POST /api/v2/api.php p_ticketeditor_assign=1";
        $objects["TicketEditor"]["Functions"]["Assign"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_ticketeditor_assign=1";
        $objects["TicketEditor"]["Functions"]["Assign"]["Response"] = "JSON code of ticket editor";
        $objects["TicketEditor"]["Functions"]["Assign"]["Fields"] = array("true");
        $objects["TicketEditor"]["Functions"]["Assign"]["Filters"] = array();
        $objects["TicketEditor"]["Functions"]["Assign"]["OutputObject"] = array("TicketEditor"=>$_ticketEditorA);
        $objects["TicketEditor"]["Functions"]["Assign"]["InputObject"] = $objects["TicketEditor"]["Fields"];

        $objects["KnowledgeBaseEntry"]["Fields"] = ApiV2::GetObjectFields("KnowledgeBaseEntry");
        $_kbEntryOut = ApiV2::ClearObject($objects["KnowledgeBaseEntry"]["Fields"],$_kbEntry,true);

        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Version"] = "6.0.0.0";
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Title"] = "List Knowledgebase Entries";
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Call"] = "POST /api/v2/api.php";
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Param"] = "POST /api/v2/api.php p_knowledgebase_entries_list=1";
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_knowledgebase_entries_list=1";
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Response"] = "JSON code of List of Knowledgebase entries";
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Fields"] = array("true");
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["Filters"] = array("Show Private"=>array("Input"=>true,"Output"=>false,"Type"=>"bool","Comment"=>"Private (non-public) entries will be returned.","Required"=>false,"Example"=>"1"),"Id"=>array("Input"=>true,"Output"=>false,"Type"=>"string","Comment"=>"Will return the entry matching the given Id.","Required"=>false,"Example"=>"14t733d03f64db3b72af327d0d835ebd"),"Limit"=>array("Type"=>"int","Required"=>false,"Comment"=>"Maximum number of tickets to return.","Example"=>"100"),"Offset"=>array("Type"=>"int","Required"=>false,"Comment"=>"Index where to start returning records (requires Limit parameter).","Example"=>"101"),"Parent Id"=>array("Type"=>"string","Required"=>false,"Comment"=>"All child nodes of parent will be returned.","Example"=>"14t733d03f64db3b72af327d0d835ebd"));
        $objects["KnowledgeBaseEntry"]["Functions"]["List"]["OutputObject"] = array("KnowledgeBaseEntries"=>array(array("KnowledgeBaseEntry"=>$_kbEntryOut)));

        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Version"] = "6.0.0.0";
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Title"] = "Create Knowledgebase Entry";
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Call"] = "POST /api/v2/api.php";
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Param"] = "POST /api/v2/api.php p_knowledgebase_entry_create=1";
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_knowledgebase_entry_create=1";
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Response"] = "JSON code of KB entry";
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Fields"] = array("true");
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["Filters"] = array();
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["OutputObject"] = array("KnowledgeBaseEntry"=>$_kbEntryOut);
        $objects["KnowledgeBaseEntry"]["Functions"]["Create"]["InputObject"] = $objects["KnowledgeBaseEntry"]["Fields"];

        $objects["Cronjob"]["Functions"]["Execute"]["Version"] = "7.0.0.3";
        $objects["Cronjob"]["Functions"]["Execute"]["Title"] = "Execute Cronjob";
        $objects["Cronjob"]["Functions"]["Execute"]["Call"] = "POST /api/v2/api.php";
        $objects["Cronjob"]["Functions"]["Execute"]["Param"] = "POST /api/v2/api.php p_cronjob_execute=1";
        $objects["Cronjob"]["Functions"]["Execute"]["CURL"] = "curl {yourdomain}{livezilla_folder}/api/v2/api.php<br>-d {authenthication}<br>-d p_cronjob_execute=1<br>-d p_cronjob_maintain=1<br>-d p_send_chat_transcripts=1<br>-d p_receive_emails=1<br>-d p_social_media=1";
        $objects["Cronjob"]["Functions"]["Execute"]["Response"] = "Result (SUCCESS/FAIL)";
        $objects["Cronjob"]["Functions"]["Execute"]["Fields"] = array("true");
        $objects["Cronjob"]["Functions"]["Execute"]["Filters"] = array(
            "Send Chat Transcripts"=>array("Input"=>true,"Output"=>false,"Type"=>"bool","Comment"=>"Closed chats will be archived and chat transcripts will be sent. Call every 10-20 hours.","Required"=>true,"Example"=>"1"),
            "Receive Emails"=>array("Input"=>true,"Output"=>false,"Type"=>"bool","Comment"=>"Emails will be downloaded from mailboxes. Call every 2-5 minutes.","Required"=>true,"Example"=>"1"),
            "Maintenance"=>array("Type"=>"bool","Required"=>true,"Comment"=>"Pruning and optimizing databases. Call every 10-120 seconds.","Example"=>"1"),
            "Social Media"=>array("Type"=>"bool","Required"=>true,"Comment"=>"Download messages from Social Media. Call every 10-120 seconds.","Example"=>"1"));

        $objects["Cronjob"]["Functions"]["Execute"]["OutputObject"] = "SUCCESS";
        $objects["Cronjob"]["Functions"]["Execute"]["InputObject"] = array();

        return $objects;
    }

    static function GetObjectFields($_objName)
    {
        if($_objName=="Operator")
            return array(
                "UserId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"The alphanumeric login ID of the operators.","Required"=>true,"Example"=>"john_doe"),
                "Firstname"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Operators First Name.","Required"=>true,"Example"=>"John"),
                "Lastname"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Operators Last Name.","Required"=>true,"Example"=>"Doe"),
                "Email"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Operators email.","Required"=>true,"Example"=>"john@doe.com"),
                "Language"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"ISO two letter language code.","Required"=>true,"Example"=>"EN"),
                "Webspace"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Webspace in MB operator is allowed to use for file uploads (0=deactivated).","Required"=>true,"Example"=>100),
                "Password"=>array("Input"=>true,"Output"=>false,"Type"=>"string","Description"=>"Operators password (MD5 encoded).","Required"=>true,"Example"=>"md5('johns_password')","Code"=>md5('johns_password')),
                "Groups"=>array("Input"=>true,"Output"=>true,"Type"=>"array","Description"=>"List of group IDs representing the groups the operators is a member of.","Required"=>true,"Example"=>"groupid1,groupid2","Code"=>array('groupid1','groupid2')),
                "PermissionSet"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"String representing the operators permission set.<br><br>[0] = TicketPermission<br>[1] = RatingPermission<br>[2] = ChatExternalArchivePermission<br>[3] = ResourcePermission<br>[4] = EventPermission<br>[5] = ReportPermission<br>[6] = MonitoringPermission<br>[7] = JoinChats<br>[8] = JoinChatsInvisibly<br>[9] = TakeChats<br>[10] = SetChatPriority<br>[11] = SetChatGroup<br>[12] = SetChatOperator<br>[13] = ChatPermission<br>[14] = SendInvitations<br>[15] = ClearText<br>[16] = ForwardChats<br>[17] = JoinChatsWhenInvited<br>[18] = DynamicGroupPermission<br>[19] = CreateFilters<br>[20] = BotEditPermission<br>[21] = ChangePicture<br>[22] = TicketReviewEmails<br>[23] = TicketCreate<br>[24] = TicketChangeSignature<br>[25] = TicketDeleteEmails<br>[26] = TicketChangeStatus<br>[27] = TicketChangeStatusOpen<br>[28] = TicketChangeStatusInProgress<br>[29] = TicketChangeStatusClosed<br>[30] = TicketAssignOperator<br>[31] = TicketAssignGroup<br>[32] = TicketOvertake<br>[33] = TicketProcessOpen<br>[34] = TicketDeleteGlobal<br>[35] = ProfilePermission<br>[36] = ChatInternalArchivePermission<br>[37] = TicketChangeStatusDeleted<br>[38] = StartChats<br>[39] = CancelInvitations<br>[40] = CancelInvitationsOfOthers<br>[41] = TicketEdit<br>[42] = CanAutoAcceptChats<br>[43] = MustAutoAcceptChats<br>[44] = CanRejectChats<br>[45] = MobileAccess<br>[46] = API Access<br>[47] = KB Entries Read<br>[48] = KB Entries Write<br>[49] = Personal Chat Link<br>[50] = Delete Feedbacks<br>[51] = View Logs<br>[52] = Delete chats archive","Required"=>true,"Example"=>"21212021010002101111011111111111110111110110110331010","MultilineDescription"=>true),
                "Description"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Description Text","Required"=>false,"Example"=>"Nice guy"),
                "Level"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Defines if operator is server administrator.","Required"=>false,"Example"=>"1"),
                "Status"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"Operator's current online status (0=Online,1=Busy,2=Offline,3=Away).","Required"=>false,"Example"=>"0"),
                "PictureFile"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Operator's Image File, add to full URL.","Required"=>false,"Example"=>"picture.php?operator..."),
                "ChatFile"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"File to initiate chat with Operator, add to full URL.","Required"=>false,"Example"=>"chat.php?operator..."),
				"IsBot"=>array("Input"=>false,"Output"=>true,"Type"=>"boolean","Description"=>"Is bot (or human).","Required"=>false,"Example"=>"1"),
                "ExternalChats"=>array("Input"=>false,"Output"=>true,"Type"=>"array","Description"=>"List of active (external) chat objects. (LiveZilla 5.4.0.1)","Required"=>false,"Example"=>"chat1,chat2"),
                "ExternalChatCount"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"Count of active (external) chats. (LiveZilla 5.4.0.1)","Required"=>false,"Example"=>"1")
			);
        if($_objName=="Chat")
            return array(
                "ChatId"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"The chat ID","Required"=>true,"Example"=>"11123"),
                "TimeStart"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"UNIX Timestamp of chat's start time","Required"=>false,"Example"=>"1395332157"),
                "TimeEnd"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"UNIX Timestamp of chat's end time","Required"=>false,"Example"=>"1395332206"),
                "Language"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's langauge (ISO two letter)","Required"=>false,"Example"=>"EN"),
                "OperatorId"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Operator's User Id","Required"=>false,"Example"=>"john_doe"),
                "VisitorId"=>array("Input"=>false,"Output"=>false,"Type"=>"string","Description"=>"Visitor's Id","Required"=>false,"Example"=>"bd1e10d650"),
                "Group"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Group Id","Required"=>false,"Example"=>"groupid1"),
                "HTML"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"HTML Chat Transcript","Required"=>false,"Example"=>htmlentities("<table width=\"97%\" border=\"0\"....")),
                "PlainText"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Plain Text Chat Transcript","Required"=>false,"Example"=>"| 20.03.2014 17:15:59 | Stefa..."),
                "Fullname"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's name","Required"=>false,"Example"=>"Johanna Doe"),
                "Email"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's email","Required"=>false,"Example"=>"johanna@jdscompany.com"),
                "Company"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's company","Required"=>false,"Example"=>"Jdscompany Ltd."),
                "Question"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's question","Required"=>false,"Example"=>"Can you help me?"),
                "Country"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's country","Required"=>false,"Example"=>"US"),
                "Phone"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's phone #","Required"=>false,"Example"=>"004988373728"),
                "Host"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's host","Required"=>false,"Example"=>"19453972n@serviceprovider.domain"),
                "IP"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Visitor's IP","Required"=>false,"Example"=>"192.168.1.222"),
                "Customs"=>array("Input"=>false,"Output"=>true,"Type"=>"array<int,array<str, str>>","Description"=>"Custom input field values<br><br>array&lt;index,array&lt;input_name, input_value&gt;&gt;","Required"=>false,"Example"=>"","MultilineDescription"=>true)
            );
        if($_objName=="Ticket")
            return array(
                "Id"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"The serial ticket ID","Required"=>false,"Example"=>"11123"),
                "Group"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Group Id","Required"=>true,"Example"=>"groupid1"),
                "Channel"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Channel<br><br>[0] = Web<br>[1] = Email<br>[2] = Phone<br>[3] = Misc<br>[4] = Chat<br>[5] = Rating","Required"=>false,"Example"=>"0","MultilineDescription"=>true),
                "SubChannel"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Sub-Channel Name","Required"=>false,"Example"=>"Sub Channel Name","MultilineDescription"=>false),
                "Language"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Ticket's Langauge (ISO two letter)","Required"=>false,"Example"=>"EN"),
                "LastUpdated"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"Last updated time (UNIX Timestamp)","Required"=>false,"Example"=>"1395332206"),
                "WaitBegin"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"Wait begin time (UNIX Timestamp)","Required"=>false,"Example"=>"1395332157"),
                "Messages"=>array("Input"=>false,"Output"=>true,"Type"=>"array<TicketMessage>","Description"=>"Ticket Messages","TypeLinkObject"=>"TicketMessage","Required"=>false,"Example"=>""),
                "Editor"=>array("Input"=>false,"Output"=>true,"Type"=>"TicketEditor","TypeLinkObject"=>"TicketEditor","Description"=>"Ticket Editor (Operator)","Required"=>false,"Example"=>"")
               );
        if($_objName=="TicketMessage")
            return array(
                "Id"=>array("Input"=>false,"Output"=>true,"Type"=>"string","Description"=>"Message ID<br><br>First message's ID must be equal to ticket ID.","Required"=>false,"Example"=>"<br>First Message: 11701<br>Second Message: 246733d03f64db3b72af327d0d835ebd","MultilineDescription"=>true),
                "TicketId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Ticket ID","Required"=>true,"Example"=>"11701"),
                "Fullname"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Visitor's name","Required"=>false,"Example"=>"Johanna Doe"),
                "Email"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Visitor's email","Required"=>false,"Example"=>"johanna@jdscompany.com"),
                "Company"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Visitor's company","Required"=>false,"Example"=>"Jdscompany Ltd."),
                "Phone"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Visitor's phone #","Required"=>false,"Example"=>"004988373728"),
                "IP"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Visitor's IP","Required"=>false,"Example"=>"192.168.1.222"),
                "CallMeBack"=>array("Input"=>false,"Output"=>true,"Type"=>"boolean","Description"=>"Callback Required","Required"=>false,"Example"=>"true"),
                "Type"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Message Type<br><br>[0] = (Incoming) Web<br>[1] = (Outgoing) Email<br>[2] = Chat<br>[3] = (Incoming) Email","Required"=>false,"Example"=>"0","MultilineDescription"=>true),
                "Subject"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Subject / URL","Required"=>false,"Example"=>"Can you help?"),
                "SenderId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Operator or Visitor Id","Required"=>false,"Example"=>"john_doe"),
                "ChannelId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"","Required"=>false,"Example"=>""),
                "Comments"=>array("Input"=>true,"Output"=>true,"Type"=>"array<int,array<str, str>>","Description"=>"Ticket Comments<br><br>array&lt;index,array&lt;operator_id, comment_text&gt;&gt;","TypeLinkObject"=>"","Required"=>false,"Example"=>"","MultilineDescription"=>true),
                "Attachments"=>array("Input"=>false,"Output"=>true,"Type"=>"array<Attachment>","Description"=>"File attachments","Required"=>false,"Example"=>""),
                "Edited"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"Last edited time (UNIX Timestamp)","Required"=>false,"Example"=>"1395332206"),
                "Created"=>array("Input"=>false,"Output"=>true,"Type"=>"int","Description"=>"Created (UNIX Timestamp)","Required"=>false,"Example"=>"1395332206"),
                "Customs"=>array("Input"=>true,"Output"=>true,"Type"=>"array<int,array<str, str>>","Description"=>"Custom input field values<br><br>array&lt;index,array&lt;input_name, input_value&gt;&gt;","Required"=>false,"Example"=>"","MultilineDescription"=>true),
                "Text"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Message Plaintext","Required"=>false,"Example"=>"Hello, please help me.")
            );
        if($_objName=="TicketEditor")
            return array(
                "Editor"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Editor (=Operator) ID","Required"=>true,"Example"=>"john_doe"),
                "Status"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Ticket Status<br><br>[0] = Open<br>[1] = In Progress<br>[2] = Closed<br>[3] = Deleted<br>[4] = Pending","Required"=>false,"Example"=>"0","MultilineDescription"=>true),
                "SubStatus"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Ticket Sub-Status","Required"=>false,"Example"=>"Sub-Status Name","MultilineDescription"=>false),
                "Id"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Ticket ID","Required"=>true,"Example"=>"11123")
            );
        if($_objName=="KnowledgeBaseEntry")
            return array(
                "Id"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Unique ID (32 chars)","Required"=>false,"Example"=>"14t733d03f64db3b72af327d0d835ebd"),
                "Tags"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Tags (comma separated)","Required"=>false,"Example"=>"car,sharing,stations"),
                "Value"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Content (HTML, URL or Filename)","Required"=>true,"Example"=>"..."),
                "Title"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Title","Required"=>true,"Example"=>"Where to find car sharing stations?"),
                "Languages"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Languages (comma-separated iso 2 letter code)","Required"=>false,"Example"=>"de,fr"),
                "Type"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Resource Type (0=Folder, 1=HTML/Text, 2=Link, 3=Operator File, 4=Customer File)","Required"=>true,"Example"=>"0"),
                "ParentId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Parent's Id (root = 1)","Required"=>true,"Example"=>"ae33b353c9174c3f97628067f8104405"),
                "IsPublic"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Defines if entry can be found in Knowledgebase","Required"=>false,"Example"=>"1"),
                "FulltextSearch"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Defines if fulltext (= content) search will be used","Required"=>false,"Example"=>"1"),
                "ShortcutWord"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Shortword for operator client","Required"=>false,"Example"=>"myshortcut"),
                "GroupId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Group ID (owner) (LiveZilla 6.1.1.0)","Required"=>false,"Example"=>"support"),
                "OwnerId"=>array("Input"=>true,"Output"=>true,"Type"=>"string","Description"=>"Operator ID (owner) (LiveZilla 6.1.1.0)","Required"=>false,"Example"=>"d351dc9"),
                "AllowBotAccess"=>array("Input"=>true,"Output"=>true,"Type"=>"int","Description"=>"Defines if entry will be used by Virtual Assistance","Required"=>true,"Example"=>"1")
            );
        return array();
    }
}

?>