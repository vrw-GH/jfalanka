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

                $('#app_code').bind('input', function () {
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
                <h1>Add Product Appearance</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                    <input type="hidden" name="category" value="itm_app">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Appearance Name :-</label>
                        <input name="app_name" type="text" class="validate[required] form-control" maxlength="45" value="<?php
                        if (isset($_POST['app_name'])) {
                            echo $_POST['app_name'];
                        }
                        ?>" placeholder="">
                    </div>
                    <div class="form-group col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Appearance</button>
                    </div>
                    <!-- forms END -->
                </form>
            </div>
            <hr class="faded"/>
            <?php
            include_once ('../pagination_function.php');
            $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
            /* page URL */
            $url = 'dashboard.php?page=master_entries&subpage=3';
            /* Item Limit */
            $limit = 15;
            $startpoint = ($pagepagi * $limit) - $limit;

            $statement = "* FROM item_appear ORDER BY app_code ASC";
            $pagination_statment = "item_appear ORDER BY app_code ASC";

            $query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
            $result = $myCon->query($query);
            if ($result) {
                $count = mysqli_num_rows($result);
            } else {
                $count = 0;
            }
            if ($count > 0) {
                ?>
                <h1>Product Appearance</h1>
                <div id="details-viewer" class="col-xs-12 voffset-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Appearance Name</td>
                                    <td style="width: 50px">Edit</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td <?php
                                        if ($row['active'] == '0') {
                                            echo'class="innactive"';
                                        }
                                        ?>><?php echo($row['app_name']); ?></td>
                                        <td><a href="javascript:void(0)" onClick="editAppearance(<?php echo($row['app_code']); ?>)" class="btn btn-xs btn-primary voffset-b-2">Edit</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
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