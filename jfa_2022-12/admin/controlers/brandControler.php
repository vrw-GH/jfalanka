<?php

if (file_exists('admin_config.php')) {
    include_once 'admin_config.php';
} else if (file_exists('../admin_config.php')) {
    include_once '../admin_config.php';
} else {
    include_once '../../admin_config.php';
}

/* Check posting data ------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'models/brandClass.php';
    include_once 'models/urlSlugClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['brand_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter brand name</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $brandObj = new brandClass();
            $urlSlug = new urlSlugClass();

            $cat_code = $_POST['cat_code'];
            $brand_name = $myCon->escapeString($_POST['brand_name']);
            $brand_url_slug = $urlSlug->urlSlug($brand_name);
            /* description & custom url */
            if ($website['descp']['brand_display'] == true) {
                $brand_details = $myCon->escapeString($_POST['brand_details']);
            } else {
                $brand_details = null;
            }
            if ($website['custom_url']['brand_display'] == true) {
                $brand_custom_url = $myCon->escapeString($_POST['brand_custom_url']);
            } else {
                $brand_custom_url = null;
            }
            /* end */

            $brandObj->setCat_code($cat_code);
            $brandObj->setBrand_name($brand_name);
            $brandObj->setBrand_url_slug($brand_url_slug);
            $brandObj->setBrand_custom_url($brand_custom_url);
            $brandObj->setBrand_details($brand_details);

            $myCon->closeCon();
            try {
                $brandObj->brandSave();

                if (isset($_FILES['brand_img']['name']) && $_FILES['brand_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->imageCropped($_FILES['brand_img']['name'], $_FILES['brand_img']['tmp_name'], '../uploads/', $website['images'] ['brand_width'], $website['images'] ['brand_height'], false, null, null, false);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($brandObj->getBrand_code());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(4);/** Brand Image */
                    $fileDbObj->uploadFile();
                }

                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New brand has been Added!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['brand_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter brand name</div>');
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$("#mm").load("item_brands_edit.php", {brand_code: ' . $_POST['brand_code'] . '});'
            . ' }, 500);});</script>';
        } else if (trim($_POST['brand_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Brand code is missing</div>');
            /* No jquery reload */
        } else {

            $myCon = new dbConfig();
            $myCon->connect();
            $brandObj = new brandClass();
            $urlSlug = new urlSlugClass();

            $brand_name = $_POST['brand_name'];
            $cat_code = $_POST['cat_code'];
            $brand_code = $_POST['brand_code'];
            $active = $_POST['active'];
            $brand_name = $myCon->escapeString($brand_name);
            $brand_url_slug = $urlSlug->urlSlug($brand_name);

            /* description & custom url */
            if ($website['descp']['brand_display'] == true) {
                $brand_details = $myCon->escapeString($_POST['brand_details']);
            } else {
                $brand_details = null;
            }
            if ($website['custom_url']['brand_display'] == true) {
                $brand_custom_url = $myCon->escapeString($_POST['brand_custom_url']);
            } else {
                $brand_custom_url = null;
            }
            /* end */

            $brandObj->setBrand_name($brand_name);
            $brandObj->setCat_code($cat_code);
            $brandObj->setBrand_code($brand_code);
            $brandObj->setBrand_url_slug($brand_url_slug);
            $brandObj->setBrand_custom_url($brand_custom_url);
            $brandObj->setBrand_details($brand_details);
            $brandObj->setActive($active);

            $myCon->closeCon();
            try {
                $brandObj->brandUpdate();

                /* delete image on tick. this part should come first, because this may delete updated image as well */
                if (isset($_POST['brand_img_delete']) && $_POST['brand_img_delete'] == 'true') {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    try {
                        $fileDbObj->removeFileAndRecordWithID();
                        echo('<div class="alert alert-success" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>Brand image has been deleted!</div>');
                    } catch (Exception $ex) {
                        echo('<div class="alert alert-danger" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                    }
                }

                if (isset($_FILES['brand_img']['name']) && $_FILES['brand_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->imageCropped($_FILES['brand_img']['name'], $_FILES['brand_img']['tmp_name'], '../uploads/', $website['images'] ['brand_width'], $website['images'] ['brand_height'], false, null, null, false);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($brandObj->getBrand_code());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(4);/** Brand Image */
                    /* If file Uploaded */
                    if ($fileDbObj->checkFeatured()) {
                        /* Method should be called after the file upload */
                        $fileDbObj->updateFile();
                    } else {
                        $fileDbObj->uploadFile();
                    }
                }

                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Brand "' . $brandObj->getBrand_name() . '" has been Updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                echo '<script type="text/javascript">$(function(){setTimeout(function(){'
                . '$("#mm").load("item_brands_edit.php", {brand_code: ' . $_POST['brand_code'] . '});'
                . ' }, 500);});</script>';
            }
        }
        /* ------------------ Delete Record -------------------- */
    } else if ($_POST['action'] == 'delete') {
        
    }
}
/* end of post -------------------------------------------------------------------- */
?>