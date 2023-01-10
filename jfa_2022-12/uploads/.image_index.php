<?php
if (!isset($_SESSION)) {
    session_start();
}
/* include all the settings */
include_once '../site_config.php';

if (!empty($_SESSION['system_logged_status']) && !empty($_SESSION['system_logged_email']) && $_SESSION['system_logged_mem_type_id'] < 5) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Directory Contents</title>
            <link rel="stylesheet" href="<?php echo WEB_HOST ?>/uploads/.style.css">
            <script src="<?php echo WEB_HOST ?>/uploads/.sorttable.js"></script>
            <link rel="stylesheet" type="text/css" href="<?php echo WEB_HOST ?>/<?php echo $website['boostrap_folder']; ?>/css/bootstrap.css" media="all"/>
            <link rel="stylesheet" type="text/css" href="<?php echo WEB_HOST ?>/resources/css/style.css" media="all"/>

            <link rel="stylesheet" href="<?php echo WEB_HOST ?>/resources/css/Errors/validationEngine.jquery.css" type="text/css"/>
            <link rel="stylesheet" href="<?php echo WEB_HOST ?>/resources/css/Errors/template.css" type="text/css"/>

            <script type="text/javascript" src="<?php echo WEB_HOST ?>/<?php echo $website['jquery_min_js']; ?>" charset="utf-8"></script>
            <script type="text/javascript" src="<?php echo WEB_HOST ?>/<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8"></script>

            <!-- Add fancyBox main JS and CSS files -->
            <link rel="stylesheet" type="text/css" href="<?php echo WEB_HOST; ?>/resources/fancybox/source/jquery.fancybox.css?v=2.1.4" media="screen" />
            <script type="text/javascript" src="<?php echo WEB_HOST; ?>/resources/fancybox/source/jquery.fancybox.js?v=2.1.4"></script>

            <!-- fancyBox ------------------------------->
            <script type="text/javascript">
                $(document).ready(function () {
                    $("a.gal").fancybox({
                        'transitionIn': 'fade',
                        'transitionOut': 'fade',
                        'speedIn': 600,
                        'speedOut': 200,
                        'overlayShow': false
                    });

                    $('.fancybox').fancybox({
                        helpers: {
                            overlay: {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
                        }
                    });
                });
            </script>
        </head>
        <body>
            <div class="container-fluid voffset-2">
                <div class="row">
                    <div class=" col-xs-12">
                        <a href=".index.php" class="btn"><strong><span class="glyphicon glyphicon-file"></span> File Index</strong></a>&nbsp;|&nbsp;<a href=".image_index.php" class="btn"><strong><span class="glyphicon glyphicon-picture"></span> Image Index</strong></a> | <a href="<?php echo '../' . ADMIN . '/file_upload.php'; ?>" class="btn"><strong><span class="glyphicon glyphicon-cloud-upload"></span> Upload image/file</strong></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-danger voffset-1">
                        <strong>Warning!</strong> Careful when you deleting files /images. Some Files/Images may have linked with database server and prompt database error, 404 error with frontend users (Visitors)
                    </div>
                    <div class="col-xs-12 voffset-2">
                        <h1>Image Contents</h1>
                        <?php
                        if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                            $action = $_POST['action'];
                            if ($action == 'delete_file') {
                                include_once '../' . ADMIN . '/controlers/fileControler.php';
                            }
                        }

                        $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                        ?>
                    </div>
                    <div class="col-xs-12 voffset-1 grid">
                        <?php
                        $folder = '';
                        $inner_folder = $_SERVER['REQUEST_URI'];
                        $thumb_folder = $inner_folder . '.thumb/';
                        $filetype = '*.*';
                        $files = glob($folder . $filetype);
                        $count = count($files);

                        $sortedArray = array();
                        for ($i = 0; $i < $count; $i++) {
                            $sortedArray[date('YmdHis', filemtime($files[$i]))] = $files[$i];
                        }

                        ksort($sortedArray);
                        $aaa = 0;
                        foreach ($sortedArray as &$filename) {
                            $extn = pathinfo($filename, PATHINFO_EXTENSION);
                            if ($extn != 'pdf' && $extn != 'txt' && $extn != 'xls' &&
                                    $extn != 'xlsx' && $extn != 'doc' && $extn != 'docx') {
                                list($width, $height) = getimagesize($filename);
                                echo '<div class="col-xs-4 col-md-3 col-lg-2 img-outer grid-item voffset-1">';
                                echo '<div>' . $width . 'px * ' . $height . 'px</div>';
                                echo '<a name="' . $filename . '" rel="img" href="' . $filename . '" class="gal">';
                                if (file_exists(WEB_HOST . '/uploads/thumbs/' . basename($filename))) {
                                    echo '<img src="' . WEB_HOST . '/uploads/thumbs/' . basename($filename) . '" class="img-responsive"/>';
                                } else {
                                    echo '<img src="' . WEB_HOST . '/uploads/' . basename($filename) . '" class="img-responsive"/>';
                                }
                                echo '</a>';
                                echo " 
                                    <div class='input-group'>
                                        <input id='copy" . $aaa . "' class='form-control input-sm' type='text' value='" . WEB_HOST . "/uploads/$filename'>
                                        <div class='input-group-addon' style='padding:0'>
                                            <button class='btn btn-xs' data-clipboard-action='copy' data-clipboard-target='#copy" . $aaa . "'><span class='glyphicon glyphicon-copy'></span> Copy</button>
                                        </div>
                                    </div>";
                                echo " <form class='form-inline voffset-1 voffset-b-1' id='del$aaa' action='' method='POST'>
                                        <input type='hidden' name='category' value='file_manager'>
                                        <input type='hidden' name='action' value='delete_file'>
                                        <input type='hidden' name='key' value='" . $_SESSION['key'] . "'/>
                                        <input type='hidden' name='upload_path' value='" . basename($filename) . "'>
                                        <button type='submit' class='btn btn-danger btn-xs'>delete</button>
                                    </form>";
                                echo '</div>';
                            }
                            $aaa+=1;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <script src="<?php echo WEB_HOST ?>/<?php echo ADMIN ?>/plugins/clipboard.js-master/dist/clipboard.min.js"></script>
            <script>
                var clipboard = new Clipboard('.btn');
                clipboard.on('success', function (e) {
                    console.log(e);
                });
                clipboard.on('error', function (e) {
                    console.log(e);
                });
            </script>
            <script src="<?php echo WEB_HOST ?>/resources/js/masonry.pkgd.min.js"></script>
            <script>
                $(function () {
                    $('.grid').masonry({
                        /* options */
                        itemSelector: '.grid-item',
                    });
                });
            </script>
        </body>
    </html>
    <?php
} else {
    header('location: https://www.roomsbook.net/forum/');
}
?>