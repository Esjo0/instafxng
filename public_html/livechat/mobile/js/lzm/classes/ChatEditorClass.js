/****************************************************************************************
 * LiveZilla ChatEditorClass.js
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function ChatEditorClass(editor,_id) {
    this.editor = editor;
    this.isBold = false;
    this.isItalic = false;
    this.isUnderlined = false;
    this.uploadImageId = '';
    this.Id = d(_id) ? _id : '';
}

ChatEditorClass.ActiveEditor = null;
ChatEditorClass.IsActiveEditor = false;
ChatEditorClass.ExpandChatInputOffset = 0;

ChatEditorClass.prototype.init = function(loadedValue, caller, cpId) {

    if(lzm_chatDisplay.ChatsUI.EditorBlocked)
    {
        return;
    }

    cpId = d(cpId) ? cpId : ChatManager.ActiveChat;

    this.removeEditor();
    make_wyzz(this.editor);

    loadedValue = (typeof loadedValue != 'undefined') ? loadedValue : '';
    lz_he_setFocus(this.editor);
    this.setHtml(loadedValue);
    if (this.editor == 'chat-input')
    {
        $('#chat-input-body').data('cp-id', cpId);
        lz_he_onEnterPressed(chatInputEnterPressed);
        document.getElementById("wysiwyg" + this.editor).contentWindow.document.body.onkeyup=chatInputTyping;
    }
    else
    {
        lz_he_onEnterPressed(null);
        document.getElementById("wysiwyg" + this.editor).contentWindow.document.body.onkeyup=doNothing;
    }

    ChatEditorClass.ActiveEditor = this;
    this.getBody().addEventListener('paste', ChatEditorClass.HandlePaste);
};

ChatEditorClass.prototype.clearEditor = function(os) {
    if (typeof os == 'undefined' || os.toLowerCase() == 'ios')
    {
        this.setHtml('');
    }
    else
    {
        this.init('', 'clearEditor');
    }
};

ChatEditorClass.prototype.removeEditor = function() {
    $('#wysiwyg' + this.editor).remove();
    ChatEditorClass.ActiveEditor = null;
};

ChatEditorClass.prototype.bold = function() {
    if (browserName == 'Microsoft Internet Explorer') {
        lz_he_setFocus(this.editor);
        lz_he_setCursor(this.editor);
        if (!this.isBold) {
            this.isBold = true;
            lz_he_setBold(this.editor);
        } else {
            this.isBold = false;
            lz_he_setNoStyle(this.editor);
        }
    } else {
        lz_he_setFocus(this.editor);
        lz_he_setBold(this.editor);
    }
};

ChatEditorClass.prototype.italic = function() {
    if (browserName == 'Microsoft Internet Explorer') {
        lz_he_setFocus(this.editor);
        lz_he_setCursor(this.editor);
        if (!this.isItalic) {
            this.isItalic = true;
            lz_he_setItalic(this.editor);
        } else {
            this.isItalic = false;
            lz_he_setNoStyle(this.editor);
        }
    } else {
        lz_he_setFocus(this.editor);
        lz_he_setItalic(this.editor);
    }
};

ChatEditorClass.prototype.underline = function() {
    if (browserName == 'Microsoft Internet Explorer') {
        lz_he_setFocus(this.editor);
        lz_he_setCursor(this.editor);
        if (!this.isUnderlined) {
            this.isUnderlined = true;
            lz_he_setUnderline(this.editor);
        } else {
            this.isUnderlined = false;
            lz_he_setNoStyle(this.editor);
        }
    } else {
        lz_he_setFocus(this.editor);
        lz_he_setUnderline(this.editor);
    }
};

ChatEditorClass.prototype.showHTML = function() {
    var lastWindow = TaskBarManager.GetActiveWindow(),html = this.grabHtml(),that = this;
    var bodyString = '<div class="lzm-fieldset">' + lzm_inputControls.createArea('editor-edit-html-'+this.Id, '', '','HTML:','font-family:courier;font-size:15px;') + '</div>';
    var footerString = lzm_inputControls.createButton('btn-save-html', '', '', t('Ok'), '', 'lr',{'margin-left': '4px'},'',30,'d') +
        lzm_inputControls.createButton('btn-cancel-html', '', '', t('Close'), '', 'lr',{'margin-left': '4px'},'',30,'d');

    lzm_commonDialog.CreateDialogWindow('HTML', bodyString, footerString, 'code', 'edit-kb-html', 'edit-kb-html-' + this.Id, 'btn-cancel-html');

    $('#editor-edit-html-'+this.Id).val(html);
    $('#btn-save-html').click(function() {
        var html = $('#editor-edit-html-'+that.Id).val();
        $('#btn-cancel-html').click();
        that.setHtml(html);
    });
    $('#btn-cancel-html').click(function() {
        TaskBarManager.RemoveActiveWindow();
        if(lastWindow != null)
            lastWindow.Maximize();
    });

    if(!IFManager.IsMobileOS)
        $('#editor-edit-html-'+this.Id).focus();

    $('#editor-edit-html-'+this.Id).css('height',parseInt($('#edit-kb-html-'+this.Id+'-body').css('height').replace('px','')-50)+'px');
};

ChatEditorClass.prototype.addImage = function() {
    var that = this;
    that.uploadImageId = lzm_commonTools.guid();
    this.focus();
    var fhtml = lzm_inputControls.createInput('add-image-file','',tid('image'),tidc('image'),'','file','');
    lzm_commonDialog.createAlertDialog(fhtml, [{id: 'dok', name: tid('ok')}, {id: 'dcancel', name: tid('cancel')}]);
    $('#alert-btn-dok').click(function() {
        var file = $('#add-image-file')[0].files[0];
        CommunicationEngine.uploadFile(file, 'user_file', 102, 0, null, null, that);
    });
    $('#alert-btn-dcancel').click(function() {
        lzm_commonDialog.removeAlertDialog();
    });
};

ChatEditorClass.prototype.addLink = function() {
    var that = this;
    var fhtml = lzm_inputControls.createInput('add-link-title','','',tidc('title'),'','text','');
    fhtml += '<div class="top-space">' + lzm_inputControls.createInput('add-link-url','','',tidc('url'),'','text','') + '</div>';
    fhtml += '<div class="top-space-half">' + lzm_inputControls.createCheckbox('add-link-target-blank',tid('new_window'),true) + '</div>';
    lzm_commonDialog.createAlertDialog(fhtml, [{id: 'dok', name: tid('ok')}, {id: 'dcancel', name: tid('cancel')}]);
    $('#add-link-title').focus();
    $('#alert-btn-dok').click(function() {
        try
        {
            var title = $('#add-link-title').val();
            var url = $('#add-link-url').val();
            var tb = ($('#add-link-target-blank').prop('checked')) ? ' target="_blank"' : '';
            var tag = '<a class="lz_chat_link" href="'+url+'"'+tb+'>' + title + '</a>';
            lzm_commonDialog.removeAlertDialog();
            that.insertHtml(tag);
            that.focus();
        }
        catch(e)
        {

        }
    });
    $('#alert-btn-dcancel').click(function() {
        lzm_commonDialog.removeAlertDialog();
        that.focus();
    });
};

ChatEditorClass.prototype.addPlaceholder = function() {
    var that = this;
    var obj,fhtml = '<div style="width:600px;height:500px;">';
    for(var key in ChatEditorClass.PlaceholdersList)
    {
        obj = ChatEditorClass.PlaceholdersList[key];
        fhtml += lzm_inputControls.createButton('add-ph-'+obj.id, 'add-ph-btn', '', obj.id + ': ' + obj.p, '', 'force-text', {display:'inline-block','margin': '4px',padding:'6px'}, '',90,'b');
    }
    fhtml += '</div>';
    lzm_commonDialog.createAlertDialog(fhtml, [{id: 'addphinfo', name: tid('further_information')},{id: 'addphcancel', name: tid('cancel')}],true);

    $('.add-ph-btn').click(function() {

        for(var key in ChatEditorClass.PlaceholdersList)
        {
            obj = ChatEditorClass.PlaceholdersList[key];
            if('add-ph-'+obj.id == $(this)[0].id)
            {
                that.insertHtml(obj.p);
                break;
            }
        }
        lzm_commonDialog.removeAlertDialog();
        that.focus();
    });
    $('#alert-btn-addphinfo').click(function() {
        openLink('https://www.livezilla.net/faq/en/?fid=livezilla-placeholder');

    });
    $('#alert-btn-addphcancel').click(function() {
        lzm_commonDialog.removeAlertDialog();
        that.focus();
    });
};

ChatEditorClass.prototype.placeImage = function() {
    var imgTag = '<img src="'+DataEngine.getServerUrl('getfile.php')+'?file=&id='+this.uploadImageId+'" />';
    lzm_commonDialog.removeAlertDialog();
    this.insertHtml(imgTag);
};

ChatEditorClass.prototype.grabText = function() {
    return lz_he_getText(this.editor);
};

ChatEditorClass.prototype.grabHtml = function() {
    return lz_he_getHTML(this.editor);
};

ChatEditorClass.prototype.getBody = function() {
    return lz_he_getBODY(this.editor);
};

ChatEditorClass.prototype.insertHtml = function(html) {
    lz_he_insertHTML(html, this.editor);
};

ChatEditorClass.prototype.setHtml = function(html) {
    var that = this;
    lz_he_setHTML(html, this.editor);
    if (browserName == 'Microsoft Internet Explorer') {
        setTimeout(function() {
            $('#chat-progress').focus();
            setTimeout(function() {
                lz_he_setFocus(that.editor);
                lz_he_setCursor(that.editor);
            }, 50);
        }, 20);
    } else {
        lz_he_setFocus(that.editor);
        lz_he_setCursor(that.editor);
    }
};

ChatEditorClass.prototype.blur = function() {
    lz_he_removeFocus(this.editor);
};

ChatEditorClass.prototype.focus = function() {
    if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
        lz_he_setFocus(this.editor);

};

ChatEditorClass.prototype.switchDisplayMode = function() {
    lz_he_switchDisplayMode(this.editor);
    if (!IFManager.IsMobileOS)
        lz_he_setFocus(this.editor);
};

ChatEditorClass.prototype.enterPressed = function() {
    this.setHtml('');
};

ChatEditorClass.PlaceholdersList = [
    {id:'ChatId',p:'%chat_id%'},
    {id:'ChatTranscript',p:'%transcript%'},
    {id:'FeedbackResult',p:'%rating%'},
    {id:'FeedbackLink',p:'%feedback_link%'},
    {id:'LocalTime',p:'%localtime%'},
    {id:'LocalDate',p:'%localdate%'},
    {id:'OperatorName',p:'%operator_name%'},
    {id:'OperatorFirstName',p:'%operator_lastname%'},
    {id:'OperatorLastName',p:'%operator_firstname%'},
    {id:'OperatorID',p:'%operator_id%'},
    {id:'OperatorEmail',p:'%operator_email%'},
    {id:'OperatorGroup',p:'%group_id%'},
    {id:'OperatorGroupDescription',p:'%group_description%'},
    {id:'TicketSubject',p:'%subject%'},
    {id:'TicketText',p:'%mailtext%'},
    {id:'TicketID',p:'%ticket_id%'},
    {id:'TicketQuote',p:'%quote%'},
    {id:'TicketHash',p:'%ticket_hash%'},
    {id:'TargetURL',p:'%target_url%'},
    {id:'SearchQuery',p:'%searchstring%'},
    {id:'VisitorLastName',p:'%external_lastname%'},
    {id:'VisitorFirstName',p:'%external_firstname%'},
    {id:'VisitorFullName',p:'%external_name%'},
    {id:'VisitorEmail',p:'%external_email%'},
    {id:'VisitorCompany',p:'%external_company%'},
    {id:'VisitorTelephone',p:'%external_phone%'},
    {id:'VisitorIP',p:'%external_ip%'},
    {id:'VisitorCountry',p:'%location_country%'},
    {id:'VisitorCountryISO',p:'%location_country_iso%'},
    {id:'VisitorRegion',p:'%location_region%'},
    {id:'VisitorRegion',p:'%location_region%'},
    {id:'VisitorCity',p:'%location_city%'},
    {id:'VisitorQuestion',p:'%question%'},
    {id:'VisitorDetails',p:'%details%'},
    {id:'WebsitePageDomain',p:'%domain%'},
    {id:'WebsiteName',p:'%website_name%'},
    {id:'WebsitePageTitle',p:'%page_title%'},
    {id:'WebsitePageURL',p:'%url%'}
];

ChatEditorClass.__UpdateChatInputSize = function(){

    /*
    if((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
    {
        var body = lzm_chatInputEditor.getBody();
        var elemheight = $('#wysiwygchat-input').height();
        var scrollheight = $(body).height();

        if(scrollheight > (elemheight-5) && ChatEditorClass.ExpandChatInputOffset == 0)
        {
            //ChatEditorClass.ExpandChatInputOffset = 100;
            //UIRenderer.resizeMychats();
        }
    }
    */
};

ChatEditorClass.HandlePaste = function(e) {

    var clipboardData, pastedData, removeHTML = !LocalConfiguration.PasteHTML;

    e.stopPropagation();
    e.preventDefault();
    clipboardData = e.clipboardData || window.clipboardData;

    var _html = clipboardData.getData('text/html');
    var _plain = clipboardData.getData('text/plain');

    if(!_html.length && _plain.length)
        _html = _plain;

    pastedData = (removeHTML) ? _plain : _html;
    pastedData = (removeHTML) ? lzm_commonTools.escapeHtml(pastedData,true) : pastedData;

    if(ChatEditorClass.ActiveEditor != null)
    {
        if(ChatEditorClass.ActiveEditor.grabHtml()=='')
            ChatEditorClass.ActiveEditor.setHtml(pastedData);
        else
            ChatEditorClass.ActiveEditor.insertHtml(pastedData);
    }
};