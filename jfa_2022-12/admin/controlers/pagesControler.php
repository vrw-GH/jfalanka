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
    include_once 'models/pagesClass.php';
    include_once 'models/urlSlugClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';

    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['page_title']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter a page title</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $pageObj = new pagesClass();
            $urlSlug = new urlSlugClass();

            $page_title = $myCon->escapeString($_POST['page_title']);
            $page_content = $myCon->escapeString($_POST['page_content']);

            if ($website['custom_url']['page'] == true) {
                $page_custom_url = $myCon->escapeString($_POST['page_custom_url']);
            } else {
                $page_custom_url = null;
            }
            /* end */
            $page_url_slug = $urlSlug->urlSlug($page_title);
            $gallery_type = $_POST['gallery_type'];
            $gallery_name = $myCon->escapeString($_POST['gallery_name']);
            $add_date = date('Y-m-d H:i:s');
            $add_by = $_POST['user_id'];
            $active = $_POST['active'];

            $pageObj->setActive($active);
            $pageObj->setAdd_by($add_by);
            $pageObj->setAdd_date($add_date);
            $pageObj->setPage_content($page_content);
            $pageObj->setPage_custom_url($page_custom_url);
            $pageObj->setGallery_type($gallery_type);
            $pageObj->setGallery_name($gallery_name);
            $pageObj->setPage_order($pageObj->getPageOrder());
            $pageObj->setPage_title($page_title);
            $pageObj->setPage_url_slug($page_url_slug);

            $myCon->closeCon();
            try {
                $pageObj->pageSave();
                if (isset($_FILES['page_img']['name']) && $_FILES['page_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->imageCropped($_FILES['page_img']['name'], $_FILES['page_img']['tmp_name'], '../uploads/', $website['images'] ['page_width'], $website['images'] ['page_height'], false, null, null, false);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($pageObj->getPage_id());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(11);/** Page Image */
                    $fileDbObj->uploadFile();
                }
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New page has been Added!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['page_title']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please a page title</div>');
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$("#mm").load("page_edit.php", {page_id: ' . $_POST['page_id'] . '});'
            . ' }, 500);});</script>';
        } else if (trim($_POST['page_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Page ID is missing</div>');
            /* No jquery reload */
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $pageObj = new pagesClass();
            $urlSlug = new urlSlugClass();

            $page_id = $_POST['page_id'];
            $page_title = $myCon->escapeString($_POST['page_title']);
            $page_content = $myCon->escapeString($_POST['page_content']);

            if ($website['custom_url']['page'] == true) {
                $page_custom_url = $myCon->escapeString($_POST['page_custom_url']);
            } else {
                $page_custom_url = null;
            }
            /* end */
            $page_url_slug = $urlSlug->urlSlug($page_title);
            $gallery_type = $_POST['gallery_type'];
            $gallery_name = $myCon->escapeString($_POST['gallery_name']);
            $upd_date = date('Y-m-d H:i:s');
            $upd_by = $_POST['user_id'];
            $active = $_POST['active'];

            $pageObj->setActive($active);
            $pageObj->setPage_content($page_content);
            $pageObj->setPage_custom_url($page_custom_url);
            $pageObj->setPage_id($page_id);
            $pageObj->setPage_title($page_title);
            $pageObj->setPage_url_slug($page_url_slug);
            $pageObj->setGallery_type($gallery_type);
            $pageObj->setGallery_name($gallery_name);
            $pageObj->setUpd_by($upd_by);
            $pageObj->setUpd_date($upd_date);

            $myCon->closeCon();
            try {
                /* update record */
                $pageObj->pageUpdate();

                /* delete image on tick. this part should come first, because this may delete updated image as well */
                if (isset($_POST['page_img_delete']) && $_POST['page_img_delete'] == 'true') {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    try {
                        $fileDbObj->removeFileAndRecordWithID();
                        echo('<div class="alert alert-success" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>Page image has been deleted!</div>');
                    } catch (Exception $ex) {
                        echo('<div class="alert alert-danger" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                    }
                }

                /* update image */
                if (isset($_FILES['page_img']['name']) && $_FILES['page_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->imageCropped($_FILES['page_img']['name'], $_FILES['page_img']['tmp_name'], '../uploads/', $website['images'] ['page_width'], $website['images'] ['page_height'], false, null, null, false);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($pageObj->getPage_id());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(11);/** Page Image */
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
                . '<span aria-hidden="true">&times;</span></button>Page "' . $pageObj->getPage_title() . '" has been Updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Gallery Image Add -------------------- */
    } else if ($_POST['action'] == 'add_image') {
        if (trim($_POST['page_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please select a page</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();

            $page_id = $_POST['page_id'];

            $myCon->closeCon();
            try {
                if (isset($_FILES['page_img']['name']) && $_FILES['page_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->singleImageResizedWithThumb($_FILES['page_img']['name'], $_FILES['page_img']['tmp_name'], '../uploads/', $website['images']['page_gal_width'], $website['images']['page_gal_height'], $website['images']['page_thumb_width'], $website['images']['page_thumb_height']);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($page_id);
                    $fileDbObj->setUpload_type_id(11);/** Page Image */
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
            $page_id = $_POST['page_id'];
            try {
                $fileDbObj->removeImageAndRecordWithID();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Page image has been deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Page Delete -------------------- */
    } else if ($_POST['action'] == 'delete') {
        if (trim($_POST['page_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please select a page</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $pageObj = new pagesClass();

            $page_id = $_POST['page_id'];

            $myCon->closeCon();
            $pageObj->setPage_id($page_id);
            try {
                /* update record */
                $pageObj->pageDelete();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Page has been deleted!</div>');
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