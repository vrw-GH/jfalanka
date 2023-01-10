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
        <link rel="stylesheet" href="login_themes/th_03/css/style.css">

        <link rel="stylesheet" type="text/css" href="../<?php echo $website['boostrap_folder']; ?>/css/bootstrap.css" media="all"/>

        <script type="text/javascript" src="../<?php echo $website['jquery_min_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8"></script>

        <script src="colorbox/jquery.colorbox-min.js"></script>
        <link rel="stylesheet" href="colorbox/colorbox.css" />
        <script type="text/javascript">
            $(function () {
                $(".iframe").colorbox({escKey: false, overlayClose: false, iframe: true, width: "480px", height: "340px"});
            });
        </script>

        <!-- Optimize for mobile devices -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
    </head>
    <body>
        <div class="container-fluid gray">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4 voffset-1 voffset-b-2">
                    <div class="col-xs-12 form-outer">
                        <form action="" method="post" id="login-form" class="voffset-3">
                            <?php
                            if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                                
                            } else {
                                ?>
                                <div class="alert alert-info square" role="alert"><strong>Sign in</strong> to continue to <?php echo $GLOBALS['comp_name']; ?></div>
                                <?php
                            }
                            /* username and password sent from form */
                            if (($_SERVER['REQUEST_METHOD'] == 'POST') && $_SESSION['key'] == $_POST['key']) {
                                if (trim($_POST['uname']) == null || trim($_POST['pword']) == null) {
                                    if (trim($_POST['uname']) == null) {
                                        echo('<div class="alert alert-danger square" role="alert"> Please enter Username</div><br/>');
                                    } else {
                                        echo('<div class="alert alert-danger square" role="alert"> Please enter Password</div><br/>');
                                    }
                                } else {
                                    include_once '../models/dbConfig.php';
                                    include_once '../models/login.php';

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
                                    $captcha = stripslashes($_POST['captchabox']);
                                    $captcha = $myCon->escapeString($captcha);
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
                                            echo('<div class="alert alert-danger square" role="alert">An error occurred, please try again later</div><br/>');
                                        }
                                    } catch (Exception $ex) {
                                        echo('<div class="alert alert-danger square" role="alert">' . $ex->getMessage() . '</div><br/>');
                                    }
                                }
                            }
                            /* key */
                            $_SESSION['key'] = date('His') . mt_rand(1000, 9999);
                            ?>
                            <input type="hidden" name="key" value="<?= $_SESSION['key'] ?>" />
                            <fieldset>

                                <div class="form-group">
                                    <label for="login-username">Username</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                        <input type="text" id="login-username" class="square form-control"  name="uname" autofocus placeholder="username" tabindex="1"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="login-password">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                                        <input type="password" id="login-password" class="square form-control" name="pword" placeholder="password"/>
                                    </div>
                                </div>

                                <div class="form-group col-xs-12 voffset-1"><span class="text-warning">Just making sure you're not a robot</span></div>

                                <div class="form-group col-xs-12 text-center">
                                    <?php
                                    /* show captcha HTML using Securimage::getCaptchaHtml() */
                                    require_once '../resources/securimage/securimage.php';
                                    $options = array();
                                    $options['input_name'] = 'captchabox'; /* change name of input element for form post */
                                    $options['disable_flash_fallback'] = false; /* allow flash fallback */

                                    if (!empty($_SESSION['ctform']['captcha_error'])) {
                                        /* error html to show in captcha output */
                                        $options['error_html'] = $_SESSION['ctform']['captcha_error'];
                                    }

                                    echo Securimage::getCaptchaHtml($options);
                                    ?>
                                </div>
                                <div class="form-group col-xs-12">
                                    <input type="submit" class="btn bg-primary float-right" value="LOG IN"/>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="col-xs-12 voffset-3">
                        I've <a href="password_assistance.php" class="iframe">forgotten my password</a>.
                    </div>
                    <div class="col-xs-12 voffset-1">
                        <a href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Return to my website</a>
                    </div> 
                </div>
            </div>
        </div>
        <div class="container-fluid footer">
            <div class="row">
                <div class="col-xs-12">
                    <p>Developed by <a href="http://slwebcreations.com" target="_blank">SL Web Creations</a>. &copy; <?php echo date('Y'); ?> All rights reserved. Hotline: <?php echo $GLOBALS['config_hotline']; ?>
                        <br/>
                        CMS version <?php echo $GLOBALS['config_version']; ?>
                        <br/>
                        <img src="images/slweb-logo-cms.png" width="60"/>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>