<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once '../models/dbConfig.php';

class companyInfoClass {

    private $comp_name;
    private $comp_web_title;
    private $comp_prv_name;
    private $comp_address;
    private $comp_hotline;
    private $comp_phone;
    private $comp_fax;
    private $comp_email;
    private $comp_email2;
    private $comp_domain;
    private $comp_google_map;
    private $comp_google_map_size;
    private $comp_skype;
    private $comp_fb;
    private $comp_tw;
    private $comp_gplus;
    private $comp_yt;

    function getComp_name() {
        return $this->comp_name;
    }

    function getComp_web_title() {
        return $this->comp_web_title;
    }

    function getComp_prv_name() {
        return $this->comp_prv_name;
    }

    function getComp_address() {
        return $this->comp_address;
    }

    function getComp_hotline() {
        return $this->comp_hotline;
    }

    function getComp_phone() {
        return $this->comp_phone;
    }

    function getComp_fax() {
        return $this->comp_fax;
    }

    function getComp_email() {
        return $this->comp_email;
    }

    function getComp_email2() {
        return $this->comp_email2;
    }

    function getComp_domain() {
        return $this->comp_domain;
    }

    function getComp_google_map() {
        return $this->comp_google_map;
    }

    function getComp_google_map_size() {
        return $this->comp_google_map_size;
    }

    function getComp_skype() {
        return $this->comp_skype;
    }

    function getComp_fb() {
        return $this->comp_fb;
    }

    function getComp_tw() {
        return $this->comp_tw;
    }

    function getComp_gplus() {
        return $this->comp_gplus;
    }

    function getComp_yt() {
        return $this->comp_yt;
    }

    function setComp_name($comp_name) {
        $this->comp_name = $comp_name;
    }

    function setComp_web_title($comp_web_title) {
        $this->comp_web_title = $comp_web_title;
    }

    function setComp_prv_name($comp_prv_name) {
        $this->comp_prv_name = $comp_prv_name;
    }

    function setComp_address($comp_address) {
        $this->comp_address = $comp_address;
    }

    function setComp_hotline($comp_hotline) {
        $this->comp_hotline = $comp_hotline;
    }

    function setComp_phone($comp_phone) {
        $this->comp_phone = $comp_phone;
    }

    function setComp_fax($comp_fax) {
        $this->comp_fax = $comp_fax;
    }

    function setComp_email($comp_email) {
        $this->comp_email = $comp_email;
    }

    function setComp_email2($comp_email2) {
        $this->comp_email2 = $comp_email2;
    }

    function setComp_domain($comp_domain) {
        $this->comp_domain = $comp_domain;
    }

    function setComp_google_map($comp_google_map) {
        $this->comp_google_map = $comp_google_map;
    }

    function setComp_google_map_size($comp_google_map_size) {
        $this->comp_google_map_size = $comp_google_map_size;
    }

    function setComp_skype($comp_skype) {
        $this->comp_skype = $comp_skype;
    }

    function setComp_fb($comp_fb) {
        $this->comp_fb = $comp_fb;
    }

    function setComp_tw($comp_tw) {
        $this->comp_tw = $comp_tw;
    }

    function setComp_gplus($comp_gplus) {
        $this->comp_gplus = $comp_gplus;
    }

    function setComp_yt($comp_yt) {
        $this->comp_yt = $comp_yt;
    }

    public function getInfo() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT comp_name FROM company_info";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $GLOBALS['comp_name'] = $row['comp_name'];
                $_SESSION['comp_name'] = $row['comp_name'];
            }
        }
        $query = "SELECT * FROM config_options";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $GLOBALS['config_version'] = $row['config_version'];
                $GLOBALS['config_hotline'] = $row['config_hotline'];
            }
        }
    }

    public function updateCompany() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "UPDATE company_info SET comp_name = '" . $this->getComp_name() . "', comp_web_title = '" . $this->getComp_web_title() . "', "
                . "comp_address = '" . $this->getComp_address() . "', comp_hotline = '" . $this->getComp_hotline() . "', "
                . "comp_phone = '" . $this->getComp_phone() . "', comp_fax = '" . $this->getComp_fax() . "', "
                . "comp_email = '" . $this->getComp_email() . "', comp_email2 = '" . $this->getComp_email2() . "', "
                . "comp_domain = '" . $this->getComp_domain() . "', "
                . "comp_google_map = '" . $this->getComp_google_map() . "', comp_google_map_size = '" . $this->getComp_google_map_size() . "', "
                . "comp_skype = '" . $this->getComp_skype() . "', comp_fb = '" . $this->getComp_fb() . "', "
                . "comp_tw = '" . $this->getComp_tw() . "', comp_gplus = '" . $this->getComp_gplus() . "', "
                . "comp_yt = '" . $this->getComp_yt() . "' WHERE comp_name = '" . $this->getComp_prv_name() . "'";
        $result = $myCon->query($query);
        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

}

?>