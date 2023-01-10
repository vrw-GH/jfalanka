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
        <script>
            function confirmOnDelete(id, name) {
                $("#jq_pass_name").html('Page: ' + name);

                $("#dialog-delete-confirm").dialog({
                    resizable: false,
                    width: 400,
                    height: 180,
                    modal: true,
                    buttons: {
                        "Delete Record": function () {
                            $(this).dialog("close");
                            document.getElementById('form-delete' + id).submit();
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
        <div id="dialog-delete-confirm" title="Delete Confirmation" style="display:none">
            <p><span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>You're going to delete the <strong id="jq_pass_name"></strong>.<br/>Are you sure?</p>
        </div>
        <div id="outer-box">
            <div id="mm">
                <h1>Add a Page</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                    <input type="hidden" name="category" value="pages">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['system_logged_uname']; ?>">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <div class="form-group col-xs-12 col-sm-6">
                        <label><span class="text-danger">*</span>Page Title :-</label>
                        <input name="page_title" type="text" class="validate[required] form-control" maxlength="60" value="<?php
                        if (isset($_POST['page_title'])) {
                            echo $_POST['page_title'];
                        }
                        ?>" placeholder="Page Title">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label>Page Top Image <span class="text-warning">(Featured)</span> :-</label>
                        <input type="file" name="page_img" class="form-control"/><p class="help-block">Image must be a min. <?php echo $website['images'] ['page_width']; ?>px * <?php echo $website['images'] ['page_height']; ?>px image</p>
                    </div>
                    <?php if ($website['custom_url']['page']) { ?>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Page Custom URL :-</label>
                            <input name="page_custom_url" type="text" class="form-control" maxlength="255" value="<?php
                            if (isset($_POST['page_custom_url'])) {
                                echo $_POST['page_custom_url'];
                            }
                            ?>" placeholder="URL">
                        </div>
                    <?php } ?>
                    <div class="form-group col-xs-12 voffset-2">
                        <label>Page Content :-</label>
                        <textarea name="page_content"><?php
                            if (isset($_POST['page_content'])) {
                                echo $_POST['page_content'];
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
                                if (isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type'] == 1) {
                                    echo 'selected';
                                }
                                ?>>Page Bottom Galley</option>
                                <option value="2" data-subtext="no thumbnails" <?php
                                if (isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type'] == 2) {
                                    echo 'selected';
                                }
                                ?>>Page Bottom Galley</option>
                                <option value="3" data-subtext="with thumbnails" <?php
                                if (isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type'] == 3) {
                                    echo 'selected';
                                }
                                ?>>Page Top Galley</option>
                                <option value="4" data-subtext="no thumbnails" <?php
                                if (isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type'] == 4) {
                                    echo 'selected';
                                }
                                ?>>Page Top Galley</option>
                            </optgroup>
                            <optgroup label="Full Width Slider">
                                <option value="5" <?php
                                if (isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type'] == 5) {
                                    echo 'selected';
                                }
                                ?>>Page Bottom Slider</option>
                                <option value="6" <?php
                                if (isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type'] == 6) {
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
                        if (isset($_POST['gallery_name'])) {
                            echo $_POST['gallery_name'];
                        } else {
                            echo 'Galley';
                        }
                        ?>" placeholder="Galley">
                        <p class="help-block">Keep this blank if you do not want to display galley title</p>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 voffset-2">
                        <label>Page Display Status :-</label>
                        <?php if (isset($_POST['active']) && $_POST['active'] == '0') { ?>
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
                    <div class="form-group col-xs-12 text-right voffset-2">
                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Page</button>
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

            $statement = "* FROM pages ORDER BY page_id ASC";
            $pagination_statment = "pages ORDER BY page_id ASC";

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
                    <h1>Page List</h1>
                    <div class="col-xs-12 voffset-1">
                        <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                    </div>
                    <div id="details-viewer" class="col-xs-12 voffset-1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>Page Name</td>
                                        <td style="width: 150px">Function</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td <?php
                                            if ($row['active'] == '0') {
                                                echo'class="innactive"';
                                            }
                                            ?>><?php echo($row['page_title']); ?>
                                                <div class="smallfont text-muted">
                                                    <strong> url slug</strong> <?php echo $row['page_url_slug']; ?>
                                                </div>
                                            </td>
                                            <td align="center" valign="middle">
                                                <form method="post" id="form-delete<?php echo($row['page_id']); ?>" action="" class="form-item">
                                                    <input type="hidden" name="category" value="pages"/>
                                                    <input type="hidden" name="action" value="delete"/>
                                                    <input type="hidden" name="page_id" value="<?php echo $row['page_id']; ?>" />
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                                                </form>
                                                <a href="javascript:void(0);" onClick="editPage(<?php echo $row['page_id']; ?>)" class="btn btn-xs btn-primary voffset-b-2 float-left">Edit</a>
                                                <a href="page_gallery.php?page_id=<?php echo($row['page_id']); ?>"  class="btn btn-xs btn-primary voffset-b-2 float-left offset-l-2 iframe"><span class="glyphicon glyphicon-picture"></span></a>
                                                <a href="javascript:void(0);" onClick="confirmOnDelete('<?php echo($row['page_id']); ?>', '<?php echo($row['page_title']); ?>');" class="btn btn-danger btn-xs offset-l-2 float-left"><span class="glyphicon glyphicon-trash"></span> Delete</a>
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