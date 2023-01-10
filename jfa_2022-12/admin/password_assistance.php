<?php
if (!isset($_SESSION)) {
    session_start();
}
$pid = 1;
if (file_exists('site_config.php')) {
    include_once('site_config.php');
} else {
    include_once('../site_config.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Password Assistance - <?php echo $_SESSION['comp_name']; ?></title>
        <meta name="robots" content="noindex,nofollow" />
        <link rel="stylesheet" type="text/css" href="../<?php echo $website['boostrap_folder']; ?>/css/bootstrap.css" media="all"/>
        <link  rel="stylesheet" type="text/css" href="css/style.css" media="all"/>

        <script type="text/javascript" src="../<?php echo $website['jquery_min_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="../<?php echo $website['jquery_migrate_lower_js']; ?>" charset="utf-8"></script>

        <script type="text/javascript" src="../<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8"></script>
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_css']; ?>" />
        <link rel="stylesheet" href="../<?php echo $website['jquery_ui_theme_css']; ?>" />
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
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 voffset-2">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        include_once '../models/emailTemplates.php';
                        $mailObj = new emailTemplates();

                        if (trim($_POST['email']) == null) {
                            echo('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please enter your email address</div>');
                        } else if ($mailObj->is_valid_email($_POST['email']) == false) {
                            echo('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Correct Image Text</div>');
                        } else if (trim($_POST['captchabox']) != $_SESSION['captcha']) {
                            echo('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Correct Image Text</div>');
                        } else {
                            include_once '../models/dbConfig.php';
                            $myCon = new dbConfig();
                            $myCon->connect();

                            $email = $_POST['email'];
                            $email = stripslashes($email);
                            $email = $myCon->escapeString($email);

                            $query = "SELECT user_email, user_pword FROM login WHERE user_email='" . $email . "' LIMIT 1";
                            $result = $myCon->query($query);
                            $count = mysqli_num_rows($result);
                            if ($count == 1) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $password = $row['user_pword'];
                                }
                                $myCon->closeCon();
                                include_once '../models/encryption.php';
                                $myEnc = new Encryption();
                                $password = $myEnc->decode($password);
                                if ($mailObj->sendPassword($password, $email)) {
                                    unset($_POST['email']);
                                    echo('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Password has been sent! Please check your e-mail inbox </div>');
                                } else {
                                    echo('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Error, Password not sent</div>');
                                }
                            } else {
                                echo('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Sorry, Email does not exist in system</div>');
                            }
                        }
                    }
                    ?>
                </div>
                <div class="col-xs-12 voffset-3">
                    <form id="formID" method="post" action="">
                        <div class="form-group">
                            <label for="emailInput">Email address</label>
                            <input name="email" type="text" id="emailInput" value="" maxlength="255" class="validate[required,custom[email]] square form-control">
                        </div>
                        <img src="../captcha/captcha.php" name="captcha" id="captcha" /><div id="reload" style="display:inline"><a href="javascript:void(0);" onClick="document.getElementById('captcha').src = '../captcha/captcha.php?' + Math.random();
                                document.getElementById('captchabox').focus();" id="change-image"><img src="../resources/images/refresh.png" alt="R" border="0" align="absmiddle" style="display:inline-block"><div id="text" style="display:inline-block"> Reload Image</div></a></div>
                        <div class="form-group">
                            <label for="captchabox">Type the Image Text</label>
                            <input name="captchabox" type="text" maxlength="10" id="captchabox" value="" class="validate[required] square form-control">
                        </div>
                        <div class="form-group text-right">
                            <input name="submit" type="submit" class="btn btn-primary" value="Get My Password">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>