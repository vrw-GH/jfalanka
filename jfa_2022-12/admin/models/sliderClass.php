<?php

include_once '../models/credentialCheckClass.php';

class sliderClass {

    private $content_id;
    private $upload_id;
    private $content_header;
    private $content_descp;
    private $content_url;
    private $slider_order;

    function getContent_id() {
        return $this->content_id;
    }

    function getUpload_id() {
        return $this->upload_id;
    }

    function getContent_header() {
        return $this->content_header;
    }

    function getContent_descp() {
        return $this->content_descp;
    }

    function getContent_url() {
        return $this->content_url;
    }

    function getSlider_order() {
        return $this->slider_order;
    }

    function setContent_id($content_id) {
        $this->content_id = $content_id;
    }

    function setUpload_id($upload_id) {
        $this->upload_id = $upload_id;
    }

    function setContent_header($content_header) {
        $this->content_header = $content_header;
    }

    function setContent_descp($content_descp) {
        $this->content_descp = $content_descp;
    }

    function setContent_url($content_url) {
        $this->content_url = $content_url;
    }

    function setSlider_order($slider_order) {
        $this->slider_order = $slider_order;
    }

    public function sliderOrder() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT slider_order FROM slider_content ORDER BY slider_order DESC LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = $row['slider_order'] + 1;
            }
        } else {
            /* no recordes */
            $num = 0;
        }
        return $num;
    }

    public function sliderChnageOrder() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE slider_content SET slider_order='" . $this->getSlider_order() . "' "
                . "WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function addSliderContent() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "INSERT INTO slider_content (upload_id, content_header, content_descp, content_url, slider_order) VALUES "
                . "('" . $this->getUpload_id() . "', '" . $this->getContent_header() . "', "
                . "'" . $this->getContent_descp() . "', '" . $this->getContent_url() . "', '" . $this->getSlider_order() . "')";
        $result = $myCon->query($query);

        if ($result) {
            return TRUE;
        } else {
            throw new Exception($query);
        }
    }

    public function editSliderContent() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE slider_content SET content_header='" . $this->getContent_header() . "', "
                . "content_descp='" . $this->getContent_descp() . "', content_url='" . $this->getContent_url() . "' "
                . "WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);

        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function removeSliderContent() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM slider_content WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);

        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

}
