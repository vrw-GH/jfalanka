<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';
/* include all the settings */
include_once '../site_config.php';
/* admin config must load through site_config.php. If there is a fault, execute direct include */
include_once 'admin_config.php';

include_once '../models/dbConfig.php';

$post_code = (int) $_REQUEST['post_code'];
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

        <script src="../resources/js/masonry.pkgd.min.js"></script>

        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });

                $('.grid').masonry({
                    /* options */
                    itemSelector: '.grid-item',
                });
            });
        </script>

        <link rel="stylesheet" href="../resources/dropzone-4.0.1/dist/basic.css" />
        <link rel="stylesheet" href="../resources/dropzone-4.0.1/dist/dropzone.css" />
        <script type="text/javascript" src="../resources/dropzone-4.0.1/dist/dropzone.js"></script>
        <script>
            var Dropzone = require("enyo-dropzone");
            Dropzone.autoDiscover = false;
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 voffset-1">
                    <?php
                    if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                        $action = $_POST['action'];
                        if ($action == 'delete_image') {
                            include_once 'controlers/postControler.php';
                        }
                        /* must be changed after posting data */
                        $post_code = (int) $post_code;
                    }

                    if (!isset($_SESSION['key'])) {
                        $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                    }
                    ?>
                    <div id="outer-box">
                        <div id="mm">
                            <h1>Add Post Gallery Images</h1>
                            <!-- forms END -->
                            <div class="col-xs-12 voffset-b-3">
                                <form id="my-awesome-dropzone" action="dropzoneupload.php" class="dropzone" enctype="multipart/form-data">
                                    <input type="hidden" name="category" value="post"/>
                                    <input type="hidden" name="action" value="add_image"/>
                                    <input type="hidden" name="post_code" value="<?php echo $post_code; ?>"/>
                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                                    <div class="fallback">
                                        <input name="post_img" type="file" multiple />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xs-12 col-pdn-both-0 voffset-1">
                            <a href="post_gallery.php?post_code=<?php echo $post_code; ?>" class="btn btn-info float-right" id="refresh">Reload uploaded files</a>
                        </div>
                        <?php
                        $myCon = new dbConfig();
                        $myCon->connect();
                        $query = "SELECT p.post_code, p.post_name, u.upload_id, u.upload_path FROM upload_data u "
                                . "LEFT JOIN posts p ON u.upload_ref = p.post_code WHERE "
                                . "u.upload_type_id='6' AND u.featured != 1 AND u.upload_ref = '" . $post_code . "' "
                                . "ORDER BY u.upload_id DESC";
                        $result = $myCon->query($query);
                        ?>
                        <h1>Post Gallery Images</h1>
                        <div id="details-viewer" class="full-wide">
                            <div class="col-xs-12 col-pdn-both-0 grid">
                                <?php
                                $form_id = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <div class="col-xs-6 col-sm-3 col-lg-2 grid-item">
                                        <div id="imgbox-outer">
                                            <form id="formID<?php echo $form_id ?>" method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="category" value="post">
                                                <input type="hidden" name="action" value="delete_image">
                                                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                                                <input type="hidden" name="upload_id" value="<?php echo($row['upload_id']) ?>">
                                                <input type="hidden" name="post_code" value="<?php echo($row['post_code']) ?>">
                                                <img src="../uploads/thumbs/<?php echo($row['upload_path']) ?>" alt="" class="img-responsive img-thumbnail" style="max-width: 150px;"/>
                                                <button type="submit" class="btn btn-danger btn-xs voffset-4 voffset-b-2"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                    $form_id = $form_id + 1;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <script>
                        Dropzone.options.myAwesomeDropzone = {
                            url: "dropzoneupload.php",
                            paramName: "post_img", /* Defaults is file and no need to put here */
                            dictDefaultMessage: "Drop images here or click to upload.",
                            addRemoveLinks: false,
                            maxFilesize: 8, /* MB */
                            maxFiles: 50,
                            parallelUploads: 1,
                            acceptedFiles: 'image/*', /*   image/*,application/pdf,.psd */
                            init: function () {
                                this.on("sending", function () {
                                    $("#refresh").text('Upload in progress, please wait...');
                                    $("#refresh").addClass("disabled");
                                });
                                this.on('complete', function () {
                                    if (this.getQueuedFiles().length == 0) {
                                        $("#refresh").text('Reload uploaded files');
                                        $("#refresh").removeClass("disabled");
                                    }
                                });
                            }
                        };
                    </script>
                </div>
            </div>
        </div>
    </body>
</html>