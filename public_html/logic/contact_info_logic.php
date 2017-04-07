<?php

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success) {
            $full_name = $_POST['full_name'];
            $email_address = $_POST['email_address'];
            $phone = $_POST['phone_number'];
            $comment = $_POST['comment'];
            
            
            $email_subject = "CLIENT INQUIRY FROM WEBSITE";
            $email_message = "
            Hello,


            Details:

            Name: " . $full_name . "
            Phone Number: " . $phone . "
            Comment: " . $comment . "


            Regards.
            Instafxng Webmaster

            ";

            $admin_email = "Instafxng Support <support@instafxng.com>";
            $headers = "From: {$email_address}";
            mail($admin_email, $email_subject, $email_message, $headers);
            header("Location: ../contact_info.php?s=1");
        }
    } else {
        
    }
} else {
    header("Location: http://instafxng.com/");
}