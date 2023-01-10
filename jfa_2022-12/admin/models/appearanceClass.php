<?php

include_once '../models/credentialCheckClass.php';

class appearanceClass {

    private $app_name;
    private $app_code;
    private $active;

    function getApp_name() {
        return $this->app_name;
    }

    function getApp_code() {
        return $this->app_code;
    }

    function getActive() {
        return $this->active;
    }

    function setApp_name($app_name) {
        $this->app_name = $app_name;
    }

    function setApp_code($app_code) {
        $this->app_code = $app_code;
    }

    function setActive($active) {
        $this->active = $active;
    }

    public function checkAppName() {
        $myCon = new dbConfig();
        $myCon->connect();
        $querycat = "SELECT app_name FROM item_appear WHERE app_name='" . $this->getApp_name() . "'";
        $resultcat = $myCon->query($querycat);
        $myCon->closeCon();
        if (mysqli_num_rows($resultcat) >= 1) {
            throw new Exception('Sorry, Appearance name "' . $this->getApp_name() . '" already in the database');
        }
    }

    public function checkAppNameOnUpdate() {
        $myCon = new dbConfig();
        $myCon->connect();
        $querycat = "SELECT app_name FROM item_appear WHERE app_name='" . $this->getApp_name() . "' "
                . "AND app_code!='" . $this->getApp_code() . "'";
        $resultcat = $myCon->query($querycat);
        $myCon->closeCon();
        if (mysqli_num_rows($resultcat) >= 1) {
            throw new Exception('Sorry, Appearance name "' . $this->getApp_name() . '" already in the database');
        }
    }

    public function appSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();
        $this->checkAppName();

        $query = "INSERT INTO item_appear (app_name, active) VALUES ('" . $this->getApp_name() . "', '1')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setApp_code($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function appUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();
        $this->checkAppNameOnUpdate();

        $query = "UPDATE item_appear SET app_name='" . $this->getApp_name() . "', "
                . "active='" . $this->getActive() . "' WHERE app_code='" . $this->getApp_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

}

?>