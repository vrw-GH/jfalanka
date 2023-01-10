<?php

include_once '../models/credentialCheckClass.php';

class brandClass {

    private $brand_code;
    private $cat_code;
    private $brand_name;
    private $brand_url_slug;
    private $brand_details;
    private $brand_custom_url;
    private $active;

    function getBrand_code() {
        return $this->brand_code;
    }

    function getCat_code() {
        return $this->cat_code;
    }

    function getBrand_name() {
        return $this->brand_name;
    }

    function getBrand_url_slug() {
        return $this->brand_url_slug;
    }

    function getBrand_details() {
        return $this->brand_details;
    }

    function getBrand_custom_url() {
        return $this->brand_custom_url;
    }

    function getActive() {
        return $this->active;
    }

    function setBrand_code($brand_code) {
        $this->brand_code = $brand_code;
    }

    function setCat_code($cat_code) {
        $this->cat_code = $cat_code;
    }

    function setBrand_name($brand_name) {
        $this->brand_name = $brand_name;
    }

    function setBrand_url_slug($brand_url_slug) {
        $this->brand_url_slug = $brand_url_slug;
    }

    function setBrand_details($brand_details) {
        $this->brand_details = $brand_details;
    }

    function setBrand_custom_url($brand_custom_url) {
        $this->brand_custom_url = $brand_custom_url;
    }

    function setActive($active) {
        $this->active = $active;
    }

    public function checkBrandName() {
        $myCon = new dbConfig();
        $myCon->connect();

        $querycat = "SELECT brand_name FROM item_brand WHERE "
                . "brand_name='" . $this->getBrand_name() . "' 
            AND brand_code='" . $this->getBrand_code() . "'";
        $resultcat = $myCon->query($querycat);
        if (mysqli_num_rows($resultcat) >= 1) {
            throw new Exception('Sorry, Brand name "' . $this->getBrand_name() . '" already in the database');
        } else {
            return true;
        }
    }

    public function checkBrandNameOnUpdate() {
        $myCon = new dbConfig();
        $myCon->connect();

        $querycat = "SELECT brand_name FROM item_brand WHERE "
                . "brand_name='" . $this->getBrand_name() . "' 
            AND brand_code!='" . $this->getBrand_code() . "'";
        $resultcat = $myCon->query($querycat);
        if (mysqli_num_rows($resultcat) >= 1) {
            throw new Exception('Sorry, Brand name "' . $this->getBrand_name() . '" already in the database');
        } else {
            return true;
        }
    }

    public function brandSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkLoginStatus();

        $this->checkBrandName();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "INSERT INTO item_brand (cat_code, brand_name, brand_url_slug, brand_custom_url,"
                . " brand_details, active) VALUES ('" . $this->getCat_code() . "', "
                . "'" . $this->getBrand_name() . "', '" . $this->getBrand_url_slug() . "', "
                . "'" . $this->getBrand_custom_url() . "', '" . $this->getBrand_details() . "', '1')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setBrand_code($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function brandUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $this->checkBrandNameOnUpdate();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE item_brand SET cat_code ='" . $this->getCat_code() . "', "
                . "brand_name ='" . $this->getBrand_name() . "', "
                . "brand_url_slug='" . $this->getBrand_url_slug() . "', "
                . "brand_custom_url ='" . $this->getBrand_custom_url() . "', brand_details ='" . $this->getBrand_details() . "', "
                . "active='" . $this->getActive() . "' WHERE brand_code='" . $this->getBrand_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

}

?>