<?php
class imageClass {

    private $upload_id;
    private $upload_title;
    private $upload_alter;

    function getUpload_id() {
        return $this->upload_id;
    }

    function getUpload_title() {
        return $this->upload_title;
    }

    function getUpload_alter() {
        return $this->upload_alter;
    }

    function setUpload_id($upload_id) {
        $this->upload_id = $upload_id;
    }

    function setUpload_title($upload_title) {
        $this->upload_title = $upload_title;
    }

    function setUpload_alter($upload_alter) {
        $this->upload_alter = $upload_alter;
    }

    public function titleUpdate() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE upload_data SET upload_title ='" . $this->getUpload_title() . "', "
                . "upload_alter ='" . $this->getUpload_alter() . "' "
                . "WHERE upload_id='" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

}
