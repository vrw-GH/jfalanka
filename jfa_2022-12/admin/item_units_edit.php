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

$id = $_REQUEST['unit_code'];
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
        $queryupdate = "SELECT * FROM item_unit WHERE unit_code='" . $id . "' LIMIT 1";
        $resultupdate = $myCon->query($queryupdate);
        while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
            ?>
            <h1>Edit Product Unit</h1>
            <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                <input type="hidden" name="category" value="itm_unit">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">
                <input type="hidden" name="unit_code" value="<?= $rowupdate['unit_code'] ?>">
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Unity Symbol :-</label>
                    <input type="text" class="form-control" value="<?= $rowupdate['unit_code'] ?>" disabled>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label><span class="text-danger">*</span>Unity Name :-</label>
                    <input name="unit_name" type="text" class="validate[required] form-control" maxlength="45" value="<?php
                    if (isset($_POST['unit_name'])) {
                        echo $_POST['unit_name'];
                    } else {
                        echo $rowupdate['unit_name'];
                    }
                    ?>" placeholder="Unit Name">
                </div>
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
                    <a class="btn btn-warning" href="dashboard.php?page=master_entries&subpage=4"><span class="glyphicon glyphicon-share"></span> Cancel Update</a>
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Unit</button>
                </div>
                <!-- forms END -->
            </form>
        <?php } ?>
    </body>
</html>