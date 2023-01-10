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

$page_id = $_REQUEST['page_id'];
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
        <script>
            function submitFormTitle(formid) {
                $.ajax({
                    type: 'post',
                    url: 'controlers/imageControler.php',
                    data: $('#formTitleID' + formid).serialize(),
                    success: function (data){
                         $(".tit-res-"+formid).html('updating...');
                         $(".tit-res-"+formid).html(data);
                    }
                });
            }
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
                            include_once 'controlers/pagesControler.php';
                        }
                    }

                    if (!isset($_SESSION['key'])) {
                        $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                    }
                    ?>
                    <div id="outer-box">
                        <div id="mm">
                            <h1>Add Page Gallery Images</h1>
                            <!-- forms END -->
                            <div class="col-xs-12 voffset-b-3">
                                <form id="my-awesome-dropzone" action="dropzoneupload.php" class="dropzone">
                                    <input type="hidden" name="category" value="pages"/>
                                    <input type="hidden" name="action" value="add_image"/>
                                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>"/>
                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                                    <div class="fallback">
                                        <input name="page_img" type="file" multiple />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xs-12 col-pdn-both-0 voffset-1">
                            <a href="page_gallery.php?page_id=<?php echo $page_id; ?>" class="btn btn-info float-right" id="refresh">Reload uploaded files</a>
                        </div>
                        <div id="ccc" class="col-xs-12 col-pdn-both-0">
                            <?php include_once './page_gallery_content.php'; ?>
                        </div>
                    </div>
                    <script>
                        Dropzone.options.myAwesomeDropzone = {
                            url: "dropzoneupload.php",
                            paramName: "page_img", /* Defaults is file and no need to put here */
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
                                    $("#cc").addClass("disabled");
                                });
                                this.on('complete', function () {
                                    if (this.getQueuedFiles().length == 0) {
                                        $("#refresh").text('Reload uploaded files');
                                        $("#refresh").removeClass("disabled");
                                        $("#ccc").load('page_gallery_content.php', {page_id: <?php echo $page_id; ?>});
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