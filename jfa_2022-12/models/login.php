<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once 'dbConfig.php';
include_once 'encryption.php';

class login {

    public function is_valid_email($email) {
        if (preg_match("/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkpass($uname, $pword) {
        $ecryptObj = new encryption();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT * FROM login WHERE (user_id='" . $uname . "' OR user_email='" . $uname . "') AND user_pword='" . $ecryptObj->encode($pword) . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_array($result)) {
                if ($row['verified_once'] == $ecryptObj->encode('yes') && $row['active'] == $ecryptObj->encode('yes')) {
                    $_SESSION['system_logged_email'] = $row['user_email']; /* user email */
                    $_SESSION['system_logged_uname'] = $row['user_id']; /* username */
                    $_SESSION['system_logged_access_level'] = $row['user_level']; /* Access Level */

                    $query2 = "SELECT me.mem_fname, me.mem_lname, me.mem_country,  mt.mem_type_id, 
                        mt.mem_type_name FROM members AS me, mem_types AS mt WHERE me.mem_email='" . $row['user_email'] . "' AND
                            me.mem_type_id = mt.mem_type_id LIMIT 1";
                    $result2 = $myCon->query($query2);
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $_SESSION['system_logged_fname'] = $row2['mem_fname'];  /* First Name */
                        $_SESSION['system_logged_lname'] = $row2['mem_lname'];  /* Last Name */
                        $_SESSION['system_logged_mem_type_id'] = $row2['mem_type_id'];  /* Stakeholder Type */
                        $_SESSION['system_logged_mem_type_name'] = $row2['mem_type_name'];  /* Stakeholder Type Name */
                        $_SESSION['system_logged_mem_country'] = $row2['mem_country'];  /* Stakeholder country */
                    }
                    $_SESSION['system_logged_status'] = true; /* user is logged. */
                    $_SESSION['server_domain_user'] = $_SERVER['SERVER_NAME'].$row['user_id'];

                    return true; /* user is a active user and email is verified */
                } else if ($row['verified_once'] == $ecryptObj->encode('no') && $row['active'] == $ecryptObj->encode('yes')) {
                    throw new Exception("Email is not confirmed. Please check your email 'inbox' or 'spam' folder for confirmation email.");
                } else {
                    throw new Exception("User name is suspended, Please Contact your Administrator");
                }
            }//end while
            $myCon->closeCon();
        } else {
            $myCon->closeCon();
            //wrong password
            throw new Exception("Sorry, password do not match.");
        }
    }

    /* This function calling from when checkout login */

    public function setUserSessions($uname) {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT * FROM members WHERE mem_id='" . $uname . "' OR mem_email='" . $uname . "' LIMIT 1";
        $result = $myCon->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['mem_fname'] = $row['mem_fname'];
            $_SESSION['mem_lname'] = $row['mem_lname'];
            $_SESSION['mem_title'] = $row['mem_title'];
            $_SESSION['mem_phone'] = $row['mem_phone'];
            $_SESSION['mem_mobile'] = $row['mem_mobile'];
            $_SESSION['mem_email'] = $row['mem_email'];
            $_SESSION['mem_add_line1'] = $row['mem_add_line1'];
            $_SESSION['mem_add_line2'] = $row['mem_add_line2'];
            $_SESSION['mem_city'] = $row['mem_city'];
            $_SESSION['mem_country'] = $row['mem_country'];
            $_SESSION['mem_state'] = $row['mem_state'];
            $_SESSION['mem_pst_code'] = $row['mem_pst_code'];
        }
    }

    public function checkEmail($email) {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT user_email FROM login WHERE user_email='" . $email . "' LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        $myCon->closeCon();
        if ($count == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUser($uname) {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT user_id FROM login WHERE user_id='" . $uname . "' LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        $myCon->closeCon();
        if ($count == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function loginUser($uname, $pword, $captcha_true = false, $captcha = null) {
        if ($captcha_true == true) {
            if (strtolower($captcha) != $_SESSION['securimage_code_value']['default']) {
                throw new Exception("Please Enter Correct Image value.");
            }
        }

        if ($this->is_valid_email($uname)) {
            if ($this->checkEmail($uname)) {
                if ($this->checkpass($uname, $pword)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new Exception("Sorry, Username do not match.");
            }
        } else {
            if ($this->checkUser($uname)) {
                if ($this->checkpass($uname, $pword)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new Exception("Sorry, Username do not match.");
            }
        }
    }

}

?>