<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 side-menu dark-color text-center text-center col-xs-text-center col-xxs-text-center">
                <div class="voffset-b-5">&nbsp;</div>
                <a href="<?php echo WEB_HOST ?>/index.php"><img src="<?php echo WEB_HOST ?>/resources/images/logo-footer.png" alt="<?php echo $website['domain']; ?>" class="img-responsive img-center"/></a>
                <ul class="listnone text-center voffset-5">
                    <li class="h2">Hotline</li>
                    <li><?php echo $website['hotline']; ?></li>
                    <li class="h2 voffset-2">Phone</li>
                    <li><?php echo $website['phone']; ?></li>
                    <li class="h2 voffset-2">Email</li>
                    <li><?php echo $website['email']; ?></li>
                    <li class="h2 voffset-2">Address</li>
                    <li><?php echo nl2br($website['address']); ?></li>
                </ul>
                <div class="voffset-b-5">&nbsp;</div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 side-menu">
                <ul class="listnone">
                    <li class="h1">Quick Links</li>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact_link">Contact Us</a></li>
                    <li><a href="<?= SITE2[0] ?>/page/opportunities">Careers & Business Opportunities</a></li>					
                </ul>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 side-menu">
                <ul class="listnone">
                    <li class="h1">JFA Products & Services</li>
                    <?php
                    $query = "SELECT * FROM item_main_category WHERE active = 1 ORDER BY cat_order ASC";
                    $result = $myCon->query($query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <li><a href="<?php echo $row['custom_url']; ?>"><?php echo $row['cat_name']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 side-menu">
                <ul class="listnone">
                    <li class="h1">Follow Us</li>
                </ul>
                <ul class="listnone media-24 theme-5 reverse withlabel">
                    <?php if ($website["fb"] != null) { ?>
                        <li><a href="<?php echo $website["fb"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Like us on Facebook"><div class="media-icn top fb"></div> Like us on Facebook</a></li>
                    <?php } if ($website["tw"] != null) { ?>
                        <li><a href="<?php echo $website["tw"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Follow us on Twitter"><div class="media-icn top tw"></div> Follow us on Twitter</a></li>
                    <?php } if ($website["gplus"] != null) { ?>
                        <li><a href="<?php echo $website["gplus"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Follow on Google Plus"><div class="media-icn top gp"></div> Follow on Google Plus</a></li>
                    <?php } if ($website["yt"] != null) { ?>
                        <li><a href="<?php echo $website["yt"]; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Watch us on YouTube"><div class="media-icn top ut"></div> Watch us on YouTube</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid copyright">
        <div class="container voffset-2 voffset-b-2">
            <div class="col-xs-12 col-sm-6 col-xs-text-center col-xxs-text-center col-pdn-both-0">
                Copyright &copy; 1989 - <?php echo date('Y') . ' ' . $website['title']; ?>. All Rights Reserved.<br/>
            </div>
            <div class="col-xs-12 col-sm-6 col-xs-text-center col-xxs-text-center col-pdn-both-0 text-right">
				Site Design <a href="<?= SITE_DEV[0] ?>"><?= SITE_DEV[1] ?></a>
            </div>
        </div>
    </div>	
</div>
<br/>
<?php

include_once './footer_css_js.php';