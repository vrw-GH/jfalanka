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
        <div class="container-fluid voffset-2">
            <div class="row">
                <div class="col-xs-12">
                    <a href="../uploads/.index.php" class="btn-link"><strong><span class="glyphicon glyphicon-file"></span> File Index</strong></a>&nbsp;|&nbsp;<a href="../uploads/.image_index.php" class="btn-link"><strong><span class="glyphicon glyphicon-picture"></span> Image Index</strong></a> | <a href="<?php echo '../' . ADMIN . '/file_upload.php'; ?>" class="btn-link"><strong><span class="glyphicon glyphicon-cloud-upload"></span> Upload image/file</strong></a>
                </div>
                <div class="col-xs-12 voffset-2">
                    <?php
                    $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                    ?>
                    <div id="outer-box">
                        <div id="mm">
                            <h1>File Upload</h1>
                            <!-- forms END -->
                            <div class="col-xs-12 voffset-b-3">
                                <form id="my-awesome-dropzone" action="dropzoneupload.php" class="dropzone">
                                    <input type="hidden" name="category" value="file_manager"/>
                                    <input type="hidden" name="action" value="add_file"/>
                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                                    <div class="fallback">
                                        <input name="up_file" type="file" multiple />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                        Dropzone.options.myAwesomeDropzone = {
                            url: "dropzoneupload.php",
                            paramName: "up_file", /* Defaults is file and no need to put here */
                            dictDefaultMessage: "Drop images/files here or click to upload.",
                            addRemoveLinks: false,
                            maxFilesize: 8, /* MB */
                            maxFiles: 50,
                            parallelUploads: 1,
                            acceptedFiles: 'image/*,application/pdf,.xls, .xlsx, .doc, .docx ', /*   image/*,application/pdf,.psd */
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