        <!--<base href="https://instafxng.com/admin/" />-->
        <meta http-equiv="Content-Language" content="en" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Instant Web-Net Technologies Limited" />
        <meta name="publisher" content="Instant Web-Net Technologies Limited" />
        <meta name="copyright" content="Instant Web-Net Technologies Limited" />
        <meta name="rating" content="General" />
        <meta name="doc-rights" content="Private" />
        <meta name="doc-class" content="Living Document" />
        <link rel="stylesheet" href="../css/instafx_admin.css">
        <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
        <link rel="stylesheet" href="../css/bootstrap_3.3.5.min.css">
        <link rel="stylesheet" href="../css/font-awesome_4.6.3.min.css">
        <link href="../css/bootstrap-datetimepicker.css" rel="stylesheet">
        <script src="../js/jquery_2.1.1.min.js"></script>
        <script src="../js/bootstrap_3.3.5.min.js"></script>
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

        <script src="../js/ie10-viewport-bug-workaround.js"></script>
        <script src="../js/validator.min.js"></script>
        <script src="../js/npm.js"></script>
        <script src="../js/Chart.min.js"></script>
        <script src="../js/instafx_admin.js"></script>
        <!-- NOTIFICATION CSS -->
        <script src="../js/class_push_notification.js"></script>
        <script>
                $(document).ready(function()
                {
                        push_notifications.count_notifications();
                        setInterval(function(){push_notifications.load_last_notification();}, 5000);//TODO: Fix this back to 5000
                        push_notifications.count_notifications();
                });
        </script>


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
                                //console.log('my IP: ', myIP);
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

