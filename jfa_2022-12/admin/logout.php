<?PHP

/* Turn off all error reporting */
error_reporting(0);
session_start();
session_destroy();

header("location:index.php");
?>