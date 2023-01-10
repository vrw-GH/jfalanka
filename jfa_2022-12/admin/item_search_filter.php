<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once '../models/dbConfig.php';
$myCon = new dbConfig();
$myCon->connect();
$params = $_REQUEST['term'];

$sql = "SELECT post_code, post_name FROM posts WHERE (post_name LIKE '%$params%' OR post_code LIKE '%$params%') ORDER BY post_name ASC";
$result = $myCon->query($sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = array('label' => $row['post_name'] . ' [' . $row['post_code'] . ']');
    }
    echo json_encode($results);
}
?>