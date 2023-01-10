<?php

if (!isset($_SESSION)) {
    session_start();
}

if (file_exists('admin_config.php')) {
    include_once 'admin_config.php';
} else if (file_exists('../admin_config.php')) {
    include_once '../admin_config.php';
} else {
    include_once '../../admin_config.php';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'models/commentClass.php';
    include_once 'models/urlSlugClass.php';
    include_once '../models/dbConfig.php';
    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['comm_name']) == null ||
                trim($_POST['comm_email']) == null || trim($_POST['comment']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required Feilds</div>');
        } else if ($_POST['captchabox'] != $_SESSION['captcha']) {
            echo('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Correct Image value</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $commObj = new commentClass();
            $urlSlug = new urlSlugClass();

            $comm_title = $myCon->escapeString($_POST['comm_title']);
            $comm_url_slug = $urlSlug->urlSlug($comm_title);
            $comm_name = $myCon->escapeString($_POST['comm_name']);
            $comm_email = $_POST['comm_email'];
            $comment = $myCon->escapeString($_POST['comment']);

            if (isset($_POST['cat_code']) && $_POST['cat_code'] != null) {
                $cat_code = $_POST['cat_code'];
            } else {
                $cat_code = 0;
            }
            if (isset($_POST['sub_code']) && $_POST['sub_code'] != null) {
                $sub_code = $_POST['sub_code'];
            } else {
                $sub_code = 0;
            }
            if (isset($_POST['post_code']) && $_POST['post_code'] != null) {
                $post_code = $_POST['post_code'];
            } else {
                $post_code = 0;
            }

            $approved = 0;
            $comm_order = $commObj->getCommentOrder();
            $comm_date = date('Y-m-d H:i:s');


            $commObj->setApproved($approved);
            $commObj->setCat_code($cat_code);
            $commObj->setComm_date($comm_date);
            $commObj->setComm_email($comm_email);
            $commObj->setComm_name($comm_name);
            $commObj->setComm_order($comm_order);
            $commObj->setComm_title($comm_title);
            $commObj->setComm_url_slug($comm_url_slug);
            $commObj->setComment($comment);
            $commObj->setPost_code($post_code);
            $commObj->setSub_code($sub_code);

            $myCon->closeCon();
            try {
                $commObj->addComment();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Thank you for your comment. your comment will be visible after approval</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    } else if ($_POST['action'] == 'delete') {
        if (trim($_POST['comm_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required Feilds</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $commObj = new commentClass();

            $comm_code = $_POST['comm_code'];

            $commObj->setComm_code($comm_code);

            $myCon->closeCon();
            try {
                $commObj->deleteComment();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Comment has been deleted</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    } else if ($_POST['action'] == 'approve') {
        if (trim($_POST['comm_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required Feilds</div>');
        } else {
            $myCon = new dbConfig();
            $myCon->connect();
            $commObj = new commentClass();

            $comm_code = $_POST['comm_code'];

            $commObj->setComm_code($comm_code);

            $myCon->closeCon();
            try {
                $commObj->approveComment();
                unset($_POST);
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Comment has been published!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    }
}
?>          
