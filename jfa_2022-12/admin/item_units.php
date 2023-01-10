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

                $('#unit_code').bind('input', function () {
                    $(this).val(function (_, v) {
                        return v.replace(/\s+/g, '');
                    });
                });
            });
        </script>
    </head>
    <body>
        <div id="outer-box">
            <div id="mm">
                <h1>Add Product Units</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                    <input type="hidden" name="category" value="itm_unit">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Unit Symbol :-</label>
                        <input name="unit_code" type="text" id="unit_code" class="validate[required] form-control" maxlength="45" value="<?php
                        if (isset($_POST['unit_code'])) {
                            echo $_POST['unit_code'];
                        }
                        ?>" placeholder="">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Unit Name :-</label>
                        <input name="unit_name" type="text" class="validate[required] form-control" maxlength="45" value="<?php
                        if (isset($_POST['unit_name'])) {
                            echo $_POST['unit_name'];
                        }
                        ?>" placeholder="">
                    </div>
                    <div class="form-group col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Unit</button>
                    </div>
                    <!-- forms END -->
                </form>
            </div>
            <hr class="faded"/>
            <?php
            include_once ('../pagination_function.php');
            $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
            /* page URL */
            $url = 'dashboard.php?page=master_entries&subpage=4';
            /* Item Limit */
            $limit = 15;
            $startpoint = ($pagepagi * $limit) - $limit;

            $statement = "* FROM item_unit ORDER BY unit_code ASC";
            $pagination_statment = "item_unit ORDER BY unit_code ASC";

            $query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
            $result = $myCon->query($query);
            if ($result) {
                $count = mysqli_num_rows($result);
            } else {
                $count = 0;
            }
            if ($count > 0) {
                ?>
                <h1>Product Units</h1>
                <div class="col-xs-12 voffset-1">
                    <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                </div>
                <div id="details-viewer" class="col-xs-12 voffset-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Unit Name</td>
                                    <td style="width: 50px">Edit</td>
                                </tr>
                            </thead>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tbody>
                                    <tr>
                                        <td <?php
                                        if ($row['active'] == '0') {
                                            echo'class="innactive"';
                                        }
                                        ?>>[<?php echo $row['unit_code']; ?>] <?php echo($row['unit_name']); ?>
                                        </td>
                                        <td align="left" valign="middle"><a href="javascript:void(0)" onClick="editUnit('<?php echo($row['unit_code']); ?>')" class="btn btn-xs btn-primary voffset-b-2">Edit</a></td>
                                    </tr>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                    <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                </div>
            <?php } ?> 
        </div>
    </body>
</html>