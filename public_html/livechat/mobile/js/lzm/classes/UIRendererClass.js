/****************************************************************************************
 * LiveZilla UIRendererClass.js
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function UIRendererClass() {
    this.windowWidth = 0;
    this.windowHeight = 0;
    this.maxWindowHeight = 0;
    this.chatPageHeight = 0;
    this.chatPageTop = 0;
    this.mainMenuPosition = {top: 13, left: 15};
    this.mainMenuHeight = 40;
    this.mainMenuWidth = 0;
    this.viewSelectPanelHeight = 31;
    this.viewContainerHeight = 0;
    this.viewContainerWidth = 0;
    this.viewContainerCss = {};
}

UIRendererClass.prototype.resizeAll = function() {

    this.windowWidth = $(window).width();
    this.windowHeight = $(window).height();
    this.mainMenuWidth = this.windowWidth - 32;
    if (this.windowHeight > this.maxWindowHeight)
        this.maxWindowHeight = this.windowHeight;

    this.chatPageHeight = this.windowHeight;
    this.chatPageTop = this.mainMenuPosition.top + this.mainMenuHeight + this.viewSelectPanelHeight + 10;
    if (IFManager.IsAppFrame && this.windowHeight < 390) {
        this.chatPageHeight = Math.min(390, this.maxWindowHeight);
    }
    this.viewContainerHeight = this.chatPageHeight - this.chatPageTop - 27;
    this.viewContainerWidth = this.mainMenuWidth - 10;

    this.viewContainerCss = {position: 'absolute', width: this.viewContainerWidth+'px', height: this.viewContainerHeight+'px',
        padding: '5px 5px 5px 5px', left: '0px', top: '2px', display: 'block', border: '1px solid #ccc', 'border-radius': '4px',
        overflow: 'hidden', background: '#ffffff'};

    this.resizeStartPage();
    this.resizeMychats();
    this.resizeTicketList();
    this.resizeArchive();
    this.resizeOperatorList();
    this.ResizeVisitorList();
    this.resizeFilter();
    this.resizeAllChats();
    this.resizeReportList();
    this.resizeTicketDetails();
    this.resizeEmailDetails();
    this.resizeOptions();
    this.resizeNavigation();
    this.resizeResources();
    this.resizeAddResources();
    this.resizeEditResources();
    this.resizeResourceSettings();
    this.resizeTicketReply();
    this.resizeVisitorDetails();
    this.resizeVisitorInvitation();
    this.resizeOperatorForwardSelection();
    this.resizeMessageForwardDialog();
    this.resizeArchivedChat();
    this.resizeDynamicGroupDialogs();
    this.resizeTranslateOptions();
    this.resizeChatView();
    this.resizeTranslationEditor();
    this.resizeFilterCreation();
    this.resizeFilterList();
    this.resizeLinkGenerator();
    this.resizeSendTranscriptDialog();
    this.resizeObjTranslator();
    this.resizeTicketLinker();
    this.resizeUserManagement();
    this.resizeMenuPanels();

    try
    {
        lzm_commonDialog.resizeAlertDialog();
    }
    catch(ex)
    {
        deblog(ex);
    }
};

UIRendererClass.prototype.resizeTicketDetails = function(){

    var winObj = TaskBarManager.GetActiveWindow();
    var bodyObj = null;

    if(winObj != null && winObj.TypeId == 'ticket-details')
    {
        bodyObj = $('#' + winObj.DialogId + '-body');
        var isNew = !$('#ticket-message-details').length;

        var myHeight = Math.max(bodyObj.height(), $('#email-list-body').height());
        myHeight = Math.max(myHeight, $('#visitor-information-body').height());

        var navHeightOuter = 70;
        var historyHeight;
        var fullScreenMode = lzm_chatDisplay.ticketDisplay.isFullscreenMode();
        historyHeight = Math.floor((myHeight-navHeightOuter)*0.4);

        $('#ticket-comment-list-frame').css({height: 'calc(100% - 31px)'});
        $('#ticket-attachment-list').css({'height': 'calc(100% - 1px)'});
        $('#ticket-ticket-details').css({'min-height': (historyHeight - 22) + 'px'});
        $('#ticket-new-input').css({height: '100%'});
        $('#comment-text').css({'min-height': (myHeight - 22)+'px'});

        if(!IFManager.IsMobileOS)
            $('#ticket-details-placeholder-tab-0').css({width:$('#ticket-details-inner th:first-child').width()-4+'px'});

        $('#change-message-text').css({width: '100%', height: '100%'});

        $('#ticket-ticket-details').css({height: (myHeight-28) + 'px'});

        if(fullScreenMode)
        {
            $('#ticket-details-div').css({right:0,'border-width':'0 0 0 1px'});
            $('#ticket-message-div').css({left:(!isNew) ? '301px' : 0,right:'342px'});
            $('#ticket-history-div').css({left:0,'border-width':'0  1px 0 0'});
            $('#ticket-details-inner').css({height: (myHeight-28) + 'px'});
            $('#ticket-message-placeholder').css({height:'100%','overflow-y':'auto'});
            $('#ticket-history-placeholder').css({height:'100%','overflow-y':'auto'});
            $('.ticket-history-placeholder-content').css({height: (myHeight-29) + 'px'});
            $('.ticket-message-placeholder-content').css({height: (myHeight-29) + 'px'});
            $('#ticket-message-list').css({'height': (myHeight - 29) + 'px'});
            $('#ticket-message-iframe').css({'height': (myHeight - $('#ticket-message-details').outerHeight() - $('#ticket-message-insecure').outerHeight() - 34) + 'px'});

            if($('#att-img-preview').data('scalable'))
            {
                if($('#att-img-preview').data('natural') == '1')
                {
                    var width = $('#att-img-preview-field').width();
                    $('#att-img-preview').css({width:width+'px'});
                }
                else
                    $('#att-img-preview').css({width:'auto'});

                if($('#att-img-preview').data('natural') == '1')
                    $('#att-img-preview').css({cursor:'zoom-in'});
                else if($('#att-img-preview').data('natural') == '0')
                    $('#att-img-preview').css({cursor:'zoom-out'});
            }
        }
        else
        {
            $('.ticket-message-placeholder-content').css({height: '100%'});
            if(!isNew)
            {
                $('#ticket-message-text').css({'max-height': '248px',width:lzm_chatDisplay.windowWidth-20+'px',overflow:'auto'});
                $('#ticket-message-div').css({width:lzm_chatDisplay.windowWidth-20+'px','box-shadow':'0px 0px 5px #555'});
            }
            else
            {
                $('#ticket-message-text').css({'max-height': '248px',width:lzm_chatDisplay.windowWidth+'px',overflow:'auto'});
                $('#ticket-message-div').css({width:lzm_chatDisplay.windowWidth+'px','box-shadow':'0px 0px 5px #555'});
            }
            $('#ticket-message-list').css({'height': (myHeight - 29) + 'px',width:lzm_chatDisplay.windowWidth+'px'});
            $('#ticket-ticket-details').css({width:lzm_chatDisplay.windowWidth+'px'});
        }

        var p = (ChatTicketClass.DisplayType=='TEXT') ? '10px' : 0;
        if(isNew)
            $('#ticket-message-text').css({overflow:'hidden',padding:p,'height': (myHeight - 29) + 'px'});
        else
            $('#ticket-message-text').css({padding:p,'height': (myHeight - $('#ticket-message-details').height() - 42) + 'px'});

        var height = $('#ticket-attachment-list').height()-$('#attachment-table').height()-7;

        $('#att-img-preview-field').css({'height':height+'px'});
    }
};

UIRendererClass.prototype.resizeTicketReply = function() {

    var winObj = TaskBarManager.GetActiveWindow();
    var bodyObj = null;

    if (winObj != null && winObj.DialogId.endsWith('_reply'))
    {
        bodyObj = $('#' + winObj.DialogId + '-body');
    }
    else if (winObj != null && winObj.DialogId.endsWith('_preview'))
    {
        bodyObj = $('#' + winObj.DialogId + '-body');
    }
    else
        return;

    var displayViews = {'reply': 2, 'preview': 1};
    var tabControlWidth = 0, ticketDetailsBodyHeight = bodyObj.height();

    for (var view in displayViews)
        if (displayViews.hasOwnProperty(view))
            if ($('#' + view + '-placeholder-content-' + displayViews[view]).css('display') == 'block')
                tabControlWidth = $('#' + view + '-placeholder-content-' + displayViews[view]).width();

    $('.reply-placeholder-content').css({height: (ticketDetailsBodyHeight - 30) + 'px'});
    $('#message-attachment-list').css({'min-height': (ticketDetailsBodyHeight - 62) + 'px'});

    if (tabControlWidth != 0)
        $('#preview-comment-text').css({'min-height': (ticketDetailsBodyHeight - 62) + 'px'});

    var replyTextHeight = ($('#ticket-reply-files').length) ? bodyObj.height() - $('#ticket-reply-files').height() - 428 : bodyObj.height() - 378;

    if ($('#ticket-reply-subject').prop('type')=='hidden')
        replyTextHeight += 32;

    $('#new-message-comment').css({'min-height': '60px'});
    $('#ticket-reply-text').css({'height': replyTextHeight+'px'});

    var auoSearchWidth = $('#ticket-kb-auto-search').css('display') == 'block' ? 360 : 0;
    $('#ticket-composer-form').css({'width': (bodyObj.width() - auoSearchWidth)+'px'});
};

UIRendererClass.prototype.resizeEmailDetails = function() {
    if ($('#email-list-body').length > 0)
    {
        var myHeight = $('#email-list-body').height() + 10;
        var listHeight = Math.floor(Math.max(myHeight / 2, 175) - 45);
        var contentHeight = (myHeight - listHeight) - 93;
        $('#email-list-frame').css({height: listHeight + 'px'});
        $('.email-placeholder-content').css({height: (contentHeight + 52) + 'px'});
        $('#incoming-email-list-active').css({'height': (listHeight - 27) + 'px',overflow:'auto'});
        $('#incoming-email-list-deleted').css({'height': (myHeight - 39) + 'px',overflow:'auto'});
        $('#incoming-email-table-deleted').css({cursor:'default'});
        $('#email-text').css({'height': ($('.email-placeholder-content').height() - 74) + 'px'});
        $('#email-content').css({height:'calc(100% - 1px)'});
        $('#email-html').css({'height': (contentHeight + 52) + 'px'});
        $('#ticket-message-iframe').css({height: (contentHeight + 49 - $('#ticket-message-insecure').outerHeight()) + 'px'});
        $('#email-attachment-list').css({'min-height': (contentHeight - 22) + 'px'});
    }
};

UIRendererClass.prototype.resizeOptions = function() {

    if ($('#user-settings-dialog-body').length > 0) {
        var tabContentHeight = $('#user-settings-dialog-body').height() - 25;
        $('#view-select-settings').css({'min-height': (tabContentHeight - 35) + 'px'});
        $('#about-settings').css({'min-height': (tabContentHeight - 35) + 'px'});
        $('#tos').css({'min-height': (tabContentHeight-7) + 'px'});
        $('.settings-placeholder-content').css('height',(tabContentHeight-4) + 'px');
        $('.table-settings-placeholder-content').css('height',(tabContentHeight-34) + 'px');
    }
};

UIRendererClass.prototype.resizeNavigation = function() {

    $('#new-view-select-panel').css({right:'auto',height:'auto',top:0,bottom:0,width:'70px',background:'#777'});
    $('.view-select-button').css({display:'block'});
    $('#main_frame').css({left:'70px'});
    $('#task-bar-panel').css({left:'70px'});
    $('#main-menu-panel').css({left:'0px','z-index':113});

    if(CommonUIClass.BlockNavigationHidden)
    {
        $('#main_frame').css({left:'0'});
        $('#new-view-select-panel').css({right:0,left:'auto',top:'36px','z-index':104});
        $('#task-bar-panel').css({left:'0px'});
    }
    else
        $('#new-view-select-panel').css('display','block');
};

UIRendererClass.prototype.resizeResources = function() {
    var bh,resultListHeight;
    var winObj = TaskBarManager.GetActiveWindow();
    var sp = lzm_chatDisplay.resourcesDisplay.ShowPreview && lzm_chatDisplay.windowWidth > 800;

    if (winObj == null && lzm_chatDisplay.selected_view == 'qrd')
    {
        bh = $('#qrd-tree-body').height();

        if($('#all-resources-inner').outerHeight() > $('#all-resources').outerHeight())
            $('#all-resources-preview').css('box-shadow','none');

        if($('#qrd-tree-placeholder-content-0').css('display') == 'block')
        {
            // main KB - TREE
            $('#all-resources').css({height: (bh-32) + 'px',width : (sp) ? (lzm_chatDisplay.windowWidth - 500) + 'px' : '100%'});
        }
        else
        {
            // main KB - SEARCH
            resultListHeight = $('#qrd-tree-body').height() - $('#search-input').height() - 97;
            $('#search-results').css({'min-height': resultListHeight + 'px'});
        }

        if(sp)
            $('#preview-qrd').addClass('lzm-button-b-pushed');
        else
            $('#preview-qrd').removeClass('lzm-button-b-pushed');

        $('.qrd-tree-placeholder-content').css({height:''});
        $('#all-resources-preview').css({display: (sp) ? 'block' : 'none',left:(lzm_chatDisplay.windowWidth - 500) + 'px',height: (bh-32) + 'px'});

    }
    else if(winObj != null && winObj.TypeId.startsWith('qrd-tree-dialog'))
    {
        // dialog KB - TREE
        $('.qrd-tree-placeholder-content').css({height: ($('#'+winObj.DialogId+'-body').height() - 40) + 'px'});
        resultListHeight = $('#'+winObj.DialogId+'-body').height() - $('#search-input').height() - 89;

        $('#search-results').css({'min-height': resultListHeight + 'px'});
        $('#all-resources-dialog').css({overflow:'auto',height: (lzm_chatDisplay.windowHeight-94) + 'px',width : (sp) ? (lzm_chatDisplay.windowWidth - 500) + 'px' : '100%'});

        bh = $('#qrd-tree-dialog-placeholder-content-0').height();
        $('#all-resources-preview-dialog').css({display: (lzm_chatDisplay.windowWidth > 800) ? 'block' : 'none',left:(lzm_chatDisplay.windowWidth - 500) + 'px',height: (bh+2) + 'px'});
    }
};

UIRendererClass.prototype.resizeAddResources = function() {

    var winObj = TaskBarManager.GetActiveWindow();
    if (winObj != null && $('#'+winObj.DialogId+'-body').length > 0)
    {
        var myHeight = Math.max($('#'+winObj.DialogId+'-body').height());
        myHeight -= $('#kb-entry-placeholder-tabs-row').height();
        var qrdTextHeight = myHeight - 150;

        // new
        $('#show-kb-entry-text-inner').css({height:  (qrdTextHeight +24)+'px',border: '1px solid #ccc','background-color': '#f5f5f5'});
        $('#show-kb-entry-text').css({height: (qrdTextHeight +25)+'px','box-shadow': 'none', 'border-radius': '0px', padding: '0px', margin: '0px', border: '1px solid #ccc'});
        $('#show-kb-entry-body').css({height: (qrdTextHeight - 11)+'px','box-shadow': 'none', 'border-radius': '0px', margin: '0px','background-color': '#ffffff', 'overflow-y': 'hidden', 'border-top': '1px solid #ccc'});
        $('#show-kb-entry-url-icon').css({top: '0px'});
        $('#show-kb-entry-url-text').css({top: '0px'});
        $('#file-drop-box').css({height: (myHeight-33)});
    }
};

UIRendererClass.prototype.resizeEditResources = function() {
    var winObj = TaskBarManager.GetActiveWindow();
    if (winObj != null && $('#edit-resource').length > 0)
    {
        var myHeight = Math.max($('#'+winObj.DialogId+'-body').height(), $('#qrd-tree-dialog-body').height(), $('#ticket-details-body').height());
        var qrdTextHeight = myHeight - 140;
        $('#qrd-edit-text-inner').css({width:'100%', height:  (qrdTextHeight + 24)+'px', border: '1px solid #ccc','background-color': '#f5f5f5'});
        $('#qrd-edit-text').css({height: (qrdTextHeight +25)+'px','box-shadow': 'none', 'border-radius': '0px', padding: '0px', margin: '0px', border: '1px solid #ccc'});
        $('#qrd-edit-text-body').css({height: (qrdTextHeight - 11)+'px','box-shadow': 'none', 'border-radius': '0px', margin: '0px','background-color': '#fff', 'overflow-y': 'hidden', 'border-top': '1px solid #ccc'});
        $('#qrd-edit-url-icon').css({top: '0px'});
        $('#qrd-edit-url-text').css({top: '0px'});
    }
};

UIRendererClass.prototype.resizeResourceSettings = function() {
    if ($('#qrd-settings').length > 0 || $('#qrd-add').length > 0 || $('#qrd-edit').length > 0)
    {
        var myHeight = Math.max(Math.max($('#qrd-settings-body').height(), $('#qrd-add-body').height()), $('#qrd-edit-body').height());
        $('.qrd-settings-placeholder-content').css({'min-height': (myHeight - 41)+'px'});
        $('#qrd-knb-shortcuts-text-container').css({height: '50px'});
    }
};

UIRendererClass.prototype.resizeVisitorDetails = function() {

    $('.dialog-visitor-info, .embedded-visitor-info').each(function()
    {
        var dialog = $(this).attr('class')=='dialog-visitor-info';
        var elementId = ((!dialog) ? 'e-' : 'd-') + $(this).attr('data-visitor-id');
        var navHeight = $('#visitor-info-'+elementId+'-placeholder-tabs-row').height()+3;
        var contentHeight = (dialog) ? $('#visitor-info-'+elementId+'-placeholder').parent().height()-navHeight : $('#chat-info-body').height()-navHeight;
        var visBodyWidth = (dialog) ? $('#visitor-info-'+elementId+'-placeholder').parent().width() : $('#chat-info-body').width()-8;

        var htrh = $('#visitor-history-'+elementId+'-placeholder-tabs-row').height()+1;

        var showPreview = (lzm_chatDisplay.windowWidth > ChatArchiveClass.PreviewSwitchWidth);
        if(showPreview)
        {
            // desktop
            $('#matching-chats-'+elementId+'-inner-div').css({left:0, position: 'absolute', 'border-right': '1px solid #ccc', right: 451 + 'px', height: contentHeight});
            $('#matching-chats-'+elementId+'-inner').css({overflow:'auto', height: contentHeight});
            $('#chat-content-'+elementId+'-inner').css({display: 'block', overflow:'auto', position: 'absolute', right: 0, width: 450 + 'px', height: contentHeight});
        }
        else
        {
            // mobile
            $('#matching-chats-'+elementId+'-inner-div').css({position: 'absolute', 'border-width': '0px', right: 0, height: contentHeight});
            $('#matching-chats-'+elementId+'-inner').css({overflow:'auto', width: visBodyWidth + 'px', height: contentHeight});
            $('#chat-content-'+elementId+'-inner').css({display: 'none'});
        }

        $('#visitor-history-'+elementId+'-placeholder').css({height: (contentHeight) + 'px'});
        $('.visitor-history-'+elementId+'-placeholder-content').css({height: (contentHeight-htrh-1) + 'px'});
        $('.visitor-info-placeholder-content').css({height: (contentHeight + 7) + 'px'});
        $('#visitor-comment-'+elementId+'-list').css({'height': (contentHeight) + 'px'});
        $('#visitor-invitation-'+elementId+'-list').css({'height': (contentHeight) + 'px'});
        $('#visitor-details-'+elementId+'-list').css({'height': (contentHeight + 1) + 'px'});
        $('#visitor-cobrowse-'+elementId).css({'box-sizing':'border-box','height': (contentHeight-10) + 'px'});
        $('#visitor-cobrowse-'+elementId+'-browser-select').css({'min-width': '0px', width: (visBodyWidth - 40)+'px'});

        if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
            $('#visitor-cobrowse-'+elementId+'-iframe').css({height: (contentHeight - 80)+'px'});
        else
            $('#visitor-cobrowse-'+elementId+'-iframe-container').css({'-webkit-overflow-scrolling':'touch','overflow-y':'scroll',height: (contentHeight - 80)+'px'});

        $('#matching-tickets-'+elementId+'-inner').css({overflow:'auto','height': contentHeight + 'px'});
        $('#ticket-content-'+elementId+'-inner').css({'display':'none'});
        $('#matching-feedbacks-'+elementId+'-inner').css({overflow:'auto',height: (contentHeight) + 'px'});
    });
};

UIRendererClass.prototype.resizeVisitorInvitation = function() {
    if ($('#chat-invitation-body').length > 0)
    {
        var invTextHeight = Math.max((lzm_chatDisplay.dialogWindowHeight - 280), 100);
        $('#invitation-text-div').css({height:  invTextHeight+'px'});

        $('#invitation-text-inner').css({height:  (invTextHeight - 42)+'px'});
        $('#invitation-text').css({height: (invTextHeight - 50)+'px'});

        if((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
            $('#invitation-text-body').css({height: (invTextHeight - 78)+'px'});
    }
};

UIRendererClass.prototype.resizeOperatorForwardSelection = function() {
    var winObj = TaskBarManager.GetActiveWindow();
    if(winObj != null && winObj.TypeId == 'operator-forward-selection')
    {
        var fwdTextHeight = Math.max((lzm_chatDisplay.dialogWindowHeight - 260), 100);
        $('#forward-receiver').css({overflow:'auto',height: (fwdTextHeight) + 'px'});
    }
};

UIRendererClass.prototype.resizeMessageForwardDialog = function() {
    if ($('#message-forward-placeholder').length > 0)
    {
        var winObj = TaskBarManager.GetActiveWindow();
        if(winObj != null)
        {
            var filesHeight = (!isNaN(parseInt($('#forward-files').height()))) ? $('#forward-files').height() + 53 : 0;
            $('#forward-text').css({height: Math.max(100, $('#'+winObj.DialogId+'-body').height() - 235 - filesHeight) + 'px'});
        }
    }
};

UIRendererClass.prototype.resizeArchivedChat = function(elementId) {

    if ($('#matching-chats-body').length > 0) {
        var contentHeight = $('#matching-chats-body').height()-30;
        var upperFieldsetHeight = Math.floor(contentHeight / 3);
        var lowerFieldsetHeight = contentHeight - upperFieldsetHeight - 49;
        $('#matching-chats-'+elementId+'-inner').css({overflow:'auto','height': upperFieldsetHeight + 'px'});
        $('#chat-content-'+elementId+'-inner').css({overflow:'auto',height: (lowerFieldsetHeight+3) + 'px'});
    }
};

UIRendererClass.prototype.resizeFilterCreation = function() {
    if ($('#visitor-filter-body').length > 0) {
        var myHeight = $('#visitor-filter-body').height();
        var mainTableHeight = $('#visitor-filter-main-table').height();
        var baseTableHeight = $('#visitor-filter-base-table').height();
        var appliesTableHeight = $('#visitor-filter-applies-table').height();
        var fieldsetAddedheight = Math.floor((myHeight - 20 - 38 - (mainTableHeight + 22) - (baseTableHeight + 22) - (appliesTableHeight + 22)) / 3);
        var mainFieldsetHeight = mainTableHeight + fieldsetAddedheight;
        var baseFieldsetHeight = baseTableHeight + fieldsetAddedheight;
        var appliesFieldsetHeight = appliesTableHeight + fieldsetAddedheight;
        $('.visitor-filter-placeholder-content').css({height: '100%'});
        $('#visitor-filter-main').css({'min-height': mainFieldsetHeight + 'px'});
        $('#visitor-filter-base').css({'min-height': baseFieldsetHeight + 'px'});
        $('#visitor-filter-applies').css({'min-height': appliesFieldsetHeight + 'px'});
    }
};

UIRendererClass.prototype.resizeFilterList = function() {
    if ($('#filter-list').length > 0) {
        $('#filter-list-body').css({'overflow-x': 'auto'});
    }
};

UIRendererClass.prototype.resizeLinkGenerator = function() {
    if ($('#link-generator-dialog').length > 0) {
        $('#link-generator-dialog-body').css({'overflow-x': 'hidden'});
        var myHeight = $('#link-generator-dialog-body').height()-95;
        $('#elements-list-div, #code-list-div').css({'min-height': myHeight + 'px'});
        $('#inline-preview').css({'height': (myHeight+11) + 'px'});
    }
};

UIRendererClass.prototype.resizeDynamicGroupDialogs = function() {

};

UIRendererClass.prototype.resizeTranslateOptions = function() {

};

UIRendererClass.prototype.resizeChatView = function() {
    return;
};

UIRendererClass.prototype.resizeMenuPanels = function() {
    var settingsButtonWidth = (this.windowWidth > 500) ? 250 : this.windowWidth-144;

    $('#usersettings-menu table').css({'box-sizing':'border-box',width: (settingsButtonWidth-1)+'px'});
    $('#main-menu-panel-settings').css({width: (settingsButtonWidth)+'px'});
    $('#main-menu-panel-settings-text').css({width: (settingsButtonWidth - 50)+'px'});
    $('#main-menu-panel-tools-configuration').css({'margin-left': (settingsButtonWidth)+'px'});
    $('.main-menu-panel-opt-tools').css({display: (this.windowWidth>550) ? 'inline-block' : 'none'});
    $('#main-menu-info-box').css('display',(this.windowWidth > 1000) ?  'block' : 'none');
    $('.log-list-placeholder-content').css({height: ($('#log-viewer-body').height() - 50) + 'px'});
};

UIRendererClass.prototype.resizeStartPage = function() {
    if (lzm_chatDisplay.selected_view == 'home') {
        var startPageIframeCss = {border: '0px', width: '100%', height: '99%',
            'background-color': '#ffffff'};
        var numberOfStartPages = (lzm_chatDisplay.startPages.show_lz == 1) ? 1 : 0;
        for (var i=0; i<lzm_chatDisplay.startPages.others.length; i++) {
            numberOfStartPages++;
        }
        if (numberOfStartPages == 1) {
            this.resizeSingleStartPage();
        } else if (numberOfStartPages > 1)
        {
            $('.startpage-iframe').css(startPageIframeCss);
            $('.startpage-placeholder-content').css({'position': 'relative', 'min-height': (this.viewContainerHeight)+'px'})
            $('#startpage-placeholder-tabs-row').css({'margin-top': '4px','border':0})
        }
    }
};

UIRendererClass.prototype.resizeSingleStartPage = function() {
    if ($('#single-startpage-iframe').length > 0) {
        $('#single-startpage-iframe').css({width: '100%', height: '99%'});
    }
};

UIRendererClass.prototype.resizeMychats = function() {

    if(TaskBarManager.IsActiveChatWindow())
    {
        var cib = $('#chat-info-body');
        var mychatsInputControlsCss = {position: 'absolute', width: '0px', height: '0px', border: '0px', left: '0px', top: '0px', display: 'none'};
        var mychatsInputBodyCss = {position: 'absolute', right: 0, bottom:0,border: '0px', 'border-bottom-left-radius': '4px', 'border-bottom-right-radius': '4px', left: '0px', top: '0px','overflow-y': 'hidden'};
        var mychatsInputCss = {position: 'absolute', right:0, bottom:0, border: '0px', 'border-bottom-left-radius': '4px', 'border-bottom-right-radius': '4px', left: '0px', top: '0px','text-align': 'left', 'font-size': '12px', 'overflow': 'hidden'};
        var maxAutoKBHeight = Math.floor(lzm_chatDisplay.windowHeight/3);

        $('#chat-buttons').css({bottom:(ChatEditorClass.ExpandChatInputOffset + 100) + 'px'});
        $('#chat-qrd-preview').css({'max-height':maxAutoKBHeight+'px',bottom:(ChatEditorClass.ExpandChatInputOffset + 136) + 'px'});
        $('#chat-action').css({height:(ChatEditorClass.ExpandChatInputOffset + 96) + 'px'});
        $('#chat-members-minimize').css({bottom:(ChatEditorClass.ExpandChatInputOffset + 102) + 'px'});
        $('#chat-members-list').css({height:(lzm_chatDisplay.windowHeight-28) + 'px'});
        $('#chat-input-controls').css(mychatsInputControlsCss);
        $('#chat-input-body').css(mychatsInputBodyCss);
        $('#chat-input').css(mychatsInputCss);
        $('#chat-title').css({display: 'none'});

        if(cib.data('hidden')=='1' || lzm_chatDisplay.windowWidth < CommonUIClass.MinWidthChatVisitorInfo)
        {
            cib.css({display:'none'});
            $('#chat-controls').css({right:'0'});
        }
        else
        {
            cib.css({display:'block'});
            $('#chat-controls').css({right:CommonUIClass.ChatVisitorInfoWidth+'px'});
        }
    }
    else
        $('#chat-info-body').css({display:'none'});
};

UIRendererClass.prototype.resizeTicketList = function() {
    if (lzm_chatDisplay.selected_view == 'tickets')
    {
        var showPreview = (lzm_chatDisplay.windowWidth > ChatTicketClass.PreviewSwitchWidth);
        var showTree = (lzm_chatDisplay.windowWidth > ChatTicketClass.TreeSwitchWidth) && lzm_commonStorage.loadValue('show_ticket_tree_' + DataEngine.myId) != 0;
        var previewWidth = Math.floor(lzm_chatDisplay.windowWidth * 0.25);

        if(showPreview)
        {
            $('#ticket-list-actions').css({display: 'block',width: previewWidth+20+'px'});
            $('#ticket-list-right').css({display: 'block',width:previewWidth+'px'});
            $('#ticket-list-left').css({right: previewWidth+20+'px', 'border-width': '1px'});
        }
        else
        {
            $('#ticket-list-actions').css({display: 'none'});
            $('#ticket-list-right').css({display: 'none'});
            $('#ticket-list-left').css({right: '0px', 'border-width': '0px'});
        }

        if(showTree || lzm_chatDisplay.ticketDisplay.CategorySelect)
        {
            $('#ticket-list-left').css({left: '230px'});
        }
        else
        {
            $('#ticket-list-left').css({left: '0px'});
        }

        if(lzm_chatDisplay.ticketDisplay.CategorySelect)
        {
            $('#ticket-list-tree').css({display: 'block', width: 'auto', right:0, 'border-width' :0});
            $('#ticket-list-left').css({display: 'none'});
            $('#ticket-list-search-settings').css({width: 'auto', right:0, 'border-right' :0});
            $('#search-ticket').css({width:lzm_chatDisplay.windowWidth-55 + 'px'});
        }
        else if(lzm_chatDisplay.windowWidth > ChatTicketClass.TreeSwitchWidth)
        {
            $('#ticket-list-tree').css({display: 'block', width: '229px', right:'', 'border-width' :'1px'});
            $('#ticket-list-left').css({display: 'block'});
        }
        else if(!lzm_chatDisplay.ticketDisplay.CategorySelect)
        {
            $('#ticket-list-tree').css({display: 'none'});
            $('#ticket-list-left').css({display: 'block'});
        }
    }
};

UIRendererClass.prototype.resizeObjTranslator = function() {

    var winObj = TaskBarManager.GetActiveWindow();
    if(winObj != null && winObj.TypeId == 'object_translator')
    {
        var bodyHeight = $('#'+winObj.DialogId+'-body').height();
        var textAreaHeight = Math.floor((bodyHeight - 185) / 2);
        var textAreaCss = {'margin-top': '10px', height: textAreaHeight+'px'};
        $('#obj-translator-orig-text').css(textAreaCss);
        $('#obj-translator-translated-text').css(textAreaCss);
    }
};

UIRendererClass.prototype.resizeTicketLinker = function() {
};

UIRendererClass.prototype.resizeArchive = function() {
    if (lzm_chatDisplay.selected_view == 'archive') {
        var showPreview = (lzm_chatDisplay.windowWidth > ChatArchiveClass.PreviewSwitchWidth);
        if(showPreview){
            $('#archive-list-right').css({display: 'block'});
            $('#archive-list-left').css({left:0,right: '450px', 'border-width': '1px'});
        }
        else{
            $('#archive-list-right').css({display: 'none'});
            $('#archive-list-left').css({left:0,right: '0px', 'border-width': '0px'});
        }
    }
};

UIRendererClass.prototype.resizeQrdTree = function() {
};

UIRendererClass.prototype.resizeOperatorList = function() {
};

UIRendererClass.prototype.ResizeVisitorList = function() {
    if (lzm_chatDisplay.selected_view == 'external')
    {
        lzm_chatDisplay.VisitorsUI.OnResize();
        $('.visitor-simple-cell div').css({width:lzm_chatDisplay.windowWidth-160+'px'});
    }
};

UIRendererClass.prototype.resizeFilter = function() {
    if (lzm_chatDisplay.selected_view == 'filter') {
        var filterCss = lzm_commonTools.clone(this.viewContainerCss);
        var headlineCss = this.createHeadlineFromContainer(filterCss);
        var secondHeadlineCss = this.createSecondHeadlineFromContainer(filterCss);
        var bodyCss = this.createBodyFromContainer(filterCss, true, false);
        $('#filter').css(filterCss);
        $('#filter-headline').css(headlineCss);
        $('#filter-headline2').css(secondHeadlineCss);
        $('#filter-body').css(bodyCss);
    }
};

UIRendererClass.prototype.resizeAllChats = function() {
    if (lzm_chatDisplay.selected_view == 'mychats') {
        var allChatsCss = lzm_commonTools.clone(this.viewContainerCss);
        var secondHeadlineCss = this.createSecondHeadlineFromContainer(allChatsCss);
        var bodyCss = this.createBodyFromContainer(allChatsCss, true, false);
        bodyCss.top = '0px';
        bodyCss.height = (parseInt(bodyCss.height) - 30)+'px';
        bodyCss.overflow = 'auto';
        secondHeadlineCss.top = (parseInt(bodyCss.height)  + 10)+'px';

        var showTree = (lzm_chatDisplay.windowWidth > 700);
        var showObserver = (lzm_chatDisplay.windowWidth > 1000);

        if(showTree && showObserver && lzm_chatDisplay.ChatsUI.ObserverModePossible)
        {
            $('#all-chats-body').css({display: 'block',left: '230px',right: lzm_chatDisplay.ChatsUI.ObserverMode ? '401px' : '0'});
            $('#all-chats-preview').css({display: lzm_chatDisplay.ChatsUI.ObserverMode ? 'block' : 'none'});
            $('#all-chats-tree').css({display: 'block',width:'229px',right:''});
            $('#allchats-observer-mode').css({display: 'inline'});
            $('#allchats-tree-switch').css({display: 'none'});
        }
        else if(showTree)
        {
            $('#all-chats-body').css({display: 'block',left: '190px',right:'0'});
            $('#all-chats-preview').css({display: 'none'});
            $('#all-chats-tree').css({display: 'block',width:'189px',right:''});
            $('#allchats-observer-mode').css({display: 'none'});
            $('#allchats-tree-switch').css({display: 'none'});
        }
        else if(lzm_chatDisplay.ChatsUI.CategorySelect)
        {
            $('#all-chats-preview').css({display: 'none'});
            $('#all-chats-tree').css({display: 'block',right:'-1px',width:'auto'});
            $('#all-chats-body').css({display: 'none',left: '190px',right:'0px'});
            $('#allchats-observer-mode').css({display: 'none'});
            $('#allchats-tree-switch').css({display: 'inline'});
        }
        else
        {
            $('#all-chats-preview').css({display: 'none'});
            $('#all-chats-tree').css({display: 'none',right:''});
            $('#all-chats-body').css({display: 'block',left: '0'});
            $('#allchats-observer-mode').css({display: 'none'});
            $('#allchats-tree-switch').css({display: 'inline'});

        }
    }
};

UIRendererClass.prototype.resizeReportList = function() {
    if (lzm_chatDisplay.selected_view == 'reports') {

    }
};

UIRendererClass.prototype.resizeTranslationEditor = function() {
    if ($('#translation_editor').length > 0)
    {
        var bodyHeight = $('#translation_editor-body').height();
        var bodyWidth = $('#translation_editor-body').width();
        var scrollBarHeight = (lzm_displayHelper.checkIfScrollbarVisible('translation_editor-body', 'horizontal')) ? lzm_displayHelper.getScrollBarHeight() : 0;
        var fsScrollbarIsVisible = (lzm_chatDisplay.FullscreenDialogWindowWidth <= 1030) ? 'scroll' : 'hidden';
        var fsScrollBarHeight = (lzm_chatDisplay.FullscreenDialogWindowWidth <= 1030) ? lzm_displayHelper.getScrollBarHeight() : 0;
        var colHeight = bodyHeight - scrollBarHeight - fsScrollBarHeight - 38;
        var pfxArray = ['', 'srv-'];
        var langSelColWidth = 250;
        var langTableWidth = langSelColWidth - 22;
        var editorColWidth = Math.max(750, bodyWidth - langSelColWidth - 20);

        $('.translation-editor-placeholder-content').css({position: 'relative', height: (bodyHeight - 29)+'px', 'overflow-y': 'hidden', 'overflow-x': fsScrollbarIsVisible});
        $('#translation-editor-placeholder-tabs-row').css({position: 'relative', 'z-index': 1});
        for (var i=0; i<pfxArray.length; i++)
        {
            var pfx = pfxArray[i];
            $('#' + pfx + 'translation-language-selection').css({position: 'absolute', left: '5px', top: '4px', width: langSelColWidth+'px', height: colHeight+'px'});
            $('#' + pfx + 'translation-string-editor').css({position: 'absolute', left: '263px', top: '4px', width: editorColWidth+'px', height: colHeight+'px'});
            $('#' + pfx + 'translation-language-selection-inner').css({'min-height': (colHeight - 22)+'px'});
            $('#' + pfx + 'translation-string-editor-inner').css({'min-height': (colHeight - 22)+'px'});
            $('#' + pfx + 'translation-languages-bottom').css({position: 'absolute', bottom: '5px'});
            var leftButtonsHeight = $('#' + pfx + 'translation-languages-bottom').height();
            $('#' + pfx + 'translation-languages-top').css({position: 'absolute', top: '16px', height: (colHeight - leftButtonsHeight - 27)+'px', 'overflow-y': 'auto',border: '1px solid #ccc'});
            $('#' + pfx + 'translation-values-bottom').css({position: 'absolute', bottom: '6px', width: (editorColWidth - 38)+'px'});
            var rightButtonsHeight = $('#' + pfx + 'translation-values-bottom').height();
            $('#' + pfx + 'translation-values-top').css({position: 'absolute', top: '16px', height: (colHeight - rightButtonsHeight - 47)+'px', width: (editorColWidth - 40)+'px','overflow-y': 'auto', border: '1px solid #ccc'});
            $('#' + pfx + 'translation-search-string').css({width: '100%'});
            $('#' + pfx + 'translation-language-table').css({'width': langTableWidth+'px'});

            $('.translation-lang-btn').each(function() {
                var myBtnWidth = Math.max(0, $(this).width());
                if (myBtnWidth != 0) {
                    var myLeftPadding = Math.floor((langTableWidth - myBtnWidth) / 2);
                    var myRightPadding = Math.ceil((langTableWidth - myBtnWidth) / 2);
                    $(this).css({'padding-left': myLeftPadding+'px', 'padding-right': myRightPadding+'px'});
                }
            });
            $('#' + pfx + 'translation-string-add-language').css({'min-height': (colHeight + 16)+'px'});
        }
    }
};

UIRendererClass.prototype.resizeSendTranscriptDialog = function() {
    if ($('#send-transcript-to').length > 0) {
        var bodyHeight = $('#send-transcript-to-body').height();
        $('.send-transcript-placeholder-content').css({height: (bodyHeight - 40)+'px'});
        $('#send-transcript-to-inner').css({'min-height': (bodyHeight - 40 - 22)+'px'});
    }
};

UIRendererClass.prototype.resizeUserManagement = function() {
    if ($('#user-management-dialog').length > 0) {
        var myWidth = $('#user-management-dialog-body').width();
        var myHeight = $('#user-management-dialog-body').height();

        $('#user-management-iframe').css({width: (myWidth)+'px', height: (myHeight)+'px'});
    }
};


/************************************************** Some general tools **************************************************/
UIRendererClass.prototype.createHeadlineFromContainer = function(containerCss) {
    var headlineCss = {position: 'absolute', top: '0px', left: '0px', width: containerCss.width, height: '22px',
        'border-bottom': '1px solid #cccccc', 'border-radius': '0px', 'background-image': 'none',
        'background-color': '#f5f5f5', color: '#333333', 'text-shadow': 'none', 'font-weight': 'bold', 'font-size': '10px',
        'line-height': '0px', 'text-align': 'left', 'padding-left': '10px'};

    return headlineCss;
};

UIRendererClass.prototype.createSecondHeadlineFromContainer = function(containerCss) {
    var headlineCss = {position: 'absolute', top: '23px', left: '0px', width: (parseInt(containerCss.width) + 10)+'px',
        height: '28px', 'background-color': '#ededed', color: '#333333', 'text-shadow': 'none', margin: '0px'};

    return headlineCss;
};

UIRendererClass.prototype.createBodyFromContainer = function (containerCss, withSecondHeadline, withFootline) {
    var bodyHeight = (withSecondHeadline && withFootline) ? this.viewContainerHeight - 66 :
        (withSecondHeadline) ? this.viewContainerHeight - 48 :
            (withFootline) ? this.viewContainerHeight - 41 : this.viewContainerHeight - 23;
    var bodyTop = (withSecondHeadline) ? 51 : 23;
    var bodyCss = {position: 'absolute', 'text-align': 'left', width: containerCss.width, height: bodyHeight+'px',
        top: bodyTop+'px', left: '0px', overflow: 'hidden', padding: '5px', 'text-overflow': 'ellipsis'};

    return bodyCss;
};

UIRendererClass.prototype.getForm = function(formType,obj,name) {
    var contentHtml = '';
    var that = this;
    var showIconLine = false;
    var lzch = DataEngine.getConfigValue('gl_lzch',false) == 1;

    obj.m_Settings.forEach(function(entry_name) {
        if(entry_name.name==formType){

            var iconcss = (typeof entry_name.iconcss != 'undefined') ? ' style="'+entry_name.iconcss+'";' : '';

            if(showIconLine)
                contentHtml += '<i class="fa fa-'+((typeof entry_name.icon != 'undefined') ? entry_name.icon : 'cogs')+' lzm-tab-scroll-content-icon"'+iconcss+'></i>';

            entry_name.groups.forEach(function(entry_group)
            {
                if(!(lzch && $.inArray('lzch',entry_group.not) !== -1))
                    if($.inArray(obj.m_Type,entry_group.not) === -1 && $.inArray('all',entry_group.not) === -1)
                    {
                        var addClass = (typeof entry_group.class != 'undefined') ? ' '+entry_group.class : '';

                        contentHtml += '<fieldset class="lzm-fieldset-full'+addClass+'" id="'+name+'-configuration-'+entry_group.name+'"><legend>' + entry_group.title + '</legend><form>';

                        if(entry_group.custom === true || entry_group.custom == 'top')
                            contentHtml += obj.GetCustomForm(entry_group.name);

                        entry_group.controls.forEach(function(entry_control)
                        {
                            if(!(lzch && $.inArray('lzch',entry_control.not) !== -1))
                                if($.inArray(obj.m_Type,entry_control.not) === -1 && $.inArray('all',entry_control.not) === -1)
                                    contentHtml += that.getControlHTML(entry_control);

                        });

                        if(entry_group.custom == 'bottom')
                            contentHtml += obj.GetCustomForm(entry_group.name);

                        contentHtml += '</form></fieldset>';
                    }
            });
        }
    });
    contentHtml = '<div class="lzm-tab-scroll-content'+((showIconLine) ? ' lzm-tab-scroll-content-line' : '')+'">'+ contentHtml + '</div>';
    return contentHtml;
};

UIRendererClass.prototype.GetControlValue = function(controlDefinition) {
    try
    {
        if(controlDefinition.type == 'bool' || controlDefinition.type == 'radio')
            return $('#'+UIRenderer.getControlID(controlDefinition)).prop('checked');
        else if(controlDefinition.type == 'hidden' || controlDefinition.type == 'array' || controlDefinition.type == 'password' || controlDefinition.type == 'string' || controlDefinition.type == 'int' || controlDefinition.type == 'color' || controlDefinition.type == 'area')
            return $('#'+UIRenderer.getControlID(controlDefinition)).val();
        else if(controlDefinition.type == 'position')
            return ($(".lzm-position-selected",'#'+UIRenderer.getControlID(controlDefinition)).prop('id').replace(UIRenderer.getControlID(controlDefinition), ''));
    }
    catch(ex)
    {

    }
    return null;
};

UIRendererClass.prototype.getSettingsProperty = function(settings,propertyName) {
    var prop = null;
    settings.forEach(function(entry_name) {
        entry_name.groups.forEach(function(entry_group) {
            entry_group.controls.forEach(function(entry_control) {
                if(entry_control.name == propertyName)
                    prop = entry_control;
            });
        });
    });
    return prop;
};

UIRendererClass.prototype.getControlID = function(controlDefintion) {
    if(controlDefintion.type == 'bool')
        return 'cb-'+controlDefintion.name;
    else if(controlDefintion.type == 'array')
        return 'sl-'+controlDefintion.name;
    else if(controlDefintion.type == 'color')
        return 'ci-'+controlDefintion.name;
    else if(controlDefintion.type == 'position')
        return 'po-'+controlDefintion.name;
    else if(controlDefintion.type == 'int')
        return 'int-'+controlDefintion.name;
    else if(controlDefintion.type == 'string')
        return 's-'+controlDefintion.name;
    else if(controlDefintion.type == 'radio')
        return 'r-'+controlDefintion.name;
    else if(controlDefintion.type == 'area')
        return 'a-'+controlDefintion.name;
    else
        return controlDefintion.name;
}

UIRendererClass.prototype.getControlHTML = function(controlDefintion) {
    var contentHtml = '';
    var addClass = (typeof controlDefintion.class != 'undefined') ? controlDefintion.class : '';
    if(controlDefintion.type == 'bool')
        contentHtml = lzm_inputControls.createCheckbox(this.getControlID(controlDefintion), controlDefintion.title, controlDefintion.value, addClass, '');
    else if(controlDefintion.type == 'array')
        contentHtml = lzm_inputControls.createSelect(this.getControlID(controlDefintion), addClass, '', '', {position: 'right', gap: '0px'}, {}, '', controlDefintion.options, controlDefintion.value, '');
    else if(controlDefintion.type == 'color')
        contentHtml = lzm_inputControls.createColor(this.getControlID(controlDefintion), addClass, controlDefintion.value, controlDefintion.title, '');
    else if(controlDefintion.type == 'position')
        contentHtml += lzm_inputControls.createPosition(this.getControlID(controlDefintion), controlDefintion.value);
    else if(controlDefintion.type == 'int')
        contentHtml = lzm_inputControls.createInput(this.getControlID(controlDefintion), addClass, controlDefintion.value, controlDefintion.title, '', 'number', '', '', (typeof controlDefintion.titleright != 'undefined') ? controlDefintion.titleright : '', (typeof controlDefintion.titleleft != 'undefined') ? controlDefintion.titleleft : '');
    else if(controlDefintion.type == 'string' || controlDefintion.type == 'password'){

        var type = (controlDefintion.type == 'password') ? 'password' : 'text';
        var myData = (typeof controlDefintion.dataattr != 'undefined') ? ' data-attr-name="'+controlDefintion.dataattr+'"' : '';
        contentHtml = lzm_inputControls.createInput(this.getControlID(controlDefintion), addClass, controlDefintion.value, controlDefintion.title, '', type, '', myData, '', (typeof controlDefintion.titleleft != 'undefined') ? controlDefintion.titleleft : '');

        if(d(controlDefintion.titlebottom))
            contentHtml += '<div class="top-space-half lzm-info-text text-s">'+controlDefintion.titlebottom+'</div>';
    }
    else if(controlDefintion.type == 'radio')
        contentHtml = lzm_inputControls.createRadio(this.getControlID(controlDefintion), addClass, controlDefintion.group, controlDefintion.title, controlDefintion.value);
    else if(controlDefintion.type == 'div')
        contentHtml = '<div id="'+this.getControlID(controlDefintion)+'"></div>';
    else if(controlDefintion.type == 'label')
        contentHtml = '<label class="'+addClass+'">'+controlDefintion.title+'</label>';
    else if(controlDefintion.type == 'hidden')
        contentHtml = '<input id="'+this.getControlID(controlDefintion)+'" value="'+controlDefintion.value+'" type="hidden" />';
    else if(controlDefintion.type == 'link')
        contentHtml = '<a class="'+addClass+'" style="font-size:12px;" onclick="openLink(\''+controlDefintion.value+'\');" target="_blank">'+controlDefintion.title+'</a>';
    else if(controlDefintion.type == 'area')
        contentHtml = '<div class="top-space"><label>'+controlDefintion.title+'</label></div><div>' + lzm_inputControls.createArea(this.getControlID(controlDefintion), controlDefintion.value, addClass) + "</div>";

    var classes = "";
    if(typeof controlDefintion.top != 'undefined' && controlDefintion.top == 'single')
        classes += " top-space";
    if(typeof controlDefintion.top != 'undefined' && controlDefintion.top == 'half')
        classes += " top-space-half";
    if(typeof controlDefintion.top != 'undefined' && controlDefintion.top == 'double')
        classes += " top-space-double";
    if(typeof controlDefintion.bottom != 'undefined' && controlDefintion.bottom == 'single')
        classes += " bottom-space";

    if(typeof controlDefintion.left != 'undefined' && controlDefintion.left == 'single')
        contentHtml = '<div class="left-space-child'+classes+'">' + contentHtml + '</div>';
    else if(typeof controlDefintion.left != 'undefined' && controlDefintion.left == 'double')
        contentHtml = '<div class="left-space-child'+classes+'"><div class="left-space-child">' + contentHtml + '</div></div>';
    else if(typeof controlDefintion.left != 'undefined' && controlDefintion.left == 'triple')
        contentHtml = '<div class="left-space-child'+classes+'"><div class="left-space-child"><div class="left-space-child">' + contentHtml + '</div></div></div>';
    else if(classes.length)
        contentHtml = '<div class="'+ $.trim(classes) + '">' + contentHtml + '</div>';

    return contentHtml;
};
