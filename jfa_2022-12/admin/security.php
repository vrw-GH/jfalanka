<?php

if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['system_logged_status']) || empty($_SESSION['system_logged_email']) ||
        ($_SERVER['SERVER_NAME'] . $_SESSION['system_logged_uname'] != $_SESSION['server_domain_user'])) {
    error_reporting(0);
    header("location:dashboard.php");
}
?>