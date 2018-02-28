<div id="<!--id-->">
    <div class="lz_overlay_chat_message lz_overlay_chat_message_full">
        <div class="lz_overlay_chat_message_list">
            <div class="lz_overlay_chat_message_element lz_overlay_chat_message_avatar lz_overlay_br_two">
                <div style="width:80px;height:60px;margin-right:10px;background-image: url('<!--server-->picture.php?operator=<!--sender_id-->');"></div>
            </div>
            <div class="lz_overlay_chat_message_element">
                <span class="lz_overlay_chat_message_time"><!--time--></span>
                <div class="lz_overlay_chat_message_name"><span><!--name--></span></div>
                <div class="lz_overlay_chat_message_text"><!--message--></div>
            </div>
        </div>
    </div>
    <div class="lz_overlay_chat_message lz_overlay_chat_message_add">
        <div class="lz_overlay_chat_message_list">
            <div class="lz_overlay_chat_message_element lz_overlay_chat_message_avatar"></div>
            <div class="lz_overlay_chat_message_element">
                <div class="lz_overlay_chat_message_text" style="text-align:right;">
                    <input type="hidden" id="lz_chat_invite_id" value="<!--id-->">
                    <a href="#" onclick="lz_chat_decline_request('<!--id-->',false,true,false,true);this.blur();" class="lz_decline_link lz_decline_link_frame">
                        <svg style="width:20px;" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path style="transform:scale(.01) !important;" transform="scale(.01)" d="M1277 1122q0-26-19-45l-181-181 181-181q19-19 19-45 0-27-19-46l-90-90q-19-19-46-19-26 0-45 19l-181 181-181-181q-19-19-45-19-27 0-46 19l-90 90q-19 19-19 46 0 26 19 45l181 181-181 181q-19 19-19 45 0 27 19 46l90 90q19 19 46 19 26 0 45-19l181-181 181 181q19 19 45 19 27 0 46-19l90-90q19-19 19-46zm387-226q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg>
                        <!--lang_client_decline_invite-->
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
