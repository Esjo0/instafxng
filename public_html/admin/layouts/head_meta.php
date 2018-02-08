        <base href="https://instafxng.com/admin/" />
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
        <script src="../js/class_attendance_system.js"></script>

