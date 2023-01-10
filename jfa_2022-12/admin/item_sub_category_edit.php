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

$auto_num = $_REQUEST['auto_num'];
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
        $queryupdate = "SELECT s.*, u.upload_id, u.upload_path FROM item_sub_category s LEFT JOIN "
                . "upload_data u ON s.auto_num = u.upload_ref AND u.upload_type_id = 3 "
                . "AND u.featured = 1 WHERE s.auto_num='" . $auto_num . "' LIMIT 1";
        $resultupdate = $myCon->query($queryupdate);
        while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
            ?>
            <h1>Edit Sub Entry</h1>
            <form method="post" action="" class="allforms" id="formIDEdit" enctype="multipart/form-data">
                <input type="hidden" name="category" value="itm_sub_cat">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                <input type="hidden" name="auto_num" value="<?php echo $rowupdate['auto_num']; ?>"/>
                <input type="hidden" name="pre_cat_code" value="<?php echo $rowupdate['cat_code']; ?>"/>
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Main Entry Name :-</label>
                    <?php
                    $query = "SELECT * FROM item_main_category WHERE active!='0' ORDER BY cat_name ASC";
                    $result = $myCon->query($query);
                    ?>
                    <select name="cat_code" class="validate[required] form-control selectpicker" data-size="5">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <option value="<?php echo($row['cat_code']); ?>" <?php
                            if (isset($_POST['cat_code']) && $_POST['cat_code'] == $row['cat_code']) {
                                echo 'selected';
                            } else if ($rowupdate['cat_code'] == $row['cat_code']) {
                                echo 'selected';
                            }
                            ?>>
                                <?php echo($row['cat_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Sub Entry Name :-</label>
                    <input name="sub_name" type="text" id="sub_name" class="validate[required] form-control" maxlength="45" value="<?php
                    if (isset($_POST['sub_name'])) {
                        echo $_POST['sub_name'];
                    } else {
                        echo $rowupdate['sub_name'];
                    }
                    ?>" placeholder="Sub Entry Name">
                </div>
                <?php if ($website['images']['sub_display']) { ?>
                    <div class="form-group col-xs-6 col-xxs-full-width">
                        <label>Sub Entry Image <span class="text-warning">(Featured)</span> :-</label>
                        <input type="file" name="sub_img" class="form-control"/><p class="help-block">Image must be a min. <?php echo $website['images'] ['sub_entry_width']; ?>px * <?php echo $website['images'] ['sub_entry_height']; ?>px image</p>
                    </div>
                    <div class="radio col-xs-6 col-xxs-full-width">
                        <label class="">
                            <input type="radio" name="sub_img_crop" value="both" checked <?php
                            if (isset($_POST['sub_img_crop']) && $_POST['sub_img_crop'] == 'both') {
                                echo 'checked';
                            }
                            ?>>
                            Crop Image Width & Height (Default)
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="sub_img_crop" value="none" <?php
                            if (isset($_POST['sub_img_crop']) && $_POST['sub_img_crop'] == 'none') {
                                echo 'checked';
                            }
                            ?>>
                            Do not Crop
                        </label>
                    </div>
                    <div class="form-group col-xs-12 voffset-b-3">
                        <p class="help-block">Sub Category image (featured)</p>
                        <?php if ($rowupdate['upload_path'] != null && $rowupdate['upload_path'] != '') { ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sub_img_delete" value="true"> <span class="text-danger">delete image</span>
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
                <?php if ($website['descp'] ['sub_entry']) { ?>
                    <div class="form-group col-xs-12">
                        <label>Sub Entry Description :-</label>
                        <textarea name="sub_details" class=""><?php
                            if (isset($_POST['sub_details'])) {
                                echo $_POST['sub_details'];
                            } else {
                                echo $rowupdate['sub_details'];
                            }
                            ?></textarea>
                        <script>
                            CKEDITOR.replace('sub_details', {
                                removeButtons: 'About',
                                uiColor: '#ffffff',
                                extraPlugins: 'imageuploader',
                                allowedContent: true,
                            });
                        </script>
                    </div>
                <?php } ?>
                <div class="form-group col-xs-12 col-sm-6 voffset-2 clear-left">
                    <label><span class="text-danger">*</span>Display Status :-</label>
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
                    <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=2"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Sub Entry</button>
                </div>
            </form>
        <?php } ?>
    </body>
</html>