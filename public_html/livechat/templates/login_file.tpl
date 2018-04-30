<input type="hidden" id="lz_form_active_<!--name-->" value="<!--active-->">
<table id="lz_form_<!--name-->" class="lz_input">
	<tr>
		<td>
            <div class="lz_form_file_box">
                <span id="lz_form_caption_<!--name-->">&nbsp;&nbsp;<!--caption_plain--></span>
                <span class="lz_form_file">
                    <input type="file" id="<!--id-->" name="form_<!--name-->" onchange="if(this.files.length>0){lz_chat_save_input_value('<!--name-->',this.files[0]);}else{lz_chat_save_input_value('<!--name-->',null);}">
                </span>
            </div>
        </td>
        <td class="lz_form_icon">

            <div class="lz_form_info_box" id="lz_form_info_<!--name-->"><!--info_text--></div>
            <div id="lz_form_mandatory_<!--name-->" style="display:none;"><!--lz_chat_req--></div>

        </td>
	</tr>
</table>