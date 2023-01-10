<?php
if (!isset($_SESSION)) {
    session_start();
}

$updated = 'false';
$added = 'false';
$deleted = 'false';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['cart_submit']) && $_POST['cart_submit'] == 'true')) {
    if (isset($_REQUEST['itm_code']) && isset($_REQUEST['itm_name']) && isset($_REQUEST['itm_qty'])) {
        if (empty($_SESSION['count_prd'])) {
            $_SESSION['count_prd'] = 0;
        }
        $idx = $_SESSION['count_prd'];
        if ($idx != 0) {
            foreach ($_SESSION['itm_code'] as $key => $ic) {
                if ($_SESSION['itm_code'][$key] == $_REQUEST['itm_code']) {
                    $it_count = $_SESSION['itm_qty'][$key];
                    $it_count = $it_count + $_REQUEST['itm_qty'];
                    $_SESSION['itm_qty'][$key] = $it_count;
                    $updated = 'true';
                }
            }
        }
        if ($updated == 'false') {
            $_SESSION['count_prd'] ++;
            $_SESSION['itm_code'][$idx] = $_REQUEST['itm_code'];
            $_SESSION['itm_name'][$idx] = $_REQUEST['itm_name'];
            $_SESSION['itm_qty'][$idx] = $_REQUEST['itm_qty'];
            $added = 'true';
        }
    }
}