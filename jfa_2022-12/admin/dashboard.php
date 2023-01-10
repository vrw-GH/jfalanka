<?php
if (!isset($_SESSION)) {
    session_start();
}
/* include all the settings */
include_once '../site_config.php';
/* admin config must load through site_config.php. If there is a fault, execute direct include */
include_once 'admin_config.php';

$subid = 0;
if (!empty($_SESSION['system_logged_status']) && !empty($_SESSION['system_logged_email']) &&
        $_SESSION['system_logged_mem_type_id'] < 5 && ($_SERVER['SERVER_NAME'] . $_SESSION['system_logged_uname'] == $_SESSION['server_domain_user'])) {
    if (isset($_GET['page'])) {
        if ($_GET['page'] == 'master_entries') {
            $pid = 1;
        } else if ($_GET['page'] == 'post_entries') {
            $pid = 2;
        } else {
            $pid = 0;
        }
    } else {
        $pid = 0;
    }
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

            <script src="colorbox/jquery.colorbox-min.js"></script>
            <link rel="stylesheet" href="colorbox/colorbox.css" />

            <link rel="stylesheet" href="../resources/css/Errors/validationEngine.jquery.css" type="text/css"/>
            <link rel="stylesheet" href="../resources/css/Errors/template.css" type="text/css"/>
            <script src="../resources/js/Languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
            <script src="../resources/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

            <link href="../resources/pagination_static/css/pagination.css" rel="stylesheet" type="text/css" />
            <link href="../resources/pagination_static/css/grey.css" rel="stylesheet" type="text/css" />

            <link rel="stylesheet" href="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/css/bootstrap-select.min.css">
            <script src="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>

            <script src="../resources/ckeditor/ckeditor.js"></script>
            <script>
                $(function () {
                    $('.dropdown').on('mouseenter mouseleave click tap', function () {
                        $(this).toggleClass("open");
                    });
                });
                /* Clear Messages */
                function msgClear() {
                    $(".alert").css("display", "none");
                }
                /* main Category Functions */
                function editMainCategory(cat_code) {
                    msgClear();
                    $("#mm").load('item_main_category_edit.php', {id: cat_code});
                }

                /* Sub Category Functions */
                function viewSubCategory(cat_code) {
                    msgClear();
                    $(".lastviewer").load('item_sub_category_filter.php', {cat_code: cat_code});
                }
                function editSubCategory(auto_num) {
                    msgClear();
                    $("#mm").load('item_sub_category_edit.php', {auto_num: auto_num});
                }
                function editBrand(brand_code) {
                    msgClear();
                    $("#mm").load('item_brands_edit.php', {brand_code: brand_code});
                }
                function editUnit(unit_code) {
                    msgClear();
                    $("#mm").load('item_units_edit.php', {unit_code: unit_code});
                }
                function editAppearance(app_code) {
                    msgClear();
                    $("#mm").load('item_appearance_edit.php', {app_code: app_code});
                }

                function filterPost(post_code) {
                    $("#ff").load('item_search_result.php', {post_code: post_code});
                }

                function editSlider(upload_id) {
                    msgClear();
                    $("#mm").load('slider_img_edit.php', {upload_id: upload_id});
                }

                function editNews(ann_id) {
                    msgClear();
                    $("#mm").load('news_events_edit.php', {ann_id: ann_id});
                }

                function editPage(page_id) {
                    msgClear();
                    $("#mm").load('pages_edit.php', {page_id: page_id});
                }

                function displayCity(d_id) {
                    msgClear();
                    $("#iii").load('city_filter.php', {d_id: d_id});
                }

            </script>
            <script>
                $(function () {
                    $("#ajaxbox").ajaxStart(function () {
                        $("#wait").css("display", "block");
                    });
                    $("#ajaxbox").ajaxComplete(function () {
                        $("#wait").css("display", "none");
                    });
                    $("#mm").ajaxStart(function () {
                        $("#wait").css("display", "block");
                    });
                    $("#mm").ajaxComplete(function () {
                        $("#wait").css("display", "none");
                    });

                });
            </script>
            <script>
                var timeout = 400;
                var closetimer = 100;
                var tpmenu = 0;

                function tpm_open() {
                    tpm_canceltimer();
                    tpm_close();
                    tpmenu = $(this).find('ul').css('visibility', 'visible');
                }

                function tpm_close() {
                    if (tpmenu)
                        tpmenu.css('visibility', 'hidden');
                }

                function tpm_timer() {
                    closetimer = window.setTimeout(tpm_close, timeout);
                }

                function tpm_canceltimer() {
                    if (closetimer) {
                        window.clearTimeout(closetimer);
                        closetimer = null;
                    }
                }

                $(function () {
                    $('#TPM > li').bind('mouseover', tpm_open)
                    $('#TPM > li').bind('mouseout', tpm_timer)
                });

                document.onclick = tpm_close;
            </script>
            <script>
                $(function () {
                    $(".iframe").colorbox({escKey: false, overlayClose: false, iframe: true, width: "94%", height: "94%"});
                });
            </script>
        </head>
        <body>
            <div id="wrapper">
                <div id="header">
                    <?php include_once 'menu.php'; ?>
                </div>
                <div id="wait"><img src='images/loader.gif'/><br/>Loading..</div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <div id="container">
                                <?php
                                if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                                    $category = $_POST['category'];
                                    $action = $_POST['action']; //insert/update/delete
                                    if ($category == 'itm_main_cat') {
                                        include_once 'controlers/mainCatControler.php';
                                    }
                                    if ($category == 'itm_sub_cat') {
                                        include_once 'controlers/subCatControler.php';
                                    }
                                    if ($category == 'itm_brand') {
                                        include_once 'controlers/brandControler.php';
                                    }
                                    if ($category == 'itm_unit') {
                                        include_once 'controlers/unitControler.php';
                                    }
                                    if ($category == 'itm_app') {
                                        include_once 'controlers/appearanceControler.php';
                                    }
                                    if ($category == 'post') {
                                        include_once 'controlers/postControler.php';
                                    }
                                    if ($category == 'portfolio') {
                                        include_once 'controlers/portfolioControler.php';
                                    }
                                    if ($category == 'company_info') {
                                        include_once 'controlers/companyInfoControler.php';
                                    }
                                    if ($category == 'slider_img') {
                                        include_once 'controlers/sliderControler.php';
                                    }
                                    if ($category == 'news') {
                                        include_once 'controlers/newsEventControler.php';
                                    }
                                    if ($category == 'comments') {
                                        include_once 'controlers/commentControler.php';
                                    }
                                    if ($category == 'pages') {
                                        include_once 'controlers/pagesControler.php';
                                    }
                                    if ($category == 'seo') {
                                        include_once 'controlers/seoControler.php';
                                    }
                                    if ($category == 'member') {
                                        include_once 'controlers/memberControler.php';
                                    }
                                }
                                ?>

                                <?php
                                if (isset($_GET['page'])) {
                                    if ($_GET['page'] == 'master_entries') {
                                        include_once('master_entries.php');
                                    } else if ($_GET['page'] == 'post_entries') {
                                        include_once('post_entries.php');
                                    } else if ($_GET['page'] == 'payment_entries') {
                                        include_once('payment_entries.php');
                                    } else if ($_GET['page'] == 'order_entries') {
                                        include_once('order_entries.php');
                                    } else {
                                        ?>
                                        <script type="text/javascript">
                                            window.location.href = "dashboard.php?page=master_entries";
                                        </script>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <script type="text/javascript">
                                        window.location.href = "dashboard.php?page=master_entries";
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
} else {
    header("location:" . WEB_HOST . "/" . ADMIN);
}
?>