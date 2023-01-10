<?php

include_once '../models/credentialCheckClass.php';

class postClass {

    private $post_code;
    private $post_url_slug;
    private $post_name;
    private $post_details;
    private $cat_code;
    private $sub_code;
    private $brand_code;
    private $unit_code;
    private $app_code;
    private $active;
    private $featured;
    private $add_by;
    private $add_date;
    private $upd_by;
    private $upd_date;
    private $d_id;
    private $c_id;
    private $selling_price;
    private $off_value;
    private $off_presentage;
    private $init_stock;
    private $now_stock;
    private $terms;
    private $policies;
    private $data1;
    private $data2;
    private $data3;

    function getPost_code() {
        return $this->post_code;
    }

    function getPost_url_slug() {
        return $this->post_url_slug;
    }

    function getPost_name() {
        return $this->post_name;
    }

    function getPost_details() {
        return $this->post_details;
    }

    function getCat_code() {
        return $this->cat_code;
    }

    function getSub_code() {
        return $this->sub_code;
    }

    function getBrand_code() {
        return $this->brand_code;
    }

    function getUnit_code() {
        return $this->unit_code;
    }

    function getApp_code() {
        return $this->app_code;
    }

    function getActive() {
        return $this->active;
    }

    function getFeatured() {
        return $this->featured;
    }

    function getAdd_by() {
        return $this->add_by;
    }

    function getAdd_date() {
        return $this->add_date;
    }

    function getUpd_by() {
        return $this->upd_by;
    }

    function getUpd_date() {
        return $this->upd_date;
    }

    function getD_id() {
        return $this->d_id;
    }

    function getC_id() {
        return $this->c_id;
    }

    function getSelling_price() {
        return $this->selling_price;
    }

    function getOff_value() {
        return $this->off_value;
    }

    function getOff_presentage() {
        return $this->off_presentage;
    }

    function getInit_stock() {
        return $this->init_stock;
    }

    function getNow_stock() {
        return $this->now_stock;
    }

    function getTerms() {
        return $this->terms;
    }

    function getPolicies() {
        return $this->policies;
    }

    function getData1() {
        return $this->data1;
    }

    function getData2() {
        return $this->data2;
    }

    function getData3() {
        return $this->data3;
    }

    function setPost_code($post_code) {
        $this->post_code = $post_code;
    }

    function setPost_url_slug($post_url_slug) {
        $this->post_url_slug = $post_url_slug;
    }

    function setPost_name($post_name) {
        $this->post_name = $post_name;
    }

    function setPost_details($post_details) {
        $this->post_details = $post_details;
    }

    function setCat_code($cat_code) {
        $this->cat_code = $cat_code;
    }

    function setSub_code($sub_code) {
        $this->sub_code = $sub_code;
    }

    function setBrand_code($brand_code) {
        $this->brand_code = $brand_code;
    }

    function setUnit_code($unit_code) {
        $this->unit_code = $unit_code;
    }

    function setApp_code($app_code) {
        $this->app_code = $app_code;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function setFeatured($featured) {
        $this->featured = $featured;
    }

    function setAdd_by($add_by) {
        $this->add_by = $add_by;
    }

    function setAdd_date($add_date) {
        $this->add_date = $add_date;
    }

    function setUpd_by($upd_by) {
        $this->upd_by = $upd_by;
    }

    function setUpd_date($upd_date) {
        $this->upd_date = $upd_date;
    }

    function setD_id($d_id) {
        $this->d_id = $d_id;
    }

    function setC_id($c_id) {
        $this->c_id = $c_id;
    }

    function setSelling_price($selling_price) {
        $this->selling_price = $selling_price;
    }

    function setOff_value($off_value) {
        $this->off_value = $off_value;
    }

    function setOff_presentage($off_presentage) {
        $this->off_presentage = $off_presentage;
    }

    function setInit_stock($init_stock) {
        $this->init_stock = $init_stock;
    }

    function setNow_stock($now_stock) {
        $this->now_stock = $now_stock;
    }

    function setTerms($terms) {
        $this->terms = $terms;
    }

    function setPolicies($policies) {
        $this->policies = $policies;
    }

    function setData1($data1) {
        $this->data1 = $data1;
    }

    function setData2($data2) {
        $this->data2 = $data2;
    }

    function setData3($data3) {
        $this->data3 = $data3;
    }

    public function postSubCatSave() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "INSERT INTO posts_sub_cat (post_code, cat_code, sub_code) VALUES "
                . "('" . $this->getPost_code() . "', '" . $this->getCat_code() . "', "
                . "'" . $this->getSub_code() . "')";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function postSubCatdelete() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM posts_sub_cat WHERE cat_code='" . $this->getCat_code() . "' AND "
                . "sub_code = '" . $this->getSub_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }
    public function postSubCatdeleteAll() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM posts_sub_cat WHERE post_code='" . $this->getPost_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function checkUrlSlug() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT post_url_slug FROM posts WHERE post_url_slug = '" . $this->getPost_url_slug() . "'";
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
        $query = "SELECT post_url_slug FROM posts WHERE post_url_slug = '" . $this->getPost_url_slug() . "' "
                . "AND post_code != '" . $this->getPost_code() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function postSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $a = 1;
        $url_slug_up = $this->getPost_url_slug() . '-0';
        while ($this->checkUrlSlug() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setPost_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "INSERT INTO posts (post_name, post_url_slug, post_details, brand_code, "
                . "unit_code, app_code, active, featured, add_by, add_date, d_id, c_id, "
                . "selling_price, off_value, off_presentage, init_stock, now_stock, "
                . "terms, policies, data1, data2, data3) VALUES "
                . "('" . $this->getPost_name() . "', '" . $this->getPost_url_slug() . "' , "
                . "'" . $this->getPost_details() . "', '" . $this->getBrand_code() . "', "
                . "'" . $this->getUnit_code() . "', '" . $this->getApp_code() . "', "
                . "'" . $this->getActive() . "', '" . $this->getFeatured() . "', "
                . "'" . $this->getAdd_by() . "', '" . $this->getAdd_date() . "', "
                . "'" . $this->getD_id() . "', '" . $this->getC_id() . "', "
                . "'" . $this->getSelling_price() . "', '" . $this->getOff_value() . "', "
                . "'" . $this->getOff_presentage() . "', '" . $this->getInit_stock() . "', "
                . "'" . $this->getNow_stock() . "', '" . $this->getTerms() . "', "
                . "'" . $this->getPolicies() . "', '" . $this->getData1() . "', "
                . "'" . $this->getData2() . "', '" . $this->getData3() . "')";
        $result = $myCon->query($query);

        if ($result) {
            $this->setPost_code($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
        $myCon->closeCon();
    }

    public function postUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $a = 1;
        $url_slug_up = $this->getPost_url_slug() . '-0';
        while ($this->checkUrlSlugOnUpdate() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setPost_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "UPDATE posts SET post_name='" . $this->getPost_name() . "', "
                . "post_url_slug='" . $this->getPost_url_slug() . "', post_details='" . $this->getPost_details() . "', "
                . "brand_code='" . $this->getBrand_code() . "', unit_code='" . $this->getUnit_code() . "', "
                . "app_code='" . $this->getApp_code() . "', active='" . $this->getActive() . "', "
                . "featured='" . $this->getFeatured() . "', upd_by='" . $this->getUpd_by() . "', "
                . "upd_date='" . $this->getUpd_date() . "', d_id='" . $this->getD_id() . "', c_id='" . $this->getC_id() . "', "
                . "selling_price='" . $this->getSelling_price() . "', off_value='" . $this->getOff_value() . "', "
                . "off_presentage='" . $this->getOff_presentage() . "', init_stock='" . $this->getInit_stock() . "', "
                . "now_stock='" . $this->getNow_stock() . "', terms='" . $this->getTerms() . "', "
                . "policies='" . $this->getPolicies() . "', data1='" . $this->getData1() . "', "
                . "data2='" . $this->getData2() . "', data3='" . $this->getData3() . "' "
                . "WHERE post_code='" . $this->getPost_code() . "'";
        $result = $myCon->query($query);

        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
        $myCon->closeCon();
    }

    public function postDisplay() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE posts SET active='" . $this->getActive() . "' WHERE post_code='" . $this->getPost_code() . "'";
        $result = $myCon->query($query);

        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
        $myCon->closeCon();
    }

    public function postDelete() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM posts WHERE post_code='" . $this->getPost_code() . "'";
        $result = $myCon->query($query);
        if ($result) {
            /* delete post images */
            $query = "SELECT * FROM upload_data WHERE upload_ref = '" . $this->getPost_code() . "' "
                    . "AND (upload_type_id >=6 AND upload_type_id <=9)";
            $result = $myCon->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            /* delete image records */
            $query = "DELETE FROM upload_data WHERE upload_ref = '" . $this->getPost_code() . "' "
                    . "AND (upload_type_id >=6 AND upload_type_id <=9)";
            $result = $myCon->query($query);

            /* delete post subcat data */
            $query = "DELETE FROM posts_sub_cat WHERE post_code = '" . $this->getPost_code() . "'";
            $result = $myCon->query($query);
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
        $myCon->closeCon();
    }

}
