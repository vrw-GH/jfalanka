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

$upload_id = $_REQUEST['upload_id'];
?>
<script>
    $(function () {
        $("#formIDEdit").validationEngine();
        $("#formIDEdit").bind("jqv.field.result", function (event, field, errorFound, prompText) {
            console.log(errorFound)
        });
    });
</script>
<?php
$queryupdate = "SELECT s.*, u.upload_path FROM slider_content s LEFT JOIN upload_data u ON "
        . "s.upload_id = u.upload_id WHERE s.upload_id ='" . $upload_id . "'";
$resultupdate = $myCon->query($queryupdate);
while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
    ?>

    <h1>Edit Slider Image</h1>
    <form id="formID" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="category" value="slider_img"/>
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
        <input type="hidden" name="upload_id" value="<?php echo $rowupdate['upload_id']; ?>"/>
        <fieldset>
            <legend>Change Main Slider Image</legend>
            <div class="form-group col-xs-12 voffset-b-3">
                <?php if ($rowupdate['upload_path'] != null && $rowupdate['upload_path'] != '') { ?>
                    <img src="../uploads/<?php echo $rowupdate['upload_path']; ?>" class="img-thumbnail" style="height:180px;">
                    <?php
                } else {
                    echo '<h4 class"text-danger"><span class="glyphicon glyphicon-picture"></span> no image found</h4>';
                }
                ?>
            </div>
            <div class="form-group col-xs-12">
                <label>Slider Headline (Optional) : </label>
                <input class="form-control" type="text" name="content_header" placeholder="Headline" value="<?php echo $rowupdate['content_header']; ?>"/>
            </div>
            <div class="form-group col-xs-12 col-sm-6">
                <label>Slider Description (Optional) : </label>
                <textarea class="form-control" name="content_descp" placeholder="Description"><?php echo $rowupdate['content_descp']; ?></textarea>
            </div>
            <div class="form-group col-xs-12 col-sm-6">
                <label>Image URL (Optional) : </label>
                <input class="form-control" type="text" name="content_url" placeholder="http://page_url.php" value="<?php echo $rowupdate['content_url']; ?>"/>
            </div>
            <div class="form-group col-xs-12 col-sm-6 clear-both">
                <label>Select an Image (Optional) : </label>
                <input class="form-control" type="file" name="slider_img"/>
                <div class="help-block">Image should be minimum (<?php echo $website['images'] ['main_slider_width']; ?>px * <?php echo $website['images'] ['main_slider_height']; ?>px & max. 8Mb).</div>
            </div>
            <div class="form-group col-xs-12 text-right">
                <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=6"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Image</button>
            </div>
        </fieldset>
        <!-- forms END -->
    </form>
<?php } ?>