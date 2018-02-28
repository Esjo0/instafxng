/****************************************************************************************
 * LiveZilla KnowledgebaseUI.js
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function KnowledgebaseUI() {
    this.SelectedResourceTab = 0;
    this.openedResourcesFolder = ['1'];
    this.KBSearchCategories = ['ti', 't', 'text'];
    this.qrdChatPartner = '';
    this.qrdSearchResults = [];
    this.CacheResourceList = null;
    this.ShowPreview = true;
}

KnowledgebaseUI.PreviewResourceId = null;
KnowledgebaseUI.CopyResource = null;
KnowledgebaseUI.CutResource = null;
KnowledgebaseUI.FileToUpload = null;
KnowledgebaseUI.TextEditor = null;
KnowledgebaseUI.QuickSearchReady = false;
KnowledgebaseUI.ShortCutResources = [];
KnowledgebaseUI.DraftResources = [];
KnowledgebaseUI.IsSyncing = false;
KnowledgebaseUI.IsLoading = false;
KnowledgebaseUI.CacheValid = false;
KnowledgebaseUI.TypeFolder = 0;
KnowledgebaseUI.TypeText = 1;
KnowledgebaseUI.TypeURL = 2;
KnowledgebaseUI.TypeFile = 3;
KnowledgebaseUI.AutoSearchTimer = null;

var checkEnding = function(fileName, ending) {
    ending = (typeof ending == 'string') ? [ending] : (typeof ending == 'object' && ending instanceof Array) ? ending : [];
    var fnLength = fileName.length, eLength = 0, rt = false;
    for (var i=0; i<ending.length; i++) {
        eLength = ending[i].length;
        rt = rt || (ending[i] != '' && fileName.indexOf('.' + ending[i]) == fnLength - eLength - 1);
    }
    return rt;
};

KnowledgebaseUI.prototype.CreateKBTopNode = function(caller, _context){

    if(!KnowledgebaseUI.CacheValid)
    {
        var i,that = this;
        var chatPartnerName = lzm_displayHelper.getChatPartner(_context)['name'];

        this.CacheResourceList = DataEngine.cannedResources.GetResourceList();

        that.qrdChatPartner = _context;

        var treeString = that.CreateKBTreeTopLevel(_context, false);
        var searchString = that.createQrdSearch(_context, false);

        var qrdTreeHtml = '<div id="qrd-tree-headline" class="lzm-dialog-headline"></div>' +
            '<div id="qrd-tree-body" class="lzm-dialog-body" onclick="removeKBContextMenu();">' +
                '<div id="qrd-tree-placeholder"></div></div>' +
            '<div id="qrd-tree-headline" class="lzm-dialog-headline2">' +
            '<span class="lzm-dialog-hl2-info" id="qrd-info"></span><span id="kb-button-line" style="padding-top:4px;float:right;">' + this.createButtonLine(caller, _context, chatPartnerName) + '</span></div>';

        $('#qrd-tree').html(qrdTreeHtml).trigger('create');

        lzm_displayHelper.createTabControl('qrd-tree-placeholder',
            [
                {name: t('All Resources'), content: treeString},
                {name: tid('search'), content: searchString}
            ],
            that.SelectedResourceTab);

        for (i=0; i<this.CacheResourceList.length; i++)
        {
            if ($('#folder-' + this.CacheResourceList[i].rid).html() == "")
            {
                $('#resource-' + this.CacheResourceList[i].rid + '-open-mark').css({background: 'none'})
            }
        }

        this.UpdateKBInfo();
        UIRenderer.resizeResources();

        for (i=0; i<that.openedResourcesFolder.length; i++)
            KnowledgebaseUI.HandleResourceClickEvents(that.openedResourcesFolder[i], true, false, _context);

        $('#search-qrd').keyup(function(e) {
            lzm_chatDisplay.searchButtonUp('qrd', this.CacheResourceList, e, false);
        });
        $('#search-resource').keyup(function(e) {
            lzm_chatDisplay.searchButtonUp('qrd-list', this.CacheResourceList, e, false);
        });
        $('.qrd-search-by').change(function() {
            setTimeout('lzm_chatDisplay.resourcesDisplay.fillQrdSearchList("'+that.qrdChatPartner+'", false);',10);
        });
        $('#search-resource-icon').click(function() {
            $('#search-resource').val('');
            $('#search-resource').keyup();
        });
        $('.qrd-tree-placeholder-tab').click(function() {

            var oldSelectedTabNo = that.SelectedResourceTab;
            that.SelectedResourceTab = parseInt($(this).data('tab-no'));
            $('#kb-button-line').html(that.createButtonLine(caller, _context, chatPartnerName));
            if (oldSelectedTabNo != that.SelectedResourceTab) {
                var newSelectedResource = lzm_chatDisplay.tabSelectedResources[that.SelectedResourceTab];
                lzm_chatDisplay.tabSelectedResources[oldSelectedTabNo] = lzm_chatDisplay.selectedResource;
                KnowledgebaseUI.HandleResourceClickEvents(newSelectedResource, true, false, _context);
            }
            if (that.SelectedResourceTab != 0)
                $('#add-qrd').addClass('ui-disabled');


            UIRenderer.resizeResources();
        });

        this.UpdateSelectedNodeUI();
    }
    KnowledgebaseUI.CacheValid = true;
};

KnowledgebaseUI.prototype.UpdateSelectedNodeUI = function(){
    var res = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    if(res != null)
    {
        if(res.ty != 0)
        {
            KnowledgebaseUI.HandleResourceClickEvents(lzm_chatDisplay.selectedResource,false);
        }
    }
};

KnowledgebaseUI.prototype.createButtonLine = function(caller){

    var html = '',sendButton=null,toChat='';
    var chatObj = DataEngine.ChatManager.GetChat();
    var isChatPartner = (chatObj != null && ((chatObj.GetStatus() != Chat.Closed && chatObj.GetMember(DataEngine.myId) != null) || chatObj.Type != Chat.Visitor));

    if(isChatPartner)
    {
        sendButton = lzm_inputControls.createButton('send-qrd-preview', 'lzm-button-b-active ui-disabled qrd-change-buttons', 'sendQrdPreview(\'\', \'' + chatObj.SystemId + '\');',t('To <!--chat-partner-->',[['<!--chat-partner-->',chatObj.GetName()]]), '', 'lr',{'margin-left': '2px'}, '', 30, '');
        toChat = chatObj.SystemId;
    }
    if (caller == 'view-select-panel')
    {
        if(sendButton!=null)
            html += sendButton;

        html += lzm_inputControls.createButton('add-qrd', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.ShowKBAddMenu(event);', tid('add'), '<i class="fa fa-plus"></i>', 'lr', {'margin-right': '4px'},'',-1,'e');
        html += lzm_inputControls.createButton('edit-qrd', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.EditEntry();', tid('edit'), '<i class="fa fa-edit"></i>', 'lr', {'margin-left': '0'},'',-1,'e');
        html += lzm_inputControls.createButton('delete-qrd', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.Remove();', tid('remove'), '<i class="fa fa-remove"></i>', 'lr', {'margin-left': '-1px','margin-right': '4px'},'',-1,'e');

        if(this.SelectedResourceTab==0)
            html += lzm_inputControls.createButton('sync-kb', '', 'KnowledgebaseUI.Synchronize();', tid('synchronize'), '<i class="fa fa-refresh"></i>', 'lr', {'margin-right': '4px'},'',-1,'e');
    }
    else
    {
        if(sendButton!=null)
            html += sendButton;

        html += lzm_inputControls.createButton('cancel-qrd', '', 'cancelQrd(\'\',\''+toChat+'\');', tid('cancel'), '', 'lr', {'margin-left': '5px'},'',30,'d');
    }
    return html;
};

KnowledgebaseUI.prototype.UpdateKBInfo = function(_count){

    if(!d(_count))
        _count = (d(this.CacheResourceList) && this.CacheResourceList != null) ? KnowledgebaseUI.CountVisibleEntries(this.CacheResourceList) : 0;

    if(KnowledgebaseUI.IsSyncing)
        $('#qrd-info').html('<b>'+tidc('loading',' ...')+'</b> (' + tid('total_entries',[['<!--total-->',_count]])+')');
    else
    {
        $('#qrd-info').html(tid('total_entries',[['<!--total-->',_count]]));
    }

    if(lzm_chatDisplay.windowWidth<500)
        $('#qrd-info').css({display:'none'});
};

KnowledgebaseUI.prototype.CreateKBTreeDialog = function(resources, _context, menuEntry, _ticketWindow) {

    var i,that = this;
    that.qrdChatPartner = _context;
    var saveResourceSelector = (_context.indexOf('TICKET SAVE') != -1);
    var loadResourceSelector = (_context.indexOf('TICKET LOAD') != -1);
    var hostElementId = _context.split('~')[1];
    var dialogId = 'qrd-tree-dialog-'+md5(hostElementId);

    this.CacheResourceList = DataEngine.cannedResources.GetResourceList();

    menuEntry = (typeof menuEntry != 'undefined') ? menuEntry : '';

    $('#qrd-tree-body').data('chat-partner', _context);
    $('#qrd-tree-body').data('in-dialog', true);

    var chatObj,closeToTicket = '',closeToChat='',storedDialogImage = '';
    if (_context.indexOf('TICKET LOAD') == -1 && _context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
    {
        chatObj = DataEngine.ChatManager.GetChat();
        closeToChat = chatObj.SystemId;
    }
    else
    {
        closeToTicket = hostElementId;
        storedDialogImage = '<i class="fa fa-envelope"></i>';
    }

    var sbTitle = (!saveResourceSelector) ? t('Save Resource'): tid('select_folder');
    var footerString = '';

    if (d(_context))
    {
        if (_context.indexOf('TICKET LOAD') == -1 && _context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
            footerString += lzm_inputControls.createButton('d-send-qrd-preview', 'ui-disabled qrd-change-buttons', 'sendQrdPreview(\'\', \'' + _context + '\');',t('To <!--chat-partner-->',[['<!--chat-partner-->',chatObj.GetName()]]), '', 'lr',{'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
        else if (_context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
            footerString +=  lzm_inputControls.createButton('insert-qrd-preview', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.InsertIntoTicket(\'' + closeToTicket + '\');',t('Insert Resource'), '', 'lr', {'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
        else if (_context.indexOf('ATTACHMENT') == -1)
            footerString +=  lzm_inputControls.createButton('add-or-edit-qrd', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.AddOrEditResourceFromTicket(\'' + closeToTicket + '\',\'' + dialogId + '\',null);',sbTitle, '', 'lr', {'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
        else
            footerString +=  lzm_inputControls.createButton('add-qrd-attachment', 'ui-disabled qrd-change-buttons', 'addQrdAttachment(\'' + closeToTicket + '\');',t('Attach Resource'), '', 'lr',{'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
    }

    footerString +=  lzm_inputControls.createButton('cancel-qrd', '', 'cancelQrd(\'' + closeToTicket + '\',\'' + closeToChat + '\');', t('Cancel'), '', 'lr', {'margin-left': '5px'},'',30,'d');

    var treeString = that.CreateKBTreeTopLevel(_context, true);
    var searchString = (!saveResourceSelector) ? that.createQrdSearch(_context, true) : '';

    var dialogData = {'exceptional-img': storedDialogImage};

    if (_context.indexOf('TICKET LOAD') == -1 && _context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
        dialogData = {'chat-partner': chatObj.SystemId, 'chat-partner-name': chatObj.GetName(), 'chat-partner-userid': chatObj.v};

    if (_context.indexOf('ATTACHMENT') != -1 || _context.indexOf('TICKET LOAD') != -1 || _context.indexOf('TICKET SAVE') != -1)
        dialogData.menu = menuEntry

    var headerString = (saveResourceSelector) ? tid('select_folder') : tid('knowledgebase');
    var bodyString = '<div id="qrd-tree-dialog-placeholder"></div>';

    var taskBarIndex = (_ticketWindow != null) ? _ticketWindow.TaskBarIndex : null;

    dialogId = lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'database',dialogId,dialogId,'cancel-qrd',true, dialogData, true, taskBarIndex);
    var tabList = [];

    tabList.push({name: t('All Resources'), content: treeString});
    if(!saveResourceSelector)
    {
        tabList.push({name: t('Quick Search'), content: searchString});
    }
    else
        tabList[0].name = tidc('select_folder','...');

    var selectedTab = that.SelectedResourceTab;

    if(saveResourceSelector)
        selectedTab = 0;
    else if(loadResourceSelector)
        selectedTab = 1;

    lzm_displayHelper.createTabControl('qrd-tree-dialog-placeholder', tabList, selectedTab);

    $('.qrd-tree-dialog-placeholder-content').css({height: ($('#qrd-tree-dialog-'+md5(hostElementId)+'-body').height() - 29) + 'px'});
    var resultListHeight = $('#qrd-tree-dialog-body').height() - $('#d-search-input').height() - 89;
    $('#d-search-results').css({'min-height': resultListHeight + 'px'});

    that.PopulateKBTree(this.CacheResourceList, _context, true);

    for (i=0; i<this.CacheResourceList.length; i++)
        if ($('#d-folder-' + this.CacheResourceList[i].rid).html() == "")
            $('#d-resource-' + this.CacheResourceList[i].rid + '-open-mark').css({background: 'none'});

    KnowledgebaseUI.HandleResourceClickEvents('1', true, false, _context);

    $('#d-search-resource').keyup(function(e) {
        lzm_chatDisplay.searchButtonUp('qrd-list', this.CacheResourceList, e, true);
    });
    $('.qrd-d-search-by').change(function() {
        setTimeout('lzm_chatDisplay.resourcesDisplay.fillQrdSearchList("'+that.qrdChatPartner+'", true);',10);
    });
    $('#d-search-resource-icon').click(function() {
        $('#d-search-resource').val('');
        $('#d-search-resource').keyup();
    });
    $('.qrd-tree-dialog-placeholder-tab').click(function() {
        var oldSelectedTabNo = that.SelectedResourceTab;
        UIRenderer.resizeResources();
        that.SelectedResourceTab = parseInt($(this).data('tab-no'));
        if (oldSelectedTabNo != that.SelectedResourceTab) {
            var newSelectedResource = lzm_chatDisplay.tabSelectedResources[that.SelectedResourceTab];
            lzm_chatDisplay.tabSelectedResources[oldSelectedTabNo] = lzm_chatDisplay.selectedResource;
            KnowledgebaseUI.HandleResourceClickEvents(newSelectedResource, true, false, _context);
        }
    });

    if(selectedTab==1)
        $('#d-search-resource').focus();
    UIRenderer.resizeResources();
    return dialogId;
};

KnowledgebaseUI.prototype.PopulateKBTree = function(resources, chatPartner, inDialog) {
    var sid = (inDialog) ? 'd-' : '';
    KnowledgebaseUI.PopulateFolder('1',sid);
};

KnowledgebaseUI.prototype.fillQrdSearchList = function(_caller, inDialog) {
    var that = this, searchCategories =  ['ti', 't', 'text'];

    that.KBSearchCategories = [];
    var sid = (inDialog) ? 'd-' : '';
    _caller = (inDialog) ? _caller : '';

    for (var i=0; i<searchCategories.length; i++)
        if ($('#'+sid+'search-by-' + searchCategories[i]).attr('checked') == 'checked')
            that.KBSearchCategories.push(searchCategories[i]);

    var searchString = $('#'+sid+'search-resource').val();
    if(d(searchString))
        searchString = $('#'+sid+'search-resource').val().replace(/^ */, '').replace(/ *$/, '');
    else
        searchString = '';

    $('#'+sid+'search-result-table').children('tbody').html(that.CreateQrdSearchResults(searchString, _caller, inDialog));
};

KnowledgebaseUI.prototype.highlightSearchResults = function(resources, isNewSearch) {
    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var that = this;
    if (isNewSearch) {
        var searchString = $('#search-qrd').val().replace(/^ */, '').replace(/ *$/, '').toLowerCase();
        if (searchString != '') {
            var i, j;
            that.qrdSearchResults = [];
            for (i=0; i<resources.length; i++) {
                if (resources[i].text.toLowerCase().indexOf(searchString) != -1 ||
                    resources[i].ti.toLowerCase().indexOf(searchString) != -1) {
                    that.qrdSearchResults.push(resources[i]);
                }
            }
        }
        else
            that.qrdSearchResults = [];
    }

    if (isNewSearch) {
        var openedResourceFolders = that.openedResourcesFolder;
        $('.resource-div').css({'background-color': '#FFF', color: '#000'});
        for (i=0; i<openedResourceFolders.length; i++) {
            KnowledgebaseUI.OpenOrCloseFolder(openedResourceFolders[i], false, true);
        }
    }
    for (i=0; i<that.qrdSearchResults.length; i++) {
        $('#'+sid+'resource-' + that.qrdSearchResults[i].rid).css({'background-color': '#FFFFC6', color: '#000', 'border-radius': '4px'});
        var parentId = that.qrdSearchResults[i].pid, counter = 0;
        if (isNewSearch) {
            while (parentId != 0 && counter < 1000) {
                for (j=0; j<resources.length; j++) {
                    if(resources[j].ty == 0 && resources[j].rid == parentId) {
                        KnowledgebaseUI.OpenOrCloseFolder(resources[j].rid, true, true);
                        parentId = resources[j].pid;
                    }
                }
                counter++;
            }
        }
    }
};

KnowledgebaseUI.prototype.UpdateResources = function(_newResources) {

    if(lzm_chatDisplay.selected_view == 'qrd')
        this.CacheResourceList = DataEngine.cannedResources.GetResourceList();
    else
        KnowledgebaseUI.CacheValid = false;

    var key,res, parentsToUpdate=[];
    for(key in _newResources)
    {
        res = _newResources[key];
        if(res.di != 0)
        {
            // REMOVE
            if ($('#resource-' + res.rid).length != 0)
                $('#resource-' + res.rid).remove();
        }
        else
        {
            // ADD,UPDATE
            if($.inArray(res.pid,parentsToUpdate)== -1)
                parentsToUpdate.push(res.pid);
        }
    }

    for(key in parentsToUpdate)
    {
        KnowledgebaseUI.PopulateFolder(parentsToUpdate[key],'',true);
    }

    this.UpdateKBInfo();

    if(lzm_chatDisplay.selectedResource != '')
        KnowledgebaseUI.HandleResourceClickEvents(lzm_chatDisplay.selectedResource,true);
};

KnowledgebaseUI.prototype.GetResourceIconHTML = function(resource) {
    var that = this;

    var resourceIcon;
    switch(resource.ty.toString())
    {
        case '0':
            resourceIcon = '<i class="fa fa-folder"></i>';
            break;
        case '1':
            resourceIcon = '<i class="fa fa-file-text icon-gray"></i>';
            break;
        case '2':
            if (typeof resource.text != 'undefined' && resource.text.indexOf('mailto:') == 0)
                resourceIcon = '<i class="fa fa-envelope icon-blue"></i>';
            else
                resourceIcon = '<i class="fa fa-link icon-red"></i>';
            break;
        default:
            resourceIcon = that.getFileTypeIcon(resource.ti);
            break;
    }
    return resourceIcon + this.GetPublicIconHTML(resource);
};

KnowledgebaseUI.prototype.getFileTypeIcon = function(fileName) {

    fileName = (d(fileName)) ? fileName.toLowerCase() : '';

    var fileIcon = '<i class="fa fa-file"></i>';
    if (checkEnding(fileName, ['mp3', 'wav', 'ogg', 'wma']))
        fileIcon = '<i class="fa fa-file-sound-o icon-orange"></i>';
    else if (checkEnding(fileName, ['png', 'gif', 'jpg', 'bmp', 'jpeg']))
        fileIcon = '<i class="fa fa-file-picture-o icon-blue"></i>';
    else if (checkEnding(fileName, ['doc', 'docx', 'odt', 'rtf'])) {
        fileIcon = '<i class="fa fa-file-word-o icon-blue"></i>';
    } else if (checkEnding(fileName, ['xls', 'xlsx', 'ods', 'csv'])) {
        fileIcon = '<i class="fa fa-file-excel-o icon-green"></i>';
    } else if (checkEnding(fileName, ['ppt', 'pptx', 'odp'])) {
        fileIcon = '<i class="fa fa-file-powerpoint-o"></i>';
    } else if (checkEnding(fileName, ['zip', 'rar', 'tar', 'tar.gz', 'tar.bz2', 'tar.xz', 'tgz', '7z'])) {
        fileIcon = '<i class="fa fa-file-archive-o icon-red"></i>';
    } else if (checkEnding(fileName, ['pdf', 'ps'])) {
        fileIcon = '<i class="fa fa-file-pdf-o icon-red"></i>';
    } else if (checkEnding(fileName, ['exe', 'bat'])) {
        fileIcon = '<i class="fa fa-gear icon-red"></i>';
    } else if (checkEnding(fileName, ['mpg', 'mpeg', 'avi', 'mp4', 'webm', 'mov', 'ogm', 'wmf'])) {
        fileIcon = '<i class="fa fa-file-movie-o"></i>';
    } else if (checkEnding(fileName, ['js', 'php', 'html', 'css', 'py', 'sh', 'pl', 'cs', 'java', 'c', '.c++', '.cpp']))
        fileIcon = '<i class="fa fa-file-code-o"></i>';

    return fileIcon;
};

KnowledgebaseUI.prototype.GetPublicIconHTML = function(resource) {
    var html = '';
    if(d(resource.p) && resource.p.toString()=='1')
        html += '<i class="fa fa-life-ring icon-blue icon-public"></i>';
    if(d(resource.ba) && resource.ba.toString()=='1')
        html += '<i class="fa fa-microchip icon-green icon-public icon-bot"></i>';
    return html;
};

KnowledgebaseUI.prototype.CreateKBTreeTopLevel = function(_context, inDialog) {

    var topLayerResource = DataEngine.cannedResources.getResource('1');
    var onclickAction = ' onclick="KnowledgebaseUI.HandleResourceClickEvents(\'' + topLayerResource.rid + '\',false,false,\'' + _context + '\')"';
    var onContextMenu = ' oncontextmenu="return false;"', that = this;

    if (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !inDialog)
        onContextMenu = ' oncontextmenu="KnowledgebaseUI.OpenKBContextMenu(event, \'' + _context + '\', \'' + topLayerResource.rid + '\');return false;"';

    var id = (inDialog) ? 'all-resources-dialog' : 'all-resources';
    var sid = (inDialog) ? 'd-' : '';
    var plusMinusImage = '';
    var resourceHtml = '<div id="'+id+'" class="lzm-fieldset">' +
        '<div id="'+id+'-inner"><div id="'+sid+'resource-' + topLayerResource.rid + '" class="resource-div lzm-unselectable">' +
        '<span class="resource-open-mark" id="'+sid+'resource-' + topLayerResource.rid + '-open-mark"' +
        onclickAction + onContextMenu + '>' + plusMinusImage + '</span>' +
        '<span class="resource-icon-and-text" id="'+sid+'resource-' + topLayerResource.rid + '-icon-and-text"' +
        onclickAction + onContextMenu + '>' + that.GetResourceIconHTML(topLayerResource) +
        '<span class="qrd-title-span">' + lzm_commonTools.htmlEntities(topLayerResource.ti) + '</span>' +
        '</span></div><div id="'+sid+'folder-' + topLayerResource.rid + '" style="display: none;"></div>' +
        '</div></div>';

    if(!inDialog)
        resourceHtml += '<div id="all-resources-preview" class="kb-preview-box"></div>';
    else
        resourceHtml += '<div id="all-resources-preview-dialog" class="kb-preview-box"></div>';

    return resourceHtml;
};

KnowledgebaseUI.prototype.SetPreview = function(_resource){

    var pd = null;
    if($('#all-resources-preview-dialog').length)
        pd = $('#all-resources-preview-dialog');
    else if($('#all-resources-preview').length)
        pd = $('#all-resources-preview');

    if(pd != null)
    {
        KnowledgebaseUI.PreviewResourceId = null;
        pd.html('');
        if(_resource!=null && _resource.rid.length>1)
        {
            KnowledgebaseUI.PreviewResourceId = _resource.rid;
            if(_resource.ty=='1'||_resource.ty=='0')
                pd.html(_resource.text);
            else if(_resource.ty=='2')
                pd.html('<a target="_blank" href="'+_resource.text+'">' + _resource.ti+'</a>');
            else if(_resource.ty=='3' && checkEnding(_resource.ti, ['png', 'gif', 'jpg', 'bmp', 'jpeg']))
            {
                pd.html('<img src="'+DataEngine.getServerUrl("getfile.php")+"?file=&id="+_resource.rid+'">');
            }
        }
    }
};

KnowledgebaseUI.prototype.UpdatePreview = function(){
    if(KnowledgebaseUI.PreviewResourceId != null)
    {
        var ures = DataEngine.cannedResources.getResource(KnowledgebaseUI.PreviewResourceId);
        if(ures != null)
        {
            if($('#all-resources-preview').html() != ures.text)
                this.SetPreview(ures);
        }
        else
            this.SetPreview(null);
    }
};

KnowledgebaseUI.prototype.createQrdSearch = function(chatPartner, inDialog) {
    var sid = (inDialog) ? 'd-' : '';
    var that = this, attachmentDataString = (chatPartner.indexOf('ATTACHMENT') != -1) ? ' data-attachment="1"' : ' data-attachment="0"';
    var searchHtml = '<div id="search-input">' +
        '<div class="lzm-fieldset"><table id="search-input-inner">' +
        '<tr><td colspan="2">' +
        lzm_inputControls.createInput(sid+'search-resource','', '', t('Search'), '<i class="fa fa-remove"></i>', 'text', 'b') +
        '</td>';
    var checkedString = ($.inArray('ti', that.KBSearchCategories) != -1) ? ' checked="checked"' : '';
    searchHtml += '<tr><td>' +
        '<input type="checkbox" class="checkbox-custom qrd-'+sid+'search-by" id="'+sid+'search-by-ti"' + checkedString + ' />' +
        '<label for="'+sid+'search-by-ti" class="checkbox-custom-label"></label>' +
        '</td>' +
        '<td>' + t('Title') + '</td></tr>';
    checkedString = ($.inArray('t', that.KBSearchCategories) != -1) ? ' checked="checked"' : '';
    searchHtml += '<tr><td><input type="checkbox" id="'+sid+'search-by-t" class="checkbox-custom qrd-'+sid+'search-by"' + checkedString + ' />' +
        '<label for="'+sid+'search-by-t" class="checkbox-custom-label"></label>' +
        '</td>' +
        '<td>' + t('Tags') + '</td></tr>';
    checkedString = ($.inArray('text', that.KBSearchCategories) != -1) ? ' checked="checked"' : '';
    searchHtml += '<tr><td><input type="checkbox" id="'+sid+'search-by-text" class="checkbox-custom qrd-'+sid+'search-by"' + checkedString + ' />' +
        '<label for="'+sid+'search-by-text" class="checkbox-custom-label"></label>' +
        '</td>' +
        '<td>' + t('Content') + '</td></tr>' +
        '</table></div>' +
        '</div><br>' +
        '<div id="'+sid+'search-results">' +
        '<div class="lzm-dialog-headline4"><span>' + t('Results') + '</span></div>' +
        '<div id="'+sid+'search-result-frame">' +
        '<table id="'+sid+'search-result-table" class="visible-list-table alternating-rows-table lzm-unselectable" style="width: 100%;"' + attachmentDataString + '><thead><tr>' +
        '<th></th><th>' + t('Title') + '</th><th>' + t('Tags') + '</th><th>' + t('Content') + '</th>' +
        '</tr></thead><tbody></tbody></table></div>' +
        '</div>';

    return searchHtml;
};

KnowledgebaseUI.prototype.CreateQrdSearchResults = function(searchString, _caller, inDialog) {
    var searchHtml = '', that = this;
    var sid = (inDialog) ? 'd-' : '';
    var resources = DataEngine.cannedResources.GetResourceList();
    var searchCategories = that.KBSearchCategories;
    $('#'+sid+'search-result-table').data('search-string', searchString);
    var resultIds = [];
    if (searchString != '')
    {
        for (var i=0; i<resources.length; i++)
        {
            for (var j=0; j<searchCategories.length; j++)
            {
                var contentToSearch = resources[i][searchCategories[j]].toLowerCase();
                if (resources[i].ty != 0 && contentToSearch.indexOf(searchString.toLowerCase()) != -1 && $.inArray(resources[i].rid, resultIds) == -1)
                {
                    if (resources[i].ty == 3 || resources[i].ty == 4 || $('#'+sid+'search-result-table').data('attachment') != '1')
                    {
                        searchHtml += that.CreateSearchResultLine(resources[i], searchString, _caller, inDialog);
                        resultIds.push(resources[i].rid);
                    }
                }
            }
        }
    }
    return searchHtml;
};

KnowledgebaseUI.prototype.CreateSearchResultLine = function(resource, searchString, _context, inDialog) {
    searchString = (typeof searchString != 'undefined') ? searchString : '';
    _context = (typeof _context != 'undefined') ? _context : '';
    var regExp = new RegExp(RegExp.escape(searchString), 'i'), that = this;
    var sid = (inDialog) ? 'd-' : '';
    var onclickAction = ' onclick="KnowledgebaseUI.HandleResourceClickEvents(\'' + resource.rid + '\',false,false, \'' + _context + '\');"';
    var onDblClickAction = '', onContextMenu = ' oncontextmenu="return false;"';

    if (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !inDialog)
        onContextMenu = ' oncontextmenu="KnowledgebaseUI.OpenKBContextMenu(event, \'' + _context + '\', \'' + resource.rid + '\');return false;"';

    if (_context.indexOf('TICKET SAVE') == -1)
        if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
        {
            if (_context.indexOf('TICKET LOAD') != -1)
                onDblClickAction = ' ondblclick="KnowledgebaseUI.InsertIntoTicket(\'' + _context.split('~')[1] + '\');"';
            else if (_context.indexOf('ATTACHMENT') != -1)
                onDblClickAction = ' ondblclick="addQrdAttachment(\'' + _context.split('~')[1] + '\');"';
            else if (inDialog && _context != '')
                onDblClickAction = ' ondblclick="sendQrdPreview(\'' + resource.rid + '\', \'' + _context + '\');"';
            else
                onDblClickAction = ' ondblclick="KnowledgebaseUI.EditEntry();"';
        }


    var content = ($.inArray(parseInt(resource.ty), [3,4]) == -1) ? resource.text.replace(/<.*?>/g, ' ') : '';

    content = lzm_commonTools.HightlightSearchKey(content,searchString);

    var title = lzm_commonTools.htmlEntities(resource.ti).replace(/<.*?>/g, ' ');
    if(title.length > 200)
        title = title.substring(0,200)+" ...";
    //title = title.replace(regExp, '<span class="search-highlight">' + searchString + '</span>');
    title = lzm_commonTools.HightlightSearchKey(title,searchString);

    var searchLineHtml = '<tr style="cursor: pointer;" class="qrd-search-line lzm-unselectable" id="qrd-'+sid+'search-line-' + resource.rid + '"' + onclickAction + onDblClickAction + onContextMenu + '>' +
        '<td class="icon-column resource-icon-and-text">' + that.GetResourceIconHTML(resource,false) + '</td>' +
        '<td>' + title + '</td>' +
        '<td>' + resource.t + '</td>' +
        '<td>' + content + '</td>' +
        '</tr>';
    return searchLineHtml;
};

KnowledgebaseUI.prototype.GetResourceHTML = function(resource, _context, inDialog) {

    _context = (typeof _context != 'undefined') ? _context : '';
    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;

    var sid = (inDialog) ? 'd-' : '';
    var onclickAction = ' onclick="KnowledgebaseUI.HandleResourceClickEvents(\'' + resource.rid + '\',false,false,\''+_context+'\')"';
    var onDblClickAction = '', that = this;
    var onContextMenu = ' oncontextmenu="return false;"';

    if (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !inDialog)
        onContextMenu = ' oncontextmenu="KnowledgebaseUI.OpenKBContextMenu(event, \'' + _context + '\', \'' + resource.rid + '\');return false;"';

    var resourceHtml = '<div id="'+sid+'resource-' + resource.rid + '" class="resource-div lzm-unselectable" style="padding-left: ' + (20 * resource.ra) + 'px;">';
    if (resource.ty == 0)
    {
        var openMarkIcon = (KnowledgebaseUI.GetChildNodes(resource.rid).length > 0) ? '<i class="fa fa-caret-right"></i>' : '';
        resourceHtml += '<span class="resource-open-mark" id="'+sid+'resource-' + resource.rid + '-open-mark"' + onclickAction + onContextMenu + '>' + openMarkIcon + '</span>';
    }
    else
    {
        resourceHtml += '<span class="resource-empty-mark"></span>';
        if (_context.indexOf('TICKET SAVE') == -1)
            if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
            {
                if (_context.indexOf('TICKET LOAD') != -1)
                {
                    onDblClickAction = ' ondblclick="KnowledgebaseUI.InsertIntoTicket(\'' + _context.split('~')[1] + '\');"';
                }
                else if (_context.indexOf('ATTACHMENT') != -1)
                {
                    onDblClickAction = ' ondblclick="addQrdAttachment(\'' + _context.split('~')[1] + '\');"';
                }
                else if (inDialog && _context != '')
                {
                    onDblClickAction = ' ondblclick="sendQrdPreview(\'' + resource.rid + '\', \'' + _context + '\');"';
                }
                else
                {
                    onDblClickAction = ' ondblclick="KnowledgebaseUI.EditEntry();"';
                }
            }
    }
    resourceHtml += '<span class="resource-icon-and-text" id="'+sid+'resource-' + resource.rid + '-icon-and-text"' +
        onclickAction + onDblClickAction + onContextMenu + '>' +
        that.GetResourceIconHTML(resource) +
        '<span class="qrd-title-span">' +
        lzm_commonTools.htmlEntities(resource.ti) + '</span>' +
        '</span></div>';

    if (resource.ty == 0)
        resourceHtml += '<div id="'+sid+'folder-' + resource.rid + '" style="display: none;"></div>';

    return resourceHtml;
};

KnowledgebaseUI.prototype.CreateKBTreeContextMenu = function(myObject){
    var contextMenuHtml = '',disabledClass;

    contextMenuHtml += '<div onclick="showSubMenu(\'qrd-tree\', \'kb_add\', \'\', %CONTEXTX%, %CONTEXTY%, %MYWIDTH%, %MYHEIGHT%)"><span class="cm-line cm-click">' + tid('add') + '</span><i class="fa fa-caret-right lzm-ctxt-right-fa"></i></div><hr />';

    disabledClass = (myObject == 'MENU' || myObject.rid == 1 || myObject.ty == 0 || DataEngine.ChatManager.Chats.length==0) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="showSubMenu(\'qrd-tree\', \'kb_send\', \'\', %CONTEXTX%, %CONTEXTY%, %MYWIDTH%, %MYHEIGHT%)"><span class="cm-line cm-click">' + tid('send_to') + '</span><i class="fa fa-caret-right lzm-ctxt-right-fa"></i></div><hr />';

    disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.__Copy();"><span class="cm-line">' + tid('copy') + '</span></div>';

    disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.__Cut();"><span class="cm-line">' + tid('cut') + '</span></div>';

    disabledClass = (myObject != 'MENU' && (KnowledgebaseUI.CopyResource==null&&KnowledgebaseUI.CutResource==null)) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.__Paste();"><span class="cm-line">' + tid('paste') + '</span></div><hr />';

    disabledClass = (myObject != 'MENU' && (myObject.rid.length == 1)) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.EditEntry();"><span id="edit-qrd-ctxt" class="cm-line">' + tid('edit') + '</span></div>';

    disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.Remove();"><span id="delete-qrd-ctxt" class="cm-line">' + tid('delete') + '</span></div><hr />';

    disabledClass = (myObject != 'MENU' && (myObject.ty < 3 || myObject.ty > 4)) ? ' class="ui-disabled"' : '';
    contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.DownloadFile();">' + '<i class="fa fa-download"></i><span id="show-qrd-download-ctxt" class="cm-line cm-line-icon-left">' + tid('save') + '</span></div>';

    if(this.SelectedResourceTab == 1)
    {
        contextMenuHtml += '<hr /><div onclick="KnowledgebaseUI.ShowInTreeView();">' + '<span id="show-qrd-download-ctxt" class="cm-line">' + tid('show') + '</span></div>';
    }

    return contextMenuHtml;
};

KnowledgebaseUI.prototype.CalculateRank = function(_resource,_rank){
    _rank = (d(_rank)) ? _rank : 0;
    var p = DataEngine.cannedResources.getResource(_resource.pid);
    if(p==null)
        return _rank;
    else
        return this.CalculateRank(p,_rank+1);
};

KnowledgebaseUI.prototype.IsParentOf = function(_a,_b){

    if(_b.pid == _a.pid)
    {
        return false;
    }

    if(_b.pid == _a.rid)
    {
        return true;
    }

    var parent = DataEngine.cannedResources.getResource(_b.pid);
    if(parent == null)
        return false;
    else if(parent.rid==_a.pid && parent.rid != '1')
    {
        return true;
    }
    else
        return this.IsParentOf(_a,parent);
};

KnowledgebaseUI.__Copy = function(){
    removeKBContextMenu();
    KnowledgebaseUI.CutResource = null;
    KnowledgebaseUI.CopyResource = lzm_chatDisplay.selectedResource;
};

KnowledgebaseUI.__Cut = function(){
    removeKBContextMenu();

    if (!lzm_commonPermissions.checkUserResourceWritePermissions(lzm_chatDisplay.myId, 'edit', DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource)))
    {
        showNoPermissionMessage();
        return;
    }

    KnowledgebaseUI.CopyResource = null;
    KnowledgebaseUI.CutResource = lzm_chatDisplay.selectedResource;
};

KnowledgebaseUI.__Paste = function(){
    removeKBContextMenu();
    var actionNode=null,action='';

    if(KnowledgebaseUI.CutResource != null)
    {
        action = 'cut';
        actionNode = DataEngine.cannedResources.getResource(KnowledgebaseUI.CutResource);
        KnowledgebaseUI.CutResource = null;
    }
    else if(KnowledgebaseUI.CopyResource != null)
    {
        action = 'copy';
        actionNode = DataEngine.cannedResources.getResource(KnowledgebaseUI.CopyResource);
    }

    var targetFolder = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);

    if(targetFolder.ty != '0')
        targetFolder = DataEngine.cannedResources.getResource(targetFolder.pid);

    if (!lzm_commonPermissions.checkUserResourceWritePermissions(lzm_chatDisplay.myId, 'add', targetFolder))
    {
        showNoPermissionMessage();
        return;
    }

    if(actionNode != null && targetFolder != null)
    {
        if(actionNode.rid != targetFolder.rid || action == 'copy')
        {
            if(action == 'cut' && actionNode.ty == '0' && lzm_chatDisplay.resourcesDisplay.IsParentOf(actionNode,targetFolder))
            {
                return;
            }

            if(action == 'cut')
            {
                $('#resource-'+actionNode.rid).remove();
            }
            CommunicationEngine.PollServerResource({First:actionNode,Second:targetFolder},action);
        }
    }
};

KnowledgebaseUI.__ShowKBEntrySettings = function(_resourceId, caller, editorText){
    removeKBContextMenu();
    _resourceId = (_resourceId == null) ? lzm_chatDisplay.selectedResource : _resourceId;
    var resource = DataEngine.cannedResources.getResource(_resourceId);

    if (resource == null && d(KnowledgebaseUI.DraftResources[_resourceId]))
    {
        resource = KnowledgebaseUI.DraftResources[_resourceId];
    }

    if (resource == null)
    {
        resource = {t: ''};
        if (_resourceId == 'FOLDER')
            resource.ty = 0;
    }

    if (caller == 'edit-resource' || caller == 'add-resource' || _resourceId == 'TEXT_FILE_URL' ||  caller == 'qrd-tree' || _resourceId == 'FOLDER')
    {
        if(lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource))
        {
            lzm_chatDisplay.resourcesDisplay.ShowKBEntrySettings(resource, editorText, caller);
        }
        else
            showNoPermissionMessage();
    }

};

KnowledgebaseUI.RunsInDialog = function(){
    var winObj = TaskBarManager.GetActiveWindow();
    return (winObj != null);
};

KnowledgebaseUI.UpdateSelection = function(){


};

KnowledgebaseUI.DownloadFile = function(){
    removeKBContextMenu();
    var resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    openLink(KnowledgebaseUI.GetFileURL(resource));
};

KnowledgebaseUI.GetFileURL = function(_resource){
    var thisServer = CommunicationEngine.chosenProfile.server_protocol + CommunicationEngine.chosenProfile.server_url;

    if(!thisServer.endsWith('/'))
        thisServer += '/';

    return thisServer + 'getfile.php?file=' + _resource.ti + '&id=' + _resource.rid;
};

KnowledgebaseUI.ResourceIsFile = function(_resource){
    return $.inArray(_resource.ty, ['2', '3', '4']) != -1
};

KnowledgebaseUI.GetAccessUrl = function(_resource){
    if(!KnowledgebaseUI.ResourceIsFile(_resource))
    {
        var thisServer = CommunicationEngine.chosenProfile.server_protocol + CommunicationEngine.chosenProfile.server_url;

        thisServer = thisServer.replace(':80/','/').replace(':443/','/');

        if(!thisServer.endsWith('/'))
            thisServer += '/';

        thisServer += 'knowledgebase.php?article=' + lzm_commonTools.htmlEntities(_resource.rid);
        return thisServer;
    }
    else
        return KnowledgebaseUI.GetFileURL(_resource);
};

KnowledgebaseUI.GetURL = function(_resource){
    if(KnowledgebaseUI.ResourceIsFile(_resource))
    {
        return KnowledgebaseUI.GetFileURL(_resource);
    }
    else if(_resource.ty>0)
    {
        return KnowledgebaseUI.GetAccessUrl(_resource);
    }
    return '';
};

KnowledgebaseUI.ShowKBAddMenu = function(e){
    KnowledgebaseUI.OpenKBContextMenu(e,'LIST','MENU');
    e.stopPropagation();
};

KnowledgebaseUI.OpenKBContextMenu = function(e, _context, resourceId){

    if(resourceId != 'MENU')
    {
        lzm_chatDisplay.selectedResource = resourceId;
        KnowledgebaseUI.HandleResourceClickEvents(resourceId, true, !IFManager.IsMobileOS, _context);
    }
    else if(lzm_chatDisplay.selectedResource != '')
    {
        /*var sel = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
        if(sel != null)
        {
            //if(sel.ty == 0)
              //  KnowledgebaseUI.OpenOrCloseFolder(sel.rid, true, true);
        }
        */
    }
    var resource = (resourceId != 'MENU') ? DataEngine.cannedResources.getResource(resourceId) : 'MENU';
    var scrolledDownY = (resourceId != 'MENU') ? $('#qrd-tree-body').scrollTop() : 15;
    var scrolledDownX = (resourceId != 'MENU') ? $('#qrd-tree-body').scrollLeft() : -15;
    var parentOffset = $('#qrd-tree-body').offset();
    var yValue = e.pageY - parentOffset.top;
    var xValue = e.pageX - parentOffset.left;
    if (resource != null && resource != 'MENU')
    {
        resource.chatPartner = _context;
        lzm_chatDisplay.showContextMenu('qrd-tree', resource, xValue + scrolledDownX, yValue + scrolledDownY);
        e.preventDefault();
    }else{
        var cm = {
            id: 'qrd-tree-context',
            entries: [
                {
                    label: tid('text'),
                    onClick: 'KnowledgebaseUI.ShowEntry(null,1);'
                },
                {
                    label: tid('link'),
                    onClick: 'KnowledgebaseUI.ShowEntry(null,2);'
                },
                {
                    label: tid('file'),
                    onClick: 'KnowledgebaseUI.ShowEntry(null,3);'
                },
                {
                    label: tid('resource_folder'),
                    onClick: 'KnowledgebaseUI.ShowEntry(null,0);'
                }
            ]
        };
        if(IFManager.IsDesktopApp() && d(IFManager.DeviceInterface.hasModule) && IFManager.DeviceInterface.hasModule('lz-screenshot-widget'))
        {
            cm.entries[4] = $.extend(true, {}, cm.entries[3]);
            cm.entries[3] = {
                label: tid('screenshot'),
                onClick: "IFManager.IFScreenCast(\'knowledgebase\', \'' + lzm_chatDisplay.selectedResource + '\');lzm_chatDisplay.RemoveAllContextMenus();",
            };
        }
        ContextMenuClass.BuildMenu(e, cm);
    }
};

KnowledgebaseUI.HandleResourceClickEvents = function(resourceId, onlyOpenFolders, _rightClick, _context){
    removeKBContextMenu();

    onlyOpenFolders = (d(onlyOpenFolders)) ? onlyOpenFolders : false;
    _rightClick = d(_rightClick) ? _rightClick : false;

    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var resource = DataEngine.cannedResources.getResource(resourceId);

    lzm_chatDisplay.resourcesDisplay.SetPreview(resource);
    lzm_chatDisplay.selectedResource = resourceId;

    if (resource != null)
    {
        var parentFolder = DataEngine.cannedResources.getResource(resource.pid);
        var wasSelected = $('#'+sid+'resource-' + resourceId).hasClass('selected-resource-div');

        $('.resource-div').removeClass('selected-resource-div');
        $('.qrd-search-line').removeClass('selected-table-line');
        $('.qrd-recently-line').removeClass('selected-table-line');
        $('.resource-open-mark').removeClass('resource-open-mark-selected');
        $('.resource-icon-and-text').removeClass('resource-icon-and-text-selected');

        lzm_chatDisplay.resourcesDisplay.highlightSearchResults(DataEngine.cannedResources.CacheResourceList, false);

        $('#'+sid+'resource-' + resourceId).addClass('selected-resource-div');

        $('#qrd-'+sid+'search-line-' + resourceId).addClass('selected-table-line');
        $('#qrd-'+sid+'recently-line-' + resourceId).addClass('selected-table-line');
        $('#'+sid+'resource-' + resourceId + '-open-mark').addClass('resource-open-mark-selected');
        $('#'+sid+'resource-' + resourceId + '-icon-and-text').addClass('resource-icon-and-text-selected');
        $('.qrd-change-buttons').addClass('ui-disabled');

        switch (parseInt(resource.ty))
        {
            case 0:
                if(!_rightClick)
                    KnowledgebaseUI.OpenOrCloseFolder(resourceId, onlyOpenFolders, wasSelected, _context);
                if (resourceId.length > '1' && lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#edit-qrd').removeClass('ui-disabled');
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }

                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', resource))
                    $('#add-qrd').removeClass('ui-disabled');

                if (resourceId.length > '1' && lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', resource)) {
                    $('#add-or-edit-qrd').removeClass('ui-disabled');
                }
                $('#add-qrd-attachment').addClass('ui-disabled');
                break;
            case 1:
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#edit-qrd').removeClass('ui-disabled');
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }
                $('#view-qrd').removeClass('ui-disabled');

                $('#'+sid+'send-qrd-preview').removeClass('ui-disabled');

                $('#insert-qrd-preview').removeClass('ui-disabled');
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', resource)) {
                    $('#add-or-edit-qrd').removeClass('ui-disabled');
                }
                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && parentFolder != null && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                    $('#add-qrd').removeClass('ui-disabled');

                $('#add-qrd-attachment').addClass('ui-disabled');
                break;
            case 2:
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#edit-qrd').removeClass('ui-disabled');
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }
                $('#view-qrd').removeClass('ui-disabled');

                $('#'+sid+'send-qrd-preview').removeClass('ui-disabled');

                $('#insert-qrd-preview').removeClass('ui-disabled');

                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && parentFolder != null && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                    $('#add-qrd').removeClass('ui-disabled');

                $('#add-qrd-attachment').addClass('ui-disabled');
                break;

            default:
                $('#edit-qrd').removeClass('ui-disabled');
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }

                $('#'+sid+'send-qrd-preview').removeClass('ui-disabled');

                $('#insert-qrd-preview').removeClass('ui-disabled');

                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && parentFolder != null && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                    $('#add-qrd').removeClass('ui-disabled');

                $('#add-qrd-attachment').removeClass('ui-disabled');
                break;
        }
    }
};

KnowledgebaseUI.GetResourceStorageHash = function(_resId){
    return md5(_resId).substr(0,7);
};

KnowledgebaseUI.Synchronize = function(){

    KnowledgebaseUI.HandleResourceClickEvents('1');

    var resourceIdList = lzm_commonStorage.loadValue('kb_list_' + DataEngine.myId,'');
    if(resourceIdList != null)
    {
        try
        {
            resourceIdList = LocalConfiguration.ParseKnowledgeBaseShortList(resourceIdList);
            for(var key in resourceIdList)
            {
                lzm_commonStorage.deleteKeyValuePair('kb_i_' + DataEngine.myId + resourceIdList[key]);
            }
        }
        catch(ex)
        {
            deblog(resourceIdList);
            deblog(ex);
        }
    }

    lzm_commonStorage.deleteKeyValuePair('kb_dut_' + DataEngine.myId);
    lzm_commonStorage.deleteKeyValuePair('kb_list_' + DataEngine.myId);

    KnowledgebaseUI.CacheValid = false;
    KnowledgebaseUI.IsSyncing = true;

    DataEngine.resourceIdList = [];
    lzm_chatDisplay.resourcesDisplay.CacheResourceList = [];

    DataEngine.resourceLastEdited = 0;
    CommunicationEngine.qrdRequestTime = 1;
    DataEngine.cannedResources = new LzmResources();
    KnowledgebaseUI.CreateRootResource();

    //lzm_chatDisplay.resourcesDisplay.UpdateResources();
    lzm_chatDisplay.resourcesDisplay.UpdateKBInfo(0);
};

KnowledgebaseUI.RenderFolderArrow = function(_resourceId,_hasChildren){
    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var markDiv = $('#'+sid+'resource-' + _resourceId + '-open-mark');

    if(!d(_hasChildren))
    {
        var folderDiv = $('#'+sid+'folder-' + _resourceId);
        _hasChildren = (folderDiv.html() != '');
    }

    if(_hasChildren)
    {
        if ($.inArray(_resourceId, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) == -1)
            markDiv.html('<i class="fa fa-caret-right"></i>');
        else
            markDiv.html('<i class="fa fa-caret-down"></i>');
    }
    else
        markDiv.html('');
};

KnowledgebaseUI.OpenOrCloseFolder = function(resourceId, onlyOpenFolders, wasSelected, _context) {
    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var folderDiv = $('#'+sid+'folder-' + resourceId);

    KnowledgebaseUI.PopulateFolder(resourceId,sid,false,_context);

    if (folderDiv.html() != "")
    {
        var markDiv = $('#'+sid+'resource-' + resourceId + '-open-mark');
        if (folderDiv.css('display') == 'none')
        {
            folderDiv.css('display', 'block');
            markDiv.html('<i class="fa fa-caret-down"></i>');
            if ($.inArray(resourceId, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) == -1)
            {
                lzm_chatDisplay.resourcesDisplay.openedResourcesFolder.push(resourceId);
            }
        }
        else if (!onlyOpenFolders)
        {
            if(!wasSelected)
                return;

            folderDiv.css('display', 'none');
            markDiv.html('<i class="fa fa-caret-right"></i>');
            var tmpOpenedFolder = [];
            for (var i=0; i<lzm_chatDisplay.resourcesDisplay.openedResourcesFolder.length; i++) {
                if (resourceId != lzm_chatDisplay.resourcesDisplay.openedResourcesFolder[i]) {
                    tmpOpenedFolder.push(lzm_chatDisplay.resourcesDisplay.openedResourcesFolder[i]);
                }
            }
            lzm_chatDisplay.resourcesDisplay.openedResourcesFolder = tmpOpenedFolder;
        }
    }
};

KnowledgebaseUI.PopulateFolder = function(_resourceId,_sid,_onlyExpanded,_context){

    _onlyExpanded = d(_onlyExpanded) ? _onlyExpanded : false;

    if (_onlyExpanded && $.inArray(_resourceId, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) == -1)
        return;

    if(d(DataEngine.cannedResources.objects[_resourceId]))
    {
        var res,childNodes = KnowledgebaseUI.GetChildNodes(_resourceId);
        if(childNodes.length)
        {
            $('#'+_sid+'folder-' + _resourceId).html('');
            for(var key in childNodes)
            {
                res = childNodes[key];
                res.ra = lzm_chatDisplay.resourcesDisplay.CalculateRank(res,0);
                var resourceHtml = lzm_chatDisplay.resourcesDisplay.GetResourceHTML(res, _context, KnowledgebaseUI.RunsInDialog());
                $('#'+_sid+'folder-' + res.pid).append(resourceHtml);

                if ($.inArray(res.rid, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) != -1)
                {
                    KnowledgebaseUI.OpenOrCloseFolder(res.rid,true);
                }

            }
        }
    }
};

KnowledgebaseUI.ReplaceTitle = function(_resource){
    _resource.ti = _resource.ti
        .replace(/%%_Files_%%/, tid('files'))
        .replace(/%%_External_%%/, tid('visitors'))
        .replace(/%%_Internal_%%/, tid('operators'));
    return _resource;
};

KnowledgebaseUI.GetChildNodes = function(_resourceId){

    var list=[],tlist = [];

    if(lzm_chatDisplay.resourcesDisplay.CacheResourceList != null)
        tlist = lzm_commonTools.GetElementByProperty(lzm_chatDisplay.resourcesDisplay.CacheResourceList,'pid',_resourceId);

    var parent = (_resourceId=='1') ? KnowledgebaseUI.GetRootResource() : lzm_commonTools.GetElementByProperty(lzm_chatDisplay.resourcesDisplay.CacheResourceList,'rid',_resourceId)[0];
    for (var i=0; i<tlist.length; i++) {

        var res = tlist[i];
        if (lzm_commonPermissions.checkUserResourceReadPermission(lzm_chatDisplay.myId, res, parent))
            list.push(res);
    }
    return list;
};

KnowledgebaseUI.GetRootResource = function(){
    return {
        di: "0",
        ed: "0",
        eid: "0000000",
        oid: "0000000",
        pid: "0",
        ra: "0",
        rid: "1",
        si: "6",
        t: "",
        text: tid('knowledgebase'),
        ti: tid('knowledgebase'),
        ty: "0"
    };
};

KnowledgebaseUI.CreateRootResource = function(){
    DataEngine.cannedResources.setResource(KnowledgebaseUI.GetRootResource());
};

KnowledgebaseUI.AutoSearchChat = function(_instant){

    var i,previewHeight= 0;

    $('#chat-qrd-preview').html('');
    $('#chat-qrd-preview').css({display:'none'});

    if(KnowledgebaseUI.AutoSearchTimer != null)
        clearTimeout(KnowledgebaseUI.AutoSearchTimer);

    KnowledgebaseUI.AutoSearchTimer = setTimeout(function()
    {
        var editorContents = $.trim(grabEditorContents('plaintext').replace(/<.*?>/g, ''));

        /*
        var cpos = lz_he_getCursorPosition('chat-input');
        if(cpos != null && cpos > 0)
        {
            var lastWord = lzm_commonTools.GetLastWordAt(editorContents,cpos);

            if(lastWord.length > 1)
                editorContents = lastWord;
        }
        */

        if (editorContents.length > 1 && editorContents.length < 20)
        {
            var frequentlyUsedResources = DataEngine.cannedResources.GetResourceList('usage_counter', {ty: '1,2,3,4', t: editorContents, text: editorContents, ti: editorContents, s: editorContents});

            if ($('#chat-progress').height() > 200 && frequentlyUsedResources.length > 0)
            {
                var listHTML = KnowledgebaseUI.CreateAutoSearchListHTML('chat',editorContents,frequentlyUsedResources);
                $('#chat-qrd-preview').html(listHTML);

                $('.auto-search-button').click(function(e){
                    e.stopPropagation();
                    var rid = $(this).data('rid');
                    var link = $(this).data('link');
                    if(!$(this).hasClass('ui-disabled') && d(link))
                        KnowledgebaseUI.UseAutoSearchResult('chat',rid,d(link));
                    else
                        KnowledgebaseUI.ShowEntry(rid);
                });

                if(listHTML.length)
                    $('#chat-qrd-preview').css({display:'block'});

                lzm_chatDisplay.RenderWindowLayout(true);

                previewHeight = $('#chat-qrd-preview').height()+4;

                setTimeout(function(){
                    chatScrollDown(1);
                },50);

                $('.editor-preview-inner').css({'max-width': ($('#chat-qrd-preview').width() - $('.editor-preview-shortcut').width() - 14)+'px'});

                for (i=0; i<KnowledgebaseUI.ShortCutResources.length; i++)
                {
                    var resource = DataEngine.cannedResources.getResource(KnowledgebaseUI.ShortCutResources[i].id);
                    KnowledgebaseUI.ShortCutResources[i].complete = (resource != null && '/' + resource.s == editorContents);
                }
                KnowledgebaseUI.QuickSearchReady = true;
            }
            else
            {
                KnowledgebaseUI.ShortCutResources = [];
                KnowledgebaseUI.QuickSearchReady = true;
            }
        }
        else
        {
            KnowledgebaseUI.ShortCutResources = [];
            KnowledgebaseUI.QuickSearchReady = true;
        }
    },_instant ? 0 : 500);
    $('#chat-progress').css({'bottom': (137 + previewHeight + ChatEditorClass.ExpandChatInputOffset) + 'px'});
};

KnowledgebaseUI.AutoSearchTicket = function(_instant){

    if(KnowledgebaseUI.AutoSearchTimer != null)
        clearTimeout(KnowledgebaseUI.AutoSearchTimer);

    KnowledgebaseUI.AutoSearchTimer = setTimeout(function()
    {
        if (LocalConfiguration.KBAutoSearch && ChatTicketClass.ComposerAutoSearchWord.length > 1 && lzm_chatDisplay.windowWidth > 900)
        {
            var frequentlyUsedResources = DataEngine.cannedResources.GetResourceList('usage_counter', {ty: '1,2,3,4', t: ChatTicketClass.ComposerAutoSearchWord, text: ChatTicketClass.ComposerAutoSearchWord, ti: ChatTicketClass.ComposerAutoSearchWord, s: ChatTicketClass.ComposerAutoSearchWord});
            var listHTML = KnowledgebaseUI.CreateAutoSearchListHTML('ticket',ChatTicketClass.ComposerAutoSearchWord,frequentlyUsedResources);

            if(listHTML.length)
                $('#ticket-kb-auto-search').html(listHTML);

            if(listHTML.length)
                $('#ticket-kb-auto-search').css({display:'block'});

            $('.auto-search-button').click(function(e){

                e.stopPropagation();
                var rid = $(this).data('rid');
                var link = $(this).data('link');
                if(!$(this).hasClass('ui-disabled') && d(link))
                    KnowledgebaseUI.UseAutoSearchResult('ticket',rid,d(link));
                else
                {
                    TaskBarManager.ReturnToActiveWindowOnNextClose();
                    KnowledgebaseUI.ShowEntry(rid);
                }
            });
            $('.editor-preview-inner').css({'max-width': ($('#chat-qrd-preview').width() - $('.editor-preview-shortcut').width() - 14)+'px'});
        }
        //else
          //  $('#ticket-kb-auto-search').css({display:'none'});

        UIRenderer.resizeTicketReply();

    },_instant ? 0 : 500);
};

KnowledgebaseUI.CreateAutoSearchListHTML = function(_target,_editorContents,_frequentlyUsedResources){

    var i,row,title,shortcut,maxIterate = Math.min(100, _frequentlyUsedResources.length), resultTableHTML = '',resultRowHTML = '',resultPrioRowHTML = '';

    for (i=0; i<maxIterate; i++)
    {
        var resourceText = /*lzm_commonTools.htmlEntities*/(_frequentlyUsedResources[i].text.replace(/<.*?>/g, ''));

        if(!$.trim(resourceText).length)
            continue;

        if(!lzm_commonPermissions.checkUserResourceReadPermission(lzm_chatDisplay.myId,_frequentlyUsedResources[i],DataEngine.cannedResources.getResource(_frequentlyUsedResources[i].pid)))
            continue;

        if(_frequentlyUsedResources[i].ty == KnowledgebaseUI.TypeFile)
            resourceText = '<b>' + lzm_commonTools.htmlEntities(_frequentlyUsedResources[i].ti.replace(/<.*?>/g, '')) + '</b>';
        else if(_frequentlyUsedResources[i].ty < KnowledgebaseUI.TypeFile)
        {
            resourceText = '<span>'+lzm_commonTools.HightlightSearchKey(resourceText,_editorContents)+'</span>';
            if(_frequentlyUsedResources[i].ti.length)
            {
                title = lzm_commonTools.htmlEntities(lzm_commonTools.SubStr(_frequentlyUsedResources[i].ti,50,true));
                resourceText = '<b>'+lzm_commonTools.HightlightSearchKey(title,_editorContents)+'</b><br>' + resourceText;
            }
        }

        var path = KnowledgebaseUI.GetPath(_frequentlyUsedResources[i]);
        if(path.length)
            resourceText += '<br><span class="path">'+path+'</span>';

        if(d(_frequentlyUsedResources[i].s))
        {
            shortcut = (_frequentlyUsedResources[i].s.length) ? '<span class="editor-preview-shortcut" id="editor-preview-shortcut-' + _frequentlyUsedResources[i].rid +'">/' + _frequentlyUsedResources[i].s + '</span>' : '';
            resourceText = '<td class="editor-preview-cell"><div class="editor-preview-inner">' + shortcut + resourceText + '</div></td>';

            if (_editorContents.indexOf('/') == 0 && ('/' + _frequentlyUsedResources[i].s.toLowerCase()).indexOf(_editorContents.toLowerCase()) == 0)
                KnowledgebaseUI.ShortCutResources.push({id: _frequentlyUsedResources[i].rid, complete: false});
        }

        // buttons
        var linkcss = (KnowledgebaseUI.GetAccessUrl(_frequentlyUsedResources[i])=='' || _frequentlyUsedResources[i].p == '0') ? ' ui-disabled' : '';
        var iconcss = (KnowledgebaseUI.GetAccessUrl(_frequentlyUsedResources[i])=='' || _frequentlyUsedResources[i].p == '0') ? ' icon-light' : ' icon-blue';

        resourceText += '<td class="auto-search-buttons">';
        resourceText += '<div class="auto-search-button" title="'+tid('edit')+'" data-rid="'+_frequentlyUsedResources[i].rid + '" id="kb-auto-send-text-'+_frequentlyUsedResources[i].rid + '"><i class="fa fa-pencil icon-large icon-blue"></i></div>';
        resourceText += '<div class="auto-search-button'+linkcss+'" title="'+tid('link')+'" data-link="true" data-rid="'+_frequentlyUsedResources[i].rid + '" id="kb-auto-send-link-'+_frequentlyUsedResources[i].rid + '"><i class="fa fa-link icon-large'+iconcss+'"></i></div>';
        resourceText += '</td>';

        row = '<tr class="lzm-unselectable" style="cursor: pointer;" onclick="KnowledgebaseUI.UseAutoSearchResult(\''+_target+'\',\'' + _frequentlyUsedResources[i].rid + '\');">' + resourceText + '</tr>';

        if(d(_frequentlyUsedResources[i].s) && _frequentlyUsedResources[i].s.toLowerCase().indexOf(_editorContents.toLowerCase()) != -1)
            resultPrioRowHTML += row;
        else
            resultRowHTML += row;
    }

    if(resultPrioRowHTML.length || resultRowHTML.length)
    {
        resultTableHTML += '<table>';
        resultTableHTML += resultPrioRowHTML;
        resultTableHTML += resultRowHTML;
        resultTableHTML += '</table>';
    }
    return resultTableHTML;
};

KnowledgebaseUI.UseAutoSearchResult = function(_target, _resourceId, _link) {

    _link = (d(_link)) ? _link : false;
    var resource = DataEngine.cannedResources.getResource(_resourceId), resourceHtmlText;
    if (resource != null)
    {
        UserActions.messageFromKnowledgebase = true;

        if(_link)
        {
            if(_target == 'ticket')
                resourceHtmlText = KnowledgebaseUI.GetURL(resource);
            else
                resourceHtmlText = '<a href="' + KnowledgebaseUI.GetURL(resource) + '" class="lz_chat_link" target="_blank">' + resource.ti + '</a>';
        }
        else
            switch (resource.ty)
            {
                case '1':
                    resourceHtmlText = (((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()) || _target == 'ticket') ? KnowledgebaseUI.PrepareResourceTextForTicket(resource) : resource.text;
                    break;
                case '2':
                    var linkHtml = '<a href="' + resource.text + '" class="lz_chat_link" target="_blank">' + resource.ti + '</a>';
                    resourceHtmlText = (((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()) || _target == 'ticket') ? resource.text : linkHtml;
                    break;
                default:
                    var urlFileName = encodeURIComponent(resource.ti.replace(/ /g, '+').replace(/<.*?>/g, ''));
                    var acid = lzm_commonTools.pad(Math.floor(Math.random() * 1048575).toString(16), 5);
                    var fileId = resource.text.split('_')[1];

                    var thisServer = CommunicationEngine.chosenProfile.server_protocol + CommunicationEngine.chosenProfile.server_url;
                    var thisFileUrl = thisServer + '/getfile.php?';
                    thisFileUrl += 'acid=' + acid + '&file=' + urlFileName + '&id=' + fileId;

                    var fileHtml = '<a ' +
                        'href="' + thisFileUrl + '" ' +
                        'class="lz_chat_file" target="_blank">' + resource.ti.replace(/<.*?>/g, '') + '</a>';

                    resourceHtmlText = (((IFManager.IsAppFrame || IFManager.IsMobileOS) && !IFManager.IsDesktopApp()) || _target == 'ticket') ? thisFileUrl : fileHtml;
                    break;
            }

        if(_target == 'chat')
        {
            setEditorContents(resourceHtmlText);
            setFocusToEditor();
            KnowledgebaseUI.ShortCutResources = [];
            $('#chat-qrd-preview').css({display:'none'});
            chatInputTyping();
        }
        else if(_target == 'ticket')
        {
            var fullComposerText = $('#ticket-reply-input').val();
            var compTextBefore = fullComposerText.substr(0,ChatTicketClass.ComposerLastCursorPosition-ChatTicketClass.ComposerAutoSearchWord.length);
            var compTextAfter = fullComposerText.substr(ChatTicketClass.ComposerLastCursorPosition,fullComposerText.length-ChatTicketClass.ComposerLastCursorPosition);
            var newText = compTextBefore + resourceHtmlText + compTextAfter;

            $('#ticket-reply-input').val(newText);
            $('#ticket-reply-input').focus();
            $('#ticket-reply-input').prop("selectionStart",ChatTicketClass.ComposerLastCursorPosition+newText.length+1);
            $('#ticket-reply-input').prop("selectionEnd",ChatTicketClass.ComposerLastCursorPosition+newText.length+1);

            ChatTicketClass.ComposerAutoSearchWord = '';
            ChatTicketClass.ComposerLastCursorPosition = 0;

            KnowledgebaseUI.AutoSearchTicket(true);
        }
    }
};

KnowledgebaseUI.InsertIntoTicket = function(ticketId){
    var resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    if (resource != null)
    {
        DataEngine.cannedResources.riseUsageCounter(lzm_chatDisplay.selectedResource);

        TaskBarManager.RemoveActiveWindow();
        TaskBarManager.GetWindow(ticketId + '_reply').Maximize();

        var replyText = KnowledgebaseUI.PrepareResourceTextForTicket(resource);

        insertAtCursor('ticket-reply-input', replyText);
        $('#ticket-reply-input-resource').val(resource.rid);
        $('#ticket-reply-input').change();

        if (resource.ty.toString() == '1' && (!resource.p || resource.p=='0'))
            $('#ticket-reply-input-save').removeClass('ui-disabled');
        else
            $('#ticket-reply-input-save').addClass('ui-disabled');
    }
};

KnowledgebaseUI.PrepareResourceTextForTicket = function(_resource){
    var replyText = '';
    switch(_resource.ty.toString())
    {
        case '1':
            replyText += _resource.text
                .replace(/^<p>/gi,'').replace(/^<div>/gi,'')
                .replace(/<p>/gi,'<br>').replace(/<div>/gi,'<br>')
                .replace(/<br>/gi,'\n').replace(/<br \/>/gi, '\n');
            if (replyText.indexOf('openLink') != -1)
            {
                replyText = replyText.replace(/<a.*openLink\('(.*?)'\).*>(.*?)<\/a>/gi, '$2 ($1)');
            }
            else
            {
                replyText = replyText.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, '$2 ($1)');
            }
            replyText = replyText.replace(/<.*?>/g, '').replace(/&nbsp;/gi, ' ').replace(/&.*?;/g, '');
            break;
        case '2':
            replyText += _resource.ti + ':\n' + _resource.text;
            break;
        default:
            var urlFileName = encodeURIComponent(_resource.ti.replace(/ /g, '+'));
            var fileId = _resource.text.split('_')[1];
            var urlParts = lzm_commonTools.getUrlParts(CommunicationEngine.chosenProfile.server_protocol + CommunicationEngine.chosenProfile.server_url, 0);
            var thisServer = ((urlParts.protocol == 'http://' && urlParts.port == 80) || (urlParts.protocol == 'https://' && urlParts.port == 443)) ?
                urlParts.protocol + urlParts.urlBase + urlParts.urlRest : urlParts.protocol + urlParts.urlBase + ':' + urlParts.protocol + urlParts.urlRest;
            replyText += thisServer + '/getfile.php?';
            replyText += 'file=' + urlFileName + '&id=' + fileId;
    }
    return replyText;
};

KnowledgebaseUI.InsertIntoChat = function(){
    if(TaskBarManager.GetActiveWindow() != null)
    {
        var sysId = TaskBarManager.GetActiveWindow().Tag;
        ChatManager.SaveEditorInput();
        removeEditor();
        lzm_chatDisplay.resourcesDisplay.CreateKBTreeDialog(null, sysId);
    }
};

KnowledgebaseUI.AddToChat = function(kbType) {
    var dialogId = 'add-qrd-to-chat-' + md5(Math.random().toString());
    KnowledgebaseUI.ShowEntry(null, kbType, null, {type: kbType, dialog_id: dialogId, chat_partner: ChatManager.ActiveChat, cp_name: ''});
};

KnowledgebaseUI.AddToTicket = function(_kbType,_dialogId){
    KnowledgebaseUI.ShowEntry(null, _kbType, _dialogId);
};

KnowledgebaseUI.GetPath = function(_entry){

    var __addString = function(parent,path){
        if(d(parent) && parent != null && parent.rid != '1')
            return parent.ti + ' / ' + path;
        else
            return path;
    };
    var path = '';
    if(_entry != null && d(_entry.pid))
    {
        var parent = DataEngine.cannedResources.objects[_entry.pid];
        path = __addString(parent,path);
        while(d(parent) && parent.rid != '1')
        {
            parent = DataEngine.cannedResources.objects[parent.pid];
            path = __addString(parent,path);
        }
    }
    return path;
};

KnowledgebaseUI.GetEmptyEntry = function(_type){
    return {
        ty:_type,//type

        //allowBotAccess:'0',allow bot access depr
        ba:'0',// bot access

        //isPublic:'0',public resource depr
        p:'0',//public resource

        di:'0',
        c:'0',//created
        ed:'0',//edited
        eid:'',//editorid

        //fullTextSearch:'0'//deprecated
        f:'0', // fulltext search

        g :'',

        //languages:''//deprecated
        l:'',//languages

        md5:'',//hash!?
        oid:'',//ownerid

        pid:'1',//parentid
        ra:'1',//rank?
        rid:lzm_commonTools.guid(),//id

        //shortcutWord:''//deprecated
        s:'',//shortcut text

        si:'22',
        t:'',//tags
        text:'',//value
        ti:''//title
    };
};

KnowledgebaseUI.EditEntry = function(){
    KnowledgebaseUI.ShowEntry(lzm_chatDisplay.selectedResource);
};

KnowledgebaseUI.ShowEntry = function(_entryId, _entryType, _ticketReplyWindowId, _sendToChat, _ticketComposeWindow){

    removeKBContextMenu();

    if(IFManager.IsAppFrame && IFManager.IsMobileOS && _entryType == KnowledgebaseUI.TypeFile)
    {
        showNotMobileMessage();
        return;
    }

    _entryType = d(_entryType) ? _entryType : KnowledgebaseUI.TypeText;
    _sendToChat = (d(_sendToChat) && _sendToChat) ? _sendToChat : null;
    _ticketReplyWindowId = d(_ticketReplyWindowId) ? _ticketReplyWindowId : null;
    _ticketComposeWindow = d(_ticketComposeWindow) ? _ticketComposeWindow : null;

    var isNew = !(d(_entryId) && _entryId != null);
    var kbEntryObject = KnowledgebaseUI.GetEmptyEntry(_entryType);

    if(!isNew && d(DataEngine.cannedResources.objects[_entryId]))
        kbEntryObject = DataEngine.cannedResources.objects[_entryId];
    else if(_sendToChat == null && _ticketComposeWindow == null)
        __SetSelectedParent();
    else if(_sendToChat != null)
        kbEntryObject.pid = '';
    else if(_ticketReplyWindowId != null)
        kbEntryObject.pid = '101';

    /*
     // change 7089 cut
    if(_ticketComposeWindow != null)
    {
        if(isNew)
            __SetSelectedParent();
        kbEntryObject.text = _ticketComposeWindow.text;
    }
    */

    _entryId = !isNew ? _entryId : kbEntryObject.rid;

    var headerString,footerString='',bodyString='';

    if (_ticketReplyWindowId != null)
        headerString = tid('add_attachment');
    else if(_sendToChat != null)
        headerString = (_sendToChat.type == 'link') ? tid('send_url') : tid('send_file');
    else if(isNew)
        headerString = (kbEntryObject.ty < KnowledgebaseUI.TypeFile) ? tid('resource_add_new') : tid('new_file_resource');
    else
    {
        headerString = tid('edit');
        if(kbEntryObject.ti.length)
            headerString += ' (' + lzm_commonTools.SubStr(kbEntryObject.ti,10,true)+ ')';
    }

    footerString += lzm_inputControls.createButton('kb-entry-save', '', '', tid('ok'), '', 'lr', {'margin-left': '4px'}, '',30,'d');
    footerString += lzm_inputControls.createButton('kb-entry-cancel', '', '', tid('cancel'), '', 'lr', {'margin-left': '4px'}, '',30,'d');

    bodyString += '<div id="kb-entry-placeholder"></div>';

    var tabArray = [];
    if(kbEntryObject.ty < KnowledgebaseUI.TypeFile)
        tabArray.push({name: tid('entry'), content: __GetEntryContent()});
    else if(isNew)
        tabArray.push({name: tid('file'), content: __GetEntryContent()});

    if(kbEntryObject.ty < KnowledgebaseUI.TypeFile || !isNew)
    {
        tabArray.push({name: tid('settings'), content: __GetSettingsContent()});
        tabArray.push({name: tid('tags'), content: __GetTagsContent()});
    }

    var wdId = 'show-kb-entry-'+md5(_entryId);

    if(TaskBarManager.WindowExists(wdId))
    {
        TaskBarManager.Maximize(wdId);
        return;
    }

    if(!__ValidatePermission())
    {
        if(_ticketComposeWindow != null)
            __UpdateTicketComposer(false);
        return;
    }

    // change 7089 paste
    if(_ticketComposeWindow != null)
    {
        if(isNew)
            __SetSelectedParent();
        kbEntryObject.text = _ticketComposeWindow.text;
    }

    lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'database', wdId, wdId, 'kb-entry-cancel');
    TaskBarManager.GetWindow(wdId).Tag = lzm_commonTools.clone(kbEntryObject);

    lzm_inputControls.createTabControl('kb-entry-placeholder', tabArray, 0, lzm_chatDisplay.windowWidth);

    if(kbEntryObject.ty < KnowledgebaseUI.TypeFile)
    {
        KnowledgebaseUI.TextEditor = new ChatEditorClass('show-kb-entry-text', '');
        KnowledgebaseUI.TextEditor.init(kbEntryObject.text, 'show-kb-entry');
    }

    $('#qrd-knb-shortcuts-sc').attr('readonly', 'readonly').css({'border-color':'#3399ff',background:'#3399ff',color:'#fff'});
    $('#qrd-knb-date-edited-value, #qrd-knb-date-created-value').attr('readonly', 'readonly');

    $('#kb-entry-save').click(function(){

        if(kbEntryObject.ty < KnowledgebaseUI.TypeFile || !isNew)
        {
            var id = $('#qrd-knb-id-text').val();

            var alertMessage = '';
            if(id.length < 6 || id.indexOf(' ') != -1)
                alertMessage = tid('invalid_id');

            if(alertMessage.length > 0)
            {
                lzm_commonDialog.createAlertDialog(alertMessage, [{id: 'ok', name: t('Ok')}]);
                $('#alert-btn-ok').click(function() {
                    lzm_commonDialog.removeAlertDialog();
                });
                return true;
            }
            else
            {
                if(kbEntryObject.ty < KnowledgebaseUI.TypeFile)
                    kbEntryObject.ti = $('#show-kb-entry-title').val();
                if(kbEntryObject.ty == KnowledgebaseUI.TypeText || kbEntryObject.ty == KnowledgebaseUI.TypeFolder)
                    kbEntryObject.text = KnowledgebaseUI.TextEditor.grabHtml();
                else if(kbEntryObject.ty == KnowledgebaseUI.TypeURL)
                    kbEntryObject.text = $('#show-kb-url').val();

                kbEntryObject.t = $('#show-kb-tags-input').val();
                kbEntryObject.new_id = id;
                kbEntryObject.p = $('#qrd-knb-pub-entry').prop('checked') ? '1' : '0';
                kbEntryObject.ba = $('#qrd-knb-pub-bot').prop('checked') ? '1' : '0';
                kbEntryObject.f = $('#qrd-knb-search-full').prop('checked') ? '1' : '0';
                kbEntryObject.s = $('#qrd-knb-shortcuts-text').val();
                kbEntryObject.l = $('#qrd-knb-language-text').val().replace(/ +/g, '');
                kbEntryObject.oid = $('#qrd-knb-owner-op-id-fs').val();
                kbEntryObject.g = (kbEntryObject.ty == 0) ? $('#qrd-knb-owner-gr-id-fs').val() : '';

                CommunicationEngine.PollServerResource({First:kbEntryObject}, "set");

                $('#resource-' + kbEntryObject.rid + '-icon-and-text').html(lzm_chatDisplay.resourcesDisplay.GetResourceIconHTML(kbEntryObject) + '<span class="qrd-title-span">'+lzm_commonTools.htmlEntities(kbEntryObject.ti)+'</span>');
            }

            TaskBarManager.RemoveActiveWindow();

            if(_ticketComposeWindow != null)
                __UpdateTicketComposer(true);
        }
        else
        {
            if (_sendToChat == null)
            {
                var pid = (_ticketReplyWindowId == null) ? kbEntryObject.pid : 110;
                uploadFile('user_file', pid, null, _ticketReplyWindowId, null);
            }
            else
            {
                uploadFile('user_file', null, null, null, _sendToChat, wdId);
            }
        }

    });
    $('#kb-entry-cancel').click(function(){

        TaskBarManager.RemoveActiveWindow();
        if (_sendToChat != null)
        {
            var winObj = TaskBarManager.GetWindowByTag(_sendToChat.chat_partner);
            if(winObj != null)
                winObj.Maximize();
        }

        if(_ticketComposeWindow != null)
            __UpdateTicketComposer(false);
        else if(_ticketReplyWindowId != null)
            __ContinueTicketReply();
    });

    $('#qrd-knb-id-text').change(function(){
        $('#qrd-knb-id-text').val(lzm_commonTools.SubStr($('#qrd-knb-id-text').val(),32,false));
    });

    function __ValidatePermission(){

        if(_ticketReplyWindowId == null && _ticketReplyWindowId == null)
        {
            var parentFolder = DataEngine.cannedResources.getResource(kbEntryObject.pid);
            if(parentFolder != null)
            {
                if(isNew && !lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                {
                    showNoPermissionMessage();
                    return false;
                }

                if(!isNew && !lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', kbEntryObject))
                {
                    showNoPermissionMessage();
                    return false;
                }
            }
        }
        return true;
    }
    function __GetEntryContent(){
        var html = '<div id="show-kb-entry" class="lzm-fieldset"><div id="show-kb-entry-inner">';

        var isVisible = (kbEntryObject.ty == KnowledgebaseUI.TypeText || kbEntryObject.ty == KnowledgebaseUI.TypeURL || kbEntryObject.ty == KnowledgebaseUI.TypeFolder) ? 'block' : 'none';
        html += '<div style="display:' + isVisible + ';" id="show-kb-entry-title-div" class="top-space-half">' + lzm_inputControls.createInput('show-kb-entry-title', 'resource-input-new', lzm_commonTools.htmlEntities(kbEntryObject.ti), tidc('title'), '', 'text', '') + '</div>';

        isVisible = (kbEntryObject.ty == KnowledgebaseUI.TypeText || kbEntryObject.ty == KnowledgebaseUI.TypeFolder) ? 'block' : 'none';
        html += '<div style="display:' + isVisible + ';" id="show-kb-entry-div" class="show-kb-resource show-kb-html-resource"><div class="top-space"><label for="show-kb-entry-text">' + tidc('text') + '</label></div><div id="show-kb-entry-text-inner">';
        html += '<div style="display:' + isVisible + ';" id="show-kb-entry-controls">' + lzm_inputControls.CreateInputControlPanel('basic').replace(/lzm_chatInputEditor/g,'KnowledgebaseUI.TextEditor') + '</div>';
        html += '<div style="display:' + isVisible + ';" id="show-kb-entry-body"><textarea id="show-kb-entry-text"></textarea></div></div></div>';

        isVisible = (kbEntryObject.ty == KnowledgebaseUI.TypeURL) ? 'block' : 'none';
        html += '<div style="display:' + isVisible + ';" id="show-kb-url-div" class="top-space show-kb-link-resource show-kb-resource">' + lzm_inputControls.createInput('show-kb-url', 'resource-input-url-new', lzm_commonTools.htmlEntities(kbEntryObject.text), tidc('url'), '', 'text', '') + '</div>';

        isVisible = (kbEntryObject.ty == KnowledgebaseUI.TypeFile) ? 'block' : 'none';
        html += '<div style="display:' + isVisible + ';" id="show-kb-file-div" class="show-kb-file-resource show-kb-resource">';
        html += '<label class="file-upload-label file-drop-zone">';
        html += '<i id="file-upload-icon" class="fa fa-cloud-upload icon-xxxl icon-light lzm-clickable"></i>';
        html += lzm_inputControls.createInput('file-upload-input', 'resource-input-new', '', '', '', 'file', '');
        html += '<span></span>';
        html += '<div id="file-upload-progress" style="display: none;"><span id="file-upload-numeric" class="text-xxl text-green">0%</span></div>';
        html += '<div id="file-upload-name" class="text-bold text-xl"></div>';
        html += '<div id="file-upload-size"></div>';
        html += '<div id="file-upload-type"></div>';
        html += '<div id="file-upload-error" class="text-orange text-bold"></div>';
        html += '<div id="cancel-file-upload-div" style="display:none;"><br><br>';
        html += lzm_inputControls.createButton('cancel-file-upload','', 'cancelFileUpload(event)', tid('cancel'), '', 'lr',{padding:'5px 10px;','display': 'none'}) + '</div>';
        html += '</label>';
        html += '<div id="file-drop-box" class="file-drop-zone"></div></div></div></div>';
        return html;
    }
    function __GetSettingsContent(){

        // owner operator
        var i,ownerOPArray = [], ownerGRArray = [];
        var operators = DataEngine.operators.getOperatorList('name','',true,false);
        var parent = DataEngine.cannedResources.getResource(kbEntryObject.pid);

        for (i=0; i<operators.length; i++)
        {
            ownerOPArray.push({value: operators[i].id, text: operators[i].name});

            if(kbEntryObject.oid == '' && operators[i].id == lzm_chatDisplay.myId)
                kbEntryObject.oid = lzm_chatDisplay.myId;
        }

        ownerGRArray.push({value: '', text: '-'});
        var groups = DataEngine.groups.getGroupList('id',true,false);
        for (i=0; i<groups.length; i++)
            ownerGRArray.push({value: groups[i].id, text: groups[i].id});

        var html = '<fieldset class="lzm-fieldset" id="qrd-knb-id"><legend>' + tid('entry') + '</legend>';


        html += '<table class="tight"><tr><td style="width:90px;">';
        html += lzm_inputControls.createInput('qrd-knb-id-title', 'ui-disabled', 'Id:', '', '', 'text', '');
        html += '</td><td>';
        html += lzm_inputControls.createInput('qrd-knb-id-text', '', lzm_commonTools.htmlEntities(kbEntryObject.rid), '', '', 'text', '');
        html += '</td></tr></table>';

        if(d(kbEntryObject.c) && kbEntryObject.c > 0)
        {
            html += '<table class="tight"><tr><td style="width:90px;">';
            html += lzm_inputControls.createInput('qrd-knb-date-created-title', 'ui-disabled', tidc('created'), '', '', 'text', '');
            html += '</td><td>';
            html += lzm_inputControls.createInput('qrd-knb-date-created-value', '', lzm_commonTools.getHumanDate(lzm_chatTimeStamp.getLocalTimeObject(parseInt(kbEntryObject.c * 1000), true)), '', '', 'text', '');
            html += '</td></tr></table>';
        }

        if(d(kbEntryObject.ed) && kbEntryObject.ed > 0)
        {
            html += '<table class="tight"><tr><td style="width:90px;">';
            html += lzm_inputControls.createInput('qrd-knb-date-edited-title', 'ui-disabled', tidc('edited'), '', '', 'text', '');
            html += '</td><td>';
            html += lzm_inputControls.createInput('qrd-knb-date-edited-value', '', lzm_commonTools.getHumanDate(lzm_chatTimeStamp.getLocalTimeObject(parseInt(kbEntryObject.ed * 1000), true)), '', '', 'text', '');
            html += '</td></tr></table>';
        }

        html += '</fieldset>';
        html += '<fieldset class="lzm-fieldset" id="qrd-knb-owner-fs"><legend>' + tid('owner') + '</legend>';
        html += '<label for="qrd-knb-owner-op-id-fs">' + tidc('operator') + '</label>';
        html+= lzm_inputControls.createSelect("qrd-knb-owner-op-id-fs", 'bottom-space', '', '', {}, {}, tidc('operator'), ownerOPArray, kbEntryObject.oid, '');

        if(kbEntryObject.ty == 0)
        {
            var groupDisabeld = (lzm_commonPermissions.permissions.resources_write < 3) ? 'ui-disabled' : '';
            html += '<label for="qrd-knb-owner-gr-id-fs">' + tidc('group') + '</label>';
            html += lzm_inputControls.createSelect("qrd-knb-owner-gr-id-fs", groupDisabeld, '', '', {}, {}, tid('group2'), ownerGRArray, kbEntryObject.g, '');
        }

        var pnp = (parent != null && parent.p != '1' && parent.rid != '1') ? '&nbsp;&nbsp;<span class="bg-red text-white text-box">' + tid('parent not public') + '</span>' : '';

        html += '</fieldset>';
        html += '<fieldset class="lzm-fieldset" id="qrd-knb-pub-acc-fs"><legend>' + tid('public_access');
        html += '</legend><div><input type="checkbox" class="checkbox-custom" id="qrd-knb-pub-entry"' + (kbEntryObject.p==1 ? ' checked="checked"' : '');
        html += ' /><label for="qrd-knb-pub-entry" class="checkbox-custom-label">' + tid('public_knb') + pnp + '</label></div>';

        var botsDisabled = (kbEntryObject.ty == 0) ? 'ui-disabled ' : '';
        html += '<div class="' + botsDisabled + '"><input type="checkbox" class="checkbox-custom" id="qrd-knb-pub-bot"' + (kbEntryObject.ba==1 ? ' checked="checked"' : '');
        html += ' /><label for="qrd-knb-pub-bot" class="checkbox-custom-label">' + t('Bots will use this resource (Virtual Assistance)');
        html += '</label></div></fieldset>';

        if(KnowledgebaseUI.ResourceIsFile(kbEntryObject))
        {
            html += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-direct-access-fs"><legend>Download</legend>';
            html += '<div>'+lzm_inputControls.createInput('qrd-knb-access-url', '', KnowledgebaseUI.GetFileURL(kbEntryObject), 'Download URL:', '', 'text', '') +'</div>';
            html += '</fieldset>';
        }
        else if(kbEntryObject.ty>0)
        {
            html += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-direct-access-fs"><legend>' + tid('direct_access') + '</legend>';
            html += '<div>'+lzm_inputControls.createInput('qrd-knb-access-url', '', KnowledgebaseUI.GetAccessUrl(kbEntryObject), tid('direct_access')+' URL:', '', 'text', '') +'</div>';
            html += '</fieldset>';
        }

        var fulltextDisabled = (kbEntryObject.ty == 0) ? ' class="ui-disabled"' : '';
        html += '<fieldset class="lzm-fieldset" id="qrd-knb-search-fs"><legend>' + tid('search') + '</legend><div' + fulltextDisabled + '>';
        html += '<input type="checkbox" class="checkbox-custom" id="qrd-knb-search-full"' + (kbEntryObject.f==1 ? ' checked="checked"' : '');
        html += ' /><label for="qrd-knb-search-full" class="checkbox-custom-label">'
        html += t('Fulltext Search (slower)') + '</label></div></fieldset>';

        var shortcutDisabeld = (kbEntryObject.ty == 0) ? 'ui-disabled' : '';
        html += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-shortcuts-fs"><legend>' + tid('shortcuts') + '</legend>';

        html += '<table class="tight"><tr><td style="width:40px;">';
        html += lzm_inputControls.createInput('qrd-knb-shortcuts-sc', 'text-bold text-center', '/', '', '', 'text', '');
        html += '</td><td>';
        html += lzm_inputControls.createInput('qrd-knb-shortcuts-text', shortcutDisabeld, lzm_commonTools.htmlEntities(kbEntryObject.s), '', '', 'text', '');
        html += '</td></tr></table>';

        html += '<div class="top-space-half lzm-info-text">' + t('Example: /welcome') + '</div></fieldset>';
        html += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-language-fs"><legend>' + tid('language') + '</legend>';
        html += lzm_inputControls.createInput('qrd-knb-language-text', '', lzm_commonTools.htmlEntities(kbEntryObject.l), tidc('language') + ' ' + tid('blank_all'), '', 'text', '');
        html += '<div class="top-space-half lzm-info-text">' + t('ISO 639-1 twoletter, comma-separated, case insensitive, example: en, it, fr') + '</div>';
        html += '</fieldset>';
        return html;
    }
    function __GetTagsContent(){
        var tagsDisabled = (kbEntryObject.ty == 0) ? ' class="ui-disabled"' : '';
        var html = '<fieldset class="lzm-fieldset" id="qrd-tags-fs"><legend>' + t('Tags') + '</legend>';
        html += '<textarea' + tagsDisabled + ' id="show-kb-tags-input">' + lzm_commonTools.htmlEntities(kbEntryObject.t) + '</textarea></fieldset>';
        return html;
    }
    function __UpdateTicketComposer(_saved){

        var folderSelectWindow = TaskBarManager.GetWindow(_ticketComposeWindow.folderSelectWindowId);
        if(folderSelectWindow != null)
        {
            folderSelectWindow.Close();
        }

        var ticketComposer = TaskBarManager.GetWindow(_ticketComposeWindow.composerWindowId);
        if(ticketComposer != null)
        {
            ticketComposer.Maximize();
            if(_saved)
            {
                $('#ticket-reply-input-save').removeClass('ui-disabled');
                $('#ticket-reply-input-resource').val(kbEntryObject.rid);
                $('#ticket-reply-input').val(KnowledgebaseUI.PrepareResourceTextForTicket(kbEntryObject));
            }
        }
    }
    function __ContinueTicketReply(){

        var folderSelectWindow = TaskBarManager.GetWindow(_ticketReplyWindowId);
        if(folderSelectWindow != null)
        {
            folderSelectWindow.ShowInTaskBar = true;
            folderSelectWindow.Maximize();
        }
    }
    function __SetSelectedParent(){
        var parentResource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
        if(parentResource != null)
        {
            kbEntryObject.pid = lzm_chatDisplay.selectedResource;
            if(parentResource.ty != KnowledgebaseUI.TypeFolder)
                kbEntryObject.pid = parentResource.pid;
        }
    }

    UIRenderer.resizeAddResources();

    if(kbEntryObject.ty == KnowledgebaseUI.TypeFile)
    {
        var dropContainer = document.getElementById('file-drop-box');
        $('#file-upload-input').change(function(){changeFile();});
        $('.file-drop-zone').on({
            dragstart: function(evt) {
                dropContainer.style.background = '#f6f6f6';
                evt.preventDefault();
            },
            dragover: function(evt) {
                dropContainer.style.background = '#f6f6f6';
                evt.preventDefault();

            },
            dragleave: function(evt) {
                dropContainer.style.background = '#f6f6f6';
                evt.preventDefault();
            },
            dragenter: function(evt) {
                dropContainer.style.background = '#f6f6f6';
                evt.preventDefault();

            },
            dragend: function(evt) {
                dropContainer.style.background = '#fff';
                evt.preventDefault();

            },
            drop: function(evt) {
                evt.preventDefault();
                changeFile(evt.originalEvent.dataTransfer.files[0]);
            }
        });

        document.body.addEventListener('drop', function(e) {
            e.preventDefault();
        }, false);

        $('#add-resource, #qrd-add-body').on({
            dragstart: function(evt) {evt.preventDefault();},
            dragover: function(evt) {evt.preventDefault();},
            dragleave: function(evt) {evt.preventDefault();},
            dragenter: function(evt) {evt.preventDefault();},
            dragend: function(evt) {evt.preventDefault();},
            drop: function(evt) {evt.preventDefault();}
        });
    }

    $('#show-kb-entry-title').focus();
    if(!IFManager.IsMobileOS)
        if(parseInt(kbEntryObject.ty) < KnowledgebaseUI.TypeFile)
            setTimeout(function(){
                $('#show-kb-entry-title').focus();
            },100);
};

KnowledgebaseUI.CountVisibleEntries = function(_list){
    var count = 0;
    try
    {
        for(var key in _list)
        {
            if (lzm_commonPermissions.checkUserResourceReadPermission(lzm_chatDisplay.myId, _list[key], DataEngine.cannedResources.getResource(_list[key].pid)))
            {
                count++;
            }
        }
    }
    catch(ex)
    {
        deblog(ex);
    }
    return count;
};

KnowledgebaseUI.AddOrEditResourceFromTicket = function(_ticketId, _folderSelectWindowId, _resource) {

    var permission = true;

    if(_resource == null)
        _resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);

    if (_resource != null)
    {
        var tco = {
            tid: _ticketId,
            text: lzm_chatDisplay.ticketResourceText[_ticketId],
            composerWindowId: _ticketId + '_reply',
            folderSelectWindowId: _folderSelectWindowId
        };
        if (_resource.ty == 0)
        {
            permission = KnowledgebaseUI.ShowEntry(null,1,null,false,tco);
        }
        else if (_resource.ty == 1)
        {
            permission = KnowledgebaseUI.ShowEntry(_resource.rid,1,null,false,tco);
        }
    }

    //if(permission === false)
      //  lzm_chatDisplay.ticketResourceText[_ticketId] = '';
};

KnowledgebaseUI.Remove = function() {
    removeKBContextMenu();
    var resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    if (resource != null)
    {
        if (!lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource))
        {
            showNoPermissionMessage();
            return;
        }
    }

    lzm_commonDialog.createAlertDialog(tid('resource_really_delete'), [{id: 'ok', name: t('Ok')}, {id: 'cancel', name: t('Cancel')}]);
    $('#alert-btn-ok').click(function() {
        var resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
        if (resource != null)
        {
            resource.di = 1;
            CommunicationEngine.PollServerResource({First:resource}, "set");
            $('#qrd-search-line-' + resource.rid).remove();
        }
        lzm_commonDialog.removeAlertDialog();
    });

    $('#alert-btn-cancel').click(function() {
        lzm_commonDialog.removeAlertDialog();
    });
};

KnowledgebaseUI.OpenAllParents = function(_entry){
    var list=[],__open = function(parent){
        if(d(parent) && parent != null && parent.rid != '1')
            list.push(parent.rid);
    };
    if(_entry != null && d(_entry.pid))
    {
        var parent = DataEngine.cannedResources.objects[_entry.pid];
        __open(parent);
        while(d(parent) && parent.rid != '1')
        {
            parent = DataEngine.cannedResources.objects[parent.pid];
            __open(parent);
        }
    }
    list.reverse();
    for(var key in list)
        KnowledgebaseUI.HandleResourceClickEvents(list[key],true,false,'');
};

KnowledgebaseUI.ShowInTreeView = function(_toSelect) {

    if(d(_toSelect))
        lzm_chatDisplay.selectedResource = _toSelect;

    removeKBContextMenu();
    var selSearchRes = lzm_chatDisplay.selectedResource;
    $('#qrd-tree-placeholder-tab-0').click();
    KnowledgebaseUI.OpenAllParents(DataEngine.cannedResources.getResource(selSearchRes));
    KnowledgebaseUI.HandleResourceClickEvents(selSearchRes,false,false,'');
    $('#all-resources').scrollTo('resource-'+selSearchRes);
};