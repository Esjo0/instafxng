function LiveZillaTracking(){

}

LiveZillaTracking.UpdateTextLinks = function(_online){
    var links = document.getElementsByTagName("a");
    for(var i=0;i<links.length;i++)
    {
        if(links[i].className=="lz_text_link")
        {
            links[i].innerHTML = _online ? links[i].getAttribute('data-text-online') : links[i].getAttribute('data-text-offline');
            links[i].style.cssText = _online ? links[i].getAttribute('data-css-online') : links[i].getAttribute('data-css-offline');
            links[i].style.display = _online || links[i].getAttribute('data-online-only')=='0' ? 'inline' : 'none';
        }
    }
};

LiveZillaTracking.UpdateButtons = function(){
    var links = document.getElementsByTagName("a");
    var lcount = 0;
    for(var i=0;i<links.length;i++)
    {
        if(links[i].className=="lz_cbl" || links[i].className=="lz_fl")
        {
            if(lz_cb_url.length<=lcount)
                lz_cb_url[lcount] = links[i].childNodes[0].src;
            links[i].childNodes[0].src = lz_cb_url[lcount] + "&cb=" + new Date().getTime();
            lcount++;
        }
    }
};

LiveZillaTracking.AddPassThruParameters = function(_getValues){
    var val,standardKeys = [111,112,113,114,116];

    for(var key in standardKeys)
        _getValues += LiveZillaTracking.AddPassThruParameter(_getValues,standardKeys[key]);

    for(var i=0;i<10;i++)
        _getValues += LiveZillaTracking.AddPassThruParameter(_getValues,i);

    if(lz_d(lz_data['overwrite']) && lz_data['overwrite'])
    {
        val = '&pto=true';
        if(_getValues.indexOf(val) == -1)
            _getValues += val;
    }

    if(lz_d(lz_data['header']))
    {
        val = '&pth=' + encodeURIComponent(lz_data['header']);
        if(_getValues.indexOf(val) == -1)
            _getValues += val;
    }

    if(LiveZillaData.Language.length>0)
        _getValues += "&ptl="+LiveZillaData.Language;
    else if(lz_d(lz_data['language']))
    {
        val = '&ptl=' + encodeURIComponent(lz_data['language']);
        if(_getValues.indexOf(val) == -1)
            _getValues += val;
    }

    if(LiveZillaData.AreaCode.length>0)
        _getValues += "&ptw="+encodeURIComponent(lz_global_base64_decode(LiveZillaData.AreaCode));
    else if(lz_d(lz_data['website']))
    {
        val = '&ptw=' + encodeURIComponent(lz_data['website']);
        if(_getValues.indexOf(val) == -1)
            _getValues += val;
    }

    if(lz_d(lz_data['textlink']))
        _getValues += "&ctl=1";

    return _getValues;
};

LiveZillaTracking.AddPassThruParameter = function(_paramList,_key){
    var val = '',found=false;

    if(lz_d(LiveZillaData['F' + _key]) && LiveZillaData['F' + _key] != "")
    {
        val = LiveZillaData['F' + _key];
        found = true;
    }

    if(lz_d(lz_data[_key]) && (!found || (lz_d(lz_data['overwrite']) && lz_data['overwrite'])))
    {
        val = lz_global_base64_url_encode(lz_data[_key]);
    }

    if(lz_chat_get_input_value(_key) == "" && val != "")
    {
        var addKey = "&f" + _key + "=" + encodeURIComponent(val);
        if(_paramList.indexOf(addKey) == -1)
            return addKey;
    }
    return "";
};

LiveZillaTracking.Callback = function(_freq){

    if(lz_poll_frequency != _freq)
    {
        lz_poll_frequency = _freq;
        clearTimeout(lz_timer);
        lz_timer = setTimeout("lz_tracking_poll_server(8846);",(lz_poll_frequency*1000));
    }

    if(lz_timer_connection_error != null)
        clearTimeout(lz_timer_connection_error);

    if(!lz_stopped)
        lz_timer_connection_error = setTimeout("LiveZillaTracking.Callback("+_freq+");",30 * 1000);

    lz_tracking_remove_script("livezilla_pollscript");

    if(lz_poll_required)
    {
        lz_poll_required = false;
        lz_tracking_poll_server(1123);
    }

    LiveZillaTracking.UpdateButtons();
};

if(typeof lz_referrer == 'undefined'){
    var lz_referrer = document.referrer;
    var lz_stopped = false;
    var lz_request_window = null;
    var lz_alert_window = null;
    var lz_request_active = null;
    var lz_request_last = null;
    var lz_overlay_box = null;
    var lz_overlay_chat = null;
    var lz_overlay_chat_height = 0;
    var lz_overlay_chat_width = 0;
    var lz_overlay_wm = null;
    var lz_eye_catcher = null;
    var lz_floating_button = null;
    var lz_floating_button_selector = null;
    var lz_overlay_active = null;
    var lz_overlay_last = null;
    var lz_alert_active = null;
    var lz_website_push_active = null;
    var lz_chat_state_expanded = false;
    var lz_event_fire_id = null;
    var lz_session;
    var lz_poll_id = 0;
    var lz_timer = null;
    var lz_timezone_offset = (new Date().getTimezoneOffset() / 60) * -1;
    var lz_chat_windows = [];
    var lz_cb_url = [];
    var lz_document_head = document.getElementsByTagName("head")[0];
    var lz_poll_required = false;
    var lz_timer_connection_error = null;
    var lz_deactivate = null;
    var lz_force_monitoring = false;
    var lz_init_floating_selector = null;
    var lz_chat_fixed_mode = false;
    var lz_data_id = null;
    var lz_overlay_zindex = 20000000;
    var lz_remove_att = null;
    var lz_load_inputs = true;
    var lz_server_time_diff = 0;

    if(typeof lz_ovlel_fsm == 'undefined')
        var lz_ovlel_fsm = false;

    if(typeof lz_ovlec == 'undefined')
        var lz_ovlec = null;

    if(typeof lz_ovlel_tm == 'undefined')
        var lz_ovlel_tm = 0;

    if(typeof lz_code_id == 'undefined')
        var lz_code_id = '';

    if(typeof lz_data == 'undefined')
        var lz_data = {};

    lz_init_tracking();
}

function lz_init_tracking(){

    if(typeof LiveZillaData == 'undefined' || !lz_resources[0] || !lz_resources[1] || !lz_resources[2] || (lz_overlay_chat_available && (!lz_resources[4])))
    {
        setTimeout(lz_init_tracking, 100);
        return;
    }

    if(typeof lz_data['language'] != 'undefined')
        LiveZillaData.Language = encodeURIComponent(lz_data['language']);

    lz_server_time_diff = (parseInt(lz_server_time)-parseInt(lz_global_timestamp()));
    lz_geo_resolution = new lz_geo_resolver();
    lz_session = new lz_jssess();
    lz_session.Load();

    if(location.search.indexOf("lzcobrowse") != -1)
    {
        lz_session.Save();
        lz_tracking_poll_server(3251);
        return;
    }

    try
    {
        if(window.opener != null && typeof(window.opener.lz_get_session) != 'undefined')
        {
            lz_session.UserId = window.opener.lz_get_session().UserId;
            lz_session.GeoResolved = window.opener.lz_get_session().GeoResolved;
        }
    }
    catch(ex)
    {
        // ACCESS DENIED
    }

    lz_session.Save();

    if(!lz_tracking_geo_resolute())
        lz_tracking_poll_server(11121);

    if(lz_is_mobile && !lz_ovlel_fsm)
    {
        window.addEventListener("resize",lz_livebox_init_center_boxes_hide);
        window.addEventListener("scroll",lz_livebox_init_center_boxes_hide);
        window.setInterval("lz_livebox_init_center_boxes(false)",1000);
    }
}

function lz_is_geo_resolution_needed(){
	return (lz_geo_resolution_needed && lz_session.GeoResolved.length != 7 && lz_session.GeoResolutions < 3 && lz_geo_url.length);//5
}

function lz_get_session(){
	return lz_session;
}

function lz_tracking_server_request(_get,_scriptId){

	if(lz_stopped)
		return;

	var lastScript = document.getElementById(_scriptId);
	if(lastScript == null)
	{
		for(var index in lz_chat_windows)
			if(!lz_chat_windows[index].Deleted && lz_chat_windows[index].Closed)
			{
				lz_chat_windows[index].Deleted = true;
				_get += "&clch=" + lz_global_base64_encode(lz_chat_windows[index].BrowserId);
			}

		_get = "?rqst=track" + _get;
		var newScript = document.createElement("script");
		newScript.id = _scriptId;
		newScript.src = lz_poll_url + _get;
		newScript.async = true;
		lz_document_head.appendChild(newScript);
	}
	else
    {
		lz_poll_required = true;
    }
}

function lz_tracking_poll_server(_cll,_fullDataRequired){
    try
    {
        var getValues = "&tv=2.3.1.3&b="+lz_global_base64_url_encode(lz_session.BrowserId)+"&pc="+lz_global_base64_url_encode(++lz_poll_id);

        getValues += (lz_session.UserId != null) ? "&i="+ lz_global_base64_url_encode(lz_session.UserId) : "";

        if(lz_referrer.length>0)
            getValues += "&rf="+lz_global_base64_url_encode(lz_referrer);

        getValues = LiveZillaTracking.AddPassThruParameters(getValues);

        if(lz_data_id != null)
            getValues += "&di=" + lz_data_id;

        if(lz_poll_id<=3 || _fullDataRequired)
        {
            getValues += "&cd="+lz_global_base64_url_encode(window.screen.colorDepth)+"&rh="+lz_global_base64_url_encode(screen.height)+"&rw="+lz_global_base64_url_encode(screen.width)+"&tzo="+lz_global_base64_url_encode(lz_timezone_offset);
            if(lz_geo_resolution_needed && lz_session.GeoResolved.length == 7)
                getValues += "&geo_lat=" + lz_session.GeoResolved[0] + "&geo_long=" + lz_session.GeoResolved[1] + "&geo_region=" + lz_session.GeoResolved[2] + "&geo_city=" + lz_session.GeoResolved[3] + "&geo_tz=" + lz_session.GeoResolved[4] + "&geo_ctryiso=" + lz_session.GeoResolved[5] + "&geo_isp=" + lz_session.GeoResolved[6];
            if(lz_geo_resolution != null && lz_geo_resolution.Span > 0)getValues += "&geo_ss=" + lz_geo_resolution.Span;
        }

        var title = document.title;
        if(title.length > 60)
            title = title.substring(0,60)+"...";

        getValues += "&dc="+lz_global_base64_url_encode(title);
        getValues += "&ue="+lz_global_base64_url_encode(lz_global_base64_url_encode(window.location.href));

        if(lz_request_active != null)
            getValues += "&actreq=1";

        if(LiveZillaData.PassThruParameters.length > 0)
            getValues += "&" + lz_global_base64_decode(LiveZillaData.PassThruParameters);

        if(lz_overlay_chat_available)getValues += lz_chat_poll_parameters();

        if(lz_deactivate != null)getValues += "&deactr=" + lz_global_base64_url_encode(lz_deactivate);
        if(lz_init_floating_selector != null)
        {
            lz_stopped = false;
            getValues += "&ifs=1";
            if(lz_init_floating_selector[0]) getValues += "&ifs_oc=MQ_";
            if(lz_init_floating_selector[1]) getValues += "&ifs_opi=MQ_";
            if(lz_init_floating_selector[1]) getValues += "&ifs_pin=" + lz_init_floating_selector[2];
            if(lz_init_floating_selector[1]) getValues += "&ifs_pii=" + lz_init_floating_selector[3];
            if(lz_init_floating_selector[4]) getValues += "&ifs_opo=MQ_";
            if(lz_init_floating_selector[5]) getValues += "&ifs_ot=MQ_";
            if(lz_init_floating_selector[6]) getValues += "&ifs_okb=MQ_";
            getValues += "&ifs_osf=" + lz_init_floating_selector[7];
            getValues += "&ifs_ost=" + lz_init_floating_selector[8];
            getValues += "&ifs_osg=" + lz_init_floating_selector[9];
            if(lz_init_floating_selector[10]) getValues += "&ifs_cl1=" + lz_init_floating_selector[10];
            if(lz_init_floating_selector[11]) getValues += "&ifs_cl2=" + lz_init_floating_selector[11];
            if(lz_init_floating_selector[12]) getValues += "&ifs_cl3=" + lz_init_floating_selector[12];
            getValues += "&ifs_cht=" + lz_init_floating_selector[13];
            getValues += "&ifs_add=" + lz_global_base64_url_encode(lz_init_floating_selector[14]);
        }

        if(lz_event_fire_id != null)
        {
            getValues += "&fe=" + lz_global_base64_url_encode(lz_event_fire_id);
            lz_event_fire_id = null;
            lz_stopped = false;
        }

        if(lz_remove_att != null)
        {
            getValues += "&tra=" + lz_remove_att;
            lz_remove_att = null;
            lz_load_inputs = true;
        }

        if(lz_load_inputs != null)
        {
            getValues += "&ri=MQ_";
            lz_load_inputs = null;
        }
    }
    catch(ex)
    {
        console.log(ex);
        getValues += "&exception=" + lz_global_base64_url_encode(ex.message);
    }

	if(!lz_stopped)
	{
        lz_tracking_server_request(getValues,"livezilla_pollscript");
		clearTimeout(lz_timer);
		lz_timer = setTimeout("lz_tracking_poll_server(42274);",(lz_poll_frequency*1000));
	}
}

function lz_tracking_remove_script(_id){

    var lastScript = document.getElementById(_id);
    if(lastScript != null)
        lz_document_head.removeChild(lastScript);
}

function lz_tracking_set_sessid(_userId, _browId, _datId){
	lz_session.UserId = lz_global_base64_decode(_userId);
	lz_session.BrowserId = lz_global_base64_decode(_browId);
	lz_session.Save();
    lz_data_id = lz_global_base64_decode(_datId);
}

function lz_tracking_close_request(_id){
	if(lz_request_active != null)
	{
		lz_request_last = lz_request_active;
		lz_request_active = null;
	}

	if(lz_request_window != null)
	{
		lz_request_window.lz_livebox_close('lz_request_window');
		lz_request_window = null;
	}
	
	if(lz_overlay_chat != null)
	{
		if(typeof lz_chat_decline_request != "undefined")
			lz_chat_decline_request(_id,true,false);
	}
}

function lz_tracking_stop_tracking(_code){
	lz_stopped = true;
	lz_tracking_remove_overlay_chat();
    lz_tracking_remove_script("livezilla_pollscript");
    if(_code==1242)
    {
        window.name = '';
        location.reload();
    }
    //if(document.getElementById('warning-filter')!=null)
      //  document.getElementById('warning-filter').style.display = 'table-row';
}

function lz_tracking_geo_result(_lat,_long,_region,_city,_tz,_ctryi2,_isp){
	lz_session.GeoResolved = Array(_lat,_long,_region,_city,_tz,_ctryi2,_isp);
	lz_session.Save();
	lz_tracking_poll_server(1001);
}

function lz_tracking_set_geo_span(_timespan){
	lz_geo_resolution.SetSpan(_timespan);
}

function lz_tracking_geo_resolute(){

	if(lz_is_geo_resolution_needed())
	{
		lz_session.GeoResolutions++;
		lz_session.Save();
		lz_geo_resolution.SetStatus(1);
		if(lz_session.GeoResolutions < 2)
		{
			lz_geo_resolution.OnEndEvent = "lz_tracking_geo_result";
			lz_geo_resolution.OnSpanEvent = "lz_tracking_set_geo_span";
			lz_geo_resolution.OnTimeoutEvent = lz_tracking_geo_resolute;
			lz_geo_resolution.ResolveAsync();
		}
		else
			lz_tracking_geo_failure();
		return true;
	}
	else
	{
		lz_geo_resolution.SetStatus(7);
		return false;
	}
}

function lz_tracking_action_result(_action,_result,_closeOnClick,_parameters){
	if(_parameters == null)
		_parameters = "";

	_parameters = "&b="+lz_global_base64_url_encode(lz_session.BrowserId)+"&ue="+lz_global_base64_url_encode(lz_global_base64_url_encode(window.location.href)) + _parameters;
	_parameters += (lz_session.UserId != null) ? "&i=" + lz_global_base64_url_encode(lz_session.UserId) : "";

	if(_action=="alert")
		_parameters += "&confalert="+lz_alert_active;
	else if(_action=="overlay_box")
    {
		_parameters += "&confol="+lz_overlay_active;
        lz_overlay_last =
        lz_overlay_box = null;
    }
	else if(_action=="chat_request")
		_parameters += ((!_result) ? "&decreq="+lz_request_active : "&accreq="+lz_request_active);
	else if(_action=="website_push")
	{
		if(_result)
			_parameters += "&accwp="+lz_website_push_active;
		else
			_parameters += "&decwp="+lz_website_push_active;
		setTimeout("lz_website_push_active = null;",10000);
	}
	
	if(_closeOnClick)
	{
		_parameters += "&clreq=1";
		lz_tracking_close_request();
	}
	
	if(lz_overlay_chat_available)
		_parameters += lz_chat_poll_parameters();

    if(!lz_stopped)
	    lz_tracking_server_request(_parameters + "&" + lz_global_base64_decode(LiveZillaData.PassThruParameters),Math.random().toString());
}

function lz_tracking_add_floating_button(_pos,_sh,_shblur,_shx,_shy,_shcolor,_ml,_mt,_mr,_mb,_width,_height){
	if (lz_floating_button!=null || lz_ovlel_fsm)
		return;
	var fbdiv = document.getElementById("chat_button_image");
	lz_floating_button = new lz_livebox("lz_floating_button",fbdiv.parentNode.parentNode.innerHTML,_width,_height,_ml,_mt,_mr,_mb,_pos,0,6);
	if(_sh)
		lz_floating_button.lz_livebox_shadow(_shblur,_shx,_shy,_shcolor);
    lz_floating_button.lz_livebox_create();
	lz_floating_button.lz_livebox_show(lz_overlay_zindex+1);
}

function lz_tracking_remove_floating_button(){
    if (lz_floating_button==null)
        return;
    document.getElementById('lz_floating_button').parentNode.removeChild(document.getElementById('lz_floating_button'));
    lz_floating_button = null;
}

function lz_tracking_init_floating_button_selector(_params){
    if(lz_floating_button_selector == null && _params != null)
    {
        lz_floating_button.lz_livebox_div.className = (lz_floating_button.lz_livebox_div.className+' cwait').replace(/ cdef/g,'');
        lz_init_floating_selector = _params;
        lz_tracking_poll_server(1129);
    }
    else
    {
        lz_floating_button_selector.lz_livebox_close("lz_floating_button_selector");
        lz_init_floating_selector = lz_floating_button_selector = null;
    }
}

function lz_tracking_add_overlay_box(_olId,_html,_pos,_speed,_slide,_sh,_shblur,_shx,_shy,_shcolor,_ml,_mt,_mr,_mb,_width,_height,_bg,_bgcolor,_bgop,_br){
	if(!lz_ovlel_fsm && lz_request_window == null && lz_overlay_box == null && lz_overlays_possible && lz_overlay_last != _olId)
	{
        lz_overlay_last =
		lz_overlay_active = _olId;
		lz_overlay_box = new lz_livebox("lz_overlay_box",lz_global_base64_decode(_html),_width,_height,_ml,_mt,_mr+20,_mb,_pos,_speed,_slide);
        if(_sh)
			lz_overlay_box.lz_livebox_shadow(_shblur,_shx,_shy,'#'+_shcolor);
		if(_bg)
			lz_overlay_box.lz_livebox_background('#'+_bgcolor,_bgop);

        lz_overlay_box.lz_livebox_create();
		lz_overlay_box.lz_livebox_show(lz_overlay_zindex+3);
        lz_overlay_box.lz_livebox_div.style.borderRadius = _br + "px";

        if(_sh)
            lz_overlay_box.lz_livebox_div.style.background = "#FFFFFF";

        if(lz_fixed_mode_possible() && (_width> lz_global_get_window_width(true) || _height>lz_global_get_window_height() || lz_chat_fixed_mode))
        {
            lz_overlay_box.lz_livebox_fixed_mode = true;
            lz_overlay_box.lz_livebox_div.style.height = "auto";
            lz_overlay_box.lz_livebox_div.style.width= "auto";
            lz_overlay_box.lz_livebox_div.style.borderRadius = "0";
            lz_overlay_box.lz_livebox_div.style.position = "fixed";
            lz_overlay_box.lz_livebox_div.getElementsByTagName("div")[0].style.borderRadius="0";
            lz_overlay_box.lz_livebox_div.getElementsByTagName("div")[0].style.height="100%";
            lz_overlay_box.lz_livebox_div.getElementsByTagName("div")[0].style.width="100%";
            lz_overlay_box.lz_livebox_div.getElementsByTagName("div")[0].style.overflow="scroll";
        }
		window.focus();
	}
}

function lz_tracking_send_alert(_alertId,_text){
	if(lz_alert_active == null && lz_overlays_possible)
	{
        alert(lz_global_base64_decode(_text));
        lz_alert_active=null;
		window.focus();
	}
}

function lz_tracking_remove_buttons(){
    for (var i = 0;i<document.getElementsByTagName("a").length;i++)
        if(document.getElementsByTagName("a")[i].className=="lz_cbl")
            document.getElementsByTagName("a")[i].parentNode.removeChild(document.getElementsByTagName("a")[i]);
}

function lz_tracking_request_chat(_reqId,_text,_template,_width,_height,_ml,_mt,_mr,_mb,_position,_speed,_slide,_sh,_shblur,_shx,_shy,_shcolor,_bg,_bgcolor,_bgop){
	if(lz_overlay_box == null && lz_request_window == null && lz_overlays_possible && !lz_ovlel_fsm)
	{
		_template = (lz_global_base64_decode(_template)).replace("<!--invitation_text-->",(lz_global_base64_decode(_text)));
		lz_request_active = _reqId;
		lz_request_window = new lz_livebox("lz_request_window",_template,_width,_height,_ml,_mt,_mr,_mb,_position,_speed,_slide);
	
		if(_sh)
			lz_request_window.lz_livebox_shadow(_shblur,_shx,_shy,'#'+_shcolor);
		if(_bg)
			lz_request_window.lz_livebox_background('#'+_bgcolor,_bgop);

	 	if(lz_request_last != _reqId)
		{
            lz_request_window.lz_livebox_create();
			lz_request_window.lz_livebox_show(lz_overlay_zindex+4);
			window.focus();
		}
	}
}

function lz_tracking_add_overlay_chat_v2(_template,_text,_width,_height,_ml,_mt,_mr,_mb,_position,_br){

    if(typeof lz_ovlel_classic != 'undefined')
        OverlayChatWidgetV2.__ModeClassic = !lz_ovlel_fsm && !lz_is_mobile;

    if(typeof lz_ovlel_api != 'undefined')
        OverlayChatWidgetV2.__ModeAPI = true;

    if(lz_ovlel.length==1 && !OverlayChatWidgetV2.__ModeAPI)
        return;

	lz_header_text = lz_global_base64_decode(_text);
	if(lz_overlay_chat == null && lz_overlays_possible)
	{
        if(lz_chat_get_wm_element('phone') != null)
            if(lz_chat_get_wm_element('phone').inbound != false && lz_chat_get_wm_element('phone').outbound != false)
                if(_height < 720)
                    _height = 720;

        var heightDistance = 20;

        OverlayChatWidgetV2.__ModeSingle =
            OverlayChatWidgetV2.__ModeClassic ||
            (lz_ovlel.length==2) ||
            (lz_ovlel.length==3 && lz_chat_get_wm_element('chat') != null && lz_chat_get_wm_element('ticket') != null && !lz_ticket_when_online);

        if(OverlayChatWidgetV2.__ModeSingle)
            heightDistance = 80;

        if(lz_global_get_window_width()>0)
        {
            _height = Math.min(_height,lz_global_get_window_height()-(_mb+heightDistance));
            _width = Math.min(_width,lz_global_get_window_width());
        }

        lz_overlay_chat_height = _height;
        lz_overlay_chat_width =_width;

		_template = (lz_global_base64_decode(_template)).replace("<!--text-->",lz_header_text);
        _template = _template.replace("<!--lz_chat_switch_so-->",lz_get_icon('lz_chat_switch_so','toggle-off','',' lz_chat_overlay_toggle_icon'));
        _template = _template.replace("<!--lz_chat_switch_tr-->",lz_get_icon('lz_chat_switch_tr','toggle-off','',' lz_chat_overlay_toggle_icon'));
        _template = _template.replace("<!--lz_chat_switch_et-->",lz_get_icon('lz_chat_switch_et','toggle-off','',' lz_chat_overlay_toggle_icon'));
        _template = _template.replace("<!--lz_chat_fb-->",lz_get_icon('lz_chat_feedback_init','thumbs-o-up','OverlayChatWidgetV2.ToggleFeedback(true);',''));
        _template = _template.replace("<!--lz_chat_ob-->",lz_get_icon('lz_chat_options_button','ellipsis-h','lz_chat_switch_options_table();lz_stop_propagation(evt);',''));
        _template = _template.replace("<!--lz_chat_min-->",lz_get_icon('lz_chat_overlay_minimize','times-circle','OverlayChatWidgetV2.SetMode(null,true);',''));
        _template = _template.replace("<!--lz_kb_mi-->",lz_get_icon('lz_chat_kb_match_close','times-circle','lz_chat_kb_deactivate(true,false);',''));
        _template = _template.replace("<!--lz_chat_co-->",lz_get_icon('lz_chat_overlay_options_close','times-circle','lz_chat_switch_options(lz_chat_option_function,false);',''));
        _template = _template.replace(/<!--lz_chat_req-->/g,lz_get_icon('','info-circle','',''));
        _template = _template.replace(/<!--lz_chat_fp-->/g,lz_get_icon('','plus-square','',' lz_chat_fill'));
        _template = _template.replace("<!--lz_chat_po-->",lz_get_icon('lz_chat_apo_icon','expand','OverlayChatWidgetV2.Pop();',''));
        _template = _template.replace(/<!--brt-->/g,_br);
        _template = _template.replace(/<!--brb-->/g,(OverlayChatWidgetV2.__ModeClassic && _mb==0) ? 0 : _br);
        _template = _template.replace("<!--lz_chat_button_send-->",lz_get_icon('lz_chat_text_send','send','lz_chat_message(null,null);lz_chat_set_focus();',''));
        _template = _template.replace("<!--lz_chat_button_file-->",lz_get_icon('lz_chat_text_file','clip','lz_chat_switch_options(\'fu\');',''));
        var ovlmr = _mr + 2;
        var ovlmb = _mb + 6;

        if(OverlayChatWidgetV2.__ModeClassic)
        {
            ovlmr = _mr;
            ovlmb = _mb;
        }
        else if(OverlayChatWidgetV2.__ModeAPI)
            ovlmr += 0;
        else if(OverlayChatWidgetV2.__ModeTextInline && !OverlayChatWidgetV2.__ModeSingle)
            ovlmr += ((!lz_is_mobile) ? OverlayChatWidgetV2.TextInlineButtonWidth : OverlayChatWidgetV2.TextInlineButtonWidthMobile) + 15;
        else if(!OverlayChatWidgetV2.__ModeSingle)
            ovlmr += OverlayChatWidgetV2.__SizeWidth + 15;
        else
            ovlmb += OverlayChatWidgetV2.__SizeHeight + Math.floor(17*OverlayChatWidgetV2.__Ratio);

        var tm = (lz_ovlel_fsm) ? ((OverlayChatWidgetV2.__ModeSingle) ? 0 : 50) : 0;

		lz_overlay_chat = new lz_livebox_v2("lz_overlay_chat",_template,lz_overlay_chat_width,_height,_ml,tm,ovlmr,ovlmb,_position,(lz_ovlel_fsm) ? document.getElementById('lz_chat_fs_body') : document.body);
        lz_overlay_chat.m_AutoScaleMode = false;

        if(lz_ovlel_fsm)
        {
            document.getElementById('lz_chat_apo').style.display='none';
            document.getElementById('lz_chat_overlay_main').style.border=0;
            document.getElementById('lz_chat_overlay_main').style.boxShadow='none';
            document.getElementById('lz_chat_feedback_init').style.right='10px';
            document.getElementById('lz_chat_feedback_init').style.left='auto';

            if(lz_d(lz_data) && lz_d(lz_data.header))
                document.getElementById('lz_chat_fs_logo_left').firstChild.src=lz_data.header;
            else
                document.getElementById('lz_chat_fs_logo_left').firstChild.src=lz_comp_logo;

            lz_overlay_chat.SetFullscreenMode(true);
            lz_session.OVLWMState ='1';
        }
        else if(lz_is_mobile)
        {
            lz_session.OVLWMState='0';
            lz_session.OVLWMSElem = '';
        }
        else if(OverlayChatWidgetV2.__ModeClassic)
        {
            var ccntr = document.createElement('div');
            ccntr.id='livezilla_c_chat';
            ccntr.className = 'lz_overlay_c';
            ccntr.style.display = 'none';
            document.getElementById("lz_chat_overlay_main").appendChild(ccntr);
        }

        if(!lz_is_mobile || lz_overlay_chat.m_FullScreenMode)
        {
            if(lz_overlay_chat.m_FullScreenMode || (lz_session.OVLWMState=='1' && lz_session.OVLWMSElem != 'wm' && lz_session.OVLWMSElem.length))
                lz_overlay_chat.SetVisible(true);
            else if(OverlayChatWidgetV2.__ModeClassic && !OverlayChatWidgetV2.__ModeAPI)
            {
                lz_overlay_chat.SetVisible(true);
                OverlayChatWidgetV2.Minimize();
            }
        }

        lz_overlay_chat.m_FrameElement.style.zIndex = (OverlayChatWidgetV2.__ModeSingle) ?  lz_overlay_zindex+1 : lz_overlay_zindex;
        document.getElementById('lz_chat_data_form').style.zIndex = '+1000';
        document.getElementById('lz_chat_overlay_options_box_bg').style.zIndex = '+2000';
        document.getElementById('lz_chat_options_table').style.zIndex = '+3000';
        document.getElementById('lz_chat_overlay_options_frame').style.zIndex = '+4000';
        document.getElementById('lz_chat_overlay_options_close').getElementsByTagName('path')[0].setAttribute('d',lz_get_icon_path('times-circle'));
        document.getElementById('lz_chat_overlay_minimize').getElementsByTagName('path')[0].setAttribute('d',lz_get_icon_path('times-circle'));
        lz_overlay_chat.m_FrameElement.style.overflow = "visible";
    }
}

function lz_tracking_add_welcome_manager(_template,_ml,_mt,_mr,_mb,_chatAvailable){

    if(OverlayChatWidgetV2.__ModeClassic)
        return;

    if(lz_is_mobile)
    {
        _mb = Math.max(40,_mb);
        _mr = Math.max(40,_mr);
    }

    _template = lz_global_base64_decode(_template);
    _template = _template.replace(/<!--scale-->/g,OverlayChatWidgetV2.__Scale);
    _template = _template.replace("<!--border-->",OverlayChatWidgetV2.__BorderWidth);
    _template = _template.replace(/<!--size-->/g,OverlayChatWidgetV2.__SizeHeight);
    _template = _template.replace("<!--posx-->",-12*OverlayChatWidgetV2.__Ratio);
    _template = _template.replace("<!--posy-->",-11*OverlayChatWidgetV2.__Ratio);
    lz_chat_add_wm_elems(_template,_ml,_mt,_mr,_mb,_chatAvailable);
    if(lz_overlay_wm != null)
    {
        OverlayChatWidgetV2.UpdateWMUI();
        lz_overlay_wm.UpdateUI();
        lz_chat_update_css();
    }
    if(OverlayChatWidgetV2.__ModeAPI)
        lz_overlay_wm.SetVisible(false);

}

function lz_tracking_add_eye_catcher_v2(_template,_opName){

    if(!OverlayChatWidgetV2.__ModeAPI && lz_ovlec != null && !lz_ovlel_fsm && lz_eye_catcher == null && lz_overlay_chat != null && lz_session.ECH != "1")
    {
        var lz_avatar = 90;
        if(_opName=='' || !lz_d(lz_ovlec.ec_p))
            lz_avatar = 0;

        if(lz_is_mobile && !lz_d(lz_ovlec.ec_som))
            return;

        if(OverlayChatWidgetV2.IsChatAvailable() && lz_d(lz_ovlec.ec_dson))
            return;

        if(!OverlayChatWidgetV2.IsChatAvailable() && lz_d(lz_ovlec.ec_dsof))
            return;

        OverlayChatWidgetV2.EyeCatcherWidth = lz_ovlec.ec_w;
        if(OverlayChatWidgetV2.__ModeClassic)
        {
            if(!(lz_overlay_chat != null && lz_overlay_chat.IsVisible()))
                return;

            if(lz_session.OVLWMState=='1')
                OverlayChatWidgetV2.Maximize();
            else
                OverlayChatWidgetV2.Minimize();
        }

        _template = lz_global_base64_decode(_template);
        _template = _template.replace(/<!--width-->/g,lz_ovlec.ec_w);
        _template = _template.replace(/<!--height-->/g,lz_ovlec.ec_h);
        _template = _template.replace(/<!--header_color-->/g,lz_ovlec.ec_ht_c);
        _template = _template.replace(/<!--sub_header_color-->/g,lz_ovlec.ec_st_c);
        _template = _template.replace(/<!--avatar_border_c-->/g,lz_ovlec.ec_a_bc);
        _template = _template.replace(/<!--avatar_border_w-->/g,lz_ovlec.ec_a_bw);
        _template = _template.replace(/<!--left_margin-->/g,((lz_avatar > 0) ? lz_avatar : 15)+20);
        _template = _template.replace(/<!--header_text-->/g,'');
        _template = _template.replace(/<!--sub_header_text-->/g,'');
        _template = _template.replace(/<!--operator_name-->/g,lz_global_base64_decode(_opName));
        _template = _template.replace('<!--close_icon-->',lz_get_icon('lz_overlay_eyecatcher_close','times-circle','lz_tracking_remove_eye_catcher();lz_stop_propagation(evt);',''));

        var btm = Math.floor(lz_ovlec.ec_h*0.11);

        if(lz_ovlec.ec_h < 140)
            _template = _template.replace(/<!--sh_adds-->/g, 'white-space:nowrap;text-overflow:ellipsis;' );
        else
            btm = Math.floor(lz_ovlec.ec_h*0.3);

        _template = _template.replace(/<!--border-->/g,Math.floor((lz_ovlec.ec_h - btm - 100) / 2));
        _template = _template.replace(/<!--header_padding-->/g,Math.floor((lz_ovlec.ec_h - btm - 54) / 2));
        _template = _template.replace(/<!--header_sub_padding-->/g,Math.floor((lz_ovlec.ec_h - btm) / 2));

        lz_eye_catcher = new lz_livebox_v2("lz_eye_catcher",_template,lz_ovlec.ec_w,lz_ovlec.ec_h,0,0,lz_ovlec.ec_m[1],lz_ovlec.ec_m[2],'22');
        lz_eye_catcher.m_FrameElement.style.zIndex = lz_overlay_zindex + 10;

        document.getElementById("lz_overlay_eyecatcher_close").style.fill = lz_ovlec.ec_ht_c;
        lz_tracking_cbubble(document.getElementById("lz_overlay_eyecatcher_bubble").getContext("2d"), lz_avatar);

        if(lz_session.OVLWMState == '0')
        {
            lz_eye_catcher.m_FrameElement.style.opacity=0;
            lz_eye_catcher.SetVisible(true);
            setTimeout(lz_fade_in_eye_catcher,lz_d(lz_ovlec.ec_fi) ? lz_ovlec.ec_fi*1000 : 10);
        }
        else
            lz_eye_catcher.m_FrameElement.style.opacity=1;

        if(lz_d(lz_ovlec.ec_fo))
            setTimeout("lz_tracking_remove_eye_catcher();",lz_ovlec.ec_fo*1000);

        document.getElementById('lz_overlay_eyecatcher_avatar').style.left = (12+lz_ovlec.ec_bw)+'px';
        OverlayChatWidgetV2.UpdateEyeCatcherContent();
    }
}

function lz_fade_in_eye_catcher(){
    if(lz_session.OVLWMState == '0')
        lz_fade_in(document.getElementById('lz_eye_catcher'),55);
    setTimeout(function(){
        if(lz_session.OVLWMState != '0')
            lz_eye_catcher.SetVisible(false);
    },lz_d(lz_ovlec.ec_fi) ? lz_ovlec.ec_fi*1000 : 800);
}

function lz_tracking_add_eye_catcher_image(_template,_width,_height,_fi,_fo,_mr,_mb){
    try
    {
        if(!lz_ovlel_fsm && !lz_is_mobile && lz_eye_catcher == null && lz_overlay_chat != null && lz_session.ECH != "1")
        {
            _template = lz_global_base64_decode(_template);
            OverlayChatWidgetV2.EyeCatcherWidth = _width;
            lz_eye_catcher = new lz_livebox_v2("lz_eye_catcher",_template,_width,_height,0,0,_mr,_mb,'22');

            if(lz_session.OVLWMState=='0')
            {
                lz_eye_catcher.m_FrameElement.style.opacity=0;
                lz_eye_catcher.SetVisible(false);

                if(_fi > 0)
                    setTimeout("if(lz_session.OVLWMState == '0'){lz_eye_catcher.SetVisible(true);lz_fade_in(document.getElementById('lz_eye_catcher'),55);}",_fi*1000);
                else
                    setTimeout("if(lz_session.OVLWMState == '0'){lz_eye_catcher.SetVisible(true);lz_fade_in(document.getElementById('lz_eye_catcher'),55);}",10);

                if(_fo > 0)
                    setTimeout("lz_tracking_remove_eye_catcher();",_fo*1000);
            }
            else
            {
                lz_eye_catcher.m_FrameElement.style.opacity=1;
                lz_eye_catcher.SetVisible(false);
            }
        }
    }
    catch(ex)
    {
        console.log(ex);
    }
}

function lz_tracking_cbubble(_ctx, _av){
    try
    {
        var _x = 1,
            _y = 5,
            _shx = lz_d(lz_ovlec.ec_shx) ? lz_ovlec.ec_shx : 0,
            _w = (lz_ovlec.ec_w - _shx - 3),
            _h = lz_ovlec.ec_h - 25,
            _r = lz_ovlec.ec_br,
            _shb = lz_d(lz_ovlec.ec_shb) ? lz_ovlec.ec_shb : 0,
            _shy = lz_d(lz_ovlec.ec_shy) ? lz_ovlec.ec_shy : 0,
            _shc = lz_d(lz_ovlec.ec_shc) ? lz_ovlec.ec_shc : '#000',
            _sgs= lz_d(lz_ovlec.ec_bcs) ? lz_ovlec.ec_bcs : 0,
            _sge = lz_d(lz_ovlec.ec_bce) ? lz_ovlec.ec_bce : 0,
            _sglw= lz_d(lz_ovlec.ec_bw) ? lz_ovlec.ec_bw : 0,
            _fgs = lz_d(lz_ovlec.ec_bgcs) ? lz_ovlec.ec_bgcs : 0,
            _fge = lz_d(lz_ovlec.ec_bgce) ? lz_ovlec.ec_bgce : 0,
            _avbgc = lz_d(lz_ovlec.ec_a_bgc) ? lz_ovlec.ec_a_bgc : 0;

        function createFullPath(){
            _ctx.beginPath();
            _ctx.moveTo(_x + _r, _y);
            _ctx.lineTo(_x + _w - _r, _y);
            _ctx.quadraticCurveTo(_x + _w, _y, _x + _w, _y + _r);
            _ctx.lineTo(_x + _w, _y + _h - _r);
            _ctx.quadraticCurveTo(_x + _w, _y + _h, _x + _w - _r, _y + _h);
            _ctx.lineTo(m+_x+60 + 10, _y + _h);
            _ctx.lineTo(m+_x+45 + 10, _y + _h+10);
            _ctx.lineTo(m+_x+30 + 10, _y + _h);
            _ctx.lineTo(_x + _r, _y + _h);
            _ctx.quadraticCurveTo(_x, _y + _h, _x, _y + _h - _r);
            _ctx.lineTo(_x, _y + _r);
            _ctx.quadraticCurveTo(_x, _y, _x + _r, _y);
            _ctx.closePath();
            _ctx.save();
        }

        function createAvatarPath(){
            var avbgw = _av;
            var avh = _h;
            var avx = _x;
            var avy = _y;
            var avr = _r-1;
            _ctx.beginPath();
            _ctx.moveTo(avx + avr, avy);
            _ctx.lineTo(avx + avbgw, avy);
            _ctx.lineTo(avx + avbgw, avy + avh);
            _ctx.lineTo(avx + avr, avy + avh);
            _ctx.quadraticCurveTo(avx, avy + avh, avx, avy + avh - avr);
            _ctx.lineTo(avx, avy + avr);
            _ctx.quadraticCurveTo(avx, avy, avx + avr, avy);
            _ctx.closePath();
            _ctx.save();
        }

        _x+=_sglw;
        _w-=_sglw;

        var m = _w-90;

        createFullPath();

        var grdfill=_ctx.createLinearGradient(_x,_y,0,_h);
        grdfill.addColorStop(0,_fgs);
        grdfill.addColorStop(1,_fge);

        _ctx.fillStyle = grdfill;

        if(_shc != '')
        {
            _ctx.shadowColor = _shc;
            _ctx.shadowBlur = _shb;
            _ctx.shadowOffsetX = _shx;
            _ctx.shadowOffsetY = _shy;
        }

        _ctx.fill();
        _ctx.shadowBlur = 0;
        _ctx.shadowOffsetX = 0;
        _ctx.shadowOffsetY = 0;

        if(_av > 0)
        {
            createAvatarPath();
            _ctx.fillStyle = _avbgc;
            _ctx.fill();
        }

        if(_sglw>0)
        {
            createFullPath();
            var grdstroke=_ctx.createLinearGradient(_x,_y,0,_h);
            grdstroke.addColorStop(0,_sgs);
            grdstroke.addColorStop(1,_sge);
            _ctx.strokeStyle = grdstroke;
            _ctx.lineWidth = _sglw;
            _ctx.stroke();
        }
    }
    catch(ex)
    {
        console.log(ex);
    }
}

function lz_tracking_remove_eye_catcher(){
    if(lz_session != null && document.getElementById("lz_overlay_eyecatcher") != null)
    {
        lz_session.ECH = 1;
        lz_session.Save();
        lz_fade_out(document.getElementById('lz_eye_catcher'),25);
    }
}

function lz_tracking_remove_overlay_chat(){

	if(lz_overlay_chat != null)
	{
		clearTimeout(lz_chat_invite_timer);
		clearTimeout(lz_chat_waiting_posts_timer);
		lz_overlay_chat = null;
        var rmblobj = ['lz_overlay_wm','livezilla_wm','lz_overlay_chat','lz_chat_fs_header','lz_chat_fs_body'];
        for(var k in rmblobj)
            if(document.getElementById(rmblobj[k]) != null)
                document.getElementById(rmblobj[k]).remove();
	}
    lz_tracking_remove_eye_catcher();
}

function lz_tracking_geo_failure(){
	lz_tracking_set_geo_span(lz_geo_error_span);
	lz_geo_resolution.SetStatus(4);
	lz_session.GeoResolved = ['LTUyMg==','LTUyMg==','','','','',''];
	lz_session.Save();
	lz_tracking_poll_server(1002);
}

function lz_tracking_init_external_window(_name,_intid,_groupid){

    var _parameters = '';

    if(_parameters.indexOf('&ptn=') == -1 && _name != '')
        _parameters += "&ptn=" + encodeURIComponent(_name);

    if(_parameters.indexOf('&operator=') == -1 && _intid != '')
        _parameters += '&operator='+ encodeURIComponent(_intid);

    if(_parameters.indexOf('&group=') == -1 && _groupid != '')
        _parameters += '&hg=Pw__&group=' + encodeURIComponent(_groupid);

    _parameters += '&nct=MQ__&hfk=MQ__';
    _parameters = LiveZillaTracking.AddPassThruParameters(_parameters);

    void(window.open(lz_poll_server + lz_poll_file_chat + '?a=MQ__' + _parameters.replace('&&','&'),'LiveZilla','width='+lz_window_width+',height='+lz_window_height+',left=50,top=50,resizable=yes,menubar=no,location=no,status=yes,slidebars=no'));
}

function lz_tracking_deactivate(_confirm,_days){
    lz_deactivate = _days;
    lz_tracking_poll_server(1214);
    alert(_confirm);
}

function lz_tracking_set_widget_visibility(_visible){
    if(lz_session.OVLCState != '0' && !_visible)
        return;

    if(lz_eye_catcher != null && lz_overlay_chat != null)
    {
        if(_visible && !lz_eye_catcher.lz_livebox_shown)
            lz_eye_catcher.lz_livebox_show(lz_overlay_zindex);
        document.getElementById('lz_eye_catcher').style.display = (_visible && lz_session.ECH != 1) ? 'block' : 'none';
    }

    if(lz_overlay_chat != null)
    {
        if(_visible && !lz_overlay_chat.lz_livebox_shown)
        {
            lz_overlay_chat.lz_livebox_show(lz_overlay_zindex);

            if(lz_session.OVLCState == "1")
                lz_chat_change_state(false,true);
            lz_chat_set_init();
            lz_chat_update_css();
        }
        document.getElementById('lz_overlay_chat').style.display = (_visible) ? '' : 'none';
    }
}

function lz_tracking_add_tag(_html){
    var container = document.createElement("div");
    container.innerHTML = lz_global_base64_decode(_html);
    document.body.appendChild(container);
    var arr = container.getElementsByTagName('script');
    for (var n = 0; n < arr.length; n++)
    {
        if(arr[n].innerHTML!="")
            eval(arr[n].innerHTML);
        if(arr[n].src!=null)
        {
            var newScript = document.createElement("script");
            newScript.src = arr[n].src;
            newScript.async = true;
            lz_document_head.appendChild(newScript);
        }
    }
}

function lz_event_fire(_id){
    lz_event_fire_id = _id;
    lz_tracking_poll_server(1006);
}

function lz_fixed_mode_possible(){
    return (lz_is_mobile || lz_ovlel_fsm) && !lz_is_ie;
}