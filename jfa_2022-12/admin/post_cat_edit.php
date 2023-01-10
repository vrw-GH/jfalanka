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

$post_code = $_REQUEST['post_code'];

$myCon = new dbConfig();
$myCon->connect();
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

        <script type="text/javascript" src="../<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8"></script>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_css']; ?>" />
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_theme_css']; ?>" />
        <script src="../<?php echo $website['jquery_ui_js']; ?>"></script>

        <link rel="stylesheet" href="../resources/css/Errors/validationEngine.jquery.css" type="text/css"/>
        <link rel="stylesheet" href="../resources/css/Errors/template.css" type="text/css"/>
        <script src="../resources/js/Languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
        <script src="../resources/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

        <script src="../resources/ckeditor/ckeditor.js"></script>

        <link href="css/menu.css" media="screen, projection" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            var timeout = 200;
            var closetimer = 0;
            var ddmenuitem = 0;

            function jsddm_open() {
                jsddm_canceltimer();
                jsddm_close();
                ddmenuitem = $(this).find('ul').css('visibility', 'visible');
            }
            function jsddm_close() {
                if (ddmenuitem)
                    ddmenuitem.css('visibility', 'hidden');
            }
            function jsddm_timer() {
                closetimer = window.setTimeout(jsddm_close, timeout);
            }
            function jsddm_canceltimer() {
                if (closetimer)
                {
                    window.clearTimeout(closetimer);
                    closetimer = null;
                }
            }
            $(document).ready(function () {
                $('#jsddm > li').bind('mouseover', jsddm_open)
                $('#jsddm > li').bind('mouseout', jsddm_timer)
            });
            document.onclick = jsddm_close;
        </script>
        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });
            });
            function filterByMainEntry(cat_code) {
                $("#sss").load('item_item_main_cat_filter.php', {type: 'subcat', cat_code: cat_code});
                $("#bbb").load('item_item_main_cat_filter.php', {type: 'brand', cat_code: cat_code});
            }
        </script>
    </head>
    <body class="white">
        <div class="container voffset-2">
            <ul id="jsddm" class="poppage">
                <li><a href="post_edit.php?post_code=<?php echo $post_code; ?>">Edit Details</a></li>
                <li class="act"><a href="post_cat_edit.php?post_code=<?php echo $post_code; ?>">Edit Category</a></li>
            </ul>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['key'] == $_POST['key']) {
                include_once 'controlers/postControler.php';
            }
            $myCon = new dbConfig();
            $myCon->connect();


            if (!isset($_SESSION['key'])) {
                $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
            }
            ?>
            <div id="outer-box">
                <div id="mm">
                    <h1>Edit an Post Category</h1>
                    <?php
                    $query_main = "SELECT p.*, u.upload_id, u.upload_path  FROM posts p LEFT JOIN "
                            . "upload_data u ON p.post_code = u.upload_ref AND u.upload_type_id = 6 "
                            . "AND u.featured = 1 WHERE p.post_code ='" . $post_code . "' LIMIT 1";
                    $result_main = $myCon->query($query_main);
                    while ($row_main = mysqli_fetch_assoc($result_main)) {
                        ?>
                        <form id="formID" method="post" action="" class="allforms" enctype="multipart/form-data">
                            <input type="hidden" name="category" value="post">
                            <input type="hidden" name="action" value="edit_cat">
                            <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                            <input type="hidden" name="user_name" value="<?php echo $_SESSION['system_logged_uname']; ?>" >
                            <input type="hidden" name="post_code" value="<?php echo $post_code; ?>" >
                            <?php
                            if ($website['config']['main_cat'] == true || $website['config']['sub_cat'] == true) {
                                ?>
                                <h5 class="col-xs-12 text-warning voffset-1 voffset-b-1">Post Category details</h5>
                                <?php if ($website['config']['main_cat'] == true) { ?>
                                    <div class="form-group col-xs-12">
                                        <label><span class="text-danger">*</span>Select Main entry:-</label>
                                        <?php
                                        $query = "SELECT * FROM item_main_category WHERE active !=0 ORDER BY cat_name ASC";
                                        $result = $myCon->query($query);

                                        /* get checked catcodes if post fail ----- */
                                        $catcodes = array();
                                        if (!empty($_POST['cat_code'])) {
                                            foreach ($_POST['cat_code'] as $cc) {
                                                array_push($catcodes, $cc);
                                            }
                                        } else {
                                            $query_sub = "SELECT cat_code FROM posts_sub_cat WHERE "
                                                    . "post_code = '" . $row_main['post_code'] . "' GROUP BY cat_code";
                                            $result_sub = $myCon->query($query_sub);
                                            while ($row_sub = mysqli_fetch_assoc($result_sub)) {
                                                array_push($catcodes, $row_sub['cat_code']);
                                            }
                                        }
                                        /* ----- */
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <div class="checkbox col-xs-12">
                                                <label>
                                                    <input class="validate[required] post_cat_code" type="checkbox" name="cat_code[]" value="<?php echo($row['cat_code']); ?>" 
                                                    <?php
                                                    if (in_array($row['cat_code'], $catcodes)) {
                                                        echo 'checked';
                                                    }
                                                    ?>> <?php echo($row['cat_name']); ?>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                } if ($website['config']['sub_cat'] == true) {
                                    /* get checked subcodes if post fail ----- */
                                    $subcodes = array();
                                    if (!empty($_POST['sub_code'])) {
                                        foreach ($_POST['sub_code'] as $sc) {
                                            array_push($subcodes, $sc);
                                        }
                                    } else {
                                        $query_sub = "SELECT cat_code, sub_code FROM posts_sub_cat WHERE "
                                                . "post_code = '" . $row_main['post_code'] . "'";
                                        $result_sub = $myCon->query($query_sub);
                                        while ($row_sub = mysqli_fetch_assoc($result_sub)) {
                                            array_push($subcodes, $row_sub['cat_code'] . $row_sub['sub_code']);
                                        }
                                    }
                                    /* ----- */
                                    ?>
                                    <div class="form-group col-xs-12">
                                        <label><span class="text-danger">*</span>Sub Entry Name :-</label>
                                        <div id="sss" class="col-xs-12">
                                            <?php
                                            if (isset($catcodes)) {
                                                /* generating query part */
                                                if (isset($catcodes)) {
                                                    $count = count($catcodes);
                                                } else {
                                                    $count = 0;
                                                }
                                                $q_prt = "";
                                                $aa = $count;
                                                if ($count >= 1) {
                                                    foreach ($catcodes as $cat_code => $value) {
                                                        if ($count == 1) {
                                                            $q_prt = "s.cat_code= " . $value;
                                                        } else {
                                                            if ($aa == 1) {
                                                                $q_prt.="s.cat_code= " . $value;
                                                            } else {
                                                                $q_prt.="s.cat_code= " . $value . " OR ";
                                                            }
                                                            $aa-=1;
                                                        }
                                                    }
                                                } else {
                                                    $q_prt = "s.cat_code = 0";
                                                }
                                                $query = "SELECT s.*, m.cat_name FROM item_sub_category s LEFT JOIN "
                                                        . "item_main_category m ON s.cat_code = m.cat_code WHERE ($q_prt) "
                                                        . "AND s.active='1' ORDER BY s.cat_code, s.sub_name ASC";
                                                $result = $myCon->query($query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <div class="checkbox col-xs-12 col-md-6">
                                                        <label>
                                                            <input class="post_sub_code" type="checkbox" name="sub_code[]" value="<?php echo($row['cat_code'] . $row['sub_code']); ?>" 
                                                            <?php
                                                            if (isset($subcodes)) {
                                                                if (in_array($row['cat_code'] . $row['sub_code'], $subcodes)) {
                                                                    echo 'checked';
                                                                }
                                                            }
                                                            ?>> <?php echo($row['sub_name'] . ' <span class="text-warning">[' . $row['cat_name'] . ']</span>'); ?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <div class="checkbox col-xs-12">
                                                    <label>
                                                        <input class="post_sub_code" type="checkbox" name="sub_code[]" disabled=""> Please select main entry first
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <script>
                                    $(function () {
                                        $('.post_cat_code').click(function () {
                                            var cat_code = [];
                                            var sub_code = [];
                                            $('.post_sub_code:checkbox:checked').each(function (i) {
                                                sub_code[i] = $(this).val();
                                            });
                                            $('.post_cat_code:checkbox:checked').each(function (i) {
                                                cat_code[i] = $(this).val();
                                            });
                                            $("#sss").load('item_item_main_cat_filter.php', {type: 'subcat', cat_code: cat_code, sub_code: sub_code});
                                        });
                                    });
                                </script>
                            <?php } ?>
                            <div class="form-group col-xs-12 text-right">
                                <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-edit"></span> Update Post</button>
                            </div>
                            <!-- forms END -->
                        </form>
                    <?php } ?>
                </div>

            </div>
        </div>
    </body>
</html>