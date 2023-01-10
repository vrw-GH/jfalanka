<?php

/* Check posting data ------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../models/fileUploadClass.php';
    if ($_POST['action'] == 'add_file') {
        try {
            if (isset($_FILES['up_file']['name']) && $_FILES['up_file']['name'] != null) {
                $fileObj = new fileUploadClass();
                $fileObj->singleFileUpload($_FILES['up_file']['name'], $_FILES['up_file']['tmp_name'], '../uploads/');
            }
            unset($_POST);
        } catch (Exception $ex) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
        }
        /* ------------------ Gallery Image Delete -------------------- */
    } else if ($_POST['action'] == 'delete_file') {
        if (trim($_POST['upload_path']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Image path is missing</div>');
        } else {
            try {
                if (file_exists($_POST['upload_path'])) {
                    unlink($_POST['upload_path']);
                    if (file_exists('thumbs/' . $_POST['upload_path'])) {
                        unlink($_POST['thumbs/' . 'upload_path']);
                    }
                }
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>File has been deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    }
}
?>