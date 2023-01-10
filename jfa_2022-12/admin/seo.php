<?php
if (!isset($_SESSION)) {
    session_start();
}
/* security file */
include_once './security.php';

include_once '../models/dbConfig.php';
include_once '../models/encryption.php';
$myCon = new dbConfig();
$myCon->connect();
$encObj = new encryption();

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
                <h1>Web Site SEO</h1>
                <?php
                $queryupdate = "SELECT * FROM seo LIMIT 1";
                $resultupdate = $myCon->query($queryupdate);
                while ($rowupdate = mysqli_fetch_assoc($resultupdate)) {
                    ?>
                    <form id="formID" method="post" action="" enctype="multipart/form-data" class="">
                        <input type="hidden" name="category" value="seo">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="seo_id" value="<?php echo $rowupdate['seo_id']; ?>" >
                        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>" >
                        <div class="form-group col-xs-12">
                            <label><span class="text-danger">*</span>SEO Page Title :-</label>
                            <input name="seo_title" id="seo_title" type="text" class="validate[required] form-control" maxlength="65" value="<?php
                            if (isset($_POST['seo_title'])) {
                                echo $_POST['seo_title'];
                                $len_minus = 65 - strlen($_POST['seo_title']);
                            } else {
                                echo $rowupdate['seo_title'];
                                $len_minus = 65 - strlen($rowupdate['seo_title']);
                            }
                            ?>" placeholder="Web Site SEO Title">
                            <p class="help-block small"><span id="seo_title-h" class="text-danger"><?php echo $len_minus; ?></span> characters remaining. It is recommended to have between 55 and 65 characters. The title of the page has to contain the main keywords and the name of your organization/brand. Describe the page's content instead of keyword stuffing. </p>
                            <script>
                                var seo_title_length = 65;
                                $('#seo_title').keyup(function () {
                                    var length = $(this).val().length;
                                    var length = seo_title_length - length;
                                    $('#seo_title-h').text(length);
                                });
                            </script>
                        </div>
                        <div class="form-group col-xs-12">
                            <label><span class="text-danger">*</span>SEO Meta Description :-</label>
                            <input name="seo_dscp" id="seo_dscp" type="text" class="validate[required] form-control" maxlength="155" value="<?php
                            if (isset($_POST['seo_dscp'])) {
                                echo $_POST['seo_dscp'];
                                $len_minus = 155 - strlen($_POST['seo_dscp']);
                            } else {
                                echo $rowupdate['seo_dscp'];
                                $len_minus = 155 - strlen($rowupdate['seo_dscp']);
                            }
                            ?>" placeholder="Web Site SEO Description">
                            <p class="help-block small"><span id="seo_dscp-h" class="text-danger"><?php echo $len_minus; ?></span> characters remaining. It is recommended to have between 130 and 155 characters. This meta tag should describe the page without being a straight forward list of keywords and has to contain the most important keywords of the page.</p>
                            <script>
                                var seo_dscp_length = 155;
                                $('#seo_dscp').keyup(function () {
                                    var length = $(this).val().length;
                                    var length = seo_dscp_length - length;
                                    $('#seo_dscp-h').text(length);
                                });
                            </script>
                        </div>
                        <div class="form-group col-xs-12">
                            <label>SEO Meta Keywords :-</label>
                            <input name="seo_keywords" id="seo_keywords" type="text" class="form-control" maxlength="300" value="<?php
                            if (isset($_POST['seo_keywords'])) {
                                echo $_POST['seo_keywords'];
                                $len_minus = 300 - strlen($_POST['seo_keywords']);
                            } else {
                                echo $rowupdate['seo_keywords'];
                                $len_minus = 300 - strlen($rowupdate['seo_keywords']);
                            }
                            ?>" placeholder="Web Site SEO Keywords">
                            <p class="help-block small"><span id="seo_keywords-h" class="text-danger"><?php echo $len_minus; ?></span> characters remaining. Add maximum 10-20 keywords and separate them from each other by a comma an a space (Ex:/ wood, furniture, garden). However Google does not use the keywords meta tag in web ranking.</p>
                            <script>
                                var seo_keywords_length = 300;
                                $('#seo_keywords').keyup(function () {
                                    var length = $(this).val().length;
                                    var length = seo_keywords_length - length;
                                    $('#seo_keywords-h').text(length);
                                });
                            </script>
                        </div>

                        <?php if ($encObj->decode(SITE_SEO_KEY) == 'ultimate' || $encObj->decode(SITE_SEO_KEY) == 'enterprise') { ?>
                            <h5 class="col-xs-12 text-warning voffset-3 voffset-b-2">Facebook Sharing (Open Graph) <a href="https://developers.facebook.com/docs/sharing/opengraph/object-properties" target="_blank">Read More <span class="glyphicon glyphicon-info-sign"></span></a></h5>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Facebook Page ID :-</label>
                                <input name="fb_id" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['fb_id'])) {
                                    echo $_POST['fb_id'];
                                } else {
                                    echo $rowupdate['fb_id'];
                                }
                                ?>" placeholder="Facebook Page ID">
                                <p class="help-block small"><span class="text-info">Facebook page id Ex:/ 262458387237855</span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>OG Type :- <a href="https://developers.facebook.com/docs/reference/opengraph#object-type" target="_blank">Read More <span class="glyphicon glyphicon-info-sign"></span></a></label>
                                <input name="og_type" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['og_type'])) {
                                    echo $_POST['og_type'];
                                } else {
                                    echo $rowupdate['og_type'];
                                }
                                ?>" placeholder="OG Type">
                                <p class="help-block small"><span class="text-info">Ex:/ website</span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>OG Image :-</label>
                                <input name="og_img" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['og_img'])) {
                                    echo $_POST['og_img'];
                                } else {
                                    echo $rowupdate['og_img'];
                                }
                                ?>" placeholder="OG Image">
                                <p class="help-block small"><span class="text-info">Sharing Image URL. Ex:/ http://yourdomainname.com/imagename.jpg</span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>OG Site Name :-</label>
                                <input name="og_site_name" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['og_site_name'])) {
                                    echo $_POST['og_site_name'];
                                } else {
                                    echo $rowupdate['og_site_name'];
                                }
                                ?>" placeholder="OG Site Name">
                                <p class="help-block small"><span class="text-info">Ex:/ My Website Name</span>
                            </div>

                            <h5 class="col-xs-12 text-warning voffset-3 voffset-b-2">Twitter Sharing (Twitter Card) <a href="https://dev.twitter.com/cards/overview" target="_blank">Read More <span class="glyphicon glyphicon-info-sign"></span></a></h5>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Twitter Username :-</label>
                                <input name="tw_site" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['tw_site'])) {
                                    echo $_POST['tw_site'];
                                } else {
                                    echo $rowupdate['tw_site'];
                                }
                                ?>" placeholder="Twitter Username">
                                <p class="help-block small"><span class="text-info">The Twitter username used by the given site including the '@'. Ex:/ @myusername</span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Twitter Creator :-</label>
                                <input name="tw_creator" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['tw_creator'])) {
                                    echo $_POST['tw_creator'];
                                } else {
                                    echo $rowupdate['tw_creator'];
                                }
                                ?>" placeholder="Twitter Creator">
                                <p class="help-block small"><span class="text-info">The Twitter username or username of the individual author of the content. Ex:/ @myusername</span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Twitter Image :-</label>
                                <input name="tw_img" type="text" class="form-control" maxlength="100" value="<?php
                                if (isset($_POST['tw_img'])) {
                                    echo $_POST['tw_img'];
                                } else {
                                    echo $rowupdate['tw_img'];
                                }
                                ?>" placeholder="Twitter Image">
                                <p class="help-block small"><span class="text-info">Sharing Image URL. Ex:/ http://yourdomainname.com/imagename.jpg</span>
                            </div>

                            <h5 class="col-xs-12 text-warning voffset-3 voffset-b-2">Facebook Sharing & Twitter Sharing Description</h5>
                            <div class="form-group col-xs-12">
                                <label>Facebook & Twitter Description :-</label>
                                <input name="og_tw_dscp" id="og_tw_dscp" type="text" class="form-control" maxlength="200" value="<?php
                                if (isset($_POST['og_tw_dscp'])) {
                                    echo $_POST['og_tw_dscp'];
                                    $len_minus = 200 - strlen($_POST['og_tw_dscp']);
                                } else {
                                    echo $rowupdate['og_tw_dscp'];
                                    $len_minus = 200 - strlen($rowupdate['og_tw_dscp']);
                                }
                                ?>" placeholder="Description">
                                <p class="help-block small"><span id="og_tw_dscp-h" class="text-danger"><?php echo $len_minus; ?></span> characters remaining. You can use same SEO Meta Description here</p>
                                <script>
                                    var og_tw_dscp_length = 200;
                                    $('#og_tw_dscp').keyup(function () {
                                        var length = $(this).val().length;
                                        var length = og_tw_dscp_length - length;
                                        $('#og_tw_dscp-h').text(length);
                                    });
                                </script>
                            </div>

                            <h5 class="col-xs-12 text-warning voffset-3 voffset-b-2">Google+ Publisher <a href="https://support.google.com/business/answer/4569085?hl=en" target="_blank">Read More <span class="glyphicon glyphicon-info-sign"></span></a></h5>
                            <div class="form-group col-xs-12">
                                <label>Google Publisher URL :-</label>
                                <input name="google_publisher" type="text" class="form-control" maxlength="200" value="<?php
                                if (isset($_POST['google_publisher'])) {
                                    echo $_POST['google_publisher'];
                                } else {
                                    echo $rowupdate['google_publisher'];
                                }
                                ?>" placeholder="Google Publisher">
                            </div>

                            <h5 class="col-xs-12 text-warning voffset-3 voffset-b-2">Google Analytics <a href="https://www.google.com/analytics/standard/" target="_blank">Read More <span class="glyphicon glyphicon-info-sign"></span></a></h5>
                            <div class="form-group col-xs-12">
                                <label>Google Analytics Code :-</label>
                                <textarea name="google_analytics" class="form-control" placeholder="Code"><?php
                                    if (isset($_POST['google_analytics'])) {
                                        echo $_POST['google_analytics'];
                                    } else {
                                        echo $rowupdate['google_analytics'];
                                    }
                                    ?></textarea>
                            </div>
                        <?php } ?>
                        <div class="form-group col-xs-12 text-right voffset-2">
                            <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-check"></span> Update SEO</button>
                        </div>
                        <!-- forms END -->
                    </form>
                <?php } ?>
            </div>
        </div>
    </body>
</html>