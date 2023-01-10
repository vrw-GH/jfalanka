<?php

/* Check posting data ------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'models/seoClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/encryption.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'update') {
        if (trim($_POST['seo_id']) == null || trim($_POST['seo_title']) == null ||
                trim($_POST['seo_dscp']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $seoObj = new seoClass();
            $encObj = new encryption();

            $seo_id = $_POST['seo_id'];
            $seo_title = $myCon->escapeString($_POST['seo_title']);
            $seo_dscp = $myCon->escapeString($_POST['seo_dscp']);
            $seo_keywords = $myCon->escapeString($_POST['seo_keywords']);
            if ($encObj->decode(SITE_SEO_KEY) == 'ultimate' || $encObj->decode(SITE_SEO_KEY) == 'enterprise') {
                $fb_id = trim($_POST['fb_id']);
                $og_type = $_POST['og_type'];
                $og_img = trim($_POST['og_img']);
                $og_site_name = $myCon->escapeString($_POST['og_site_name']);
                $og_tw_dscp = $myCon->escapeString($_POST['og_tw_dscp']);
                $tw_site = trim($_POST['tw_site']);
                $tw_creator = trim($_POST['tw_creator']);
                $tw_img = trim($_POST['tw_img']);
                $google_publisher = trim($_POST['google_publisher']);
                $google_analytics = $myCon->escapeString($_POST['google_analytics']);
            } else {
                $fb_id = null;
                $og_type = null;
                $og_img = null;
                $og_site_name = null;
                $og_tw_dscp = null;
                $tw_site = null;
                $tw_creator = null;
                $tw_img = null;
                $google_publisher = null;
                $google_analytics = null;
            }

            $seoObj->setFb_id($fb_id);
            $seoObj->setGoogle_analytics($google_analytics);
            $seoObj->setGoogle_publisher($google_publisher);
            $seoObj->setOg_img($og_img);
            $seoObj->setOg_site_name($og_site_name);
            $seoObj->setOg_tw_dscp($og_tw_dscp);
            $seoObj->setOg_type($og_type);
            $seoObj->setSeo_dscp($seo_dscp);
            $seoObj->setSeo_id($seo_id);
            $seoObj->setSeo_keywords($seo_keywords);
            $seoObj->setSeo_title($seo_title);
            $seoObj->setTw_creator($tw_creator);
            $seoObj->setTw_img($tw_img);
            $seoObj->setTw_site($tw_site);

            $myCon->closeCon();

            try {
                $seoObj->updateSEO();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>SEO has been Updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database DELETE -------------------- */
    }
}
/* end of post -------------------------------------------------------------------- */
?>