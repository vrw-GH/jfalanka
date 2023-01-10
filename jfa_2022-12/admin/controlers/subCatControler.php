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
    include_once 'models/subCatClass.php';
    include_once 'models/urlSlugClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['sub_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter main category name</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $subCatObj = new subCatClass();
            $urlSlug = new urlSlugClass();

            $cat_code = $_POST['cat_code'];
            $sub_name = $myCon->escapeString($_POST['sub_name']);
            $subCatObj->setCat_code($cat_code);
            $subCatObj->setSub_name($sub_name);

            if ($website['descp']['sub_entry'] == true) {
                $sub_details = $myCon->escapeString($_POST['sub_details']);
            } else {
                $sub_details = null;
            }
            $sub_url_slug = $urlSlug->urlSlug($sub_name);

            $subCatObj->setSub_details($sub_details);
            $subCatObj->setSub_url_slug($sub_url_slug);
            $subCatObj->setSub_order($subCatObj->subOrder());

            $myCon->closeCon();
            try {
                $subCatObj->subCatSave();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New sub category has been Added!</div>');

                if (isset($_FILES['sub_img']['name']) && $_FILES['sub_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    /* Image crop options */
                    if ($_POST['sub_img_crop'] == 'both') {
                        $fileObj->imageCropped($_FILES['sub_img']['name'], $_FILES['sub_img']['tmp_name'], '../uploads/', $website['images'] ['sub_entry_width'], $website['images'] ['sub_entry_height'], false, null, null, false);
                    } else {
                        $fileObj->singleImageUpload($_FILES['sub_img']['name'], $_FILES['sub_img']['tmp_name'], '../uploads/', 'any', 'any');
                    }

                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($subCatObj->getAuto_num());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(3);/** Sub Cat Image */
                    $fileDbObj->uploadFile();
                    echo('<div class="alert alert-success" role="alert">  '
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span></button>New image has been Added!</div>');
                }
                unset($_POST);
            } catch (Exception $ex) {
                unset($_POST);
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['sub_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter sub category name</div>');
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$(".lastviewer").load("item_sub_category_filter.php", {cat_code: ' . $_POST['pre_cat_code'] . '});'
            . ' }, 500);});</script>';
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$("#mm").load("item_sub_category_edit.php", {auto_num: ' . $_POST['auto_num'] . '});'
            . ' }, 500);});</script>';
        } else if (trim($_POST['pre_cat_code']) == null || trim($_POST['cat_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Entry code is missing : main/sub</div>');
            /* No jquery reload */
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $subCatObj = new subCatClass();
            $urlSlug = new urlSlugClass();

            $auto_num = $_POST['auto_num'];
            $sub_name = $_POST['sub_name'];
            $cat_code = $_POST['cat_code'];
            if ($website['descp']['sub_entry'] == true) {
                $sub_details = $myCon->escapeString($_POST['sub_details']);
            } else {
                $sub_details = null;
            }
            $sub_url_slug = $urlSlug->urlSlug($sub_name);
            $pre_cat_code = $_POST['pre_cat_code'];
            $active = $_POST['active'];

            $sub_name = $myCon->escapeString($sub_name);
            $subCatObj->setSub_name($sub_name);
            $subCatObj->setAuto_num($auto_num);
            $subCatObj->setCat_code($cat_code);
            $subCatObj->setSub_details($sub_details);
            $subCatObj->setSub_url_slug($sub_url_slug);
            $subCatObj->setActive($active);

            $myCon->closeCon();
            try {
                if ($pre_cat_code == $cat_code) {
                    $subCatObj->subCatUpdate();
                } else {
                    $subCatObj->subCatUpdateWithCatCode();
                }
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Sub entry "' . $subCatObj->getSub_name() . '" has been Updated!</div>');

                /* delete image on tick. this part should come first, because this may delete updated image as well */
                if (isset($_POST['sub_img_delete']) && $_POST['sub_img_delete'] == 'true') {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    try {
                        $fileDbObj->removeFileAndRecordWithID();
                        echo('<div class="alert alert-success" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>Sub Category image has been deleted!</div>');
                    } catch (Exception $ex) {
                        echo('<div class="alert alert-danger" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                    }
                }

                if (isset($_FILES['sub_img']['name']) && $_FILES['sub_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    /* Image crop options */
                    if ($_POST['sub_img_crop'] == 'both') {
                        $fileObj->imageCropped($_FILES['sub_img']['name'], $_FILES['sub_img']['tmp_name'], '../uploads/', $website['images'] ['sub_entry_width'], $website['images'] ['sub_entry_height'], false, null, null, false);
                    } else {
                        $fileObj->singleImageUpload($_FILES['sub_img']['name'], $_FILES['sub_img']['tmp_name'], '../uploads/', 'any', 'any');
                    }

                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($subCatObj->getAuto_num());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(3);/** Sub Cat Image */
                    /* If file Uploaded */
                    if ($fileDbObj->checkFeatured()) {
                        /* Method should be called after the file upload */
                        $fileDbObj->updateFile();
                    } else {
                        $fileDbObj->uploadFile();
                    }
                }
                unset($_POST);


                echo '<script type="text/javascript">$(function(){setTimeout(function(){'
                . '$(".lastviewer").load("item_sub_category_filter.php", {cat_code: ' . $pre_cat_code . '});'
                . ' }, 500);});</script>';
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                echo '<script type="text/javascript">$(function(){setTimeout(function(){'
                . '$(".lastviewer").load("item_sub_category_filter.php", {cat_code: ' . $_POST['pre_cat_code'] . '});'
                . ' }, 500);});</script>';
                echo '<script type="text/javascript">$(function(){setTimeout(function(){'
                . '$("#mm").load("item_sub_category_edit.php", {auto_num: ' . $_POST['auto_num'] . '});'
                . ' }, 500);});</script>';
            }
        }
        /* ------------------ Gallery Image Add -------------------- */
    } else if ($_POST['action'] == 'add_image') {
        if (trim($_POST['auto_num']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please select sub category</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();

            $auto_num = $_POST['auto_num'];

            $myCon->closeCon();
            try {
                if (isset($_FILES['sub_img']['name']) && $_FILES['sub_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->singleImageResizedWithThumb($_FILES['sub_img']['name'], $_FILES['sub_img']['tmp_name'], '../uploads/', $website['images']['sub_entry_gal_width'], $website['images']['sub_entry_gal_height'], $website['images']['sub_entry_thumb_width'], $website['images']['sub_entry_thumb_height']);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($auto_num);
                    $fileDbObj->setUpload_type_id(3);/** SubCat Image */
                    $fileDbObj->uploadFile();
                }
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New image has been Added!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Gallery Image Delete -------------------- */
    } else if ($_POST['action'] == 'delete_image') {
        if (trim($_POST['upload_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Image ID is missing</div>');
        } else {
            $fileObj = new fileUploadClass();
            $fileDbObj = new fileUploadDBClass();

            $fileDbObj->setUpload_id($_POST['upload_id']);
            $auto_num = $_POST['auto_num'];
            try {
                $fileDbObj->removeImageAndRecordWithID();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Category image has been deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Change Sub Category Order -------------------- */
    } else if ($_POST['action'] == 'row_order') {
        $subCatObj = new subCatClass();
        $id_ary = explode(",", $_POST["row_order"]);
        try {
            for ($i = 0; $i < count($id_ary); $i++) {
                $subCatObj->setAuto_num($id_ary[$i]);
                $subCatObj->setSub_order($i);
                $subCatObj->subCatChnageOrder();
            }
            echo('<div class="alert alert-success" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Sub category order has been updated!</div>');
        } catch (Exception $ex) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
        }

        /* ------------------ Category Delete -------------------- */
    } else if ($_POST['action'] == 'delete') {
        if (trim($_POST['auto_num']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Sub category id is missing</div>');
        } else {
            $subCatObj = new subCatClass();

            $auto_num = $_POST['auto_num'];
            $subCatObj->setAuto_num($auto_num);
            /* check delete all the posts or not */
            if (isset($_POST['del_all']) && $_POST['del_all'] == 'true') {
                $subCatObj->setDel_all(true);
            } else {
                $subCatObj->setDel_all(false);
            }
            try {
                $subCatObj->subCatDelete();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Sub Category has been deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    }
}
/* end of post -------------------------------------------------------------------- */
?>