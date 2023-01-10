<?php
$d_id = $_REQUEST['d_id'];
if (trim($d_id) != null) {
    include '../models/dbConfig.php';
    $myCon = new dbConfig();
    $myCon->connect();
    ?>
    <select name="c_id" class="validate[required] form-control" >
        <option value="" selected>Please Select</option>
        <?php
        $result = $myCon->query("SELECT * FROM sl_city WHERE d_id='" . $d_id . "' ORDER BY 
                                c_name ASC");
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <option value="<?php echo($row['c_id']); ?>"><?php echo($row['c_name']); ?></option>
            <?php
        }
        $myCon->closeCon();
        ?>
    </select>
    <?php
}
?>