<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Dashboard - <?php echo $GLOBALS['comp_name']; ?></title>
        <link href="Login_themes/th_01/login_theme.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../resources/js/jquery-1.9.1.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="../resources/js/jquery-migrate-1.1.1.min.js" charset="utf-8"></script>
        
        <script src="colorbox/jquery.colorbox.js"></script>
        <link rel="stylesheet" href="colorbox/colorbox.css" />
        <script type="text/javascript">
        $(document).ready(function(){
            $(".iframe").colorbox({escKey: false, overlayClose: false, iframe:true, width:"640px", height:"320px"});
        
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

		</div> <!-- end full-width -->	
	
	</div> <!-- end top-bar -->
        <form method="post" action="">
            <div id="login-box">
                <div id="users"></div><h2>Login Here</h2>
                <h3><span>S</span>ecure login for <?php echo $GLOBALS['comp_name']; ?></h3>
                <div id="line"></div>
                <?php
                // username and password sent from form
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (trim($_POST['uname']) == null || trim($_POST['pword']) == null) {
                        if (trim($_POST['uname']) == null) {
                            echo('<span id="ajaxerror"> Please enter Username</span>');
                        } else {
                            echo('<span id="ajaxerror"> Please enter Password</span>');
                        }
                    } else {
                        include_once '../Models/dbConfig.php';
                        include_once '../Models/login.php';

                        $mylogObj = new login();
                        $myCon = new dbConfig();
                        $myCon->connect();

                        $uname = $_POST['uname'];
                        $pword = $_POST['pword'];

                        // To protect MySQL injection
                        $uname = stripslashes($uname);
                        $pword = stripslashes($pword);
                        $uname = $myCon->escapeString($uname);
                        $pword = $myCon->escapeString($pword);

                        try {
                            if ($mylogObj->loginUser($uname, $pword)) {
                                unset($_POST['uname']);
                                unset($_POST['pword']);
								$loc_page='';
								if($_SESSION['system_logged_mem_type_id']==01 || $_SESSION['system_logged_mem_type_id']==02){
									$loc_page='dashboard.php?page=master_entries';
								}else{
									$loc_page='dashboard.php?page=master_entries';
								}
                                ?>
                                <script type="text/javascript">
                                    window.location.replace("<?php echo $loc_page; ?>");
                                </script>
                                <?php
                            } else {
                                echo('<span id="ajaxerror">An error occurred, please try again later</span>');
                            }
                        } catch (Exception $ex) {
                            echo('<span id="ajaxerror">' . $ex->getMessage() . '</span>');
                        }
                    }//end else
                }
                ?>
                <ul>
                    <li>
                        <label>Username :</label><input name="uname" class="form-login" title="Username" value="<?php if (isset($_POST['uname'])) {
                    echo($_POST['uname']);
                } else {
                    echo('');
                } ?>" size="28" maxlength="30" placeholder="Username/Email">
                    </li>
                    <li>
                        <label>Password :</label><input name="pword" type="password" class="form-login" title="Password" value="" size="28" maxlength="30"  placeholder="Password">
                    </li>
                    <li>
                            <!-- <span class="login-box-options"><input type="checkbox" name="1" value="1"> Remember Me
                            </span> -->
                    </li>
                    <li>
                        <input type="reset" value="" id="button" class="reset"/><input type="submit" value="" id="button"/>
                    </li>
                    <li>
                        I've <a href="cant_access.php" class="iframe">forgotten my password.</a>
                    </li>
                </ul>
                <div id="line_bot"></div>
            </div>
        </form>
        <!-- FOOTER -->
        <div id="footer">
            <p>&copy; Copyright <?php echo date('Y'); ?> <a href="../index.php"><?php echo $GLOBALS['comp_name']; ?></a>. All rights reserved.</p>
			<p><strong>Design</strong> by <a href="http://slwebcreations.com">SL Web Creations</a></p>
        </div><!-- end footer -->
    </body>
</html>