
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
                                <div id="list_content" style="max-height: 400px; overflow-y: scroll; width: 300px" class="panel-body">
                                    <div onclick="$('#confirm-add-admin').modal('show');" class="alert alert-success">
                                        <a href="#" onclick="dismiss_notification('<?php echo $row['notification_id']; ?>')" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>New Project Message</strong>
                                        <p style="font-size: x-small !important;" class="text-sm-left text-muted">Feb 02, 2018 at 10:48 AM</p>
                                    </div>
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
        <div id="content">
            <!--Modal - confirmation boxes-->
            <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-8">
                                        <h4 class="modal-title">New Project Message</h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <button title="View Source" type="button" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-home"></i></button>
                                        <button title="Dismis Notification" type="button"  class="btn btn-sm btn-default"><i class="glyphicon glyphicon-ban-circle"></i></button>
                                        <button title="Close" type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-remove"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p><b>Project Title: </b> Customer care logs</p>
                            <p><b>Author: </b>Audu Emmanuel</p>
                            <p class="text-justify">Before the advent of the online training, we used to have three levels of training that our clients pay for.
                                Right now, we don't have that anymore, we only have the Forex Profit Academy...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function()
            {
                count_notifications();
                setInterval(function()
                {
                    load_last_notification();
                }, 600000);//TODO: Fix this back to 5000
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
                                document.getElementById('list_content').innerHTML = data;
                                count_notifications()
                            }
                        })
                }
                function count_notifications()
                {
                    var rows = document.getElementById("content").getElementsByTagName("div").length;
                    if(rows > 0)
                    {
                        document.getElementById("counter").innerHTML = rows;
                    }
                    if(rows <= 0)
                    {
                        //document.getElementById("counter").innerHTML = "";
                        //document.getElementById('content').innerHTML = "<center><img class='img-responsive' src='../images/notification_empty.jpg'></center>";
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