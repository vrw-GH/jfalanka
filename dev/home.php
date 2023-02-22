<?php

function gzip_output()
{
   $HTTP_ACCEPT = $_SERVER['HTTP_ACCEPT_ENCODING'];
   if (headers_sent()) {
      $encoding = false;
   } elseif (strpos($HTTP_ACCEPT, 'x-gzip') !== false) {
      $encoding = 'x-gzip';
   } elseif (strpos($HTTP_ACCEPT, 'gzip') !== false) {
      $encoding = 'gzip';
   } else {
      $encoding = false;
   }
   if ($encoding) {
      $contents = ob_get_contents();
      ob_end_clean();
      header('Content-Encoding: ' . $encoding);
      print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
      $size = strlen($contents);
      $contents = gzcompress($contents, 9);
      $contents = substr($contents, 0, $size);
      echo $contents;
      exit();
   } else {
      ob_end_flush();
      exit();
   }
}

/* At the beginning of each page call these two functions */
ob_start();
ob_implicit_flush(0);
/* Then do everything you want to do on the page */

include_once 'site_config.php';

$systemdate = date('Y-m-d');
$pid = 1;
$canonical_url = WEB_HOST;

?>

<!DOCTYPE html>
<html lang="en">

<head <?php
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
   <!-- <link rel="icon" href="./resources/images/favicon.ico" type="image/x-icon"> -->
   <?php
   /* enable google analytics */
   $seo_social = true;
   $seo_google = true;
   include_once './header_css_js.php';
   ?>
</head>

<body>
   <div id="wrapper">
      <div id="container">
         <?php include_once './header.php'; ?>
         <div class="container-fluid bg1" style="margin-top:5rem;">
            <!-- <div class="col-xs-12" style="background-color: #ffffff99;"> -->
            <div class="col-xs-12" style="background-image: linear-gradient(to bottom, white,white,#ffffff99);">
               <div class="row">
                  <div class="container  voffset-2">
                     <div class="row animatedParent">
                        <div class="col-xs-12 voffset-2 visible-xs">&nbsp;</div>

                        <a href="<?= WEB_HOST ?>">
                           <img src="<?= $website['comp_logo']; ?>"
                              class="img-responsive img-center animated fadeInUpShort" alt="JFA" />
                        </a>

                        <h1
                           class="voffset-1 voffset-b-2 text-center text-uppercase text-bold animated *fadeInDownShort">
                           Welcome to Jude Fernandopulle Associates (Pvt) Ltd.</h1>

                        <svg height="130" width="130" class="img-responsive img-center animated fadeInDownShort"
                           alt="Anniversary">
                           <text x="0" y="70" fill="#0020BB30"
                              style="font-family:georgia;font-size:12rem;"><?= date('Y') - 1989 ?></text>
                           <text x="50" y="83" fill="grey" style="font-family:serif;font-size:1.6rem;">years</text>
                           <text x="13" y="109">▒ ANNIVERSARY ▒</text>
                           <text x="37" y="128" style="font-style:italic;font-size:1.2rem;">1989
                               - <?= date('Y') ?></text>
                           <g fill="none" stroke="black">
                              <path stroke="black" d="M13 95 l107 0" />
                              <path stroke="grey" d="M13 114 l107 0" />
                           </g>
                        </svg>

                        <!-- <img src="resources/images/30yrAnniv5.png"
                           class="img-responsive img-center animated fadeInUpShort" alt="JFA" /> -->
                     </div>
                     <div class="row animatedParent animateOnce" data-sequence='500'>
                        <hr class="style-four" />
                        <div class="col-xs-12 voffset-3 visible-xs visible-sm"></div>
                        <h3 class="voffset-1 voffset-b-3 text-center text-uppercase text-bold">Products & Services</h3>
                        <?php
                        $myCon = new dbConfig();
                        $myCon->connect();
                        $query = "SELECT i.*, u.upload_path FROM item_main_category i LEFT JOIN upload_data u ON "
                           . "i.cat_code = u.upload_ref AND u.upload_type_id = 2 AND u.featured = 1 WHERE "
                           . "i.active = 1 ORDER BY i.cat_code ASC";
                        $result = $myCon->query($query);
                        $count = mysqli_num_rows($result);
                        $data_id = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                           if (($count % 3 == 1) && ($data_id == 1) && $count == 1) {
                              $colum = 'col-xs-offset-0 col-sm-offset-4';
                           } else if (($count % 3 == 1) && ($data_id == 4) && $count == 4) {
                              $colum = 'col-xs-offset-0 col-sm-offset-4';
                           } else {
                              $colum = '';
                           }
                        ?>
                        <!-- START -->
                        <div class="col-xs-6 col-sm-4 <?php echo $colum; ?> col-xxs-full-width voffset-b-5">
                           <div class="container-fluid col-xs-pdn-both-0">
                              <div class="col-xs-12">
                                 <div class="row">
                                    <div class="front-items sp animated pulse slow hvr-overline-from-center"
                                       data-id='<?php echo $data_id; ?>'>
                                       <div class="icon-wrap">
                                          <a
                                             href="<?php echo ($row['local_url'] != null) ? $row['local_url'] : $row['custom_url']; ?>"><img
                                                src="<?php echo ($row['upload_path'] != null) ? 'uploads/' . $row['upload_path'] : $row['cat_logo']; ?>"
                                                height="50" class="img-responsive"
                                                alt="<?php echo $row['cat_url_slug']; ?>"></a>
                                       </div>
                                       <div class="col-xs-12 col-lg-pdn-both-0 voffset-4">
                                          <h1 class="text-smallcaps"><?php echo $row['cat_name']; ?></h1>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- END -->
                        <?php
                           $data_id += 1;
                        }
                        ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="container-fluid bg1">
            <div class="row" style="background-color: transparent">
               <div class="contact-about col-xs-12" style="background-color: transparent;">
               </div>
            </div>
         </div>
         <div class="others bg2">
            <div class="container-fluid animatedParent color3" style=" background-color: #ffffff99">
               <div class="container voffset-3 voffset-b-3" style="background-color: #ffffff99;">
                  <div class="row">
                     <div class="voffset-3 visible-xs">&nbsp;</div>
                     <div class="col-xs-12 voffset-b-2 front-jumbo-box">
                        <a id="about-anchor"></a>
                        <a id="about"></a>
                        <div class="col-xs-12 col-sm-6">
                           <div
                              class="col-xs-12 voffset-2 front-welcome animated fadeInLeftShort hvr-underline-from-center">
                              <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                              <h3 class="text-uppercase voffset-2 text-bold">Company Profile</h3>
                              <p class="voffset-1">
                                 Incorporated in the Democratic Socialist Republic of Sri Lanka under theCompanies
                                 Act No.17 of 1982. Registration No: N(PVS)9832 dated 25-Sep-1992 ICTAD (M5)
                                 Registration No 5730. Member of the NCCASL.
                                 <br />
                                 Founded in 1989, JFA had very humble beginnings and has grown and prospered with
                                 much hard work, and today still holds on to its core values of providing the Best
                                 Service with Quality and maintaining our best asset – our very loyal customer
                                 base.
                              </p>
                              <h3 class="text-uppercase text-bold voffset-8">Our Vision</h3>
                              <p class="voffset-1">To be the forerunner in Solar Technology and set standards in
                                 eco friendly environments
                                 Preserving human values in harmony with technology
                              </p>
                              <h3 class="text-uppercase text-bold voffset-8">Our Mission</h3>
                              <p class="voffset-1">To make JFA synonymous with "Quality and Service", and SUNBIRD
                                 a household word as the “Best provider of Solar Technology” at affordable cost
                                 and of international standards with highest level of after-sales service.</p>
                           </div>
                           <div class="col-xs-12 voffset-4 text-center col-pdn-both-0">
                              <a href="javascript:void(0);"
                                 class="data_rent btn btn-primary hvr-underline-from-center voffset-3 square"
                                 data-id="JFA Products and Services"><span
                                    class="glyphicons glyphicons-message-plus"></span>&nbsp; Contact us for all
                                 your Solar needs</a>
                           </div>
                           <?php
                           include_once './quick-form.php';
                           ?>
                        </div>
                        <div class="col-xs-12 voffset-5 visible-xss visible-xs">&nbsp;</div>
                        <div class="col-xs-12 col-sm-6 animated fadeInRightShort">
                           <div class="col-xs-12 col-xxs-pdn-both-0 voffset-b-4">
                              <div class="col-xs-12 voffset-2 visible-lg">&nbsp;</div>
                              <a href="<?= WEB_HOST ?>/home.php#">
                                 <img src="<?= $website['comp_logo']; ?>" class="img-responsive img-center" alt="JFA" />
                              </a>
                              <div class="text-bold text-info text-center">
                                 Jude Fernandopulle Associates (Pvt) Ltd.
                              </div>
                              <div class="col-xs-12 col-pdn-both-0 voffset-2">
                                 <hr class="style-four" />

                                 <?php
                                 $query = "SELECT * FROM item_main_category WHERE active = 1 ORDER BY cat_order ASC";
                                 $result = $myCon->query($query);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                 <div class="row">
                                    <div class="col-xs-5 col-xxs-full-width">
                                       <a
                                          href="<?php echo ($row['local_url'] != null) ? $row['local_url'] : $row['custom_url']; ?>">
                                          <img src="<?php echo $row['cat_logo']; ?>" class="img-responsive img-center"
                                             alt="<?php echo $row['custom_url']; ?>" />
                                       </a>
                                    </div>
                                    <div class="col-xs-7 col-xxs-full-width col-xxs-text-center">
                                       <div class="text-bold text-info">
                                          <?php echo $row['cat_name']; ?>
                                       </div>
                                       <h5 class="text-bold voffset-1">
                                          <?php echo $row['cat_title']; ?>
                                       </h5>
                                       <a href="<?php echo ($row['local_url'] != null) ? $row['local_url'] : $row['custom_url']; ?>"
                                          class="voffset-1"><?php echo $row['display_url']; ?></a>
                                    </div>
                                 </div>
                                 <hr class="style-four" />
                                 <?php } ?>

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- END of Other -->
         <div class="container-fluid bg2">
            <div class="row">
               <a id="contact_link-anchor"></a>
               <a id="contact_link"></a>
               <div class="contact-about col-xs-12" style="background-color: #ffffff99">
                  <h1>We'd Love to hear from You</h1>
                  <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                  <h3 class="voffset-3 col-xs-12 col-sm-6 col-lg-4"
                     style="text-shadow: 2px 2px 1px white, -5px -4px 3px white; "><span
                        class="glyphicons glyphicons-iphone-shake"></span>
                     <a href="tel:<?php echo $website["hotline"]; ?>">
                        <?php echo $website['hotline']; ?></a>
                  </h3>
                  <h3 class="voffset-3 voffset-b-2 col-xs-12 col-sm-6 col-lg-4"
                     style="text-shadow: 2px 2px 1px white, -5px -4px 3px white;">
                     <span class="glyphicons glyphicons-envelope"></span> <a
                        href="mailto:<?php echo $website["email"]; ?>"><?php echo $website["email"]; ?></a>
                     <?php
                     if (isset($website["email2"]) && $website["email2"] != null) {
                        echo ', <a href="mailto:' . $website["email2"] . '">' . $website["email2"] . '</a>';
                     }
                     ?>
                  </h3>
                  <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                  <div class="voffset-1 col-xs-12 col-lg-4 media-64 theme-1 reverse">
                     <?php if ($website["fb"] != null) { ?>
                     <a href="<?php echo $website["fb"]; ?>" target="_blank" data-toggle="tooltip"
                        data-placement="bottom" title="Like us on Facebook">
                        <div class="media-icn top fb"></div>
                     </a>
                     <?php }
                     if ($website["tw"] != null) { ?>
                     <a href="<?php echo $website["tw"]; ?>" target="_blank" data-toggle="tooltip"
                        data-placement="bottom" title="Follow us on Twitter">
                        <div class="media-icn top tw"></div>
                     </a>
                     <?php }
                     if ($website["gplus"] != null) { ?>
                     <a href="<?php echo $website["gplus"]; ?>" target="_blank" data-toggle="tooltip"
                        data-placement="bottom" title="Follow on Google Plus">
                        <div class="media-icn top gp"></div>
                     </a>
                     <?php }
                     if ($website["yt"] != null) { ?>
                     <a href="<?php echo $website["yt"]; ?>" target="_blank" data-toggle="tooltip"
                        data-placement="bottom" title="Watch us on YouTube">
                        <div class="media-icn top ut"></div>
                     </a>
                     <?php }
                     if ($website["pint"] != null) { ?>
                     <a href="<?php echo $website["pint"]; ?>" target="_blank" data-toggle="tooltip"
                        data-placement="bottom" title="Follow us on Pinterest">
                        <div class="media-icn top pr"></div>
                     </a>
                     <?php } ?>
                  </div>
                  <div class="col-xs-12 visible-xs voffset-1">&nbsp;</div>
                  <h4 class="voffset-2 col-xs-12">Questions about our products?</h4>
                  <div class="col-xs-12 voffset-b-3 text-center">
                     <button class="data_rent btn btn-default btn-lg square voffset-2"
                        data-id="JFA Products and Services">Contact Our Sales Team</button>
                  </div>
               </div>
            </div>
         </div>
         <?php include_once './footer.php'; ?>
      </div>
   </div>
</body>
<script>
$(window).resize(function() {
   setTimeout(function() {
      if ($(window).width() < 768) {
         $(".lefttitle").css("height", (100) + 'px');
         $(".slider-btn").css("height", (100) + 'px');
         $(".leftcontent").hide();
      } else {
         $(".lefttitle").css("height", (55) + 'px');
         $(".slider-btn").css("height", (55) + 'px');
         $(".leftcontent").show();
      }
   }, 500);
});
</script>

</html>
<?php
/* Call this function to output everything as gzipped content. */

// backup
//                                                <div class="row">
//                                                    <div class="col-xs-5 col-xxs-full-width">
//                                                        <a href="http://tropicalvillas.lk" target="_blank">
//                                                            <img src="resources/images/tropicalvillas-logo.jpg" class="img-responsive img-center" alt="www.tropicalvillas.lk"/>
//                                                        </a>
//                                                    </div>
//                                                    <div class="col-xs-7 col-xxs-full-width col-xxs-text-center">
//                                                        <div class="text-bold text-info">
//                                                            Villas in Negombo
//                                                        </div>
//                                                        <h5 class="text-bold voffset-1">Tropical Villas
//                                                        </h5>
//                                                        <a href="http://tropicalvillas.lk" class="voffset-1" target="_blank">www.tropicalvillas.lk</a>
//                                                    </div>
//                                                </div> 




gzip_output();
?>