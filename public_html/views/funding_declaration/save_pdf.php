<?php
require_once '../../init/initialize_general.php';
$file_content = $_POST['file_content'];
if(isset($file_content) && !empty($file_content))
{
    $subject = "Funding Declaration";
    $message = <<<MAIL
            <div style="background-color: #F3F1F2">
            <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
            $file_content
            </div>
        </div>
    </div>
</div>
MAIL;
        $system_object->send_email($subject, $message, 'emmauel@instafxng.com', 'Demola', 'Instafxng');
    echo $message;
}