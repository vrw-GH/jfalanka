<?php
if (!isset($_SESSION)) {
    session_start();
}

include_once 'site_config.php';
include_once 'admin/admin_config.php';

include_once 'models/dbConfig.php';
include_once 'models/encryption.php';

$myCon = new dbConfig();
$enObj = new encryption();
$myCon->connect();

if (isset($_REQUEST['caro_type'])) {
    $caro_type = $_REQUEST['caro_type'];
} else {
    $caro_type = null;
}
if (isset($_REQUEST['caro_link_popup_type'])) {
    $caro_link_popup_type = $_REQUEST['caro_link_popup_type'];
} else {
    $caro_link_popup_type = null;
}
if (isset($_REQUEST['code_key'])) {
    $code_key = $_REQUEST['code_key'];
} else {
    $code_key = null;
}

$pid = 1;
$canonical_url = WEB_HOST . '/carousel_popup.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head<?php
    if (SITE_SEO != 'basic') {
        echo ' ' . OG_PRIFIX;
    }
    ?>>
        <meta charset="UTF-8">
        <title><?php echo $config['seo']['seo_title']; ?></title>
        <meta name="description" content="<?php echo $config['seo']['seo_dscp'] ?>">
        <meta name="keywords" content="<?php echo $config['seo']['seo_keywords'] ?>">
        <meta name="robots" content="index,follow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=1">
        <?php
        /* enable google analytics */
        $seo_social = true;
        $seo_google = true;
        include_once './header_css_js.php';
        ?>
        <link href="<?php echo WEB_HOST ?>/resources/pagination_static/css/pagination.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo WEB_HOST ?>/resources/pagination_static/css/grey.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div class="container-fluid common-block">
                    <div class="row">
                        <div class="col-xs-12 col-pdn-both-0 crousel-popup">
                            <?php
                            if ($caro_type != null && $caro_link_popup_type != null && $code_key != null) {
                                $upload_type_id = null;
                                if ($caro_type == 'main_cat') {
                                    $main_stat = "SELECT m.cat_name, m.cat_details, u.upload_path FROM item_main_category m LEFT JOIN upload_data u ON "
                                            . "m.cat_code = u.upload_ref AND u.upload_type_id = 2 AND u.featured = 1 WHERE "
                                            . "m.cat_code = '" . $code_key . "' LIMIT 1";
                                    $upload_type_id = 2;
                                } else if ($caro_type == 'sub_cat') {
                                    $main_stat = "SELECT s.sub_name, s.sub_details, u.upload_path FROM item_sub_category s LEFT JOIN upload_data u ON "
                                            . "s.auto_num = u.upload_ref AND u.upload_type_id = 3 AND u.featured = 1 WHERE "
                                            . "s.auto_num = '" . $code_key . "' LIMIT 1";
                                    $upload_type_id = 3;
                                } else {
                                    $main_stat = "SELECT p.post_name, p.post_details, u.upload_path FROM posts p "
                                            . "LEFT JOIN upload_data u ON p.post_code = u.upload_ref AND u.upload_type_id = 6 AND u.featured = 1 "
                                            . "WHERE p.post_code = '" . $code_key . "' LIMIT 1";
                                    $upload_type_id = 6;
                                }
                                $query_m = $main_stat;
                                $result_m = $myCon->query($query_m);
                                $aa = 1;
                                while ($row_m = mysqli_fetch_assoc($result_m)) {
                                    $title = '';
                                    $details = '';
                                    $upload_path = $row_m['upload_path'];
                                    if ($caro_type == 'main_cat') {
                                        $title = $row_m['cat_name'];
                                        $details = $row_m['cat_details'];
                                    } else if ($caro_type == 'sub_cat') {
                                        $title = $row_m['sub_name'];
                                        $details = $row_m['sub_details'];
                                    } else {
                                        $title = $row_m['post_name'];
                                        $details = $row_m['post_details'];
                                    }
                                    ?>
                                    <div class="col-xs-12">
                                        <h2><?php echo $title; ?></h2>
                                    </div>
                                    <div class="col-xs-12 col-pdn-both-0 voffset-1">
                                        <?php
                                        if ($caro_link_popup_type == 'descp') {
                                            echo '<div class="col-xs-12 col-sm-5 voffset-2 voffset-b-4">';
                                            ?>
                                            <img src="<?php echo WEB_HOST . '/uploads/' . $upload_path; ?>" class="img-responsive img-center" alt="<?php echo $title; ?>"/>
                                            <?php
                                        } else {
                                            if ($caro_link_popup_type == 'gallery') {
                                                echo '<div class="col-xs-12 voffset-2 voffset-b-4">';
                                            } else if ($caro_link_popup_type == 'both') {
                                                echo '<div class="col-xs-12 col-sm-5 voffset-2 voffset-b-4">';
                                            }
                                            /* Gallery Start */
                                            $query_gal = "SELECT COUNT(*) AS total FROM upload_data WHERE "
                                                    . "upload_type_id = '" . $upload_type_id . "'  AND featured !=1 "
                                                    . "AND upload_ref = " . (int) $code_key;
                                            $result_gal = $myCon->query($query_gal);
                                            $count_gal = mysqli_num_rows($result_gal);
                                            if ($count_gal >= 1) {
                                                ?>
                                                <!-- Gallery Start -->
                                                <div class="col-xs-12 voffset-2 voffset-b-3 col-pdn-both-0">
                                                    <?php
                                                    /* enable/disable pagination */
                                                    $gal_pagi = true;
                                                    /* enable/disable thumbs */
                                                    $sli_thumbs = true;
                                                    /* enable/disable zoom */
                                                    $sli_zoom = false;

                                                    /* without Select */
                                                    $statement = "upload_path FROM upload_data WHERE "
                                                            . "upload_ref=" . (int) $code_key . " AND "
                                                            . "upload_type_id = '" . $upload_type_id . "' AND featured !=1";

                                                    if ($gal_pagi == true) {
                                                        include_once 'pagination_function.php';
                                                        $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
                                                        /* page URL */
                                                        $url = $canonical_url . '?';
                                                        /* Item Limit */
                                                        $limit = 20;
                                                        $startpoint = ($pagepagi * $limit) - $limit;

                                                        $pagination_statment = "upload_data WHERE upload_ref=" . (int) $code_key . " AND "
                                                                . "upload_type_id = '" . $upload_type_id . "' AND featured !=1";

                                                        $query_slider = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
                                                    } else {
                                                        $query_slider = "SELECT " . $statement;
                                                    }
                                                    $result_slider = $myCon->query($query_slider);
                                                    if ($result_slider) {
                                                        $count_slider = mysqli_num_rows($result_slider);
                                                    } else {
                                                        $count_slider = 0;
                                                    }
                                                    if ($count_slider > 0) {
                                                        include 'slider_thumb.php';
                                                    }
                                                    ?>
                                                </div>
                                                <!-- Gallery END -->
                                                <?php
                                            }
                                        }
                                        echo '</div>';
                                        if ($caro_link_popup_type == 'both') {
                                            ?>
                                            <div class="col-xs-12 col-sm-7 voffset-b-3 c-content">
                                                <?php echo $details; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once './footer_css_js.php'; ?>
    </body>
</html>