<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../models/dbConfig.php';
$myCon = new dbConfig();
$myCon->connect();
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
                        "Delete Comment ": function () {
                            $(this).dialog("close");
                            document.getElementById('formIDDel' + del_id).submit();
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
            function confirmApprove(comm_id) {
                $("#dialog-confirm-approve").dialog({
                    resizable: false,
                    width: 400,
                    height: 180,
                    modal: true,
                    buttons: {
                        "Approve Comment ": function () {
                            $(this).dialog("close");
                            document.getElementById('formIDApp' + comm_id).submit();
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        </script>
        <style>
            .ui-widget{
                font-size: 13px !important;
            }
        </style>
    </head>
    <body>
        <div id="dialog-confirm-delete" title="Delete Comment" style="display:none">
            <p><span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>You're going to delete a <strong>Comment</strong>.<br/>Are you sure?</p>
        </div>
        <div id="dialog-confirm-approve" title="Approve a Comment" style="display:none">
            <p><span class="ui-icon ui-icon-info" style="float: left; margin: 0 7px 20px 0;"></span>You're going to approve a <strong>Comment</strong>.<br/>Are you sure?</p>
        </div>
        <?php
        $query = " SELECT * FROM comments WHERE approved = '0'";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        ?>
        <h2>Pending Comments <span class="badge"><?php echo $count; ?></span></h2>
        <?php
        $myCon = new dbConfig();
        $myCon->connect();


        $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
        ?>
        <div id="outer-box">
            <div id="mm">

            </div>
            <hr class="faded"/>
            <h1>Comment List</h1>
            <?php
            include_once ('../pagination_function.php');
            $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
            /* page URL */
            $url = 'dashboard.php?page=master_entries&subpage=9';
            /* Item Limit */
            $limit = 10;
            $startpoint = ($pagepagi * $limit) - $limit;

            $statement = "c.*, m.cat_name, s.sub_name, p.post_name FROM comments c LEFT JOIN "
                    . "item_main_category m ON c.cat_code= m.cat_code LEFT JOIN "
                    . "item_sub_category s ON c.cat_code= s.cat_code AND c.sub_code= s.sub_code "
                    . "LEFT JOIN posts p ON c.post_code= p.cat_code ORDER BY c.comm_code DESC";
            $pagination_statment = "comments ORDER BY comm_code DESC";

            $query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
            $result = $myCon->query($query);
            if ($result) {
                $count = mysqli_num_rows($result);
            } else {
                $count = 0;
            }
            ?>
            <div class="col-xs-12">
                <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
            </div>
            <div id="details-viewer" class="full-wide">
                <?php if ($count > 0) { ?>
                    <table>
                        <thead>
                            <tr>
                                <td align="center" valign="middle">Comment</td>
                                <td width="200" align="center" valign="middle">Function</td>
                            </tr>
                        </thead>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tbody>
                                <tr>
                                    <td align="left" valign="middle" <?php
                                    if ($row['approved'] == '0') {
                                        echo'class="red"';
                                    } else {
                                        echo'class="green"';
                                    }
                                    ?>><?php
                                            echo('<strong>Title: </strong>' . $row['comm_title'] . '<br/>'
                                            . '<strong>Name: </strong>' . $row['comm_name'] . ' | '
                                            . '<strong>Email: </strong>' . $row['comm_email'] . '<br/>' );
                                            if ($row['cat_code'] != 0) {
                                                echo '<strong>Category: </strong>' . $row['cat_name'] . '<br/>';
                                            }
                                            if ($row['sub_code'] != 0) {
                                                echo '<strong>Sub Category: </strong>' . $row['sub_name'] . '<br/>';
                                            }
                                            if ($row['post_code'] != 0) {
                                                echo '<strong>Post name: </strong>' . $row['post_name'] . '<br/>';
                                            }
                                            ?>
                                        <p style="word-break: break-all;"><?php echo $row['comment']; ?></p>
                                    </td>
                                    <td align="left" valign="middle">
                                        <?php if ($row['approved'] == '0') { ?>
                                            <span id="edit">
                                                <form method="post" action="" id="formIDApp<?php echo $row['comm_code']; ?>">
                                                    <input type="hidden" name="category" value="comments"/>
                                                    <input type="hidden" name="action" value="approve"/>
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                                    <input type="hidden" name="comm_code" value="<?php echo $row['comm_code']; ?>"/>
                                                    <a href="javascript:void(0)" onClick="confirmApprove(<?php echo($row['comm_code']); ?>)">Approve</a> | 
                                                </form>

                                            </span>
                                        <?php } ?>
                                        <span id="delete">
                                            <form method="post" action="" id="formIDDel<?php echo $row['comm_code']; ?>">
                                                <input type="hidden" name="category" value="comments"/>
                                                <input type="hidden" name="action" value="delete"/>
                                                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                                <input type="hidden" name="comm_code" value="<?php echo $row['comm_code']; ?>"/>
                                                <a href="javascript:void(0)" onClick="confirmDelete(<?php echo($row['comm_code']); ?>)">Delete</a>
                                            </form>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
            <div class="col-xs-12">
                <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
            </div>

        </div>
    </body>
</html>