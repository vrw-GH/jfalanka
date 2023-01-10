<?php

include_once '../models/credentialCheckClass.php';

class memberClass {

    private $mem_id;
    private $mem_fname;
    private $mem_lname;
    private $mem_title;
    private $mem_phone;
    private $mem_fax;
    private $mem_email;
    private $mem_add_line1;
    private $mem_add_line2;
    private $mem_city;
    private $mem_country;
    private $mem_state;
    private $mem_pst_code;
    private $mem_nic_name;
    private $mem_type_id;
    //login
    private $user_email;
    private $user_id;
    private $user_pword;
    private $verified_once;
    private $active;
    private $old_user_pword;

    function getMem_id() {
        return $this->mem_id;
    }

    function getMem_fname() {
        return $this->mem_fname;
    }

    function getMem_lname() {
        return $this->mem_lname;
    }

    function getMem_title() {
        return $this->mem_title;
    }

    function getMem_phone() {
        return $this->mem_phone;
    }

    function getMem_fax() {
        return $this->mem_fax;
    }

    function getMem_email() {
        return $this->mem_email;
    }

    function getMem_add_line1() {
        return $this->mem_add_line1;
    }

    function getMem_add_line2() {
        return $this->mem_add_line2;
    }

    function getMem_city() {
        return $this->mem_city;
    }

    function getMem_country() {
        return $this->mem_country;
    }

    function getMem_state() {
        return $this->mem_state;
    }

    function getMem_pst_code() {
        return $this->mem_pst_code;
    }

    function getMem_nic_name() {
        return $this->mem_nic_name;
    }

    function getMem_type_id() {
        return $this->mem_type_id;
    }

    function getUser_email() {
        return $this->user_email;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getUser_pword() {
        return $this->user_pword;
    }

    function getVerified_once() {
        return $this->verified_once;
    }

    function getActive() {
        return $this->active;
    }

    function getOld_user_pword() {
        return $this->old_user_pword;
    }

    function setMem_id($mem_id) {
        $this->mem_id = $mem_id;
    }

    function setMem_fname($mem_fname) {
        $this->mem_fname = $mem_fname;
    }

    function setMem_lname($mem_lname) {
        $this->mem_lname = $mem_lname;
    }

    function setMem_title($mem_title) {
        $this->mem_title = $mem_title;
    }

    function setMem_phone($mem_phone) {
        $this->mem_phone = $mem_phone;
    }

    function setMem_fax($mem_fax) {
        $this->mem_fax = $mem_fax;
    }

    function setMem_email($mem_email) {
        $this->mem_email = $mem_email;
    }

    function setMem_add_line1($mem_add_line1) {
        $this->mem_add_line1 = $mem_add_line1;
    }

    function setMem_add_line2($mem_add_line2) {
        $this->mem_add_line2 = $mem_add_line2;
    }

    function setMem_city($mem_city) {
        $this->mem_city = $mem_city;
    }

    function setMem_country($mem_country) {
        $this->mem_country = $mem_country;
    }

    function setMem_state($mem_state) {
        $this->mem_state = $mem_state;
    }

    function setMem_pst_code($mem_pst_code) {
        $this->mem_pst_code = $mem_pst_code;
    }

    function setMem_nic_name($mem_nic_name) {
        $this->mem_nic_name = $mem_nic_name;
    }

    function setMem_type_id($mem_type_id) {
        $this->mem_type_id = $mem_type_id;
    }

    function setUser_email($user_email) {
        $this->user_email = $user_email;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setUser_pword($user_pword) {
        $this->user_pword = $user_pword;
    }

    function setVerified_once($verified_once) {
        $this->verified_once = $verified_once;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function setOld_user_pword($old_user_pword) {
        $this->old_user_pword = $old_user_pword;
    }

    
    public function checkEmail() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT user_email FROM login WHERE user_email='" . $this->getUser_email() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        $myCon->closeCon();
        if ($count >= 1) {
            throw new Exception("User Email '" . $this->getUser_email() . "' is already in the database!");
        } else {
            return true;
        }
    }

    public function checkUserName() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT user_id FROM login WHERE user_id='" . $this->getUser_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        $myCon->closeCon();
        if ($count >= 1) {
            throw new Exception("UserName '" . $this->getUser_id() . "' is already in the database!");
        } else {
            return true;
        }
    }

    public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function checkEmailOnUpdate() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT user_email FROM login WHERE user_id='" . $this->getUser_id() . "' 
            AND user_email!='" . $this->getUser_email() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        $myCon->closeCon();
        if ($count >= 1) {
            throw new Exception("User Email '" . $this->getUser_email() . "' is already in the database!");
        } else {
            return true;
        }
    }

    public function insertMember() {
        $myCred = new credentialCheckClass();
        $myCred->checkLoginStatus();

        $this->checkEmail();
        $this->checkUserName();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "INSERT INTO members (mem_title, mem_fname, mem_lname, mem_phone, "
                . "mem_fax, mem_email, mem_add_line1, mem_add_line2, mem_city, mem_country, "
                . "mem_state, mem_pst_code, mem_nic_name, mem_type_id) VALUES ("
                . "'" . $this->getMem_title() . "', '" . $this->getMem_fname() . "' , "
                . "'" . $this->getMem_lname() . "' , '" . $this->getMem_phone() . "' , "
                . "'" . $this->getMem_fax() . "' , '" . $this->getMem_email() . "' , "
                . "'" . $this->getMem_add_line1() . "', '" . $this->getMem_add_line2() . "' ,"
                . "'" . $this->getMem_city() . "' , '" . $this->getMem_country() . "' , "
                . "'" . $this->getMem_state() . "' , '" . $this->getMem_pst_code() . "' , "
                . "'" . $this->getMem_nic_name() . "', '" . $this->getMem_type_id() . "')";

        $result = $myCon->query($query);
        if ($result) {
            $query2 = "INSERT INTO login (user_email, user_pword, 
                verified_once, active) VALUES ('" . $this->getUser_email() . "' , "
                    . "'" . $this->getUser_pword() . "' , '" . $this->getVerified_once() . "' , "
                    . "'" . $this->getActive() . "')";
            $result2 = $myCon->query($query2);
            $myCon->closeCon();
            if ($result2) {
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function updateMember() {
        $myCred = new credentialCheckClass();
        $myCred->checkLoginStatus();
        $myCred->checkAdminCredential();
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE members SET mem_type_id='" . $this->getMem_type_id() . "' 
            WHERE mem_id='" . $this->getMem_id() . "'";
        $result = $myCon->query($query);
        if ($result) {
            $query2 = "UPDATE login SET active='" . $this->getActive() . "' 
            WHERE user_email='" . $this->getUser_email() . "'";
            $result2 = $myCon->query($query2);
            $myCon->closeCon();
            if ($result2) {
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function updateMemberSave() {
        $myCred = new credentialCheckClass();
        $myCred->checkLoginStatus();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE members SET mem_title='" . $this->getMem_title() . "', "
                . "mem_fname='" . $this->getMem_fname() . "', mem_lname='" . $this->getMem_lname() . "', "
                . "mem_phone='" . $this->getMem_phone() . "', mem_fax='" . $this->getMem_fax() . "', "
                . "mem_add_line1='" . $this->getMem_add_line1() . "', mem_add_line2='" . $this->getMem_add_line2() . "', "
                . "mem_city='" . $this->getMem_city() . "', mem_state='" . $this->getMem_state() . "', "
                . "mem_pst_code='" . $this->getMem_pst_code() . "' WHERE mem_email='" . $this->getMem_email() . "'";
        $result = $myCon->query($query);
        $myCon->closeCon();
        if ($result) {
            return true;
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function deleteMember() {
        $myCred = new credentialCheckClass();
        $myCred->checkLoginStatus();
        $myCred->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM members WHERE mem_email='" . $this->getMem_email() . "'";
        $result = $myCon->query($query);
        if ($result) {
            $query2 = "DELETE FROM login WHERE user_email='" . $this->getUser_email() . "'";
            $result2 = $myCon->query($query2);
            $myCon->closeCon();
            if ($result2) {
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function changePassword() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT user_pword FROM login WHERE user_id='" . $this->getUser_id() . "'";
        $result = $myCon->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['user_pword'] == $this->getOld_user_pword()) {
                $query2 = "UPDATE login SET user_pword='" . $this->getUser_pword() . "' 
                    WHERE user_id='" . $this->getUser_id() . "'";
                $result2 = $myCon->query($query2);
                if (!$result2) {
                    throw new Exception(mysqli_error());
                }
            } else {
                throw new Exception("Old password is invalied!");
            }
        }


        $myCon->closeCon();
    }

}

?>
