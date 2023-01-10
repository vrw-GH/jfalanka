<?php

include_once '../models/credentialCheckClass.php';
include_once 'models/postClass.php';
include_once 'models/subCatClass.php';
include_once '../models/fileUploadDBClass.php';

class mainCatClass {

    private $cat_name;
    private $cat_code;
    private $cat_details;
    private $cat_url_slug;
    private $custom_url;
    private $cat_order;
    private $active;
    private $del_all;

    function getCat_name() {
        return $this->cat_name;
    }

    function getCat_code() {
        return $this->cat_code;
    }

    function getCat_details() {
        return $this->cat_details;
    }

    function getCat_url_slug() {
        return $this->cat_url_slug;
    }

    function getCustom_url() {
        return $this->custom_url;
    }

    function getCat_order() {
        return $this->cat_order;
    }

    function getActive() {
        return $this->active;
    }

    function setCat_name($cat_name) {
        $this->cat_name = $cat_name;
    }

    function setCat_code($cat_code) {
        $this->cat_code = $cat_code;
    }

    function setCat_details($cat_details) {
        $this->cat_details = $cat_details;
    }

    function setCat_url_slug($cat_url_slug) {
        $this->cat_url_slug = $cat_url_slug;
    }

    function setCustom_url($custom_url) {
        $this->custom_url = $custom_url;
    }

    function setCat_order($cat_order) {
        $this->cat_order = $cat_order;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function getDel_all() {
        return $this->del_all;
    }

    function setDel_all($del_all) {
        $this->del_all = $del_all;
    }

    public function checkMainCatName() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT cat_name FROM item_main_category WHERE cat_name='" . $this->getCat_name() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            throw new Exception('Sorry, Main entry name "' . $this->getCat_name() . '" already in the database');
        }
    }

    public function checkMainCatNameOnUpdate() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT cat_name FROM item_main_category WHERE cat_name='" . $this->getCat_name() . "' AND "
                . "cat_code!='" . $this->getCat_code() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            throw new Exception('Sorry, Main entry name "' . $this->getCat_name() . '" already in the database');
        }
    }

    public function checkUrlSlug() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT cat_url_slug FROM item_main_category WHERE cat_url_slug = '" . $this->getCat_url_slug() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function checkUrlSlugOnUpdate() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT cat_url_slug FROM item_main_category WHERE cat_url_slug = '" . $this->getCat_url_slug() . "' "
                . "AND cat_code != '" . $this->getCat_code() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function catOrder() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT cat_order FROM item_main_category ORDER BY cat_order DESC LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = $row['cat_order'] + 1;
            }
        } else {
            /* no recordes */
            $num = 0;
        }
        return $num;
    }

    public function mainCatSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();
        $this->checkMainCatName();

        $a = 1;
        $url_slug_up = $this->getCat_url_slug() . '-0';
        while ($this->checkUrlSlug() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setCat_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "INSERT INTO item_main_category (cat_name, cat_details, cat_url_slug, custom_url, cat_order, "
                . "active) VALUES ('" . $this->getCat_name() . "', '" . $this->getCat_details() . "', "
                . "'" . $this->getCat_url_slug() . "', '" . $this->getCustom_url() . "', "
                . "'" . $this->getCat_order() . "', '" . $this->getActive() . "')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setCat_code($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function mainCatUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $this->checkMainCatNameOnUpdate();

        $a = 1;
        $url_slug_up = $this->getCat_url_slug() . '-0';
        while ($this->checkUrlSlugOnUpdate() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setCat_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "UPDATE item_main_category SET cat_name='" . $this->getCat_name() . "', "
                . "cat_details='" . $this->getCat_details() . "', cat_url_slug ='" . $this->getCat_url_slug() . "', "
                . "custom_url='" . $this->getCustom_url() . "', active='" . $this->getActive() . "' "
                . "WHERE cat_code='" . $this->getCat_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function mainCatChnageOrder() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE item_main_category SET cat_order='" . $this->getCat_order() . "' "
                . "WHERE cat_code='" . $this->getCat_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function mainCatDelete() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        /* delete featured image & gallery images */
        $query_img = "SELECT u.upload_id FROM upload_data u LEFT JOIN item_main_category c ON "
                . "u.upload_ref = c.cat_code WHERE u.upload_type_id='2' AND "
                . "u.upload_ref = '" . $this->getCat_code() . "'";
        $result_img = $myCon->query($query_img);
        $count_img = mysqli_num_rows($result_img);
        if ($count_img > 0) {
            $fileDbObj = new fileUploadDBClass();
            while ($row_img = mysqli_fetch_assoc($result_img)) {
                $fileDbObj->setUpload_id($row_img['upload_id']);
                $fileDbObj->removeImageAndRecordWithID();
            }
        }

        /* load sub category autonumbers */
        $subObj = new subCatClass();

        /* if the check box ticked */
        if ($this->getDel_all()) {
            /* pass value to sub category class */
            $subObj->setDel_all(true);
            /* delete brands */
            $query_brand = "DELETE FROM item_brand WHERE cat_code='" . $this->getCat_code() . "'";
            $result_brand = $myCon->query($query_brand);
            if (!$result_brand) {
                throw new Exception(mysqli_error());
            }
        } else {
            /* pass value to sub category class */
            $subObj->setDel_all(false);

            $query_brand = "UPDATE item_brand SET cat_code=0 WHERE cat_code='" . $this->getCat_code() . "'";
            $result_brand = $myCon->query($query_brand);
            if (!$result_brand) {
                throw new Exception(mysqli_error());
            }
        }
        $query_sub = "SELECT auto_num FROM item_sub_category WHERE cat_code='" . $this->getCat_code() . "'";
        $result_sub = $myCon->query($query_sub);
        /* delete or unlink sub category, post & it's contents */
        while ($row_sub = mysqli_fetch_assoc($result_sub)) {
            $subObj->setAuto_num($row_sub['auto_num']);
            $subObj->subCatDeleteCallFromMainCat();
        }

        /* finally delete the category */
        $query_sub = "DELETE FROM item_main_category WHERE cat_code='" . $this->getCat_code() . "'";
        $result_sub = $myCon->query($query_sub);
        if (!$result_sub) {
            throw new Exception(mysqli_error());
        }
    }

}

?>