function CommonDisplayLayoutClass() {
    this.windowWidth = 0;
    this.windowHeight = 0;
}

CommonDisplayLayoutClass.prototype.resizeAll = function(caller) {
    var that = this;
    that.windowWidth = $(window).width();
    that.windowHeight = $(window).height();

    if (caller == 'index') {
        that.resizeLoginContainer();
    } else {
        that.resizeConfigureContainer();
    }
};

CommonDisplayLayoutClass.prototype.resizeLoginContainer = function() {
    var that = this;
    var lcWidth = 360;
    var lcHeight = $('#login-container').height();
    var lcLeft = Math.max(0, Math.floor((that.windowWidth - lcWidth) / 2));
    var lcTop = Math.max(1, Math.floor((that.windowHeight - lcHeight) / 4));
    var statusRight = $('#login_btn').width() + parseInt($('#login_btn').css('padding-right')) +
        parseInt($('#login_btn').css('padding-right')) + parseInt($('#login_btn').css('right')) + 11;
    $('#login-container').css({left: lcLeft+'px', top: lcTop+'px'});
    $('#login-copyright-link').css({top: (lcTop + lcHeight + 15)+'px'});
    $('#ldap_login').parent().css({display: (!IFManager.IsAppFrame) ? 'block' : 'none'});
    $('#user_status-outer').css({right: statusRight+'px', 'border-color': '#fff'});
    $('#user_status-inner').css({'padding-top': '4px'});
    $('#user_status-inner-text').css({'font-weight': 'normal'});
    var minWidthHeight = Math.min(that.windowWidth, that.windowHeight);
    var thisOrientationButtonCss = (IFManager.AppOS == 'android' && (minWidthHeight >= 290 || lzm_commonDisplay.orientation == 'horizontal')) ? {display: 'block'} : {'display': 'none'};
    $('#orientation_btn').css(thisOrientationButtonCss);
};

CommonDisplayLayoutClass.prototype.resizeConfigureContainer = function() {
    var that = this;
    var lcWidth = 360;//Math.min(600, Math.floor(0.9 * that.windowWidth));
    var innerWidth = 300;
    var profileActionType = $('#profile-configuration-div').data('type');
    var lcHeight = (profileActionType == 'empty') ? 330 : 550;
    var peTop = (profileActionType == 'empty') ? 327 : 697;
    var ccbTop = (profileActionType == 'empty') ? 120 : 490;
    var showProfileSelection = (profileActionType == 'empty') ? 'block' : 'none';
    var lcLeft = Math.max(0, Math.floor((that.windowWidth - lcWidth) / 2));
    var lcTop = Math.max(1, Math.floor((that.windowHeight - lcHeight) / 4));



    $('#configure-container').css({left: lcLeft+'px', top: lcTop+'px', height: lcHeight, display: 'block'});
    $('#configure-form').css({height: (lcHeight - 32)+'px'});
    $('#server_profile_selection').css({width: (innerWidth +15)+'px', 'min-width': '0px'});
    //$('#server_profile_selection-outer').css({width: '95%', 'min-width': '0px'});
   // $('#profile-configuration-div').css({width: (innerWidth - 40)+'px'});

    $('#profile-selection-div').css({display: showProfileSelection});
    $('#configure-buttons-div').css({display: showProfileSelection});
    $('#mobile-directory-container').css({display: 'none'});
    $('#configure-page-end').css({display: 'none'});

    //$('#configure-section-divide').css({width: (innerWidth - 40)+'px'});
    //$('#configure-page-end').css({'top': peTop+'px', left: lcLeft+'px', width: (innerWidth + 2)+'px'});
    //$('#configure-close-buttons-div').css({'bottom': '20px'});

    if (profileActionType != 'empty') {
        $('#save_profile').css({display: 'inline', 'margin-right': '10px'});
        $('#back_btn').html(t('Cancel'));
    } else {
        $('#save_profile').css({display: 'none', 'margin-right': '10px'});
        $('#back_btn').html(t('Ok'));
    }
};

CommonDisplayLayoutClass.prototype.resizeInput = function(inputId, width, height, left, top) {
    $('#' + inputId + '-container').css({top: top+'px', left: left+'px', width: (width - 40)+'px'});
    //$('#' + inputId + '-text').css({width: '100%'});
    $('#' + inputId).css({width: (width - 80)+'px'});
};


