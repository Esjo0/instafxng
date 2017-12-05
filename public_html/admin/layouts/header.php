
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
                                <div id="content" style="max-height: 400px; overflow-y: scroll; " class="panel-body">
                                </div>
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
            $(document).ready(function()
            {
                count_notifications();
                setInterval(function()
                {
                    load_last_notification();
                }, 5000);
                count_notifications();

                function load_last_notification()
                {
                    count_notifications();
                    $.ajax(
                        {
                            url:"push_notification_server.php",
                            method:"POST",
                            success:function(data)
                            {
                                //$('#message1').toggleClass('in');
                                document.getElementById('content').innerHTML = data;
                                count_notifications()
                            }
                        })
                }
                function count_notifications()
                {
                    var rows = document.getElementById("content").getElementsByTagName("li").length;
                    if(rows > 0)
                    {
                        document.getElementById("counter").innerHTML = rows;
                    }
                    if(rows <= 0)
                    {
                        document.getElementById("counter").innerHTML = "";
                        document.getElementById('content').innerHTML = "<center><img class='img-responsive' src='../images/notification_empty.jpg'></center>";
                    }
                }
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
            function dismiss_notification (notification_id)
            {
                if(XMLHttpRequestObject)
                {
                    XMLHttpRequestObject.open("POST", "push_notification_server.php");
                    XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                    XMLHttpRequestObject.onreadystatechange = function()
                    {
                        if (XMLHttpRequestObject.readyState == 4 &&
                            XMLHttpRequestObject.status == 200)
                        {
                            count_notifications();
                        }
                    }
                    var type = '0';
                    //window.alert("type=" + type + "&notification_id=" + notification_id);
                    XMLHttpRequestObject.send("type=" + type + "&notification_id=" + notification_id);
                    //load_last_notification();
                    count_notifications();
                }

                return false;

            }

        </script>

        <!--<script>
            function get_notifications()
            {
                var req = new XMLHttpRequest();
                req.onreadystatechange = function()
                {
                    if(req.readyState == 4 && req.status == 200)
                    {
                        document.getElementById("content").innerHTML = req.responseText;
                    }
                }
                req.open('GET', 'push_notification_server.php, true);
                req.send();
            }
            setInterval(function(){get_notifications()}, 3000)
        </script>-->