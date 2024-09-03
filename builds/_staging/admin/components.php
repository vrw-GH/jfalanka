<?php

/* --------------------------------------------------------------------
 * Resource info/URLS
 */
$website['uploads_folder']           = 'uploads';
$website['downloads_folder']         = 'downloads';
$website['images_folder']            = 'resources/images';

// components
$website['getdotenv_php']            = 'components/get-dotenvs.php';
$website['encryption_php']           = 'components/encryption.php';
$website['decryptor_php']            = 'components/decryptor.php';
$website['emailer_php']              = 'components/emailer.php';

// models
$website['db1_php']                  = 'models/dbConfig1.php';

// resources 
$website['bootstrap_folder']         = 'resources/bootstrap-3.3.7';
$website['captcha_php']              = 'resources/new_captcha/captcha.php';
$website['jquery_min_js']            = 'resources/js/jquery-3.1.1.min.js';
$website['jquery_migrate_js']        = 'resources/js/jquery-migrate-3.0.0.min.js';
$website['jquery_migrate_lower_js']  = 'resources/js/jquery-migrate-1.4.1.min.js';
$website['jquery_ui_css']            = 'resources/css/jquery-ui.min.css';
$website['jquery_ui_js']             = 'resources/js/jquery-ui.min.js';
$website['jquery_ui_theme_css']      = 'resources/css/jquery-ui.theme.min.css';
$website['jquery_ui_structure_css']  = 'resources/css/jquery-ui.structure.min.css';
$website['masonry_pkgd_min_js']      = 'resources/js/masonry.pkgd.min.js';
$website['imagesloaded_pkgd_min_js'] = 'resources/js/imagesloaded.pkgd.min.js';

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");