<?php
require_once("../../init/initialize_general.php");
$get_params = $_GET['x'];
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function()
        {
            //document.getElementById('link').click();
            $('#link')[0].click();
        });
    </script>
</head>
<body>
<div style="display: none;">
    <a id="link" href="<?php echo $get_params; ?>" download="<?php echo $get_params; ?>">Download</a>
</div>
</body>
</html>

