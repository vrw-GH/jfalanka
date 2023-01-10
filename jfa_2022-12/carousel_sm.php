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

/* define carousel type */
if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'main_cat') {
    $caro_type = 'main_cat';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'sub_cat') {
    $caro_type = 'sub_cat';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'brand') {
    $caro_type = 'brand';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'post_main_cat') {
    $caro_type = 'post_main_cat';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'post_sub_cat') {
    $caro_type = 'post_sub_cat';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'post_brand') {
    $caro_type = 'post_brand';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'post_featured') {
    $caro_type = 'post_featured';
} else if (isset($_REQUEST['caro_type']) && $_REQUEST['caro_type'] == 'post_discounted') {
    $caro_type = 'post_discounted';
} else {
    $caro_type = 'post';
}

/* details show */
if (isset($_REQUEST['caro_details'])) {
    $caro_details = $_REQUEST['caro_details'];
} else {
    $caro_details = false;
}
if (isset($_REQUEST['caro_link'])) {
    $caro_link = $_REQUEST['caro_link'];
} else {
    $caro_link = false;
}
if (isset($_REQUEST['caro_link_text'])) {
    $caro_link_text = $_REQUEST['caro_link_text'];
} else {
    $caro_link_text = '';
}
if (isset($_REQUEST['caro_link_popup'])) {
    $caro_link_popup = $_REQUEST['caro_link_popup'];
} else {
    $caro_link_popup = false;
}
if (isset($_REQUEST['caro_link_popup_type'])) {
    $caro_link_popup_type = $_REQUEST['caro_link_popup_type'];
} else {
    $caro_link_popup_type = '';
}
if (isset($_REQUEST['caro_link_text_popup'])) {
    $caro_link_text_popup = $_REQUEST['caro_link_text_popup'];
} else {
    $caro_link_text_popup = '';
}
if (isset($_REQUEST['caro_structure'])) {
    $caro_structure = $_REQUEST['caro_structure'];
} else {
    $caro_structure = 'product';
}

/* no of items show */
$caro_items = $_REQUEST['caro_items'];
$caro_col = (int) 12 / $caro_items;

if ($caro_type == 'main_cat') {
    $statment = "SELECT m.*, u.upload_path FROM item_main_category m LEFT JOIN upload_data u ON "
            . "m.cat_code = u.upload_ref AND u.upload_type_id = 2 AND u.featured = 1 WHERE "
            . "m.active = 1 ORDER BY m.cat_order ASC";
} else if ($caro_type == 'sub_cat') {
    $statment = "SELECT s.*, u.upload_path FROM item_sub_category s LEFT JOIN upload_data u ON "
            . "s.auto_num = u.upload_ref AND u.upload_type_id = 3 AND u.featured = 1 WHERE "
            . "s.active = 1 ORDER BY s.auto_num DESC";
} else if ($caro_type == 'brand') {
    $statment = "SELECT b.*, u.upload_path FROM item_brand b LEFT JOIN upload_data u ON "
            . "b.brand_code = u.upload_ref AND u.upload_type_id = 4 AND u.featured = 1 WHERE "
            . "b.active = 1 ORDER BY b.brand_code DESC";
} else if ($caro_type == 'post_main_cat') {
    $statment = "SELECT p.post_url_slug, p.post_name, p.post_details, u.upload_path, ps.post_code, m.cat_url_slug, m.cat_name "
            . "FROM posts_sub_cat ps JOIN item_main_category m ON ps.cat_code = m.cat_code JOIN "
            . "posts p ON p.post_code = ps.post_code LEFT JOIN upload_data u ON "
            . "p.post_code = u.upload_ref AND u.upload_type_id = 6 AND u.featured = 1 "
            . "WHERE m.cat_code = '" . $_REQUEST['caro_cat_code'] . "' GROUP BY ps.post_code";
} else if ($caro_type == 'post') {
    $statment = "SELECT p.post_url_slug, p.post_name, p.post_details, u.upload_path, ps.post_code, m.cat_url_slug, m.cat_name "
            . "FROM posts_sub_cat ps JOIN item_main_category m ON ps.cat_code = m.cat_code JOIN "
            . "posts p ON p.post_code = ps.post_code LEFT JOIN upload_data u ON "
            . "p.post_code = u.upload_ref AND u.upload_type_id = 6 AND u.featured = 1 "
            . "WHERE p.active=1 GROUP BY ps.post_code";
}
?>
<div class="container-fluid">
    <div class="carousel slide" id="carousel-front">
        <div class="carousel-inner">
            <?php
            $query_m = $statment;
            $result_m = $myCon->query($query_m);
            $aa = 1;
            while ($row_m = mysqli_fetch_assoc($result_m)) {
                $carousel_title = '';
                $carousel_url = '';
                $carousel_details = '';
                $code_key = 0;
                if ($caro_type == 'main_cat') {
                    $code_key = $row_m['cat_code'];
                    $carousel_title = $row_m['cat_name'];
                    if ($website['custom_url']['main_entry'] == true && !empty($row_m['custom_url'])) {
                        $carousel_url = $row_m['custom_url'];
                    } else {
                        $carousel_url = 'category/' . $row_m['cat_url_slug'];
                    }
                    $carousel_details = str_replace('\r\n', '', strip_tags($row_m['cat_details']));
                } else if ($caro_type == 'sub_cat') {
                    $code_key = $row_m['auto_num'];
                    $carousel_title = $row_m['sub_name'];
                    $carousel_url = 'category/' . $row_m['cat_url_slug'] . '/' . $row_m['sub_url_slug'];
                    $carousel_details = str_replace('\r\n', '', strip_tags($row_m['cat_details']));
                } else if ($caro_type == 'brand') {
                    $code_key = $row_m['brand_code'];
                    $carousel_title = $row_m['brand_name'];
                    $carousel_url = 'brand/' . $row_m['brand_code'];
                } else {
                    $code_key = $row_m['post_code'];
                    if (strlen($row_m['post_name']) > $_REQUEST['post_name_limit']) {
                        $carousel_title = substr($row_m['post_name'], 0, $_REQUEST['post_name_limit']) . '...';
                    } else {
                        $carousel_title = $row_m['post_name'];
                    }
                    $carousel_url = WEB_HOST . '/product/' . $row_m['post_url_slug'];
                    if (strlen($row_m['post_details']) > $_REQUEST['post_details_limit']) {
                        $carousel_details = str_replace('\r\n', '', strip_tags(substr($myCon->escapeString(strip_tags($row_m['post_details'])), 0, $_REQUEST['post_details_limit'])) . '...');
                    } else {
                        $carousel_details = str_replace('\r\n', '', strip_tags($row_m['post_details']));
                    }
                }
                ?>
                <div class="item <?php
                if ($aa == 1) {
                    echo 'active';
                }
                ?>">
                    <div class="col-xs-<?php echo $caro_col; ?>">
                        <!-- front-pro-items START -->
                        <div class="front-pro-items">
                            <div class="col-xs-12 voffset-2 col-pdn-both-0">
                                <?php if ($caro_structure == 'product') { ?>
                                    <h1 class="itm-name"><?php echo $carousel_title; ?></h1> 
                                    <?php
                                    if (isset($row_m['upload_path'])) {
                                        if ($caro_link == true) {
                                            ?>
                                            <a href="<?php echo $carousel_url; ?>"><img src="<?php echo WEB_HOST ?>/uploads/<?php echo($row_m['upload_path']) ?>" class="img-responsive" alt="<?php echo $carousel_title; ?>"/></a>
                                        <?php } else {
                                            ?>
                                            <img src="<?php echo WEB_HOST ?>/uploads/<?php echo($row_m['upload_path']) ?>" class="img-responsive" alt="<?php echo $carousel_title; ?>"/> 
                                            <?php
                                        }
                                    }
                                    if ($caro_details == true) {
                                        ?>
                                        <div class="text-justify voffset-1 voffset-b-1">
                                            <?php echo $carousel_details; ?>
                                        </div>
                                        <?php
                                    }
                                    if ($caro_link == true) {
                                        ?>
                                        <div class="col-xs-12 voffset-3 voffset-b-2 text-right col-pdn-both-0">
                                            <?php
                                            if ($caro_link_popup == true) {
                                                $pop_link = WEB_HOST . '/carousel_popup.php?caro_link_popup_type=' . $caro_link_popup_type . '&caro_type=' . $caro_type . '&code_key=' . $code_key;
                                                echo '<a href="' . $pop_link . '" class="fancybox fancybox.iframe">' . $caro_link_text_popup . '</a> &nbsp;|&nbsp; ';
                                            }
                                            ?>
                                            <a href="<?php echo $carousel_url; ?>" class=""><?php echo $caro_link_text; ?></a>
                                        </div>
                                        <?php
                                    }
                                    /* portfolios  */
                                } else if ($caro_structure == 'portfolio') {
                                    if ($caro_details == true) {
                                        ?>
                                        <div class="text-justify voffset-1 voffset-b-1">
                                            <h1>"</h1>
                                            <?php echo $carousel_details; ?>
                                            <h1 class="text-right">"</h1>
                                        </div>
                                        <?php
                                    }
                                    if (isset($row_m['upload_path'])) {
                                        if ($caro_link == true) {
                                            ?>
                                            <a href="<?php echo $carousel_url; ?>"><img src="<?php echo WEB_HOST ?>/uploads/<?php echo($row_m['upload_path']) ?>" class="img-responsive" alt="<?php echo $carousel_title; ?>"/></a>
                                        <?php } else {
                                            ?>
                                            <img src="<?php echo WEB_HOST ?>/uploads/<?php echo($row_m['upload_path']) ?>" class="img-responsive" alt="<?php echo $carousel_title; ?>"/> 
                                            <?php
                                        }
                                    }
                                    ?>
                                    <h4 class="col-xs-12 col-pdn-both-0"><?php echo $carousel_title; ?></h4>
                                    <?php if ($caro_link == true) { ?>
                                        <div class="col-xs-12 voffset-2 text-right col-pdn-both-0">
                                            <a href="<?php echo $carousel_url; ?>" class="btn"><?php echo $caro_link_text; ?> <span class="glyphicon glyphicon-chevron-right"></span></a>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <!-- front-pro-items  END -->
                    </div>
                </div>
                <?php
                $aa += 1;
            }
            ?>
        </div>
        <a class="left carousel-control" href="#carousel-front" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
        <a class="right carousel-control" href="#carousel-front" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
    </div>
</div>
<script>
    $('#carousel-front').carousel({
        interval: 8000
    });
<?php if ($caro_items > 1) { ?>
        $('#carousel-front .item').each(function () {
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

    <?php if ($caro_items == 3) { ?>
                if (next.next().length > 0) {
                    next.next().children(':first-child').clone().appendTo($(this));
                }
                else {
                    $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
                }
    <?php } else if ($caro_items == 2) { ?>
                for (var i = 0; i < 0; i++) {
                    next = next.next();
                    if (!next.length) {
                        next = $(this).siblings(':first');
                    }
                    next.children(':first-child').clone().appendTo($(this));
                }
    <?php } ?>
        });
<?php } ?>
</script>