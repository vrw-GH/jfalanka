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

        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });
            });
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
                                        <h1>Change Password</h1>
                                        <form id="formID" class="allforms" method="post" action="" enctype="multipart/form-data">
                                            <input type="hidden" name="category" value="my_acc"/>
                                            <input type="hidden" name="action" value="password"/>
                                            <input type="hidden" name="user_name" value="<?php echo $_SESSION['system_logged_uname']; ?>"/>
                                            <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" />
                                            <input type="hidden" name="user_email" value="<?php echo $_SESSION['system_logged_email']; ?>" />
                                            <div id="inner-box">
                                                <fieldset>
                                                    <legend>&nbsp;</legend>
                                                    <div class="form-group col-xs-12 col-sm-4"><label>Old Password</label><input name="old_user_pword" type="password" class="validate[required,minSize[6],maxSize[20]] form-control" id="old_user_pword" maxlength="30" value=""></div>
                                                    <div class="form-group col-xs-12 col-sm-4"><label>New Password</label><input name="user_pword" type="password" class="validate[required,minSize[6],maxSize[20]] form-control" id="user_pword" maxlength="30" value=""></div>
                                                    <div class="form-group col-xs-12 col-sm-4"><label>Re type New Password</label><input name="user_pword2" type="password" class="validate[required,minSize[6],maxSize[20],equals[user_pword]] form-control" id="user_pword2" maxlength="30" value=""></div>
                                                    <div class="form-group col-xs-12 text-right voffset-2">
                                                        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Change Password</button> 
                                                    </div>
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