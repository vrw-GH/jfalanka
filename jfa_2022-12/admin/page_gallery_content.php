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

$page_id = $_REQUEST['page_id'];

$myCon = new dbConfig();
$myCon->connect();
$query = "SELECT p.page_id, p.page_title, u.upload_id, u.upload_path, u.upload_title FROM upload_data u "
        . "LEFT JOIN pages p ON u.upload_ref = p.page_id WHERE "
        . "u.upload_type_id='11' AND u.featured != 1 AND u.upload_ref = '" . $page_id . "' "
        . "ORDER BY p.page_id ASC";
$result = $myCon->query($query);
?>
<h1>Page Gallery Images</h1>
<div id="details-viewer" class="full-wide">
    <div class="col-xs-12 col-pdn-both-0 grid">
        <?php
        $form_id = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-xs-6 col-sm-4 col-lg-3 grid-item voffset-b-1">
                <div id="imgbox-outer">
                    <img src="../uploads/thumbs/<?php echo($row['upload_path']) ?>" alt="" class="img-responsive img-thumbnail img-center voffset-1 voffset-b-1" style="max-width: 150px;"/>
                    <hr class="style-one">
                    <form id="formTitleID<?php echo $form_id ?>" method="post" action="" class="form text-left">
                        <input type="hidden" name="category" value="pages">
                        <input type="hidden" name="action" value="title_image">
                        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                        <input type="hidden" name="upload_id" value="<?php echo($row['upload_id']) ?>">
                        <input type="hidden" name="page_id" value="<?php echo($row['page_id']) ?>">
                        <div class="tit-res-<?php echo $form_id ?>"></div>
                        <label>Image Title</label>
                        <input type="text" name="upload_title" onBlur="submitFormTitle('<?php echo $form_id ?>');" class="form-control input-sm square voffset-1" placeholder="Image Title" value="<?php echo($row['upload_title']) ?>"/>
                    </form>
                    <hr class="style-one">
                    <form id="formID<?php echo $form_id ?>" method="post" action="">
                        <input type="hidden" name="category" value="pages">
                        <input type="hidden" name="action" value="delete_image">
                        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                        <input type="hidden" name="upload_id" value="<?php echo($row['upload_id']) ?>">
                        <input type="hidden" name="page_id" value="<?php echo($row['page_id']) ?>">
                        <button type="submit" class="btn btn-danger btn-xs voffset-4 voffset-b-2 float-right"><span class="glyphicon glyphicon-trash"></span> Delete image</button>
                    </form>
                </div>
            </div>
            <?php
            $form_id = $form_id + 1;
        }
        ?>
    </div>
</div>