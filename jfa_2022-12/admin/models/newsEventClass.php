<?php

include_once '../models/credentialCheckClass.php';

class newsEventClass {

    private $ann_id;
    private $ann_title;
    private $ann_details;
    private $ann_date;
    private $add_date;
    private $add_by;
    private $upd_date;
    private $upd_by;
    private $ann_type;

    function getAnn_id() {
        return $this->ann_id;
    }

    function getAnn_title() {
        return $this->ann_title;
    }

    function getAnn_details() {
        return $this->ann_details;
    }

    function getAnn_date() {
        return $this->ann_date;
    }

    function getAdd_date() {
        return $this->add_date;
    }

    function getAdd_by() {
        return $this->add_by;
    }

    function getUpd_date() {
        return $this->upd_date;
    }

    function getUpd_by() {
        return $this->upd_by;
    }

    function getAnn_type() {
        return $this->ann_type;
    }

    function setAnn_id($ann_id) {
        $this->ann_id = $ann_id;
    }

    function setAnn_title($ann_title) {
        $this->ann_title = $ann_title;
    }

    function setAnn_details($ann_details) {
        $this->ann_details = $ann_details;
    }

    function setAnn_date($ann_date) {
        $this->ann_date = $ann_date;
    }

    function setAdd_date($add_date) {
        $this->add_date = $add_date;
    }

    function setAdd_by($add_by) {
        $this->add_by = $add_by;
    }

    function setUpd_date($upd_date) {
        $this->upd_date = $upd_date;
    }

    function setUpd_by($upd_by) {
        $this->upd_by = $upd_by;
    }

    function setAnn_type($ann_type) {
        $this->ann_type = $ann_type;
    }

    public function newsSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkLoginStatus();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "INSERT INTO news_events (ann_title, ann_details, ann_date, add_date, add_by, ann_type) "
                . "VALUES ('" . $this->getAnn_title() . "', '" . $this->getAnn_details() . "', "
                . "'" . $this->getAnn_date() . "', '" . $this->getAdd_date() . "', "
                . "'" . $this->getAdd_by() . "', '" . $this->getAnn_type() . "')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setAnn_id($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function newsUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();
        $query = "UPDATE news_events SET ann_title='" . $this->getAnn_title() . "', "
                . "ann_details='" . $this->getAnn_details() . "', add_date='" . $this->getAdd_date() . "', "
                . "upd_date ='" . $this->getUpd_date() . "', upd_by='" . $this->getUpd_by() . "', "
                . "ann_type='" . $this->getAnn_type() . "' WHERE ann_id = '" . $this->getAnn_id() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function newsDelete() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();
        /* delete news */
        $query = "DELETE FROM news_events WHERE ann_id = '" . $this->getAnn_id() . "'";
        $result = $myCon->query($query);
        if ($result) {
            /* delete news images */
            $query = "SELECT * FROM upload_data WHERE upload_ref = '" . $this->getAnn_id() . "' "
                    . "AND upload_type_id = 10";
            $result = $myCon->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            /* delete news image records */
            $query = "DELETE FROM upload_data WHERE upload_ref = '" . $this->getAnn_id() . "' "
                    . "AND upload_type_id = 10";
            $result = $myCon->query($query);
        } else {
            throw new Exception(mysqli_error());
        }
    }

}

?>