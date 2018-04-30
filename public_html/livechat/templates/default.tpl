<div id="livezilla_tracking" style="display:none;"></div>
<script id="lz_r_scr" type="text/javascript">
    var script = document.createElement("script");script.async=true;
    script.type="text/javascript";
    var src = "./server.php?rqst=track&output=jcrpt&ovlts=MA__&nse="+Math.random();
    src += '<!--params-->';
    src += '&ovlv=djI_';
    src += '&ovlapo=MQ__';
    src += '&ovlc=MQ__';
    src += '&ovltwo=MQ__';
    src += '&ovlmr=NDA_';
    src += '&ovlbr=NQ__';

    var lz_ovlel_fsm = <!--fs-->;
    var lz_ovlel_text_inline = true;
    var lz_ovlel = [];

    lz_ovlel.push({type:'wm',icon:'commenting'});
    if(<!--f_chat-->)
        lz_ovlel.push({type:'chat',icon:'comments',counter:true});
    if(<!--f_ticket-->)
        lz_ovlel.push({type:'ticket',icon:'envelope'});
    if(<!--f_kb-->)
        lz_ovlel.push({type:'knowledgebase',icon:'lightbulb-o',counter:true});
    if(<!--f_phone-->)
        lz_ovlel.push({type:'phone',icon:'phone',inbound:false,outbound:true});
    /*
    var lz_ovlec = {
        ec_br:5,
        ec_bgce:'#fbfbfb',
        ec_bgcs:'#fff',
        ec_bw:0,
        ec_bcs:'#6EA30C',
        ec_bce:'#6EA30C',
        ec_shx:1,
        ec_shy:1,
        ec_shb:3,
        ec_shc:'#222',
        ec_m:[0,28,78,0],
        ec_ht_c:'#666',
        ec_st_c:'#777',
        ec_p:true,
        ec_a_bc:'#fff',
        ec_a_bw:2,
        ec_a_bgc:'#4989e1',
        ec_w:300,
        ec_h:120
    };
    */
    <!-- PASS THRU DATA -->
    var lz_data = {
        <!--ptdata-->
    };
    <!-- PASS THRU DATA -->
    script.src=src;document.getElementById('livezilla_tracking').appendChild(script);

</script>