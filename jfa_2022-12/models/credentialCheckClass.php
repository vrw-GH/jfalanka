<?php

if (!isset($_SESSION)) {
    session_start();
}

class credentialCheckClass {

    public function checkLoginStatus() {
        if (!isset($_SESSION['system_logged_email']) || $_SESSION['system_logged_email'] == null ||
                !isset($_SESSION['system_logged_uname']) || $_SESSION['system_logged_uname'] == null ||
                !isset($_SESSION['system_logged_mem_type_id']) || $_SESSION['system_logged_mem_type_id'] == null ||
                !isset($_SESSION['system_logged_status']) || $_SESSION['system_logged_status'] == null) {
            throw new Exception("Your session is expired. Please relogin.");
        } else {
            return true;
        }
    }

    public function checkSuperAdminCredential() {
        if ($_SESSION['system_logged_mem_type_id'] != 1) {
            throw new Exception("You do not have sufficient security credentials. Please contact your Administrator.");
        } else {
            return true;
        }
    }

    public function checkAdminCredential() {
        if ($_SESSION['system_logged_mem_type_id'] > 2 || $_SESSION['system_logged_mem_type_id'] < 1) {
            throw new Exception("You do not have sufficient security credentials. Please contact your Administrator.");
        } else {
            return true;
        }
    }

    public function checkFrontOfficeCredential() {
        if ($_SESSION['system_logged_mem_type_id'] != 3) {
            throw new Exception("You do not have sufficient login credentials (Cred. = FrontOffice). Please contact your Administrator.");
        } else {
            return true;
        }
    }

    public function checkMarketerCredential() {
        if ($_SESSION['system_logged_mem_type_id'] != 4) {
            throw new Exception("You do not have sufficient login credentials (Cred. = Marketer). Please contact your Administrator.");
        } else {
            return true;
        }
    }

}

?>