<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require '../resources/PHPMailer/PHPMailer.php';
require '../resources/PHPMailer/SMTP.php';
require '../resources/PHPMailer/Exception.php';

include_once '../' . $website['decryptor_php'];

/* --------------------------------------------------------------------
 * Emailing / here you can set "bare" email settings - ie for php mail() function??
 */
if (!defined('EMAIL_ISSMTP'))    define('EMAIL_ISSMTP', 0);
if (!defined('EMAIL_TO'))        define('EMAIL_TO', $website['email']);
if (!defined('EMAIL_FROM'))      define('EMAIL_FROM', 'noreply@' . $website['domain']); /* ?accountname@host.phenomhost.com */
if (!defined('EMAIL_FROM_NAME')) define('EMAIL_FROM_NAME', 'WebAdmin-' . $website['domain']);
if (!defined('EMAIL_HOST'))      define('EMAIL_HOST', 'mail.' . $website['domain']);
if (!defined('EMAIL_PORT'))      define('EMAIL_PORT', 25); // 25, 465 or 587
if (!defined('EMAIL_USER'))      define('EMAIL_USER', 'webdamin@' . $website['domain']);
if (!defined('EMAIL_PWD'))       define('EMAIL_PWD', '');

/* --------------------------------------------------------------------
 * Send the mail in normal mail or SMTP
 */
function sendEmail($message, $subject, $fname, $lname, $email, $phone, $body = null)
{
   $from =  explode(",", EMAIL_FROM);   // [0]email [1]name
   $to =    explode(",", EMAIL_TO);     // [0]email [1]name   

   if (!boolval(EMAIL_ISSMTP)) {    //default false= normal mail() (with settings in php.ini ??)
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      /* Additional headers */
      $headers .= "From: \"" . $from[0] . "\"\n"; // $headers .= 'From:' . $from . "\r\n";
      /* $headers .= 'Cc: birthdayarchive@example.com' . "\r\n"; */
      /* $headers .= 'Bcc: admin@yahoo.com' . "\r\n"; */
      if (!mail($to[0], $subject, $body, $headers)) {
         cLog(json_encode(error_get_last()));
         return false;
      }
      cLog("normal mail sent!");
      return true;
   } else {

      date_default_timezone_set('Etc/UTC');
      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      //Enable SMTP debugging
      //SMTP::DEBUG_OFF = off (for production use)
      //SMTP::DEBUG_CLIENT = client messages
      //SMTP::DEBUG_SERVER = client and server messages
      $mail->SMTPDebug           = SMTP::DEBUG_OFF;

      $mail->isSMTP();                          //Send using SMTP
      // $mail->isHTML(true);                     // Set email format to HTML      
      $mail->Host                = EMAIL_HOST;   //Set the SMTP server to send through
      // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
      //Set the SMTP port number - likely to be 25, 465 or 587
      //use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
      $mail->Port                = EMAIL_PORT;   // $mail->Port       = EMAIL_PORT; 
      $mail->SMTPAuth            = true;        //Enable SMTP authentication
      $mail->Username            = decryptor(EMAIL_USER);
      $mail->Password            = decryptor(EMAIL_PWD);
      $mail->setFrom($from[0], $from[1]); //Set who is the message from, name is optional
      $mail->addAddress($to[0], $to[1]);  //Set who is the message to, Name is optional
      $mail->addReplyTo($email, "$fname $lname");   //Set an alternative reply-to address
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
         cLog("SMTP mail sent!");
         // echo 'Message has been sent';
      } catch (Exception $e) {
         $isSent = false;
         cLog($e);
         // echo "\n** Mailer Error: {$mail->ErrorInfo}";
      }

      return $isSent;
   }
};


cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.');
