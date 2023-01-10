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
    include_once '../models/imageClass.php';
    include_once '../../models/dbConfig.php';
    if ($_POST['action'] == 'title_image') {
        $myCon = new dbConfig();
        $myCon->connect();
        $imgObj = new imageClass();

        $upload_title = $myCon->escapeString($_POST['upload_title']);
        $imgObj->setUpload_id($_POST['upload_id']);
        $imgObj->setUpload_title($upload_title);
        $imgObj->setUpload_alter($upload_title);

        $myCon->closeCon();
        try {
            $imgObj->titleUpdate();

            unset($_POST);
            echo('<div class="text-success"><span class="glyphicons glyphicons-ok"></span> Title has been updated!</div>');
        } catch (Exception $ex) {
            echo('<div class="text-danger"><span class="glyphicons glyphicons-exclamation-sign"></span> ' . $ex->getMessage() . '</div>');
        }
    }
}