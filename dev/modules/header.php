<?php
if (!isset($_SESSION)) {
   session_start();
}
if (!isset($pid)) {
   $pid = 0;
}
// include_once '../models/dbConfig.php';
// include_once '../models/encryption.php';

$myCon = new dbConfig();
$myCon->connect();
$enObj = new encryption();
?>

<div id="header" class="<?php if ($pid == 1) echo 'home'; ?>" style="position: fixed; z-index:99;">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12 top-hd-layer col-pdn-both-0">
            <div class="container-fluid col-xs-pdn-both-0 col-xxs-pdn-both-0">
               <div class="row">
                  <div class="col-xs-12 col-xs-text-center col-xxs-text-center">
                     <ul class="listnone float-right">
                        <li class="noborder"><a href="#contact_link" class="scrollme">Contact Us</a></li>

                        <li><a href="#about" class="scrollme">About Us</a></li>

                        <li><span class="glyphicons glyphicons-iphone"></span>
                           <a href="tel:<?php echo $website["hotline"]; ?>" data-toggle="tooltip"
                              data-placement="bottom" title="Opens Phone App">
                              <?php echo $website['hotline']; ?></a>
                        </li>

                        <li><span class="glyphicons glyphicons-envelope"></span>
                           <a href="mailto:<?php echo $website["email"]; ?>" data-toggle="tooltip"
                              data-placement="bottom" title="Opens eMail App">
                              <?php echo $website['email']; ?></a>
                        </li>

                        <!-- <li>
                                    <img src="<?php echo WEB_HOST ?>/resources/images/mastercard.png" alt="mastercard" class="img-responsive float-right">
                                    <img src="<?php echo WEB_HOST ?>/resources/images/visa.png" alt="visa" class="img-responsive float-right">
                                </li> -->

                        <?php if ($website["fb"] != null) { ?>
                        <li><a href="<?php echo $website["fb"]; ?>" target="_blank" data-toggle="tooltip"
                              data-placement="bottom" title="Like us on Facebook" class="scrollme" height="50px">
                              <img src="../<?= $website['images_folder'] ?>/social_fb_24x24_5_cr.png">
                           </a></li>
                        <?php } ?>
                        <?php if ($website["tw"] != null) { ?>
                        <li><a href="<?php echo $website["tw"]; ?>" target="_blank" data-toggle="tooltip"
                              data-placement="bottom" title="Follow us on Twitter" class="scrollme"><img
                                 src="../<?= $website['images_folder'] ?>/social_tw_24x24_5_cr.png">
                           </a> </li>
                        <?php } ?>
                        <?php if ($website["gplus"] != null) { ?>
                        <li><a href="<?php echo $website["gplus"]; ?>" target="_blank" data-toggle="tooltip"
                              data-placement="bottom" title="Follow on Google Plus" class="scrollme">
                              <img src="../<?= $website['images_folder'] ?>/social_gp_24x24_5_cr.png">
                           </a> </li>
                        <?php } ?>
                        <?php if ($website["yt"] != null) { ?>
                        <li> <a href="<?php echo $website["yt"]; ?>" target="_blank" data-toggle="tooltip"
                              data-placement="bottom" title="Watch us on YouTube" class="scrollme"><img
                                 src="../<?= $website['images_folder'] ?>/social_yt_24x24_5_cr.png">
                           </a></li>
                        <?php } ?>
                        <?php if ($website["pint"] != null) { ?>
                        <li> <a href="<?php echo $website["pint"]; ?>" target="_blank" data-toggle="tooltip"
                              data-placement="bottom" title="Follow us on Pinterest" class="scrollme"><img
                                 src="../<?= $website['images_folder'] ?>/social_pi_24x24_5_cr.png">
                           </a></li>
                        <?php } ?>

                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?= cLog(pathinfo(__FILE__, PATHINFO_FILENAME) . ' loaded.'); ?>