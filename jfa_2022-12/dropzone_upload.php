<?php

if (!isset($_SESSION)) {
    session_start();
}
$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'upload/' . $_SESSION['file_upload_dir'];
if (!file_exists($storeFolder) && !is_dir($storeFolder)) {
    mkdir($storeFolder);
}
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
} else {
    $action = '';
}
if ($action == '' || $action == null) {
    if (!empty($_FILES)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;
        $targetFile = $targetPath . $_FILES['file']['name'];
        move_uploaded_file($tempFile, $targetFile);
    } else {
        $result = array();

        $files = scandir($storeFolder);
        if (false !== $files) {
            foreach ($files as $file) {
                if ('.' != $file && '..' != $file) {
                    $obj['name'] = $file;
                    $obj['size'] = filesize($storeFolder . $ds . $file);
                    $result[] = $obj;
                }
            }
        }

        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($result);
    }
} else {
    unlink("upload/" . $_SESSION['file_upload_dir'] . "/" . $_REQUEST['file']);
}
?>