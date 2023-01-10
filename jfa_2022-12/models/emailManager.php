<?php

include('emailTemplates.php');

class emailManager {

    //check email ------------------------------------------------------------------
    public function is_valid_email($email) {
        if (preg_match("/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getTime() {
        $date = date("Y/m/d, g:i a");

        return $date;
    }

    public function VerificationEmail($fname, $email, $url) {
        $email_obj = new emailTemplates();
        $email_obj->send_verification_email($fname, $email, $url);
    }
}

?>