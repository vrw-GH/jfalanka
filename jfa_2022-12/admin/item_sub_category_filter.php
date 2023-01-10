<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

/* admin config must load through site_config.php. If there is a fault, execute direct include */
include_once 'admin_config.php';

include_once '../models/dbConfig.php';
$myCon = new dbConfig();
$myCon->connect();

$cat_code = $_REQUEST['cat_code'];


include_once ('../pagination_function.php');
$pagepagi = (int) (!isset($_REQUEST["pagepagi"]) ? 1 : $_REQUEST["pagepagi"]);
/* page URL */
$url = 'dashboard.php?page=master_entries&subpage=2';
/* Item Limit */
$limit = 15;
$startpoint = ($pagepagi * $limit) - $limit;

$statement = "* FROM item_sub_category WHERE cat_code='" . $cat_code . "' ORDER BY sub_code DESC";
$pagination_statment = "item_sub_category WHERE cat_code='" . $cat_code . "' ORDER BY sub_code DESC";

$query = "SELECT {$statement} LIMIT {$startpoint} , {$limit}";
$result = $myCon->query($query);
if ($result) {
    $count = mysqli_num_rows($result);
} else {
    $count = 0;
}
?>
<script type="text/javascript">
    $(function () {
        $(".iframe").colorbox({escKey: false, overlayClose: false, iframe: true, width: "94%", height: "94%"});
    });
</script>
<div class="col-xs-12">
    <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr class="text-center">
            <td>Sub Entry Name</td>
            <td style="width: 80px">Edit</td>
            <td style="width: 125px">Del</td>
        </tr>
    </thead>
    <?php
    if ($count > 0) {
        ?>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td height="10" align="left" valign="middle" <?php
                    if ($row['active'] == '0') {
                        echo'class="innactive"';
                    }
                    ?>>
                            <?php echo($row['sub_name']); ?>
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
                <?php
            }
            ?>
        </tbody>
        <?php
    } else {
        ?>
        <tbody>
            <tr>
                <td colspan="3" >Sorry, no records founds</td>
            </tr>
        </tbody>
    <?php } ?>
</table>
<div class="col-xs-12">
    <?php echo pagination($pagination_statment, $limit, $pagepagi, $url); ?>
</div>