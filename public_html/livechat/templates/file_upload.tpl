<form action="./upload.php?f=MQ__" method="post" enctype="multipart/form-data" name="lz_file_form">
    <label class="lz_chat_file_label">
        <input type="file" id="lz_chat_file_file" name="form_userfile" style="<!--mwidth-->" class="lz_chat_unselectable">
        <span><!--lang_client_file_upload_select_file--></span>
    </label>
    <br><br>
    <div id="lz_chat_file_name"><br><br></div>
    <div id="lz_chat_file_load" class="lz_anim_loading"></div>
    <img id="lz_chat_file_success" class="lz_chat_file_upload_icon" src="./images/icon_file_upload_success.png">
    <img id="lz_chat_file_error" class="lz_chat_file_upload_icon" src="./images/icon_file_upload_error.gif">
    <div id="lz_chat_file_status"></div>

    <input type="hidden" name="p_request" value="extern">
    <input type="hidden" name="p_action" value="file_upload">
    <input type="hidden" value="<!--cid-->" name="cid">
    <input type="hidden" value="<!--bid-->" name="bid">
    <input type="hidden" value="<!--uid-->" name="uid">
    <input type="hidden" value="<!--find-->" name="find">
</form>
<div id="lz_chat_file_send" class="lz_form_button" style="background:<!--bgc-->;" onclick="<!--action-->"><!--lang_client_upload--></div>