<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../site_config.php';
include_once '../models/dbConfig.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard - <?php echo $_SESSION['comp_name']; ?></title>
        <meta name="robots" content="noindex,nofollow" />
        <link rel="stylesheet" type="text/css" href="../<?php echo $website['boostrap_folder']; ?>/css/bootstrap.css" media="all"/>
        <link  rel="stylesheet" type="text/css" href="css/style.css" media="all"/>

        <script type="text/javascript" src="../<?php echo $website['jquery_min_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_lower_js']; ?>" charset="utf-8"></script>
        <!-- calling before jquery ui -->
        <script type="text/javascript" src="../<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8"></script>

        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_css']; ?>" media="all"/>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_theme_css']; ?>" media="all"/>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_structure_css']; ?>" media="all"/>
        <script src="../<?php echo $website['jquery_ui_js']; ?>"></script>

        <script src="colorbox/jquery.colorbox.js"></script>
        <link rel="stylesheet" href="colorbox/colorbox.css" />

        <link rel="stylesheet" href="../resources/css/Errors/validationEngine.jquery.css" type="text/css"/>
        <link rel="stylesheet" href="../resources/css/Errors/template.css" type="text/css"/>
        <script src="../resources/js/Languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
        <script src="../resources/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

        <link href="css/menu.css" media="screen, projection" rel="stylesheet" type="text/css">
        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });
            });
            $(function () {
                $("#sortable").sortable({
                    placeholder: "ui-state-highlight"
                });
                $("#sortable").disableSelection();
            });

            function saveOrder() {
                var selectedLanguage = new Array();
                $('ul#sortable li').each(function () {
                    selectedLanguage.push($(this).attr("id"));
                });
                document.getElementById("row_order").value = selectedLanguage;
            }

        </script>
    </head>
    <body class="white">
        <div class="container voffset-2">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['key'] == $_POST['key']) {
                include_once 'controlers/sliderControler.php';
                echo('<br/>');
            }
            ?>
            <div id="outer-box">
                <div id="mm">
                    <h1>Change Slider Image Order</h1>
                    <h2>Drag & drop slider images to change order</h2>
                    <?php
                    $myCon = new dbConfig();
                    $myCon->connect();
                    $query = "SELECT u.upload_id, u.upload_path FROM upload_data u LEFT JOIN "
                            . "slider_content s ON u.upload_id = s.upload_id WHERE u.upload_type_id='1' "
                            . "ORDER BY s.slider_order ASC";
                    $result = $myCon->query($query);
                    ?>
                    <div class="col-xs-12">
                        <form method="post" id="formIDSort" action="">
                            <input type="hidden" name="category" value="slider_img">
                            <input type="hidden" name="action" value="row_order">
                            <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                            <input type = "hidden" name="row_order" id="row_order">
                            <ul id="sortable" class="imgsort">
                                <?php
                                $form_id = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <li id="<?php echo $row["upload_id"]; ?>" class="ui-state-default">
                                        <div class="col-xs-12">
                                            <div id="imgbox-outer">
                                                <img src="../uploads/<?php echo($row['upload_path']) ?>" alt="" class="img-responsive img-thumbnail"/>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    $form_id = $form_id + 1;
                                }
                                ?>
                            </ul>
                            <div class="col-xs-12">
                                <input type="submit" class="btn btn-sm btn-primary voffset-4 float-right" name="submit" value="Update Image Order" onClick="saveOrder();">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>