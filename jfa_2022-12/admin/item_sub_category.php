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

            function confirmDelete(del_id) {
                $("#dialog-confirm-delete").dialog({
                    resizable: false,
                    width: 400,
                    height: 180,
                    modal: true,
                    buttons: {
                        "Delete Post ": function () {
                            $(this).dialog("close");
                            document.getElementById('formIDDel' + del_id).submit();
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <div id="dialog-confirm-delete" title="Delete" style="display:none">
            <p><span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>You're going to delete a <strong>sub category</strong>.<br/>Are you sure?</p>
        </div>
        <div id="outer-box">
            <div id="mm">
                <h1>Add Sub Entries</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="category" value="itm_sub_cat">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Main Entry Name :-</label>
                        <?php
                        $query = "SELECT * FROM item_main_category WHERE active!='0' ORDER BY cat_name ASC";
                        $result = $myCon->query($query);
                        ?>
                        <select name="cat_code" class="validate[required] form-control selectpicker" data-size="5">
                            <option disabled selected>Please Select</option>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?php echo($row['cat_code']); ?>" <?php
                                if (isset($_POST['cat_code']) && $_POST['cat_code'] == $row['cat_code']) {
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
                    <?php } ?>
                    <?php if ($website['descp'] ['sub_entry']) { ?>
                        <div class="form-group col-xs-12">
                            <label>Sub Entry Description :-</label>
                            <textarea name="sub_details" class=""><?php
                                if (isset($_POST['sub_details'])) {
                                    echo $_POST['sub_details'];
                                }
                                ?></textarea>
                            <script>
                                CKEDITOR.replace('sub_details', {
                                    removeButtons: 'About',
                                    uiColor: '#ffffff',
                                    extraPlugins: 'imageuploader',
                                    allowedContent: true
                                });</script>
                        </div>
                    <?php } ?>
                    <div class="form-group col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Sub Entry</button>
                    </div>
                    <!-- forms END -->
                </form>
            </div>
            <hr class="faded"/>
            <?php
            /* Main entry display */
            $queryleft = "SELECT * FROM item_main_category WHERE active !='0' ORDER BY cat_order DESC";
            $resultleft = $myCon->query($queryleft);

            /* page load Sub entry display */
            $resultf = $myCon->query("SELECT * FROM item_main_category WHERE active !='0' ORDER BY cat_order DESC LIMIT 1");
            $c_code = 0;
            while ($rowf = mysqli_fetch_assoc($resultf)) {
                $c_code = $rowf['cat_code'];
            }

            include_once ('../pagination_function.php');
            $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
            /* page URL */
            $url = 'dashboard.php?page=master_entries&subpage=2';
            /* Item Limit */
            $limit = 15;
            $startpoint = ($pagepagi * $limit) - $limit;

            $statement = "* FROM item_sub_category WHERE cat_code='" . $c_code . "' ORDER BY sub_code DESC";
            $pagination_statment = "item_sub_category WHERE cat_code='" . $c_code . "' ORDER BY sub_code DESC";

            $query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
            $result = $myCon->query($query);
            if ($result) {
                $count = mysqli_num_rows($result);
            } else {
                $count = 0;
            }
            ?>
            <div class="col-xs-12 col-xs-pdn-both-0 col-xxs-pdn-both-0">
                <h1>
                    Sub Entries List <a href="item_sub_category_sort.php" class="btn btn-sm btn-primary square float-right iframe"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp; Change Order</a>
                </h1>
                <div id="details-viewer" class="col-xs-12 col-md-5 firstviewer">
                    <h1>Main Entries</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Main Entry Name</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);" onClick="viewSubCategory(<?php echo(sprintf("%02d", 0)); ?>)" class="label label-default">[00] Uncategorized</a>
                                    </td>
                                </tr>
                                <?php while ($rowleft = mysqli_fetch_assoc($resultleft)) { ?>
                                    <tr>
                                        <td <?php
                                        if ($row['active'] == '0') {
                                            echo'class="innactive"';
                                        }
                                        if ($row['active'] == '2') {
                                            echo'class="manual"';
                                        }
                                        ?>>
                                            <a href="javascript:void(0);" onClick="viewSubCategory(<?php echo(sprintf("%02d", $rowleft['cat_code'])); ?>)" class="link">[<?php echo(sprintf("%02d", $rowleft['cat_code'])); ?>] <?php echo($rowleft['cat_name']); ?></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div id="details-viewer"  class="col-xs-12 col-pdn-both-0 lastviewer">
                        <div class="col-xs-12">
                            <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                        </div>
                        <?php if ($count > 0) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <td>Sub Entry Name</td>
                                        <td style="width: 80px">Edit</td>
                                        <td style="width: 125px">Del</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td <?php
                                            if ($row['active'] == '0') {
                                                echo'class="innactive"';
                                            }
                                            ?>><?php echo($row['sub_name']); ?>
                                                <div class="smallfont text-muted">
                                                    <strong> cat code</strong> <?php echo sprintf("%02d", $row['cat_code']); ?> | 
                                                    <strong> sub code</strong> <?php echo sprintf("%02d", $row['sub_code']); ?> |
                                                    <strong> display</strong> 
                                                    <?php
                                                    if ($row['active'] == '0') {
                                                        echo'<span class="label label-danger">Innactive</span>';
                                                    } else if ($row['active'] == '1') {
                                                        echo'<span class="label label-info">Auto</span>';
                                                    } else {
                                                        echo'<span class="label label-default">Manual</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" onClick="editSubCategory(<?php echo($row['auto_num']); ?>)" class="btn btn-xs btn-primary voffset-b-2">Edit</a>
                                                <?php if ($website['gallery']['sub_entry'] == true) { ?>
                                                    <a href="item_sub_category_gallery.php?auto_num=<?php echo $row['auto_num']; ?>"  class="btn btn-xs btn-primary voffset-b-2 iframe"><span class="glyphicon glyphicon-picture"></span></a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <form method="post" action="" id="formIDDel<?php echo $row['auto_num']; ?>">
                                                    <input type="hidden" name="category" value="itm_sub_cat">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">
                                                    <input type="hidden" name="auto_num" value="<?php echo $row['auto_num']; ?>">
                                                    <div class="checkbox text-left">
                                                        <label>
                                                            <input type="checkbox" name="del_all" value="true"> Including posts 
                                                        </label>
                                                    </div>
                                                    <a href="javascript:void(0)" onClick="confirmDelete('<?php echo($row['auto_num']); ?>')" class="btn btn-danger btn-xs float-right"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        <div class="col-xs-12">
                            <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>