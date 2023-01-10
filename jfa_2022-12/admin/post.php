<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../models/dbConfig.php';
$myCon = new dbConfig();
$myCon->connect();

$_SESSION['key'] = date('His') . mt_rand(1000, 9999);
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });
            });
        </script>
    </head>
    <body>
        <div id="outer-box">
            <div id="mm">
                <h1>Add a post</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                    <input type="hidden" name="category" value="post">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <input type="hidden" name="user_name" value="<?php echo $_SESSION['system_logged_uname']; ?>" >
                    <h5 class="col-xs-12 text-warning voffset-1 voffset-b-1">Enter your post details</h5>
                    <div class="form-group col-xs-12">
                        <label><span class="text-danger">*</span>Display Name :-</label>
                        <input name="post_name" type="text" class="validate[required] form-control" maxlength="200" value="<?php
                        if (isset($_POST['post_name'])) {
                            echo $_POST['post_name'];
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
                            Crop only image Width
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="post_img_crop" value="height" <?php
                            if (isset($_POST['post_img_crop']) && $_POST['post_img_crop'] == 'height') {
                                echo 'checked';
                            }
                            ?>>
                            Crop only Image Height
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
                    <div class="form-group col-xs-12 voffset-1">
                        <label><span class="text-danger">*</span>Description :-</label>
                        <textarea name="post_details" class=""><?php
                            if (isset($_POST['post_details'])) {
                                echo $_POST['post_details'];
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
                    if ($website['config']['main_cat'] == true || $website['config']['sub_cat'] == true ||
                            $website['config']['brands'] == true) {
                        ?>
                        <h5 class="col-xs-12 text-warning voffset-3 voffset-b-2">Post Category details</h5>
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
                            }
                            /* ----- */
                            ?>
                            <div class="form-group col-xs-12">
                                <div id="sss" class="col-xs-12 col-pdn-both-0">
                                    <label><span class="text-danger">*</span>Sub Entry Name :-</label>
                                    <?php
                                    /* Call on POST */
                                    if (isset($_POST['cat_code']) && $_POST['cat_code'] != "") {
                                        /* generating query part */
                                        if (isset($_REQUEST['cat_code'])) {
                                            $count = count($_REQUEST['cat_code']);
                                        } else {
                                            $count = 0;
                                        }
                                        $q_prt = "";
                                        $aa = $count;
                                        if ($count >= 1) {
                                            foreach ($_REQUEST['cat_code'] as $cat_code => $value) {
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
                            <?php
                        } if ($website['config']['brands'] == true) {
                            if (isset($_POST['brand_code']) && $_POST['brand_code'] != null) {
                                $brand_code = $_POST['brand_code'];
                            }
                            ?>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label><span class="text-danger">*</span>Brand Name :-</label>
                                <div id="bbb">
                                    <select name="brand_code" class="validate[required] form-control selectpicker" data-size="5" readonly>
                                        <option disabled selected>Please Select</option>
                                    </select>
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
                                    $("#bbb").load('item_item_main_cat_filter.php', {type: 'brand', cat_code: cat_code});
                                });
                            });
                        </script>
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
                                }
                                ?>" placeholder="Ex/ 1500.00">
                            </div>
                        <?php } if ($website['feature']['off_value'] == true) { ?>
                            <div class="form-group col-xs-4 col-sm-4">
                                <label>Discount Amount :-</label>
                                <input name="off_value" type="text" class="validate[custom[currency]] form-control" maxlength="12" value="<?php
                                if (isset($_POST['off_value'])) {
                                    echo $_POST['off_value'];
                                }
                                ?>" placeholder="Ex/ 500.00">
                                <p class="help-block">Display Price = Selling Price - Discount Amount/</p>
                            </div>
                        <?php } if ($website['feature']['off_precentage'] == true) { ?>
                            <div class="form-group col-xs-4 col-sm-4">
                                <label>Discount Percentage :-</label>
                                <div class="input-group">
                                    <input name="off_presentage" type="text" class="validate[custom[currency], min[0.1], max[100]] form-control" maxlength="10" value="<?php
                                    if (isset($_POST['off_presentage'])) {
                                        echo $_POST['off_presentage'];
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
                                    <option disabled selected>Please Select</option>
                                    <optgroup label="No option will be applied">
                                        <option value="0">No Apply (Blank)</option>
                                    </optgroup>
                                    <optgroup label="Database entered options">
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <option value="<?php echo($row['unit_code']); ?>" <?php
                                            if (isset($_POST['unit_code']) && $_POST['unit_code'] == $row['unit_code']) {
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
                                    <option disabled selected>Please Select</option>
                                    <optgroup label="No option will be applied">
                                        <option value="0">No Apply (Blank)</option>
                                    </optgroup>
                                    <optgroup label="Database entered options">
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <option value="<?php echo($row['app_code']); ?>" <?php
                                            if (isset($_POST['app_code']) && $_POST['app_code'] == $row['app_code']) {
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
                            }
                            ?>" placeholder="Ex/ 10">
                        </div>
                        <div class="form-group col-xs-4 col-sm-4">
                            <label>Now Stock :-</label>
                            <input name="now_stock" type="text" class="validate[custom[number]] form-control" maxlength="10" value="<?php
                            if (isset($_POST['now_stock'])) {
                                echo $_POST['now_stock'];
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
                                        if (isset($_POST['d_id'])) {
                                            $query = "SELECT * FROM sl_city WHERE d_id='" . $_POST['d_id'] . "' ORDER BY c_name ASC";
                                            $result = $myCon->query($query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                <option value="<?php echo($row['c_id']); ?>" <?php
                                                if (isset($_POST['c_id']) && $_POST['c_id'] == $row['c_id']) {
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
                            }
                            ?>>Yes, display</option>
                            <option value="0" <?php
                            if (isset($_POST['active']) && $_POST['active'] == '0') {
                                echo 'selected';
                            }
                            ?>>No, do not display</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4 clear-both">
                        <label><span class="text-danger">*</span>Featured:-</label>
                        <select name="featured" class="validate[required] form-control selectpicker" data-size="5">
                            <option value="0" <?php
                            if (isset($_POST['featured']) && $_POST['featured'] == '0') {
                                echo 'selected';
                            }
                            ?>>No, not a featured</option>
                            <option value="1" <?php
                            if (isset($_POST['featured']) && $_POST['featured'] == '1') {
                                echo 'selected';
                            }
                            ?>>Yes, featured</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Post</button>
                    </div>
                    <!-- forms END -->
                </form>
            </div>

        </div>
    </body>
</html>