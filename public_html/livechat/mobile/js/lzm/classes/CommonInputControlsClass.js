function CommonInputControlsClass() {

}

CommonInputControlsClass.prototype.createInputMenu = function(replaceElement, inputId, inputClass, width, placeHolder, value, selectList, scrollParent, selectmenuTopCorrection) {
    scrollParent = (typeof scrollParent != 'undefined') ? scrollParent : 'NOPARENTGIVEN';
    selectmenuTopCorrection = (typeof selectmenuTopCorrection != 'undefined') ? selectmenuTopCorrection : 0;

    var visibleList = [];
    var widthString = (width != 0) ? ' width: ' + width + 'px;' : ' width: 100%';

    if(selectList.length<2)
        $('#' + inputId + '-menu').css('display', 'none');

    var inputMenu = '<span id="' + inputId + '-box" class="lzm-combobox ' + inputClass + '"><input type="text" data-role="none" id="' +  inputId + '" style="box-shadow:none;padding: 0px; border: 0px;' + widthString + '" placeholder="' + placeHolder + '" value="' + value + '" /><span id="' + inputId + '-menu" style="cursor: pointer;margin-left:5px;"><i class="fa fa-chevron-down"></i></span></span>';
    inputMenu += '<ul id="' + inputId + '-select" class="lzm-menu-select" style="display: none;"></ul>';

    function switchInputList(_show){
        if (!_show)
        {
            $('.lzm-menu-select').css('display', 'none');
            $('.lzm-menu-select').data('visible', false);
        }
        else
        {
            setTimeout(function() {
                $('.lzm-menu-select').css('display', 'none');
                $('.lzm-menu-select').data('visible', false);
                $('#' + inputId + '-select').css('display', 'block');
                $('#' + inputId + '-select').data('visible', true);
            }, 10);
            var scrollX = ($('#' + scrollParent).length > 0) ? $('#' + scrollParent)[0].scrollLeft : 0;
            var scrollY = ($('#' + scrollParent).length > 0) ? $('#' + scrollParent)[0].scrollTop : 0;
            $('#' + inputId + '-select').css({
                left: Math.floor(eltPos.left + 2 - scrollX) + 'px',
                top: Math.floor(eltPos.top + eltHeight + 12 + selectmenuTopCorrection - scrollY) + 'px'
            });
        }
    }
    function updateInputList(_matchText){
        var i,kIndex = parseInt($('#' + inputId).data('key-index'));
        if(kIndex > visibleList.length-1)
            kIndex = 0;
        if(kIndex < 0)
            kIndex = visibleList.length-1;
        var listElements = '', hlClass;
        var addedCount = 0;
        visibleList = [];
        for (i=0; i<selectList.length; i++)
        {
            hlClass = '';
            var listValue = (selectList[i].constructor == Array) ? selectList[i][0] : selectList[i];
            if(_matchText == null || listValue.toLowerCase().indexOf(_matchText.toLowerCase())==0)
            {
                var usedCount = (selectList[i].constructor == Array) ? selectList[i][1] : 0;
                listElements += '<li id="'+inputId+'-selectoption-'+ addedCount.toString()+'" class="' + inputId + '-selectoption input-select-combo'+hlClass+'" style="cursor: default; position: relative;" title="'+listValue+' ('+usedCount+')">' + listValue + '<span class="lzm-delete-menu-item" onclick="deleteSalutationString(event, \'' + inputId + '\', \'' + listValue + '\');"><i class="fa fa-remove" style="color: #ee0000; font-size: 16px;"></i></span></li>';
                addedCount++;
                visibleList.push(selectList[i]);
            }
            if(addedCount==20)
                break;
        }
        $('#' + inputId + '-select').html(listElements);
        $('#tr-intro-selectoption-' + kIndex.toString()).addClass('bg-blue').addClass('text-white');
        $('.' + inputId + '-selectoption').click(function(e) {
            $('#' + inputId).val($(this).html().replace(/<span class="lzm-delete-menu-item".*?span>/, ''));
            $('#' + inputId + '-select').css('display', 'none');
        });
        $('#' + inputId).data('key-index',kIndex);
        return listElements.length > 0;
    }

    $('#' + replaceElement).html(inputMenu).trigger('create');
    updateInputList(null);

    var eltPos = $('#' + inputId + '-box').position();
    var eltWidth = $('#' + inputId + '-box').width()+8;
    var eltHeight = $('#' + inputId + '-box').height();
    $('#' + inputId + '-select').css({
        left: Math.floor(eltPos.left + 2) + 'px',
        top: Math.floor(eltPos.top + eltHeight + 8 + selectmenuTopCorrection) + 'px',
        width: Math.floor(eltWidth) + 'px'
    });

    $('#' + replaceElement).css({'line-height': 2.5, 'white-space': 'nowrap'});
    if(selectList.length>1)
        $('#' + inputId + '-menu').click(function(e) {
            switchInputList($('#' + inputId + '-select').css('display') != 'block');
        });

    if ($('#' + scrollParent).length > 0) {
        $('#' + scrollParent).scroll(function() {
            $('#' + inputId + '-select').css({display: 'none'});
        });
    }

    $('#' + inputId).data('key-index',0);
    $('#' + inputId).keyup(function(e) {
        if(e.keyCode == 40)
            $('#' + inputId).data('key-index',parseInt($('#' + inputId).data('key-index'))+1);
        else if(e.keyCode == 38)
            $('#' + inputId).data('key-index',parseInt($('#' + inputId).data('key-index'))-1);

        else if(e.keyCode == 13)
            $('#' + inputId+'-selectoption-'+ $('#' + inputId).data('key-index')).click();

        if(e.keyCode == 13 || e.keyCode == 9 || e.keyCode == 27)
            $('#' + inputId + '-select').css('display', 'none');
        else
            switchInputList(updateInputList($('#' + inputId).val()));
    });
    $('#' + inputId).keydown(function(e) {
        if(e.keyCode == 9)
            $('#' + inputId + '-select').css('display', 'none');
    });
    $('body').click(function() {
        if ($('#' + inputId + '-select').css('display') == 'block') {
            $('#' + inputId + '-select').css('display', 'none');
        }
    });
};

CommonInputControlsClass.prototype.createInput = function(myId, myClass, myText, myLabel, myIcon, myType, myLayoutType, myData, myRightText, myLeftText) {
    myLayoutType = (typeof myLayoutType != 'undefined') ? myLayoutType : 'a';
    myType = (typeof myType != 'undefined' && myType != '') ? myType : 'text';
    myData = (typeof myData != 'undefined' && myData != '') ? ' ' + myData : '';
    myRightText = (typeof myRightText != 'undefined' && myRightText != '') ? '&nbsp;<span class="lzm-input-titleright">' + myRightText +'</span>' : '';
    myLeftText = (typeof myLeftText != 'undefined' && myLeftText != '') ? '<span class="lzm-input-titleleft">' + myLeftText +'</span>&nbsp;' : '';
    myClass = (myClass != '') ? myClass + ' lzm-input-container-' + myLayoutType : 'lzm-input-container-' + myLayoutType;
    var iconWidth = (myIcon != '') ? 10 : 0;
    var textLeft = (myLayoutType == 'a') ? ' style="left: ' + (iconWidth + 21) + 'px;right:0px;"' : '';
    var inputMarginTop = (myType == 'file') ? ' style="margin-top: 4px;padding-left:10px;"' : '';
    var redWidth = (myLeftText.length && myType != 'number') ? 'max-width:80%;' : '';
    var inputHtml = '';
    if(myLayoutType == '')
    {
        inputHtml = '<div class="' + myClass + '" id="' + myId + '-container">';
        if(myLabel.length)
            inputHtml += '<label for="' + myId + '" id="' + myId + '-label">' + myLabel + '</label>';
        inputHtml+= '<div class="lzm-input-'+myType+'" id="' + myId + '-text"' + textLeft + ' style="white-space:nowrap;'+redWidth+'">'+myLeftText+'<input type="' + myType + '" autocapitalize="off" autocorrect="off" autocomplete="off"'+myData+' id="' + myId + '" value="' + myText + '" ' + inputMarginTop + ' />'+myRightText+'</div></div>';
    }
    else
    {
        inputHtml = '<div class="' + myClass + '" id="' + myId + '-container">';
        if(myLabel.length)
            inputHtml += '<label for="' + myId + '" id="' + myId + '-label" class="lzm-label-' + myLayoutType + '">' + myLabel + '</label>';
        inputHtml += '<div class="lzm-input-icon-' + myLayoutType + '" id="' + myId + '-icon" style="width: ' + iconWidth + 'px;">' + myIcon + '</div>' +
            '<div class="lzm-input-text-' + myLayoutType + '" id="' + myId + '-text"' + textLeft + '>' +
            '<input type="' + myType + '" autocapitalize="off" autocorrect="off" autocomplete="off"'+myData+' id="' + myId + '" class="lzm-input-inner-' + myLayoutType + '" value="' + myText + '" "' + inputMarginTop + ' />' +
            '</div>' +
            '</div>';
    }
    return inputHtml
};

CommonInputControlsClass.prototype.createFileSelect = function(myId,myClass,myLabel){
    myClass = (myClass != '') ? ' ' + myClass : '';
    return '<label class="lzm-file-label'+myClass+'"><input id="' + myId + '" type="file" required/><span>' + myLabel + '</span></label>';
};

CommonInputControlsClass.prototype.createColor = function(myId, myClass, myText, myLabel, myIcon) {
    var myLayoutType = 'a';
    myClass = (myClass != '') ? myClass + ' lzm-input-container-a' : 'lzm-input-container-a';
    var iconWidth = 15;
    var textLeft = ' style="left: ' + (iconWidth + 20) + 'px;right:0px;"';
    var color = '';

    if(myText.length > 0 && lzm_commonTools.isHEXColor(myText))
        color = 'background:' + myText+ ';';

    var inputHtml = '<div class="' + myClass + ' lzm-input-color" id="' + myId + '-container">';

    if(myLabel.length)
        inputHtml += '<label for="' + myId + '" id="' + myId + '-label" class="lzm-label-color">' + myLabel + '</label>';

    inputHtml+=
        '<div class="lzm-input-icon-' + myLayoutType + '" id="' + myId + '-icon" style="'+color+'width: ' + iconWidth + 'px;">' + myIcon + '</div>' +
        '<div class="lzm-input-text-' + myLayoutType + '" id="' + myId + '-text"' + textLeft + '>' +
        '<input type="text" onchange="if(this.value.indexOf(\'#\')==-1)this.value=\'#\'+this.value;" autocapitalize="off" autocorrect="off" autocomplete="off" id="' + myId + '" class="lzm-input-inner-' + myLayoutType + '" value="' + myText + '" placeholder="' + myLabel + '" />' +
        '</div>' +
        '</div>';

    return inputHtml;
};

CommonInputControlsClass.prototype.createPosition = function(myId, value) {
    var inputHtml = '<table id="'+myId+'" class="lzm-position"><tr>' +
        '<td id="'+myId+'left top" class="'+myId+((value=='left top')? ' lzm-position-selected' : '')+' y0"><i class="fa fa-arrow-left"></i></td>' +
        '<td id="'+myId+'center top" class="'+myId+((value=='center top')? ' lzm-position-selected' : '')+' y0"><i class="fa fa-arrow-up"></i></td>' +
        '<td id="'+myId+'right top" class="'+myId+((value=='right top')? ' lzm-position-selected' : '')+' y0"><i class="fa fa-arrow-right"></i></td></tr><tr>' +
        '<td id="'+myId+'left middle" class="'+myId+((value=='left middle')? ' lzm-position-selected' : '')+' y1"><i class="fa fa-arrow-left"></i></td>' +
        '<td id="'+myId+'center middle" class="'+myId+((value=='center middle')? ' lzm-position-selected' : '')+' y1"><i class="fa fa-arrows"></i></td>' +
        '<td id="'+myId+'right middle" class="'+myId+((value=='right middle')? ' lzm-position-selected' : '')+' y1"><i class="fa fa-arrow-right"></i></td></tr><tr>' +
        '<td id="'+myId+'left bottom"  class="'+myId+((value=='left bottom')? ' lzm-position-selected' : '')+' y2"><i class="fa fa-arrow-left"></i></td>' +
        '<td id="'+myId+'center bottom" class="'+myId+((value=='center bottom')? ' lzm-position-selected' : '')+' y2"><i class="fa fa-arrow-down"></i></td>' +
        '<td id="'+myId+'right bottom" class="'+myId+((value=='right bottom')? ' lzm-position-selected' : '')+' y2"><i class="fa fa-arrow-right"></i></td></tr></table>';
    return inputHtml;
}

CommonInputControlsClass.prototype.createSelect = function(myId, myClass, myAction, myText, myIcon, myCss, myTitle, myOptionList, mySelectedOption, myLayoutType, data, mySize) {
    myLayoutType = (typeof myLayoutType != 'undefined') ? myLayoutType : 'a';
    myId = (typeof myId != 'undefined' && myId != '') ? myId : md5('' + Math.random());
    var myInnerId = (typeof myId != 'undefined' && myId != '') ? ' id="' + myId + '-inner"' : '';
    var mySelectId = (typeof myId != 'undefined' && myId != '') ? ' id="' + myId + '"' : '';
    var myIconId = (typeof myId != 'undefined' && myId != '') ? ' id="' + myId + '-inner-icon"' : '';
    var myTextId = (typeof myId != 'undefined' && myId != '') ? ' id="' + myId + '-inner-text"' : '';
    var myOuterId = (typeof myId != 'undefined' && myId != '') ? ' id="' + myId + '-outer"' : '';
    myClass = (typeof myClass != 'undefined' && myClass != '') ? ' class="lzm-select- '+ myClass + '"' : ' class="lzm-select-' + myLayoutType + '"';
    myAction = (typeof myAction != 'undefined' && myAction != '') ? ' onclick="' + myAction + '"' : '';
    myCss = (typeof myCss != 'undefined') ? myCss : {};
    myTitle = (typeof myTitle != 'undefined' && myTitle != '') ? ' title="' + myTitle + '"' : '';
    myText = (typeof myText != 'undefined') ? myText : true;
    myIcon = (typeof myIcon != 'undefined') ? myIcon : false;
    mySize = (typeof mySize != 'undefined') ? ' size="'+mySize+'"' : '';
    var msClass = (typeof mySize != 'undefined') ? ' lzm-multiselect' : '';
    var label = (typeof myText != 'undefined' && myText != '') ? '<label for="'+myId+'">'+myText+'</label>' : '';
    var myIconImage = '', myIconGap = '0px', myIconTop = '6px', myIconFA = '<i class="fa fa-chevron-down"></i>';
    if (typeof myIcon == 'object') {
        myIconImage = (typeof myIcon.image != 'undefined') ? myIcon.image : 'NONE';
        myIconGap = myIcon.gap;
        myIconTop = (typeof myIcon.top != 'undefined') ? myIcon.top : myIconTop;
        myIcon = myIcon.position;
    }
    myOptionList = (typeof myOptionList != 'undefined') ? myOptionList : [];
    mySelectedOption = (typeof mySelectedOption != 'undefined') ? mySelectedOption : 0;
    var mySelectedOptionIndex = 0, i = 0;
    for (i=0; i<myOptionList.length; i++) {
        if ((typeof myOptionList[i].value != 'undefined' && myOptionList[i].value == mySelectedOption) || myOptionList[i].text == mySelectedOption) {
            mySelectedOptionIndex = i;
        }
    }
    var selectCss = ' style=\'';
    for (var cssTag in myCss) {
        if (myCss.hasOwnProperty(cssTag)) {
            selectCss += ' ' + cssTag + ': ' + myCss[cssTag] + ';';
        }
    }
    selectCss += '\'';
    var selectIconCss = '', selectTextCss = '', selectInnerCss = '';
    var selectText = '';
    if (myOptionList.length > mySelectedOptionIndex && (typeof myOptionList[mySelectedOptionIndex].icon != 'undefined' || myIconImage != '')) {
        var iconPosition = (myIcon == 'left') ? ' left: ' + myIconGap + ';' : ' right: ' + myIconGap + ';';
        selectTextCss = (myIcon == 'left') ? ' style="padding-right: ' + myIconGap + '; padding-left: 30px; font-size: 12px;"' :
            (!myIcon) ? ' style="font-size: 12px;"' : ' style="padding-left: ' + myIconGap + '; padding-right: 30px; font-size: 12px;"';
        selectInnerCss = (myIcon == 'left') ? '' : (!myIcon) ? '' : ' style="padding-left: 4px;"';
        myIconImage = (myIconImage == 'NONE' || myIconImage == '') ? (typeof myOptionList[mySelectedOptionIndex].icon != 'undefined') ?
            myOptionList[mySelectedOptionIndex].icon : '' : myIconImage;
        myIconFA = (myIconImage == '') ? myIconFA : '';
        selectIconCss += ' style=\'background-image: url("' + myIconImage + '"); ' + iconPosition + ' top: ' + myIconTop + ';\';';
        selectText = myOptionList[mySelectedOptionIndex].text;
    }
    var iconHtml = '<span' + myIconId + ' class="lzm-select-inner-icon-' + myLayoutType + '"' + selectIconCss + '>' + myIconFA + '</span>';
    var textHtml = '<span' + myTextId + ' class="lzm-select-inner-text-' + myLayoutType + '"' + selectTextCss + '>' + selectText + '</span>';
    var innerHtml = (myIcon && myText) ? iconHtml + textHtml :
        (!myIcon && myText) ? textHtml : iconHtml;
    var selectData = '';
    if (typeof data != 'undefined' && data != null)
        for (var dataTag in data)
            if (data.hasOwnProperty(dataTag))
                selectData += ' data-' + dataTag + '=' + data[dataTag];


    if(myLayoutType == '')
    {
        var selectHtml = '<div' + myOuterId + myClass + '>'+label+'<select' + mySelectId + selectData + mySize + ' class="lzm-select'+msClass+'" data-role="none">';

        for (i=0; i<myOptionList.length; i++)
        {
            var selectValue = (typeof myOptionList[i].value != 'undefined') ? myOptionList[i].value : myOptionList[i].text;
            var selectedString = (i == mySelectedOptionIndex) ? ' selected="selected"' : '';
            selectHtml += '<option' + selectedString + ' value="' + selectValue + '">' + myOptionList[i].text + '</option>';
        }
        selectHtml += '</select></div>';
    }
    else
    {
        var selectHtml = '<div' + myOuterId + myClass + myAction + myTitle + selectCss + '>' +
            '<span' + myInnerId + selectInnerCss + ' class="lzm-select-inner-' + myLayoutType + '">' + innerHtml + '</span>' +
            '<select' + mySelectId + selectData + mySize + ' class="lzm-select-select-' + myLayoutType + msClass+'" data-role="none">';
        for (i=0; i<myOptionList.length; i++) {
            var selectValue = (typeof myOptionList[i].value != 'undefined') ? myOptionList[i].value : myOptionList[i].text;
            var selectedString = (i == mySelectedOptionIndex) ? ' selected="selected"' : '';
            selectHtml += '<option' + selectedString + ' value="' + selectValue + '">' + myOptionList[i].text + '</option>';
        }
        selectHtml += '</select></div>';

    }
    return selectHtml;
};

CommonInputControlsClass.prototype.createSelectChangeHandler = function(myId, myOptions) {
    $('#' + myId).change(function() {
        for (var i=0; i<myOptions.length; i++) {
            if (myOptions[i].value == $('#' + myId).val()) {
                if (typeof myOptions[i].icon != 'undefined') {
                    $('#' + myId + '-inner-icon').css({'background-image': 'url("' + myOptions[i].icon + '")'});
                }
                $('#' + myId + '-inner-text').html(myOptions[i].text);
            }
        }
    });
};

CommonInputControlsClass.prototype.createRadio = function(myId, myClass, myName, myLabel, isChecked, myValue) {
    var check = (isChecked) ? ' checked': '';
    myClass = (typeof myClass != 'undefined') ? ' class="' + myClass + '"' : '';
    myValue = (typeof myValue != 'undefined') ? ' value="' + myValue + '"' : '';
    return '<div'+myClass+'><input type="radio" class="radio-custom '+myName+'" name="'+myName+'" id="'+myId+'" '+check+myValue+'><label class="radio-custom-label '+myName+'" for="'+myId+'">'+myLabel+'</label></div>';
};

CommonInputControlsClass.prototype.createCheckbox = function(myId, myLabel, myValue, myClass, divCss) {
    var check = (myValue) ? ' checked': '';
    slider = (typeof slider != 'undefined') ? slider : false;
    myClass = (typeof myClass != 'undefined') ? myClass : '';
    divCss = (d(divCss) && divCss != '') ? ' style="'+divCss+'"' : '';
    return '<div'+divCss+'><input type="checkbox" class="checkbox-custom '+myClass+'" id="'+myId+'" '+check+'>' +
        '<label class="checkbox-custom-label '+myClass+'" for="'+myId+'">'+myLabel+'</label></div>';
};

CommonInputControlsClass.prototype.createImageBox = function(myId) {
    return '<div class="lzm-image-box"><div id="'+myId+'-img"></div></div>';
};

CommonInputControlsClass.prototype.createButton = function(myId, myClass, myAction, myText, myIcon, myType, myCss, myTitle, myTextLength, myLayoutType, textWidth) {

    var myTextId = (typeof myId != 'undefined' && myId != '') ? myId : '';
    myId = (typeof myId != 'undefined' && myId != '') ? ' id="' + myId + '"' : '';
    myClass = (typeof myClass != 'undefined') ? myClass : '';
    myAction = (typeof myAction != 'undefined' && myAction != '') ? ' onclick="' + myAction + '"' : '';
    myText = (typeof myText != 'undefined') ? myText : '';
    myIcon = (typeof myIcon != 'undefined') ? myIcon : '';
    myType = (typeof myType != 'undefined') ? myType : '';
    myCss = (typeof myCss != 'undefined') ? myCss : {};
    textWidth = (d(textWidth)) ? textWidth : 500;
    myTitle = (typeof myTitle != 'undefined') ? ' title="' + myTitle + '"' : '';
    myTextLength = (typeof myTextLength != 'undefined') ? myTextLength : 30;

    if(typeof IFManager != 'undefined' && (IFManager.IsMobileOS || IFManager.IsTabletOS))
        if (myTextLength > 4)
        {
            myText = (myText.length > myTextLength) ? myText.substr(0, myTextLength - 3) + '...' : myText;
        }

    myLayoutType = (typeof myLayoutType != 'undefined' && myLayoutType != '') ? myLayoutType : 'b';

    var showNoText = ($(window).width() < textWidth && myType != "force-text");
    var buttonCss = ' style="%LEFTPADDING%%RIGHTPADDING%';
    for (var cssTag in myCss) {
        if (myCss.hasOwnProperty(cssTag)) {
            var myCssTag = '';
            if ((cssTag == 'padding-left' || cssTag == 'padding-right' ) && myText != '' && showNoText)
                myCssTag = (parseInt(myCss[cssTag]) + 0)+'px';
            else
                myCssTag = myCss[cssTag];

            buttonCss += ' ' + cssTag + ': ' + myCssTag + ';';
        }
    }
    buttonCss += '"';
    switch (myType)
    {
        case 'l':
            myClass = myClass + ' lzm-button-' + myLayoutType + ' lzm-button-left-' + myLayoutType;
            break;
        case 'r':
            myClass = myClass + ' lzm-button-' + myLayoutType + ' lzm-button-right-' + myLayoutType;
            break;
        case 'm':
            myClass = myClass + ' lzm-button-' + myLayoutType + '';
            break;
        default:
            myClass = myClass + ' lzm-button-' + myLayoutType + ' lzm-button-left-' + myLayoutType + ' lzm-button-right-' + myLayoutType;
            break;
    }
    myClass += ' lzm-unselectable';
    myClass = (myClass.replace(/^ */, '') != '') ? ' class="' + myClass.replace(/^ */, '') + '"' : '';
    var iconPadding = '', buttonTextCss = '';
    if (myIcon != '' && (myText == '' || showNoText)) {
        var padLeft = (typeof myCss['padding-left'] == 'undefined' && typeof myCss['padding'] == 'undefined') ? ' padding-left: 12px;' : '';
        var padRight = (typeof myCss['padding-right'] == 'undefined' && typeof myCss['padding'] == 'undefined') ? ' padding-right: 12px;' : '';
        buttonCss = buttonCss.replace(/%LEFTPADDING%/g,padLeft).replace(/%RIGHTPADDING%/g,padRight);
        buttonTextCss = ' display: none;';
    } else if (myIcon != '' && (myText != '' && !showNoText)) {
        buttonCss = buttonCss.replace(/%LEFTPADDING%/g,'').replace(/%RIGHTPADDING%/g,'');
        buttonTextCss = 'display: inline; padding-left: 5px;';
    } else {
        buttonCss = buttonCss.replace(/%LEFTPADDING%/g,'').replace(/%RIGHTPADDING%/g,'');
        buttonTextCss = 'display: inline;';
    }
    var buttonHtml = '<span' + buttonCss + myId + myClass + myTitle + myAction + '>' + myIcon + '<span id="' + myTextId + '-text" style="' + buttonTextCss + '">' + myText + '</span>' + '</span>';

    return buttonHtml
};

CommonInputControlsClass.prototype.createArea = function(myId, myText, myClass, myLabel, myCSS, myAttributes) {
    var areaHtml = '';
    myCSS = (d(myCSS)) ? myCSS : '';
    myAttributes = (typeof myAttributes != 'undefined') ? myAttributes+' ' : '';
    if(typeof myLabel != 'undefined')
        areaHtml += '<label>'+myLabel+'</label>';

    return areaHtml + '<textarea id="'+myId+'" class="'+myClass+'" '+myAttributes+'style="'+myCSS+'">'+myText+'</textarea>';
};

CommonInputControlsClass.prototype.createPriorityList = function(id,cssClass,value,max,label) {
    var priorityList = [], lhtml ='';
    for(var i=1;i<max+1;i++)
        priorityList.push({text:i,value:i});

    if(d(label) && label.length)
        lhtml += '<label for="' + id + '" id="' + id + '-label">' + label + '</label>';

    return lhtml + this.createSelect(id,cssClass,'','','','','',priorityList,value,'');
};

CommonInputControlsClass.prototype.CreateOperatorList = function(tableid,groups,priorities,checkList) {
    groups = (d(groups)) ? groups : true;
    priorities = (d(priorities)) ? priorities : true;

    var olHtml = '<table id="'+tableid+'" class="visible-list-table alternating-rows-table lzm-unselectable"><thead><tr><th style="width:16px;"></th><th>'+tid('operator')+'</th>';

    if(groups)
        olHtml += '<th>'+tid('group')+'</th>';

    if(priorities)
        olHtml += '<th>'+tid('priority')+'</th></tr>';

    olHtml += '</thead><tbody>';

    var operators = DataEngine.operators.getOperatorList('','',true,false);

    for(var key in operators)
    {
        var op = operators[key];
        var groupList = [];
        for(var i in op.groups)
        {
            var groupObj = DataEngine.groups.getGroup(op.groups[i]);
            if(groupObj != null && groupObj.external == 1)
                groupList.push({text:op.groups[i],value:op.groups[i]});
        }

        var checkit = false;
        if(d(checkList))
            for(var rkey in checkList)
                if(checkList[rkey].rec == op.id)
                    checkit = true;

        olHtml += '<tr id="'+tableid+'-'+op.id+'" data-obj="'+lz_global_base64_encode(JSON.stringify(op))+'"><td>' + this.createCheckbox(tableid + '-cb-' + op.id,'',checkit,tableid + '-cb' + ((groupList.length || !groups) ? '' : ' ui-disabled'),'') + '</td>';
        olHtml += '<td>' + lzm_commonTools.SubStr(op.userid,20,true) + '</td>';

        if(groups && groupList.length)
            olHtml += '<td>' + this.createSelect(tableid + '-groups-' + op.id,'','','','','','',groupList,0,'') + '</td>';
        else if(groups)
            olHtml+='<td class="text-center"><i>'+tid('none')+'</i></td>';

        if(priorities)
            olHtml += '<td>' + this.createPriorityList(tableid + '-priority-' + op.id,((groupList.length) ? '' : 'ui-disabled'),1,10) + '</td>';

        olHtml += '</tr>';
    }
    return olHtml + '</tbody></table>';
};

CommonInputControlsClass.prototype.createTabControl = function(replaceElement, tabList, selectedTab, placeHolderWidth, layoutType) {
    var displayType = 'inline-block';
    var mySelectedTab = (typeof selectedTab != 'undefined' && selectedTab > -1 && selectedTab < tabList.length) ? Math.max(selectedTab, 0) : 0;

    selectedTab = (typeof selectedTab != 'undefined') ? selectedTab : 0;
    placeHolderWidth = (typeof placeHolderWidth != 'undefined' && placeHolderWidth != 0) ? placeHolderWidth : $('#' + replaceElement).parent().width();
    layoutType = (typeof layoutType != 'undefined') ? layoutType : 'a';

    var allTabsWidth = 0, visibleTabsWidth = 0, closedTabColor = '#E0E0E0';

    $('body').append('<div id="test-tab-width-container" style="position: absolute; left: -1000px; top: -1000px; width: 800px; height: 100px;"></div>').trigger('create');

    var tabRowHtml = '';
    var tabRowArray = [];
    var contentRowHtml = '';
    var tabsAreTooWide = false, tabsAreStillTooWide = false;
    var thisTabHtml = '', thisTabWidth = [], firstVisibleTab = 0, lastVisibleTab = 0;
    var leftTabHtml = '<span class="lzm-tabs" id="' + replaceElement + '-tab-more-left" draggable="true" style="background-color: ' + closedTabColor + '; display: none; text-shadow: none;"> <i class="fa fa-angle-double-left"></i> </span>';
    var rightTabHtml = '<span class="lzm-tabs" id="' + replaceElement + '-tab-more-right" draggable="true" style="background-color: ' + closedTabColor + '; display: '+displayType+'; text-shadow: none;"> <i class="fa fa-angle-double-right"></i> </span>';

    $('#test-tab-width-container').html(rightTabHtml).trigger('create');
    var rightTabWidth = $('#' + replaceElement + '-tab-more-right').width() + 22;

    $('#test-tab-width-container').html(leftTabHtml).trigger('create');
    var leftTabWidth = $('#' + replaceElement + '-tab-more-left').width() + 22;

    for (var i=0; i<tabList.length; i++)
    {
        var dataHash = (typeof tabList[i].hash != 'undefined') ? ' data-hash="' + tabList[i].hash + '"' : '';
        var tabName = (tabList[i].name.length <= 40) ? tabList[i].name : tabList[i].name.substr(0, 40) + '...';
        var classSelected = (i == mySelectedTab) ? ' lzm-tabs-selected' : '';

        thisTabHtml = '<span class="lzm-tabs ' + replaceElement + '-tab' + classSelected + '" id="' + replaceElement + '-tab-' + i + '" draggable="true" style="display: %DISPLAY%;" data-tab-no="' + i + '"' + dataHash + '>' + tabName + '</span>';
        $('#test-tab-width-container').html(thisTabHtml).trigger('create');
        thisTabWidth[i] = $('#' + replaceElement + '-tab-' + i).width() + 22;
        if (false && allTabsWidth + thisTabWidth[i] > placeHolderWidth) // deactivate scroll buttons
        {
            tabsAreTooWide = true;
            if (allTabsWidth + rightTabWidth > placeHolderWidth)
            {
                tabsAreStillTooWide = true;
            }
        }
        if (tabsAreTooWide)
        {
            thisTabHtml = thisTabHtml.replace(/%DISPLAY%/, 'none');
            if (tabsAreStillTooWide)
            {
                var lastTabNo = tabRowArray.length -1;
                if(d(tabRowArray[lastTabNo]))
                    tabRowArray[lastTabNo] = tabRowArray[lastTabNo].replace(displayType, 'none');
            }
        }
        else
        {
            thisTabHtml = thisTabHtml.replace(/%DISPLAY%/, displayType);
            allTabsWidth += thisTabWidth[i];
            lastVisibleTab = i;
        }

        tabRowArray.push(thisTabHtml);
        tabRowHtml += thisTabHtml;

        var displayString = (i == mySelectedTab) ? 'block' : 'none';
        contentRowHtml += '<div class="' + replaceElement + '-content" id="' + replaceElement + '-content-' + i + '" style="display: ' + displayString + '; overflow: auto;"' + dataHash + '>' +
            tabList[i].content +
            '</div>';

    }

    tabRowHtml = tabRowArray.join('');
    if(tabsAreTooWide) {
        tabRowHtml = leftTabHtml + tabRowHtml + rightTabHtml;
    }
    if (tabsAreStillTooWide) {
        lastVisibleTab -= 1;
    }
    var tabString = '<div id="' + replaceElement + '-tabs-row" data-selected-tab="' + selectedTab + '">' + tabRowHtml + '</div>' + contentRowHtml;

    $('#test-tab-width-container').remove();
    $('#' + replaceElement).html(tabString).trigger('create');

    this.addTabControlEventHandler(replaceElement, tabList, firstVisibleTab, lastVisibleTab, thisTabWidth, leftTabWidth, rightTabWidth, visibleTabsWidth, placeHolderWidth, closedTabColor, layoutType);

    var moveRightCounter = Math.max(Math.min(selectedTab - lastVisibleTab, 10), 0);
    for (var j=0; j<moveRightCounter; j++) {
        $('#' + replaceElement + '-tab-more-right').click();
    }
    $('#' + replaceElement + '-tabs-row').attr('class', 'lzm-tabs-row');
};

CommonInputControlsClass.prototype.updateTabControl = function(replaceElement, oldTabList, layoutType) {
    var displayType = 'inline-block';
    var selectedTab = $('#' + replaceElement + '-tabs-row').data('selected-tab');
    selectedTab = (typeof selectedTab != 'undefined') ? selectedTab : 0;
    layoutType = (typeof layoutType != 'undefined') ? layoutType : 'a';

    var i = 0, j = 0, existingTabsArray = [], existingTabsHashArray = [], newTabList = [], lzTabDoesExist = false;
    $('.' + replaceElement + '-tab').each(function() {
        var thisTabHash = $(this).data('hash'), thisTabNo = $(this).data('tab-no'), thisTabHtml = $(this).html();
        existingTabsArray.push({'tab-no': thisTabNo, hash: thisTabHash, html: thisTabHtml});
        existingTabsHashArray.push(thisTabHash);
        if (thisTabHash == 'lz') {
            lzTabDoesExist = true;
        }
    });
    for (i=0; i<oldTabList.length; i++) {
        if (oldTabList[i].action == 1 && oldTabList[i].hash == 'lz' && $.inArray(oldTabList[i].hash, existingTabsHashArray) == -1) {
            newTabList.push({name: oldTabList[i].name, hash: oldTabList[i].hash, content: oldTabList[i].content});
            selectedTab += 1;
        }
    }
    for (i=0; i<existingTabsArray.length; i++) {
        var tabWasRemoved = false;
        for (j=0; j<oldTabList.length; j++) {
            if (existingTabsArray[i].hash == oldTabList[j].hash && oldTabList[j].action == 0) {
                tabWasRemoved = true;
                selectedTab = (selectedTab < i) ? selectedTab : (selectedTab > i) ? selectedTab - 1 : 0;
            }
        }
        if (!tabWasRemoved) {
            newTabList.push({name: existingTabsArray[i].html, hash: existingTabsArray[i].hash, content: null});
        }
    }
    for (i=0; i<oldTabList.length; i++) {
        if (oldTabList[i].action == 1 && oldTabList[i].hash != 'lz' && $.inArray(oldTabList[i].hash, existingTabsHashArray) == -1) {
            newTabList.push({name: oldTabList[i].name, hash: oldTabList[i].hash, content: oldTabList[i].content});
        }
    }

    var mySelectedTab = Math.max(selectedTab, 0);
    $('body').append('<div id="test-tab-width-container" style="position: absolute; left: -2000px; top: -2000px; width: 1800px; height: 100px;"></div>').trigger('create');
    var placeHolderWidth = $('#' + replaceElement).parent().width();
    var thisTabHtml = '', tabsAreTooWide = false, allTabsWidth = 0, lastVisibleTab = 0, tabRowHtml = '', thisTabWidth = [], firstVisibleTab = 0, visibleTabsWidth = 0, closedTabColor = '#E0E0E0';
    var leftTabHtml = '<span class="lzm-tabs" id="' + replaceElement + '-tab-more-left" draggable="true" style="background-color: ' + closedTabColor + '; display: none; text-shadow: none;"> ... </span>';
    var rightTabHtml = '<span class="lzm-tabs" id="' + replaceElement + '-tab-more-right" draggable="true" style="background-color: ' + closedTabColor + '; display: '+displayType+'; text-shadow: none;"> ... </span>';

    $('#test-tab-width-container').html(leftTabHtml.replace(/-tab-more-left/, '-test-tab-more-left')).trigger('create');
    var leftTabWidth = $('#' + replaceElement + '-test-tab-more-left').width() + 22;
    $('#test-tab-width-container').html(rightTabHtml.replace(/-tab-more-left/, '-test-tab-more-left')).trigger('create');
    var rightTabWidth = $('#' + replaceElement + '-test-tab-more-right').width() + 22;
    for (i=0; i<newTabList.length; i++) {
        var tabName = (newTabList[i].name.length <= 40) ? newTabList[i].name : newTabList[i].name.substr(0, 40) + '...';
        var classSelected = (i == mySelectedTab) ? ' lzm-tabs-selected' : '';
        thisTabHtml = '<span class="lzm-tabs ' + replaceElement + '-tab'+classSelected+'" id="' + replaceElement + '-tab-' + i + '" draggable="true" style="display: %DISPLAY%;" data-tab-no="' + i + '" data-hash="' + newTabList[i].hash + '">' + tabName + '</span>';
        $('#test-tab-width-container').html(thisTabHtml).trigger('create');
        thisTabWidth[i] = $('#' + replaceElement + '-tab-' + i).width() + 22;
        if (allTabsWidth + thisTabWidth[i] > placeHolderWidth) {
            tabsAreTooWide = true;
        }
        if (tabsAreTooWide) {
            thisTabHtml = thisTabHtml.replace(/%DISPLAY%/, 'none');
        } else {
            thisTabHtml = thisTabHtml.replace(/%DISPLAY%/, displayType);
            allTabsWidth += thisTabWidth[i];
            lastVisibleTab = i;
        }
        tabRowHtml += thisTabHtml;
    }
    $('#test-tab-width-container').remove();


    if(tabsAreTooWide) {
        tabRowHtml = leftTabHtml + tabRowHtml + rightTabHtml;
    }
    $('#' + replaceElement + '-tabs-row').html(tabRowHtml).trigger('create');
    //$('#' + replaceElement + '-tabs-row').attr('class', 'lzm-tabs-row');
    $('.' + replaceElement + '-content').css('display', 'none');
    for (i=0; i<existingTabsArray.length; i++) {
        $('#' + replaceElement + '-content-' + existingTabsArray[i]['tab-no']).attr('id', replaceElement + '-content-' + existingTabsArray[i].hash);
    }
    var lastVisibleElement = replaceElement + '-tabs-row';
    for (i=0; i<newTabList.length; i++) {
        if (newTabList[i].content == null) {
            $('#' + replaceElement + '-content-' + newTabList[i].hash).attr('id', replaceElement + '-content-' + i);
            lastVisibleElement = replaceElement + '-content-' + i;
        } else {
            $('#' + lastVisibleElement).after('<div class="' + replaceElement + '-content" id="' + replaceElement + '-content-' + i + '" style="border: 1px solid #ccc;' +
                ' border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; border-top-right-radius: 4px;' +
                ' padding: 8px; margin-top: 2px; display: none; overflow: auto;" data-hash="' + newTabList[i].hash + '"></div>');
            lastVisibleElement = replaceElement + '-content-' + i;
            $('#' + lastVisibleElement).html(newTabList[i].content).css('display', 'none');
        }
    }
    $('#' + replaceElement + '-content-' + mySelectedTab).css('display', 'block');
    $('#' + replaceElement + '-tabs-row').data('selected-tab', selectedTab);

    this.addTabControlEventHandler(replaceElement, newTabList, firstVisibleTab, lastVisibleTab, thisTabWidth, leftTabWidth,
        rightTabWidth, visibleTabsWidth, placeHolderWidth, closedTabColor, layoutType);
};

CommonInputControlsClass.prototype.addTabControlEventHandler = function(replaceElement, tabList) {

    var i,displayType = 'inline-block';
    for (i=0; i<tabList.length; i++)
    {
        $('#' + replaceElement + '-tab-' + i).click(function()
        {
            $('.' + replaceElement + '-content').css({display: 'none'});
            $('.' + replaceElement + '-tab').removeClass('lzm-tabs-selected');

            var tabNo = parseInt($(this).data('tab-no'));

            $('#' + replaceElement + '-tabs-row').data('selected-tab', tabNo);
            $('#' + replaceElement + '-content-' + tabNo).css({display: 'block'});
            $('#' + replaceElement + '-tab-' + tabNo).addClass('lzm-tabs-selected');

            if($('#' + replaceElement + '-tab-' + (tabNo+1)).length && $('#' + replaceElement + '-tab-' + (tabNo+1)).css('display')=='none' && $('#' + replaceElement + '-tab-more-right').css('display') == displayType)
                $('#' + replaceElement + '-tab-more-right').click();
            else if($('#' + replaceElement + '-tab-' + (tabNo-1)).length && $('#' + replaceElement + '-tab-' + (tabNo-1)).css('display')=='none' && $('#' + replaceElement + '-tab-more-left').css('display') == displayType)
                $('#' + replaceElement + '-tab-more-left').click();
        });
    }
};

CommonInputControlsClass.prototype.CreateInputControlPanel = function(mode,_disabledClass,_fontStyles) {

    _disabledClass = d(_disabledClass) ? _disabledClass : '';
    _fontStyles = d(_fontStyles) ? _fontStyles : false;

    var panelHtml = '';
    if((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp() || _fontStyles)
    {
        panelHtml += lzm_inputControls.createButton('editor-bold-btn', _disabledClass, 'lzm_chatInputEditor.bold();', '<span style="font-weight: bold;">B</span>', '', 'lr',{'margin-left': '4px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e') +
            lzm_inputControls.createButton('editor-italic-btn', _disabledClass, 'lzm_chatInputEditor.italic();', '<span style="font-style: italic;">I</span>', '', 'lr', {'margin-left': '-1px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e') +
            lzm_inputControls.createButton('editor-underline-btn', _disabledClass, 'lzm_chatInputEditor.underline();', '<span style="text-decoration: underline;">U</span>', '', 'lr',{'margin-left': '-1px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e');
    }
    if (mode == 'basic')
    {
        panelHtml += lzm_inputControls.createButton('editor-html-btn', '', 'lzm_chatInputEditor.showHTML();', 'HTML', '', 'lr',{'margin-left': '4px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e');
        panelHtml += lzm_inputControls.createButton('editor-add-image-btn', '', 'lzm_chatInputEditor.addImage();', tid('image'), '<i class="fa fa-image"></i>', 'lr',{'margin-left': '-1px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e');
        panelHtml += lzm_inputControls.createButton('editor-add-link-btn', '', 'lzm_chatInputEditor.addLink();', tid('link'), '<i class="fa fa-link"></i>', 'lr',{'margin-left': '-1px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e');
        panelHtml += lzm_inputControls.createButton('editor-add-placeholder-btn', '', 'lzm_chatInputEditor.addPlaceholder();', tid('add_placeholder'), '<i class="fa fa-percent"></i>', 'lr',{'margin-left': '-1px', 'padding-left': '12px', 'padding-right': '12px'}, '', -1,'e');
    }
    if (mode != 'basic')
        panelHtml += lzm_inputControls.createButton('send-qrd', _disabledClass, 'KnowledgebaseUI.InsertIntoChat();', '', '<i class="fa fa-database"></i>', 'lr',{'margin-left': '4px'}, tid('knowledgebase'),-1,'e');

    return panelHtml;
};

CommonInputControlsClass.prototype.createInfoField = function(icon, text, myClass) {
    myClass = (d(myClass)) ? ' ' + myClass : '';
    return '<div class="lzm-info-field'+myClass+'"><div class="lzm-info-field-inner"><i class="'+icon+'"></i></div><div class="lzm-info-field-inner"><span>' + text + '</span></div></div>';


};

CommonInputControlsClass.prototype.createAvatarField = function(myClass, name, intid){
    if(!LocalConfiguration.UIShowAvatars)
        return '';
    var url = getAvatarURL(name, intid);
    myClass = (d(myClass)) ? ' ' + myClass : '';
    return '<div class="avatar-box'+myClass+'" style="background-image: url(\'' + url + '\')"></div>';
};

function getAvatarURL(name, intid){
    var getParam = '?' + ((d(intid) && intid.length > 0) ? 'operator=' + encodeURIComponent(intid) : (d(name) && name.length > 0) ? 'name=' + lz_global_base64_url_encode(name) : '');
    return './../picture.php' + getParam;
}

CommonInputControlsClass.prototype.createSlider = function(_id){
    /*
    var html = '<i id="'+_id+'-icon" class="fa fa-toggle-on"></i><input id="'+_id+'-icon" type="checkbox" style="visibility:hidden;display:none;" checked />';

    $(html).trigger('create');
    $('#'+_id).change(function(){
        $('#'+_id+'-icon').prop('class',($('#'+_id).prop('checked') ? 'fa fa-toggle-on' : 'fa fa-toggle-off'));
    });

    $('#'+_id+'-icon').click(function(){
        //$('#'+_id+'').prop('checked',false);

    });

    return html;
    */
};
