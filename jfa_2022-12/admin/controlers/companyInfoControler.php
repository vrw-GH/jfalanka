<?php

/* Check posting data ------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'models/companyInfoClass.php';
    include_once '../models/dbConfig.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'update') {
        if (trim($_POST['comp_name']) == null || trim($_POST['comp_prv_name']) == null ||
                trim($_POST['comp_address']) == null || trim($_POST['comp_hotline']) == null ||
                trim($_POST['comp_email']) == null || trim($_POST['comp_domain']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $compObj = new companyInfoClass();
            $comp_prv_name = $myCon->escapeString($_POST['comp_prv_name']);
            $comp_name = $myCon->escapeString($_POST['comp_name']);
            $comp_web_title = $myCon->escapeString($_POST['comp_web_title']);
            $comp_address = $myCon->escapeString($_POST['comp_address']);
            $comp_hotline = $_POST['comp_hotline'];
            $comp_phone = $_POST['comp_phone'];
            $comp_fax = $_POST['comp_fax'];
            $comp_email = trim($_POST['comp_email']);
            $comp_email2 = $_POST['comp_email2'];
            $comp_domain = trim($_POST['comp_domain']);
            $comp_google_map = $_POST['comp_google_map'];
            $comp_google_map_size = $_POST['comp_google_map_size'];
            $comp_skype = $_POST['comp_skype'];
            $comp_fb = $_POST['comp_fb'];
            $comp_tw = $_POST['comp_tw'];
            $comp_gplus = $_POST['comp_gplus'];
            $comp_yt = $_POST['comp_yt'];

            $compObj->setComp_address($comp_address);
            $compObj->setComp_domain($comp_domain);
            $compObj->setComp_email($comp_email);
            $compObj->setComp_email2($comp_email2);
            $compObj->setComp_fax($comp_fax);
            $compObj->setComp_google_map($comp_google_map);
            $compObj->setComp_hotline($comp_hotline);
            $compObj->setComp_name($comp_name);
            $compObj->setComp_web_title($comp_web_title);
            $compObj->setComp_phone($comp_phone);
            $compObj->setComp_prv_name($comp_prv_name);
            $compObj->setComp_google_map_size($comp_google_map_size);
            $compObj->setComp_skype($comp_skype);
            $compObj->setComp_fb($comp_fb);
            $compObj->setComp_tw($comp_tw);
            $compObj->setComp_gplus($comp_gplus);
            $compObj->setComp_yt($comp_yt);

            $myCon->closeCon();

            try {
                $compObj->updateCompany();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Company details has been Updated!</div>');
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