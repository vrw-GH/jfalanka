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

$id = $_REQUEST['id'];
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
        $queryupdate = "SELECT im.*, u.upload_id, u.upload_path FROM item_main_category im LEFT JOIN "
                . "upload_data u ON im.cat_code = u.upload_ref AND u.upload_type_id = 2 "
                . "AND u.featured = 1 WHERE im.cat_code='" . $id . "' LIMIT 1";
        $resultupdate = $myCon->query($queryupdate);
        while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
            ?>
            <h1>Edit Main Entry</h1>
            <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                <input type="hidden" name="category" value="itm_main_cat">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">
                <input type="hidden" name="cat_code" value="<?php echo $rowupdate['cat_code']; ?>">
                <div class="form-group col-xs-12">
                    <label><span class="text-danger">*</span>Main Entry Name :-</label>
                    <input name="cat_name" type="text" id="cat_name" class="validate[required] form-control" maxlength="80" value="<?php
                    if (isset($_POST['cat_name'])) {
                        echo $_POST['cat_name'];
                    } else {
                        echo $rowupdate['cat_name'];
                    }
                    ?>" placeholder="Main Entry Name">
                </div>
                <div class="form-group col-xs-6 col-xxs-full-width">
                    <label>Main Entry Image <span class="text-warning">(Featured)</span> :-</label>
                    <input type="file" name="cat_img" class="validate[required] form-control"/><p class="help-block">Image must be a min. <?= $website['images'] ['main_entry_width'] ?>px * <?= $website['images'] ['main_entry_height'] ?>px image</p>
                </div>
                <div class="radio col-xs-6 col-xxs-full-width">
                    <label class="">
                        <input type="radio" name="cat_img_crop" value="both" checked <?php
                        if (isset($_POST['cat_img_crop']) && $_POST['cat_img_crop'] == 'both') {
                            echo 'checked';
                        }
                        ?>>
                        Crop Image Width & Height (Default)
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" name="cat_img_crop" value="none" <?php
                        if (isset($_POST['cat_img_crop']) && $_POST['cat_img_crop'] == 'none') {
                            echo 'checked';
                        }
                        ?>>
                        Do not Crop
                    </label>
                </div>
                <div class="form-group col-xs-12 voffset-b-3">
                    <p class="help-block">Category image (featured)</p>
                    <?php if ($rowupdate['upload_path'] != null && $rowupdate['upload_path'] != '') { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="cat_img_delete" value="true"> <span class="text-danger">delete image</span>
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
            </div>
            <?php if ($website['custom_url']['main_entry']) { ?>
                <div class="form-group col-xs-12 col-sm-6">
                    <label>Custom URL :-</label>
                    <input name="custom_url" type="text" class="form-control" maxlength="255" value="<?php
                    if (isset($_POST['custom_url'])) {
                        echo $_POST['custom_url'];
                    } else {
                        echo $rowupdate['custom_url'];
                    }
                    ?>" placeholder="URL">
                </div>
            <?php } if ($website['descp'] ['main_entry']) { ?>
                <div class="form-group col-xs-12 voffset-2">
                    <label>Main Entry Description :-</label>
                    <textarea name="cat_details" class="">
                        <?php
                        if (isset($_POST['cat_details'])) {
                            echo $_POST['cat_details'];
                        } else {
                            echo $rowupdate['cat_details'];
                        }
                        ?></textarea>
                    <script>
                        CKEDITOR.replace('cat_details', {
                            removeButtons: 'About',
                            uiColor: '#ffffff',
                            extraPlugins: 'imageuploader',
                            allowedContent: true
                        });
                    </script>
                </div>
            <?php } ?>
            <div class="form-group col-xs-12 col-sm-6 voffset-2 clear-left">
                <label><span class="text-danger">*</span>Display Status :-</label>
                <?php if ($rowupdate['active'] == '0') { ?>
                    <select name="active" class="form-control selectpicker" data-size="5">
                        <option value="0" selected="selected">Do not Display</option>
                        <option value="1">Display - Auto</option>
                        <option value="2">Display - Manual</option>
                    </select>
                <?php } else if ($rowupdate['active'] == '1') { ?>
                    <select name="active" class="form-control selectpicker" data-size="5">
                        <option value="0">Do not Display</option>
                        <option value="1" selected="selected">Display - Auto</option>
                        <option value="2">Display - Manual</option>
                    </select>
                <?php } else { ?>
                    <select name="active" class="form-control selectpicker" data-size="5">
                        <option value="0">Do not Display</option>
                        <option value="1">Display - Auto</option>
                        <option value="2" selected="selected">Display - Manual</option>
                    </select>
                <?php } ?>
            </div>
            <div class="form-group col-xs-12 text-right">
                <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=1"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Main Entry</button>
            </div>
            <!-- forms END -->
        </form>
        <link rel="stylesheet" href="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/css/bootstrap-select.min.css">
        <script src="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>

    <?php } ?>
</body>
</html>