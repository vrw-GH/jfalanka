<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../models/dbConfig.php';
$myCon = new dbConfig();
$myCon->connect();

$post_code = $_REQUEST['post_code'];
$post_code = substr(trim($post_code), -9, 8);

if (is_numeric($post_code)) {
    $query = "SELECT p.post_code, p.post_name, p.add_date, p.active, p.featured, m.mem_fname FROM "
            . "posts p LEFT JOIN members m ON p.add_by=m.mem_id WHERE post_code = '" . $post_code . "' "
            . "ORDER BY p.post_code DESC LIMIT 1";
    $result = $myCon->query($query);
    if ($result) {
        ?>
        <div id="details-viewer" class="col-xs-12">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <h2 class="dark"><strong>Post Code :</strong> <?php echo $row['post_code']; ?></h2>
                <h2 class="dark"><strong>Post Name :</strong> <?php echo $row['post_name']; ?></h2>
                <h2 class="dark"><strong>Display :</strong> <?php
                    if ($row['active'] == 1) {
                        echo '<span class="green">Yes</span>';
                    } else {
                        echo '<span class="red">No</span>';
                    }
                    ?>
                </h2>
                <div class="col-xs-12 voffset-1 voffset-b-2 col-pdn-both-0">
                    <h2 class="voffset-b-1">Options</h2>
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
                    <a href="post_edit.php?post_code=<?php echo $row['post_code']; ?>" class="btn btn-xs btn-primary voffset-b-2 float-left offset-l-1 iframe">Edit</a>
                    <a href="post_gallery.php?post_code=<?php echo($row['post_code']); ?>" class="btn btn-xs btn-primary voffset-b-2 offset-l-1 float-left iframe"><span class="glyphicon glyphicon-picture"></span></a>
                    <form method="post" action="" id="formIDDel<?php echo $row['post_code']; ?>">
                        <input type="hidden" name="category" value="post"/>
                        <input type="hidden" name="action" value="delete"/>
                        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                        <input type="hidden" name="post_code" value="<?php echo $row['post_code']; ?>"/>
                        <a href="javascript:void(0)" onClick="confirmDelete('<?php echo($row['post_code']); ?>')" class="btn btn-xs btn-danger float-left offset-l-1 voffset-b-2"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                    </form>
                </div>
            </div>
            <?php
        }
    }
}
?>
<script type="text/javascript">
    $(function () {
        $(".iframe").colorbox({escKey: false, overlayClose: false, iframe: true, width: "94%", height: "94%"});
    });
</script>