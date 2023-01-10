<?php

if (!isset($_SESSION)) {
    session_start();
}

$ds = DIRECTORY_SEPARATOR;

if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
    if (!empty($_FILES)) {
        $category = $_POST['category'];

        if ($category == 'itm_main_cat') {
            include_once 'controlers/mainCatControler.php';
        }
        if ($category == 'itm_sub_cat') {
            include_once 'controlers/subCatControler.php';
        }
        if ($category == 'news') {
            include_once 'controlers/newsEventControler.php';
        }
        if ($category == 'pages') {
            include_once 'controlers/pagesControler.php';
        }
        if ($category == 'post') {
            include_once 'controlers/postControler.php';
        }
        if ($category == 'file_manager') {
            include_once 'controlers/fileControler.php';
        }
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
}
?>