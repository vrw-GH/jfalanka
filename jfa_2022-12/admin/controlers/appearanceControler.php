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
    include_once 'models/appearanceClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['app_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter appearance name</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $appObj = new appearanceClass();

            $app_name = $_POST['app_name'];
            $app_name = $myCon->escapeString($app_name);
            $appObj->setApp_name($app_name);

            $myCon->closeCon();
            try {
                $appObj->appSave();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New appearance name has been Added!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['app_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter appearance name</div>');
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$("#mm").load("item_appearance_edit.php", {app_code: ' . $_POST['app_code'] . '});'
            . ' }, 500);});</script>';
        } else if (trim($_POST['app_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter appearance code</div>');
            /* No jquery reload */
        } else {
            $app_name = $_POST['app_name'];
            $app_code = $_POST['app_code'];
            $active = $_POST['active'];

            $myCon = new dbConfig();
            $myCon->connect();
            $appObj = new appearanceClass();

            $app_name = $myCon->escapeString($app_name);
            $appObj->setApp_name($app_name);
            $appObj->setApp_code($app_code);
            $appObj->setActive($active);

            $myCon->closeCon();
            try {
                $appObj->appUpdate();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Apperance "' . $appObj->getApp_name() . '" has been Updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                echo '<script type="text/javascript">$(function(){setTimeout(function(){'
                . '$("#mm").load("item_appearance_edit.php", {app_code: ' . $_POST['app_code'] . '});'
                . ' }, 500);});</script>';
            }
        }
        /* ------------------ Database DELETE -------------------- */
    } else if ($_POST['action'] == 'delete') {
        
    }
}
/* end of post -------------------------------------------------------------------- */
?>