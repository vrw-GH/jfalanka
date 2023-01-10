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
            <p><span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>You're going to delete a <strong>category</strong>.<br/>Are you sure?</p>
        </div>
        <div id="outer-box">
            <div id="mm" class="voffset-b-4">
                <h1>Add Main Entries</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                    <input type="hidden" name="category" value="itm_main_cat">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <div class="form-group col-xs-12">
                        <label><span class="text-danger">*</span>Main Entry Name :-</label>
                        <input name="cat_name" type="text" class="validate[required] form-control" maxlength="60" value="<?php
                        if (isset($_POST['cat_name'])) {
                            echo $_POST['cat_name'];
                        }
                        ?>" placeholder="Main Entry Name">
                    </div>
                    <div class="form-group col-xs-6 col-xxs-full-width">
                        <label>Main Entry Image <span class="text-warning">(Featured)</span> :-</label>
                        <input type="file" name="cat_img" class="form-control"/><p class="help-block">Image must be a min. <?php echo $website['images'] ['main_entry_width']; ?>px * <?php echo $website['images'] ['main_entry_height']; ?>px image</p>
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
                    <?php if ($website['custom_url']['main_entry']) { ?>
                        <div class="form-group col-xs-12 col-sm-6 clear-left">
                            <label>Custom URL :-</label>
                            <input name="custom_url" type="text" class="form-control" maxlength="255" value="<?php
                            if (isset($_POST['custom_url'])) {
                                echo $_POST['custom_url'];
                            }
                            ?>" placeholder="URL">
                        </div>
                    <?php } if ($website['descp'] ['main_entry']) { ?>
                        <div class="form-group col-xs-12 voffset-2">
                            <label>Main Entry Description :-</label>
                            <textarea name="cat_details"><?php
                                if (isset($_POST['cat_details'])) {
                                    echo $_POST['cat_details'];
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
                        <?php if (isset($_POST['active']) && $_POST['active'] == '0') { ?>
                            <select name="active" class="form-control selectpicker" data-size="5">
                                <option value="0" selected="selected">Do not Display</option>
                                <option value="1">Display - Auto</option>
                                <option value="2">Display - Manual</option>
                            </select>
                        <?php } else if (isset($_POST['active']) && $_POST['active'] == '1') { ?>
                            <select name="active" class="form-control selectpicker" data-size="5">
                                <option value="0">Do not Display</option>
                                <option value="1" selected="selected">Display - Auto</option>
                                <option value="2">Display - Manual</option>
                            </select>
                        <?php } else if (isset($_POST['active']) && $_POST['active'] == '2') { ?>
                            <select name="active" class="form-control selectpicker" data-size="5">
                                <option value="0">Do not Display</option>
                                <option value="1">Display - Auto</option>
                                <option value="2" selected="selected">Display - Manual</option>
                            </select>
                        <?php } else { ?>
                            <select name="active" class="form-control selectpicker" data-size="5">
                                <option value="0">Do not Display</option>
                                <option value="1" selected="selected">Display - Auto</option>
                                <option value="2">Display - Manual</option>
                            </select>
                        <?php } ?>	
                    </div>
                    <div class="form-group col-xs-12 text-right voffset-2">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Main Entry</button>
                    </div>
                    <!-- forms END -->
                </form>
            </div>
            <hr class="faded"/>
            <?php
            include_once ('../pagination_function.php');
            $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
            /* page URL */
            $url = 'dashboard.php?page=master_entries&subpage=1';
            /* Item Limit */
            $limit = 15;
            $startpoint = ($pagepagi * $limit) - $limit;

            $statement = "* FROM item_main_category ORDER BY cat_order DESC";
            $pagination_statment = "item_main_category ORDER BY cat_order DESC";

            $query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
            $result = $myCon->query($query);
            if ($result) {
                $count = mysqli_num_rows($result);
            } else {
                $count = 0;
            }
            if ($count > 0) {
                ?>
                <div class="col-xs-12 col-xs-pdn-both-0 col-xxs-pdn-both-0">
                    <h1>
                        Main Entry List <a href="item_main_category_sort.php" class="btn btn-sm btn-primary square float-right iframe"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp; Change Order</a>
                    </h1>
                    <div class="col-xs-12 voffset-1">
                        <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                    </div>
                    <div id="details-viewer" class="col-xs-12 voffset-1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <td>Main Entry Name</td>
                                        <td style="width: 80px">Edit</td>
                                        <td style="width: 150px">Del</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td <?php
                                            if ($row['active'] == '0') {
                                                echo'class="innactive"';
                                            }
                                            if ($row['active'] == '2') {
                                                echo'class="manual"';
                                            }
                                            ?>><?php echo($row['cat_name']); ?><br/>
                                                <div class="smallfont text-muted">
                                                    <strong> entry code</strong> <?php echo sprintf("%02d", $row['cat_code']); ?> | 
                                                    <strong> entry order</strong> <?php echo $row['cat_order']; ?> |
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
                                            <td style="width: 80px" class="text-center">
                                                <a href="javascript:void(0)" onClick="editMainCategory(<?php echo(sprintf("%02d", $row['cat_code'])); ?>)" class="btn btn-xs btn-primary voffset-b-2">Edit</a>
                                                <?php if ($website['gallery']['main_entry'] == true) { ?>
                                                    <a href="item_main_category_gallery.php?cat_code=<?php echo(sprintf("%02d", $row['cat_code'])); ?>"  class="btn btn-xs btn-primary voffset-b-2 iframe"><span class="glyphicon glyphicon-picture"></span></a>
                                                <?php } ?>                                 
                                            </td>
                                            <td style="width: 200px">
                                                <form method="post" action="" id="formIDDel<?php echo $row['cat_code']; ?>">
                                                    <input type="hidden" name="category" value="itm_main_cat">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">
                                                    <input type="hidden" name="cat_code" value="<?php echo $row['cat_code']; ?>">
                                                    <div class="checkbox text-left">
                                                        <label>
                                                            <input type="checkbox" name="del_all" value="true"> Including sub entries & posts 
                                                        </label>
                                                    </div>
                                                    <a href="javascript:void(0)" onClick="confirmDelete('<?php echo($row['cat_code']); ?>')" class="btn btn-danger btn-xs float-right"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                    </div>
                </div>
            <?php } ?> 
        </div>
    </body>
</html>