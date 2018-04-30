<!DOCTYPE html>
<head>
	<META NAME="robots" CONTENT="noindex,follow">
	<title><!--config_gl_site_name--></title>
    <link rel="stylesheet" type="text/css" href="./templates/style_knowledgebase.min.css">
    <!--custom_css-->
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico">
    <script type="text/javascript" src="./templates/jscriptv24/kb.min.js"></script>
    <script type="text/javascript" src="./templates/jscriptv24/icons.min.js"></script>
    <script type="text/javascript" src="./templates/jscriptv24/jsglobal.min.js"></script>
</head>
<body onload="init();">
    <div id="lz_kb_main">
        <div id="lz_kb_header" class="lz_kb_center"><!--logo--></div>
        <div id="lz_kb_h1"></div>
        <div id="lz_kb_h2">
            <div id="lz_kb_search_box">
                <form id="lz_kb_search_form" action="./knowledgebase.php" method="GET">
                    <input id="lz_kb_input" name="search-for" type="text" class="lz_form_box" value="<!--query-->" placeholder="<!--lang_client_kb_search_placeholder-->" onkeyup="if(event.keyCode==13){lz_kb.Search();}else if(event.keyCode==8 && document.getElementById('lz_kb_input').value==''){lz_kb.ResetSearch();}">
                    <input id="lz_kb_search" type="button" value="<!--lang_client_search-->" class="lz_form_button lz_chat_unselectable" onclick="lz_kb.Search();">
                    <!--params-->
                </form>
            </div>
        </div>
        <div id="lz_kb_results" class="lz_kb_center"><!--navigation--><!--results--></div>
    </div>
    <br><br>
    <script>
        var lz_kb = null;
        function init(){
            lz_kb = new KBClass();
        }

        var rhtml = document.getElementById('lz_kb_results').innerHTML;
        rhtml = rhtml.replace('<!--icon_yes-->',lz_get_icon('','thumbs-o-up','',''));
        rhtml = rhtml.replace('<!--icon_no-->',lz_get_icon('','thumbs-o-down','',''));
        document.getElementById('lz_kb_results').innerHTML = rhtml;

        if(document.getElementById('lz_kb_date') !== null)
            document.getElementById('lz_kb_date').innerHTML = lz_chat_get_locale_date(document.getElementById('lz_kb_date').innerHTML);

    </script>
</body>
</html>