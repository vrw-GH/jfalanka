<?php

include_once '../models/credentialCheckClass.php';

class pagesClass {

    private $page_id;
    private $page_title;
    private $page_content;
    private $page_url_slug;
    private $page_custom_url;
    private $gallery_type;
    private $gallery_name;
    private $add_date;
    private $add_by;
    private $upd_date;
    private $upd_by;
    private $page_order;
    private $active;

    function getPage_id() {
        return $this->page_id;
    }

    function getPage_title() {
        return $this->page_title;
    }

    function getPage_content() {
        return $this->page_content;
    }

    function getPage_url_slug() {
        return $this->page_url_slug;
    }

    function getPage_custom_url() {
        return $this->page_custom_url;
    }

    function getGallery_type() {
        return $this->gallery_type;
    }

    function getGallery_name() {
        return $this->gallery_name;
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

    function getPage_order() {
        return $this->page_order;
    }

    function getActive() {
        return $this->active;
    }

    function setPage_id($page_id) {
        $this->page_id = $page_id;
    }

    function setPage_title($page_title) {
        $this->page_title = $page_title;
    }

    function setPage_content($page_content) {
        $this->page_content = $page_content;
    }

    function setPage_url_slug($page_url_slug) {
        $this->page_url_slug = $page_url_slug;
    }

    function setPage_custom_url($page_custom_url) {
        $this->page_custom_url = $page_custom_url;
    }

    function setGallery_type($gallery_type) {
        $this->gallery_type = $gallery_type;
    }

    function setGallery_name($gallery_name) {
        $this->gallery_name = $gallery_name;
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

    function setPage_order($page_order) {
        $this->page_order = $page_order;
    }

    function setActive($active) {
        $this->active = $active;
    }

    public function checkUrlSlug() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT page_url_slug FROM pages WHERE page_url_slug = '" . $this->getPage_url_slug() . "'";
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
        $query = "SELECT page_url_slug FROM pages WHERE page_url_slug = '" . $this->getPage_url_slug() . "' "
                . "AND page_id != '" . $this->getPage_id() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getPageOrder() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT page_id FROM pages ORDER BY page_id DESC LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = $row['page_id'] + 1;
            }
        } else {
            /* no recordes */
            $num = 1;
        }
        return $num;
    }

    public function pageSave() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $a = 1;
        $url_slug_up = $this->getPage_url_slug() . '-0';
        while ($this->checkUrlSlug() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setPage_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "INSERT INTO pages (page_title, page_content, page_url_slug, page_custom_url, "
                . "gallery_type, gallery_name, add_date, add_by, page_order, active) VALUES "
                . "('" . $this->getPage_title() . "', '" . $this->getPage_content() . "', "
                . "'" . $this->getPage_url_slug() . "', '" . $this->getPage_custom_url() . "', "
                . "'" . $this->getGallery_type() . "', '" . $this->getGallery_name() . "', "
                . "'" . $this->getAdd_date() . "', '" . $this->getAdd_by() . "', "
                . "'" . $this->getPageOrder() . "', '" . $this->getActive() . "')";
        $result = $myCon->query($query);
        if ($result) {
            $this->setPage_id($myCon->mysqliInsertId());
        } else {
            throw new Exception(mysqli_error());
        }
    }

    public function pageUpdate() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $a = 1;
        $url_slug_up = $this->getPage_url_slug() . '-0';
        while ($this->checkUrlSlugOnUpdate() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setPage_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "UPDATE pages SET page_title='" . $this->getPage_title() . "', "
                . "page_content='" . $this->getPage_content() . "', page_url_slug ='" . $this->getPage_url_slug() . "', "
                . "page_custom_url = '" . $this->getPage_custom_url() . "', gallery_type = '" . $this->getGallery_type() . "', "
                . "gallery_name = '" . $this->getGallery_name() . "', upd_date = '" . $this->getUpd_date() . "', "
                . "upd_by = '" . $this->getUpd_by() . "', active='" . $this->getActive() . "' "
                . "WHERE page_id='" . $this->getPage_id() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function pageDelete() {
        $creObj = new credentialCheckClass();
        $creObj->checkAdminCredential();

        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM pages WHERE page_id='" . $this->getPage_id() . "'";
        $result = $myCon->query($query);
        if ($result) {
            /* delete post images */
            $query = "SELECT * FROM upload_data WHERE upload_ref = '" . $this->getPage_id() . "' "
                    . "AND upload_type_id = 11";
            $result = $myCon->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                if (file_exists('../uploads/' . $row['upload_path'])) {
                    unlink('../uploads/' . $row['upload_path']);
                }
                if (file_exists('../uploads/thumbs/' . $row['upload_path'])) {
                    unlink('../uploads/thumbs/' . $row['upload_path']);
                }
            }
            /* delete page image records */
            $query = "DELETE FROM upload_data WHERE upload_ref = '" . $this->getPage_id() . "' "
                    . "AND upload_type_id = 11";
            $result = $myCon->query($query);
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

}
