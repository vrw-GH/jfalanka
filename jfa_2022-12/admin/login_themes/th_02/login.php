<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dashboard - <?php echo $GLOBALS['comp_name']; ?></title>
        <link rel="stylesheet" href="Login_themes/th_02/css/style.css">

        <link rel="stylesheet" type="text/css" href="../<?= $website['boostrap_folder'] ?>/css/bootstrap.css" media="all"/>

        <script type="text/javascript" src="../<?= $website['jquery_min_js'] ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?= $website['jquery_migrate_js'] ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?= $website['boostrap_folder'] ?>/js/bootstrap.min.js" charset="utf-8"></script>

        <script src="colorbox/jquery.colorbox.js"></script>
        <link rel="stylesheet" href="colorbox/colorbox.css" />
        <script type="text/javascript">
            $(document).ready(function () {
                $(".iframe").colorbox({escKey: false, overlayClose: false, iframe: true, width: "640px", height: "320px"});
            });
        </script>

        <!-- Optimize for mobile devices -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
    </head>
    <body>
        <!-- TOP BAR -->
        <div id="top-bar">
            <div class="page-full-width">
                <a href="../index.php" class="round button dark ic-left-arrow image-left ">Return to website</a>
            </div> 
            <!-- end full-width -->	
        </div> 
        <!-- end top-bar -->
        <!-- MAIN CONTENT -->
        <div id="content">
            <form action="" method="POST" id="login-form">
                <br/><div class="information-box round">Sign in to continue to <?php echo $GLOBALS['comp_name']; ?></div><br/>
                <?php
                /* username and password sent from form */
                if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                    if (trim($_POST['uname']) == null || trim($_POST['pword']) == null) {
                        if (trim($_POST['uname']) == null) {
                            echo('<br/><div class="error-box round"> Please enter Username</div><br/>');
                        } else {
                            echo('<br/><div class="error-box round"> Please enter Password</div><br/>');
                        }
                    } else {
                        include_once '../Models/dbConfig.php';
                        include_once '../Models/login.php';

                        $mylogObj = new login();
                        $myCon = new dbConfig();
                        $myCon->connect();

                        $uname = $_POST['uname'];
                        $pword = $_POST['pword'];

                        /* To protect MySQL injection */
                        $uname = stripslashes($uname);
                        $pword = stripslashes($pword);
                        $uname = $myCon->escapeString($uname);
                        $pword = $myCon->escapeString($pword);

                        $captcha_true = true;
                        $captcha = $myCon->escapeString($_POST['captchabox']);
                        try {
                            if ($mylogObj->loginUser($uname, $pword, $captcha_true, $captcha)) {
                                unset($_POST['uname']);
                                unset($_POST['pword']);
                                $loc_page = '';
                                if ($_SESSION['system_logged_mem_type_id'] == 01 || $_SESSION['system_logged_mem_type_id'] == 02) {
                                    $loc_page = 'dashboard.php?page=master_entries';
                                } else {
                                    $loc_page = 'dashboard.php?page=master_entries';
                                }
                                ?>
                                <script type="text/javascript">
                                    window.location.replace("<?php echo $loc_page; ?>");
                                </script>
                                <?php
                            } else {
                                echo('<br/><div class="error-box round">An error occurred, please try again later</div><br/>');
                            }
                        } catch (Exception $ex) {
                            echo('<br/><div class="error-box round">' . $ex->getMessage() . '</div><br/>');
                        }
                    }
                }
                /* key */
                $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                ?>
                <input type="hidden" name="key" value="<?= $_SESSION['key'] ?>" />
                <fieldset>
                    <p>
                        <label for="login-username">username</label>
                        <input type="text" id="login-username" class="round full-width-input"  name="uname" autofocus />
                    </p>
                    <p>
                        <label for="login-password">password</label>
                        <input type="password" id="login-password" class="round full-width-input" name="pword"/>
                    </p>
                    <div class="form-group col-xs-12 voffset-1"><p class="text-warning">Just making sure you're not a robot</p></div>
                    <div class="form-group col-xs-12 col-pdn-both-0">
                        <div class="form-group col-xs-12">
                            <img src="../captcha/captcha.php" name="captcha" id="captcha" class="float-left"/>
                            <div id="reload" class="float-left offset-l-1">
                                <div id="text" class="float-left text-muted">can't read? reload image <a href="javascript:void(0);" onClick="document.getElementById('captcha').src = '../captcha/captcha.php?' + Math.random();
                                        document.getElementById('captchabox').focus();" id="change-image" class="float-left"><h4 class="glyphicon glyphicon-refresh float-left"></h4></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Type the Image Text :</label><input name="captchabox" type="text" size="20" maxlength="10" id="captchabox" value="" class="round full-width-input">
                        </div>
                    </div>
                    <p>I've <a href="password_assistance.php" class="iframe">forgotten my password</a>.</p>
                    <input type="submit" class="button round blue image-right ic-right-arrow" value="LOG IN">
                </fieldset>
            </form>
        </div> <!-- end content -->

        <!-- FOOTER -->
        <div id="footer">
            <p>&copy; Copyright <?php echo date('Y'); ?> <a href="../index.php"><?php echo $GLOBALS['comp_name']; ?></a>. All rights reserved.</p>
            <p><!-- <strong>Design</strong> by <a href="http://slwebcreations.com">SL Web Creations</a> --></p>
        </div> <!-- end footer -->
    </body>
</html>