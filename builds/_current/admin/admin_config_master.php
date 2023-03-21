<?php

/* --------------------------------------------------------------------
 * Resource info/URLS
 */
$website['admin_folder']             = 'admin';
$website['uploads_folder']           = 'uploads';
$website['downloads_folder']         = 'downloads';
$website['images_folder']            = 'resources/images';
$website['bootstrap_folder']         = 'resources/bootstrap-3.3.7';
$website['db1_php']                  = 'models/dbConfig1.php';
$website['encryption_php']           = 'components/encryption.php';
$website['decryptor_php']            = 'components/decryptor.php';
$website['emailer_php']              = 'components/emailer.php';
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

/*
 * -----------------------------------------------------------------------------
 */
$website['config']['main_cat'] = false;
$website['config']['sub_cat'] = false;
$website['config']['brands'] = false;
$website['config']['units'] = false;
$website['config']['apps'] = false;
$website['config']['main_slider'] = false;
$website['config']['news'] = false;
$website['config']['comments'] = false;
$website['config']['pages'] = false;
$website['config']['posts'] = false;

/* main category */
$website['images']['main_entry_width'] = 400;
$website['images']['main_entry_height'] = 400;
$website['descp']['main_entry'] = false;
$website['custom_url']['main_entry'] = false;
$website['gallery']['main_entry'] = false; /* enable gallery on main category wise */
$website['images']['main_entry_gal_width'] = 1024; /* scaled size. only one value for width or height */
$website['images']['main_entry_gal_height'] = 0;
$website['images']['main_entry_thumb_width'] = 360;
$website['images']['main_entry_thumb_height'] = 0;

/* sub category */
$website['images']['sub_display'] = false;
$website['images']['sub_entry_width'] = 350;
$website['images']['sub_entry_height'] = 350;
$website['descp']['sub_entry'] = false;
$website['gallery']['sub_entry'] = false; /* enable gallery on main category wise */
$website['images']['sub_entry_gal_width'] = 1024; /* scaled size. only one value for width or height */
$website['images']['sub_entry_gal_height'] = 0;
$website['images']['sub_entry_thumb_width'] = 360;
$website['images']['sub_entry_thumb_height'] = 0;

/* brands */
$website['images']['brand_display'] = false;
$website['images']['brand_width'] = 350;
$website['images']['brand_height'] = 350;
$website['descp']['brand_display'] = false;
$website['custom_url']['brand_display'] = false;

/* main slider */
$website['images']['main_slider_width'] = 1600;
$website['images']['main_slider_height'] = 500;

/* news & events */
$website['images']['news_width'] = 450;
$website['images']['news_height'] = 450;
$website['images']['news_gal_width'] = 1024; /* scaled size. only one value for width or height */
$website['images']['news_gal_height'] = 0;
$website['images']['news_thumb_width'] = 360;
$website['images']['news_thumb_height'] = 0;

/* post */
$website['feature']['district'] = false;
$website['feature']['city'] = false;
$website['feature']['cell_price'] = false;
$website['feature']['off_value'] = false;
$website['feature']['off_precentage'] = false;
$website['feature']['stock'] = false;

$website['descp']['post_terms_entry'] = false;
$website['descp']['post_policies_entry'] = false;
$website['descp']['post_data1_entry'] = false;
$website['descp']['post_data2_entry'] = false;
$website['descp']['post_data3_entry'] = false;
$website['name']['post_data1_entry'] = 'Data1';
$website['name']['post_data2_entry'] = 'Data2';
$website['name']['post_data3_entry'] = 'Data3';

$website['images']['post_watermark'] = false;
$website['images']['post_width'] = 700;
$website['images']['post_height'] = 700;
$website['images']['post_small_width'] = 400;
$website['images']['post_small_height'] = 400;
$website['gallery']['post_entry'] = false; /* enable gallery on posts  */
$website['images']['post_gal_width'] = 1024; /* scaled size. only one value for width or height */
$website['images']['post_gal_height'] = 0;
$website['images']['post_thumb_width'] = 360;
$website['images']['post_thumb_height'] = 0;

/* page */
$website['images']['page_width'] = 1600;
$website['images']['page_height'] = 300;
$website['custom_url']['page'] = false;
$website['images']['page_gal_width'] = 1024; /* scaled size. only one value for width or height */
$website['images']['page_gal_height'] = 0;
$website['images']['page_thumb_width'] = 360;
$website['images']['page_thumb_height'] = 0;

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");