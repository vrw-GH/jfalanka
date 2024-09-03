<?php

/* website configs
* ----------------------------------------
*/
$website['config']['main_cat'] = true; // false;
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
$website['images']['main_entry_width'] = 600; // 400;
$website['images']['main_entry_height'] = 600; // 400;
$website['descp']['main_entry'] = true; // false;
$website['custom_url']['main_entry'] = true; // false;
$website['gallery']['main_entry'] = true; // false; /* enable gallery on main category wise */
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