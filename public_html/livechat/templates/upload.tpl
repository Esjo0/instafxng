<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>LiveZilla</title>
    <meta name="description" content="LiveZilla Knowledgebase">
    <link rel="stylesheet" type="text/css" href="./templates/style_chat.min.css">
</head>
<body style="padding:10px 20px;margin:0;text-align:center;overflow:hidden;">
<script>
    window.onbeforeunload = lz_chat_file_before_unload;
    <!--connector_script-->

    var lzconnector = null;
    var m_Permission = false;
    var m_Aborted = false;
    var m_Started = false;

    function lz_chat_file_init_upload(){
        if(!m_Started)
        {
            if(document.getElementById('lz_chat_file_file').value == "")
            {
                return;
            }

            m_Aborted = false;
            m_Started = true;
        }
        else if(!m_Aborted)
        {
            lz_chat_file_stop();
        }
        lz_chat_file_update_ui(m_Started && !m_Aborted);
        lz_chat_request_update_status();
    }

    function lz_chat_file_stop(){
        m_Aborted = true;

        lz_chat_request_update_status();

        m_Permission =
        m_Started = false;
        document.getElementById('lz_chat_file_name').innerHTML = "<br><br>";
        document.getElementById('lz_chat_file_file').value = "";
    }

    function lz_chat_file_update_ui(_active){
        document.getElementById('lz_chat_file_status').innerHTML = "";
        document.getElementById('lz_chat_file_error').style.display =
        document.getElementById('lz_chat_file_success').style.display = "none";
        document.getElementById('lz_chat_file_load').style.display = (_active) ? "block" : "none";
        document.getElementById('lz_chat_file_file').style.display = (!_active) ? "" : "none";
        document.getElementById('lz_chat_file_send').value = (_active) ? "<!--lang_client_abort-->" : "<!--lang_client_send-->";

        if(!_active)
            document.getElementById('lz_chat_file_file').value="";
    }

    function lz_chat_request_update_status(){
        if(!m_Permission && m_Started)
        {
            var params = "p_iu=MQ_&cid=<!--chat_id-->&bid=<!--bid-->&uid=<!--uid-->&find=<!--find-->";
                params += "&p_fu_n=" + document.getElementById('lz_chat_file_file').value;

            if(m_Aborted)
                params += "&p_fu_a=MQ__";

            lzconnector = new lz_connector("./upload.php",params,4000);
            lzconnector.OnEndEvent = lz_chat_handle_response;
            lzconnector.ConnectAsync();
        }
    }

    function lz_chat_handle_response(_status,_response){
        if(_response != null)
            eval(_response);

        if(!m_Permission && !m_Aborted && m_Started)
            setTimeout("lz_chat_request_update_status()",4000);
    }

    function lz_chat_file_ready(){
        if(m_Started)
        {
            m_Permission = m_Started = m_Aborted = false;
            lz_chat_file_update_ui(false);
            document.getElementById('lz_chat_file_success').style.display = "block";
            document.getElementById('lz_chat_file_name').innerHTML = "<br><br>";
        }
    }

    function lz_chat_file_start_upload(){
        if(!m_Aborted)
        {
            m_Permission = true;
            var fd = new FormData(lz_file_form);
            lzconnector	= new lz_connector(lz_file_form.action,fd,-1);
            lzconnector.OnEndEvent = lz_chat_handle_response;
            lzconnector.OnProgressEvent = lz_chat_file_progress;
            lzconnector.ConnectAsync();
        }
    }

    function lz_chat_file_progress(evt){
        if (m_Started && evt.lengthComputable)
        {
            var percentComplete = Math.round(evt.loaded * 100 / evt.total);
            document.getElementById('lz_chat_file_send').value = "<!--lang_client_abort--> (" + percentComplete + "%)";
        }
    }

    function lz_chat_file_error(_value){
        lz_chat_file_stop();
        lz_chat_file_update_ui(false);

        if(_value == 1)
            document.getElementById('lz_chat_file_status').innerHTML = "<!--lang_client_file_request_rejected-->";
        else if(_value == 2)
            document.getElementById('lz_chat_file_status').innerHTML = "<!--lang_client_file_upload_oversized-->";

        document.getElementById('lz_chat_file_error').style.display = "block";
    }

    function lz_chat_file_before_unload(){
        if(m_Started && !m_Aborted)
        {
            return "<!--lang_client_really_close-->\r\n<!--lang_client_file_upload_abort-->";
        }
    }

</script>
<!--upload-->
<script>
    document.getElementById("lz_chat_file_file").addEventListener( 'change', function( e )
    {
        var fileName = '';
        if( this.files && this.files.length > 1 )
            fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
        else
            fileName = e.target.value.split( '\\' ).pop();

        document.getElementById('lz_chat_file_success').style.display = "none";
        document.getElementById('lz_chat_file_error').style.display = "none";

        if(fileName)
            document.getElementById("lz_chat_file_name").innerHTML = fileName;
        else
            document.getElementById("lz_chat_file_name").innerHTML = "<br><br>";
    });
</script>
</body>
</html>