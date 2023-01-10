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
    include_once 'models/mainCatClass.php';
    include_once 'models/urlSlugClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';

    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['cat_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter main entry name</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $manCatObj = new mainCatClass();
            $urlSlug = new urlSlugClass();

            $cat_name = $myCon->escapeString($_POST['cat_name']);
            /* description & custom url */
            if ($website['descp']['main_entry'] == true) {
                $cat_details = $myCon->escapeString($_POST['cat_details']);
            } else {
                $cat_details = null;
            }
            if ($website['custom_url']['main_entry'] == true) {
                $custom_url = $myCon->escapeString($_POST['custom_url']);
            } else {
                $custom_url = null;
            }
            /* end */
            $cat_url_slug = $urlSlug->urlSlug($cat_name);
            $active = $_POST['active'];

            $manCatObj->setCat_name($cat_name);
            $manCatObj->setCat_details($cat_details);
            $manCatObj->setCat_url_slug($cat_url_slug);
            $manCatObj->setCustom_url($custom_url);
            $manCatObj->setCat_order($manCatObj->catOrder());
            $manCatObj->setActive($active);

            $myCon->closeCon();
            try {
                $manCatObj->mainCatSave();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New main entry has been Added!</div>');

                if (isset($_FILES['cat_img']['name']) && $_FILES['cat_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    /* Image crop options */
                    if ($_POST['cat_img_crop'] == 'both') {
                        $fileObj->imageCropped($_FILES['cat_img']['name'], $_FILES['cat_img']['tmp_name'], '../uploads/', $website['images'] ['main_entry_width'], $website['images'] ['main_entry_height'], false, null, null, false);
                    } else {
                        $fileObj->singleImageUpload($_FILES['cat_img']['name'], $_FILES['cat_img']['tmp_name'], '../uploads/', 'any', 'any');
                    }
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref(sprintf('%02d', $manCatObj->getCat_code()));
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(2);/** Main Cat Image */
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
        if (trim($_POST['cat_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter main entry name</div>');
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$("#mm").load("item_main_category_edit.php", {id: ' . $_POST['cat_code'] . '});'
            . ' }, 500);});</script>';
        } else if (trim($_POST['cat_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter main entry code</div>');
            /* No jquery reload */
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $manCatObj = new mainCatClass();
            $urlSlug = new urlSlugClass();

            $cat_code = $_POST['cat_code'];
            $cat_name = $myCon->escapeString($_POST['cat_name']);
            $cat_url_slug = $urlSlug->urlSlug($cat_name);
            /* description & custom url */
            if ($website['descp']['main_entry'] == true) {
                $cat_details = $myCon->escapeString($_POST['cat_details']);
            } else {
                $cat_details = null;
            }
            if ($website['custom_url']['main_entry'] == true) {
                $custom_url = $myCon->escapeString($_POST['custom_url']);
            } else {
                $custom_url = null;
            }
            /* end */

            $active = $_POST['active'];

            $manCatObj->setCat_code($cat_code);
            $manCatObj->setCat_name($cat_name);
            $manCatObj->setCat_details($cat_details);
            $manCatObj->setCat_url_slug($cat_url_slug);
            $manCatObj->setCustom_url($custom_url);
            $manCatObj->setActive($active);

            $myCon->closeCon();
            try {
                /* update record */
                $manCatObj->mainCatUpdate();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Category "' . $manCatObj->getCat_name() . '" has been Updated!</div>');

                /* delete image on tick. this part should come first, because this may delete updated image as well */
                if (isset($_POST['cat_img_delete']) && $_POST['cat_img_delete'] == 'true') {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    try {
                        $fileDbObj->removeFileAndRecordWithID();
                        echo('<div class="alert alert-success" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>Category image has been deleted!</div>');
                    } catch (Exception $ex) {
                        echo('<div class="alert alert-danger" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                    }
                }

                /* update image */
                if (isset($_FILES['cat_img']['name']) && $_FILES['cat_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    /* Image crop options */
                    if ($_POST['cat_img_crop'] == 'both') {
                        $fileObj->imageCropped($_FILES['cat_img']['name'], $_FILES['cat_img']['tmp_name'], '../uploads/', $website['images'] ['main_entry_width'], $website['images'] ['main_entry_height'], false, null, null, false);
                    } else {
                        $fileObj->singleImageUpload($_FILES['cat_img']['name'], $_FILES['cat_img']['tmp_name'], '../uploads/', 'any', 'any');
                    }
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($manCatObj->getCat_code());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(2);/** Main Cat Image */
                    /* If file Uploaded */
                    if ($fileDbObj->checkFeatured()) {
                        /* Method should be called after the file upload */
                        $fileDbObj->updateFile();
                    } else {
                        $fileDbObj->uploadFile();
                    }
                }

                unset($_POST);
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Gallery Image Add -------------------- */
    } else if ($_POST['action'] == 'add_image') {
        if (trim($_POST['cat_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please select main category</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();

            $cat_code = $_POST['cat_code'];

            $myCon->closeCon();
            try {
                if (isset($_FILES['cat_img']['name']) && $_FILES['cat_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->singleImageResizedWithThumb($_FILES['cat_img']['name'], $_FILES['cat_img']['tmp_name'], '../uploads/', $website['images']['main_entry_gal_width'], $website['images']['main_entry_gal_height'], $website['images']['main_entry_thumb_width'], $website['images']['main_entry_thumb_height']);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($cat_code);
                    $fileDbObj->setUpload_type_id(2);/** Main Cat Image */
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
            $cat_code = $_POST['cat_code'];
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
        /* ------------------ Change Category Order -------------------- */
    } else if ($_POST['action'] == 'row_order') {
        $manCatObj = new mainCatClass();
        $id_ary = explode(",", $_POST["row_order"]);
        try {
            for ($i = 0; $i < count($id_ary); $i++) {
                $manCatObj->setCat_code($id_ary[$i]);
                $manCatObj->setCat_order($i);
                $manCatObj->mainCatChnageOrder();
            }
            echo('<div class="alert alert-success" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Category order has been updated!</div>');
        } catch (Exception $ex) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
        }

        /* ------------------ Category Delete -------------------- */
    } else if ($_POST['action'] == 'delete') {
        if (trim($_POST['cat_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please select main category</div>');
        } else {
            $manCatObj = new mainCatClass();

            $cat_code = $_POST['cat_code'];
            $manCatObj->setCat_code($cat_code);
            /* check delete all the posts or not */
            if (isset($_POST['del_all']) && $_POST['del_all'] == 'true') {
                $manCatObj->setDel_all(true);
            } else {
                $manCatObj->setDel_all(false);
            }
            try {
                $manCatObj->mainCatDelete();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Category has been deleted!</div>');
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