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

                /* Autocomplete */
                $("#item_code2").autocomplete({
                    minLength: 2,
                    source: "item_search_filter.php"
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

            function confirmActive(comm_id) {
                $("#dialog-confirm-approve").dialog({
                    resizable: false,
                    width: 400,
                    height: 180,
                    modal: true,
                    buttons: {
                        "Submit ": function () {
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
    </head>
    <body>
        <div id="dialog-confirm-delete" title="Delete" style="display:none">
            <p><span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>You're going to delete a <strong>post</strong>.<br/>Are you sure?</p>
        </div>
        <div id="dialog-confirm-approve" title="Display Status" style="display:none">
            <p><span class="ui-icon ui-icon-info" style="float: left; margin: 0 7px 20px 0;"></span>You're going to change <strong>Display status</strong>.<br/>Are you sure?</p>
        </div>
        <div id="outer-box">
            <h1>Post Search</h1>
            <form id="formIDSearch" class="allforms" enctype="multipart/form-data">
                <!--
                <div class="form-group col-xs-12">
                    <div class="input-group">
                        <div class="input-group-addon">Search Name</div>
                        <input name="item_code2" type="text" id="item_code2" onChange="filterPost(this.value)" class="form-control"/>
                        <div class="input-group-addon btn btn-sm" id="itm_btn" onClick="filterPost(item_code2.value)">
                            view
                        </div>
                    </div>
                </div>
                -->
                <div class="form-group col-xs-12">
                    <div class="input-group">
                        <div class="input-group-addon">Select Post</div>
                        <select class="form-control selectpicker" data-live-search="true" data-size="5" onChange="filterPost(this.value)">
                            <option disabled selected>Please select or search</option>
                            <?php
                            $query_cat = "SELECT p.cat_code, c.cat_name FROM posts_sub_cat p LEFT JOIN "
                                    . "item_main_category c ON p.cat_code=c.cat_code GROUP BY p.cat_code "
                                    . "ORDER BY c.cat_order ASC";
                            $result_cat = $myCon->query($query_cat);
                            while ($row_cat = mysqli_fetch_assoc($result_cat)) {
                                echo '<optgroup label="' . $row_cat['cat_name'] . '">';
                                $query_post = "SELECT p.post_code, p.post_name FROM posts p LEFT JOIN "
                                        . "posts_sub_cat s ON p.post_code = s.post_code WHERE "
                                        . "s.cat_code = '" . $row_cat['cat_code'] . "'";
                                $result_post = $myCon->query($query_post);
                                while ($row_post = mysqli_fetch_assoc($result_post)) {
                                    ?>
                                    <option value="<?php echo($row_post['post_code']); ?>"><?php echo($row_post['post_name']); ?></option>
                                    <?php
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </form>
            <div id="ff">&nbsp;</div>
            <h1 class="voffset-3">Post list</h1>
            <div class="col-xs-12">
                <?php
                include_once ('../pagination_function.php');
                $pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
                /* page URL */
                $url = 'dashboard.php?page=post_entries&subpage=2';
                /* Item Limit */
                $limit = 30;
                $startpoint = ($pagepagi * $limit) - $limit;

                $statement = "p.post_code, p.post_name, p.add_date, p.active, p.featured, m.mem_fname, c.cat_name FROM "
                        . "posts p LEFT JOIN members m ON p.add_by=m.mem_id LEFT JOIN posts_sub_cat sc ON sc.post_code=p.post_code "
                        . "LEFT JOIN item_main_category c ON c.cat_code=sc.cat_code ORDER BY p.post_order ASC";
                $pagination_statment = "posts ORDER BY post_order ASC";

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
                <div id="details-viewer" class="col-xs-12 voffset-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td style="width: 85px">Code</td>
                                    <td>Post</td>
                                    <td style="width: 190px">Function</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['post_code']; ?></td>
                                        <td class="<?php
                                        if ($row['active'] == '0') {
                                            echo' innactive';
                                        }
                                        if ($row['featured'] == '0') {
                                            echo' blue';
                                        }
                                        ?>"><?php echo $row['post_name']; ?> 
                                            <div class="smallfont">
                                                <strong> on</strong> <?php echo substr($row['add_date'], 0, 10); ?> | 
                                                <strong> by</strong> <?php echo $row['mem_fname']; ?> | 
                                                <strong> category</strong> <?php echo $row['cat_name']; ?> | 
                                                <strong> display</strong> 
                                                <?php
                                                if ($row['active'] == '0') {
                                                    echo'<span class="label label-danger">Innactive</span>';
                                                } else {
                                                    echo'<span class="label label-success">Active</span>';
                                                }
                                                if ($row['featured'] == '1') {
                                                    echo' &nbsp;<span class="label label-primary">Featured</span>';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($row['active'] == '0') { ?>
                                                <form method="post" action="" id="formIDApp<?php echo $row['post_code']; ?>">
                                                    <input type="hidden" name="category" value="post"/>
                                                    <input type="hidden" name="action" value="active"/>
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                                    <input type="hidden" name="post_code" value="<?php echo $row['post_code']; ?>"/>
                                                    <input type="hidden" name="active" value="1"/>
                                                    <a href="javascript:void(0)" onClick="confirmActive('<?php echo($row['post_code']); ?>')" class="btn btn-xs btn-success voffset-b-2 float-left">Show</a>
                                                </form>
                                            <?php } else { ?>
                                                <form method="post" action="" id="formIDApp<?php echo $row['post_code']; ?>">
                                                    <input type="hidden" name="category" value="post"/>
                                                    <input type="hidden" name="action" value="active"/>
                                                    <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                                    <input type="hidden" name="post_code" value="<?php echo $row['post_code']; ?>"/>
                                                    <input type="hidden" name="active" value="0"/>
                                                    <a href="javascript:void(0)" onClick="confirmActive('<?php echo($row['post_code']); ?>')" class="btn btn-xs btn-warning voffset-b-2 float-left">Hide</a>
                                                </form>
                                            <?php } ?>
                                            <a href="post_edit.php?post_code=<?php echo $row['post_code']; ?>" class="btn btn-xs btn-primary voffset-b-2 float-left offset-l-2 iframe">Edit</a>
                                            <a href="post_gallery.php?post_code=<?php echo($row['post_code']); ?>" class="btn btn-xs btn-primary voffset-b-2 offset-l-2 float-left iframe"><span class="glyphicon glyphicon-picture"></span></a>
                                            <form method="post" action="" id="formIDDel<?php echo $row['post_code']; ?>">
                                                <input type="hidden" name="category" value="post"/>
                                                <input type="hidden" name="action" value="delete"/>
                                                <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                                <input type="hidden" name="post_code" value="<?php echo $row['post_code']; ?>"/>
                                                <a href="javascript:void(0)" onClick="confirmDelete('<?php echo($row['post_code']); ?>')" class="btn btn-xs btn-danger float-left offset-l-2 voffset-b-2"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>