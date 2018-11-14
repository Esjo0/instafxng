<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25/10/2018
 * Time: 7:06 AM
 */
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}
$get_learning_position = $education_object->get_learning_position($_SESSION['client_unique_code']);
extract($get_learning_position);
$highest_lesson_published = $education_object->get_highest_lesson_published();

if($lesson_id != $highest_lesson_published){
    redirect_to("./");
}
$full_name = $_SESSION['client_last_name'] . ' ' . $_SESSION['client_first_name'];

$message_final = <<<MAIL
   <body style="background-image: url(../images/cert.jpg);
                background-repeat: no-repeat;
                background-size: cover;
                height: 1200px;
                width: 650px;">
    <div style="font-family: 'Times New Roman'; font-size: 30px; margin-left: 550px; padding-top:380px;" id="name">$full_name</div>
    </body>
MAIL;

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L'
]);
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($message_final);
$mpdf->Output($full_name .'.pdf', \Mpdf\Output\Destination::DOWNLOAD);