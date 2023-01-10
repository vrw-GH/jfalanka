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
    include_once 'models/unitClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['unit_code']) == null || trim($_POST['unit_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter main entry name</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $unitObj = new unitClass();

            $unit_code = $_POST['unit_code'];
            $unit_name = $_POST['unit_name'];
            $unit_code = $myCon->escapeString($unit_code);
            $unit_code = strtolower($unit_code);
            $unit_name = $myCon->escapeString($unit_name);
            $unitObj->setUnit_code($unit_code);
            $unitObj->setUnit_name($unit_name);

            $myCon->closeCon();
            try {
                $unitObj->unitSave();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New unit has been Added!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['unit_code']) == null || trim($_POST['unit_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter main entry name</div>');
            echo '<script type="text/javascript">$(function(){setTimeout(function(){'
            . '$("#mm").load("item_units_edit.php", {unit_code: ' . $_POST['unit_code'] . '});'
            . ' }, 500);});</script>';
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $unitObj = new unitClass();

            $unit_code = $_POST['unit_code'];
            $unit_name = $_POST['unit_name'];
            $active = $_POST['active'];

            $unit_name = $myCon->escapeString($unit_name);
            $unitObj->setUnit_code($unit_code);
            $unitObj->setUnit_name($unit_name);
            $unitObj->setActive($active);

            $myCon->closeCon();
            try {
                $unitObj->unitUpdate();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>unit "' . $unitObj->getUnit_code() . '" has been Updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                echo '<script type="text/javascript">$(function(){setTimeout(function(){'
                . '$("#mm").load("item_units_edit.php", {unit_code: ' . $_POST['unit_code'] . '});'
                . ' }, 500);});</script>';
            }
        }
        /* ------------------ Database DELETE -------------------- */
    } else if ($_POST['action'] == 'delete') {
        
    }
}
/* end of post -------------------------------------------------------------------- */
?>