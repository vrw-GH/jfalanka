<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

/* include all the settings */
include_once '../site_config.php';
/* admin config must load through site_config.php. If there is a fault, execute direct include */
include_once 'admin_config.php';

include_once '../models/dbConfig.php';

$myCon = new dbConfig();
$myCon->connect();

$type = $_REQUEST['type'];

/* generating query part */
if (isset($_REQUEST['cat_code'])) {
    $count = count($_REQUEST['cat_code']);
} else {
    $count = 0;
}
$q_prt = "";
$aa = $count;
if ($count >= 1) {
    foreach ($_REQUEST['cat_code'] as $cat_code => $value) {
        if ($count == 1) {
            $q_prt = "s.cat_code= " . $value;
        } else {
            if ($aa == 1) {
                $q_prt.="s.cat_code= " . $value;
            } else {
                $q_prt.="s.cat_code= " . $value . " OR ";
            }
            $aa-=1;
        }
    }
} else {
    $q_prt = "s.cat_code = 0";
}

/* get checked subcodes ----- */
$sub_codes = array();
if (!empty($_REQUEST['sub_code'])) {
    foreach ($_REQUEST['sub_code'] as $sc) {
        array_push($sub_codes, $sc);
    }
}
/* ----- */
?>
<script>
    $(function () {
        $('.selectpicker').selectpicker({});
    });
</script>
<?php
if ($type == 'subcat' && $website['config']['sub_cat'] = true) {
    $query = "SELECT s.*, m.cat_name FROM item_sub_category s LEFT JOIN "
            . "item_main_category m ON s.cat_code = m.cat_code WHERE ($q_prt) "
            . "AND s.active='1' ORDER BY s.cat_code, s.sub_name ASC";
    $result = $myCon->query($query);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        echo '<label><span class="text-danger">*</span>Sub Entry Name :-</label>';
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="checkbox col-xs-12 col-md-6">
                <label>
                    <input class="post_sub_code" type="checkbox" name="sub_code[]" value="<?php echo($row['cat_code'] . $row['sub_code']); ?>" 
                    <?php
                    if (in_array($row['cat_code'] . $row['sub_code'], $sub_codes)) {
                        echo 'checked';
                    }
                    ?>> <?php echo($row['sub_name'] . ' <span class="text-warning">[' . $row['cat_name'] . ']</span>'); ?>
                </label>
            </div>

            <?php
        }
    } else {
        echo '<label>Sub Entry Name :-</label><br/>';
        echo '<span class="text-info">No Sub Entries found!</span>';
    }
}

if ($type == 'brand' && $website['config']['brands'] == true) {
    $query = "SELECT s.* FROM item_brand s WHERE ($q_prt OR s.cat_code=0) AND "
            . "s.active='1' ORDER BY s.brand_name ASC";
    $result = $myCon->query($query);
    ?>
    <select name="brand_code" class="validate[required] form-control selectpicker" data-size="5">
        <option disabled selected>Please Select</option>
        <optgroup label="No option will be applied">
            <option value="0">No Apply (Blank)</option>
        </optgroup>
        <optgroup label="Database entered options">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo($row['brand_code']); ?>"
                <?php
                if (isset($_REQUEST['brandcode'])) {
                    if ($row['brand_code'] == $_REQUEST['brandcode']) {
                        echo 'selected';
                    }
                }
                ?>><?php echo($row['brand_name']); ?></option>
                    <?php } ?>
        </optgroup>
    </select>
<?php } ?>
