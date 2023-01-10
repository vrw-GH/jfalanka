<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($pid)) {
    $pid = 0;
}
include_once 'models/dbConfig.php';
include_once 'models/encryption.php';

$myCon = new dbConfig();
$myCon->connect();
$enObj = new encryption();
?>
<div id="header" class="<?php
if ($pid == 1) {
    echo 'home';
}
?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 top-hd-layer col-pdn-both-0">
                <div class="container-fluid col-xs-pdn-both-0 col-xxs-pdn-both-0">
                    <div class="row">
                        <div class="col-xs-12 col-xs-text-center col-xxs-text-center">
                            <ul class="listnone float-right">
                                <li class="noborder"><a href="#contact_link" class="scrollme">Contact Us</a></li>
                                <li><a href="#about" class="scrollme">About Us</a></li>
                                <li><span class="glyphicons glyphicons-iphone"></span> <?php echo $website['hotline']; ?></li>
                                <li><span class="glyphicons glyphicons-envelope"></span> <?php echo $website['email']; ?></li>
                                <li>
                                    <img src="<?php echo WEB_HOST ?>/resources/images/mastercard.png" alt="mastercard" class="img-responsive float-right">
                                    <img src="<?php echo WEB_HOST ?>/resources/images/visa.png" alt="visa" class="img-responsive float-right">
                                </li>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>