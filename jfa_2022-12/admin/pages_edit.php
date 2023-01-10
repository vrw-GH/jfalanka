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

$page_id = $_REQUEST['page_id'];
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
        $queryupdate = "SELECT p.*, u.upload_id, u.upload_path FROM pages p LEFT JOIN "
                . "upload_data u ON p.page_id = u.upload_ref AND u.upload_type_id = 11 "
                . "AND u.featured = 1 WHERE p.page_id='" . $page_id . "' LIMIT 1";
        $resultupdate = $myCon->query($queryupdate);
        while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
            ?>
            <h1>Edit a Page</h1>
            <form id="formID" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="category" value="pages">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">
                <input type="hidden" name="page_id" value="<?php echo $rowupdate['page_id']; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['system_logged_uname']; ?>">
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Page Title :-</label>
                    <input name="page_title" type="text" id="page_title" class="validate[required] form-control" maxlength="80" value="<?php
                    if (isset($_POST['page_title'])) {
                        echo $_POST['page_title'];
                    } else {
                        echo $rowupdate['page_title'];
                    }
                    ?>" placeholder="Page Title">
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label>Page Top Image <span class="text-warning">(Featured)</span> :-</label>
                    <input type="file" name="page_img" class="form-control"/><p class="help-block">Image must be a min. <?php echo $website['images'] ['page_width']; ?>px * <?php echo $website['images'] ['page_height']; ?>px image</p>
                </div>
                <div class="form-group col-xs-12 voffset-b-3">
                    <p class="help-block">Page image (featured)</p>
                    <?php if ($rowupdate['upload_path'] != null && $rowupdate['upload_path'] != '') { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="page_img_delete" value="true"> <span class="text-danger">delete image</span>
                            </label>
                        </div>
                        <input type="hidden" name="upload_id" value="<?php echo $rowupdate['upload_id']; ?>">
                        <img src="../uploads/<?php echo $rowupdate['upload_path']; ?>" class="img-thumbnail" height="50">
                        <?php
                    } else {
                        echo '<h4 class"text-danger"><span class="glyphicon glyphicon-picture"></span> no image found</h4>';
                    }
                    ?>
                </div>
            </div>
            <?php if ($website['custom_url']['page']) { ?>
                <div class="form-group col-xs-12 col-sm-6">
                    <label>Custom URL :-</label>
                    <input name="page_custom_url" type="text" class="form-control" maxlength="255" value="<?php
                    if (isset($_POST['page_custom_url'])) {
                        echo $_POST['page_custom_url'];
                    } else {
                        echo $rowupdate['page_custom_url'];
                    }
                    ?>" placeholder="URL">
                </div>
            <?php } ?>
            <div class="form-group col-xs-12 voffset-2">
                <label>Page Content :-</label>
                <textarea name="page_content" class="">
                    <?php
                    if (isset($_POST['page_content'])) {
                        echo $_POST['page_content'];
                    } else {
                        echo $rowupdate['page_content'];
                    }
                    ?></textarea>
                <script>
                    CKEDITOR.replace('page_content', {
                        removeButtons: 'About',
                        uiColor: '#ffffff',
                        extraPlugins: 'imageuploader',
                        allowedContent: true
                    });
                </script>
            </div>
            <div class="form-group col-xs-12 col-sm-6 voffset-2">
                <label>Page Galley :-</label>
                <select name="gallery_type" class="form-control selectpicker show-tick" data-size="5">
                    <optgroup label="Gallery">
                        <option value="1" data-subtext="with thumbnails" <?php
                        if (isset($rowupdate['gallery_type']) && $rowupdate['gallery_type'] == 1) {
                            echo 'selected';
                        }
                        ?>>Page Bottom Galley</option>
                        <option value="2" data-subtext="no thumbnails" <?php
                        if (isset($rowupdate['gallery_type']) && $rowupdate['gallery_type'] == 2) {
                            echo 'selected';
                        }
                        ?>>Page Bottom Galley</option>
                        <option value="3" data-subtext="with thumbnails" <?php
                        if (isset($rowupdate['gallery_type']) && $rowupdate['gallery_type'] == 3) {
                            echo 'selected';
                        }
                        ?>>Page Top Galley</option>
                        <option value="4" data-subtext="no thumbnails" <?php
                        if (isset($rowupdate['gallery_type']) && $rowupdate['gallery_type'] == 4) {
                            echo 'selected';
                        }
                        ?>>Page Top Galley</option>
                    </optgroup>
                    <optgroup label="Full Width Slider">
                        <option value="5" <?php
                        if (isset($rowupdate['gallery_type']) && $rowupdate['gallery_type'] == 5) {
                            echo 'selected';
                        }
                        ?>>Page Bottom Slider</option>
                        <option value="6" <?php
                        if (isset($rowupdate['gallery_type']) && $rowupdate['gallery_type'] == 6) {
                            echo 'selected';
                        }
                        ?>>Page Top Slider</option>
                    </optgroup>
                </select>
                <p class="help-block">You must upload images to display the galley</p>
            </div>
            <div class="form-group col-xs-12 col-sm-6 voffset-2">
                <label>Gallery Title :-</label>
                <input name="gallery_name" type="text" class="form-control" maxlength="45" value="<?php
                if (isset($rowupdate['gallery_name'])) {
                    echo $rowupdate['gallery_name'];
                } else {
                    echo 'Galley';
                }
                ?>" placeholder="Galley">
                <p class="help-block">Keep this blank if you do not want to display galley title</p>
            </div>
            <div class="form-group col-xs-12 col-sm-6 voffset-2">
                <label>Display Status :-</label>
                <?php if ($rowupdate['active'] == '0') { ?>
                    <select name="active" class="form-control selectpicker show-tick" data-size="5">
                        <option value="0" selected="selected">Do not Display</option>
                        <option value="1">Display</option>
                    </select>
                <?php } else { ?>
                    <select name="active" class="form-control selectpicker show-tick" data-size="5">
                        <option value="0">Do not Display</option>
                        <option value="1" selected="selected">Display</option>
                    </select>
                <?php } ?>
            </div>
            <div class="form-group col-xs-12 text-right">
                <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=10"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Page</button>
            </div>
            <!-- forms END -->
        </form>
    <?php } ?>
</body>
</html>