/****************************************************************************************
 * LiveZilla ChatForwardInviteClass.js
 *
 * Copyright 2018 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function ChatForwardInvite(){
    this.chatObject = null;
    this.type = '';
    this.groupCount = 0;
    this.selectedGroupId = ''
}

ChatForwardInvite.SelectedLineId = '';

ChatForwardInvite.prototype.showForwardInvite = function(chatId, type) {
    type = (d(type)) ? type : 'forward';

    ChatForwardInvite.SelectedLineId = '';
    var chatObj = DataEngine.ChatManager.GetChat(chatId,'i');
    if (chatObj != null)
    {
        if (lzm_commonPermissions.checkUserPermissions('', 'chats', 'forward', {}))
        {
            this.createOperatorForwardInviteHtml(type, chatObj);
        }
        else
            showNoPermissionMessage();
    }
};

ChatForwardInvite.prototype.createOperatorForwardInviteHtml = function (type, _chatObj) {

    this.type = type;
    this.chatObject = _chatObj;

    var that=this,headerString = tid('forward_chat');

    if (this.type != 'forward')
        headerString = t('Invite operator to chat');

    var footerString =
        lzm_inputControls.createButton('fwd-button', '', '', t('Ok'), '', 'lr', {'margin-left': '4px'},'',30,'d') +
        lzm_inputControls.createButton('cancel-operator-forward-selection', '', '', t('Cancel'), '', 'lr', {'margin-left': '4px'},'',30,'d');

    if(!(_chatObj != null && d(_chatObj.GetWindowId)))
        return;

    var winObjId = _chatObj.GetWindowId();
    var dialogData = {'visitor-id': this.chatObject.SystemId, 'chat-partner': this.chatObject.SystemId, 'chat-id': this.chatObject.i};
    lzm_commonDialog.CreateDialogWindow(headerString, this.GetForwardInviteBodyHTML(), footerString, (this.type != 'forward') ? 'plus' : 'share',  'operator-forward-selection', winObjId + '-operator-forward-selection','cancel-operator-forward-selection',false, dialogData, true);

    $('#cancel-operator-forward-selection').click(function() {
        TaskBarManager.RemoveActiveWindow();
        var chatWin = TaskBarManager.GetWindowByTag(_chatObj.SystemId);
        if(chatWin != null)
            chatWin.Maximize();
    });
    $('#fwd-button').click(function() {
        var row = $('#forward-receiver-table tr.selected-table-line')[0];
        var selectedOpUserId = $(row).data('id');
        if(d(selectedOpUserId) && that.selectedGroupId != '')
        {
            var selectedOperator = DataEngine.operators.getOperator(selectedOpUserId, 'id');
            selectOperatorForForwarding(that.chatObject, selectedOperator.id, selectedOperator.name, that.selectedGroupId, $('#forward-text').val(), 0);
            UserActions.forwardData.forward_text = $('#forward-text').val();
            UserActions.forwardChat(that.chatObject, that.type);
            $('#cancel-operator-forward-selection').click();
        }
    });

    this.applyEvents(true);
    UIRenderer.resizeOperatorForwardSelection();
};

ChatForwardInvite.prototype.applyEvents = function (_init){
    var that = this;
    $('#fwd-button').removeClass('ui-disabled');
    if(this.groupCount>0)
    {
        $('#forward-group-select').unbind('change').change(function() {
            $('#forward-receiver-table').html();
            that.selectedGroupId = $('#forward-group-select').val();
            $('#forward-receiver-table').html(lzm_chatDisplay.CreateOperatorsHTML({id:''},that.getMatchingGroupOperators(that.selectedGroupId,that.type),false,true,ChatForwardInvite.SelectedLineId,'','forward','ChatForwardInvite.SelectLine','nf','nf'));

            if(_init || ChatForwardInvite.SelectedLineId == '')
                $($('#forward-receiver-table tr.operator-forwardlist-line')[0]).addClass('selected-table-line');
        });
        $('#forward-group-select').change();
    }
    else
    {
        $('#fwd-button').addClass('ui-disabled');
        $('#forward-group-select').append($('<option>', {value: 1,text: tid('none')}));
        $('#forward-receiver').html('<div class="text-xxxl text-gray" style="margin-top:18%;">'+tid('none')+'</div>');
        $('#forward-receiver, #forward-text, #forward-group-select').addClass('ui-disabled');
    }
};

ChatForwardInvite.prototype.updateForwardOperators = function(){
    var ft = '';
    if($('#forward-text').length)
        ft = $('#forward-text').val();

    $('#operator-forward-selection-body').html(this.GetForwardInviteBodyHTML());
    this.applyEvents(false);

    $('#forward-text').val(ft);
    //$('#forward-text').focus();
};

ChatForwardInvite.prototype.getMatchingGroupOperators = function(gId,type){
    var i;
    var operators = DataEngine.operators.getOperatorList();
    var availableOperators = DataEngine.operators.getAvailableOperators(this.chatObject.SystemId);
    var soperators = [];
    for (i=0; i<operators.length; i++)
        if (operators[i].userid != lzm_chatDisplay.myLoginId &&
            $.inArray(gId, operators[i].groups) != -1 &&
            (typeof operators[i].isbot == 'undefined' || operators[i].isbot != 1) &&
            (operators[i].status != 2 && operators[i].status != 3) &&
            $.inArray(operators[i].id, this.chatObject.Members) == -1 &&
            ((type == 'forward' && $.inArray(operators[i].id, availableOperators.fIdList) != -1) || (type != 'forward' && $.inArray(operators[i].id, availableOperators.iIdList) != -1)))
            soperators.push(operators[i]);
    return soperators;
};

ChatForwardInvite.prototype.GetForwardInviteBodyHTML = function (){
    var groups = DataEngine.groups.getGroupList(),selected,i;
    var bodyString = '<div class="lzm-fieldset">' +
        '<div id="selection-div"><label for="forward-group-select">'+tidc('group')+'</label>' +
        '<select id="forward-group-select" data-selected-group="">';

    if(this.selectedGroupId=='')
        this.selectedGroupId = this.chatObject.dgr;

    this.groupCount = 0;
    for (i=0; i<groups.length; i++)
        if (d(groups[i].id))
        {
            var opcount = this.getMatchingGroupOperators(groups[i].id,this.type).length;
            if(opcount>0)
            {
                this.groupCount++;
                selected = (groups[i].id==this.selectedGroupId) ? ' selected' : '';
                bodyString += '<option value="' +groups[i].id + '"'+selected+'>' + groups[i].name + ' (' + opcount + ')</option>';
            }
        }

    bodyString += '</select></div>';
    bodyString += '<div class="top-space"><label>'+tidc('operator')+'</label><div id="forward-receiver" class="border-s"><table id="forward-receiver-table" class="tight"></table></div></div>';
    bodyString += '<div class="top-space">' + lzm_inputControls.createInput('forward-text','','',tidc('fwd_additional_info'),'','text','') + '</div>';
    bodyString += '</div>';
    return bodyString;
};

ChatForwardInvite.SelectLine = function(_lineId) {
    ChatForwardInvite.SelectedLineId = $('#' + _lineId).attr('data-id');
    $('#forward-receiver-table tr.selected-table-line').removeClass('selected-table-line');
    $('#' + _lineId).addClass('selected-table-line');
};