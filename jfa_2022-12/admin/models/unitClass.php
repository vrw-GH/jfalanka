<?php

include_once '../models/credentialCheckClass.php';

class unitClass {

    private $unit_name;
    private $unit_code;
    private $active;

    function getUnit_name() {
        return $this->unit_name;
    }

    function getUnit_code() {
        return $this->unit_code;
    }

    function getActive() {
        return $this->active;
    }

    function setUnit_name($unit_name) {
        $this->unit_name = $unit_name;
    }

    function setUnit_code($unit_code) {
        $this->unit_code = $unit_code;
    }

    function setActive($active) {
        $this->active = $active;
    }

    public function checkUnitCode() {
        $myCon = new dbConfig();
        $myCon->connect();
        $querycat = "SELECT unit_code FROM item_unit WHERE unit_code='" . $this->getUnit_code() . "'";
        $resultcat = $myCon->query($querycat);
        $myCon->closeCon();
        if (mysqli_num_rows($resultcat) >= 1) {
            throw new Exception('Sorry, Unit symbol "' . $this->getUnit_name() . '" already in the database');
        }
    }

    public function unitSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();
        $this->checkUnitCode();

        $query = "INSERT INTO item_unit (unit_code, unit_name, active) VALUES "
                . "('" . $this->getUnit_code() . "', '" . $this->getUnit_name() . "', '1')";
        $result = $myCon->query($query);
        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function unitUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "SELECT unit_name FROM item_unit WHERE unit_name='" . $this->getUnit_name() . "' "
                . "AND unit_code!='" . $this->getUnit_code() . "'";
        $result = $myCon->query($query);

        if (mysqli_num_rows($result) >= 1) {
            throw new Exception('Sorry, unit name  "' . $this->getUnit_name() . '" already in the database');
        } else {
            $queryinsert = "UPDATE item_unit SET unit_name='" . $this->getUnit_name() . "', "
                    . "active='" . $this->getActive() . "' WHERE unit_code='" . $this->getUnit_code() . "'";
            if (!$myCon->query($queryinsert)) {
                throw new Exception(mysqli_error());
            }
        }
    }

}

?>