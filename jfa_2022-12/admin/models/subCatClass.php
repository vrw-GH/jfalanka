<?php

include_once '../models/credentialCheckClass.php';
include_once 'models/postClass.php';
include_once '../models/fileUploadDBClass.php';

class subCatClass {

    private $auto_num;
    private $sub_code;
    private $cat_code;
    private $sub_name;
    private $sub_details;
    private $sub_url_slug;
    private $sub_order;
    private $active;
    private $del_all;

    function getAuto_num() {
        return $this->auto_num;
    }

    function getSub_code() {
        return $this->sub_code;
    }

    function getCat_code() {
        return $this->cat_code;
    }

    function getSub_name() {
        return $this->sub_name;
    }

    function getSub_details() {
        return $this->sub_details;
    }

    function getSub_url_slug() {
        return $this->sub_url_slug;
    }

    function getSub_order() {
        return $this->sub_order;
    }

    function getActive() {
        return $this->active;
    }

    function getDel_all() {
        return $this->del_all;
    }

    function setAuto_num($auto_num) {
        $this->auto_num = $auto_num;
    }

    function setSub_code($sub_code) {
        $this->sub_code = $sub_code;
    }

    function setCat_code($cat_code) {
        $this->cat_code = $cat_code;
    }

    function setSub_name($sub_name) {
        $this->sub_name = $sub_name;
    }

    function setSub_details($sub_details) {
        $this->sub_details = $sub_details;
    }

    function setSub_url_slug($sub_url_slug) {
        $this->sub_url_slug = $sub_url_slug;
    }

    function setSub_order($sub_order) {
        $this->sub_order = $sub_order;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function setDel_all($del_all) {
        $this->del_all = $del_all;
    }

    public function checkSubCatSave() {
        $myCon = new dbConfig();
        $myCon->connect();

        $querycat = "SELECT sub_name FROM item_sub_category WHERE "
                . "sub_name='" . $this->getSub_name() . "' 
            AND cat_code='" . $this->getCat_code() . "'";
        $resultcat = $myCon->query($querycat);
        if (mysqli_num_rows($resultcat) >= 1) {
            throw new Exception('Sorry, Sub entry name "' . $this->getSub_name() . '" already in the database for the selected main category');
        } else {
            return true;
        }
    }

    public function subOrder() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT sub_order FROM item_sub_category ORDER BY sub_order DESC LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = $row['sub_order'] + 1;
            }
        } else {
            /* no recordes */
            $num = 0;
        }
        return $num;
    }

    public function generateSubCode() {
        $myCon = new dbConfig();
        $myCon->connect();

        $num = 0;
        $query = "SELECT sub_code FROM item_sub_category WHERE "
                . "cat_code='" . $this->getCat_code() . "' ORDER BY sub_code DESC LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = $row['sub_code'] + 1;
            }
        } else {
            /* no recordes */
            $num = 1;
        }
        return $num;
    }

    public function checkUrlSlug() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT sub_url_slug FROM item_sub_category WHERE sub_url_slug = '" . $this->getSub_url_slug() . "' "
                . "AND cat_code != '" . $this->getCat_code() . "'";
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
        $query = "SELECT sub_url_slug FROM item_sub_category WHERE sub_url_slug = '" . $this->getSub_url_slug() . "' "
                . "AND auto_num != '" . $this->getAuto_num() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function subCatSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkLoginStatus();

        $myCon = new dbConfig();
        $myCon->connect();

        $this->setSub_code($this->generateSubCode());

        $a = 1;
        $url_slug_up = $this->getSub_url_slug() . '-0';
        while ($this->checkUrlSlug() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setSub_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "INSERT INTO item_sub_category (sub_code, cat_code, sub_name, sub_details, sub_url_slug, "
                . "sub_order, active) VALUES ('" . $this->getSub_code() . "', '" . $this->getCat_code() . "', "
                . "'" . $this->getSub_name() . "', '" . $this->getSub_details() . "', '" . $this->getSub_url_slug() . "', "
                . "'" . $this->getSub_order() . "', '1')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setAuto_num($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function subCatUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $a = 1;
        $url_slug_up = $this->getSub_url_slug() . '-0';
        while ($this->checkUrlSlugOnUpdate() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setSub_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "UPDATE item_sub_category SET sub_name ='" . $this->getSub_name() . "', "
                . "sub_url_slug = '" . $this->getSub_url_slug() . "', sub_details='" . $this->getSub_details() . "', "
                . "active = '" . $this->getActive() . "' "
                . "WHERE auto_num='" . $this->getAuto_num() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function subCatUpdateWithCatCode() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $this->setSub_code($this->generateSubCode());

        $a = 1;
        $url_slug_up = $this->getSub_url_slug() . '-0';
        while ($this->checkUrlSlugOnUpdate() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setSub_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "UPDATE item_sub_category SET sub_code='" . $this->getSub_code() . "', "
                . "cat_code='" . $this->getCat_code() . "', sub_name ='" . $this->getSub_name() . "', "
                . "sub_url_slug = '" . $this->getSub_url_slug() . "', active='" . $this->getActive() . "' "
                . "WHERE auto_num='" . $this->getAuto_num() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function subCatChnageOrder() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE item_sub_category SET sub_order='" . $this->getSub_order() . "' "
                . "WHERE auto_num='" . $this->getAuto_num() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function subCatDelete() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        /* get the cat_code & sub_code */
        $query = "SELECT cat_code, sub_code FROM item_sub_category WHERE auto_num='" . $this->getAuto_num() . "'";
        $result = $myCon->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $this->setCat_code($row['cat_code']);
            $this->setSub_code($row['sub_code']);
        }
        /* delete featured image & gallery images */
        $query_img = "SELECT u.upload_id FROM upload_data u LEFT JOIN item_sub_category s ON "
                . "u.upload_ref = s.auto_num WHERE u.upload_type_id='3' AND "
                . "u.upload_ref = '" . $this->getAuto_num() . "'";
        $result_img = $myCon->query($query_img);
        $count_img = mysqli_num_rows($result_img);
        if ($count_img > 0) {
            $fileDbObj = new fileUploadDBClass();
            while ($row_img = mysqli_fetch_assoc($result_img)) {
                $fileDbObj->setUpload_id($row_img['upload_id']);
                $fileDbObj->removeImageAndRecordWithID();
            }
        }

        /* load post & it's contents */
        $post_obj = new postClass();

        $query_post = "SELECT post_code FROM posts_sub_cat WHERE cat_code='" . $this->getCat_code() . "' "
                . "AND sub_code='" . $this->getSub_code() . "'";
        $result_post = $myCon->query($query_post);
        /* if the check box ticked */
        if ($this->getDel_all()) {
            /* delete post & it's contents */
            while ($row_post = mysqli_fetch_assoc($result_post)) {
                $post_obj->setPost_code($row_post['post_code']);
                $post_obj->postDelete();
            }
        } else {
            /* unlink post & it's contents */
            while ($row_post = mysqli_fetch_assoc($result_post)) {
                $post_obj->setPost_code($row_post['post_code']);
                $post_obj->postSubCatdeleteAll();
                $post_obj->setCat_code(0);
                $post_obj->setSub_code(0);
                $post_obj->postSubCatSave();
            }
        }

        /* finally delete the sub category */
        $query_sub = "DELETE FROM item_sub_category WHERE auto_num='" . $this->getAuto_num() . "'";
        $result_sub = $myCon->query($query_sub);
        if (!$result_sub) {
            throw new Exception(mysqli_error());
        }
    }

    public function subCatDeleteCallFromMainCat() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        /* get the cat_code & sub_code */
        $query = "SELECT cat_code, sub_code FROM item_sub_category WHERE auto_num='" . $this->getAuto_num() . "'";
        $result = $myCon->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $this->setCat_code($row['cat_code']);
            $this->setSub_code($row['sub_code']);
        }

        if ($this->getDel_all()) {
            /* delete featured image & gallery images */
            $query_img = "SELECT u.upload_id FROM upload_data u LEFT JOIN item_sub_category s ON "
                    . "u.upload_ref = s.auto_num WHERE u.upload_type_id='3' AND "
                    . "u.upload_ref = '" . $this->getAuto_num() . "'";
            $result_img = $myCon->query($query_img);
            $count_img = mysqli_num_rows($result_img);
            if ($count_img > 0) {
                $fileDbObj = new fileUploadDBClass();
                while ($row_img = mysqli_fetch_assoc($result_img)) {
                    $fileDbObj->setUpload_id($row_img['upload_id']);
                    $fileDbObj->removeImageAndRecordWithID();
                }
            }
        }
        /* load post & it's contents */
        $post_obj = new postClass();

        $query_post = "SELECT post_code FROM posts_sub_cat WHERE cat_code='" . $this->getCat_code() . "' "
                . "AND sub_code='" . $this->getSub_code() . "'";
        $result_post = $myCon->query($query_post);
        /* if the check box ticked */
        if ($this->getDel_all()) {
            /* delete post & it's contents */
            while ($row_post = mysqli_fetch_assoc($result_post)) {
                $post_obj->setPost_code($row_post['post_code']);
                $post_obj->postDelete();
            }
            /* finally delete the sub category */
            $query_sub = "DELETE FROM item_sub_category WHERE auto_num='" . $this->getAuto_num() . "'";
            $result_sub = $myCon->query($query_sub);
            if (!$result_sub) {
                throw new Exception(mysqli_error());
            }
        } else {
            /* unlink post & it's contents */
            while ($row_post = mysqli_fetch_assoc($result_post)) {
                $post_obj->setPost_code($row_post['post_code']);
                $post_obj->postSubCatdeleteAll();
                $post_obj->setCat_code(0);
                $post_obj->setSub_code(0);
                $post_obj->postSubCatSave();
            }
            /* unlink sub categories */
            $query_sub = "UPDATE item_sub_category SET cat_code = 0, sub_code = 0 "
                    . "WHERE cat_code='" . $this->getCat_code() . "'";
            $result_sub = $myCon->query($query_sub);
            if (!$result_sub) {
                throw new Exception(mysqli_error());
            }
        }
    }

}

?>