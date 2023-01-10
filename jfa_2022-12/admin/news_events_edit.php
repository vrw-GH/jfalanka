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

$ann_id = $_REQUEST['ann_id'];
$systemdate = date('Y-m-d');
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
            });
            $(function () {
                /* Date */
                $("#add_date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 2,
                    showButtonPanel: true
                });
                $("#add_date").datepicker("option", "dateFormat", "yy-mm-dd");
                $("#add_date").datepicker("setDate", new Date("<?php echo $systemdate; ?>"));
            });
        </script>
    </head>
    <body>
        <?php
        $queryupdate = "SELECT n.*, u.upload_id, u.upload_path FROM news_events n LEFT JOIN "
                . "upload_data u ON n.ann_id = u.upload_ref AND u.upload_type_id = 10 "
                . "AND u.featured = 1 WHERE ann_id='" . $ann_id . "'";
        $resultupdate = $myCon->query($queryupdate);
        while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
            ?>
            <h1>Edit News Event</h1>
            <form id="formIDEdit" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="category" value="news">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['system_logged_uname']; ?>" >
                <input type="hidden" name="ann_id" value="<?php echo $rowupdate['ann_id']; ?>"/>
                <div id="inner-box">
                    <fieldset>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label><span>*</span>News Event Title :-</label>
                            <input name="ann_title" type="text" maxlength="80" tabindex="0" value="<?php echo $rowupdate['ann_title']; ?>" class="validate[required] form-control">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>News Image <span class="text-warning">(Featured)</span> :-</label>
                            <input type="file" name="ann_img" class="form-control"/>
                            <div class="help-block">Image should be minimum (<?php echo $website['images']['news_width']; ?>px * <?php echo $website['images']['news_height']; ?>px & max. 8Mb)</div>
                        </div>
                        <div class="form-group col-xs-12 voffset-b-3">
                            <p class="help-block">News image (featured)</p>
                            <?php if ($rowupdate['upload_path'] != null && $rowupdate['upload_path'] != '') { ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="news_img_delete" value="true"> <span class="text-danger">delete image</span>
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
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Add Date</label>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="glyphicons glyphicons-calendar"></span></div>
                                <input type="text" name="add_date" id="add_date" class="dates validate[custom[date]] form-control" readonly>
                            </div>
                        </div>
                        <script>
                            $(function () {
                                $("#add_date").datepicker("option", "dateFormat", "yy-mm-dd");
                                $("#add_date").datepicker("setDate", new Date("<?php echo substr($rowupdate['add_date'], 0, 10); ?>"));
                            });
                        </script>
                        <div class="form-group col-xs-12"><label><span>*</span>News Event Details :-</label>
                            <textarea name="ann_details" class=""><?php echo $rowupdate['ann_details']; ?></textarea>
                            <script>
                                CKEDITOR.replace('ann_details', {
                                    removeButtons: 'About',
                                    uiColor: '#ffffff',
                                    height: '400px',
                                    extraPlugins: 'imageuploader',
                                    allowedContent: true
                                });
                            </script>
                        </div>
                        <div class="form-group col-xs-12 text-right">
                            <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=8"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update News/Event </button>
                        </div>
                    </fieldset>
                </div>
                <!-- forms END -->
            </form>
        <?php } ?>
    </body>
</html>