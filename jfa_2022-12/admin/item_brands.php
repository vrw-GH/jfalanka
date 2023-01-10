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
            });
        </script>
    </head>
    <body>
        <div id="outer-box">
            <div id="mm">
                <h1>Add Brands</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="category" value="itm_brand">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Main Entry Name :-</label>
                        <?php
                        $query = "SELECT * FROM item_main_category WHERE active='1' ORDER BY cat_name ASC";
                        $result = $myCon->query($query);
                        ?>
                        <select name="cat_code" class="validate[required] form-control selectpicker" data-size="5">
                            <option disabled selected>Please Select</option>
                            <option value="0">Generic Category (can be applied for all categories)</option>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?php echo($row['cat_code']); ?>"><?php echo($row['cat_name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Brand Name :-</label>
                        <input name="brand_name" type="text" class="validate[required] form-control" maxlength="45" value="<?php
                        if (isset($_POST['brand_name'])) {
                            echo $_POST['brand_name'];
                        }
                        ?>" placeholder="Brand Name">
                    </div>
                    <?php if ($website['images']['brand_display']) { ?>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Brand Image <span class="text-warning">(Featured)</span> :-</label>
                            <input type="file" name="brand_img" class="form-control"/><p class="help-block">Image must be a min. <?= $website['images'] ['brand_width'] ?>px * <?= $website['images'] ['brand_height'] ?>px image</p>
                        </div>
                    <?php } ?>
                    <?php if ($website['custom_url']['brand_display']) { ?>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Custom URL :-</label>
                            <input name="brand_custom_url" type="text" class="form-control" maxlength="255" value="<?php
                            if (isset($_POST['brand_custom_url'])) {
                                echo $_POST['brand_custom_url'];
                            }
                            ?>" placeholder="URL">
                        </div>
                    <?php } if ($website['descp'] ['brand_display']) { ?>
                        <div class="form-group col-xs-12 voffset-2">
                            <label>Brand Description :-</label>
                            <textarea name="brand_details"><?php
                                if (isset($_POST['brand_details'])) {
                                    echo $_POST['brand_details'];
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
                    <div class="form-group col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Brand</button>
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

            $statement = "* FROM item_brand ORDER BY brand_code DESC";
            $pagination_statment = "item_brand ORDER BY brand_code DESC";

            $query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
            $result = $myCon->query($query);
            if ($result) {
                $count = mysqli_num_rows($result);
            } else {
                $count = 0;
            }
            if ($count > 0) {
                ?>
                <h1>Brand List</h1>
                <div class="col-xs-12 voffset-1">
                    <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                </div>
                <div id="details-viewer" class="col-xs-12 voffset-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Brand Name</td>
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
                                        ?>>[<?php echo(sprintf("%03d", $row['brand_code'])); ?>] <?php echo($row['brand_name']); ?></td>
                                        <td><a href="javascript:void(0)" onClick="editBrand(<?php echo(sprintf("%03d", $row['brand_code'])); ?>)" class="btn btn-xs btn-primary voffset-b-2">Edit</a></td>
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