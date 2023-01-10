<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../site_config.php';
include_once '../models/dbConfig.php';

$item_code = $_REQUEST['item_code'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Dashboard - <?php echo $_SESSION['comp_name']; ?></title>
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
        </script>
    </head>
    <body class="white">
        <div class="container voffset-2">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['key'] == $_POST['key']) {
                include_once 'Controlers/itemControler.php';
                echo('<br/>');
            }
            // prevent multiple posting on page reload
            $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
            ?>
            <div id="outer-box">
                <div id="mm">
                    <h1>Add Item Image</h1>
                    <form id="formID" method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="category" value="item">
                        <input type="hidden" name="action" value="insert_img">
                        <input type="hidden" name="item_code" value="<?php echo $item_code; ?>" >
                        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                        <div class="form-group col-xs-12">
                            <label><span class="text-danger">*</span>Add Product Image :-</label>
                            <input type="file" name="item_img" class="validate[required] form-control">
                            <p class="help-block">Image at leat a 740px * 740px image</p>
                        </div>
                        <div class="form-group col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-picture"></span> Add Product Image</button>
                        </div>
                </div>
                <!-- forms END -->
                </form>
            </div>
            <?php
            $myCon = new dbConfig();
            $myCon->connect();
            $query = "SELECT upload_id, upload_path FROM upload_data WHERE "
                    . "upload_type_id='6' AND upload_ref='" . $item_code . "' ORDER BY upload_id ASC";
            $result = $myCon->query($query);
            ?>
            <h3 class="text-primary">Item Images</h3>
            <div id="details-viewer" class="full-wide">
                <?php
                $form_id = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-xs-6 col-sm-4">
                        <div id="imgbox-outer">
                            <form id="formID<?php echo $form_id ?>" method="post" action="" class="allforms" enctype="multipart/form-data">
                                <input type="hidden" name="category" value="item">
                                <input type="hidden" name="action" value="delete_img">
                                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                                <input type="hidden" name="upload_id" value="<?php echo($row['upload_id']) ?>">
                                <input type="hidden" name="upload_path" value="<?php echo($row['upload_path']) ?>">
                                <img src="../upload_images/Thumbs/<?php echo($row['upload_path']) ?>" alt="" width="196"/>
                                <button type="submit" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                            </form>
                        </div>
                    </div>
                    <?php
                    $form_id = $form_id + 1;
                }
                ?>
            </div>
        </div>
    </body>
</html>