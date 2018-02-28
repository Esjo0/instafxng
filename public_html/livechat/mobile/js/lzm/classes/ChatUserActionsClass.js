/****************************************************************************************
 * LiveZilla ChatUserActionsClass.js
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function ChatUserActionsClass(lzm_commonTools, lzm_chatPollServer, lzm_chatDisplay, lzm_chatServerEvaluation, lzm_commonTranslation, lzm_commonStorage, lzm_chatInputEditor, chosenProfile) {

    this.forwardData = {};
    this.lzm_commonTools = lzm_commonTools;
    this.lzm_chatPollServer = lzm_chatPollServer;
    this.lzm_chatDisplay = lzm_chatDisplay;
    this.lzm_chatServerEvaluation = lzm_chatServerEvaluation;
    //this.lzm_commonTranslation = lzm_commonTranslation;
    this.lzm_commonStorage = lzm_commonStorage;
    this.chosenProfile = chosenProfile;
    this.lzm_chatInputEditor = lzm_chatInputEditor;
    this.userLanguage = '';
    this.gTranslateLanguage = '';
    this.acceptedChatCounter = 0;
    this.messageFromKnowledgebase = false;
}


/**************************************** General functions ****************************************/
ChatUserActionsClass.prototype.resetWebApp = function() {

};

ChatUserActionsClass.prototype.sendChatMessage = function (new_chat, translated_chat, _chatFullId) {

    var chatText = new_chat.text;
    this.lzm_chatPollServer.stopPolling();
    var pPostsVObject = {
        a: chatText,
        b:new_chat.reco,
        c:new_chat.id,
        d: '',
        e: ''
    };

    if(d(new_chat.cid))
        pPostsVObject.f = new_chat.cid;

    if(translated_chat != null && _chatFullId != null)
    {
        if (translated_chat != '' && lzm_chatDisplay.chatTranslations[_chatFullId].tmm.targetLanguage != '')
        {
            pPostsVObject.d = translated_chat;
            pPostsVObject.e = lzm_chatDisplay.chatTranslations[_chatFullId].tmm.targetLanguage.toUpperCase();
        }
    }

    this.messageFromKnowledgebase = false;

    if(this.lzm_chatPollServer.addToOutboundQueue('p_posts_v', pPostsVObject))
        this.lzm_chatPollServer.InstantPoll();
};

ChatUserActionsClass.prototype.getTranslationLanguages = function(target) {
    var that = this;
    if (DataEngine.otrs != '' && DataEngine.otrs != null && DataEngine.otrs.length > 5)
    {
        var gUrl = 'https://www.googleapis.com/language/translate/v2/languages';
        try
        {
            target = (typeof target != 'undefined') ? target : DataEngine.operators.getOperator(lzm_chatDisplay.myId).lang.toLowerCase();
        }
        catch(ex)
        {
            target = 'en';
        }
        var dataObject = {key: DataEngine.otrs, target: target};
        $.ajax({
            type: "GET",
            url: gUrl,
            data: dataObject,
            success: function (data) {
                that.gTranslateLanguage = target;
                lzm_chatDisplay.translationLanguages = lzm_commonTools.clone(data.data.languages);
                lzm_chatDisplay.translationLangCodes = [];
                for (var i=0; i<data.data.languages.length; i++){
                    lzm_chatDisplay.translationLangCodes.push(data.data.languages[i].language);
                }
                lzm_chatDisplay.translationServiceError = null;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (target.indexOf('-') != -1) {
                    target = target.split('-')[0];
                    that.getTranslationLanguages(target);
                } else if (target != 'en') {
                    that.getTranslationLanguages('en');
                } else {
                    lzm_chatDisplay.translationServiceError = 'Google API Failure'
                }
            },
            dataType: 'json'
        });
    }
};

ChatUserActionsClass.prototype.saveTranslationSettings = function(visitorChat, tmm, tvm) {
    var visitor = visitorChat.split('~');
    var myObject = {visitorId: visitor[0], browserId: visitor[1], chatId: visitor[2], sourceLanguage: '', targetLanguage: ''};
    if (typeof lzm_chatDisplay.chatTranslations[visitorChat] == 'undefined')
        lzm_chatDisplay.chatTranslations[visitorChat] = {tmm: null, tvm: null};

    var translate = tmm.translate && (tmm.sourceLanguage != tmm.targetLanguage);
    lzm_chatDisplay.chatTranslations[visitorChat].tmm = {translate: translate, sourceLanguage: tmm.sourceLanguage,targetLanguage: tmm.targetLanguage};
    translate = tvm.translate && (tvm.sourceLanguage != tvm.targetLanguage);

    lzm_chatDisplay.chatTranslations[visitorChat].tvm = {translate: translate, sourceLanguage: tvm.sourceLanguage,targetLanguage: tvm.targetLanguage};
    if (tvm.translate)
    {
        myObject.sourceLanguage = tvm.sourceLanguage;
        myObject.targetLanguage = tvm.targetLanguage;
    }
    CommunicationEngine.pollServerSpecial(myObject, 'set-translation');
    lzm_chatDisplay.UpdateTranslateButtonUI(visitorChat);
};

ChatUserActionsClass.prototype.TranslateTextAndSend = function(_chatFullId, chatMessage, chatReco) {
    var gUrl = 'https://www.googleapis.com/language/translate/v2';
    var dataObject = {key: DataEngine.otrs, source: lzm_chatDisplay.chatTranslations[_chatFullId].tmm.sourceLanguage,
        target: lzm_chatDisplay.chatTranslations[_chatFullId].tmm.targetLanguage, q: chatMessage};
    $.ajax({
        type: "GET",
        url: gUrl,
        data: dataObject,
        success: function (data) {
            var translatedChatMessage = data.data.translations[0].translatedText;
            SendChat(chatMessage, chatReco, translatedChatMessage);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            SendChat(chatMessage, chatReco, '');
        },
        dataType: 'json'
    });
};

ChatUserActionsClass.prototype.removeForwardFromList = function (id, b_id) {
    var tmp_external_forwards = [];
    //var tmp_extForwardIdList = [];
    var removeExternalForwardId = [];
    for (var extFwdIndex = 0; extFwdIndex < this.lzm_chatServerEvaluation.external_forwards.length; extFwdIndex++) {
        if (this.lzm_chatServerEvaluation.external_forwards[extFwdIndex].u != id + '~' + b_id) {
            tmp_external_forwards.push(this.lzm_chatServerEvaluation.external_forwards[extFwdIndex]);
        } else {
            removeExternalForwardId.push(this.lzm_chatServerEvaluation.external_forwards[extFwdIndex].id);
        }
    }
    /*for (var extFwdIdIndex = 0; extFwdIdIndex < this.DataEngine.extForwardIdList.length; extFwdIdIndex++) {
        if ($.inArray(this.DataEngine.extForwardIdList[extFwdIdIndex], removeExternalForwardId) == -1) {
            tmp_extForwardIdList.push(this.DataEngine.extForwardIdList[extFwdIdIndex]);
        }
    }*/
    this.lzm_chatServerEvaluation.external_forwards = tmp_external_forwards;
    //this.DataEngine.extForwardIdList = tmp_extForwardIdList;
};

ChatUserActionsClass.prototype.saveUserSettings = function (settings) {

    // DONT ADD HERE

    this.chosenProfile.user_away_after = settings.awayAfterTime;
    this.lzm_chatDisplay.awayAfterTime = settings.awayAfterTime;

    this.chosenProfile.background_mode = settings.backgroundMode;
    this.lzm_chatDisplay.backgroundModeChecked = settings.backgroundMode;
    this.chosenProfile.save_connections = settings.saveConnections;
    this.lzm_chatDisplay.saveConnections = settings.saveConnections;
    this.chosenProfile.tickets_read = settings.ticketsRead;
    this.lzm_chatDisplay.ticketReadStatusChecked = settings.ticketsRead;


    this.chosenProfile.vibrate_notifications = settings.vibrateNotifications;
    lzm_chatDisplay.vibrateNotifications = settings.vibrateNotifications;
    this.chosenProfile.show_view_select_panel = JSON.stringify(settings.showViewSelectPanel);
    lzm_chatDisplay.showViewSelectPanel = settings.showViewSelectPanel;

    // DONT ADD HERE



    this.chosenProfile.alert_new_filter = settings.alertNewFilter;
    lzm_chatDisplay.alertNewFilter = settings.alertNewFilter;

    if(settings.tableColumns != null)
    {
        var tableNames = lzm_chatDisplay.settingsDisplay.tableIds,i;
        for (i=0; i<tableNames.length; i++)
        {
            var tableColumns = {}, j = 0;

            for (j=0; j<settings.tableColumns[tableNames[i]].general.length; j++)
                tableColumns[settings.tableColumns[tableNames[i]].general[j].cid.toString()] = settings.tableColumns[tableNames[i]].general[j].display;

            lzm_commonStorage.saveValue(tableNames[i] + '_column_tbl_' + DataEngine.myId, JSON.stringify(tableColumns));
            LocalConfiguration.CreateTableArray(tableNames[i], 'general', tableColumns);
        }
    }

    // DONT ADD HERE

    LocalConfiguration.ShowViewSelectPanel = settings.showViewSelectPanel;
    LocalConfiguration.ViewSelectArray = settings.viewSelectArray;

    lzm_commonStorage.saveValue('save_connections_' + DataEngine.myId, JSON.stringify(settings.saveConnections));
    lzm_commonStorage.saveValue('vibrate_notifications_' + DataEngine.myId, JSON.stringify(settings.vibrateNotifications));
    lzm_commonStorage.saveValue('tickets_read_' + DataEngine.myId, JSON.stringify(settings.ticketsRead));
    lzm_commonStorage.saveValue('alert_new_filter_' + DataEngine.myId, JSON.stringify(settings.alertNewFilter));

    IFManager.IFSetVibrateOnNotifications(settings.vibrateNotifications);


    // DONT ADD HERE

    this.lzm_commonStorage.loadProfileData();
    var tmpProfile = this.lzm_commonTools.clone(this.chosenProfile);
    if (this.chosenProfile.server_url.indexOf(':') != -1) {
        var tmpUrlArray = this.chosenProfile.server_url.split(':');
        var tmpUrl = tmpUrlArray[0];
        tmpUrlArray = tmpUrlArray[1].split('/');
        for (i=1; i< tmpUrlArray.length; i++)
        {
            tmpUrl += '/' + tmpUrlArray[i];
        }
        tmpProfile.server_url = tmpUrl;
    }
    tmpProfile.keepPassword = true;
    var savedIndex = this.lzm_commonStorage.saveProfile(tmpProfile);

    IFManager.IFKeepActiveInBackgroundMode(settings.backgroundMode == 1);

    LocalConfiguration.Save();

    // DONT ADD HERE

};

ChatUserActionsClass.prototype.replaceLinks = function(myText) {
    var links = myText.match(/href="#" onclick="openLink\('.*?'\)"/);
    if (typeof links != 'undefined' && links != null) {
        for (var i=0; i<links.length; i++) {
            var address = links[i].replace(/href="#" onclick="openLink\('/,'').replace(/'\)"/,'');
            var replacement = 'href="' + address + '" target="_blank"';
            myText = myText.replace(links[i],replacement);
        }
    }
    return myText;
};

ChatUserActionsClass.prototype.leaveInternalChat = function() {
    ChatManager.SaveEditorInput(ChatManager.ActiveChat,null);
};

ChatUserActionsClass.prototype.SaveChatGroup = function(action, groupId, groupName, memberId, additionalData) {
    var dynamicGroupObject = {}, pollType = '', memberUserId = '', memberBrowserId = '', memberChatId = '',
        memberIsPersistent = 1, newGroupId, i = 0;
    var group = DataEngine.groups.getGroup(groupId);
    var operator = DataEngine.operators.getOperator(memberId);
    var visitor = VisitorManager.GetVisitor(memberId);

    if (operator != null)
    {
        memberUserId = operator.userid;
    }
    if (visitor != null) {
        memberUserId = visitor.id;
        memberBrowserId = additionalData.browserId;
        memberChatId = additionalData.chatId;
        memberIsPersistent = (additionalData.isPersistent) ? '1' : '0';
        memberId = memberId + '~' + memberBrowserId;
    }
    switch (action) {
        case 'create':
            newGroupId = md5('' + Math.random());
            dynamicGroupObject.myUserId = DataEngine.myUserId;
            dynamicGroupObject.myId = DataEngine.myId;
            dynamicGroupObject.groupId = newGroupId;
            dynamicGroupObject.groupName = groupName;
            pollType = 'dynamic-group-create';
            break;
        case 'delete':
            dynamicGroupObject.myUserId = DataEngine.myUserId;
            dynamicGroupObject.myId = DataEngine.myId;
            dynamicGroupObject.groupId = groupId;
            pollType = 'dynamic-group-delete';
            break;
        case 'create-add':
            newGroupId = md5('' + Math.random());
            dynamicGroupObject.myUserId = DataEngine.myUserId;
            dynamicGroupObject.myId = DataEngine.myId;
            dynamicGroupObject.groupId = newGroupId;
            dynamicGroupObject.groupName = groupName;
            dynamicGroupObject.operatorUserId = memberUserId;
            dynamicGroupObject.operatorId = memberId;
            dynamicGroupObject.isPersistent = memberIsPersistent;
            dynamicGroupObject.browserId = memberBrowserId;
            dynamicGroupObject.chatId = memberChatId;
            pollType = 'dynamic-group-create-add';
            break;
        case 'add':
            dynamicGroupObject.groupId = groupId;
            dynamicGroupObject.operatorUserId = memberUserId;
            dynamicGroupObject.browserId = memberBrowserId;
            dynamicGroupObject.chatId = memberChatId;
            dynamicGroupObject.operatorId = memberId;
            dynamicGroupObject.isPersistent = memberIsPersistent;
            pollType = 'dynamic-group-add';
            break;
        case 'remove':
            dynamicGroupObject.groupId = groupId;
            dynamicGroupObject.operatorUserId = memberUserId;
            dynamicGroupObject.operatorId = memberId;
            pollType = 'dynamic-group-remove';
            break;
    }
    CommunicationEngine.pollServerSpecial(dynamicGroupObject, pollType);
    lzm_chatDisplay.CreateOperatorList();
};

ChatUserActionsClass.prototype.inviteExternalUser = function (id, b_id, text, _groupId) {
    CommunicationEngine.stopPolling();
    CommunicationEngine.addToOutboundQueue('p_requests_va', id, 'nonumber');
    CommunicationEngine.addToOutboundQueue('p_requests_vb', b_id, 'nonumber');
    CommunicationEngine.addToOutboundQueue('p_requests_vc', DataEngine.myName, 'nonumber');
    CommunicationEngine.addToOutboundQueue('p_requests_vd', DataEngine.myUserId, 'nonumber');
    CommunicationEngine.addToOutboundQueue('p_requests_ve', lz_global_base64_encode(text), 'nonumber');
    CommunicationEngine.addToOutboundQueue('p_requests_vf', _groupId, 'nonumber');
    CommunicationEngine.pollServer(CommunicationEngine.fillDataObject(), 'shout');
};

ChatUserActionsClass.prototype.cancelInvitation = function(id) {
    this.lzm_chatPollServer.stopPolling();
    this.lzm_chatPollServer.addToOutboundQueue('p_cncl_inv', id, 'nonumber');
    this.lzm_chatPollServer.InstantPoll();
};

ChatUserActionsClass.prototype.getChatPM = function(_chatObj, visitorId, browserId, pmId, language, groupId) {

    var visitor=null,j,chatGroup = '', visitorName = '', visitorEmail = '', visitorCompany = '', visitorPhone = '';
    var visitorIp = '', visitorQuestion = '', visitorChatId = '', visitorUrl = '', visitorPageTitle = '';
    var visitorSearchString = '';
    var pm = {}, fallbackPm = {}, fallbackPm2 = {}, pm2 = {}, pm3 = {};
    var chatLang = DataEngine.defaultLanguage.toUpperCase();
    var chatLangShort = chatLang.substr(0,2);

    if(_chatObj != null)
    {
        chatGroup = _chatObj.dcg;
        visitorQuestion = lzm_commonTools.htmlEntities(_chatObj.s);
        visitorChatId = _chatObj.i;
    }

    if(_chatObj != null && _chatObj.Visitor != null)
        visitor = _chatObj.Visitor;
    else
        visitor = VisitorManager.GetVisitor(visitorId);

    if(visitor != null)
    {
        visitorName = DataEngine.inputList.getInputValueFromVisitor(111,visitor);
        visitorEmail = DataEngine.inputList.getInputValueFromVisitor(112,visitor);
        visitorCompany = DataEngine.inputList.getInputValueFromVisitor(113,visitor);
        visitorPhone = DataEngine.inputList.getInputValueFromVisitor(116,visitor);

        for (j=0; j<visitor.b.length; j++)
        {
            if (browserId != null && browserId.indexOf('_OVL') == -1 && d(visitor.b[j].h2))
            {
                var hLast = visitor.b[j].h2.length - 1;
                if (typeof visitor.b[j].h2[hLast].url != 'undefined')
                {
                    visitorUrl = visitor.b[j].h2[hLast].url;
                }
                if (typeof visitor.b[j].h2[hLast].title != 'undefined')
                {
                    visitorPageTitle = visitor.b[j].h2[hLast].title;
                }
                if (typeof visitor.b[j].ss != 'undefined')
                {
                    visitorSearchString = visitor.b[j].ss;
                }
                break;
            }
        }

        if (d(visitor.lang) && visitor.lang != '')
        {
            chatLang = visitor.lang;
        }
        if (d(visitor.ip))
        {
            visitorIp = visitor.ip;
        }
    }

    var pmLanguages = this.getPmLanguages(chatGroup);
    var globalDefaultLanguage = pmLanguages['default'][1];
    if (typeof language != 'undefined' && language != '')
    {
        chatLang = language;
        chatLangShort = language.substr(0,2);
    }

    if (d(groupId) || chatGroup!='')
    {
        groupId = (typeof groupId != 'undefined' && groupId != '') ? groupId : chatGroup;
        var group = DataEngine.groups.getGroup(groupId);
        if (group != null)
        {
            for (j=0; j<group.pm.length; j++)
            {
                if (chatLang == group.pm[j].lang)
                {
                    pm = lzm_commonTools.clone(group.pm[j]);
                }
                if (chatLangShort == group.pm[j].lang)
                {
                    pm2 = lzm_commonTools.clone(group.pm[j]);
                }
                if (chatLangShort == group.pm[j].shortlang)
                {
                    pm3 = lzm_commonTools.clone(group.pm[j]);
                }
                if (globalDefaultLanguage == group.pm[j].lang)
                {
                    fallbackPm = lzm_commonTools.clone(group.pm[j]);
                }
                if (globalDefaultLanguage == group.pm[j].shortlang)
                {
                    fallbackPm2 = lzm_commonTools.clone(group.pm[j]);
                }
            }
        }
    }

    pm = (typeof pm[pmId] != 'undefined' && pm[pmId] != '') ? pm : (typeof pm2[pmId] != 'undefined' && pm2[pmId] != '') ? pm2 : pm3;
    pm = (typeof pm[pmId] != 'undefined' && pm[pmId] != '') ? pm : fallbackPm;

    if (typeof pm[pmId] != 'undefined')
    {
        pm[pmId] = CommonToolsClass.prototype.ReplaceCommonPlaceholders(visitorId+'~'+browserId,pm[pmId]);
    }
    else
    {
        pm[pmId] = '';
    }
    return pm;
};

ChatUserActionsClass.prototype.getPmLanguages = function(groupId) {
    var pmLanguages = {group: [], user:[], all: [], default: []};
    var i, j;
    var group = (groupId != '') ? DataEngine.groups.getGroup(groupId) : DataEngine.groups.getGroupList()[0];
    if (group != null)
    {
        for (j=0; j<group.pm.length; j++)
        {
            pmLanguages.group.push(group.pm[j].lang);
            pmLanguages.all.push(group.pm[j].lang);
            if (group.pm[j].def == '1')
            {
                pmLanguages.default = ['group', group.pm[j].lang];
            }
        }
    }
    return pmLanguages;
};

ChatUserActionsClass.prototype.ShowVisitorChat = function (_chat, _isOpened) {

    var thisClass = this;
    var systemId = _chat.SystemId;

    if(!_isOpened)
    {
        if(ChatEditorClass.ActiveEditor != null)
            ChatManager.SaveEditorInput();

        ChatManager.SetActiveChat(systemId);
        _chat.OpenChatWindow(true);
    }

    if (_chat.GetStatus() == Chat.Active && _chat.IsMember(DataEngine.myId))
    {
        thisClass.lzm_chatDisplay.RenderChatVisitorActivated();

        if(!_isOpened || !ChatEditorClass.IsActiveEditor)
        {
            var loadedValue = ChatManager.LoadEditorInput();
            initEditor(loadedValue, 'viewUserData');

            $('#chat-action').css('display', 'block');
            $('#chat-progress').css('display', 'block');
            thisClass.lzm_chatDisplay.removeSoundPlayed(_chat.SystemId);
        }
    }
    else
    {
        removeEditor();
        thisClass.lzm_chatDisplay.RenderChatVisitorOpen();
        $('#accept-chat').click(function() {
            thisClass.AcceptChat(_chat,true);
            thisClass.ShowVisitorChat(_chat, _isOpened);
        });
        $('#decline-chat').click(function() {
            thisClass.declineChat(_chat.v, _chat.b, _chat.i);
        });
        $('#forward-chat').click(function() {

            forwardChat(_chat.i,'forward');
            if (lzm_commonPermissions.checkUserPermissions('', 'chats', 'forward', {}))
            {
                if(thisClass.lzm_chatDisplay.ChatForwardInvite != null)
                    thisClass.lzm_chatDisplay.ChatForwardInvite.createOperatorForwardInviteHtml('forward', CommunicationEngine.thisUser, _chat.v, _chat.b, _chat.i);
            }
            else
                showNoPermissionMessage();


        });
    }
};

ChatUserActionsClass.prototype.declineChat = function(id, b_id, chat_id){
    if (lzm_commonPermissions.checkUserPermissions('', 'chats', 'decline', {}))
    {
        this.lzm_chatPollServer.stopPolling();
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_va', id, 'nonumber');
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vb', b_id, 'nonumber');
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vc', chat_id, 'nonumber');
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vd', 'DeclineChat', 'nonumber');
        this.lzm_chatPollServer.InstantPoll();
        this.lzm_chatDisplay.removeSoundPlayed(id + '~' + b_id);
    }
    else
    {
        showNoPermissionMessage();
    }
};

ChatUserActionsClass.prototype.AcceptChat = function(_chatObj, _showSalutation) {
    if (_chatObj != null)
    {
        _chatObj.IsUnread = false;

        CommunicationEngine.pollServerSpecial(_chatObj, 'accept-chat');

        _chatObj.AcceptInitiated = true;

        setTimeout(function(){
            $('#accept-chat').addClass('ui-disabled');
        },200);
        setTimeout(function(){

            if(_chatObj != null)
            {
                _chatObj.AcceptInitiated = false;
                if(ChatManager.ActiveChat == _chatObj.SystemId && _chatObj.GetStatus() != Chat.Closed)
                    $('#accept-chat').removeClass('ui-disabled');
            }
        },10000);

        var pm = null, pmId = 'wel';
        try
        {
            pmId = 'wel';
        }
        catch(ex)
        {
            deblog(ex);
        }

        try
        {
            pm = this.getChatPM(_chatObj, _chatObj.v, _chatObj.b, pmId, _chatObj.Visitor.lang);
        }
        catch(ex)
        {
            deblog(ex);
        }

        if (pm != null && typeof pm.aw != 'undefined' && pm.aw == 1 && _showSalutation)
        {
            var pmMessage = pm[pmId];
            if (typeof pm.edit != 'undefined' && pm.edit == 0)
            {
                _chatObj.AutoAcceptMessage = pmMessage;
            }
            else
            {
                ChatManager.SaveEditorInput(_chatObj.v + '~' + _chatObj.b,pmMessage);
            }
        }
    }
};

ChatUserActionsClass.prototype.leaveExternalChat = function (id, b_id, chat_id, chat_no, closeOrLeave) {

    ChatManager.SaveEditorInput(id + '~' + b_id,null);

    this.removeForwardFromList(id, b_id);
    var chatObj = DataEngine.ChatManager.GetChat(chat_id,'i');
    if (chatObj.GetMember(DataEngine.myId)!=null)
    {
        this.lzm_chatPollServer.stopPolling();
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_va', id, 'nonumber');
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vb', b_id, 'nonumber');
        this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vc', chat_id, 'nonumber');
        if (closeOrLeave == 'close')
            this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vd', 'CloseChat', 'nonumber');
        else
            this.lzm_chatPollServer.addToOutboundQueue('p_ca_0_vd', 'LeaveChat', 'nonumber');
        this.lzm_chatPollServer.InstantPoll();
    }
    clearEditorContents();
};

ChatUserActionsClass.prototype.forwardChat = function (_chatObj, _type) {

    var new_chat;
    if (typeof this.forwardData.id != 'undefined')
    {
        ChatManager.SaveEditorInput(ChatManager.ActiveChat,null);

        if (_type == 'forward')
        {
            CommunicationEngine.pollServerSpecial({v: _chatObj.v, b: _chatObj.b, c: _chatObj.i, g: this.forwardData.forward_group, takeover: true,o: this.forwardData.forward_id, a:0}, 'take-chat');
            var extUserName = _chatObj.GetName();
            new_chat = {};
            new_chat.id = md5(String(Math.random())).substr(0, 32);
            new_chat.rp = '';
            new_chat.sen = '0000000';
            new_chat.rec = '';
            new_chat.reco = ChatManager.ActiveChat;
            var tmpdate = lzm_chatTimeStamp.getLocalTimeObject();
            new_chat.date = lzm_chatTimeStamp.getServerTimeString(tmpdate, true);
            new_chat.date_human = lzm_commonTools.getHumanDate(tmpdate, 'date', this.userLanguage);
            new_chat.time_human = lzm_commonTools.getHumanDate(tmpdate, 'time', this.userLanguage);
            new_chat.text = tid('fwd3',[['<!--visitor_name-->','<b>'+extUserName+'</b>'],['<!--op_name-->','<b>'+this.forwardData.forward_name+'</b>']]);
            _chatObj.AddMessage(new_chat);

            if(this.forwardData.forward_text.length)
            {
                new_chat = {};
                new_chat.id = md5(String(Math.random())).substr(0, 32);
                new_chat.rp = '';
                new_chat.sen = DataEngine.myId;
                new_chat.rec = '';
                new_chat.cid = _chatObj.i;
                new_chat.reco = this.forwardData.forward_id;
                new_chat.text = this.forwardData.forward_text + '[__[forward_info:'+_chatObj.i+']__]';
                this.sendChatMessage(new_chat,null,null);
            }
        }
        else if(_type == 'invite')
        {
            new_chat = {};
            new_chat.id = md5(String(Math.random())).substr(0, 32);
            new_chat.rp = '';
            new_chat.sen = DataEngine.myId;
            new_chat.rec = '';
            new_chat.reco = this.forwardData.forward_id;
            new_chat.text = this.forwardData.forward_text + '[__[invite_info:'+_chatObj.i+']__]';
            this.sendChatMessage(new_chat,null,null);
        }
    }
};

ChatUserActionsClass.prototype.selectOperatorForForwarding = function (_chatObj, forward_id, forward_name, forward_group, forward_text, chat_no) {
    this.forwardData = {id:_chatObj.v,
        b_id:_chatObj.b,
        chat_id:_chatObj.i,
        forward_id:forward_id,
        forward_name:forward_name,
        forward_group:forward_group,
        forward_text:forward_text,
        chat_no:chat_no};
};

ChatUserActionsClass.prototype.saveVisitorComment = function(visitorId, commentText) {
    commentText = commentText.replace(/\r\n/g, '\n').replace(/\r/g, '\n').replace(/\n/g, '\r\n');
    CommunicationEngine.pollServerSpecial({id: visitorId, t: commentText}, 'visitor-comment');
};

ChatUserActionsClass.prototype.deleteVisitorComment = function(visitorId, commentText) {
    commentText = commentText.replace(/\r\n/g, '\n').replace(/\r/g, '\n').replace(/\n/g, '\r\n');
    CommunicationEngine.pollServerSpecial({id: visitorId, t: commentText}, 'visitor-comment-remove');
};

ChatUserActionsClass.prototype.editQrd = function(myResource, ticketId, inDialog) {

    alert("DEPRECATED 31");
    /*
    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;
    var thisClass = this;
    var resource = {};
    if (typeof myResource != 'undefined' && myResource != false)
        resource = myResource
    else
        resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);

    if (resource != null)
    {
        var newRid = resource.rid;
        var newPid = resource.pid;
        var newRank = resource.ra;
        newType = resource.ty;
        var newTitle, newType, newText, newSize, newTags;
        thisClass.lzm_chatDisplay.resourcesDisplay.editQrd(resource, ticketId, inDialog);

        var editResource = $('.qrd-edit-resource');
        var editHtmlResource = $('.qrd-edit-html-resource');
        var editLinkResource = $('.qrd-edit-link-resource');

        switch(parseInt(newType)) {

            case 0:
                editResource.css('display', 'none');
                editHtmlResource.css('display', 'block');
                KnowledgebaseUI.TextEditor = new ChatEditorClass('qrd-edit-text',resource.rid);
                KnowledgebaseUI.TextEditor.init(resource.text, 'editQrd');
                UIRenderer.resizeEditResources();
                setTimeout(function(){
                    $('#qrd-edit-title').select();
                    $('#qrd-edit-title').focus();
                },25);
                $("#qrd-edit-text-div label[for='qrd-edit-text']").html(tidc('description'));
                break;
            case 1: // HTML resource
                editResource.css('display', 'none');
                editHtmlResource.css('display', 'block');
                KnowledgebaseUI.TextEditor = new ChatEditorClass('qrd-edit-text',resource.rid);
                KnowledgebaseUI.TextEditor.init(resource.text, 'editQrd');
                UIRenderer.resizeEditResources();
                setTimeout(function(){
                    $('#qrd-edit-title').select();
                    $('#qrd-edit-title').focus();
                },25);
                break;
            case 2: // URL
                editResource.css('display', 'none');
                editLinkResource.css('display', 'block');
                break;
        }
        UIRenderer.resizeEditResources();
    }
    $('#save-edited-qrd').click(function() {
        var editTitle = $('#qrd-edit-title').val();
        var editTags = $('#edit-resource').data('tags');
        newTitle = editTitle;

        switch (parseInt(newType)) {
            case 0:
                newText = KnowledgebaseUI.TextEditor.grabHtml();
                KnowledgebaseUI.TextEditor.blur();
                newSize = newText.length + newTitle.length;
                newTags = '';
                break;
            case 1:
                newText = KnowledgebaseUI.TextEditor.grabHtml();
                KnowledgebaseUI.TextEditor.blur();
                newSize = newText.length + newTitle.length;
                newTags = editTags;
                break;
            case 2:
                newText = $('#qrd-edit-url').val();
                newSize = newText.length + newTitle.length;
                newTags = editTags;
                break;
        }

        var isPublic = $('#edit-resource').data('is_public');
        var fullTextSearch = $('#edit-resource').data('full_text_search');
        var shortcutWord = $('#edit-resource').data('shorcut_word');
        var allowBotAccess = $('#edit-resource').data('allow_bot');
        var languages = $('#edit-resource').data('languages');
        var ownerOPId = $('#edit-resource').data('oid');
        var ownerGRId = $('#edit-resource').data('g');

        if (inDialog)
        {
            TaskBarManager.RemoveActiveWindow();
            cancelQrd(ticketId);
        }
        else
        {
            TaskBarManager.RemoveActiveWindow();
        }

        if(!d(shortcutWord))
        {
            shortcutWord = '';
            deblog("Undefined KB shortcut");
        }

        if(!d(languages))
        {
            languages = '';
            deblog("Undefined KB language");
        }

        thisClass.lzm_chatPollServer.PollServerResource({First:{
            rid: newRid,
            pid: newPid,
            ra: newRank,
            ti: newTitle,
            ty: newType,
            text: newText,
            si: newSize,
            t: newTags,
            oid: ownerOPId,
            g: ownerGRId,
            di: 0,
            isPublic: isPublic,
            fullTextSearch: fullTextSearch,
            shortcutWord: shortcutWord,
            allowBotAccess: allowBotAccess,
            languages: languages
        }}, "set");

        resource.p = isPublic;
        $('#resource-' + newRid + '-icon-and-text').html(lzm_chatDisplay.resourcesDisplay.GetResourceIconHTML(resource) + '<span class="qrd-title-span">'+lzm_commonTools.htmlEntities(newTitle)+'</span>');
    });
    $('#cancel-edited-qrd').click(function() {

        if (inDialog)
        {
            //lzm_displayHelper.removeDialogWindow('qrd-tree-dialog');

            TaskBarManager.RemoveActiveWindow();


            cancelQrd(ticketId);

        }
        else
        {
            TaskBarManager.RemoveActiveWindow();
        }
    });
    */
};

ChatUserActionsClass.prototype.addQrd = function(type, ticketId, inDialog, toAttachment, sendToChat, menuEntry, _windowObj) {

    console.trace();
    alert("DEPRECATED 32");
    // DEPRECATED
    /*
    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;
    toAttachment = (typeof toAttachment != 'undefined') ? toAttachment : false;
    menuEntry = (typeof menuEntry != 'undefined') ? menuEntry : '';
    sendToChat = (typeof sendToChat != 'undefined') ? sendToChat : null;
    type = (d(type)) ? type : 1;
    _windowObj = (d(_windowObj)) ? _windowObj : null;

    var resourceText = (ticketId != '') ? this.lzm_chatDisplay.ticketResourceText[ticketId] : '';
    var thisClass = this;
    var parentResource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);

    var newRid = md5(Math.random().toString());
    var newPid = '';

    KnowledgebaseUI.DraftResources[newRid] = {rid:newRid,draft:true,ty:type};

    if(parentResource == null)
    {
        parentResource = {ra: 0,di: 0};
    }

    if(toAttachment)
        newPid = '101';
    else if(sendToChat != null)
        newPid = '';
    else if(parentResource != null && parentResource.ty == 0)
        newPid = parentResource.rid;
    else if(parentResource != null)
        newPid = parentResource.pid;

    var newRank = (parentResource.ty == 0) ? parseInt(parentResource.ra) + 1 : parseInt(parentResource.ra);
    var newTitle, newType, newText, newSize, newTags;

    thisClass.lzm_chatDisplay.resourcesDisplay.AddQrd(type, newRid, parentResource, ticketId, inDialog, toAttachment, sendToChat, menuEntry, _windowObj);

    var addResource = $('.qrd-add-resource');
    var addHtmlResource = $('.qrd-add-html-resource');
    var addLinkResource = $('.qrd-add-link-resource');
    var addFolderResource = $('.qrd-add-folder-resource');
    var addFileResource = $('.qrd-add-file-resource');
    var addTitle = $('#qrd-add-title');

    switch (type)
    {
        case 0: // Folder
            addResource.css('display', 'none');
            addResource.css('display', 'none');
            addHtmlResource.css('display', 'block');
            addFolderResource.css('display', 'block');
            addTitle.val(t('New Folder'));
            KnowledgebaseUI.TextEditor = new ChatEditorClass('qrd-add-text', ticketId);
            KnowledgebaseUI.TextEditor.init('', 'addQrd');
            $('#save-new-qrd').removeClass('ui-disabled');
            $('#new-qrd-settings').removeClass('ui-disabled');

            setTimeout(function(){
                $('#qrd-add-title').select();
                $('#qrd-add-title').focus();
            },25);

            $("#qrd-add-text-div label[for='qrd-add-text']").html(tidc('description'));

            break;
        case 1: // HTML resource
            addResource.css('display', 'none');
            addHtmlResource.css('display', 'block');
            addTitle.val(t('New Text'));
            KnowledgebaseUI.TextEditor = new ChatEditorClass('qrd-add-text', ticketId);
            KnowledgebaseUI.TextEditor.init('', 'addQrd');

            setTimeout(function(){
                $('#qrd-add-title').select();
                $('#qrd-add-title').focus();
            },25);

            $('#save-new-qrd').removeClass('ui-disabled');
            $('#new-qrd-settings').removeClass('ui-disabled');

            break;
        case 2: // URL
            addResource.css('display', 'none');
            addLinkResource.css('display', 'block');
            if(sendToChat==null)
                addTitle.val(t('New Link Resource'));
            $('#save-new-qrd').removeClass('ui-disabled');
            $('#new-qrd-settings').removeClass('ui-disabled');
            $('#qrd-add-title').select();
            break;
        case 3: // File
            addResource.css('display', 'none');
            addFileResource.css('display', 'block');
            addTitle.val(t('New File Resource'));
            $('#save-new-qrd').removeClass('ui-disabled');
            $('#new-qrd-settings').removeClass('ui-disabled');
    }

    UIRenderer.resizeAddResources();

    if (typeof ticketId != 'undefined' && ticketId != '')
    {
        addResource.css('display', 'none');
        addHtmlResource.css('display', 'block');
        addTitle.val(t('New Text'));
        if((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
        {
            KnowledgebaseUI.TextEditor = new ChatEditorClass('qrd-add-text', ticketId);
            KnowledgebaseUI.TextEditor.init(resourceText, 'addQrd');
        }
        else
            $('#qrd-add-text').val(resourceText);
    }

    if (toAttachment)
    {
        addResource.css('display', 'none');
        addFileResource.css('display', 'block');
        addTitle.val(t('New File Resource'));
    }

    $('#save-new-qrd').click(function() {
        var addTitle = $('#qrd-add-title').val(), newUrl = '';
        var addTags = $('#add-resource').data('tags');

        newTitle = addTitle;
        newType = type;
        if (newType != 3)
        {
            switch (type)
            {
                case 0:
                    //if((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
                    //{
                        newText = KnowledgebaseUI.TextEditor.grabHtml();
                        KnowledgebaseUI.TextEditor.blur();
                    //}
                    //else
                      //  newText = $('#qrd-add-text').val();
                    newSize = newText.length + newTitle.length;
                    newTags = '';
                    break;
                case 1:
                    //if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
                    //{
                        newText = KnowledgebaseUI.TextEditor.grabHtml();
                        KnowledgebaseUI.TextEditor.blur();
                    //}
                    //else
                      //  newText = $('#qrd-add-text').val();
                    newSize = newText.length + newTitle.length;
                    newTags = addTags;
                    break;
                case 2:
                    newUrl = $('#qrd-add-url').val();
                    newText = $('#qrd-add-url').val();
                    newSize = newText.length + newTitle.length;
                    newTags = addTags;
                    break;
            }

            var isPublic = $('#add-resource').data('is_public');
            var fullTextSearch = $('#add-resource').data('full_text_search');
            var shortcutWord = $('#add-resource').data('shorcut_word');
            var allowBotAccess = $('#add-resource').data('allow_bot');
            var languages = $('#add-resource').data('languages');
            var ownerOPId = $('#add-resource').data('oid');
            var ownerGRId = $('#add-resource').data('g');

            if(parentResource != null && parentResource.ty == 0 && d(parentResource.g))
                ownerGRId = parentResource.g;

            if(!d(ownerOPId))
                ownerOPId = DataEngine.myId;

            if (inDialog)
            {
                TaskBarManager.RemoveActiveWindow();

                var dialogContainerHtml = '<div id="qrd-tree-dialog-container" class="dialog-window-container"></div>';

                $('#chat_page').append(dialogContainerHtml).trigger('create');
                $('#qrd-tree-dialog-container').css(lzm_chatDisplay.dialogWindowContainerCss);
                $('#qrd-tree-dialog-container').replaceWith(lzm_chatDisplay.resourcesDisplay.qrdTreeDialog[ticketId]);

                delete lzm_chatDisplay.resourcesDisplay.qrdTreeDialog[ticketId];
                cancelQrd(ticketId);

                $('#ticket-reply-input-save').removeClass('ui-disabled');
                $('#ticket-reply-input-resource').val(newRid);
            }
            else
            {
                TaskBarManager.RemoveActiveWindow();
            }

            if (sendToChat == null)
            {

                thisClass.lzm_chatPollServer.PollServerResource({First:{
                    rid: newRid,
                    pid: newPid,
                    ra: newRank,
                    ti: newTitle,
                    ty: newType,
                    text: newText,
                    si: newSize,
                    t: newTags,
                    di: 0,
                    oid: ownerOPId,
                    g: ownerGRId,
                    isPublic: isPublic,
                    fullTextSearch: fullTextSearch,
                    shortcutWord: shortcutWord,
                    allowBotAccess: allowBotAccess,
                    languages: languages
                }}, "set");

                var newResource = {di: 0, ed: lzm_chatTimeStamp.getServerTimeString(null, true), eid: DataEngine.myId,
                    md5: '', oid: DataEngine.myId, pid: newPid, ra: newRank, rid: newRid, si: newSize, t: newTags,
                    text: newText, ti: newTitle, ty: newType, p:isPublic};

                DataEngine.cannedResources.setResource(newResource);
            }
        }
        else
        {
            if (sendToChat == null)
            {
                uploadFile('user_file', newPid, newRank, toAttachment, null);
            }
            else
            {
                uploadFile('user_file', null, null, false, sendToChat);
            }
        }
        KnowledgebaseUI.HandleResourceClickEvents(newPid, true);
    });
    $('#cancel-new-qrd').click(function() {
        if (inDialog)
        {
            if (toAttachment)
            {
                TaskBarManager.RemoveActiveWindow();
                TaskBarManager.GetWindow(toAttachment).Maximize();
            }
            else
            {
                alert("ERR: 0x453291");
            }
        }
        else
        {
            TaskBarManager.RemoveActiveWindow();
            if(sendToChat != null)
            {
                var winObj = TaskBarManager.GetWindowByTag(sendToChat.chat_partner);
                if(winObj != null)
                    winObj.Maximize();
            }
        }
    });

    */

};

ChatUserActionsClass.prototype.deleteTicket = function(ticketId, silent) {

    silent = (d(silent)) ? silent : false;
    if (lzm_commonPermissions.checkUserPermissions('','tickets', 'delete_tickets', {}))
    {
        this.lzm_chatPollServer.pollServerTicket([{id: ticketId}], [], 'delete-ticket');
        lzm_chatDisplay.ticketReadArray = lzm_commonTools.removeTicketFromReadStatusArray(ticketId, lzm_chatDisplay.ticketReadArray);
        for (var i=0; i<lzm_chatDisplay.ticketListTickets.length; i++) {
            if (lzm_chatDisplay.ticketListTickets[i].id == ticketId) {
                lzm_chatDisplay.ticketListTickets[i].del = 1;
                break;
            }
        }
        $('#ticket-list-row-' + ticketId).remove();
    }
    else if(!silent)
    {
        showNoPermissionMessage();
    }
};

ChatUserActionsClass.prototype.sendTicketReply = function (ticket, receiver, cc, bcc, subject, message, comment, attachments, messageId, previousMessageId, addToWL, _newStatus) {

    bcc = bcc.replace(/ /g,'').replace(/\(/g,'').replace(/\)/g,'').replace(/;/g,',');
    cc = cc.replace(/ /g,'').replace(/\(/g,'').replace(/\)/g,'').replace(/;/g,',');
    receiver = receiver.replace(/ /g,'').replace(/\(/g,'').replace(/\)/g,'').replace(/;/g,',').replace(/,,/g,',');

    this.lzm_chatPollServer.pollServerTicket([{id: ticket.id, ed: this.lzm_chatDisplay.myId, me: message, rec: receiver, cc:cc, bcc:bcc, lg: ticket.l, gr: ticket.gr, su: subject, mid: messageId, comment: comment, attachments: attachments, newstatus: _newStatus, pmid: previousMessageId}], [], 'send-message');

    if(addToWL.length>0)
    {
        var i,tList = [];
        for (i=0; i<addToWL.length; i++)
            tList.push({id: ticket.id,operatorId: addToWL[i]});
        this.addTicketToWatchList(tList);
    }
};

ChatUserActionsClass.prototype.setTicketPriority = function(list) {
    this.lzm_chatPollServer.pollServerTicket(list,[],'set-priority');
};

ChatUserActionsClass.prototype.addTicketToWatchList = function(list) {
    this.lzm_chatPollServer.pollServerTicket(list,[],'add-to-watch-list');
};

ChatUserActionsClass.prototype.removeTicketFromWatchList = function(list) {
    this.lzm_chatPollServer.pollServerTicket(list,[],'remove-from-watch-list');
};

ChatUserActionsClass.prototype.saveTicketComment = function(ticketId, messageId, commentText) {
    this.lzm_chatPollServer.pollServerTicket([{id: ticketId, mid: messageId, text: commentText}], [], 'add-comment');
};

ChatUserActionsClass.prototype.saveEmailChanges = function(emailChanges, ticketsCreated) {
    var emails = [emailChanges, ticketsCreated];
    CommunicationEngine.pollServerTicket([], emails, 'email-changes');
};