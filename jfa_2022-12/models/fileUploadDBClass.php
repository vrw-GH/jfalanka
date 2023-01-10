<?php

class fileUploadDBClass {

    private $upload_id;
    private $upload_path;
    private $upload_ref;
    private $upload_ref2;
    private $upload_type_id;
    private $upload_title;
    private $upload_alter;
    private $featured;
    private $remove_done;

    function getUpload_id() {
        return $this->upload_id;
    }

    function getUpload_path() {
        return $this->upload_path;
    }

    function getUpload_ref() {
        return $this->upload_ref;
    }

    function getUpload_ref2() {
        return $this->upload_ref2;
    }

    function getUpload_type_id() {
        return $this->upload_type_id;
    }

    function getUpload_title() {
        return $this->upload_title;
    }

    function getUpload_alter() {
        return $this->upload_alter;
    }

    function getFeatured() {
        return $this->featured;
    }

    function getRemove_done() {
        return $this->remove_done;
    }

    function setUpload_id($upload_id) {
        $this->upload_id = $upload_id;
    }

    function setUpload_path($upload_path) {
        $this->upload_path = $upload_path;
    }

    function setUpload_ref($upload_ref) {
        $this->upload_ref = $upload_ref;
    }

    function setUpload_ref2($upload_ref2) {
        $this->upload_ref2 = $upload_ref2;
    }

    function setUpload_type_id($upload_type_id) {
        $this->upload_type_id = $upload_type_id;
    }

    function setUpload_title($upload_title) {
        $this->upload_title = $upload_title;
    }

    function setUpload_alter($upload_alter) {
        $this->upload_alter = $upload_alter;
    }

    function setFeatured($featured) {
        $this->featured = $featured;
    }

    function setRemove_done($remove_done) {
        $this->remove_done = $remove_done;
    }

    public function checkFile() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            return true;
        } else {
            return false;
        }
        $myCon->closeCon();
    }

    public function checkFileAdvance() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_ref2 = '" . $this->getUpload_ref2() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            return true;
        } else {
            return false;
        }
        $myCon->closeCon();
    }

    public function checkFeatured() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "' AND "
                . "featured = '1'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            return true;
        } else {
            return false;
        }
        $myCon->closeCon();
    }

    /* ------------------------------------------------------------------------------- */

    public function uploadFile() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "INSERT INTO upload_data (upload_path, upload_ref, upload_ref2, upload_type_id, "
                . "upload_title, upload_alter, featured) VALUES ('" . $this->getUpload_path() . "', "
                . "'" . $this->getUpload_ref() . "', '" . $this->getUpload_ref2() . "', "
                . "'" . $this->getUpload_type_id() . "', '" . $this->getUpload_title() . "', "
                . "'" . $this->getUpload_alter() . "', '" . $this->getFeatured() . "')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setUpload_id($myCon->mysqliInsertId());
            $myCon->closeCon();
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function updateFile() {
        /* This method shoud call after upload a new file through the controler class */
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_id, upload_path FROM upload_data WHERE "
                . "upload_ref = '" . $this->getUpload_ref() . "' AND "
                . "upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $this->setUpload_id($row['upload_id']);

                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }

                $query2 = "UPDATE upload_data SET upload_path='" . $this->getUpload_path() . "' "
                        . "WHERE upload_id = '" . $this->getUpload_id() . "'";
                $result2 = $myCon->query($query2);
                if ($result2) {
                    return true;
                } else {
                    throw new Exception(mysqli_error());
                }
            }
        }
    }

    public function updateFileWithID() {
        /* This method shoud call after upload a new file through the controler class */
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE "
                . "upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {

                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }

                $query2 = "UPDATE upload_data SET upload_path='" . $this->getUpload_path() . "' "
                        . "WHERE upload_id = '" . $this->getUpload_id() . "'";
                $result2 = $myCon->query($query2);
                if ($result2) {
                    return true;
                } else {
                    throw new Exception(mysqli_error());
                }
            }
        }
    }

    public function updateImage() {
        /* This method shoud call after upload a new file through the controler class */
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_id, upload_path FROM upload_data WHERE "
                . "upload_ref = '" . $this->getUpload_ref() . "' AND "
                . "upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $this->setUpload_id($row['upload_id']);

                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }

                $query2 = "UPDATE upload_data SET upload_path='" . $this->getUpload_path() . "' "
                        . "WHERE upload_id = '" . $this->getUpload_id() . "'";
                $result2 = $myCon->query($query2);
                if ($result2) {
                    return true;
                } else {
                    throw new Exception(mysqli_error());
                }
            }
        }
    }

    public function removeFile() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $this->setRemove_done('true');
            return true;
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function removeFileWithID() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $this->setRemove_done('true');
            return true;
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function removeFileAndRecord() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $query = "DELETE FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                    . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
            $result = $myCon->query($query);
            if ($result) {
                $this->setRemove_done('true');
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function removeFileAndRecordWithID() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $query = "DELETE FROM upload_data WHERE upload_id = '" . $this->getUpload_id() . "'";
            $result = $myCon->query($query);
            if ($result) {
                $this->setRemove_done('true');
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function removeImage() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            $this->setRemove_done('true');
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
        } else {
            $this->setRemove_done('false');
            return true;
        }
        $myCon->closeCon();
    }

    public function removeImageAndRecord() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $query = "DELETE FROM upload_data WHERE upload_ref = '" . $this->getUpload_ref() . "' "
                    . "AND upload_type_id = '" . $this->getUpload_type_id() . "'";
            $result = $myCon->query($query);
            if ($result) {
                $this->setRemove_done('true');
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function removeImageWithID() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $this->setRemove_done('true');
            return true;
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function removeImageAndRecordWithID() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT upload_path FROM upload_data WHERE upload_id = '" . $this->getUpload_id() . "'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            $query = "DELETE FROM upload_data WHERE upload_id = '" . $this->getUpload_id() . "'";
            $result = $myCon->query($query);
            if ($result) {
                $this->setRemove_done('true');
                return true;
            } else {
                throw new Exception(mysqli_error());
            }
        } else {
            $this->setRemove_done('false');
        }
        $myCon->closeCon();
    }

    public function changefileTypeId($roll_back_upload_id) {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "UPDATE upload_data SET upload_type_id = '" . $roll_back_upload_id . "' "
                . "WHERE upload_ref = '" . $this->getUpload_ref() . "' AND "
                . "upload_type_id = '" . $this->getUpload_type_id() . "'";
        $result = $myCon->query($query);
        if ($result) {
            return true;
        } else {
            throw new Exception(mysqli_error());
        }
        $myCon->closeCon();
    }

    public function changefileTypeWithUploadID() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "UPDATE upload_data SET upload_type_id = '" . $this->getUpload_type_id() . "',  "
                . "upload_ref2 = '" . $this->getUpload_ref2() . "' "
                . "WHERE upload_id = '" . $this->getUpload_id() . "' ";
        $result = $myCon->query($query);
        if ($result) {
            return true;
        } else {
            throw new Exception(mysqli_error());
        }
        $myCon->closeCon();
    }

}

?>
