<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../models/dbConfig.php';
$myCon = new dbConfig();
$myCon->connect();

$_SESSION['key'] = date('His') . mt_rand(1000, 9999);
?>
<!DOCTYPE html>
<html>
    <head>
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
        <div id="outer-box">
            <div id="mm">
                <h1>Update Contact Details</h1>
                <?php
                $queryupdate = "SELECT * FROM company_info LIMIT 1";
                $resultupdate = $myCon->query($queryupdate);
                while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
                    ?>
                    <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                        <input type="hidden" name="category" value="company_info">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">
                        <input type="hidden" name="comp_prv_name" value="<?= $rowupdate['comp_name'] ?>">
                        <h5 class="col-xs-12 text-warning voffset-2">General Information</h5>
                        <div class="form-group col-xs-12">
                            <label><span class="text-danger">*</span>Company Name :-</label>
                            <input name="comp_name" type="text" id="comp_name" class="validate[required] form-control" maxlength="70" value="<?php
                            if (isset($_POST['comp_name'])) {
                                echo $_POST['comp_name'];
                            } else {
                                echo $rowupdate['comp_name'];
                            }
                            ?>" placeholder="Company Name">
                        </div>
                        <div class="form-group col-xs-12">
                            <label><span class="text-danger">*</span>Web Site Title :-</label>
                            <input name="comp_web_title" type="text" id="comp_web_title" class="validate[required] form-control" maxlength="60" value="<?php
                            if (isset($_POST['comp_web_title'])) {
                                echo $_POST['comp_web_title'];
                            } else {
                                echo $rowupdate['comp_web_title'];
                            }
                            ?>" placeholder="Website Title">
                        </div>
                        <div class="form-group col-xs-12">
                            <label><span class="text-danger">*</span>Company Address :-</label>
                            <textarea name="comp_address" class="validate[required] form-control"><?php
                                if (isset($_POST['comp_address'])) {
                                    echo $_POST['comp_address'];
                                } else {
                                    echo $rowupdate['comp_address'];
                                }
                                ?></textarea>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label><span class="text-danger">*</span>Company Hotline (One Number):-</label>
                            <input name="comp_hotline" type="text" id="comp_name" class="validate[required, custom[phone]] form-control" maxlength="40" value="<?php
                            if (isset($_POST['comp_hotline'])) {
                                echo $_POST['comp_hotline'];
                            } else {
                                echo $rowupdate['comp_hotline'];
                            }
                            ?>" placeholder="Company Hotline">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Company Phone/s :-</label>
                            <input name="comp_phone" type="text" id="comp_name" class="form-control" maxlength="150" value="<?php
                            if (isset($_POST['comp_phone'])) {
                                echo $_POST['comp_phone'];
                            } else {
                                echo $rowupdate['comp_phone'];
                            }
                            ?>" placeholder="Company Email">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Company Fax/s :-</label>
                            <input name="comp_fax" type="text" id="comp_name" class="form-control" maxlength="100" value="<?php
                            if (isset($_POST['comp_fax'])) {
                                echo $_POST['comp_fax'];
                            } else {
                                echo $rowupdate['comp_fax'];
                            }
                            ?>" placeholder="Company Fax">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label><span class="text-danger">*</span>Company Email (One Number) :-</label>
                            <input name="comp_email" type="text" id="comp_name" class="validate[required, custom[email]] form-control" maxlength="80" value="<?php
                            if (isset($_POST['comp_email'])) {
                                echo $_POST['comp_email'];
                            } else {
                                echo $rowupdate['comp_email'];
                            }
                            ?>" placeholder="Company Email">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Company Email Address 2 (One Number) :-</label>
                            <input name="comp_email2" type="text" id="comp_name" class="validate[custom[email]] form-control" maxlength="80" value="<?php
                            if (isset($_POST['comp_email2'])) {
                                echo $_POST['comp_email2'];
                            } else {
                                echo $rowupdate['comp_email2'];
                            }
                            ?>" placeholder="Company Email">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label><span class="text-danger">*</span>Company Main Domain :-</label>
                            <input name="comp_domain" type="text" id="comp_name" class="validate[required] form-control" maxlength="80" value="<?php
                            if (isset($_POST['comp_domain'])) {
                                echo $_POST['comp_domain'];
                            } else {
                                echo $rowupdate['comp_domain'];
                            }
                            ?>" placeholder="Company Domain">
                            <p class="help-block">Only domain name (without http:// or www.)</p>
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Google Map URL :-</label>
                            <input name="comp_google_map" type="text" id="comp_name" class="form-control" value="<?php
                            if (isset($_POST['comp_google_map'])) {
                                echo $_POST['comp_google_map'];
                            } else {
                                echo $rowupdate['comp_google_map'];
                            }
                            ?>" placeholder="Google Map URL">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Google Map Position :-</label>
                            <select name="comp_google_map_size" class="validate[required] form-control" >
                                <option value="small" <?php if ($rowupdate['comp_google_map_size'] == 'small') {
                                   echo 'selected';
                               }
                               ?>>Small (Side on the Contact Us page)</option>
                                <option value="large-top" <?php if ($rowupdate['comp_google_map_size'] == 'large-top') {
                                   echo 'selected';
                               }
                               ?>>Large (Top on the Contact Us page)</option>
                                <option value="large-bottom" <?php if ($rowupdate['comp_google_map_size'] == 'large-bottom') {
                                   echo 'selected';
                               }
                               ?>>Large (Bottom on the Contact Us page)</option>
                            </select>
                        </div>
                        <h5 class="col-xs-12 text-warning voffset-2">Social Media Information</h5>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Skype:-</label>
                            <input name="comp_skype" type="text" class="form-control" maxlength="100" value="<?php
                               if (isset($_POST['comp_skype'])) {
                                   echo $_POST['comp_skype'];
                               } else {
                                   echo $rowupdate['comp_skype'];
                               }
                            ?>" placeholder="Skype Name">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Facebook :-</label>
                            <input name="comp_fb" type="text" class="validate[custom[url]] form-control" maxlength="150" value="<?php
                        if (isset($_POST['comp_fb'])) {
                            echo $_POST['comp_fb'];
                        } else {
                            echo $rowupdate['comp_fb'];
                        }
                            ?>" placeholder="Facebook URL">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Twitter :-</label>
                            <input name="comp_tw" type="text" class="validate[custom[url]] form-control" maxlength="150" value="<?php
                        if (isset($_POST['comp_tw'])) {
                            echo $_POST['comp_tw'];
                        } else {
                            echo $rowupdate['comp_tw'];
                        }
                            ?>" placeholder="Twitter URL">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>Google Plus :-</label>
                            <input name="comp_gplus" type="text" class="validate[custom[url]] form-control" maxlength="150" value="<?php
                        if (isset($_POST['comp_gplus'])) {
                            echo $_POST['comp_gplus'];
                        } else {
                            echo $rowupdate['comp_gplus'];
                        }
                            ?>" placeholder="Google Plus URL">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label>You-Tube :-</label>
                            <input name="comp_yt" type="text" class="validate[custom[url]] form-control" maxlength="150" value="<?php
                        if (isset($_POST['comp_yt'])) {
                            echo $_POST['comp_yt'];
                        } else {
                            echo $rowupdate['comp_yt'];
                        }
                        ?>" placeholder="You-Tube URL">
                        </div>
                        <div class="form-group col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update Contact</button>
                        </div>
                        <!-- forms END -->
                    </form>
<?php } ?>
            </div>
        </div>
    </body>
</html>