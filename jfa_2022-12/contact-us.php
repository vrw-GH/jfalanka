<?php
include_once 'site_config.php';
$pid = 6;
$canonical_url = WEB_HOST . 'contact-us';
?>
<!DOCTYPE html>
<html lang="en">
    <head<?php
    if (SITE_SEO != 'basic') {
        echo ' ' . OG_PRIFIX;
    }
    ?>>
        <meta charset="UTF-8">
        <title><?php echo $config['seo']['seo_title']; ?></title>
        <meta name="description" content="<?php echo $config['seo']['seo_dscp'] ?>">
        <meta name="keywords" content="<?php echo $config['seo']['seo_keywords'] ?>">
        <meta name="robots" content="index,follow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=1">
        <?php
        /* enable google analytics */
        $seo_social = true;
        $seo_google = true;
        include_once './header_css_js.php';
        ?>
        <script>
            $(function () {
                $("#formID").validationEngine();
                $("#formID").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                    console.log(errorFound)
                });

                $("#contact-me").click(function () {
                    $('html, body').animate({
                        scrollTop: $(".contact-form").offset().top
                    }, 2000);
                });
            });
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-pdn-both-0">
                            <?php include_once './header.php'; ?>
                        </div>
                    </div>
                </div>
                <!-- END of container-fluid -->
                <?php if ($website['google_map_size'] == 'large-top') { ?>
                    <div class="container-fluid">
                        <div class="row voffset-2 voffset-b-2">
                            <div class="col-xs-12 voffset-3 visible-xs"></div>
                            <div class="col-xs-12">
                                <div id="map">
                                    <iframe src="<?php echo $website['google_map']; ?>" width="100%" height="350" frameborder="0" style="border:0; padding:0"></iframe>
                                </div>
                            </div>
                            <div class="col-xs-12 voffset-3 visible-xs"></div>
                        </div>
                    </div>
                <?php } ?>
                <div class="others">
                    <div class="container-fluid">
                        <div class="row voffset-b-2">
                            <div class="contact-about col-xs-12">
                                <h1>We'd Love to hear from You</h1>
                                <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                                <h3 class="voffset-3 col-xs-12 col-sm-6 col-lg-4"><span class="glyphicons glyphicons-iphone-shake"></span> <?php echo $website['hotline']; ?></h3>
                                <h3 class="voffset-3 voffset-b-2 col-xs-12 col-sm-6 col-lg-4"><span class="glyphicons glyphicons-envelope"></span> <a href="mailto:<?php echo $website["email"]; ?>"><?php echo $website["email"]; ?></a>
                                    <?php
                                    if (isset($website["email2"]) && $website["email2"] != null) {
                                        echo ', <a href="mailto:' . $website["email2"] . '">' . $website["email2"] . '</a>';
                                    }
                                    ?>
                                </h3>
                                <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                                <div class="voffset-1 col-xs-12 col-lg-4 media-64 theme-1 reverse">
                                    <?php if ($website["fb"] != null) { ?>
                                        <a href="<?php echo $website["fb"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Like us on Facebook"><div class="media-icn top fb"></div></a>
                                    <?php } if ($website["tw"] != null) { ?>
                                        <a href="<?php echo $website["tw"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Follow us on Twitter"><div class="media-icn top tw"></div></a>
                                    <?php } if ($website["gplus"] != null) { ?>
                                        <a href="<?php echo $website["gplus"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Follow on Google Plus"><div class="media-icn top gp"></div></a>
                                    <?php } if ($website["yt"] != null) { ?>
                                        <a href="<?php echo $website["yt"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Watch us on YouTube"><div class="media-icn top ut"></div></a>
                                    <?php } ?>
                                </div>
                                <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                                <h4 class="voffset-2 col-xs-12">Questions about our products?</h4>
                                <div class="col-xs-12 voffset-b-3">
                                    <button id="contact-me" class="btn btn-default btn-lg square voffset-2">Contact Our Sales Team</button>
                                </div>
                            </div>
                            <div class="col-xs-12 voffset-3 visible-xs"></div>
                        </div>
                    </div>
                    <div class="container white voffset-2 voffset-b-3">
                        <div class="row voffset-1">
                            <div class="col-xs-12">
                                <div class="container-fluid">
                                    <div class="row voffset-2 voffset-b-2" id="contact-us">
                                        <div class="col-xs-12 col-sm-8 contact-form col-xxs-pdn-both-0">
                                            <h1 class="voffset-b-3">Drop us a line</h1>
                                            <?php include_once('contact_mail.php'); ?>
                                        </div>
                                        <div class="col-xs-12 voffset-4 voffset-b-4 visible-xs"><hr class="style-four"/></div>
                                        <div class="contact-info col-xs-12 col-sm-4">
                                            <h1 class="voffset-b-2">Contact</h1>
                                            <ul class="listnone">
                                                <li class="h1"><?php echo $website['title']; ?></li>
                                                <li class="address">
                                                    <label><span class="glyphicon glyphicon-map-marker"></span>Address :</label>
                                                    <span><strong><?php echo nl2br($website['address']); ?></strong></span>
                                                </li>
                                                <li><label><span class="glyphicon glyphicon-phone"></span>Hotline :</label><span><?php echo $website['hotline']; ?></span></li>
                                                <?php if (isset($website['phone']) && $website['phone'] != null) { ?>
                                                    <li><label><span class="glyphicon glyphicon-phone-alt"></span>Phone :</label><span><?php echo $website['phone']; ?></span></li>
                                                <?php } ?>
                                                <li><label><span class="glyphicon glyphicon-envelope"></span>E-mail :</label>
                                                    <span>
                                                        <a href="mailto:<?php echo $website["email"]; ?>"><?php echo $website["email"]; ?></a>
                                                        <?php
                                                        if (isset($website["email2"]) && $website["email2"] != null) {
                                                            echo ', <a href="mailto:' . $website["email2"] . '">' . $website["email2"] . '</a>';
                                                        }
                                                        ?>
                                                    </span>
                                                </li>
                                                <?php if (isset($website['fax']) && $website['fax'] != null) { ?>
                                                    <li><label><span class="glyphicons glyphicons-fax"></span>Fax :</label><span><?php echo $website['fax']; ?></span></li>
                                                <?php } ?>
                                                <?php if (isset($website['skype']) && $website['skype'] != null) { ?>
                                                    <li><label><span class="glyphicons glyphicons-user-conversation"></span>Skype:</label>
                                                        <span>
                                                            <script type="text/javascript" src="http://www.skypeassets.com/i/scom/js/skype-uri.js"></script>
                                                            <div id="SkypeButton_Call" class="skypebutton">
                                                                <script>
            Skype.ui({
                "name": "dropdown",
                "element": "SkypeButton_Call",
                "participants": ["<?php echo $website["skype"]; ?>"],
                "imageSize": 32
            });
                                                                </script>
                                                            </div>
                                                        </span>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <label><span class="glyphicons glyphicons-group"></span>Social Medias:</label>
                                                    <span class="media-32 theme-4">
                                                        <?php if ($website["fb"] != null) { ?>
                                                            <a href="<?php echo $website["fb"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Like us on Facebook"><div class="media-icn top fb"></div></a>
                                                        <?php } if ($website["tw"] != null) { ?>
                                                            <a href="<?php echo $website["tw"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Follow us on Twitter"><div class="media-icn top tw"></div></a>
                                                        <?php } if ($website["gplus"] != null) { ?>
                                                            <a href="<?php echo $website["gplus"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Follow on Google Plus"><div class="media-icn top gp"></div></a>
                                                        <?php } if ($website["yt"] != null) { ?>
                                                            <a href="<?php echo $website["yt"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Watch us on YouTube"><div class="media-icn top ut"></div></a>
                                                        <?php } ?>
                                                    </span>
                                                </li>

                                            </ul>
                                            <?php if ($website['google_map_size'] == 'small') { ?>
                                                <div id="map" class="voffset-6 voffset-b-3">
                                                    <iframe src="<?php echo $website['google_map']; ?>" width="100%" height="350" frameborder="0" style="border:0; padding:0"></iframe>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($website['google_map_size'] == 'large-bottom') { ?>
                                            <div class="col-xs-12 voffset-4 voffset-b-3">
                                                <div id="map">
                                                    <iframe src="<?php echo $website['google_map']; ?>" width="100%" height="350" frameborder="0" style="border:0; padding:0"></iframe>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END of container -->
                </div>
                <!-- others END -->
                <?php include_once './footer.php'; ?>
            </div>
        </div>
    </body>
</html>