/****************************************************************************************
 * LiveZilla CommonUIClass.js
 *
 * Copyright 2018 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function CommonUIClass(now, lzm_commonConfig, lzm_commonTools, lzm_chatInputEditor, messageTemplates, userConfigData) {
    this.m_OperatorsListLineCounter = 0;
    this.m_OperatorsListSelectedLine = 0;
    this.myLoginId = '';
    this.myId = '';
    this.myName = '';
    this.myEmail = '';
    this.myGroups = [];
    this.myGroupsAway = null;
    this.newGroupsAway = null;
    this.selected_view = '';
    this.lastChatSendingNotification = '';
    this.thisUser = {id: ''};
    this.soundPlayed = [];
    this.userLanguage = 'en';
    this.selectedResource = '';
    this.tabSelectedResources = ['1', '1', '1'];
    this.serverIsDisabled = false;
    this.lastDiabledWarningTime = 0;
    this.askBeforeUnload = true;
    this.startPages = {show_lz: '0', others: []};
    this.startPageTabControlDoesExist = false;
    this.startPageExists = false;
    this.awayAfterTime = userConfigData['awayAfter'];
    this.vibrateNotifications = 1;
    this.ticketReadStatusChecked = 1;
    this.alertNewFilter = 1;
    this.backgroundModeChecked = userConfigData['backgroundMode'];
    this.saveConnections = 0;

    if (IFManager.IsAppFrame && !IFManager.IsDesktopApp())
        IFManager.IFKeepActiveInBackgroundMode(this.backgroundModeChecked == 1);

    this.allViewSelectEntries = {home: {pos: 0, title: 'Startpage', icon: 'img/home-white.png'},
        mychats: {pos: 0, title: 'Chats', icon: ''}, tickets: {pos: 0, title: 'Tickets', icon: ''},
        external: {pos: 0, title: 'Visitors', icon: ''}, archive: {pos: 0, title: 'Chat Archive', icon: ''},
        internal: {pos: 0, title: 'Operators', icon: ''}, qrd: {pos: 0, title: 'Knowledge Base', icon: ''},
        reports: {pos: 1, title: 'Reports', icon: ''}};

    this.showViewSelectPanel = {home: 1, world: 1, mychats: 1, tickets: 1, external: 1, archive: 1, internal: 1, qrd: 1, report: 1};
    this.viewSelectArray = [];
    this.firstVisibleView = 'home';

    this.availableLanguages = {'aa':'Afar','ab':'Abkhazian','af':'Afrikaans','am':'Amharic','ar':'Arabic','as':'Assamese','ay':'Aymara','az':'Azerbaijani','ba':'Bashkir',
        'be':'Byelorussian','bg':'Bulgarian','bh':'Bihari','bi':'Bislama','bn':'Bengali','bo':'Tibetan','br':'Breton','ca':'Catalan','co':'Corsican','cs':'Czech','cy':'Welsh',
        'da':'Danish','de':'German','dz':'Bhutani','el':'Greek','en':'English','en-gb':'English (Great Britain)','en-us':'English (United States)','eo':'Esperanto','es':'Spanish',
        'et':'Estonian','eu':'Basque','fa':'Persian','fi':'Finnish','fj':'Fiji','fo':'Faeroese','fr':'French','fy':'Frisian','ga':'Irish','gd':'Gaelic','gl':'Galician','gn':'Guarani',
        'gu':'Gujarati','ha':'Hausa','he':'Hebrew','hi':'Hindi','hr':'Croatian','hu':'Hungarian','hy':'Armenian','ia':'Interlingua','id':'Indonesian','ie':'Interlingue','ik':'Inupiak',
        'is':'Icelandic','it':'Italian','ja':'Japanese','ji':'Yiddish','jw':'Javanese','ka':'Georgian','kk':'Kazakh','kl':'Greenlandic','km':'Cambodian','kn':'Kannada','ko':'Korean',
        'ks':'Kashmiri','ku':'Kurdish','ky':'Kirghiz','la':'Latin','lb':'Luxembourgish','ln':'Lingala','lo':'Laothian','lt':'Lithuanian','lv':'Latvian','mg':'Malagasy','mi':'Maori','mk':'Macedonian',
        'ml':'Malayalam','mn':'Mongolian','mo':'Moldavian','mr':'Marathi','ms':'Malay','mt':'Maltese','my':'Burmese','na':'Nauru','nb':'Norwegian (Bokmal)','ne':'Nepali','nl':'Dutch',
        'nn':'Norwegian (Nynorsk)','oc':'Occitan','om':'Oromo','or':'Oriya','pa':'Punjabi','pl':'Polish','ps':'Pashto','pt':'Portuguese','pt-br':'Portuguese (Brazil)','qu':'Quechua',
        'rm':'Rhaeto-Romance','rn':'Kirundi','ro':'Romanian','ru':'Russian','rw':'Kinyarwanda','sa':'Sanskrit','sd':'Sindhi','sg':'Sangro','sh':'Serbo-Croatian','si':'Singhalese',
        'sk':'Slovak','sl':'Slovenian','sm':'Samoan','sn':'Shona','so':'Somali','sq':'Albanian','sr':'Serbian','ss':'Siswati','st':'Sesotho','su':'Sudanese','sv':'Swedish','sw':'Swahili',
        'ta':'Tamil','te':'Tegulu','tg':'Tajik','th':'Thai','ti':'Tigrinya','tk':'Turkmen','tl':'Tagalog','tn':'Setswana','to':'Tonga','tr':'Turkish','ts':'Tsonga','tt':'Tatar','tw':'Twi',
        'uk':'Ukrainian','ur':'Urdu','uz':'Uzbek','vi':'Vietnamese','vo':'Volapuk','wo':'Wolof','xh':'Xhosa','yo':'Yoruba','zh':'Chinese','zh-cn':'Chinese (Simplified)',
        'zh-tw':'Chinese (Traditional)','zu':'Zulu'};

    this.searchButtonUpSet = {};
    this.storedSuperMenu = null;
    this.StoredDialogIds = [];
    this.dialogData = {};
    this.ticketListTickets = [];
    this.ticketControlTickets = [];
    this.archiveControlChats = [];
    this.ticket = {};
    this.showTicketContextMenu = false;
    this.showTicketMessageContextMenu = false;
    this.ticketDialogId = {};
    this.ticketResourceText = {};
    this.ticketReadArray = [];
    this.ticketUnreadArray = [];
    this.ticketGlobalValues = {t: -1, r: -1, mr: 0, updating: false};
    this.ticketFilterPersonal = 'hidden';
    this.ticketFilterGroup = 'hidden';
    this.selectedTicketRow = '';
    this.selectedTicketRowNo = 0;
    this.emailReadArray = [];
    this.emailDeletedArray = [];
    this.ticketsFromEmails = [];
    this.showArchiveFilterMenu = false;
    this.archiveFilterChecked = ['visible', 'visible', 'visible'];
    this.showReportFilterMenu = false;
    this.showReportContextMenu = false;
    this.minimizedMemberLists = [];
    this.chatTranslations = {};
    this.translatedPosts = [];
    this.translationLanguages = [];
    this.translationLangCodes = [];
    this.translationServiceError = 'No translations fetched';
    this.lastPhoneProtocol = 'callto:';
    this.newDynGroupHash = '';
    this.showUserstatusHtml = false;
    this.showUsersettingsHtml = false;
    this.windowWidth = 0;
    this.windowHeight = 0;
    this.initialWindowHeight = 0;
    this.chatPanelHeight = 0;
    this.showChatActionsMenu = false;
    this.showOpInviteDialog = false;
    this.translationEditor = new ChatTranslationEditorClass();
    this.reportsDisplay = new ChatReportsClass();
    this.settingsDisplay = new ChatSettingsClass();
    this.startpageDisplay = new ChatStartpageClass();
    this.resourcesDisplay = new KnowledgebaseUI();
    this.archiveDisplay = new ChatArchiveClass();
    this.VisitorsUI = new ChatVisitorClass();
    this.ticketDisplay = new ChatTicketClass();
    this.ChatsUI = new ChatUI();
    this.LinkGenerator = null;
    this.ServerConfigurationClass = null;
    this.EventConfiguration = null;
    this.FilterConfiguration = null;
    this.ChatForwardInvite = null;
    this.FeedbacksViewer = null;
    this.hiddenChats = [];
    this.validationErrorCount = 0;
    this.memberListWidth = 150;
    this.HeartBeat = null;
    this.HeartBeatCounter = 0;
    this.SoundIntervalQueue = false;
    this.SoundIntervalRing = false;
    this.now = now;
    this.lzm_commonConfig = lzm_commonConfig;
    this.lzm_commonTools = lzm_commonTools;
    this.lzm_chatTimeStamp = {};
    this.messageTemplates = messageTemplates;
    this.chatPanelLineHeight = 26;
    this.activeChatPanelHeight = this.chatPanelLineHeight;
    this.dialogWindowWidth = 0;
    this.dialogWindowHeight = 0;
    this.FullscreenDialogWindowWidth = 0;
    this.FullscreenDialogWindowHeight = 0;
    this.dialogWindowLeft = 0;
    this.dialogWindowTop = 0;
    this.FullscreenDialogWindowTop = 0;
    this.dialogWindowContainerCss = {};
    this.dialogWindowCss = {};
    this.dialogWindowHeadlineCss = {};
    this.dialogWindowBodyCss = {};
    this.dialogWindowFootlineCss = {};
    this.FullscreenDialogWindowCss = {};
    this.FullscreenDialogWindowHeadlineCss = {};
    this.FullscreenDialogWindowBodyCss = {};
    this.FullscreenDialogWindowFootlineCss = {};
    this.DialogBorderRatioFull = 0.95;

    this.browserName = 'other';
    if ($.browser.chrome)
        this.browserName = 'chrome';
    else if ($.browser.mozilla)
        this.browserName = 'mozilla';
    else if ($.browser.msie)
        this.browserName = 'ie';
    else if ($.browser.safari)
        this.browserName = 'safari';
    else if ($.browser.opera)
        this.browserName = 'opera';
    if ($.browser.version.indexOf('.') != -1) {
        this.browserVersion = $.browser.version.split('.')[0];
        this.browserMinorVersion = $.browser.version.split('.')[1];
    } else {
        this.browserVersion = $.browser.version;
        this.browserMinorVersion = 0;
    }

    if (this.browserName == 'mozilla' && this.browserVersion == 11)
        this.browserName = 'ie';

    lzm_displayHelper.browserName = this.browserName;
    lzm_displayHelper.browserVersion = this.browserVersion;
    lzm_displayHelper.browserMinorVersion = this.browserMinorVersion;
}

CommonUIClass.MinWidthChatVisitorInfo = 1350;
CommonUIClass.ChatVisitorInfoWidth = 750;
CommonUIClass.BlockUIUpdate = false;
CommonUIClass.LicenseMissing = false;
CommonUIClass.LicenseCheckPassed = false;
CommonUIClass.TranslateChatPost = null;
CommonUIClass.IsExternalAvailable = false;
CommonUIClass.IsOutsideOfOpeningHours = false;
CommonUIClass.UpdateUserList = true;
CommonUIClass.LastTaskBarUpdate = '';
CommonUIClass.ResizeUITimer = null;
CommonUIClass.BlockNavigation = false;
CommonUIClass.BlockNavigationHidden = false;
CommonUIClass.Hovered = false;
CommonUIClass.HoverTimeout = null;

CommonUIClass.prototype.resetWebApp = function() {
    this.validationErrorCount = 0;
    //this.StopIndication([]);
};

CommonUIClass.prototype.toggleVisibility = function () {

    var setCssDisplay = function(elt, displayMode) {
        if (typeof elt != 'undefined' && elt.length > 0 && elt.css('display') != displayMode) {
            elt.css({display: displayMode});
        }
    };

    var activeWindow = TaskBarManager.GetActiveWindow();
    var thisOperatorList = $('#operator-list');
    var thisTicketList = $('#ticket-list');
    var thisArchive = $('#archive');
    var thisStartPage = $('#startpage');
    var thisChat = $('#chat');
    var thisChatContainer = $('#chat-container');
    var thisErrors = $('#errors');
    var thisChatTable = $('#chat-table');
    var thisVisitorList = $('#visitor-list');
    var thisQrdTree = $('#qrd-tree');
    var thisFilter = $('#filter');
    var thisAllChats = $('#all-chats');
    var thisReportList = $('#report-list');

    setCssDisplay(thisStartPage, 'none');
    setCssDisplay(thisOperatorList, 'none');
    setCssDisplay(thisTicketList, 'none');
    setCssDisplay(thisArchive, 'none');
    setCssDisplay(thisErrors, 'none');
    setCssDisplay(thisVisitorList, 'none');
    setCssDisplay(thisQrdTree, 'none');
    setCssDisplay(thisFilter, 'none');
    setCssDisplay(thisReportList, 'none');

    if (!(activeWindow != null && activeWindow.TypeId == 'chat-window'))
    {
        setCssDisplay($('#chat-progress'), 'none');
        setCssDisplay($('#chat-qrd-preview'), 'none');
        setCssDisplay($('#chat-action'), 'none');
        setCssDisplay($('#chat-buttons'), 'none');

        if(IFManager.IsMobileOS && !IFManager.IsTabletOS)
        {
            $('#chat').css('top', '36px');
        }
    }
    else
    {
        setCssDisplay($('#chat-progress'), 'block');
        setCssDisplay($('#chat-action'), 'block');
        setCssDisplay($('#chat-buttons'), 'block');

        if(IFManager.IsMobileOS && !IFManager.IsTabletOS)
        {
            $('#chat').css('top', '27px');
            $('#chat-controls').css('top', 0);
        }
    }

    if(this.selected_view != 'mychats')
    {
        setCssDisplay(thisChat, 'block');
        setCssDisplay(thisChatContainer, 'none');
        setCssDisplay(thisChatTable, 'none');
        setCssDisplay(thisAllChats, 'none');
    }

    switch (this.selected_view)
    {
        case 'mychats':
            setCssDisplay(thisChat, 'block');
            setCssDisplay(thisChatContainer, 'block');
            setCssDisplay(thisChatTable, 'block');
            setCssDisplay(thisAllChats, 'none');
            break;
        case 'internal':
            setCssDisplay(thisOperatorList, 'block');
            break;
        case 'external':
            setCssDisplay(thisVisitorList, 'block');
            break;
        case 'qrd':

            setCssDisplay(thisQrdTree, 'block');
            break;
        case 'tickets':

            setCssDisplay(thisTicketList, 'block');
            break;
        case 'archive':
            setCssDisplay(thisArchive, 'block');

            break;
        case 'home':
            setCssDisplay(thisStartPage, 'block');

            break;
        case 'reports':
            setCssDisplay(thisReportList, 'block');
            break;
    }
};

CommonUIClass.prototype.logoutOnValidationError = function (validationError) {
    var that = this,  alertString = '';

    if (this.validationErrorCount == 0 && $.inArray(validationError, ['0','3','101']) == -1)
    {
        tryNewLogin(false);
        this.validationErrorCount++;
    }
    else if (validationError == '3')
    {
        if (!CommonDialogClass.IsAlert)
        {
            alertString = tid('logged_off');
            lzm_commonDialog.createAlertDialog(alertString, [{id: 'ok', name: t('Ok')}]);
            $('#alert-btn-ok').click(function()
            {
                lzm_commonDialog.removeAlertDialog();
                that.askBeforeUnload = false;
                CommunicationEngine.finishLogout();
                that.validationErrorCount++;
            });
        }
    }
    else if (this.validationErrorCount == 1)
    {
        this.askBeforeUnload = false;
        var noLogout = false;
        if (!CommonDialogClass.IsAlert)
        {
            switch (validationError)
            {
                case '0':
                    alertString = t('Wrong username or password.');
                    break;
                case '2':
                    alertString = t('The operator <!--op_login_name--> is already logged in.',[['<!--op_login_name-->', this.myLoginId]]);
                    break;
                case '3':
                    alertString = tid('logged_off');
                    break;
                case "4":
                    alertString = t('Session timed out.');
                    break;
                case "5":
                    alertString = t('Your password has expired. Please enter a new password.');
                    break;
                case "9":
                    alertString = t('You are not an administrator.');
                    break;
                case "10":
                    alertString = tid('server_deactivated') + '\n' + tid('server_deactivated_undo');
                    break;
                case "13":
                    alertString = t('There are problems with the database connection.');
                    break;
                case "14":
                    alertString = t('This server requires secure connection (SSL). Please activate HTTPS in the server profile and try again.');
                    break;
                case "15":
                    alertString = t('Your account has been deactivated by an administrator.');
                    break;
                case "19":
                    alertString = t('No mobile access permitted.');
                    break;
                default:
                    alertString = 'Validation Error : ' + validationError;
                    break;
            }
            lzm_commonDialog.createAlertDialog(alertString.replace(/\n/g, '<br />'), [{id: 'ok', name: t('Ok')}]);
            $('#alert-btn-ok').click(function() {
                if (!noLogout)
                {
                    that.askBeforeUnload = false;
                    /*
                    if (!IFManager.IsAppFrame)
                    {
                        loginPage = 'index.php?LOGOUT';
                        window.location.href = loginPage;
                    }
                    else
                        IFManager.IFOpenLoginView();
                        */

                    CommunicationEngine.finishLogout();
                }
                else
                    that.validationErrorCount = 0;
                that.validationErrorCount++;
            });
        }
    }
    else if(validationError == '0')
    {
        if (!CommonDialogClass.IsAlert)
        {
            if(IFManager.IsDesktopApp()){
                IFManager.IFGotLoggedOff();
            }
            lzm_commonDialog.createAlertDialog(tid('logged_off'), [{id: 'ok', name: t('Ok')}]);
            $('#alert-btn-ok').click(function()
            {
                lzm_commonDialog.removeAlertDialog();
                CommunicationEngine.finishLogout();
            });
        }
        else
            CommunicationEngine.finishLogout();
    }
};

CommonUIClass.prototype.createGeoTracking = function() {

    if ($('#geotracking-iframe').length == 0)
    {
        $('#geotracking-body').html('<iframe id="geotracking-iframe" src=""></iframe>');
        $('#geotracking-body').data('src', '');
        $('#geotracking-footline').html(lzm_displayHelper.createGeotrackingFootline());
    }

};

CommonUIClass.prototype.CreateOperatorList = function () {

    var that=this,onlineOnly = lzm_commonStorage.loadValue('op_list_onlonl_' + DataEngine.myId)=='1',showElements = lzm_commonStorage.loadValue('op_list_elements_' + DataEngine.myId)=='1';
    this.m_OperatorsListLineCounter = 0;
    if (CommonUIClass.UpdateUserList)
    {
        CommonUIClass.UpdateUserList = false;
        var operators = null;
        var internalChatsAreDisabled = (this.myGroups.length > 0);
        for (i=0; i<this.myGroups.length; i++)
        {
            var myGr = DataEngine.groups.getGroup(this.myGroups[i]);
            if (myGr != null && (typeof myGr.internal == 'undefined' || myGr.internal == '1'))
                internalChatsAreDisabled = false;
        }

        var searchQuery = $('#search-operators').val();
        if(!d(searchQuery))
            searchQuery = '';
        else
            searchQuery = searchQuery.toLowerCase();

        var intUserHtmlString = '<div class="lzm-dialog-headline2" style="top:0;"><span style="float:left;"><table class="tight vtight"><tr>' +
            '<td>' + lzm_inputControls.createCheckbox('operator-list-display',tid('by_groups'),!showElements) + '</td>' +
            '<td>' + lzm_inputControls.createCheckbox('operator-list-offline',tid('show_offline'),!onlineOnly) +'</td>' +
            '</tr></table>';
        intUserHtmlString += '</span>' + lzm_inputControls.createInput('search-operators','', searchQuery, t('Search'), '<i class="fa fa-remove"></i>', 'text', 'b')+'</div><div id="operator-list-body"><table id="operator-list-table">';
        intUserHtmlString += this.CreateOperatorListLine('group',{id:'everyoneintern',name:tid('all_operators')},showElements);

        this.m_OperatorsListLineCounter++;

        var groups = DataEngine.groups.getGroupList('name', false, false);

        for (var i=0; i<groups.length; i++)
        {
            operators = DataEngine.operators.getOperatorList('name', groups[i].id, true);

            var showThisGroup = !internalChatsAreDisabled;

            if ($.inArray(groups[i].id, this.myGroups) != -1)
                showThisGroup = true;

            if (showThisGroup &&  (operators.length > 0 || (d(groups[i].o) && groups[i].o == this.myId)))
            {
                var expanded = searchQuery.length || $.inArray(groups[i].id,LocalConfiguration.CollapsedGroups) == -1;
                intUserHtmlString += this.CreateOperatorListLine('group',groups[i],showElements,expanded);
                this.m_OperatorsListLineCounter++;

                if(!showElements)
                {
                    if(expanded)
                        intUserHtmlString += this.CreateOperatorsHTML(groups[i],operators,internalChatsAreDisabled,onlineOnly,this.m_OperatorsListSelectedLine,searchQuery);
                }
            }
        }

        if(showElements)
        {
            intUserHtmlString += '<tr><td colspan="5"></td></tr>';
            operators = DataEngine.operators.getOperatorList('name', '', true);

            intUserHtmlString += this.CreateOperatorsHTML({id:''},operators,internalChatsAreDisabled,onlineOnly,this.m_OperatorsListSelectedLine,searchQuery);
        }

        intUserHtmlString += '</table></div>';

        var tempScrollTop = $('#operator-list-body').scrollTop();

        $('#operator-list').html(intUserHtmlString);

        UIRenderer.resizeOperatorList();

        $('#operator-list-body').scrollTop(tempScrollTop);
        $('#operator-list-display').change(function(){

            CommonUIClass.UpdateUserList = true;
            lzm_commonStorage.saveValue('op_list_elements_' + DataEngine.myId,$('#operator-list-display').prop('checked')?'0':'1');
            that.CreateOperatorList();
        });
        $('#operator-list-offline').change(function(){

            CommonUIClass.UpdateUserList = true;
            lzm_commonStorage.saveValue('op_list_onlonl_' + DataEngine.myId,$('#operator-list-offline').prop('checked')?'0':'1');
            that.CreateOperatorList();
        });

        $('#search-operators').keyup(function(e) {
            lzm_chatDisplay.searchButtonUp('operators', null, e);
        });
        $('#search-operators').keydown(function(e) {
            lzm_chatDisplay.searchButtonChange('operators');
        });
        $('#search-operators').keydown();

        $('#search-operators-icon').click(function() {
            $('#search-operators').val('');
            $('#search-operators').keyup();
        });
        $('.op-exp-switch').click(function(e){
            if(d(e))
                e.stopPropagation();

            var gid = lz_global_base64_decode($(this).data('gid'));
            var expanded = $.inArray(gid,LocalConfiguration.CollapsedGroups) == -1;
            if(expanded)
                LocalConfiguration.CollapsedGroups.push(gid);
            else
                lzm_commonTools.RemoveFromArray(LocalConfiguration.CollapsedGroups,gid);
            LocalConfiguration.Save();
            CommonUIClass.UpdateUserList = true;
            lzm_chatDisplay.CreateOperatorList();
        });
    }
};

CommonUIClass.prototype.CreateOperatorListLine = function(type,obj,_showElements,_expanded) {
    var onclickAction='',oncontextmenuAction,selectedLine='',gtitle,lineId='';
    if(type == 'group')
    {
        var status = (!_expanded) ? '<i class="fa fa-plus-square icon-blue"></i>' : '<i class="fa fa-minus-square icon-blue"></i>';
        if(_showElements || obj.id=='everyoneintern')
            status = '';
        else
            status = '<span class="op-exp-switch lzm-clickable2" data-gid="'+lz_global_base64_encode(obj.id)+'">'+status+'</span>';

        lineId = 'operator-list-line-'+obj.id + '_' + this.m_OperatorsListLineCounter;
        onclickAction = ((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()) ? ' onclick="openOperatorListContextMenu(event, \'group\', \'' + obj.id + '\',\'' + lineId + '\', \'\', \'' + this.m_OperatorsListLineCounter + '\');"' : ' onclick="selectOperatorLine(\'' + lineId + '\', \'' + this.m_OperatorsListLineCounter + '\',\'' + obj.id + '\',\'' + obj.id + '\',\'' + lz_global_base64_url_encode(obj.name) + '\', true);"';
        oncontextmenuAction = ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) ? ' oncontextmenu="openOperatorListContextMenu(event, \'group\', \'' + obj.id + '\',\'' + lineId + '\', \'everyoneintern\', \'' + this.m_OperatorsListLineCounter + '\');"' : '';
        selectedLine = (parseInt(this.m_OperatorsListSelectedLine)==parseInt(this.m_OperatorsListLineCounter)) ? ' selected-table-line' : '';
        gtitle = (d(obj.name)) ? obj.name : obj.id;
        return '<tr id="'+lineId+'" class="operator-list-line'+selectedLine+'" ' + onclickAction + oncontextmenuAction + '><th class="lzm-unselectable" colspan="3">'+status+'<span class="nic">' + gtitle + '</span></th><th></th><th></th></tr>';
    }
    return '';
};

CommonUIClass.prototype.CreateOperatorsHTML = function (group,operators,internalChatsAreDisabled,onlineOnly,selectedLine,_searchQuery,tableId,clickFunc,contFunc,dbcFunc) {

    clickFunc = (d(clickFunc)) ? clickFunc : 'selectOperatorLine';
    contFunc = (d(contFunc)) ? contFunc : 'openOperatorListContextMenu';
    dbcFunc = (d(dbcFunc)) ? dbcFunc : 'OpenChatWindow';
    tableId = (d(tableId)) ? tableId : '';

    var selectedLineClass,onclickAction = '', ondblclickAction = '', ccount, oncontextmenuAction = '', i = 0,intUserHtmlString = '',lineId ='';
    for (var j=0; j<operators.length; j++)
        if($.inArray(group.id, operators[j].groups) != -1 || group.id=='')
            if(!onlineOnly || operators[j].status!=2)
                if (!internalChatsAreDisabled || operators[j].id == this.myId)
                {
                    if(_searchQuery.length)
                    {
                        if(operators[j].id.toLowerCase().indexOf(_searchQuery) == -1)
                            if(operators[j].name.toLowerCase().indexOf(_searchQuery) == -1)
                                continue;
                    }

                    var operatorLogo = operators[j].logo;
                    var avcol = (operators[j].status==2) ? '-gray' : (operators[j].status==0) ? '-green' : '-orange';
                    if (operators[j].status != 2 && $.inArray(group.id, operators[j].groupsAway) != -1)
                    {
                        operatorLogo = 'img/lz_away.png';
                        avcol = '-orange';
                    }

                    var statusIcon = '<span class="operator-list-icon" style="background-image: url(\'' + operatorLogo + '\');"></span>';

                    if(operators[j].isbot)
                        statusIcon = '<i class="fa fa-microchip icon-large '+( d(operators[j].deac) ? 'icon-light' : 'icon-green' )+'" style="margin: 3px 0 0 2px;"></i>';

                    lineId = 'operator-'+ tableId +'list-line-'+operators[j].id + '_' + this.m_OperatorsListLineCounter;
                    onclickAction = ((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()) ? ' onclick="'+contFunc+'(event, \'operator\', \'' + operators[j].id + '\',\'' + lineId + '\', \'' + group.id + '\', \'' + this.m_OperatorsListLineCounter + '\');"' : ' onclick="'+clickFunc+'(\'' + lineId + '\', \'' + this.m_OperatorsListLineCounter + '\',\'' + operators[j].id + '\',\'' + operators[j].userid + '\',\'' + lz_global_base64_url_encode(operators[j].name) + '\', true);"';
                    ondblclickAction = (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !internalChatsAreDisabled) ? ' ondblclick="'+dbcFunc+'(\'' + operators[j].id + '\',\'' + operators[j].userid + '\',\'' + operators[j].name + '\', true);"' : '';
                    oncontextmenuAction = (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && contFunc.length) ? ' oncontextmenu="'+contFunc+'(event, \'operator\', \'' + operators[j].id + '\',\'' + lineId + '\', \'' + group.id + '\', \'' + this.m_OperatorsListLineCounter + '\');"' : '';

                    lineId = 'operator-'+ tableId +'list-line-' + operators[j].id + '_' + this.m_OperatorsListLineCounter;
                    selectedLineClass = (parseInt(selectedLine)==parseInt(this.m_OperatorsListLineCounter) || operators[j].id == selectedLine) ? ' selected-table-line' : '';

                    intUserHtmlString += '<tr id="'+lineId+'" data-id="'+operators[j].id+'" class="operator-'+ tableId +'list-line'+selectedLineClass+'" ' + onclickAction + oncontextmenuAction + '>' +
                        '<td class="">'+statusIcon+'</td><td class="">'+lzm_inputControls.createAvatarField('avatar-box-small avatar-box','',operators[j].id)+'</div></td>';

                    ccount = (operators[j].status != '2') ? ' <span class="lzm-info-text">(' + DataEngine.ChatManager.GetChatsOf(operators[j].id,[Chat.Active,Chat.Open]).length.toString() + ' ' + tid('chats') + ')</span>' : '';

                    intUserHtmlString += '<td class="lzm-unselectable">' + operators[j].name + ccount + '</td><td>';

                    if(operators[j].status == '2' && lzm_chatDisplay.windowWidth > 500)
                    {
                        var laString = '';
                        if(operators[j].la>0)
                            laString = tidc('last_online',': ') + lzm_commonTools.getHumanDate(lzm_chatTimeStamp.getLocalTimeObject(parseInt(operators[j].la * 1000), true), '', lzm_chatDisplay.userLanguage) + ' ';
                        intUserHtmlString += '<span class="text-s">'+laString+'</span>';
                    }

                    intUserHtmlString += '</td><td>';

                    if ((operators[j].mobileAccount && operators[j].status == '2'))
                        intUserHtmlString += '<i class="fa fa-tablet icon-light icon-large"></i>';
                    else if (operators[j].clientMobile && operators[j].status != '2' && !IFManager.IsDesktopApp(operators[j].appOS))
                        intUserHtmlString += '<i class="fa fa-tablet icon-light icon-large"></i>';

                    if (operators[j].level==1)
                        intUserHtmlString += '<i class="fa fa-star-o icon-light icon-large" title="'+tid('admin')+'"></i>';

                    intUserHtmlString += '</td></tr>';
                    this.m_OperatorsListLineCounter++;
                }
    return intUserHtmlString;
};

CommonUIClass.prototype.createPublicGroup = function () {
    this.newDynGroupHash = md5(String(Math.random())).substr(0, 10);
    var input = '<label>'+tidc('group') + ' (' + tid('name') + ')'+'</label><input type="text" id="new-dynamic-group-name" data-role="none" class="lzm-text-input" autofocus />';
    lzm_commonDialog.createAlertDialog(input, [{id: 'ok', name: t('Ok')}, {id: 'cancel', name: t('Cancel')}]);
    $('#new-dynamic-group-name').focus();
    $('#alert-btn-cancel').click(function(e) {
        lzm_commonDialog.removeAlertDialog();
    });
    $('#alert-btn-ok').click(function(e) {
        saveNewDynamicGroup();
        lzm_commonDialog.removeAlertDialog();
    });
};

CommonUIClass.prototype.addToChatGroup = function (id, browserId, chatId){

    if(TaskBarManager.WindowExists('dynamic-group-dialog',true))
        return;

    var parentWindow = TaskBarManager.GetActiveWindow();
    var accChat=false, headerString = tid('group_chat_add');

    var bodyString = lzm_displayHelper.createAddToDynamicGroupHtml(id, browserId);
    var footerString = lzm_inputControls.createButton('save-dynamic-group', '', '', t('Ok'), '', 'lr',{'margin-left': '4px'},'',30,'d') +
        lzm_inputControls.createButton('cancel-dynamic-group', '', '', t('Close'), '', 'lr',{'margin-left': '4px'},'',30,'d');

    lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'group', 'dynamic-group', 'dynamic-group-dialog', 'cancel-dynamic-group', false);
    UIRenderer.resizeDynamicGroupDialogs();

    selectChatGroup($('#dynamic-group-table').data('selected-group'));
    $('#save-dynamic-group').click(function()
    {
        lzm_chatDisplay.ChatsUI.EditorBlocked = false;
        if ($('#create-new-group').attr('checked') == 'checked')
        {
            UserActions.SaveChatGroup('create-add', '', $('#new-group-name').val(), id,{isPersistent: $('#persistent-group-member').attr('checked') == 'checked', browserId: browserId, chatId: chatId});
            accChat = true;
            parentWindow = null;
        }
        else
        {
            var group = DataEngine.groups.getGroup($('#dynamic-group-table').data('selected-group'));
            if (lzm_commonPermissions.checkUserPermissions(lzm_chatDisplay.myId, 'group', '', group)) {
                var isAlreadyInGroup = false;
                for (var i=0; i<group.members.length; i++)
                {
                    isAlreadyInGroup = (group.members[i].i == id) ? true : isAlreadyInGroup;
                }
                if (!isAlreadyInGroup)
                {
                    UserActions.SaveChatGroup('add', $('#dynamic-group-table').data('selected-group'), '', id,{isPersistent: $('#persistent-group-member').attr('checked') == 'checked', browserId: browserId, chatId: chatId});
                    accChat = true;
                    var chatObj = DataEngine.ChatManager.GetChat(chatId,'i');
                    if(chatObj != null)
                    {
                        OpenChatWindow(group.id);
                        parentWindow = TaskBarManager.GetWindow(group.id);
                    }
                }
                else
                {
                    var alertText =  t('A user with this name already exists in this group.');
                    lzm_commonDialog.createAlertDialog(alertText, [{id: 'ok', name: t('Ok')}]);
                    $('#alert-btn-ok').click(function() {
                        lzm_commonDialog.removeAlertDialog();
                    });
                }
            }
            else
                showNoPermissionMessage();
        }

        var winObj = TaskBarManager.GetWindow('dynamic-group-dialog');
        if(winObj != null)
            winObj.Maximize();

        $('#cancel-dynamic-group').click();
        if(accChat && d(chatObj) && chatObj != null && chatObj.Type == Chat.Visitor && !chatObj.IsAccepted())
        {
            UserActions.AcceptChat(chatObj,false);
        }
    });
    $('#cancel-dynamic-group').click(function() {

        TaskBarManager.RemoveWindowByDialogId('dynamic-group-dialog');

        if(parentWindow != null)
            parentWindow.Maximize();
        else
            SelectView('mychats');
    });
};

CommonUIClass.prototype.createOperatorListContextMenu = function(myObject){
    var checkIcon,checkClass,disabledClass, onclickAction, contextMenuHtml = '', awayGroup = '', thisClass = this;
    var isBot = (d(myObject['chat-partner'].isbot) && myObject['chat-partner'].isbot == 1);
    var browserId = (typeof myObject.browser != 'undefined' && typeof myObject.browser.id != 'undefined') ? myObject.browser.id : '';
    var chatId = (typeof myObject.browser != 'undefined' && typeof myObject.browser.chat != 'undefined') ? myObject.browser.chat.id : '';
    var group = DataEngine.groups.getGroup(myObject.groupId);
    var internalChatsAreDisabled = true;
    for (var i=0; i<this.myGroups.length; i++) {
        var myGr = DataEngine.groups.getGroup(this.myGroups[i]);
        if (myGr == null || myGr.internal == '1')
            internalChatsAreDisabled = false;
    }

    var groupIsDynamic = (group != null && typeof group.i != 'undefined');
    disabledClass = (myObject.type == 'operator' && (myObject['chat-partner'].userid == thisClass.myLoginId || myObject['chat-partner'].isbot) || internalChatsAreDisabled) ? ' class="ui-disabled"' : '';

    onclickAction = 'OpenChatWindow(\'' + myObject['chat-partner'].id + '\');';
    contextMenuHtml += '<div' + disabledClass + ' onclick="' + onclickAction + 'removeOperatorListContextMenu();"><span id="chat-with-this-partner" class="cm-line cm-click">' +
        t('Start Chat') + '</span></div><hr />';

    disabledClass = (myObject.type != 'operator' || myObject['chat-partner'].userid == thisClass.myLoginId || isBot || myObject['chat-partner'].status == 2) ?
        ' class="ui-disabled"' : '';
    onclickAction = 'signOffOperator(\'' + myObject['chat-partner'].id + '\');';
    contextMenuHtml += '<div' + disabledClass + ' onclick="' + onclickAction + 'removeOperatorListContextMenu();"><span id="sign-off-this-operator" class="cm-line cm-click">' + tid('sign_off') + '</span></div><hr />';



    disabledClass = (myObject['chat-partner'].id != thisClass.myId) ? ' class="ui-disabled"' : '';
    awayGroup = (myObject.type == 'operator' && $.inArray(myObject.groupId, myObject['chat-partner'].groupsAway) != -1);

    checkIcon = (!awayGroup) ? '<i class="fa fa-check-circle icon-green"></i>' : '';
    checkClass = (!awayGroup) ? ' cm-line-icon-left' : '';

    onclickAction = 'toggleIndividualGroupStatus(\'' + myObject.groupId + '\', \'remove\');';
    contextMenuHtml += '<div' + disabledClass + '>' + checkIcon + '<span class="cm-line cm-click'+checkClass+'" onclick="' + onclickAction + '">' + t('Status: Default') + '</span></div>';


    checkIcon = (awayGroup) ? '<i class="fa fa-check-circle icon-orange"></i>' : '';
    checkClass = (awayGroup) ? ' cm-line-icon-left' : '';

    onclickAction = 'toggleIndividualGroupStatus(\'' + myObject.groupId + '\', \'add\');';
    contextMenuHtml += '<div' + disabledClass + '>' + checkIcon + '<span class="cm-line cm-click'+checkClass+'" onclick="' + onclickAction + '">' + t('Status: Away') + '</span></div><hr />';


    disabledClass = (myObject.type != 'operator' || internalChatsAreDisabled || isBot) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + '><span id="add-to-dynamic-group" class="cm-line cm-click" onclick="addToChatGroup(\'' + myObject['chat-partner'].id + '\', \'' + browserId + '\', \'' + chatId + '\'); removeOperatorListContextMenu();">' + tid('group_chat_add') + '</span></div>';

    disabledClass = ((myObject.type != 'operator' && myObject.type != 'visitor') || !groupIsDynamic || internalChatsAreDisabled) ? ' class="ui-disabled"' : '';
    var cpId = (myObject.type != 'visitor') ? myObject['chat-partner'].id : myObject['chat-partner'].id + '~' + myObject['chat-partner'].b_id;

    contextMenuHtml += '<div' + disabledClass + ' onclick="removeFromChatGroup(\'' + cpId +'\', \'' + myObject.groupId + '\');removeOperatorListContextMenu();">' +
        '<span id="remove-from-dynamic-group" class="cm-line cm-click">' + tid('group_chat_remove') + '</span></div><hr>';

    disabledClass = (myObject.type != 'group' || typeof myObject['chat-partner'].i == 'undefined' || internalChatsAreDisabled) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="getDynamicGroupURL(\'' + myObject['chat-partner'].id + '\'); removeOperatorListContextMenu();"><span id="delete-dynamic-group" class="cm-line cm-click">' + tid('get_group_url') + '</span></div>';

    return contextMenuHtml;
};

CommonUIClass.prototype.GetPostHTML = function(_cpc,_showAvatar,_notify,_dlh){

    var messageText='',addClass = '',avparams,aspace = '',avatar='',name;
    var post = _cpc.postObj;
    var xoperator = DataEngine.operators.getOperator(post.sen);
    var postText = (xoperator==null || typeof post.textOriginal == 'undefined') ? post.text : post.textOriginal;

    postText = (typeof postText != 'undefined') ? lzm_commonTools.replaceLinksInChatView(postText) : '';

    var notifier='',messageTime = post.time_human;
    var chatText = '<span>' + lzm_displayHelper.replaceSmileys(postText) + '</span>';

    if (typeof post.tr != 'undefined' && post.tr != '')
        chatText = '<span>' + lzm_displayHelper.replaceSmileys(post.tr) + '</span><br /><span class="lz_message_translation">' + lzm_displayHelper.replaceSmileys(postText) + '</span>';
    else if(this.translatedPosts.length)
    {
        var tr = lzm_commonTools.GetElementByProperty(this.translatedPosts,'id',post.id);
        if(tr.length)
        {
            if (d(post.info_header))
                post.info_header.question = tr[0].text;
            else
                chatText = '<span>' + lzm_displayHelper.replaceSmileys(tr[0].text) + '</span>';
        }
    }

    if (d(post.info_header))
    {
        var targetGroup = DataEngine.groups.getGroup(post.info_header.group);
        var inputText,val,groupName = (targetGroup != null) ? (!d(targetGroup.name)) ? targetGroup.id : targetGroup.name : post.info_header.group;

        var visitorInfoVisible = _dlh && lzm_commonStorage.loadValue('show_chat_visitor_info_' + DataEngine.myId,1)!=0 && $('#chat-container').width() > CommonUIClass.MinWidthChatVisitorInfo;
        var rfield='',rfieldclass='TCBAE',addRow = '<tr><td class="TCBHF"><!--label--></td><td class="last"><!--value--></td></tr>';

        if(_showAvatar)
        {
            name = (_cpc.chatObj.Visitor != null) ? _cpc.chatObj.GetName() : post.info_header.name;
            avparams = 'name=' + lz_global_base64_url_encode(name);
            avatar = '<div class="avatar-box" style="background-image: url(\'./../picture.php?'+avparams+'\');"></div>';
            rfield += avatar;
        }

        if(_dlh)
            rfield += '<div class="dl-c-history lzm-clickable" onclick="ChatManager.DLChatMessagesList.push('+post.info_header.chat_id+');CommunicationEngine.InstantPoll();"><i class="fa fa-cloud-download icon-large text-white"></i></div>';

        if(rfield.length)
            rfieldclass = 'TCBA';

        messageText = '<div class="TCBB"><table class="TCB header_class_placeholder"><tr><td class="TCBG" rowspan="100"></td><td class="TCBHF"><!--group_label-->&nbsp;&nbsp;</td><td class="last" style="white-space: normal;"><b><!--group_name--></b><!--receivers--></td><td class="'+rfieldclass+'" rowspan="100">' + rfield + '</td></tr>';
        messageText = messageText.replace(/<!--group_label-->/g,tidc('group'));
        messageText = messageText.replace(/<!--group_name-->/g,groupName);

        var wsn = VisitorManager.GetWebsiteNames(_cpc.chatObj.Visitor);

        if(lz_global_trim(wsn).length > 0)
            messageText += addRow.replace('<!--label-->',tidc('website_name')).replace('<!--value-->',this.lzm_commonTools.htmlEntities(wsn));
        else
            messageText += addRow.replace('<!--label-->',tidc('website_name')).replace('<!--value-->','-');

        messageText += addRow.replace('<!--label-->',tidc('chat_id')).replace('<!--value-->',this.lzm_commonTools.htmlEntities(post.info_header.chat_id));

        if(_cpc.chatObj.Visitor.d != null)
        {
            for (var key in _cpc.chatObj.Visitor.d)
            {
                var input = DataEngine.inputList.getCustomInput(key);
                if(input != null && !visitorInfoVisible)
                {
                    val = DataEngine.inputList.getInputValueFromVisitor(key,_cpc.chatObj.Visitor);

                    try
                    {
                        inputText = (input.type != 'CheckBox') ? val : (val.toString() == '1') ? t('Yes') : t('No');
                        if(input.type == 'ComboBox')
                            inputText = input.value[parseInt(val)];
                        if(key == 'f116' && inputText != '')
                            inputText = '<a href="#" onclick="showPhoneCallDialog(\'' + _cpc.chatObj.Visitor.i + '\', -1, \'chat\');">' + lzm_commonTools.htmlEntities(inputText) + '</a>';
                    }
                    catch(ex)
                    {
                        deblog(ex);
                    }

                    if(d(inputText) && inputText.length)
                        messageText += '<tr><td style="vertical-align: top;">' + LzmCustomInputs.GetLocaleName(input,true)  + '</td><td>' + inputText + '</td></tr>';
                }
            }
        }

        if(post.info_header.url.length > 0)
            messageText += '<tr><td class="TCBHF">'+tidc('url')+'</td><td><a class="lz_chat_link_no_icon" href="#" data-url="'+post.info_header.url+'" onclick="openLink(\''+post.info_header.url+'\');">'+post.info_header.url+'</a></td></tr>';
        messageText += '</table></div>';

        _cpc.previousMessageSender = '';
        _cpc.previousMessageRepost = 1;
        _cpc.previousAddMessageStyle = 1;
    }
    else
    {
        var senderName = _cpc.chatObj.GetName();

        if(xoperator != null)
            senderName = xoperator.name;
        else
        {
            var vchatObj = DataEngine.ChatManager.GetChat(post.sen);
            if(vchatObj != null)
                senderName = vchatObj.GetName();
        }

        if (d(post.warn))
            addClass = ' WCMT';
        else if (post.rp == 1)
            addClass = ' RCMT';
        else if (post.sen == DataEngine.myId)
            addClass = ' OCMT';
        else if(xoperator != null || post.sen != DataEngine.myId)
            addClass = ' OOCMT';

        if(!LocalConfiguration.UIShowAvatars)
            addClass += ' NOAV';

        if (_cpc.previousMessageSender != post.sen || _cpc.previousMessageRepost != post.rp || parseInt(post.date) - _cpc.previousMessageTimestamp > 300)
        {
            if (post.rp == 1)
            {
                messageText = this.messageTemplates['repost'].replace(/<!--name-->/g,(senderName));
            }
            else
            {
                if (post.sen == this.myId)
                    messageText = this.messageTemplates['internal'].replace(/<!--name-->/g,(senderName));
                else if (post.sen == '0000000')
                    messageText = this.messageTemplates['system'].replace(/<!--name-->/g,(senderName));
                else
                    messageText = this.messageTemplates['external'].replace(/<!--name-->/g,(senderName));
            }
            _cpc.previousAddMessageStyle = 1;
        }
        else
        {
            if (post.sen == '0000000')
                messageText = this.messageTemplates['systemadd'].replace(/<!--name-->/g,lzm_commonTools.escapeHtml(senderName, true));
            else if (_cpc.previousAddMessageStyle == 0)
                messageText = this.messageTemplates['add'].replace(/<!--name-->/g,lzm_commonTools.escapeHtml(senderName, true));
            else
                messageText = this.messageTemplates['addalt'].replace(/<!--name-->/g,lzm_commonTools.escapeHtml(senderName, true));
            _cpc.previousAddMessageStyle = 1 - _cpc.previousAddMessageStyle;
        }

        if(LocalConfiguration.UIShowAvatars)
        {
            avparams = (xoperator==null) ? 'name=' + lz_global_base64_url_encode(senderName) : 'operator=' + encodeURIComponent(post.sen);
            aspace = ' style="width:56px;"';
            avatar = '<div style="background-image: url(\'./../picture.php?'+avparams+'\');"></div>';
        }

        if(post.sen == this.myId && _notify)
        {
            var group = DataEngine.groups.getGroup(post.reco);
            if(group==null && post.reco != 'everyoneintern')
                notifier += '<i style="margin-left:5px;" class="fa fa-check-circle ' + ((d(post.Received) && post.Received) ? 'icon-blue' : 'icon-light') + '"></i>';
            if(post.reco.indexOf('~')!=-1)
                notifier += '<i style="margin-left:2px;" class="fa fa-check-circle ' + ((d(post.Noticed) && post.Noticed) ? 'icon-green' : 'icon-light') + '"></i>';
        }

        if(xoperator == null)
            chatText = lzm_commonTools.addLinksToChatInput(chatText);

        messageText = messageText.replace(/<!--avatar-->/g, avatar);
        messageText = messageText.replace(/<!--aspace-->/g, aspace);
        messageText = messageText.replace(/<!--t-->/g, addClass);
        messageText = messageText.replace(/<!--pn-->/g, post.id);
        messageText = messageText.replace('<!--notifier-->', notifier);
        messageText = messageText.replace('<!--time-->', messageTime);
        messageText = messageText.replace(/<!--message-->/g, chatText);
        messageText = messageText.replace(/<!--dir-->/g, 'ltr');

        _cpc.previousMessageSender = post.sen;
        _cpc.previousMessageRepost = (post.rp == 1) ? 1 : 0;
    }

    _cpc.html = messageText;
    _cpc.previousMessageTimestamp = parseInt(_cpc.postObj.date);
    return _cpc;
};

CommonUIClass.prototype.RenderWindowLayout = function (recreate) {

    var ratio = 1;
    var windowHeight = window.innerHeight;

    if (windowHeight >= this.initialWindowHeight)
        this.initialWindowHeight = windowHeight;

    var switchWidth = 600;
    var switchHeight = 500;
    var topDistance = 36;
    var bottomDistance = $('#task-bar-panel').outerHeight();
    var winObj = TaskBarManager.GetActiveWindow();

    if((winObj != null && !winObj.Fullscreen) || (IFManager.IsMobileOS && !IFManager.IsTabletOS))
        topDistance = 0;

    windowHeight -= bottomDistance;
    windowHeight -= topDistance;

    if (recreate || UIRenderer.windowWidth != this.windowWidth || windowHeight != this.windowHeight ||
        this.activeChatPanelHeight < (this.chatPanelHeight - 5) ||
        this.activeChatPanelHeight > (this.chatPanelHeight + 5))
    {
        this.chatPanelHeight = this.activeChatPanelHeight;
        this.FullscreenDialogWindowWidth = (UIRenderer.windowWidth <= switchWidth || windowHeight <= switchHeight) ? UIRenderer.windowWidth : Math.floor(ratio * UIRenderer.windowWidth) - 0;
        this.FullscreenDialogWindowHeight = (UIRenderer.windowWidth <= switchWidth || windowHeight <= switchHeight) ? windowHeight : Math.floor(ratio * windowHeight) - 0;

        if(!CommonUIClass.BlockNavigationHidden)
            this.FullscreenDialogWindowWidth -= 70;

        if (this.FullscreenDialogWindowWidth <= switchWidth || this.FullscreenDialogWindowHeight <= switchHeight)
        {
            this.dialogWindowWidth = this.FullscreenDialogWindowWidth;
            this.dialogWindowHeight = this.FullscreenDialogWindowHeight;
        }
        else
        {
            this.dialogWindowWidth = switchWidth;
            this.dialogWindowHeight = switchHeight;
        }

        this.dialogWindowLeft = (this.dialogWindowWidth < UIRenderer.windowWidth) ? Math.floor((UIRenderer.windowWidth - this.dialogWindowWidth) / 2) : 0;
        //this.FullscreenDialogWindowLeft = (CommonUIClass.BlockNavigation) ? '70px' : 0;//(this.FullscreenDialogWindowWidth < UIRenderer.windowWidth) ? Math.floor((UIRenderer.windowWidth - this.FullscreenDialogWindowWidth) / 2) : 0;
        this.dialogWindowTop = (this.dialogWindowHeight < windowHeight) ? Math.floor((windowHeight - this.dialogWindowHeight) / 2) : 0;
        this.FullscreenDialogWindowTop = (this.FullscreenDialogWindowHeight < windowHeight) ? Math.floor((windowHeight - this.FullscreenDialogWindowHeight) / 2) : 0;

        this.dialogWindowContainerCss =
        {
            top: topDistance + 'px',
            position: 'absolute',
            left: 0, bottom: 0,
            right:0,
            'border-top':'1px solid #fff',
            height: windowHeight+'px',
            'background-color': 'rgba(0,0,0,0.75)', 'background-image': 'repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.02) 35px, rgba(255,255,255,.02) 70px)', 'z-index': '1001', overflow: 'hidden'

        };

        this.dialogWindowCss =
        {
            position: 'absolute',
            left: this.dialogWindowLeft+'px',
            bottom: this.dialogWindowTop+'px',
            width: this.dialogWindowWidth+'px',
            height: this.dialogWindowHeight+'px',
            'z-index': '1002'
        };

        this.dialogWindowHeadlineCss =
        {
            position: 'absolute', left: '0px', top: '0px',
            width: (this.dialogWindowWidth - 5)+'px', height: '20px'
        };

        var bh = this.dialogWindowHeight - 65;
        if(winObj != null && !$('#' + winObj.DialogId + '-footline').length)
            bh += 27;

        this.dialogWindowBodyCss =
        {
            position: 'absolute', left: '0px', top: '27px',
            width: '100%', height: bh+'px',
            padding: '0', 'text-shadow': 'none',
            'background-color': '#fff', 'overflow-y': 'auto', 'overflow-x': 'hidden'
        };

        this.dialogWindowFootlineCss = {
            position: 'absolute', left: '0px', top: (this.dialogWindowHeight - 38)+'px', 'border-top': '1px solid #ccc',
            width: (this.dialogWindowWidth - 6)+'px', height: '27px', 'text-align': 'right',
            padding: '11px 6px 0px 0px', 'background-color': '#f5f5f5'
        };

        this.FullscreenDialogWindowCss = {
            position: 'absolute', /*left: this.FullscreenDialogWindowLeft+'px',*/ bottom: this.FullscreenDialogWindowTop+'px',
            /*width: this.FullscreenDialogWindowWidth+'px',*/right:0,left:0,height: this.FullscreenDialogWindowHeight+'px',
            'z-index': '1002'
        };
        this.FullscreenDialogWindowHeadlineCss = {
            position: 'absolute', left: '0px', top: '0px',
            /*width: (this.FullscreenDialogWindowWidth - 5)+'px',*/right:0, height: '20px'};

        bh = this.FullscreenDialogWindowHeight - 65;
        if(winObj != null && !$('#' + winObj.DialogId + '-footline').length)
            bh += 38;

        this.FullscreenDialogWindowBodyCss = {
            //'border-top': '1px solid #fff',
            position: 'absolute', left: '0px', top: '27px',
            width: '100%', height: bh+'px',
            padding: '0',
            'background-color': '#fff', 'overflow-y': 'auto', 'overflow-x': 'hidden'
        };

        this.FullscreenDialogWindowFootlineCss = {
            position: 'absolute', left: '0px', top: (this.FullscreenDialogWindowHeight - 38)+'px', 'border-top': '1px solid #ccc',
            /*width: (this.FullscreenDialogWindowWidth - 6)+'px',*/right:0, height: '27px', 'text-align': 'right',
            padding: '11px 6px 0px 0px', 'background-color': '#f5f5f5'
        };

        $('.dialog-window-container').css(this.dialogWindowContainerCss);
        $('.dialog-window').css(this.dialogWindowCss);
        $('.dialog-window-headline').css(this.dialogWindowHeadlineCss);
        $('.dialog-window-body').css(this.dialogWindowBodyCss);
        $('.dialog-window-footline').css(this.dialogWindowFootlineCss);
        $('.dialog-window-fullscreen').css(this.FullscreenDialogWindowCss);
        $('.dialog-window-headline-fullscreen').css(this.FullscreenDialogWindowHeadlineCss);
        $('.dialog-window-body-fullscreen').css(this.FullscreenDialogWindowBodyCss);
        $('.dialog-window-footline-fullscreen').css(this.FullscreenDialogWindowFootlineCss);

        this.windowWidth = UIRenderer.windowWidth;
        this.windowHeight = windowHeight;
    }

    UIRenderer.resizeAll();

    this.toggleVisibility('resize');
    if (this.selected_view == 'home' && this.startPageExists)
    {
        this.startpageDisplay.createStartPage(false, [], []);
    }

};

CommonUIClass.prototype.RenderMainMenuPanel = function() {
    var panelHtml = lzm_displayHelper.RenderMainMenuPanel();
    $('#main-menu-panel').html(panelHtml).trigger('create');
    UIRenderer.resizeMenuPanels();
};

CommonUIClass.prototype.RenderViewSelectPanel = function() {

    var viewSelectArray = lzm_commonTools.clone(lzm_chatDisplay.viewSelectArray), panelContents = [];
    for (i=0; i<viewSelectArray.length; i++)
    {
        var buttonText = t(viewSelectArray[i].name), buttonIcon = '';
        switch(viewSelectArray[i].id)
        {
            case 'home':
                buttonIcon = 'fa-home';
                break;
            case 'mychats':
                buttonIcon = 'fa-comments';
                break;
            case 'tickets':
                buttonIcon = 'fa-envelope';
                break;
            case 'external':
                buttonIcon = 'fa-map';
                break;
            case 'archive':
                buttonIcon = 'fa-archive';
                break;
            case 'internal':
                buttonIcon = 'fa-users';
                break;
            case 'qrd':
                buttonIcon = 'fa-database';
                break;
            case 'reports':
                buttonIcon = 'fa-pie-chart';
                break;
        }
        var showThisView = true;

        if(IFManager.IsAppFrame && !IFManager.IsDesktopApp() && viewSelectArray[i].id == 'external' && DataEngine.getConfigValue('gl_apvm',false) != '1')
            showThisView = false;

        if (lzm_chatDisplay.showViewSelectPanel[viewSelectArray[i].id] == 0)
            showThisView = false;

        if (viewSelectArray[i].id == 'home' && (DataEngine.crc3 == null || DataEngine.crc3[1] == '-2'))
            showThisView = true;

        if (showThisView)
            panelContents.push({id: viewSelectArray[i].id, icon: buttonIcon, text: buttonText});
    }

    var panelHtml = '', buttonWidth = 0, thisButtonWidth = 0, i, showButtonText;
    var numberOfIconOnlyButtons = 0;

    for (i=0; i<panelContents.length; i++)
    {
        var vsPos = ' view-select-block';
        var buttonHtml = '<div id="%ID%view-select-' + panelContents[i].id + '" class="lzm-unselectable view-select-button' + vsPos + '"';
        buttonHtml += ' onclick="SelectView(\'' + panelContents[i].id + '\');">';
        buttonHtml += '<i class="fa ' + panelContents[i].icon + '"></i>';
        buttonHtml += '<span class="view-select-button-text">' + panelContents[i].text + '</span>';

        if($.inArray(panelContents[i].id,['external','mychats','tickets']) != -1)
            buttonHtml += '<span class="view-select-number"></span>';

        buttonHtml += '</div>';
        buttonWidth = Math.max(buttonWidth, thisButtonWidth);
        panelContents[i].html = buttonHtml.replace(/%ID%/g, '');

        if (panelContents[i].id == 'home')
            numberOfIconOnlyButtons++;
    }

    for (i=0; i<panelContents.length; i++)
    {
        panelHtml += panelContents[i].html;
    }

    $('#new-view-select-panel').html(panelHtml);

    if(CommonUIClass.LicenseMissing)
        $('#new-view-select-panel').addClass('ui-disabled');

    if(!IFManager.IsMobileOS)
    {
        $('#new-view-select-panel').unbind('mouseenter');
        $('#new-view-select-panel').mouseenter(function(){
            CommonUIClass.Hovered = true;
            $('#new-view-select-panel').css({'z-index':50000});
        });

        $('.view-select-block').click(function(){
            $(this).mouseleave();
        });

        $('#new-view-select-panel').unbind('mouseleave');
        $('#new-view-select-panel').mouseleave(function(){
            $('#new-view-select-panel').css({'z-index':0,'box-shadow':'none'});
            CommonUIClass.Hovered = false;
        });

        $('.view-select-button').unbind('mouseenter');
        $('.view-select-button').mouseenter(function(){
            var x = $(this);
            if(CommonUIClass.HoverTimeout != null)
                clearTimeout(CommonUIClass.HoverTimeout);
            CommonUIClass.HoverTimeout = setTimeout(function(){
                if(CommonUIClass.Hovered)
                    x.find('.view-select-button-text').css({display:'inline-block'});
                CommonUIClass.HoverTimeout = null;
            },1000);
            $('#new-view-select-panel').mouseenter();
        });

        $('.view-select-button').unbind('mouseleave');
        $('.view-select-button').mouseleave(function(){
            $(this).find('.view-select-button-text').css({display:'none'});
        });

        if(CommonUIClass.Hovered)
            $('#new-view-select-panel').mouseenter();

        $('#new-view-select-panel').unbind('contextmenu').on('contextmenu',function(){
            var cm = {id: 'main_panel_cm',entries: [{label: tid('settings'),onClick : 'LocalConfiguration.__ShowClientSettings(event);$(\'#settings-placeholder-tab-2\').click();'}]};
            ContextMenuClass.BuildMenu(event,cm);
            return false;
        });
    }
};

CommonUIClass.prototype.RenderTaskBarPanel = function(){
    var winObj,key,html = '';
    var currentIndex = 0;
    var minimizedView = lzm_chatDisplay.windowWidth < 500;

    for(key in TaskBarManager.Windows)
    {
        winObj = TaskBarManager.Windows[key];
        if(winObj.ShowInTaskBar)
        {
            if(winObj.TaskBarIndex == 11111)
                winObj.TaskBarIndex = currentIndex;
            currentIndex++;
        }
        winObj.TaskBarSorterIndex = lzm_commonTools.PadStart(winObj.TaskBarIndex.toString(),'0',7) + "_" + winObj.DialogId;
    }

    TaskBarManager.Windows = lzm_commonTools.SortByProperty(TaskBarManager.Windows,'TaskBarSorterIndex',false);

    var showChatControls = false;
    for(key in TaskBarManager.Windows)
    {
        winObj = TaskBarManager.Windows[key];
        if(winObj.ShowInTaskBar)
        {
            html += winObj.GetTaskBarHTML(minimizedView);
        }
        if(!winObj.Minimized && winObj.TypeId == 'chat-window')
            showChatControls = true;
    }

    var hash = md5(html);
    if(hash != CommonUIClass.LastTaskBarUpdate)
    {
        $('#task-bar-panel').html(html);
        CommonUIClass.LastTaskBarUpdate = hash;

        var tbh = $('#task-bar-panel').outerHeight();
        if(TaskBarManager.Windows.length)
        {
            $('#chat_page').css('bottom',tbh+'px');
            $('#task-bar-panel').css('display','block');
        }
        else
        {
            $('#chat_page').css('bottom',0);
            $('#task-bar-panel').css('display','none');
        }
        if (showChatControls)
        {
            $('#chat-controls').css('display','block');
        }
        else
            $('#chat-controls').css('display','none');
    }

    if(CommonUIClass.LicenseMissing)
        $('#task-bar-panel').addClass('ui-disabled');

};

CommonUIClass.prototype.RenderChatHistory = function() {

    if(ChatManager.ActiveChat=='')
        return;

    var key,mtoadd,senderName,myCurrentChat = DataEngine.ChatManager.GetChat(ChatManager.ActiveChat);
    var chatHtmlString = '',messageText = '',addClass = '';
    var messageList = [];

    myCurrentChat.SetUnread(false);
    var allChats = lzm_commonTools.GetElementByProperty(DataEngine.ChatManager.Chats,'SystemId',ChatManager.ActiveChat);

    if(allChats.length>1)
        allChats = lzm_commonTools.SortByProperty(allChats,'i',false);

    for(key in allChats)
    {
        mtoadd = lzm_commonTools.SortByProperty(allChats[key].Messages,'mtime',false);
        for(var m in mtoadd)
            messageList.push(mtoadd[m]);
    }

    var prevMessage='',cpc = new ChatPostController();
    for (var i=0; i<messageList.length; i++)
    {
        if(messageList[i].text == prevMessage && messageList[i].sen == '0000000')
            continue;

        cpc.chatObj = myCurrentChat;
        cpc.postObj = messageList[i];
        cpc = this.GetPostHTML(cpc,LocalConfiguration.UIShowAvatars,true,true);
        chatHtmlString += cpc.html;
        if (d(messageList[i].info_header))
        {
            if(messageList[i].info_header.chat_id != cpc.chatObj.i)
                chatHtmlString = chatHtmlString.replace(/header_class_placeholder/g,'TCBOLD');
        }
        prevMessage = messageList[i].text;
    }

    this.RenderChatMembers();
    this.RenderChatInfo();
    this.UpdateAutoForwardUI(myCurrentChat,false);

    if(myCurrentChat.IndicateTyping)
    {
        senderName = myCurrentChat.GetName();
        addClass = ' OOCMT';

        if(!LocalConfiguration.UIShowAvatars)
            addClass += ' NOAV';

        messageText = this.messageTemplates['internal'].replace(/<!--name-->/g,(senderName));
        messageText = messageText.replace(/<!--message-->/g, '<div class="lz_point_load"><span></span><span></span><span></span></div>');
        messageText = messageText.replace(/<!--t-->/g, addClass);
        messageText = messageText.replace(/<!--avatar-->/g, myCurrentChat.GetAvatarObject());
        chatHtmlString += messageText;
    }

    if(myCurrentChat.GetStatus() == Chat.Closed || myCurrentChat.HasDeclined(DataEngine.myId))
    {
        chatHtmlString = chatHtmlString.replace(/header_class_placeholder/g,'TCBOLD');
        chatHtmlString = chatHtmlString.replace(/info_class_placeholder/g,'SCMTOLD');
    }
    else
        chatHtmlString = chatHtmlString.replace(/header_class_placeholder/g,'');


    var thisChatProgress = $('#chat-progress');
    chatHtmlString = chatHtmlString.replace(/lz_chat_link/g, 'lz_chat_link_no_icon').replace(/lz_chat_mail/g, 'lz_chat_mail_no_icon').replace(/_no_icon_no_icon/g, '_no_icon');
    thisChatProgress.html(chatHtmlString);

    chatScrollDown(5);

    $('.CMT').contextmenu(function(event){
        CommonUIClass.TranslateChatPost = this;
        var cm = {id: 'chat_post_cm',entries: [{label: tid('translate'), onClick : 'CommonUIClass.__ShowChatMsgTranslator();'}]};
        ContextMenuClass.BuildMenu(event,cm);
        return false;
    });
    $('.last.AP').contextmenu(function(){
        showChatQuestionTranslator(this);
        return false;
    });

    $('#chat-action').css('visibility', 'visible');
    $('#chat-buttons').css('visibility', 'visible');

    UIRenderer.resizeChatView();
};

CommonUIClass.prototype.RenderChatInfo = function(_self){

    var hideInfo = true;
    var addNew = null;

    if(lzm_commonStorage.loadValue('show_chat_visitor_info_' + DataEngine.myId,1)!=0 && ChatManager.ActiveChat != '')
    {
        var visitor = VisitorManager.GetFullDataVisitor(ChatManager.ActiveChat);

        if(visitor == null)
            visitor = VisitorManager.GetVisitor(ChatManager.ActiveChat);

        if(visitor != null)
        {
            hideInfo = false;
            if($('#visitor-info-e-'+visitor.id+'-placeholder').length > 0)
            {

                $('.embedded-visitor-info').css('display','none');
                $('#visitor-info-e-'+visitor.id+'-placeholder').css('display','block');
            }
            else
            {
                addNew = visitor.id;
            }
        }
    }

    $('#chat-info-body').data('hidden', (hideInfo ? '1' : '0'));

    if(!hideInfo)
        $('#chat-info-body').css({display:'block'});

    if(addNew != null)
    {
        $('#chat-info-elements').append('<div id="visitor-info-e-'+visitor.id+'-placeholder" class="embedded-visitor-info" data-visitor-id="'+visitor.id+'"></div>');
        ChatVisitorClass.ShowVisitorInformation(visitor.id, 0, '','', false);
        if(!_self)
            this.RenderChatInfo(true);
    }
    UIRenderer.resizeMychats();
};

CommonUIClass.prototype.RenderChatInternal = function() {

    $('#visitor-info').html('<div id="visitor-info-headline"><h3>' + t('Visitor Information') + '</h3></div><div id="visitor-info-headline2"></div>').trigger('create');
    $('#chat').css('display', 'block');
    $('#errors').css('display', 'none');

    SetEditorDisplay('block');

    $('#chat-progress').css('display', 'block');
    $('#chat-action').css('display', 'block');

    var thisChatButtons = $('#chat-buttons');
    var chatButtonsHtml = '<div style="margin: 6px 0;">';
    chatButtonsHtml += lzm_inputControls.CreateInputControlPanel();
    chatButtonsHtml += lzm_inputControls.createButton('visitor-chat-actions', '', 'showVisitorChatActionContextMenu(\'' + ChatManager.ActiveChat + '\', \'actions\', event);', tid('actions'), '<i class="fa fa-wrench"></i>', 'lr', {'margin-left': '-1px'}, '', '','e');
    chatButtonsHtml += '<span style="float:right">'+lzm_inputControls.createButton('send-chat-btn', '', 'chatInputEnterPressed()', t('Send'), '<i class="fa fa-send"></i>', 'lr', {'padding-left': '10px', 'padding-right': '10px', 'margin-right': '4px'}, tid('send'),'','e');
    chatButtonsHtml += '</span></div>';
    thisChatButtons.html(chatButtonsHtml).trigger('create').css('display', 'block');

    $('.lzm-button').mouseenter(function() {
        $(this).css('background-image', $(this).css('background-image').replace(/linear-gradient\(.*\)/,'linear-gradient(#f6f6f6,#e0e0e0)'));
    });
    $('.lzm-button').mouseleave(function() {
        $(this).css('background-image', $(this).css('background-image').replace(/linear-gradient\(.*\)/,'linear-gradient(#ffffff,#f1f1f1)'));
    });
};

CommonUIClass.prototype.RenderChatVisitorActivated = function() {

    var thisUserChat = DataEngine.ChatManager.GetChat();
    var thisChatProgress = $('#chat-progress');
    var thisChatTable = $('#chat-table');
    var thisChatButtons = $('#chat-buttons');

    thisChatTable.css('display', 'block');

    var member = thisUserChat.GetMember(DataEngine.myId);

    if (thisUserChat.GetStatus() != Chat.Closed && member.s != 2)
        SetEditorDisplay('block');
    else
        SetEditorDisplay('none');

    thisChatProgress.css('display', 'block');

    var openChatHtmlString = '';
    var visitorChat = thisUserChat.v + '~' + thisUserChat.b + '~' + thisUserChat.i;

    if (thisUserChat != null && member != null)
    {
        openChatHtmlString += '<div style="margin: 6px 0;">';
        var disabledClass = '';

        if (thisUserChat.GetStatus()==Chat.Closed || thisUserChat.HasDeclined(DataEngine.myId))
            disabledClass += 'ui-disabled ';

        var hiddenClass = (member.s != '0') ? 'disabled-chat-button ui-disabled ' : '';

        if (member.s != 2)
            openChatHtmlString += lzm_inputControls.CreateInputControlPanel('', disabledClass);

        var visitorLanguage = DataEngine.userLanguage;
        try
        {
            visitorLanguage = ($.inArray(thisUserChat.Visitor.lang, this.translationLangCodes) != -1) ? thisUserChat.Visitor.lang : thisUserChat.Visitor.lang.split('-')[0].split('_')[0];
        }
        catch(e) {}

        if(visitorLanguage.toUpperCase()=='EN' && thisUserChat.Visitor.ctryi2 != '' && thisUserChat.Visitor.ctryi2.toUpperCase() != 'EN')
            visitorLanguage = this.getCountryLanguage(thisUserChat.Visitor.ctryi2);

        if (member.s != 2)
            openChatHtmlString += lzm_inputControls.createButton('translate-chat', hiddenClass + disabledClass,'showTranslateOptions(\'' + visitorChat + '\', \'' + visitorLanguage + '\');', '', '<i class="fa fa-lg fa-language"></i>', 'lr',  {'margin-left': '-1px'}, t('Translate'),-1,'e');

        openChatHtmlString += lzm_inputControls.createButton('visitor-chat-actions', '', 'showVisitorChatActionContextMenu(\'' + thisUserChat.v + '~' + thisUserChat.b + '\', \'actions\', event);', tid('actions'), '<i class="fa fa-wrench"></i>', 'lr', {'margin-left': '4px'},'','','e',1150);

        if (member.s != 2)
            openChatHtmlString += '<span style="float:right">'+lzm_inputControls.createButton('send-chat-btn', '', 'SendTranslatedChat(grabEditorContents())', t('Send'), '<i class="fa fa-send"></i>', 'lr', {'padding-left': '10px', 'padding-right': '10px','margin-right': '4px'}, t('Send'),'','e',1150)+'</span>';

        openChatHtmlString += '</div>';
    }

    thisChatButtons.html(openChatHtmlString).trigger("create");
    this.UpdateTranslateButtonUI(visitorChat);
    thisChatButtons.css('display', 'block');

    $('.lzm-button').mouseenter(function() {
        $(this).css('background-image', $(this).css('background-image').replace(/linear-gradient\(.*\)/,'linear-gradient(#f6f6f6,#e0e0e0)'));
    });
    $('.lzm-button').mouseleave(function() {
        $(this).css('background-image', $(this).css('background-image').replace(/linear-gradient\(.*\)/,'linear-gradient(#ffffff,#f1f1f1)'));
    });
};

CommonUIClass.prototype.RenderChatVisitorOpen = function(){

    clearEditorContents();

    var thisChatButtons = $('#chat-buttons');

    SetEditorDisplay('none');

    $('#chat-progress').css('display', 'block');

    var noOpenChatHtmlString = '';
    var thisUserChat = DataEngine.ChatManager.GetChat();
    var acceptString = t('Start Chat');

    if (thisUserChat != null)
    {
        var disabledClass = '';

        if (thisUserChat.AcceptInitiated || thisUserChat.GetStatus()==Chat.Closed || thisUserChat.HasDeclined(DataEngine.myId) || !thisUserChat.IsMember(DataEngine.myId))
            disabledClass = 'ui-disabled ';

        noOpenChatHtmlString += '<div style="margin: 6px 0;">';
        noOpenChatHtmlString += lzm_inputControls.createButton('accept-chat', disabledClass, '', acceptString, '<i class="fa fa-check"></i>', 'force-text',{padding:'4px 10px 4px 6px','margin-left': '4px'}, t('Start Chat'), 20, 'd');
        noOpenChatHtmlString += lzm_inputControls.createButton('decline-chat', disabledClass, '', tid('decline'), '<i class="fa fa-remove"></i>', 'lr', {'margin-left': '4px'}, tid('decline'),20,'e');
        noOpenChatHtmlString += lzm_inputControls.createButton('show-visitor-info', '', 'ChatVisitorClass.ShowVisitorInformation(\'' + thisUserChat.v + '\');', '', '<i class="fa fa-info"></i>', 'lr',{'margin-left': '4px'}, t('Show information'),20,'e');
        noOpenChatHtmlString += lzm_inputControls.createButton('forward-chat', disabledClass, '', '', '<i class="fa fa-arrow-circle-right"></i>', 'lr',{'margin-left': '-1px'}, tid('forward'),20,'e');

        noOpenChatHtmlString += '</div>';
        thisChatButtons.html(noOpenChatHtmlString).trigger("create");
        thisChatButtons.css('display', 'block');
    }
    else
        thisChatButtons.html(noOpenChatHtmlString).trigger("create");
};

CommonUIClass.prototype.RenderChatMembers = function(){
    try
    {
        var hideMembers = true;
        if(ChatManager.ActiveChat != '')
        {
            var chat = DataEngine.ChatManager.GetChat(ChatManager.ActiveChat);

            if(chat==null)
                return;

            var objid = ChatManager.ActiveChat, memberList = [], addedList = [],operators,operator = null;
            var chatIsOnline = true, displayMinimized = false;

            if($('#chat-container').width()<500 && $.inArray(objid+"AUTOMIN",this.minimizedMemberLists)==-1){
                this.minimizedMemberLists.push(objid+"AUTOMIN");
                this.minimizedMemberLists.push(objid);
            }

            if($.inArray(objid,this.minimizedMemberLists)>-1)
                displayMinimized = true;

            this.memberListWidth = (displayMinimized) ? 0 : 190;

            try
            {
                if($('#chat-container').width()>260)
                {
                    if(chat.Type==Chat.Visitor && ChatManager.ActiveChat == chat.SystemId)
                    {
                        memberList = lzm_commonTools.clone(chat.Members);
                        memberList.push({id:chat.SystemId});
                    }
                    else if(chat.Type == Chat.ChatGroup)
                    {
                        var group = DataEngine.groups.getGroup(chat.SystemId);
                        if(group != null)
                        {
                            if(!d(group.members))
                            {
                                operators = DataEngine.operators.getOperatorList();
                                for (i=0; i<operators.length; i++)
                                {
                                    if ($.inArray(ChatManager.ActiveChat, operators[i].groups) != -1)
                                    {
                                        if(!operators[i].isbot)
                                        {
                                            memberList.push({id:operators[i].id});
                                            chatIsOnline = true;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                for(var key in group.members)
                                {
                                    addedList.push(group.members[key].id);
                                    memberList.push(group.members[key]);
                                }
                            }
                        }
                        else if(chat.SystemId=='everyoneintern')
                        {
                            operators = DataEngine.operators.getOperatorList();
                            for (i=0; i<operators.length; i++)
                            {
                                if(!operators[i].isbot)
                                {
                                    memberList.push({id:operators[i].id});
                                    chatIsOnline = true;
                                }
                            }
                        }
                    }
                    else if(chat.Type == Chat.Operator)
                    {
                        memberList.push({id:chat.SystemId});
                        memberList.push({id:DataEngine.myId});
                    }
                }
            }
            catch(e)
            {
                deblog(e);
            }

            if(memberList.length > 0 && chatIsOnline)
            {
                hideMembers = false;
                $('#chat-members').css('display','block');
                $('#chat-progress, #chat-action, #chat-buttons').css({left: this.memberListWidth + 'px'});
                $('#chat-qrd-preview').css({left: this.memberListWidth + 'px'});
                var membersHtml = '', operatorsHTML = '';
                var nameWidth = ' style="width:' + (this.memberListWidth) + 'px;"';
                var addedMembers = [];

                for(var i = 0;i<memberList.length;i++)
                {
                    var membId = (typeof memberList[i].i != 'undefined') ? memberList[i].i : memberList[i].id;
                    var hasDeclined = (typeof memberList[i].d != 'undefined' && memberList[i].d=='1');
                    var isInv = (typeof memberList[i].s != 'undefined' && memberList[i].s=='2');
                    var isHidden = (isInv && membId != DataEngine.myId);

                    if(!isHidden && $.inArray(membId,addedMembers)===-1)
                    {
                        addedMembers.push(membId);
                        operator = DataEngine.operators.getOperator(membId);
                        var chatObj = DataEngine.ChatManager.GetChat(membId);

                        if(operator != null)
                        {
                            var icon = '';

                            if(hasDeclined)
                                icon = '<i class="fa fa-times icon-member-status icon-orange"></i>';
                            else if(isInv)
                                icon = '<i class="fa fa-eye icon-member-status icon-blue"></i>';
                            else
                                icon = '<span class="operator-list-icon" style="background-image: url(\'' + this.lzm_commonConfig.lz_user_states[operator.status].icon + '\');"></span>';

                            operatorsHTML += '<div '+nameWidth+' id="'+(chat.SystemId+'-'+i)+'" class="lzm-unselectable chat-member-div '+chat.SystemId+'">'+icon+lzm_commonTools.SubStr(operator.name,22,true)+'</div>';
                        }
                        else if(chatObj != null)
                        {
                            membersHtml += '<div '+nameWidth+' id="'+(chat.SystemId+'-'+i)+'" class="lzm-unselectable chat-member-div '+chat.SystemId+'"><span class="user-list-icon"><i class="fa fa-user icon-light"></i></span>'+chatObj.GetName()+'</div>';
                        }
                    }
                }

                if(operatorsHTML != '')
                    operatorsHTML = '<div class="chat-member-div chat-member-split-div"><b>'+t('Operators')+'</b></div>' + operatorsHTML;

                if(membersHtml != '')
                    membersHtml = '<div class="chat-member-div chat-member-split-div"><b>'+t('Visitors')+'</b></div>' + membersHtml;

                $('#chat-members-list').html(operatorsHTML + membersHtml);
                $('#chat-members-list').css({display:(displayMinimized) ? 'none' : 'block'});
                $('#chat-members').css({'z-index':(displayMinimized) ? 'auto' : '1',height:(displayMinimized) ? '136px': '', top: (displayMinimized) ? '' : '0', width: (displayMinimized) ? '19px' : this.memberListWidth + 'px'});
                $('#chat-members-minimize').css({display:'block'});
                $('#chat-buttons').css({'padding-left': (displayMinimized) ? '20px' : 0});
                UIRenderer.resizeChatView();
            }
            else
                hideMembers = true;
        }

        if(hideMembers)
        {
            $('#chat-members-minimize, #chat-members').css({display:'none'});
            $('#chat-progress, #chat-action, #chat-buttons').css({left: 0});
            $('#chat-buttons').css({'padding-left': 0});
        }
        $('#chat-members-minimize i').attr('class',(displayMinimized) ? 'fa fa-chevron-right' : 'fa fa-chevron-left');
    }
    catch(e){deblog(e);}
};

CommonUIClass.prototype.UpdateViewSelectPanel = function(){

    var bo = (CommonUIClass.BlockNavigation) ? '' : '(';
    var bc = (CommonUIClass.BlockNavigation) ? '' : ')';

    $('.view-select-button').removeClass('view-select-button-selected')
                            .removeClass('view-select-button-notify');

    // CHATS
    var notifyNewMessage = DataEngine.ChatManager.GetQueued().length>0;
    if(notifyNewMessage)
    {
        blinkPageTitle(tid('new_chat_activity'));
    }

    if(notifyNewMessage)
        $('#view-select-mychats').find('.view-select-number').addClass('view-select-number-notify');
    else
        $('#view-select-mychats').find('.view-select-number').removeClass('view-select-number-notify');

    // TICKETS
    if(lzm_chatDisplay.ticketDisplay.notifyNewTicket && lzm_chatDisplay.selected_view != 'tickets')
        $('#view-select-tickets').find('.view-select-number').addClass('view-select-number-notify');
    else
        $('#view-select-tickets').find('.view-select-number').removeClass('view-select-number-notify');

    var numberOfUnreadTickets = (d(lzm_chatDisplay.ticketGlobalValues.q)) ? lzm_chatDisplay.ticketGlobalValues.q : 0;
    //var numberOfEmails = (d(lzm_chatDisplay.ticketGlobalValues.e)) ? lzm_chatDisplay.ticketGlobalValues.e : 0;

    // SET
    var ti = numberOfUnreadTickets > 999 ? Math.floor(numberOfUnreadTickets/1000)+'k' : numberOfUnreadTickets;
    $('#view-select-mychats').find('.view-select-number').html(bo + ((!d(ChatManager.Counts.AllActive)) ? '0' : ChatManager.Counts.AllActive) + bc).css('display',(ChatManager.Counts.AllActive > 0) ? 'block' : 'none');
    $('#view-select-tickets').find('.view-select-number').html(bo + ti + bc).css('display',(ti != 0) ? 'block' : 'none');
    $('#view-select-external').find('.view-select-number').html(bo + VisitorManager.ActiveVisitors + bc).css('display',(VisitorManager.ActiveVisitors > 0) ? 'block':'none');
    //$('#view-select-archive').find('.view-select-number').html(bo + DataEngine.chatArchive.t + bc);
    //$('#view-select-reports').find('.view-select-number').html(bo + DataEngine.reports.totalReports + bc);

    if(IFManager.IsDesktopApp() && d(IFManager.DeviceInterface.hasMinVersion) && IFManager.DeviceInterface.hasMinVersion('1.2.0')){
        IFManager.IFSetToolTipCounts({
            chatCount: ((!d(ChatManager.Counts.AllActive)) ? '0' : ChatManager.Counts.AllActive),
            visitorCount: VisitorManager.ActiveVisitors,
            ticketCount: numberOfUnreadTickets
        });
    }

    if(TaskBarManager.GetActiveWindow()==null)
    {
        $('#view-select-' + lzm_chatDisplay.selected_view).addClass('view-select-button-selected');
    }
};

CommonUIClass.prototype.IsFullscreenMode = function(){
    return (this.windowHeight > 450 && this.windowWidth > 575 && !IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp();
};

CommonUIClass.prototype.ProcessChatUpdates = function(_oldChatObj,_newChatObj,_updateUI){

    var poll=false;
    _updateUI = (d(_updateUI)) ? _updateUI : false;

    if(_oldChatObj==null)
    {
        _updateUI = 1;
        if(_newChatObj.GetStatus() != Chat.Closed)
        {
            //ChatManager.DLChatMessagesList.push(_newChatObj.i);
            //poll = true;
        }
    }
    else
    {
        if(_updateUI && _oldChatObj.Messages.length == _newChatObj.Messages.length)
            _updateUI = false;

        if(_newChatObj.IsAccepted() && !_oldChatObj.IsAccepted())
        {
            _updateUI = 2;
            addAcceptedMessageToChat(_newChatObj);

            if(_newChatObj.GetMember(DataEngine.myId) != null && _newChatObj.AutoAcceptMessage != null)
                setTimeout(function() {SendTranslatedChat(_newChatObj.AutoAcceptMessage,_newChatObj.SystemId);}, 500);
        }

        if(_newChatObj.GetStatus() != _oldChatObj.GetStatus())
        {
            _updateUI = 3;
            if(_newChatObj.GetStatus()==Chat.Closed && _oldChatObj.GetStatus() != Chat.Closed)
            {
                if(_newChatObj.ClosedBy()==Chat.Operator)
                    addOperatorLeftMessageToChat(_newChatObj,[_newChatObj.GetMember(DataEngine.myId)]);
                if(_newChatObj.ClosedBy()==Chat.Visitor)
                    addLeftMessageToChat(_newChatObj,_newChatObj.GetName(),"");

                if(DataEngine.getConfigValue('gl_acco',false) == '1' || DataEngine.getConfigValue('gl_accc',false) == '1')
                {
                    // download system added messages
                    ChatManager.DLChatMessagesList.push(_newChatObj.i);
                    CommunicationEngine.InstantPoll();
                }
            }

            if(!_oldChatObj.HasDeclined(DataEngine.myId) && _newChatObj.HasDeclined(DataEngine.myId))
            {
                addOperatorLeftMessageToChat(_newChatObj,[_newChatObj.GetMember(DataEngine.myId)]);
            }
        }

        if(_newChatObj.IsDeclined() && !_oldChatObj.IsDeclined())
        {
            _updateUI = 4;
        }

        var m,joins = [];
        for(m in _newChatObj.Members)
        {
            var oldMember = lzm_commonTools.GetElementByProperty(_oldChatObj.Members,'i',_newChatObj.Members[m].i);
            if(!oldMember.length)
            {
                joins.push(_newChatObj.Members[m].i);
                if(_newChatObj.Members[m].i == DataEngine.myId)
                {
                    ChatManager.DLChatMessagesList.push(_newChatObj.i);
                    poll = true;
                }
            }
            else
            {
                if(oldMember[0].d != _newChatObj.Members[m].d)
                    _updateUI = 5;

            }
        }

        var leaves = [];
        for(m in _oldChatObj.Members)
            if(!lzm_commonTools.GetElementByProperty(_newChatObj.Members,'i',_oldChatObj.Members[m].i).length)
                leaves.push(_oldChatObj.Members[m]);

        if(joins.length)
            addOperatorJoinedMessageToChat(_newChatObj,joins);
        if(leaves.length)
            addOperatorLeftMessageToChat(_newChatObj,leaves);

        if(joins.length || leaves.length)
            _updateUI = 6;

        _newChatObj.IndicateTyping = _newChatObj.t=='1' && _newChatObj.GetStatus() != Chat.Closed;

        if(_newChatObj.t != _oldChatObj.t)
            _updateUI = 7;
    }

    if(isAutoAcceptActive() && !_newChatObj.IsAccepted() && _newChatObj.GetMember(DataEngine.myId) != null && _newChatObj.GetMember(DataEngine.myId).s != Chat.StatusFollowerInvisible)
        UserActions.AcceptChat(_newChatObj,true);

    if(_newChatObj.IsUnread && _newChatObj.GetMember(DataEngine.myId)==null)
        _newChatObj.SetUnread(false);

    if(_newChatObj.WasInPublicChatGroup && _newChatObj.GetStatus()==Chat.Closed)
    {
        _newChatObj.CloseChatWindow();
        _newChatObj.WasInPublicChatGroup = false;
    }

    if(poll)
        CommunicationEngine.InstantPoll();

    if(ChatManager.ActiveChat == _newChatObj.SystemId && (_newChatObj.IsUnread||_updateUI))
    {
        OpenChatWindow(_newChatObj.SystemId);
    }

    if(_updateUI)
        this.UpdateViewSelectPanel();
};

CommonUIClass.prototype.showUsersettingsMenu = function(){
    $('#userstatus-menu').css('display', 'none');
    $('#minified-dialogs-menu').css('display', 'none');
    this.showUserstatusHtml = false;
    var tableWidth = $('#main-menu-panel-settings').width();
    var thisUsersettingsMenu = $('#usersettings-menu');
    var usersettingsMenuHtml = '<table style="min-width: ' + tableWidth + 'px;" class="lzm-unselectable">';
    usersettingsMenuHtml += '<tr><td onclick="LocalConfiguration.__ShowClientSettings(event);">' + tid('client_configuration') + '</td></tr>';
    usersettingsMenuHtml += '<tr><td onclick="showFilterList(event);">' + t('Filters') + '</td></tr>';
    usersettingsMenuHtml += '<tr><td onclick="ChangePassword(event);">' + t('Change Password') + '</td></tr>';
    usersettingsMenuHtml += '<tr><td onclick="personalChatLink();">' + tid('per_c_link') + '</td></tr>';
    usersettingsMenuHtml += '<tr><td class="usersettings-menu-spacer"></td></tr>';
    if(!IFManager.IsMobileOS)
    {
        usersettingsMenuHtml += '<tr><td onclick="showTranslationEditor(event);">' + t('Translation Editor') + '</td></tr>';
        usersettingsMenuHtml += '<tr><td onclick="showUserManagement(event);">' + t('User Management') + '</td></tr>';
        var dc = (DataEngine.m_ServerConfigBlocked) ? ' class="ui-disabled' : '';
        usersettingsMenuHtml += '<tr><td'+dc+' onclick="initServerConfiguration(event);">' + tid('server_conf') + '</td></tr>';
        usersettingsMenuHtml += '<tr><td onclick="initLinkGenerator(event);">' + tid('link_generator') + '</td></tr>';
        usersettingsMenuHtml += '<tr><td onclick="initDataExport(event);" style="display:none;">' + tid('export') + '</td></tr>';
    }
    usersettingsMenuHtml += '<tr><td onclick="initEventConfiguration(event);">' + tid('events') + '</td></tr>';
    usersettingsMenuHtml += '<tr><td onclick="initFeedbacksConfiguration(event);">' + tid('feedbacks') + '</td></tr>';
    usersettingsMenuHtml += '<tr><td onclick="showLogs();">Logs</td></tr>';
    usersettingsMenuHtml += '<tr><td class="usersettings-menu-spacer"></td></tr>';
    usersettingsMenuHtml += '<tr><td onclick="logout(true, false, event);">' + t('Log out') + '</td></tr>';

    if(IFManager.IsDesktopApp())
    {
        usersettingsMenuHtml += '<tr><td class="usersettings-menu-spacer"></td></tr>';
        usersettingsMenuHtml += '<tr><td onclick="IFManager.ExitApp=true;logout(true,false);">' + tid('close_application') + '</td></tr>';
    }

    usersettingsMenuHtml += '</table>';
    thisUsersettingsMenu.html(usersettingsMenuHtml);
    thisUsersettingsMenu.css({display: 'block'});
};

CommonUIClass.prototype.createCommentHtml = function(type, line, commentText, operatorName, operatorId, time){
    var commentHtml = '';
    var avatar = '<div style="background-image: url(\'./../picture.php?operator='+encodeURIComponent(operatorId)+'\');"></div>';
    if(type=='ticket')
        commentHtml = '<tr id="comment-line-' + line + '" class="comment-line lzm-unselectable" style="cursor:pointer;" onclick="handleTicketCommentClick(' + line + ', \'' + lz_global_base64_encode(commentText) + '\');"><td style="width:50px;" class="CMTP">' + avatar + '</td><td style="vertical-align: top;"><span class="comment-line-date">' + time + '</span><br><span><b>' + operatorName + '</b></span><div>' + lzm_commonTools.escapeHtml(commentText) +'</div></td></tr>';
    else
        commentHtml = '<tr onclick="handleVisitorCommentClick(' + line + ');" style="cursor: pointer;" id="visitor-comment-line-' + line + '" class="comment-line lzm-unselectable" data-comment-no="' + line + '"><td style="width:50px;" class="CMTP">' + avatar + '</td><td style="vertical-align: top;"><span class="comment-line-date">' + time + '</span><br><span><b>' + operatorName + '</b></span><div>' + lzm_commonTools.escapeHtml(commentText) + '</div></td></tr>';
    return commentHtml;
};

CommonUIClass.prototype.showUserstatusMenu = function(){
    $('#usersettings-menu').css('display', 'none');
    $('#minified-dialogs-menu').css('display', 'none');
    this.showUsersettingsHtml = false;

    var click,tableWidth = $('#main-menu-panel-settings').width();
    var thisUserstatusMenu = $('#userstatus-menu');
    var userstatusMenuHtml = '<table style="min-width: ' + tableWidth + 'px;">';
    for (var statusIndex = 0; statusIndex < this.lzm_commonConfig.lz_user_states.length; statusIndex++)
    {
        if (this.lzm_commonConfig.lz_user_states[statusIndex].index != 2)
        {
            click = ' onclick="setUserStatus(' + this.lzm_commonConfig.lz_user_states[statusIndex].index + ', event)"';
            userstatusMenuHtml += '<tr><td class="lzm-unselectable"' + click + '><img src="' + this.lzm_commonConfig.lz_user_states[statusIndex].icon + '"></td>';
            userstatusMenuHtml += '<td' + click + '>' + t(this.lzm_commonConfig.lz_user_states[statusIndex].text) + '</td></tr>';
        }
    }
    userstatusMenuHtml += '</table>';
    thisUserstatusMenu.html(userstatusMenuHtml);
    thisUserstatusMenu.css({display: 'block'});
};

CommonUIClass.prototype.setUserStatus = function(statusValue){
    $('#userstatus-menu').css('display', 'none');
    this.showUserstatusHtml = false;
    ChatPollServerClass.__UserStatus = statusValue;
    var statusIcon = lzm_commonConfig.lz_user_states[2].icon;
    for (var i=0; i<lzm_commonConfig.lz_user_states.length; i++)
        if (lzm_commonConfig.lz_user_states[i].index == ChatPollServerClass.__UserStatus)
            statusIcon = lzm_commonConfig.lz_user_states[i].icon;
    $('#main-menu-panel-status').css({'background-image': 'url(\'' + statusIcon + '\')'});
};

CommonUIClass.prototype.UpdateTranslateButtonUI = function(_visitorChat){
    var highlight = false;
    if (d(lzm_chatDisplay.chatTranslations[_visitorChat]))
        highlight = ((lzm_chatDisplay.chatTranslations[_visitorChat].tvm != null && lzm_chatDisplay.chatTranslations[_visitorChat].tvm.translate)
            || (lzm_chatDisplay.chatTranslations[_visitorChat].tmm != null && lzm_chatDisplay.chatTranslations[_visitorChat].tmm.translate));

    if(highlight && _visitorChat.indexOf(ChatManager.ActiveChat) === 0)
        $('#translate-chat').addClass('lzm-button-b-active');
    else
        $('#translate-chat').removeClass('lzm-button-b-active');
};

CommonUIClass.prototype.createChatMemberActionMenu = function(object){
    var contextMenuHtml = '', disabledClass = '';

    if(object.browserId.length==0)
    {
        contextMenuHtml += '<div onclick="chatInternalWith(\''+object.userId+'\',\'\',\'\');removeChatMembersListContextMenu();"><span id="chat-show-info" class="cm-line cm-click">' + tid('start_chat') + '</span></div>';
    }
    else
    {
        var activeUserChat = DataEngine.ChatManager.GetChat(object.userId + '~' + object.browserId);
        var gc = activeUserChat.GetChatGroup();
        var cRemove = 'removeFromChatGroup(\''+activeUserChat.SystemId+'\', \''+gc.id+'\');';
        var cTake = 'takeChat(\''+activeUserChat.v+'\',\''+activeUserChat.b+'\',\''+activeUserChat.i+'\', \''+activeUserChat.dcg+'\');';

        disabledClass = (gc==null || ChatPollServerClass.__UserStatus == 3) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="'+cRemove+cTake+';removeChatMembersListContextMenu();"><span id="chat-show-info" class="cm-line cm-click">' + tid('take') + '</span></div>';

        disabledClass = (activeUserChat.GetStatus() != Chat.Closed) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="closeChat(\''+activeUserChat.i+'\',\''+activeUserChat.v+'\',\''+activeUserChat.b+'\',true);removeChatMembersListContextMenu();"><span id="chat-show-info" class="cm-line cm-click">' + tid('close') + '</span></div>';

        disabledClass = (activeUserChat.GetStatus() == Chat.Waiting) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="declineChat(\''+activeUserChat.v+'\', \''+activeUserChat.b+'\', \''+activeUserChat.i+'\');removeChatMembersListContextMenu();"><span id="chat-show-info" class="cm-line cm-click">' + tid('decline') + '</span></div>';

        contextMenuHtml += '<div onclick="showFilterCreation(\'visitor\',\''+activeUserChat.v+'\');removeChatMembersListContextMenu();"><span id="chat-show-info" class="cm-line cm-click">' + tid('ban_add_filter') + '</span></div>';
    }
    return contextMenuHtml;
};

CommonUIClass.prototype.createChatActionMenu = function(myObject){
    var disabledClass, contextMenuHtml = '';

    disabledClass = (myObject.Type != Chat.Visitor) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="ChatVisitorClass.ShowVisitorInformation(\'' + myObject.v + '\');removeVisitorChatActionContextMenu();"><span id="chat-show-info" class="cm-line cm-click">' + t('Details') + '</span></div><hr />';

    contextMenuHtml += '<div onclick="KnowledgebaseUI.AddToChat(KnowledgebaseUI.TypeFile);removeVisitorChatActionContextMenu();"><span id="chat-send-file" class="cm-line cm-click">' + t('Send File') + '</span></div>';
    if(IFManager.IsDesktopApp() && d(IFManager.DeviceInterface.hasModule) && IFManager.DeviceInterface.hasModule('screenshot', 2)){
        contextMenuHtml += '<div onclick="IFManager.IFScreenCast(\'chat\',\'' + TaskBarManager.GetActiveWindow().DialogId + '\');removeVisitorChatActionContextMenu();"><span id="chat-send-screenshot" class="cm-line cm-click">' + tid('screenshot') + '</span></div>';
    }

    if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
        contextMenuHtml += '<div onclick="addLinkToChat();"><span id="chat-send-link" class="cm-line cm-click">' + t('Send Url') + '</span></div>';

    disabledClass = (!(myObject.Type == Chat.Visitor && myObject.Visitor != null && DataEngine.inputList.getInputValueFromVisitor(116,myObject.Visitor) != '')) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="showPhoneCallDialog(\'' + myObject.v + '~' + myObject.b + '\', -1, \'chat\');removeVisitorChatActionContextMenu();"><span id="chat-start-phone-call" class="cm-line cm-click">' + t('Phone Call') + '</span></div><hr />';

    var memberStatus = myObject.GetMemberStatus(DataEngine.myId);
    disabledClass = (myObject.Type != Chat.Visitor || memberStatus == Chat.StatusFollowerInvisible) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="forwardChat(\'' + myObject.i + '\', \'forward\');removeVisitorChatActionContextMenu();"><span id="chat-forward-chat" class="cm-line cm-click">' + t('Forward Chat') + '</span></div>';

    disabledClass = (myObject.Type != Chat.Visitor) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="forwardChat(\'' + myObject.i + '\', \'invite\');removeVisitorChatActionContextMenu();"><span id="chat-invite-operator" class="cm-line cm-click">' + t('Invite Operator') + '</span></div>';

    disabledClass = (myObject.Type == Chat.ChatGroup) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="addToChatGroup(\'' + myObject.v + '\', \'' + myObject.b + '\', \'' + myObject.i + '\');removeVisitorChatActionContextMenu();"><span class="cm-line cm-click">' + tid('group_chat_add') + '</span></div><hr />';

    disabledClass = (myObject.Type != Chat.Visitor) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="showFilterCreation(\'visitor\',\'' + myObject.v + '\');removeVisitorChatActionContextMenu();"><span id="chat-add-filter" class="cm-line cm-click">' + t('Ban (add filter)') + '</span></div><hr />';

    disabledClass = (!DataEngine.groups.isChatGroup(myObject.SystemId)) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="leaveChat();removeFromChatGroup(\''+DataEngine.myId+'\', \''+myObject.SystemId+'\');removeVisitorChatActionContextMenu();"><span id="chat-leave-group" class="cm-line cm-click">' + tid('leave_group') + '</span></div>';

    if(!IFManager.IsMobileOS)
        contextMenuHtml += '<div onclick="Chat.ChangePaste();"><span id="chat-change-paste" class="cm-line cm-click">' + Chat.GetChangePasteText() + '</span></div><hr />';

    disabledClass = (myObject.Type != Chat.Visitor) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="Chat.CreateTicket(\''+myObject.i+'\');removeVisitorChatActionContextMenu();"><span id="chat-add-filter" class="cm-line cm-click">' + tid('create_ticket') + '</span></div>';

    disabledClass = (myObject.Type != Chat.Visitor) ? ' class="ui-disabled"' : ' class="text-bold text-white bg-red nobg"';
    contextMenuHtml += '<hr /><div' + disabledClass + ' onclick="leaveChat();removeVisitorChatActionContextMenu();"><span id="chat-leave-chat" class="cm-line cm-click">' + tid('leave_chat') + '</span></div>';

    return contextMenuHtml;
};

CommonUIClass.prototype.catchEnterButtonPressed = function(e){
    var that = this, thisChatInput = $('#chat-input');
    if (e.which == 13 || e.keyCode == 13)
    {
        try {
            var useResource = '';
            for (var i=0; i<KnowledgebaseUI.ShortCutResources.length; i++) {
                if (KnowledgebaseUI.ShortCutResources[i].complete) {
                    useResource = KnowledgebaseUI.ShortCutResources[i].id;
                    break;
                }
            }
            if (useResource != '')
            {
                var resource = DataEngine.cannedResources.getResource(useResource);
                if (resource != null && $.inArray(resource.ty, ['2', '3', '4']) != -1 && ((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()) && ChatManager.ActiveChat != '')
                    sendQrdPreview(useResource, ChatManager.ActiveChat);
                else if (resource != null && $.inArray(resource.ty, ['2', '3', '4']) != -1 && ((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()))
                {

                }
                else
                    KnowledgebaseUI.UseAutoSearchResult('chat',useResource);
            }
            else if (thisChatInput.val().indexOf('/') == 0)
            {

            }
            else
            {
                SendTranslatedChat(grabEditorContents());
            }
        } catch(ex) {}
        e.preventDefault();
    }
    if (e.which == 10 || e.keyCode == 10) {
        var tmp = thisChatInput.val();
        thisChatInput.val(tmp + '\n');
    }
};

CommonUIClass.prototype.searchButtonChange = function(type){
    $('#search-'+type).css('background',($('#search-'+type).val().length) ? '#ffffe1' : '#fff');
};

CommonUIClass.prototype.searchButtonUp = function(type, myObjects, event, inDialog, _instant) {

    if(d(event) && event != null)
        event.stopPropagation();

    var thisClass = this,  searchString = '';
    var sid = (inDialog) ? 'd-' : '';
    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;
    _instant = (d(_instant)) ? _instant : false;

    if ((d(event) && event != null && (event.which == 13 || event.keycode == 13 || event.charCode == 13)) || _instant)
    {
        thisClass.searchButtonUpSet[type] = 0;
        switch (type)
        {
            case 'operators':
                CommonUIClass.UpdateUserList = true;
                thisClass.CreateOperatorList();
                break;
            case 'qrd':
                thisClass.resourcesDisplay.highlightSearchResults(myObjects,true);
                break;
            case 'ticket':
                searchString = $('#search-ticket').val();
                searchTickets(searchString);
                break;
            case 'archive':
                searchString = $('#search-archive').val();
                if (searchString != '')
                {
                    $('#clear-archive-search').css({display: 'inline'});
                    thisClass.archiveDisplay.styleArchiveClearBtn();
                    $('#archive-filter').addClass('ui-disabled');
                }
                else
                {
                    $('#clear-archive-search').css({display: 'none'});
                    $('#archive-filter').removeClass('ui-disabled');
                }
                searchArchive(searchString);
                break;
            case 'qrd-list':
                searchString = $('#'+sid+'search-resource').val();
                thisClass.resourcesDisplay.fillQrdSearchList(thisClass.resourcesDisplay.qrdChatPartner, inDialog);
                break;
        }
    }
    else
    {
        thisClass.searchButtonUpSet[type] = lzm_chatTimeStamp.getServerTimeString(null, false, 1);
        setTimeout(function() {
            if (thisClass.searchButtonUpSet[type] != 0 && lzm_chatTimeStamp.getServerTimeString(null, false, 1) - thisClass.searchButtonUpSet[type] >= 990) {
                switch (type)
                {
                    case 'operators':
                        CommonUIClass.UpdateUserList = true;
                        thisClass.CreateOperatorList();
                        break;
                    case 'qrd':
                        thisClass.resourcesDisplay.highlightSearchResults(myObjects,true);
                        break;
                    case 'ticket':
                        searchString = $('#search-ticket').val();
                        if (searchString != '') {
                            $('#clear-ticket-search').css({display: 'inline'});
                            thisClass.styleTicketClearBtn();
                        } else {
                            $('#clear-ticket-search').css({display: 'none'});
                        }
                        searchTickets(searchString);
                        break;
                    case 'archive':
                        searchString = $('#search-archive').val();
                        if (searchString != '') {
                            $('#clear-archive-search').css({display: 'inline'});
                            thisClass.archiveDisplay.styleArchiveClearBtn();
                        } else {
                            $('#clear-archive-search').css({display: 'none'});
                        }
                        searchArchive(searchString);
                        break;
                    case 'qrd-list':
                        searchString = $('#search-resource').val();
                        thisClass.resourcesDisplay.fillQrdSearchList(thisClass.resourcesDisplay.qrdChatPartner, inDialog);
                        break;
                }
            }
        }, 1400);
    }
};

CommonUIClass.prototype.showSubMenu = function(place, category, objectId, contextX, contextY, menuWidth, menuHeight) {
    var i = 0, inDialog;
    var contextMenuHtml = '<div class="cm lzm-unselectable" id="' + place + '-context" onclick="handleContextMenuClick(event);">';
    contextMenuHtml += '<div onclick="showSuperMenu(\'' + place + '\', \'' + category + '\', \'' + objectId + '\', ' + contextX + ', ' + contextY + ', ' + menuWidth + ', ' + menuHeight + ')"><i class="fa fa-caret-left lzm-ctxt-left-fa"></i><span id="show-super-menu" class="cm-line cm-line-icon-left cm-click">' + t('Back') + '</span></div><hr />';
    switch(place)
    {
        case 'qrd-tree':
            if(category=='kb_add')
            {
                inDialog = false;
                contextMenuHtml += '<div onclick="KnowledgebaseUI.ShowEntry(null,1);"><span id="add-qrd-ctxt" class="cm-line">' + tid('text') + '</span></div>';
                contextMenuHtml += '<div onclick="KnowledgebaseUI.ShowEntry(null,2);"><span id="add-qrd-clnk" class="cm-line">' + tid('link') + '</span></div>';
                contextMenuHtml += '<div onclick="KnowledgebaseUI.ShowEntry(null,3);"><span id="add-qrd-cfile" class="cm-line">' + tid('file') + '</span></div>';
                if(IFManager.IsDesktopApp() && d(IFManager.DeviceInterface.hasModule) && IFManager.DeviceInterface.hasModule('lz-screenshot-widget')){
                    contextMenuHtml += '<div onclick="IFManager.IFScreenCast(\'knowledgebase\', \'' + lzm_chatDisplay.selectedResource + '\');"><span id="add-qrd-cfile" class="cm-line">' + tid('screenshot') + '</span></div>';
                }
                contextMenuHtml += '<div onclick="KnowledgebaseUI.ShowEntry(null,0);"><span id="add-qrd-cfld" class="cm-line">' + tid('resource_folder') + '</span></div>';
            }
            else if(category=='kb_send')
            {
                inDialog = false;
                var chat,chats = DataEngine.ChatManager.Chats;
                for(var key in chats)
                {
                    chat = chats[key];
                    contextMenuHtml += '<div onclick="sendQrdPreview(\'\', \''+chat.SystemId+'\');"><span class="cm-line">' + chat.GetName(64,false) + '</span></div>';
                }

            }
            break;
        case 'ticket-list':
        case 'visitor-information':
            var ticket = null, ticketEditor = null, ticketGroup = null;
            for (i=0; i<this.ticketListTickets.length; i++) {
                if(this.ticketListTickets[i].id == objectId) {
                    ticket = this.ticketListTickets[i];
                }
            }
            if (ticket != null)
            {
                ticketEditor = (typeof ticket.editor != 'undefined' && ticket.editor != false) ? ticket.editor.ed : '';
                ticketGroup = ticket.gr;
            }
            if(category=='operator')
            {
                var operators = DataEngine.operators.getOperatorList();
                for (i=0; i<operators.length; i++)
                    if (operators[i].isbot != '1' && operators[i].id != ticketEditor) {
                        contextMenuHtml += '<div onclick="setTicketOperator(\'' + objectId + '\', \'' + operators[i].id + '\')"><span id="ticket-set-operator-' + operators[i].id + '" class="cm-line cm-click">' + operators[i].name + '</span></div>';
                    }
            }
            else if(category=='group')
            {
                var groups = DataEngine.groups.getGroupList();
                for (i=0; i<groups.length; i++) {
                    if (groups[i].id != ticketGroup) {
                        var groupHash = md5(groups[i].id).substr(0,6);
                        contextMenuHtml += '<div onclick="setTicketGroup(\'' + objectId + '\', \'' + groups[i].id + '\')"><span id="ticket-set-group-' + groupHash + '" class="cm-line cm-click">' + groups[i].id + '</span></div>';
                    }
                }
            }
            else if(category=='ticket_priority')
            {
                inDialog = (place == 'ticket-list') ? false : true;
                contextMenuHtml += '<div onclick="setTicketPriority(\'' + objectId + '\',4)"><span class="cm-line cm-line-icon-left cm-click text-red text-bold">' + tid('priority_4') + '</span></div>';
                contextMenuHtml += '<div onclick="setTicketPriority(\'' + objectId + '\',3)"><span class="cm-line cm-line-icon-left cm-click text-orange text-bold">' + tid('priority_3') + '</span></div>';
                contextMenuHtml += '<div onclick="setTicketPriority(\'' + objectId + '\',2)"><span class="cm-line cm-line-icon-left cm-click">' + tid('priority_2') + '</span></div>';
                contextMenuHtml += '<div onclick="setTicketPriority(\'' + objectId + '\',1)"><span class="cm-line cm-line-icon-left cm-click">' + tid('priority_1') + '</span></div>';
                contextMenuHtml += '<div onclick="setTicketPriority(\'' + objectId + '\',0)"><span class="cm-line cm-line-icon-left cm-click">' + tid('priority_0') + '</span></div>';

            }
            else if(category=='ticket_status')
            {
                inDialog = (place == 'ticket-list') ? false : true;
                contextMenuHtml += '<div onclick="changeTicketStatus(0,null,null,null,false)"><i class="fa fa-question-circle icon-blue"></i><span id="set-ticket-open" class="cm-line cm-line-icon-left cm-click">' + tidc('ticket_status_0', ' (O)') + '</span></div>';
                contextMenuHtml += '<div onclick="changeTicketStatus(1,null,null,null,false)"><i class="fa fa-gear" style="color: #808080"></i><span id="set-ticket-progress" class="cm-line cm-line-icon-left cm-click">' + tidc('ticket_status_1', ' (P)') + '</span></div>';
                contextMenuHtml += '<div onclick="changeTicketStatus(4,null,null,null,false)"><i class="fa fa-hourglass-end icon-purple"></i><span id="set-ticket-progress" class="cm-line cm-line-icon-left cm-click">' + tidc('ticket_status_4', ' (E)') + '</span></div>';
                contextMenuHtml += '<div onclick="changeTicketStatus(2,null,null,null,false)"><i class="fa fa-check-circle icon-green"></i><span id="set-ticket-closed" class="cm-line cm-line-icon-left cm-click">' + tidc('ticket_status_2', ' (C)') + '</span></div>';
                contextMenuHtml += '<div onclick="changeTicketStatus(3,null,null,null,false)"><i class="fa fa-remove icon-red"></i><span id="set-ticket-deleted" class="cm-line cm-line-icon-left cm-click">' + tidc('ticket_status_3', ' (D)') + '</span></div>';
            }
            else if(category=='ticket_sub_status')
            {
                var inDialog = (place == 'ticket-list') ? false : true;
                var myStatus = (ticket.editor) ? ticket.editor.st : 0;
                for(key in DataEngine.global_configuration.database['tsd'])
                {
                    var elem = DataEngine.global_configuration.database['tsd'][key];
                    if(elem.type == 0 && elem.parent == myStatus){
                        contextMenuHtml += '<div onclick="changeTicketStatus(null,\''+elem.name+'\',null,null,false)"><span class="cm-line cm-line-icon-left cm-click">' + elem.name + '</span></div>';
                    }
                }
            }
            else if(category=='ticket_channel')
            {
                var inDialog = (place == 'ticket-list') ? false : true;
                var channels = [t('Web'), t('Email'), t('Phone'), t('Misc'), t('Chat'), tid('feedback')];

                for(key in channels)
                {
                    var elem = channels[key];
                    contextMenuHtml += '<div onclick="changeTicketStatus(null,null,\''+key+'\',null,false)"><span class="cm-line cm-line-icon-left cm-click">' + elem + '</span></div>';
                }
            }
            else if(category=='ticket_sub_channel')
            {
                var inDialog = (place == 'ticket-list') ? false : true;
                var myChannel = ticket.t;
                for(key in DataEngine.global_configuration.database['tsd'])
                {
                    var elem = DataEngine.global_configuration.database['tsd'][key];
                    if(elem.type == 1 && elem.parent == myChannel){
                        contextMenuHtml += '<div onclick="changeTicketStatus(null,null,null,\''+elem.name+'\',false)"><span class="cm-line cm-line-icon-left cm-click">' + elem.name + '</span></div>';
                    }
                }
            }
            else if(category=='add_to_watch_list')
            {
                var operators = DataEngine.operators.getOperatorList('name', '', true);
                for (i=0; i<operators.length; i++)
                    if (operators[i].isbot != 1)
                        contextMenuHtml += '<div onclick="addTicketToWatchList(\'' + objectId + '\',\'' + operators[i].id + '\')"><span id="set-ticket-open" class="cm-line cm-line-icon-left cm-click">' + operators[i].name + '</span></div>';
            }
            break;
    }
    contextMenuHtml += '</div>';

    var myParent = 'body';
    if (place != 'body' && place != 'ticket-details' && place != 'visitor-list-table-div') {
        myParent = '#' + place + '-body';
    } else if (place != 'body') {
        myParent = '#' + place;
    }
    var checkSizeDivHtml = '<div id="context-menu-check-size-div" style="position:absolute; left: -3000px; top: -3000px;' + ' width: 2500px; height: 2500px;"></div>';
    $('body').append(checkSizeDivHtml);
    var testContextMenuHtml = contextMenuHtml.replace(/id="/g, 'id="test-');
    $('#context-menu-check-size-div').html(testContextMenuHtml);
    var contextHeight = $('#test-' + place + '-context').height();
    var contextWidth = (contextHeight > menuHeight) ? menuWidth + lzm_displayHelper.getScrollBarWidth() : menuWidth;
    contextHeight = Math.min(contextHeight, menuHeight);
    var contextTop = (contextHeight >= menuHeight) ? contextY : contextY + Math.round((menuHeight - contextHeight) / 2);

    $('#context-menu-check-size-div').remove();
    this.storedSuperMenu = $('#' + place + '-context').html();
    $('#' + place + '-context').replaceWith(contextMenuHtml);
    var myStyleObject = {left: contextX, width: contextWidth+'px', height: contextHeight+'px', top: contextTop};
    $('#' + place + '-context').css(myStyleObject);
};

CommonUIClass.prototype.showSuperMenu = function(place, category, objectId, contextX, contextY, menuWidth, menuHeight) {
    var contextMenuHtml = '<div class="cm lzm-unselectable" id="' + place + '-context" onclick="handleContextMenuClick(event);">' + this.storedSuperMenu + '</div>';
    $('#' + place + '-context').replaceWith(contextMenuHtml);
    var myStyleObject = {left: contextX+'px', width: menuWidth+'px', height: menuHeight+'px', top: contextY+'px'};
    $('#' + place + '-context').css(myStyleObject);
};

CommonUIClass.prototype.showContextMenu = function(place, myObject, mouseX, mouseY, button) {

    var winObj = TaskBarManager.GetActiveWindow();
    if(winObj==null)
        winObj = {DialogId:'not-existing'};

    button = (typeof button != 'undefined') ? button : '';

    var thisClass = this;
    var checkSize = true;
    var contextX = mouseX + 'px', contextY = mouseY + 'px', contextMenuName = place;
    var widthOffset = 0;
    $('#' + place + '-context').remove();

    var contextMenuHtml = '<div class="cm lzm-unselectable" id="' + contextMenuName + '-context" onclick="handleContextMenuClick(event);">';
    switch(place)
    {
        case 'qrd-tree':
            contextMenuHtml += thisClass.resourcesDisplay.CreateKBTreeContextMenu(myObject);
            break;
        case 'chat-info':
        case 'ticket-list':
        case 'visitor-information':
            widthOffset = 40;
            contextMenuHtml += thisClass.ticketDisplay.createTicketListContextMenu(myObject, place, widthOffset);
            break;
        case 'filter_list_dialog':
            contextMenuHtml += thisClass.FilterConfiguration.createFilterListContextMenu(myObject);
            break;
        case winObj.DialogId:
        case 'ticket-details':
            widthOffset = 20;
            contextMenuHtml += thisClass.ticketDisplay.createTicketDetailsContextMenu(myObject);
            break;
        case 'archive-filter':
            contextMenuHtml += thisClass.archiveDisplay.createArchiveFilterMenu(myObject);
            place = 'chat_page';
            break;
        /*
        case 'visitor-list-table-div':
            widthOffset = 20;
            contextMenuHtml += thisClass.VisitorsUI.createVisitorListContextMenu(myObject);
            break;
            */
        case 'operator-list':
            widthOffset = 20;
            contextMenuHtml += thisClass.createOperatorListContextMenu(myObject);
            break;
        case 'report-list':
            contextMenuHtml += thisClass.reportsDisplay.createReportListContextMenu(myObject);
            break;
        case 'report-filter':
            contextMenuHtml += thisClass.reportsDisplay.createReportFilterMenu(myObject);
            place = 'chat_page';
            break;
        case 'chat-allchats':
            widthOffset = 20;
            contextMenuHtml += thisClass.ChatsUI.CreateChatContextMenu(myObject,place);
            break;
        case 'task-bar-panel':
            widthOffset = 20;
            checkSize = false;
            contextMenuHtml += thisClass.ChatsUI.CreateChatContextMenu(myObject,place);
            break;
        case 'events-list':
            contextMenuHtml += thisClass.EventConfiguration.createEventsListContextMenu(myObject);
            place = 'event-configuration';
            break;
        case 'chat-actions':
            widthOffset = 40;
            contextMenuHtml += thisClass.createChatActionMenu(myObject);
            place = 'chat-controls';
            break;
        /*
        case 'archive':
            widthOffset = 20;
            contextMenuHtml += thisClass.archiveDisplay.createArchiveContextMenu(myObject);
            break;
            */
        case 'chat-members':
            widthOffset = 20;
            contextMenuHtml += thisClass.createChatMemberActionMenu(myObject);
            break;
    }
    contextMenuHtml += '</div>';

    var myParent = 'body';

    if(place == 'task-bar-panel')
        myParent = 'body';
    else if($('#' + place + '-body').length)
        myParent = '#' + place + '-body';
    else if (place != 'body')
        myParent = '#' + place;

    if(typeof myObject.parent != 'undefined')
        myParent = '#' + myObject.parent;

    var checkSizeDivHtml = '<div id="context-menu-check-size-div" style="position:absolute; z-index:400000; left: -1000px; top: -1000px; width: 800px; height: 800px;"></div>';
    $('body').append(checkSizeDivHtml);
    $('#context-menu-check-size-div').html(contextMenuHtml);

    var parentWidth = $(myParent).width();
    var parentHeight = $(myParent).height();

    if(!checkSize)
    {
        parentHeight = 1000;
    }

    var contextWidth = $('#' + contextMenuName + '-context').width();
    var contextHeight = Math.min(parentHeight - 24, $('#' + contextMenuName + '-context').height());

    if (parentHeight != null && parentWidth != null)
    {
        var remainingHeight = parentHeight - mouseY;
        var remainigWidth = parentWidth - mouseX;
        var widthDiff = remainigWidth - contextWidth - 12;
        var heightDiff = remainingHeight - contextHeight - 12;

        if ($.inArray(contextMenuName, ['ticket-filter', 'report-filter', 'archive-filter']) == -1)
        {
            if (widthDiff < 0)
            {
                contextX = Math.max((mouseX - contextWidth - 12), 5) + 'px';
            }
            if (heightDiff < 0) {
                contextY = Math.max((mouseY - contextHeight - 12), 5) + 'px';
            }
        }
        else
        {
            if (widthDiff < 0) {
                contextX = Math.max((mouseX + widthDiff - 10), 5) + 'px';
            }
            if (heightDiff < 0) {
                contextY = Math.max((mouseY + heightDiff- 10), 5) + 'px';
            }
        }
    }

    $('#context-menu-check-size-div').remove();
    contextMenuHtml = contextMenuHtml.replace(/%CONTEXTX%/g, parseInt(contextX)).replace(/%CONTEXTY%/g, parseInt(contextY)).replace(/%MYWIDTH%/g, parseInt(contextWidth)).replace(/%MYHEIGHT%/g, parseInt(contextHeight));
    $(myParent).append(contextMenuHtml);

    var myStyleObject = {left: contextX, width: (contextWidth+widthOffset)+'px', height: contextHeight+'px'};

    if (place == 'task-bar-panel')
        myStyleObject.bottom = '15px';
    else if (button == 'ticket-message-actions')
        myStyleObject.bottom = '0';
    else
        myStyleObject.top = contextY;

    $('#' + contextMenuName + '-context').css(myStyleObject);
};

CommonUIClass.prototype.styleTicketClearBtn = function(){
    var ctsBtnWidth = $('#clear-ticket-search').width();
    var ctsBtnHeight =  $('#clear-ticket-search').height();
    var ctsBtnPadding = Math.floor((18-ctsBtnHeight)/2)+'px ' +  Math.floor((18-ctsBtnWidth)/2)+'px ' + Math.ceil((18-ctsBtnHeight)/2)+'px ' +  Math.ceil((18-ctsBtnWidth)/2)+'px';
    $('#clear-ticket-search').css({padding: ctsBtnPadding});
};

CommonUIClass.prototype.playSound = function(name, sender){

    if (name == 'message')
        blinkPageTitle(tid('new_chat_activity'));
    else if (name == 'ticket')
        blinkPageTitle(tid('new_ticket_activity'));

    var thisClass = this;

    if ($.inArray(sender, thisClass.soundPlayed) == -1)
    {
        IFManager.IFPlaySound(name, LocalConfiguration.SoundVolume/100);
    }
};

CommonUIClass.prototype.addSoundPlayed = function(sender){
    if ($.inArray(sender,this.soundPlayed) == -1) {
        this.soundPlayed.push(sender);
    }
};

CommonUIClass.prototype.removeSoundPlayed = function(sender){
    if ($.inArray(sender,this.soundPlayed) != -1) {
        var tmpSoundPlayed = [];
        for (var i=0; i<this.soundPlayed.length; i++) {
            if (this.soundPlayed[i] != sender) {
                tmpSoundPlayed.push(this.soundPlayed[i]);
            }
        }
        this.soundPlayed = tmpSoundPlayed;
    }
};

CommonUIClass.prototype.ProcessChatIndication = function(){

    var chatobj,key,chatlist = DataEngine.ChatManager.GetChatsOf(DataEngine.myId,[Chat.Open]);
    this.SoundIntervalRing = false;
    if(LocalConfiguration.RepeatChatSound)
        this.SoundIntervalRing = chatlist.length>0;
    else if(LocalConfiguration.PlayChatSound)
    {
        for(key in chatlist)
        {
            chatobj = chatlist[key];
            if($.inArray(chatobj.i ,ChatUI.ChatsSoundPlayed) == -1)
            {
                ChatUI.ChatsSoundPlayed.push(chatobj.i);
                this.SoundIntervalRing = true;
                HeartBeatActions(true);
            }
        }
    }

    this.SoundIntervalQueue = false;
    var queuedList = DataEngine.ChatManager.GetQueued();
    if(LocalConfiguration.RepeatQueueSound)
        this.SoundIntervalQueue = queuedList.length>0;
    else if(LocalConfiguration.PlayQueueSound)
    {
        for(key in queuedList)
        {
            chatobj = queuedList[key];
            if($.inArray(chatobj.i ,ChatUI.ChatsQueueSoundPlayed) == -1)
            {
                ChatUI.ChatsQueueSoundPlayed.push(chatobj.i);
                this.SoundIntervalQueue = true;
                HeartBeatActions(true);
            }
        }
    }

    if(this.HeartBeat == null /*&& chatlist.length && DataEngine.ChatManager.IsUnread()*/)
    {
        this.HeartBeat = setInterval(function(){HeartBeatActions(false);},2000);
        HeartBeatActions(true);
    }
    /*

    for (var k=0; k<chatlist.length; k++)
    {
        var chatObj = chatlist[k];

        if(chatObj.NotificationsSent)
            continue;

        chatObj.NotificationsSent = true;
        var senderQuestion = '', senderName = chatObj.GetName(56,true);

        if (chatObj.Visitor != null)
            senderQuestion = chatObj.s.length ? chatObj.s : t('New Chat Request');

        NotificationManager.NotifyNewChat(senderName, notificationPushText, chatObj.SystemId);
    }
    */

    for (var k=0; k<chatlist.length; k++)
    {
        var chatObj = chatlist[k];

        if(chatObj.NotificationsSent)
            continue;

        chatObj.NotificationsSent = true;

        NotificationManager.NotifyNewChat(chatObj);

        this.lastChatSendingNotification = chatObj.SystemId;
    }
};

CommonUIClass.prototype.UpdateAutoForwardUI = function(chatObj,_fromTimer){

    if(chatObj != null && chatObj.AutoForwardCountdown != null)
    {
        if(chatObj.GetStatus() != Chat.Open || chatObj.GetMember(DataEngine.myId)==null || chatObj.GetChatGroup()!=null)
        {
            clearInterval(chatObj.AutoForwardCountdown);
            chatObj.AutoForwardCountdown = null;
            chatObj.AutoForwardTimeLeft = -1;
            return;
        }

        if(chatObj.AutoForwardTimeLeft == 0)
        {
            CommunicationEngine.pollServerSpecial({v: chatObj.v, b: chatObj.b, c: chatObj.i, g: chatObj.dcg, o: chatObj.AutoForwardTarget,a:true}, 'take-chat');
            clearInterval(chatObj.AutoForwardCountdown);
            chatObj.AutoForwardCountdown = null;
            chatObj.AutoForwardTimeLeft = -1;
        }

        var uiText = tid('start_chat')+' ('+ Math.max(chatObj.AutoForwardTimeLeft,0).toString()+ ')';
        if(ChatManager.ActiveChat == chatObj.SystemId)
        {
            $('#accept-chat-text').html(uiText);
        }
        else if(ChatManager.ActiveChat == '' && lzm_chatDisplay.selected_view == 'mychats')
        {
            $('#ac-accept-chat-'+chatObj.i).html(uiText);
        }

        if(chatObj.AutoForwardTimeLeft<=0)
            $('#accept-chat').addClass('ui-disabled');

        if(_fromTimer && chatObj.AutoForwardTimeLeft!=-1)
            chatObj.AutoForwardTimeLeft--;
    }
};

CommonUIClass.prototype.showDisabledWarning = function(){
    var that = this;
    if (this.serverIsDisabled && (lzm_chatTimeStamp.getServerTimeString(null, false, 1) - this.lastDiabledWarningTime >= 90000)) {
        if (!CommonDialogClass.IsAlert)
        {
            var confirmText = t('This LiveZilla server has been deactivated by the administrator.') + '<br />' +
                t('Do you want to logout now?');
            lzm_commonDialog.createAlertDialog(confirmText, [{id: 'ok', name: t('Ok')}, {id: 'cancel', name: t('Cancel')}]);
            $('#alert-btn-ok').click(function() {
                logout(false);
            });
            $('#alert-btn-cancel').click(function() {
                that.lastDiabledWarningTime = lzm_chatTimeStamp.getServerTimeString(null, false, 1);
                lzm_commonDialog.removeAlertDialog();
            });
        }
    }
};

CommonUIClass.prototype.getLanguageDisplayName = function(lang){
    return (typeof this.availableLanguages[lang.toLowerCase()] != 'undefined') ?
        lang + ' - ' + this.availableLanguages[lang.toLowerCase()] :
        (typeof this.availableLanguages[lang.toLowerCase().split('-')[0]] != 'undefined') ?
            lang + ' - ' + this.availableLanguages[lang.toLowerCase().split('-')[0]] :
            lang;
};

CommonUIClass.prototype.openPhoneCallDialog = function(myObject, lineNo, caller) {

    var that = this;
    var phoneNumber = '';
    if(caller=='ticket')
    {
        lineNo = (caller == 'ticket') ? (myObject.messages[lineNo].p != '') ? lineNo : 0 : lineNo;
        phoneNumber = myObject.messages[lineNo].p
    }
    else
    {
        var vis = VisitorManager.GetVisitor(myObject);
        phoneNumber = DataEngine.inputList.getInputValueFromVisitor(116,vis);
    }

    var phoneProtocols = [{value: 'callto:', text: tid('callto')},
        {value: 'tel:', text: tid('tel')},
        {value: 'skype:', text: tid('skype')}];

    var phoneContent = lzm_inputControls.createSelect('phonecall-protocol', '', '', tidc('protocol'), {position: 'right', gap: '0px'},{width: '205px'}, '', phoneProtocols, that.lastPhoneProtocol, '');
    phoneContent += '<br>';
    phoneContent +=lzm_inputControls.createInput('phone-number', '', phoneNumber, tidc('number'), '', 'text', '');

    lzm_commonDialog.createAlertDialog(phoneContent, [{id: 'phone-call-now', name: tid('call_now')}, {id: 'phone-call-cancel', name: t('Cancel')}]);

    $('#phonecall-protocol-inner-text').html(that.lastPhoneProtocol);
    $('#phonecall-protocol').change(function() {
        var selectText = '';
        for (var i=0; i<phoneProtocols.length; i++) {
            if (phoneProtocols[i].value == $('#phonecall-protocol').val()) {
                selectText = phoneProtocols[i].value;
            }
        }
        $('#phonecall-protocol-inner-text').html(selectText);
    });
    $('#alert-btn-phone-call-cancel').click(function() {
        lzm_commonDialog.removeAlertDialog();
    });
    $('#alert-btn-phone-call-now').click(function() {
        that.lastPhoneProtocol = $('#phonecall-protocol').val();
        startPhoneCall($('#phonecall-protocol').val(), $('#phone-number').val());
        lzm_commonStorage.saveValue('ph_cll_prot_' + DataEngine.myId,that.lastPhoneProtocol);
        $('#alert-btn-phone-call-cancel').click();
    });
    $('#phone-number').focus();
    $('#phone-number').blur();
};

CommonUIClass.prototype.getCountryName = function(isoCode,addRegion){

    addRegion = d(addRegion) ? addRegion : true;
    var countryName = isoCode;

    var cobj = lzm_commonTools.GetElementByProperty(lzcil,'alpha2Code',isoCode.toUpperCase());
    if(cobj.length)
    {
        cobj = cobj[0];
        if(d(cobj['name']))
            countryName = cobj['name'];
        if(d(cobj['translations']))
        {
            var mylang = DataEngine.operators.getOperator(this.myId).lang.toLowerCase();
            if(d(cobj['translations'][mylang]))
                countryName = cobj['translations'][mylang];
        }
        if(addRegion && d(cobj['subregion']) && UIRenderer.windowWidth > 700)
            countryName += ' (' + cobj['subregion'] + ' / ' + cobj['region'] + ')';
    }
    return countryName;
};

CommonUIClass.prototype.getCountryLanguage = function(isoCode){
    var spLanguage = 'EN';
    var cobj = lzm_commonTools.GetElementByProperty(lzcil,'alpha2Code',isoCode.toUpperCase());
    if(cobj.length)
    {
        cobj = cobj[0];
        if(d(cobj['languages']))
            spLanguage = cobj['languages'][0];
    }
    if(!d(spLanguage))
        return 'EN';
    return spLanguage.toUpperCase();
};

CommonUIClass.prototype.showObjectTranslator = function(_type, _obj) {

    var that=this,headerString = tid('translate'),msgText,dialogId,dialogData='';
    var parentWindow = TaskBarManager.GetActiveWindow();
    var footerString = '<span style="float:right;">' + lzm_inputControls.createButton('translate-obj-replace', '', '', t('Replace'), '', 'lr',{'margin-left': '6px', 'margin-top': '-2px'},'',30,'d');

    if(_type!='chat_question')
        footerString += lzm_inputControls.createButton('translate-obj-attach', '', '', t('Attach'), '', 'lr',{'margin-left': '6px', 'margin-top': '-2px'},'',30,'d');

    if(_type=='ticket')
        footerString += lzm_inputControls.createButton('translate-obj-comment', '', '', t('Comment'), '', 'lr',{'margin-left': '6px', 'margin-top': '-2px'},'',30,'d');

    footerString += lzm_inputControls.createButton('translate-obj-cancel', '', '', t('Cancel'), '', 'lr',{'margin-left': '6px', 'margin-top': '-2px'},'',30,'d') + '</span>';

    if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
        footerString += '<span style="float:left;">' + lzm_inputControls.createButton('translate-obj-retranslate', '', '', t('Translate'), '', 'lr',{'margin-left': '6px', 'margin-top': '-2px'},'',30,'e') + '</span>';

    if(_type=='ticket')
    {
        msgText = _obj[0].messages[_obj[1]].mt;
        dialogId = _obj.id;

        var ticketSender = (_obj[0].messages[0].fn.length > 20) ? lzm_commonTools.escapeHtml(_obj[0].messages[0].fn).substr(0, 17) + '...' : lzm_commonTools.escapeHtml(_obj[0].messages[0].fn);
        var menuEntry = t('Ticket (<!--ticket_id-->, <!--name-->)',[['<!--ticket_id-->', _obj[0].id],['<!--name-->', ticketSender]]);
        dialogData = {'ticket-id': _obj[0].id, menu: menuEntry};
    }
    else
    {
        msgText = _obj[0].innerText;
        dialogId = _obj[1];
        dialogData = {};
    }

    dialogId += '_translator';

    var winObj = TaskBarManager.GetWindow(dialogId);
    if(winObj != null)
    {
        winObj.Maximize();
        return;
    }

    var defaultLanguage = DataEngine.defaultLanguage, i;
    defaultLanguage = ($.inArray(defaultLanguage, lzm_chatDisplay.translationLangCodes) != -1) ? defaultLanguage : ($.inArray(defaultLanguage.split('-')[0], lzm_chatDisplay.translationLangCodes) -1) ? defaultLanguage.split('-')[0] : 'en';

    var bodyString = '<fieldset id="obj-translator-original" class="lzm-fieldset">' +
        '<legend>' + t('Original') + '</legend><select id="obj-translator-orig-select" class="lzm-select ui-disabled"><br /><option value="">' + t('Auto-Detect') + '</option>';
    bodyString += '</select><textarea id="obj-translator-orig-text" class="obj-reply-text" style="padding: 4px;">' + msgText + '</textarea>' +
        '</fieldset><fieldset id="obj-translator-translation" class="lzm-fieldset" data-role="none" style="margin-top: 5px;">' +
        '<legend>' + t('Translation') + '</legend><select id="obj-translator-translated-select" class="lzm-select"><br />';

    for (i=0; i<lzm_chatDisplay.translationLanguages.length; i++)
    {
        var selectedString = (lzm_chatDisplay.translationLanguages[i].language == defaultLanguage) ? ' selected="selected"' : '';
        bodyString += '<option' + selectedString + ' value="' + lzm_chatDisplay.translationLanguages[i].language + '">' + lzm_chatDisplay.translationLanguages[i].language.toUpperCase() + ' - ' + lzm_chatDisplay.translationLanguages[i].name + '</option>'
    }

    bodyString += '</select><textarea id="obj-translator-translated-text" class="obj-reply-text" style="padding: 4px;"></textarea></fieldset>';

    var curWin = TaskBarManager.GetActiveWindow();
    if(curWin != null)
        curWin.Minimize();

    lzm_commonDialog.CreateDialogWindow(headerString,bodyString, footerString, 'language', 'object_translator', dialogId, 'translate-obj-cancel', false, dialogData);

    UIRenderer.resizeObjTranslator();

    var fillTranslatedText = function(sourceLanguage, targetLanguage) {
        var gUrl = 'https://www.googleapis.com/language/translate/v2';
        var dataObject = {key: DataEngine.otrs,
            target: targetLanguage, q: $('#obj-translator-orig-text').val()};
        if (sourceLanguage != '') {
            dataObject.source = sourceLanguage;
        }
        $.ajax({
            type: "GET",
            url: gUrl,
            data: dataObject,
            dataType: 'json'
        }).done(function(data) {
                $('#obj-translator-translated-text').val(data.data.translations[0].translatedText);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                deblog(jqXHR);
                deblog(jqXHR.status);
                deblog(textStatus);
                deblog(errorThrown);
            });
    };

    function updateChatMsg(text,att){
        var myCurrentChat = DataEngine.ChatManager.GetChat(ChatManager.ActiveChat);
        var orgpost =  lzm_commonTools.GetElementByProperty(myCurrentChat.Messages,'id',_obj[1]);
        if(orgpost.length)
        {
            orgpost = orgpost[0];
            lzm_commonTools.RemoveElementByProperty(that.translatedPosts,'id',orgpost.id);
            if(!att)
                that.translatedPosts.push({id:orgpost.id,text:text});
            else
                that.translatedPosts.push({id:orgpost.id,text:orgpost.text + '<br><span class="lz_message_translation">' + text + '</span>'});
            that.RenderChatHistory();
        }
    }

    fillTranslatedText('', defaultLanguage);
    $('#obj-translator-translated-select').change(function() {
        fillTranslatedText($('#obj-translator-orig-select').val(), $('#obj-translator-translated-select').val());
    });
    $('#obj-translator-orig-select').change(function() {
        fillTranslatedText($('#obj-translator-orig-select').val(), $('#obj-translator-translated-select').val());
    });
    $('#translate-obj-retranslate').click(function() {
        fillTranslatedText($('#obj-translator-orig-select').val(), $('#obj-translator-translated-select').val());
    });
    $('#translate-obj-replace').click(function() {

        var translatedText = $('#obj-translator-translated-text').val();
        $('#translate-obj-cancel').click();
        if(_type=='ticket')
            saveTicketTranslationText(_obj[0], _obj[1], translatedText);
        else
            updateChatMsg(translatedText,false);
    });
    $('#translate-obj-attach').click(function() {
        if(_type=='ticket')
            saveTicketTranslationText(_obj[0], _obj[1], msgText + '\r\n\r\n' + $('#obj-translator-translated-text').val());
        else
            updateChatMsg($('#obj-translator-translated-text').val(),true);
        $('#translate-obj-cancel').click();
    });

    if(_type=='ticket')
        $('#translate-obj-comment').click(function() {
            var translatedText = $('#obj-translator-translated-text').val();
            $('#translate-obj-cancel').click();
            if(_type=='ticket')
                saveTicketTranslationText(ticket, msgNo, translatedText, 'comment');
        });

    $('#translate-obj-cancel').click(function() {
        TaskBarManager.RemoveActiveWindow();
        if(parentWindow != null)
            parentWindow.Maximize();
    });
};

CommonUIClass.prototype.RemoveAllContextMenus = function(){

    removeTicketContextMenu();
    removeArchiveFilterMenu();
    removeKBContextMenu();
    removeTicketMessageContextMenu();
    removeTicketFilterMenu();
    removeVisitorListContextMenu();
    removeOperatorListContextMenu();
    removeChatMembersListContextMenu();
    removeReportFilterMenu();
    removeReportContextMenu();
    removeChatLineContextMenu();
    removeFiltersListContextMenu();
    removeVisitorChatActionContextMenu();
    
    ContextMenuClass.RemoveAll();
};

CommonUIClass.__ShowChatMsgTranslator = function() {
    var field = CommonUIClass.TranslateChatPost;
    if(!$(field).hasClass('RCMT'))
        showTranslationDialog('chat',[$(field).find('div span')[0],$(field).data('pn')]);
};

CommonUIClass.ToggleSelectViewMenu = function(_hideOnly){

    if(!CommonUIClass.BlockNavigationHidden)
        return;

    _hideOnly = (d(_hideOnly)) ? _hideOnly : false;

    if(!_hideOnly && $('#new-view-select-panel').css('display') != 'block')
        $('#new-view-select-panel').css('display','block');
    else
        $('#new-view-select-panel').css('display','none');
};

CommonUIClass.WindowHasFocus = function(){
    if(!d(document.hasFocus))
        return true;

    return document.hasFocus();
};

var HeartBeatActions = function (_playNow) {

    if(DataEngine.myId != '')
    {
        lzm_chatDisplay.HeartBeatCounter++;

        if(IFManager.IsDesktopApp())
        {
            var st = ChatPollServerClass.__UserStatus;

            if(CommonUIClass.WindowHasFocus())
            {
                if(ChatManager.ActiveChat != '')
                    if(DataEngine.ChatManager.GetChat(ChatManager.ActiveChat).IsUnread)
                        DataEngine.ChatManager.GetChat(ChatManager.ActiveChat).SetUnread(false);

                if (lzm_chatDisplay.selected_view == 'tickets')
                    DataEngine.userHasSeenCurrentTickets();
            }

            if(DataEngine.ChatManager.IsUnread())
                st=4;
            else if(lzm_chatDisplay.ticketDisplay.IsLatestTicketUnseen())
                st=5;

            IFManager.IFSetOperatorStatus(st);
        }

        if(lzm_chatDisplay.SoundIntervalRing && (lzm_chatDisplay.HeartBeatCounter%3==0 || _playNow))
        {
            IFManager.IFPlaySound('ringtone',LocalConfiguration.SoundVolume/100);
        }
        else if(lzm_chatDisplay.SoundIntervalQueue)
        {
            if(lzm_chatDisplay.HeartBeatCounter%10==0 || _playNow)
            {
                if(!IFManager.IsAppFrame || IFManager.IsDesktopApp())
                    IFManager.IFPlaySound('queue',LocalConfiguration.SoundVolume/100);
            }
        }

        if (!IFManager.IsAppFrame && !IFManager.IsMobileOS)
        {
            if (doBlinkTitle && blinkTitleMessage != '')
            {
                var newTitle = (lzm_chatDisplay.HeartBeatCounter%2==0) ? blinkTitleMessage : DataEngine.siteName;
                $('title').html(newTitle);
            }
            else
            {
                $('title').html(DataEngine.siteName);
            }
        }
    }
};

function OverlayChatWidgetV2(){}

OverlayChatWidgetV2.APIButtonClick = function(){};
