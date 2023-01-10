<?php

if (file_exists('admin_config.php')) {
    include_once 'admin_config.php';
} else if (file_exists('../admin_config.php')) {
    include_once '../admin_config.php';
} else {
    include_once '../../admin_config.php';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'models/newsEventClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';

    if ($_POST['action'] == 'insert') {
        if (trim($_POST['ann_title']) == null || trim($_POST['ann_details']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $newsObj = new newsEventClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $ann_title = $myCon->escapeString($_POST['ann_title']);
            $ann_details = $myCon->escapeString($_POST['ann_details']);

            $myCon->closeCon();

            $newsObj->setAnn_date(date('Y-m-d H:i:s'));
            $newsObj->setAnn_title($ann_title);
            $newsObj->setAnn_details($ann_details);
            $newsObj->setAdd_by($_POST['user_id']);
            $newsObj->setAdd_date(date('Y-m-d H:i:s'));
            $newsObj->setAnn_type('1');

            try {
                $newsObj->newsSave();
                if (isset($_FILES['ann_img']['name']) && $_FILES['ann_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->imageCropped($_FILES['ann_img']['name'], $_FILES['ann_img']['tmp_name'], '../uploads/', $website['images']['news_width'], $website['images'] ['news_height'], false, null, null, false);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($newsObj->getAnn_id());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(10);/** News Image */
                    $fileDbObj->uploadFile();
                }
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New news/event has been Added!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* Database Update part Start */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['ann_title']) == null || trim($_POST['ann_details']) == null ||
                trim($_POST['ann_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $newsObj = new newsEventClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $ann_title = $myCon->escapeString($_POST['ann_title']);
            $ann_details = $myCon->escapeString($_POST['ann_details']);

            $myCon->closeCon();

            $newsObj->setAdd_date($_POST['add_date']);
            $newsObj->setAnn_id($_POST['ann_id']);
            $newsObj->setAnn_title($ann_title);
            $newsObj->setAnn_details($ann_details);
            $newsObj->setUpd_by($_POST['user_id']);
            $newsObj->setUpd_date(date('Y-m-d H:i:s'));
            $newsObj->setAnn_type('1');
            try {
                $newsObj->newsUpdate();


                /* delete image on tick. this part should come first, because this may delete updated image as well */
                if (isset($_POST['news_img_delete']) && $_POST['news_img_delete'] == 'true') {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    try {
                        $fileDbObj->removeFileAndRecordWithID();
                        echo('<div class="alert alert-success" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>News image has been deleted!</div>');
                    } catch (Exception $ex) {
                        echo('<div class="alert alert-danger" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                    }
                }

                if (isset($_FILES['ann_img']['name']) && $_FILES['ann_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->imageCropped($_FILES['ann_img']['name'], $_FILES['ann_img']['tmp_name'], '../uploads/', $website['images']['news_width'], $website['images'] ['news_height'], false, null, null, false);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($newsObj->getAnn_id());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(10);/** News Image */
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
                . '<span aria-hidden="true">&times;</span></button>News/Event has been Updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  ' . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* Database Update part END */
    } else if ($_POST['action'] == 'delete') {
        if (trim($_POST['ann_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $newsObj = new newsEventClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $newsObj->setAnn_id($_POST['ann_id']);
            try {
                $newsObj->newsDelete();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>News/Event has been Deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    } else if ($_POST['action'] == 'add_image') {
        if (trim($_POST['ann_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please select main category</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();

            $ann_id = $_POST['ann_id'];

            $myCon->closeCon();
            try {
                if (isset($_FILES['ann_img']['name']) && $_FILES['ann_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->singleImageResizedWithThumb($_FILES['ann_img']['name'], $_FILES['ann_img']['tmp_name'], '../uploads/', $website['images']['news_gal_width'], $website['images']['news_gal_height'], $website['images']['news_thumb_width'], $website['images']['news_thumb_height']);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref($ann_id);
                    $fileDbObj->setUpload_type_id(10);/** News Image */
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
    } else if ($_POST['action'] == 'delete_image') {
        if (trim($_POST['upload_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $fileObj = new fileUploadDBClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $fileObj->setUpload_id($_POST['upload_id']);
            try {
                $fileObj->removeImageAndRecordWithID();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Image has been Deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    }
}
?>