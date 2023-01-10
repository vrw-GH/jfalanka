<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../models/dbConfig.php';

$_SESSION['key'] = date('His') . mt_rand(1000, 9999);
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
            $(document).ready(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });
            });

            $(function () {
                $("#sortable").sortable({
                    placeholder: "ui-state-highlight"
                });
                $("#sortable").disableSelection();
            });

            function saveOrder() {
                var selectedLanguage = new Array();
                $('ul#sortable li').each(function () {
                    selectedLanguage.push($(this).attr("id"));
                });
                document.getElementById("row_order").value = selectedLanguage;
            }

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
            <p><span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>You're going to delete this <strong>slider image</strong>.<br/>Are you sure?</p>
        </div>
        <div id="outer-box">
            <div id="mm">
                <h1>Add Slider Image</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="category" value="slider_img"/>
                    <input type="hidden" name="action" value="insert"/>
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                    <fieldset>
                        <div class="form-group col-xs-12">
                            <label>Slider Headline (Optional) : </label>
                            <input class="form-control" type="text" name="content_header" placeholder="Headline"/>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Slider Description (Optional) : </label>
                            <textarea class="form-control" name="content_descp" placeholder="Description"></textarea>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Image URL (Optional) : </label>
                            <input class="form-control" type="text" name="content_url" placeholder="http://page_url.php"/>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 clear-both">
                            <label><span class="text-danger">*</span>Select an Image : </label>
                            <input class="validate[required] form-control" type="file" name="slider_img"/>
                            <div class="help-block">Image should be minimum (<?php echo $website['images'] ['main_slider_width']; ?>px * <?php echo $website['images'] ['main_slider_height']; ?>px & max. 8Mb).</div>
                        </div>
                        <div class="form-group col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Add Image</button>
                        </div>
                    </fieldset>
                    <!-- forms END -->
                </form>
            </div>
            <hr class="faded"/>
            <?php
            $myCon = new dbConfig();
            $myCon->connect();
            $query = "SELECT u.upload_id, u.upload_path FROM upload_data u LEFT JOIN "
                    . "slider_content s ON u.upload_id = s.upload_id WHERE u.upload_type_id='1' "
                    . "ORDER BY s.slider_order ASC";
            $result = $myCon->query($query);
            ?>
            <h1>
                Slider Images <a href="slider_sort.php" class="btn btn-sm btn-primary square float-right iframe"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp; Change Order</a>
            </h1>
            <div id="details-viewer" class="full-wide">
                <?php
                $form_id = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-xs-6 col-sm-4">
                        <div id="imgbox-outer">
                            <form id="formIDDel<?php echo $form_id ?>" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="category" value="slider_img">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                                <input type="hidden" name="upload_id" value="<?php echo($row['upload_id']) ?>">
                                <img src="../uploads/<?php echo($row['upload_path']) ?>" alt="" class="img-responsive img-thumbnail"/>
                                <a class="btn btn-warning btn-sm voffset-4 voffset-b-2" href="javaScript:void(0);" onclick="editSlider(<?php echo($row['upload_id']) ?>)"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                <a href="javascript:void(0)" onClick="confirmDelete('<?php echo $form_id; ?>')" class="btn btn-danger btn-sm voffset-4 voffset-b-2"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                            </form>
                        </div>
                    </div>
                    <?php
                    $form_id = $form_id + 1;
                }
                ?>
            </div>
        </div>
    </body>
</html>