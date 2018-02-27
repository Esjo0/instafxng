<!-- Header Section: Logo and Live Chat  -->
<header id="header">
    <div class="container-fluid no-gutter masthead">
        <div class="row">
            <div id="main-logo" class="col-sm-12 col-md-9">
                <a href="./" title="Home Page"><img src="../images/ifxlogo.png?v=1.1" alt="Instaforex Nigeria Logo" /></a>
            </div>
            <div id="top-nav" class="col-sm-12 col-md-3" style="margin-top: 8px;">
                <div style="font-size: 1.4em;">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Notifications"><span id="counter" class="label label-pill label-danger count" style="border-radius:10px;"></span><i class="fa fa-bell fa-fw"></i></a>
                    <div class="panel panel-info dropdown-menu">
                        <div class="panel-heading"><b>Notifications</b></div>
                        <div id="list_content" style="max-height: 400px; overflow-y: scroll; max-width: 300px; min-width: 300px;" class="panel-body"></div>
                    </div>
                    <a href="profile_setting.php" title="Profile Settings"><i class="fa fa-user fa-fw"></i></a>&nbsp;&nbsp;
                    <a href="logout.php" title="Log Out"><i class="fa fa-sign-out fa-fw"></i></a>
                </div>
            </div>
        </div>
    </div>
    <hr>
</header>


<script>
    var XMLHttpRequestObject = false;

    if (window.XMLHttpRequest)
    {
        XMLHttpRequestObject = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    function sign_in (ip)
    {
        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open("POST", "hr_attendance_system.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 &&
                    XMLHttpRequestObject.status == 200)
                {
                    document.getElementById('hr_attendance_system').innerHTML = XMLHttpRequestObject.responseText;
                    if(document.getElementById('hr_attendance_system').innerHTML != "")
                    {
                        //window.alert(document.getElementById('hr_attendance_system').innerHTML);
                        $(document).ready(function()
                        {
                            $('#confirm-add-admin').modal("show");
                        });
                    }
                }
            };
            XMLHttpRequestObject.send("lan_ip=" + ip);
        }
        return false;
    }
    function get_lan_ip()
    {
        window.RTCPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;   //compatibility for firefox and chrome
        var pc = new RTCPeerConnection({iceServers:[]}), noop = function(){};
        pc.createDataChannel("");    //create a bogus data channel
        pc.createOffer(pc.setLocalDescription.bind(pc), noop);    // create offer and set local description
        pc.onicecandidate = function(ice)
        {  //listen for candidate events
            if(!ice || !ice.candidate || !ice.candidate.candidate)  return;
            var myIP = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)[1];
            console.log('my IP: ', myIP);
            sign_in(myIP);
            pc.onicecandidate = noop;
        };
    }
    function BrowserDetection()
    {
        var ua = navigator.userAgent;
        var info =
        {
            browser: /Edge\/\d+/.test(ua) ? 'ed' : /MSIE 9/.test(ua) ? 'ie9' : /MSIE 10/.test(ua) ? 'ie10' : /MSIE 11/.test(ua) ? 'ie11' : /MSIE\s\d/.test(ua) ? 'ie?' : /rv\:11/.test(ua) ? 'ie11' : /Firefox\W\d/.test(ua) ? 'ff' : /Chrom(e|ium)\W\d|CriOS\W\d/.test(ua) ? 'gc' : /\bSafari\W\d/.test(ua) ? 'sa' : /\bOpera\W\d/.test(ua) ? 'op' : /\bOPR\W\d/i.test(ua) ? 'op' : typeof MSPointerEvent !== 'undefined' ? 'ie?' : '',
            os: /Windows NT 10/.test(ua) ? "win10" : /Windows NT 6\.0/.test(ua) ? "winvista" : /Windows NT 6\.1/.test(ua) ? "win7" : /Windows NT 6\.\d/.test(ua) ? "win8" : /Windows NT 5\.1/.test(ua) ? "winxp" : /Windows NT [1-5]\./.test(ua) ? "winnt" : /Mac/.test(ua) ? "mac" : /Linux/.test(ua) ? "linux" : /X11/.test(ua) ? "nix" : "",
            touch: 'ontouchstart' in document.documentElement,
            mobile: /IEMobile|Windows Phone|Lumia/i.test(ua) ? 'w' : /iPhone|iP[oa]d/.test(ua) ? 'i' : /Android/.test(ua) ? 'a' : /BlackBerry|PlayBook|BB10/.test(ua) ? 'b' : /Mobile Safari/.test(ua) ? 's' : /webOS|Mobile|Tablet|Opera Mini|\bCrMo\/|Opera Mobi/i.test(ua) ? 1 : 0,
            tablet: /Tablet|iPad/i.test(ua),
        };
        return info;
    }
</script>
<?php $admin_code = $_SESSION['admin_unique_code'];
$query = "SELECT * FROM hr_attendance_log WHERE hr_attendance_log.admin_code = '$admin_code' AND hr_attendance_log.date = '$today' ";
$result = $db_handle->numRows($query);
$today = $db_handle->sanitizePost(date("d-m-Y"));
$day = date('l', strtotime($today));
if(($result < 1) && ($day != 'Saturday' || $day != 'Sunday')): ?>
    <script>
        var browser_info = BrowserDetection();
        if(browser_info['browser'] == 'ff' || browser_info['browser'] == 'gc'){get_lan_ip();}else{window.alert("To log your attendance in please sign in with Mozilla Firefox or Google Chrome.");}
    </script>
<?php endif; ?>
<div id="hr_attendance_system"></div>

