<?php

/*
 * -----------------------------------------------------------------------------
 * site_config
 */
if (file_exists('site_config.php')) {
    include_once 'site_config.php';
} else if (file_exists('../site_config.php')) {
    include_once '../site_config.php';
} else {
    include_once '../../site_config.php';
}

class emailTemplates {

    public function is_valid_email($email) {
        if (preg_match("/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* Gmail ignoring style section, external css links, classes and id's */

    private $body = "text-align:left;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;text-decoration:none;";
    private $site_mail_container = "border:#CCC 1px solid;background:#FFF;width:600px;height:auto;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:13px;color:#222;text-align:left;line-height:24px;margin:10px auto;padding:20px 10px;";
    private $small_f = "font-size:11px;";
    private $header = "width:100%; height:auto;overflow:hidden; background:#FFF;margin:10px auto 0;padding:10px 0;";
    private $site_logo = "width:140px;height:90px;float:left;margin-left:5px;overflow:hidden;";
    private $name = "width:440px;height:26px;padding-top:10px;float:left;overflow:hidden;font-family:Arial, Helvetica, sans-serif;text-transform:uppercase;font-size:19px;font-weight:700;color:#e69802;text-shadow:0 0 1px rgba(60,60,60,0.8);margin:6px 0 5px 10px;";
    private $name2 = "width:440px;height:24px;float:left;overflow:hidden;font-family:Arial, Helvetica, sans-serif;font-size:15px;font-weight:700;color:#a90014;text-shadow:0 0 1px rgba(170,0,0,0.7);margin:6px 0 5px 10px;";
    private $hh = "width:100%;height:auto;overflow:hidden;clear:both;color:#003399;text-shadow:0 0 1px rgba(20,20,20,0.6);font-weight:700;font-size:14px;text-align:left;text-transform:capitalize;margin:0 auto;padding:15px 10px 6px 0;";
    private $a = "font-size:12px;font-family:Geneva, Arial, Helvetica, sans-serif;color:#555;font-weight:700;cursor:pointer;";
    private $footer = "width:100%;height:auto;overflow:hidden;clear:both;font-size:11px;line-height:16px;text-align:left;color:#333;margin:10px auto 0;";
    private $disclaim = "font-size:10px;color:#999;";
    private $un = "text-decoration:underline;";
    private $payment = "width:90%;height:auto;overflow:hidden;float:left;padding-left:5%;margin-left:5%;";


    /* Contact Variables */
    private $cont_site_name;
    private $cont_title;
    private $cont_address;
    private $cont_hotline;
    private $cont_phone;
    private $cont_fax;
    private $cont_email;
    private $cont_email2;
    private $cont_domain;
    private $cont_skype;
    private $cont_fb;
    private $cont_tw;
    private $cont_gplus;
    private $cont_yt;

    private function setConctat() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT * FROM company_info LIMIT 1";
        $result = $myCon->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $this->cont_site_name = $row['comp_name'];
            $this->cont_title = $row['comp_web_title'];
            $this->cont_address = $row['comp_address'];
            $this->cont_hotline = $row['comp_hotline'];
            $this->cont_phone = $row['comp_phone'];
            $this->cont_fax = $row['comp_fax'];
            $this->cont_email = $row['comp_email'];
            $this->cont_email2 = $row['comp_email2'];
            $this->cont_domain = $row['comp_domain']; /* without www and http */
            $this->cont_skype = $row['comp_skype'];
            $this->cont_fb = $row['comp_fb'];
            $this->cont_tw = $row['comp_tw'];
            $this->cont_gplus = $row['comp_gplus'];
            $this->cont_yt = $row['comp_yt'];
        }
    }

    /* Email Header */

    private function mailHeader() {
        $mail_header = '<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <style type="text/css">
            html,body,div,p,form,ul,li,label,span,img{border:none;display:block;margin:0;padding:0;}
            body{text-align:left;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;text-decoration:none;}
            .site-mail-container{border:#CCC 1px solid;background:#FFF;width:600px;height:auto;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:13px;color:#222;text-align:left;line-height:24px;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;margin:10px auto;padding:20px 10px;}
            .site-mail-container #small-f{font-size:11px;color:#036}
            .site-mail-container>.header{width:100%; height:auto;overflow:hidden; background:#FFF;margin:10px auto 0;padding:10px 0;}
            .header .site-logo{width:140px;height:90px;float:left;margin-left:5px;overflow:hidden;}
            .header .name{width:440px;height:26px;padding-top:10px;float:left;overflow:hidden;font-family:Arial, Helvetica, sans-serif;text-transform:uppercase;font-size:19px;font-weight:700;color:#e69802;text-shadow:0 0 1px rgba(60,60,60,0.8);margin:6px 0 5px 10px;}
            .header .name2{width:440px;height:24px;float:left;overflow:hidden;font-family:Arial, Helvetica, sans-serif;font-size:15px;font-weight:700;color:#a90014;text-shadow:0 0 1px rgba(170,0,0,0.7);margin:6px 0 5px 10px;}
            .site-mail-container .hh{width:100%;height:auto;overflow:hidden;clear:both;color:#003399;text-shadow:0 0 1px rgba(20,20,20,0.6);font-weight:700;font-size:14px;text-align:left;text-transform:capitalize;margin:0 auto;padding:15px 10px 6px 0;}
            a{font-size:12px;font-family:Geneva, Arial, Helvetica, sans-serif;color:#555;font-weight:700;cursor:pointer;}
            a:hover{color:#b90000;}
            .site-mail-container #footer{width:100%;height:auto;overflow:hidden;clear:both;font-size:11px;line-height:16px;text-align:left;color:#333;margin:10px auto 0 auto;}
            .disclaim{font-size:10px;color:#999;}
            .un{text-decoration:underline;}
            ul.payment{width:90%;height:auto;overflow:hidden;float:left;padding-left:5%;margin-left:5%;}
        </style>
    </head>
    <body style="' . $this->body . '">
        <div class="site-mail-container" style="' . $this->site_mail_container . '">
            <div class="header" style="' . $this->header . '">
                <div class="site-logo" style="' . $this->site_logo . '"><a href="' . WEB_HOST . '"><img src="' . WEB_HOST . '/resources/images/logo-mail.png" border="0"></a></div>
                <div class="name" style="' . $this->name . '">' . $this->cont_title . '</div>
            </div>';
        return $mail_header;
    }

    /* Email Footer */

    private function mailFooter() {
        $year = date('Y');
        $mail_footer = '<br/>Sincerely,<br/>
                        ' . $this->cont_title . '
                        <br/><br/>
                        Need help? <a href="' . WEB_HOST . '/contact-us" style="' . $this->a . '">Contact Us</a><br/>
                        <div id="footer" style="' . $this->footer . '">
                                &copy; ' . $year . ' ' . $this->cont_title . '<br/>
                                ' . $this->cont_address . '
                                <br/>
                                Hotline : ' . $this->cont_hotline . '<br/>
                                Tel : ' . $this->cont_phone . '<br/>
                                E-mail : ' . $this->cont_email . '
                                <br/><br/>
                           <div class="disclaim" style="' . $this->disclaim . '">     
                                This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. If you have received this email in error, please notify us and remove it from your system.
                            </div>
                        </div>
                </div>
            </body>
        </html>';

        return $mail_footer;
    }

    /* Email Template */

    private function emailBlock($from, $to, $subject, $message) {
        $this->setConctat();
        $mail_header = $this->mailHeader();
        $mail_footer = $this->mailFooter();

        /* Creating email Message */
        $message = $mail_header . $message . $mail_footer;

        $headers = "From: " . $from . "\n";
        $headers .= "Reply-To:  " . $to . "\n";
        /* $headers .= 'To: test <test@example.com>, test2 <test2@example.com>' . "\n";
          $headers .= 'Cc: birthdayarchive@example.com' . "\n";
          $headers .= 'Bcc: admin@yahoo.com' . "\n"; */
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=UTF-8";
        /* Send the email */
        mail($to, $subject, $message, $headers);
        return true;
    }

    /* Sending Password email------------------------------------------------ */

    public function sendPassword($password, $to) {
        $from = $this->cont_title . '<' . SEND_EMAIL . '>';
        $subject = $this->cont_title . 'Password Assistance';
        $message = '<div class="hh" style="' . $this->hh . '">Password Assistance</div>
                    <br/>
                    <strong>This is an automatically generated message, Please do not reply to this message.</strong>
                    <br/>
                    We have received a Account Password request from this email address. You can log in to our web site using your email address and a password.<br/>
                    <br/>
                    <strong>Your password is -</strong> ' . $password . '<br/>';

        $this->emailBlock($from, $to, $subject, $message);
        return true;
    }

    /* ---------------------------------------------------------------------- */

    /* Sending Welcome email------------------------------------------------- */

    public function sendWelcomeEmail($password, $to) {
        $from = $this->cont_title . '<' . SEND_EMAIL . '>';
        $subject = 'Welcome to ' . $this->cont_title;
        $message = '<div class="hh" style="' . $this->hh . '">Welcome to ' . $this->cont_title . '!</div>
                    <br/>
                    <strong>Your Account has been successfully activated.</strong> You can now log in to this <a href="' . WEB_HOST . '/user-login.php" style="' . $this->a . '">web site</a> using your email address and a password.<br/>
                    <br/>
                    <strong>Email -</strong> ' . $to . '<br/>
                    <strong>Password -</strong> ' . $password . '<br/>
                    <br/><br/>';

        $this->emailBlock($from, $to, $subject, $message);
        return true;
    }

    /* ---------------------------------------------------------------------- */

    /* Sending Order email------------------------------------------------- */

    public function sendOrderEmail($order_id, $order_details, $to) {
        $from = $this->cont_title . '<' . SEND_EMAIL . '>';
        $subject = 'Order Details - Order ID' . $order_id;
        $message = '<div class="hh" style="' . $this->hh . '">Order ID #' . $order_id . '!</div>
                    <br/>
                   ' . $order_details;

        $this->emailBlock($from, $to, $subject, $message);
        return true;
    }

    /* ---------------------------------------------------------------------- */

    /* Sending Dispatch email------------------------------------------------- */

    public function sendDispatchEmail($to, $order_id, $message) {
        $from = $this->cont_title . '<' . SEND_EMAIL . '>';
        $subject = 'Order Dispatch note - Order ID' . $order_id;
        $message = '<div class="hh" style="' . $this->hh . '">Dispatch note!</div>
                    <br/>
                   ' . $message;

        $this->emailBlock($from, $to, $subject, $message);
        return true;
    }

    /* ---------------------------------------------------------------------- */
}

?>