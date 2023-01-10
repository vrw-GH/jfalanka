<?php
include_once 'site_config.php';
$pid = 2;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $website['title'] ?></title>
        <meta name="description" content="<?= $config['descp'] ?>" />
        <meta name="keywords" content="<?= $config['keywords'] ?>" />
        <meta name="robots" content="index,follow" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=1">
        <link rel="stylesheet" type="text/css" href="<?= $website['boostrap_folder'] ?>/css/bootstrap.css" media="all"/>
        <link rel="stylesheet" type="text/css" href="resources/css/style.css" media="all"/>

        <link rel="stylesheet" href="resources/css/Errors/validationEngine.jquery.css" type="text/css"/>
        <link rel="stylesheet" href="resources/css/Errors/template.css" type="text/css"/>

        <script type="text/javascript" src="<?= $website['jquery_min_js'] ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?= $website['jquery_migrate_js'] ?>" charset="utf-8"></script>

        <!-- Jquery UI Plugins -->
        <link rel="stylesheet" href="<?= $website['jquery_ui_css'] ?>" />
        <script type="text/javascript" src="<?= $website['jquery_ui_js'] ?>" charset="utf-8"></script>

        <script type="text/javascript" src="<?= $website['boostrap_folder'] ?>/js/bootstrap.min.js" charset="utf-8"></script>
        <script src="resources/js/Languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
        <script src="resources/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
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
                <div class="container-fluid">
                    <div class="row animatedParent">
                        <div class="col-xs-12 col-pdn-both-0">
                            <img src="resources/images/about-us.jpg" class="img-responsive animated fadeInDownShort" alt="about us"/>
                        </div>
                    </div>
                </div>
                <div class="others blue">
                    <!-- container-fluid END -->
                    <div class="container white voffset-2 voffset-b-3">
                        <div class="row voffset-1">
                            <div class="col-xs-12">
                                <div class="container-fluid common-block about-us voffset-b-3">
                                    <div class="row voffset-2 voffset-b-2 animatedParent">
                                        <div id="about-welcome-anchor" class="col-xs-12 animated fadeInRightShort">
                                            <h1>WelCome to Blue Line Lands</h1>
                                            <div class="col-xs-12 voffset-2 visible-xs"></div>
                                            <p>
                                                Welcome to Blue Line Lands (Pvt)Ltd (reg. PV106356), Srilanka`s one of the most emerging property development and sale business. We’re dedicated to giving you the very best of deals for your life-lone investment, business asset, or most values personal investment of your whole life. With  focus on reliability, customer service and novelty in project ideas, the company is focusing on giving the best suited personal/business solution for your property requirement.
                                                <br/>
                                                Founded in 2013, Blue Line Lands (Pvt)Ltd  has come a long way from its beginning. The company is mainly focused on four sectors, namely ,"Blue Line Lands", "Villa Homes","Ratagiya atto", "Live in Tropic". While "Blue Line Lands" projects focuses on normal property development and reselling, "Villa Homes" is targeted for people looking for customers who are looking for a more fulfilling living environment, with complete infrastructure and amenities. "Ratagiya atto" is for the srilankans who are working in other countries and expiates who are thinking on spending there hard earned money for a peaceful life in a modern neighborhood or who are thinking of making a reliable investment in the country. "Villa Homes"is a type of project targeting non srilankan citizens giving them the chance to acquire property in this paradise island through a reliable property developer with correct legal basis giving them the sense of security for there investment.  
                                                <br/>
                                                We hope you would  enjoy our services offering to you. If you have any questions or comments, please don’t hesitate to contact us.
                                                <br/><br/>
                                                Sincerely,<br/>
                                                <strong>
                                                    Thilina Pussewela<br class="no-margin"/>
                                                    Managing Director<br class="no-margin"/>
                                                    Blue Line Lands (Pvt)Ltd
                                                </strong>
                                            </p>
                                        </div>
                                        <!-- column END -->
                                    </div>
                                </div>
                                <!-- container-fluid END -->
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