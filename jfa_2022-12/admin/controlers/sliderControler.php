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
    include_once('../models/dbConfig.php');
    include_once('models/sliderClass.php');
    include_once ('../models/fileUploadClass.php');
    include_once ('../models/fileUploadDBClass.php');

    if ($_POST['action'] == 'insert') {
        if ($_FILES['slider_img']['name'] == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please Select an image</div>');
        } else {
            $fileDbObj = new fileUploadDBClass();
            $fileObj = new fileUploadClass();

            try {
                $fileObj->imageCropped($_FILES['slider_img']['name'], $_FILES['slider_img']['tmp_name'], '../uploads/', $website['images']['main_slider_width'], $website['images']['main_slider_height'], false, null, null, false);
                $fileDbObj->setUpload_type_id('1');
                $fileDbObj->setUpload_path($fileObj->getFilename());
                $fileDbObj->uploadFile();

                $myCon = new dbConfig();
                $myCon->connect();
                $sliderObj = new sliderClass();
                $sliderObj->setUpload_id($fileDbObj->getUpload_id());
                $sliderObj->setContent_header($myCon->escapeString($_POST['content_header']));
                $sliderObj->setContent_descp($myCon->escapeString($_POST['content_descp']));
                $sliderObj->setContent_url($myCon->escapeString($_POST['content_url']));
                $sliderObj->setSlider_order($sliderObj->sliderOrder());
                $sliderObj->addSliderContent();

                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New slider image has been uploaded!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* Database UPDATE part */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['upload_id']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Upload ID is missing</div>');
        } else {
            $fileDbObj = new fileUploadDBClass();
            $fileObj = new fileUploadClass();

            try {
                if (isset($_FILES['slider_img']['name']) && $_FILES['slider_img']['name'] != null) {
                    $fileObj->imageCropped($_FILES['slider_img']['name'], $_FILES['slider_img']['tmp_name'], '../uploads/', $website['images']['main_slider_width'], $website['images']['main_slider_height'], false, null, null, false);
                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    $fileDbObj->setUpload_type_id('1');
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->updateFileWithID();
                    echo('<div class="alert alert-success" role="alert">  '
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span></button>New slider image has been uploaded!</div>');
                }

                $myCon = new dbConfig();
                $myCon->connect();
                $sliderObj = new sliderClass();
                $sliderObj->setUpload_id($_POST['upload_id']);
                $sliderObj->setContent_header($myCon->escapeString($_POST['content_header']));
                $sliderObj->setContent_descp($myCon->escapeString($_POST['content_descp']));
                $sliderObj->setContent_url($myCon->escapeString($_POST['content_url']));

                $sliderObj->editSliderContent();

                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Content has been updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Change Slider Order -------------------- */
    } else if ($_POST['action'] == 'row_order') {
        $sliderObj = new sliderClass();
        $id_ary = explode(",", $_POST["row_order"]);
        try {
            for ($i = 0; $i < count($id_ary); $i++) {
                $sliderObj->setUpload_id($id_ary[$i]);
                $sliderObj->setSlider_order($i);
                $sliderObj->sliderChnageOrder();
            }
            echo('<div class="alert alert-success" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Slider order has been updated!</div>');
        } catch (Exception $ex) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
        }

        /* ------------------ Slider Delete -------------------- */
    } else if ($_POST['action'] == 'delete') {
        $fileDbObj = new fileUploadDBClass();
        $sliderObj = new sliderClass();

        $fileDbObj->setUpload_id($_POST['upload_id']);

        $sliderObj->setUpload_id($_POST['upload_id']);
        try {
            $fileDbObj->removeFileAndRecordWithID();
            $sliderObj->removeSliderContent();
            echo('<div class="alert alert-success" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Slider image has been deleted!</div>');
        } catch (Exception $ex) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
        }
    }
}
/* end of post -------------------------------------------------------------------- */
?>