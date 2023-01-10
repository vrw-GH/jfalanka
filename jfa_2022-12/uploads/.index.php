<?php
if (!isset($_SESSION)) {
    session_start();
}
/* include all the settings */
include_once '../site_config.php';
if (!empty($_SESSION['system_logged_status']) && !empty($_SESSION['system_logged_email']) && $_SESSION['system_logged_mem_type_id'] < 5) {

    function scan_dir($path) {
        $ite = new RecursiveDirectoryIterator($path);
        $bytestotal = 0;
        $nbfiles = 0;

        foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
            /* Ignore Unix hidden folders by their leading dot. */
            $aa = basename(pathinfo($filename, PATHINFO_DIRNAME));
            if (substr($aa, 0, 1) != '.') {
                if (pathinfo($filename, PATHINFO_EXTENSION) == 'jpg') {
                    $filesize = $cur->getSize();
                    $bytestotal+=$filesize;
                    $nbfiles++;
                    $files[] = $filename;
                }
            }
        }
        $size = $bytestotal;
        if ($size < 1024) {
            $size = $size . " Bytes";
        } elseif (($size < 1048576) && ($size > 1023)) {
            $size = round($size / 1024, 1) . " KB";
        } elseif (($size < 1073741824) && ($size > 1048575)) {
            $size = round($size / 1048576, 1) . " MB";
        } else {
            $size = round($size / 1073741824, 1) . " GB";
        }
        return array('total_files' => $nbfiles, 'total_size' => $size);
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Directory Contents</title>
            <link rel="stylesheet" href="<?php echo WEB_HOST ?>/uploads/.style.css">
            <link rel="stylesheet" type="text/css" href="<?php echo WEB_HOST ?>/<?php echo $website['boostrap_folder']; ?>/css/bootstrap.css" media="all"/>
            <link rel="stylesheet" type="text/css" href="<?php echo WEB_HOST ?>/resources/css/style.css" media="all"/>

            <script type="text/javascript" src="<?php echo WEB_HOST ?>/<?php echo $website['jquery_min_js']; ?>" charset="utf-8"></script>
            <script type="text/javascript" src="<?php echo WEB_HOST ?>/<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8"></script>
            <script src="<?php echo WEB_HOST ?>/uploads/.sorttable.js"></script>
        </head>
        <body>
            <div class="container-fluid voffset-2">
                <div class="row">
                    <div class="col-xs-12">
                        <a href=".index.php" class="btn"><strong><span class="glyphicon glyphicon-file"></span> File Index</strong></a>&nbsp;|&nbsp;<a href=".image_index.php" class="btn"><strong><span class="glyphicon glyphicon-picture"></span> Image Index</strong></a> | <a href="<?php echo '../' . ADMIN . '/file_upload.php'; ?>" class="btn"><strong><span class="glyphicon glyphicon-cloud-upload"></span> Upload image/file</strong></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-danger voffset-1">
                        <strong>Warning!</strong> Careful when you deleting files /images. Some Files/Images may have linked with database server and prompt database error, 404 error with frontend users (Visitors)
                    </div>
                    <div class="col-xs-12 voffset-2">
                        <h1>File Contents</h1>
                        <?php
                        if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                            $action = $_POST['action'];
                            if ($action == 'delete_file') {
                                include_once '../' . ADMIN . '/controlers/fileControler.php';
                            }
                        }

                        $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                        ?>
                        <table class="sortable table table-bordered voffset-1">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Date Modified</th>
                                    <th>Opt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /* Adds pretty filesizes */

                                function pretty_filesize($file) {
                                    $size = filesize($file);
                                    if ($size < 1024) {
                                        $size = $size . " Bytes";
                                    } elseif (($size < 1048576) && ($size > 1023)) {
                                        $size = round($size / 1024, 1) . " KB";
                                    } elseif (($size < 1073741824) && ($size > 1048575)) {
                                        $size = round($size / 1048576, 1) . " MB";
                                    } else {
                                        $size = round($size / 1073741824, 1) . " GB";
                                    }
                                    return $size;
                                }

                                /* Checks to see if veiwing hidden files is enabled */
                                if ($_SERVER['QUERY_STRING'] == "hidden") {
                                    $hide = "";
                                    $ahref = "./";
                                    $atext = "Hide";
                                } else {
                                    $hide = ".";
                                    $ahref = "./?hidden";
                                    $atext = "Show";
                                }

                                /* Opens directory */
                                $myDirectory = opendir(".");

                                /* Gets each entry */
                                while ($entryName = readdir($myDirectory)) {
                                    $dirArray[] = $entryName;
                                }

                                /* Closes directory */
                                closedir($myDirectory);

                                /* Counts elements in array */
                                $indexCount = count($dirArray);

                                /* Sorts files */
                                sort($dirArray);

                                /* Loops through the array of files */
                                for ($index = 0; $index < $indexCount; $index++) {
                                    /* Decides if hidden files should be displayed, based on query above. */
                                    if (substr("$dirArray[$index]", 0, 1) != $hide) {

                                        /* Resets Variables */
                                        $favicon = "";
                                        $class = "file";

                                        /* Gets File Names */
                                        $name = $dirArray[$index];
                                        $namehref = $dirArray[$index];

                                        /* Gets Date Modified */
                                        $modtime = date("M j Y g:i A", filemtime($dirArray[$index]));
                                        $timekey = date("YmdHis", filemtime($dirArray[$index]));


                                        /* Separates directories, and performs operations on those directories */
                                        if (is_dir($dirArray[$index])) {
                                            $extn = "&lt;Directory&gt;";
                                            $files_array = scan_dir($dirArray[$index]);
                                            $size = "Total: {$files_array['total_files']} files, {$files_array['total_size']} \n";
                                            $sizekey = "0";
                                            $class = "dir";

                                            /* Gets favicon.ico, and displays it, only if it exists. */
                                            if (file_exists("$namehref/favicon.ico")) {
                                                $favicon = " style='background-image:url($namehref/favicon.ico);'";
                                                $extn = "&lt;Website&gt;";
                                            }

                                            /* Cleans up . and .. directories */
                                            if ($name == ".") {
                                                $name = ". (Current Directory)";
                                                $extn = "&lt;System Dir&gt;";
                                                $favicon = " style='background-image:url($namehref/.favicon.ico);'";
                                            }
                                            if ($name == "..") {
                                                $name = ".. (Parent Directory)";
                                                $extn = "&lt;System Dir&gt;";
                                            }
                                        }

                                        /* File-only operations */ else {
                                            /* Gets file extension */
                                            $extn = pathinfo($dirArray[$index], PATHINFO_EXTENSION);
                                            switch ($extn) {
                                                case "png": $extn = "PNG Image";
                                                    break;
                                                case "jpg": $extn = "JPEG Image";
                                                    break;
                                                case "jpeg": $extn = "JPEG Image";
                                                    break;
                                                case "svg": $extn = "SVG Image";
                                                    break;
                                                case "gif": $extn = "GIF Image";
                                                    break;
                                                case "ico": $extn = "Windows Icon";
                                                    break;

                                                case "txt": $extn = "Text File";
                                                    break;
                                                case "log": $extn = "Log File";
                                                    break;
                                                case "htm": $extn = "HTML File";
                                                    break;
                                                case "html": $extn = "HTML File";
                                                    break;
                                                case "xhtml": $extn = "HTML File";
                                                    break;
                                                case "shtml": $extn = "HTML File";
                                                    break;
                                                case "php": $extn = "PHP Script";
                                                    break;
                                                case "js": $extn = "Javascript File";
                                                    break;
                                                case "css": $extn = "Stylesheet";
                                                    break;

                                                case "pdf": $extn = "PDF Document";
                                                    break;
                                                case "xls": $extn = "Spreadsheet";
                                                    break;
                                                case "xlsx": $extn = "Spreadsheet";
                                                    break;
                                                case "doc": $extn = "Microsoft Word Document";
                                                    break;
                                                case "docx": $extn = "Microsoft Word Document";
                                                    break;

                                                case "zip": $extn = "ZIP Archive";
                                                    break;
                                                case "htaccess": $extn = "Apache Config File";
                                                    break;
                                                case "exe": $extn = "Windows Executable";
                                                    break;

                                                default: if ($extn != "") {
                                                        $extn = strtoupper($extn) . " File";
                                                    } else {
                                                        $extn = "Unknown";
                                                    } break;
                                            }

                                            /* Gets and cleans up file size */
                                            $size = pretty_filesize($dirArray[$index]);
                                            $sizekey = filesize($dirArray[$index]);
                                        }
                                        $short_extn = pathinfo($dirArray[$index], PATHINFO_EXTENSION);
                                        if ($short_extn == 'pdf' || $short_extn == 'txt' || $short_extn == 'xls' ||
                                                $short_extn == 'xlsx' || $short_extn == 'doc' || $short_extn == 'docx') {
                                            /* Output */
                                            echo("
                                            <tr class='$class'>
                                                <td><a href='./$namehref'$favicon class='gal'>$name</a>
                                                    <div class='input-group'>
                                                        <div class='copytext' id='copy" . $index . "'>" . WEB_HOST . "/uploads/$namehref</div>
                                                        <div class='input-group-addon' style='padding:0'>
                                                            <button class='btn btn-xs' data-clipboard-action='copy' data-clipboard-target='#copy" . $index . "'><span class='glyphicon glyphicon-copy'></span> Copy</button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><a href='./$namehref' target='_blank'>$extn</a></td>
                                                <td sorttable_customkey='$sizekey'><a href='./$namehref' target='_blank'>$size</a></td>
                                                <td sorttable_customkey='$timekey'><a href='./$namehref' target='_blank'>$modtime</a></td>
                                                <td>
                                                <form class='form-inline' id='del$index' action='' method='POST'>
                                                    <input type='hidden' name='category' value='file_manager'>
                                                    <input type='hidden' name='action' value='delete_file'>
                                                    <input type='hidden' name='key' value='" . $_SESSION['key'] . "'/>
                                                    <input type='hidden' name='upload_path' value='$name'>
                                                    <button type='submit' class='btn btn-danger btn-xs'>delete</button>
                                                </form>
                                                </td>
                                            </tr>"
                                            );
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <h2><?php // echo("<a href='$ahref'>$atext hidden files</a>");     ?></h2>
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
        </body>
    </html>
    <?php
} else {
    header('location: ../index.php');
}
?>