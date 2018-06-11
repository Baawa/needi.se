<?php

function sendMail($adress, $subject, $message){
  $email_to = $adress;
  $email_subject = $subject;

  $name = constant('email_name');
  $email_from = constant('email_account');

  $error_message = "";
  $email_message = "<style>body{background: #f7f7f7; margin: 0; padding: 0; font-family: sans-serif;} .container{max-width: 100%; height: auto; background: #ffffff; border: 1px solid transparent; border-radius: 8px; margin: 20px; padding: 20px;} h4{font-size: 18pt; font-weight: 700; line-height: 1; margin: 0; padding: 0;} p{font-size: 12pt; line-height: 1.5; margin: 0; margin-top: 1em; padding: 0;}</style>"
   . "<body><div class='container'>"
   . "$message"
   . "</div></body>";

  // create email headers
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
  $headers .= 'From: '."$name"."\r\n".
  'Reply-To: '.$email_from."\r\n" .
  'X-Mailer: PHP/' . phpversion();
  @mail($email_to, $email_subject, $email_message, $headers);
}


 ?>
