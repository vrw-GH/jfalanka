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
                $("#jq_pass_name").html('News Name: ' + name);

                $("#dialog-delete-confirm").dialog({
                    resizable: false,
                    width: 400,
                    height: 180,
                    modal: true,
                    buttons: {
                        "Delete Record": function () {
                            $(this).dialog("close");
                            document.getElementById('form' + id).submit();
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
                <h1>Add News and Events</h1>
                <form id="formID" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="category" value="news">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['system_logged_uname']; ?>" >
                    <fieldset>
                        <div class="form-group col-xs-12 col-sm-6"><label><span class="text-danger">*</span>News Event Title :-</label>
                            <input name="ann_title" type="text" class="validate[required] form-control" maxlength="80" value="<?php
                            if (isset($_POST['ann_title'])) {
                                echo $_POST['ann_title'];
                            }
                            ?>">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6"><label>News Image <span class="text-warning">(Featured)</span> :-</label>
                            <input type="file" name="ann_img" class="form-control"/>
                            <div class="help-block">Image should be minimum (<?php echo $website['images']['news_width']; ?>px * <?php echo $website['images']['news_height']; ?>px & max. 8Mb)</div>
                        </div>
                        <div class="form-group col-xs-12 voffset-b-2">
                            <label><span class="text-danger">*</span>News Event Details :-</label>
                            <textarea name="ann_details" class=""><?php
                                if (isset($_POST['ann_details'])) {
                                    echo $_POST['ann_details'];
                                }
                                ?></textarea>
                            <script>
                                CKEDITOR.replace('ann_details', {
                                    removeButtons: 'About',
                                    uiColor: '#ffffff',
                                    height: '400px',
                                    extraPlugins: 'imageuploader',
                                    allowedContent: true
                                });
                            </script>
                        </div>
                        <div class="form-group col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-check"></span> Add News / Event</button>
                        </div>
                    </fieldset>
                    <!-- forms END -->
                </form>
            </div>
            <hr class="faded"/>
            <?php
            include_once ('../pagination_function.php');
            $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
            /* page URL */
            $url = 'dashboard.php?page=master_entries&subpage=8';
            /* Item Limit */
            $limit = 15;
            $startpoint = ($pagepagi * $limit) - $limit;

            $statement = "* FROM news_events ORDER BY ann_id ASC";
            $pagination_statment = "news_events ORDER BY ann_id ASC";

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
                    <h1>News Event List</h1>
                    <div class="col-xs-12 voffset-1">
                        <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
                    </div>
                    <div id="details-viewer" class="col-xs-12 voffset-1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>News Event Name</td>
                                        <td style="width: 150px">Function</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td>
                                                <?php echo($row['ann_title']); ?>
                                                <br/>
                                                <div class="smallfont text-muted">
                                                    <strong> add date</strong> <?php echo $row['add_date']; ?> | 
                                                    <strong> updated date</strong> <?php echo $row['upd_date']; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" onClick="editNews(<?php echo $row['ann_id']; ?>)" class="btn btn-xs btn-primary voffset-b-2 float-left">Edit</a>
                                                <form method="post" id="form<?php echo($row['ann_id']); ?>" action="" class="form-item">
                                                    <input type="hidden" name="category" value="news"/>
                                                    <input type="hidden" name="action" value="delete"/>
                                                    <input type="hidden" name="ann_id" value="<?php echo $row['ann_id']; ?>" />
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>"/>
                                                </form>
                                                <a href="news_gallery.php?ann_id=<?php echo $row['ann_id']; ?>" class="btn btn-xs btn-primary voffset-b-2 float-left offset-l-2 iframe"><span class="glyphicon glyphicon-picture"></span></a>
                                                <a href="javascript:void(0)" onClick="confirmOnDelete('<?php echo($row['ann_id']); ?>', '<?php echo($row['ann_title']); ?>');" class="btn btn-danger btn-xs offset-l-2 float-left"><span class="glyphicon glyphicon-trash"></span> Delete</a> 
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