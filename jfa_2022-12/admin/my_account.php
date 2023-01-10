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

$systemdate = date('Y-m-d');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard - <?php echo $_SESSION['comp_name']; ?></title>
        <meta name="robots" content="noindex,nofollow" />
        <link rel="stylesheet" type="text/css" href="../<?php echo $website['boostrap_folder']; ?>/css/bootstrap.css" media="all"/>
        <link  rel="stylesheet" type="text/css" href="css/style.css" media="all"/>

        <script type="text/javascript" src="../<?php echo $website['jquery_min_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_lower_js']; ?>" charset="utf-8"></script>
        <!-- calling before jquery ui -->
        <script type="text/javascript" src="../<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8"></script>

        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_css']; ?>" media="all"/>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_theme_css']; ?>" media="all"/>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_structure_css']; ?>" media="all"/>
        <script src="../<?php echo $website['jquery_ui_js']; ?>"></script>

        <link rel="stylesheet" href="../resources/css/Errors/validationEngine.jquery.css" type="text/css"/>
        <link rel="stylesheet" href="../resources/css/Errors/template.css" type="text/css"/>
        <script src="../resources/js/Languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
        <script src="../resources/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

        <link rel="stylesheet" href="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/css/bootstrap-select.min.css">
        <script src="../<?php echo $website['boostrap_folder']; ?>/custom_select/dist/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>

        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });
            });

            function filterCity(d_id) {
                $("#iii").load('../filter_city.php', {d_id: d_id});
            }
        </script>
        <link href="css/menu.css" media="screen, projection" rel="stylesheet" type="text/css">
        <script>
            var timeout = 200;
            var closetimer = 0;
            var ddmenuitem = 0;

            function jsddm_open()
            {
                jsddm_canceltimer();
                jsddm_close();
                ddmenuitem = $(this).find('ul').css('visibility', 'visible');
            }

            function jsddm_close()
            {
                if (ddmenuitem)
                    ddmenuitem.css('visibility', 'hidden');
            }

            function jsddm_timer()
            {
                closetimer = window.setTimeout(jsddm_close, timeout);
            }

            function jsddm_canceltimer()
            {
                if (closetimer)
                {
                    window.clearTimeout(closetimer);
                    closetimer = null;
                }
            }

            $(document).ready(function ()
            {
                $('#jsddm > li').bind('mouseover', jsddm_open)
                $('#jsddm > li').bind('mouseout', jsddm_timer)
            });

            document.onclick = jsddm_close;
        </script>
        <script>
            $(function () {
                /* Date */
                $("#mem_dob").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '-90:+0',
                    showOn: "button",
                    buttonImage: "Images/calendar.png",
                    buttonImageOnly: true
                });
                $("#mem_dob").datepicker("option", "dateFormat", "yy-mm-dd");
            });
        </script>
    </head>
    <body class="white">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div id="container" class="popup">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['key'] == $_POST['key']) {
                            include_once 'controlers/memberControler.php';
                            echo('<br/>');
                        }
                        ?>
                        <ul id="jsddm" class="poppage">
                            <li class="act"><a href="my_account.php">Change Account</a></li>
                            <li><a href="my_password.php">Change Password</a></li>
                        </ul>
                        <?php
                        // prevent multiple posting on page reload
                        $_SESSION['key'] = date('His') . mt_rand(1000, 9999);

                        $myCon = new dbConfig();
                        $myCon->connect();
                        $query = "SELECT * FROM members WHERE mem_email='" . $_SESSION['system_logged_email'] . "' LIMIT 1";
                        $result = $myCon->query($query);
                        $count = mysqli_num_rows($result);
                        if ($count == 1) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div id="outer-box">
                                    <div id="mm">
                                        <h1>Change Account Details</h1>
                                        <form id="formID" class="allforms" method="post" action="" enctype="multipart/form-data">
                                            <input type="hidden" name="category" value="my_acc"/>
                                            <input type="hidden" name="action" value="change"/>
                                            <input type="hidden" name="user_name" value="<?php echo $_SESSION['system_logged_uname']; ?>"/>
                                            <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                            <input type="hidden" name="user_email" value="<?php echo $_SESSION['system_logged_email']; ?>" />
                                            <div id="inner-box">
                                                <fieldset>
                                                    <legend>Personal Details</legend>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>Title</label>
                                                        <select name="mem_title" class="form-control selectpicker" data-size="5">
                                                            <option value="Mr" <?php
                                                            if (isset($row['mem_title']) && $row['mem_title'] == "Mr") {
                                                                echo("selected");
                                                            }
                                                            ?>>Mr</option>
                                                            <option value="Mrs" <?php
                                                            if (isset($row['mem_title']) && $row['mem_title'] == "Mrs") {
                                                                echo("selected");
                                                            }
                                                            ?>>Mrs</option>
                                                            <option value="Miss" <?php
                                                            if (isset($row['mem_title']) && $row['mem_title'] == "Miss") {
                                                                echo("selected");
                                                            }
                                                            ?>>Miss</option>
                                                            <option value="Ms" <?php
                                                            if (isset($row['mem_title']) && $row['mem_title'] == "Ms") {
                                                                echo("selected");
                                                            }
                                                            ?>>Ms</option>
                                                            <option value="Dr" <?php
                                                            if (isset($row['mem_title']) && $row['mem_title'] == "Dr") {
                                                                echo("selected");
                                                            }
                                                            ?>>Dr</option>
                                                            <option value="Rev" <?php
                                                            if (isset($row['mem_title']) && $row['mem_title'] == "Rev") {
                                                                echo("selected");
                                                            }
                                                            ?>>Rev</option> 
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>First Name</label><input name="mem_fname" id="mem_fname" type="text" class="validate[required] form-control" maxlength="40" value="<?php echo $row['mem_fname']; ?>"></div>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>Last Name</label><input name="mem_lname" id="mem_lname" type="text" class="validate[required] form-control" maxlength="40" value="<?php echo $row['mem_lname']; ?>"></div>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>Tel No </label><input name="mem_phone" id="mem_phone" type="text" class="validate[required, custom[phone] ,minSize[10]] form-control" maxlength="10" value="<?php echo $row['mem_phone']; ?>"></div>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>Fax No</label><input name="mem_fax" id="mem_fax" type="text"  maxlength="10" value="<?php echo $row['mem_fax']; ?>" class="form-control"></div>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>Address Line1</label><input name="mem_add_line1" id="mem_add_line1" type="text" class="validate[required] form-control" maxlength="40" value="<?php echo $row['mem_add_line1']; ?>"></div>
                                                    <div class="form-group col-xs-12 col-sm-6"><label>Address line2 (optional)</label><input name="mem_add_line2" id="mem_add_line2" type="text" maxlength="40" value="<?php echo $row['mem_add_line2']; ?>" class="form-control"></div>
                                                    <!-- 
                                                     <div class="form-group col-xs-12 col-sm-6"><label>District</label>
                                                        <select name="mem_state"  class="validate[required]" onChange="filterCity(this.value)">
                                                               <option value="" selected>Please Select</option>
                                                    <?php
                                                    $result2 = $myCon->query("SELECT * FROM district ORDER BY d_name ASC");
                                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                                        ?>
                                                                                                                                                       <option value="<?php echo($row2['d_id']); ?>" <?php
                                                        if ($row['mem_state'] == $row2['d_id']) {
                                                            echo 'selected';
                                                        }
                                                        ?>><?php echo($row2['d_name']); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                           </select>
                                                    </div>
                                                     <div class="form-group col-xs-12 col-sm-6"><label>City</label>
                                                     <div id="iii">
                                                        <select name="mem_city"  class="validate[required]">
                                                               <option value="" selected>Please Select</option>
                                                    <?php
                                                    $result2 = $myCon->query("SELECT * FROM city WHERE d_id='" . $row['mem_state'] . "' ORDER BY 
									c_name ASC");
                                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                                        ?>
                                                                                                                                                       <option value="<?php echo($row2['c_id']); ?>" <?php
                                                        if ($row['mem_city'] == $row2['c_id']) {
                                                            echo 'selected';
                                                        }
                                                        ?>><?php echo($row2['c_name']); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                           </select>
                                                     </div>
                                                    </div>
                                                    -->
                                                    <div class="form-group col-xs-12 col-sm-6">
                                                        <label>Postal Code</label>
                                                        <input name="mem_pst_code" id="mem_pst_code" type="text" maxlength="10" value="<?php echo $row['mem_pst_code']; ?>" class="form-control">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-lg float-right"><span class="glyphicon glyphicon-check"></span> Change Details</button>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo('<div class="alert alert-danger" role="alert">Invalid Member ID</div>');
                        }
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>