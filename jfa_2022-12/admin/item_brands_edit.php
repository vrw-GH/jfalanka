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
$myCon = new dbConfig();
$myCon->connect();

$brand_code = $_REQUEST['brand_code'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script>
            $(function () {
                $("#formIDEdit").validationEngine();
                $("#formIDEdit").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });

                $('.selectpicker').selectpicker({});
            });
        </script>
    </head>
    <body>
        <?php
        $queryupdate = "SELECT b.*, u.upload_id, u.upload_path FROM item_brand b LEFT JOIN "
                . "upload_data u ON b.brand_code = u.upload_ref AND u.upload_type_id = 4 "
                . "AND u.featured = 1 WHERE b.brand_code='" . $brand_code . "' LIMIT 1";
        $resultupdate = $myCon->query($queryupdate);
        while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
            ?>
            <h1>Edit Product Brand</h1>
            <form method="post" action="" id="formIDEdit" enctype="multipart/form-data">
                <input type="hidden" name="category" value="itm_brand">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                <input type="hidden" name="brand_code" value="<?php echo $rowupdate['brand_code']; ?>" >
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Main Entry Name :-</label>
                    <?php
                    $query = "SELECT * FROM item_main_category ORDER BY cat_name ASC";
                    $result = $myCon->query($query);
                    ?>
                    <select name="cat_code" class="validate[required] form-control selectpicker" data-size="5">
                        <option disabled selected>Please Select</option>
                        <option value="0" <?php
                        if ($rowupdate['cat_code'] == 0) {
                            echo 'selected';
                        }
                        ?>>Generic Category (can be applied for all categories)</option>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <option value="<?php echo($row['cat_code']); ?>" <?php
                            if ($rowupdate['cat_code'] == $row['cat_code']) {
                                echo 'selected';
                            }
                            ?>><?php echo($row['cat_name']); ?></option>
                                <?php } ?>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Brand Name :-</label>
                    <input name="brand_name" type="text" class="validate[required] form-control" maxlength="45" value="<?php
                    if (isset($_POST['brand_name'])) {
                        echo $_POST['brand_name'];
                    } else {
                        echo $rowupdate['brand_name'];
                    }
                    ?>" placeholder="Brand Name">
                </div>
                <?php if ($website['images']['brand_display']) { ?>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label>Brand Image <span class="text-warning">(Featured)</span> :-</label>
                        <input type="file" name="brand_img" class="form-control"/><p class="help-block">Image must be a min. <?= $website['images'] ['sub_entry_width'] ?>px * <?= $website['images'] ['sub_entry_height'] ?>px image</p>
                    </div>
                    <div class="form-group col-xs-12 voffset-b-3">
                        <p class="help-block">Brand image (featured)</p>
                        <?php if ($rowupdate['upload_path'] != null && $rowupdate['upload_path'] != '') { ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="brand_img_delete" value="true"> <span class="text-danger">delete image</span>
                                </label>
                            </div>
                            <input type="hidden" name="upload_id" value="<?php echo $rowupdate['upload_id']; ?>">
                            <img src="../uploads/<?php echo $rowupdate['upload_path']; ?>" class="img-thumbnail" width="200">
                            <?php
                        } else {
                            echo '<h4 class"text-danger"><span class="glyphicon glyphicon-picture"></span> no image found</h4>';
                        }
                        ?>
                    </div>
                <?php } ?>
                <?php if ($website['custom_url']['brand_display']) { ?>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label>Custom URL :-</label>
                        <input name="brand_custom_url" type="text" class="form-control" maxlength="255" value="<?php
                        if (isset($_POST['brand_custom_url'])) {
                            echo $_POST['brand_custom_url'];
                        } else {
                            echo $rowupdate['brand_custom_url'];
                        }
                        ?>" placeholder="URL">
                    </div>
                <?php } if ($website['descp'] ['brand_display']) { ?>
                    <div class="form-group col-xs-12 voffset-2">
                        <label>Brand Description :-</label>
                        <textarea name="brand_details"><?php
                            if (isset($_POST['brand_details'])) {
                                echo $_POST['brand_details'];
                            } else {
                                echo $rowupdate['brand_details'];
                            }
                            ?></textarea>
                        <script>
                            CKEDITOR.replace('brand_details', {
                                removeButtons: 'About',
                                uiColor: '#ffffff',
                                extraPlugins: 'imageuploader',
                                allowedContent: true
                            });
                        </script>
                    </div>
                <?php } ?>
                <div class="form-group col-xs-12 col-sm-6">
                    <label>Display Status :-</label>
                    <?php if ($rowupdate['active'] == '1') { ?>
                        <select name="active" class="form-control selectpicker" data-size="5">
                            <option value="1" selected="selected">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    <?php } else { ?>
                        <select name="active" class="form-control selectpicker" data-size="5">
                            <option value="0" selected="selected">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    <?php } ?>	
                </div>
                <div class="form-group col-xs-12 text-right">
                    <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=3"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Brand</button>
                </div>
                <!-- forms END -->
            </form>	
        <?php } ?>
    </body>
</html>