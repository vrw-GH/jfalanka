<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

//Load Composer's autoloader
// require 'vendor/autoload.php';
require '../resources/PHPMailer/Exception.php';
require '../resources/PHPMailer/PHPMailer.php';
require '../resources/PHPMailer/SMTP.php';

function sendSMTP($message, $subject, $fname, $lname, $email, $phone, $to = EMAIL_TO, $from = EMAIL_FROM, $body = null)
{
   //Create an instance; passing `true` enables exceptions
   date_default_timezone_set('Etc/UTC');
   $mail = new PHPMailer(true);

   $mail->isSMTP();                          //Send using SMTP
   // $mail->isHTML(true);                     // Set email format to HTML      
   //Enable SMTP debugging
   //SMTP::DEBUG_OFF = off (for production use)
   //SMTP::DEBUG_CLIENT = client messages
   //SMTP::DEBUG_SERVER = client and server messages
   $mail->SMTPDebug           = SMTP::DEBUG_SERVER;
   //Set the hostname of the mail server
   $mail->Host                = SMTP_HOST;   //Set the SMTP server to send through
   // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
   //Set the SMTP port number - likely to be 25, 465 or 587
   //use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
   $mail->Port                = SMTP_PORT;   // $mail->Port       = SMTP_PORT; 
   $mail->SMTPAuth            = true;        //Enable SMTP authentication
   $mail->Username            = SMTP_USER;   //SMTP username
   $mail->Password            = SMTP_PWD;    //SMTP password
   $mail->setFrom($from, 'JFA web inq.');    //Set who the message is to be sent from
   $mail->addAddress($to);                   //Set who the message is to be sent to, Name is optional
   $mail->addReplyTo($email, "$fname $lname"); //Set an alternative reply-to address
   // $mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');
   $mail->Subject = $subject;                //Set the subject line
   //Read an HTML message body from an external file, convert referenced images to embedded   
   // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
   $mail->msgHTML($body);
   //convert HTML into a basic plain-text alternative body
   //TODO $mail->AltBody = strip_tags($body, '<br>');
   $mail->AltBody = "
                        Sender Name  : $fname $lname \n
                        Sender Email : $email \n
                        Sender Phone : $phone \n
                        Message      : $message
                        ";
   //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

   try {
      $isSent = $mail->send();
      // echo 'Message has been sent';
   } catch (Exception $e) {
      $isSent = false;
      // echo "\n** Mailer Error: {$mail->ErrorInfo}";
   }



   return $isSent;
}


cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.');
