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
        <!-- calling before jquery ui -->
        <script type="text/javascript" src="../<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8"></script>

        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_css']; ?>" media="all"/>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_theme_css']; ?>" media="all"/>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_structure_css']; ?>" media="all"/>
        <script src="../<?php echo $website['jquery_ui_js']; ?>"></script>

        <link rel="stylesheet" href="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/css/bootstrap-select.min.css">
        <script src="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>

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
                <li class="act"><a href="post_edit.php?post_code=<?php echo $post_code; ?>">Edit Post</a></li>
                <li><a href="post_cat_edit.php?post_code=<?php echo $post_code; ?>">Edit Category</a></li>
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
                    <h1>Edit an Post</h1>
                    <?php
                    $query_main = "SELECT p.*, u.upload_id, u.upload_path  FROM posts p LEFT JOIN "
                            . "upload_data u ON p.post_code = u.upload_ref AND u.upload_type_id = 6 "
                            . "AND u.featured = 1 WHERE p.post_code ='" . $post_code . "' LIMIT 1";
                    $result_main = $myCon->query($query_main);
                    while ($row_main = mysqli_fetch_assoc($result_main)) {
                        ?>
                        <form id="formID" method="post" action="" class="allforms" enctype="multipart/form-data">
                            <input type="hidden" name="category" value="post">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                            <input type="hidden" name="user_name" value="<?php echo $_SESSION['system_logged_uname']; ?>" >
                            <input type="hidden" name="post_code" value="<?php echo $post_code; ?>" >
                            <h5 class="col-xs-12 text-warning voffset-1 voffset-b-1">Enter your post details</h5>
                            <div class="form-group col-xs-12">
                                <label><span class="text-danger">*</span>Display Name :-</label>
                                <input name="post_name" type="text" class="validate[required] form-control" maxlength="200" value="<?php
                                if (isset($_POST['post_name'])) {
                                    echo $_POST['post_name'];
                                } else {
                                    echo $row_main['post_name'];
                                }
                                ?>" placeholder="Post Name">
                            </div>
                            <div class="form-group col-xs-6 col-sm-4 col-xxs-full-width">
                                <label>Image <span class="text-warning">(Featured)</span> :-</label>
                                <input type="file" name="post_img" class="form-control">
                                <p class="help-block">Image must be a min. <?php echo $website['images']['post_width']; ?>px * <?php echo $website['images']['post_height']; ?>px image</p>
                            </div>
                            <div class="radio col-xs-6 col-sm-8 col-xxs-full-width voffset-b-2">
                                <label class="">
                                    <input type="radio" name="post_img_crop" value="both" checked <?php
                                    if (isset($_POST['post_img_crop']) && $_POST['post_img_crop'] == 'both') {
                                        echo 'checked';
                                    }
                                    ?>>
                                    Crop Image Width & Height (Default)
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="radio" name="post_img_crop" value="width" <?php
                                    if (isset($_POST['post_img_crop']) && $_POST['post_img_crop'] == 'width') {
                                        echo 'checked';
                                    }
                                    ?>>
                                    Crop image Width
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="radio" name="post_img_crop" value="height" <?php
                                    if (isset($_POST['post_img_crop']) && $_POST['post_img_crop'] == 'height') {
                                        echo 'checked';
                                    }
                                    ?>>
                                    Crop Image Height
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="radio" name="post_img_crop" value="none" <?php
                                    if (isset($_POST['post_img_crop']) && $_POST['post_img_crop'] == 'none') {
                                        echo 'checked';
                                    }
                                    ?>>
                                    Do not Crop <span class="text-danger">(Image must be a min. <?php echo $website['images']['post_small_width']; ?>px * <?php echo $website['images']['post_small_height']; ?>px image)</span>
                                </label>
                            </div>
                            <div class="form-group col-xs-12 voffset-b-3">
                                <p class="help-block">Post image (featured)</p>
                                <?php if ($row_main['upload_path'] != null && $row_main['upload_path'] != '') { ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="post_img_delete" value="true"> <span class="text-danger">delete image</span>
                                        </label>
                                    </div>
                                    <input type="hidden" name="upload_id" value="<?php echo $row_main['upload_id']; ?>">
                                    <img src="../uploads/<?php echo $row_main['upload_path']; ?>" class="img-thumbnail" width="200">
                                    <?php
                                } else {
                                    echo '<h4 class"text-danger"><span class="glyphicon glyphicon-picture"></span> no image found</h4>';
                                }
                                ?>
                            </div>
                            <div class="form-group col-xs-12">
                                <label><span class="text-danger">*</span>Description :-</label>
                                <textarea name="post_details" class=""><?php
                                    if (isset($_POST['post_details'])) {
                                        echo $_POST['post_details'];
                                    } else {
                                        echo $row_main['post_details'];
                                    }
                                    ?>
                                </textarea>
                                <script>
                                    CKEDITOR.replace('post_details', {
                                        removeButtons: 'About',
                                        uiColor: '#ffffff',
                                        extraPlugins: 'imageuploader',
                                        allowedContent: true
                                    });
                                </script>
                                <p class="help-block"><strong>short codes are</strong> [main_cat_details]</p>
                            </div>
                            <?php
                            if ($website['config']['brands'] == true) {
                                /* get checked catcodes ----- */
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
                                ?>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label><span class="text-danger">*</span>Brand Name :-</label>
                                    <div id="bbb">
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
                                            /* ----- */

                                            if (isset($_POST['brand_code']) && $_POST['brand_code'] != null) {
                                                $brand_code = $_POST['brand_code'];
                                            } else {
                                                $brand_code = $row_main['brand_code'];
                                            }

                                            $query = "SELECT s.* FROM item_brand s WHERE ($q_prt OR s.cat_code=0) AND "
                                                    . "s.active='1' ORDER BY s.brand_name ASC";
                                            $result = $myCon->query($query);
                                            ?>
                                            <select name="brand_code" class="validate[required] form-control selectpicker" data-size="5">
                                                <optgroup label="No option will be applied">
                                                    <option value="0">No Apply (Blank)</option>
                                                </optgroup>
                                                <optgroup label="Database entered options">
                                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                        <option value="<?php echo($row['brand_code']); ?>"
                                                        <?php
                                                        if ($row['brand_code'] == $brand_code) {
                                                            echo 'selected';
                                                        }
                                                        ?>><?php echo($row['brand_name']); ?></option>
                                                            <?php } ?>
                                                </optgroup>
                                            </select>
                                            <?php
                                        } else {
                                            ?>
                                            <select name="brand_code" class="validate[required] form-control selectpicker" data-size="5" readonly>
                                                <option disabled selected>Please Select</option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            }
                            if ($website['feature']['cell_price'] == true || $website['config']['units'] == true ||
                                    $website['config']['apps'] == true) {
                                ?>    
                                <h5 class="col-xs-12 text-warning voffset-3 voffset-b-1">Enter unit & price details</h5>
                                <?php if ($website['feature']['cell_price'] == true) { ?>
                                    <div class="form-group col-xs-4 col-sm-4">
                                        <label><span class="text-danger">*</span>Selling Price :-</label>
                                        <input name="selling_price" type="text" class="validate[required, custom[currency]] form-control" maxlength="12" value="<?php
                                        if (isset($_POST['selling_price'])) {
                                            echo $_POST['selling_price'];
                                        } else {
                                            echo $row_main['selling_price'];
                                        }
                                        ?>" placeholder="Ex/ 1500.00">
                                    </div>
                                <?php } if ($website['feature']['off_value'] == true) { ?>
                                    <div class="form-group col-xs-4 col-sm-4">
                                        <label>Discount Amount :-</label>
                                        <input name="off_value" type="text" class="validate[custom[currency]] form-control" maxlength="12" value="<?php
                                        if (isset($_POST['off_value'])) {
                                            echo $_POST['off_value'];
                                        } else {
                                            echo $row_main['off_value'];
                                        }
                                        ?>" placeholder="Ex/ 500.00">
                                        <p class="help-block">Display Price = Selling Price - Discount Amount/</p>
                                    </div>
                                <?php } if ($website['feature']['off_precentage'] == true) { ?>
                                    <div class="form-group col-xs-4 col-sm-4">
                                        <label>Discount Percentage :-</label>
                                        <div class="input-group">
                                            <input name="off_presentage" type="text" class="validate[custom[currency], max[100]] form-control" maxlength="10" value="<?php
                                            if (isset($_POST['off_presentage'])) {
                                                echo $_POST['off_presentage'];
                                            } else {
                                                echo $row_main['off_presentage'];
                                            }
                                            ?>" placeholder="Ex/ 10">
                                            <div class="input-group-addon">%</div>
                                        </div>
                                        <p class="help-block">Display Price = Selling Price - Discount Amount/</p>
                                    </div>
                                <?php } if ($website['config']['units'] == true) { ?>
                                    <div class="form-group col-xs-4 col-sm-4">
                                        <label><span class="text-danger">*</span>Unit :-</label>
                                        <?php
                                        $query = "SELECT * FROM item_unit WHERE active='1' ORDER BY unit_name ASC";
                                        $result = $myCon->query($query);
                                        ?>
                                        <select name="unit_code" class="validate[required] form-control selectpicker" data-size="5">
                                            <optgroup label="No option will be applied">
                                                <option value="0">No Apply (Blank)</option>
                                            </optgroup>
                                            <optgroup label="Database entered options">
                                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                    <option value="<?php echo($row['unit_code']); ?>" <?php
                                                    if (isset($_POST['unit_code']) && $_POST['unit_code'] == $row['unit_code']) {
                                                        echo 'selected';
                                                    } else if ($row_main['unit_code'] == $row['unit_code']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo('(' . $row['unit_code'] . ') ' . $row['unit_name']); ?></option>
                                                        <?php } ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                <?php } if ($website['config']['apps'] == true) { ?>
                                    <div class="form-group col-xs-4 col-sm-4">
                                        <label><span class="text-danger">*</span>Appearance :-</label>
                                        <?php
                                        $query = "SELECT * FROM item_appear WHERE active='1' ORDER BY app_name ASC";
                                        $result = $myCon->query($query);
                                        ?>
                                        <select name="app_code" class="validate[required] form-control selectpicker" data-size="5">
                                            <optgroup label="No option will be applied">
                                                <option value="0">No Apply (Blank)</option>
                                            </optgroup>
                                            <optgroup label="Database entered options">
                                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                    <option value="<?php echo($row['app_code']); ?>" <?php
                                                    if (isset($_POST['app_code']) && $_POST['app_code'] == $row['app_code']) {
                                                        echo 'selected';
                                                    } else if ($row_main['app_code'] == $row['app_code']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo($row['app_name']); ?></option>
                                                        <?php } ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <?php
                                }
                            }
                            if ($website['feature']['stock'] == true) {
                                ?>
                                <h5 class="col-xs-12 text-warning voffset-3 voffset-b-1">Stock details</h5>
                                <div class="form-group col-xs-4 col-sm-4">
                                    <label>Start Stock :-</label>
                                    <input name="init_stock" type="text" class="validate[custom[number], min[1]] form-control" maxlength="10" value="<?php
                                    if (isset($_POST['init_stock'])) {
                                        echo $_POST['init_stock'];
                                    } else {
                                        echo $row_main['init_stock'];
                                    }
                                    ?>" placeholder="Ex/ 10">
                                </div>
                                <div class="form-group col-xs-4 col-sm-4">
                                    <label>Now Stock :-</label>
                                    <input name="now_stock" type="text" class="validate[custom[number]] form-control" maxlength="10" value="<?php
                                    if (isset($_POST['now_stock'])) {
                                        echo $_POST['now_stock'];
                                    } else {
                                        echo $row_main['now_stock'];
                                    }
                                    ?>" placeholder="Ex/ 10">
                                </div>
                                <?php
                            }
                            if ($website['feature']['district'] == true || $website['feature']['city'] == true) {
                                ?>
                                <h5 class="col-xs-12 text-warning voffset-3 voffset-b-1">Location details</h5>
                                <?php
                                if ($website['feature']['district'] == true) {
                                    ?>
                                    <div class="form-group col-xs-6">
                                        <label><span class="text-danger">*</span>District :-</label>
                                        <?php
                                        $query = "SELECT * FROM sl_district ORDER BY d_name ASC";
                                        $result = $myCon->query($query);
                                        ?>
                                        <select name="d_id" class="validate[required] form-control selectpicker" data-size="5" onchange="displayCity(this.value)">
                                            <option disabled selected>Please Select</option>
                                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <option value="<?php echo($row['d_id']); ?>" <?php
                                                if (isset($_POST['d_id']) && $_POST['d_id'] == $row['d_id']) {
                                                    echo 'selected';
                                                } else if ($row_main['d_id'] == $row['d_id']) {
                                                    echo 'selected';
                                                }
                                                ?>><?php echo($row['d_name']); ?></option>
                                                    <?php } ?>
                                        </select>
                                    </div>
                                    <?php
                                } if ($website['feature']['city'] == true) {
                                    ?>
                                    <div class="form-group col-xs-6">
                                        <label><span class="text-danger">*</span>City :-</label>
                                        <div id="iii">
                                            <select name="c_id" class="validate[required] form-control selectpicker" data-size="5">
                                                <option disabled selected>Please Select</option>
                                                <?php
                                                if (isset($_POST['d_id']) || isset($row_main['c_id'])) {
                                                    $query = "SELECT * FROM sl_city WHERE d_id='" . $_POST['d_id'] . "' ORDER BY c_name ASC";
                                                    $result = $myCon->query($query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        ?>
                                                        <option value="<?php echo($row['c_id']); ?>" <?php
                                                        if (isset($_POST['c_id']) && $_POST['c_id'] == $row['c_id']) {
                                                            echo 'selected';
                                                        } else if ($row_main['c_id'] == $row['c_id']) {
                                                            echo 'selected';
                                                        }
                                                        ?>><?php echo($row['c_name']); ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <h5 class="col-xs-12 text-warning voffset-1 voffset-b-1">Enter other details</h5>
                            <?php if ($website['descp']['post_terms_entry'] == true) { ?>
                                <div class="form-group col-xs-12">
                                    <label>Terms :-</label>
                                    <textarea name="terms" class=""><?php
                                        if (isset($_POST['terms'])) {
                                            echo $_POST['terms'];
                                        } else {
                                            echo $row_main['terms'];
                                        }
                                        ?>
                                    </textarea>
                                    <script>
                                        CKEDITOR.replace('terms', {
                                            removeButtons: 'About',
                                            uiColor: '#ffffff',
                                            extraPlugins: 'imageuploader',
                                            allowedContent: true
                                        });
                                    </script>
                                </div>
                            <?php } if ($website['descp']['post_policies_entry']) { ?>
                                <div class="form-group col-xs-12">
                                    <label>Policies :-</label>
                                    <textarea name="policies" class=""><?php
                                        if (isset($_POST['policies'])) {
                                            echo $_POST['policies'];
                                        } else {
                                            echo $row_main['policies'];
                                        }
                                        ?>
                                    </textarea>
                                    <script>
                                        CKEDITOR.replace('policies', {
                                            removeButtons: 'About',
                                            uiColor: '#ffffff',
                                            extraPlugins: 'imageuploader',
                                            allowedContent: true
                                        });
                                    </script>
                                </div>
                            <?php } if ($website['descp']['post_data1_entry']) { ?>
                                <div class="form-group col-xs-12">
                                    <label><?php echo $website['name']['post_data1_entry']; ?> :-</label>
                                    <textarea name="data1" class=""><?php
                                        if (isset($_POST['data1'])) {
                                            echo $_POST['data1'];
                                        } else {
                                            echo $row_main['data1'];
                                        }
                                        ?>
                                    </textarea>
                                    <script>
                                        CKEDITOR.replace('data1', {
                                            removeButtons: 'About',
                                            uiColor: '#ffffff',
                                            extraPlugins: 'imageuploader',
                                            allowedContent: true
                                        });
                                    </script>
                                </div>
                            <?php } if ($website['descp']['post_data2_entry']) { ?>
                                <div class="form-group col-xs-12">
                                    <label><?php echo $website['name']['post_data2_entry']; ?> :-</label>
                                    <textarea name="data2" class=""><?php
                                        if (isset($_POST['data2'])) {
                                            echo $_POST['data2'];
                                        } else {
                                            echo $row_main['data2'];
                                        }
                                        ?>
                                    </textarea>
                                    <script>
                                        CKEDITOR.replace('data2', {
                                            removeButtons: 'About',
                                            uiColor: '#ffffff',
                                            extraPlugins: 'imageuploader',
                                            allowedContent: true
                                        });
                                    </script>
                                </div>
                            <?php } if ($website['descp']['post_data3_entry']) { ?>
                                <div class="form-group col-xs-12">
                                    <label><?php echo $website['name']['post_data3_entry']; ?> :-</label>
                                    <textarea name="data3" class=""><?php
                                        if (isset($_POST['data3'])) {
                                            echo $_POST['data3'];
                                        } else {
                                            echo $row_main['data3'];
                                        }
                                        ?>
                                    </textarea>
                                    <script>
                                        CKEDITOR.replace('data3', {
                                            removeButtons: 'About',
                                            uiColor: '#ffffff',
                                            extraPlugins: 'imageuploader',
                                            allowedContent: true
                                        });
                                    </script>
                                </div>
                            <?php } ?>
                            <div class="form-group col-xs-12 col-sm-4 clear-both">
                                <label><span class="text-danger">*</span>Display :-</label>
                                <select name="active" class="validate[required] form-control selectpicker" data-size="5">
                                    <option value="1" <?php
                                    if (isset($_POST['active']) && $_POST['active'] == '1') {
                                        echo 'selected';
                                    } else if ($row_main['active'] == '1') {
                                        echo 'selected';
                                    }
                                    ?>>Yes, display</option>
                                    <option value="0" <?php
                                    if (isset($_POST['active']) && $_POST['active'] == '0') {
                                        echo 'selected';
                                    } else if ($row_main['active'] == '0') {
                                        echo 'selected';
                                    }
                                    ?>>No, do not display</option>
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-4 clear-both">
                                <label><span class="text-danger">*</span>Featured:-</label>
                                <select name="featured" class="validate[required] form-control selectpicker" data-size="5">
                                    <option disabled selected>Please Select</option>
                                    <option value="1" <?php
                                    if (isset($_POST['featured']) && $_POST['featured'] == '1') {
                                        echo 'selected';
                                    } else if ($row_main['featured'] == '1') {
                                        echo 'selected';
                                    }
                                    ?>>Yes, featured</option>
                                    <option value="0" <?php
                                    if (isset($_POST['featured']) && $_POST['featured'] == '0') {
                                        echo 'selected';
                                    } else if ($row_main['featured'] == '0') {
                                        echo 'selected';
                                    }
                                    ?>>No, not a featured</option>
                                </select>
                            </div>
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