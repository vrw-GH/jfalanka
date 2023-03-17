<?php

include_once 'admin_config_master.php';

$website['config']['main_cat'] = true;

/* main category */
$website['images']['main_entry_width'] = 600;
$website['images']['main_entry_height'] = 600;
$website['descp']['main_entry'] = true;
$website['custom_url']['main_entry'] = true;
$website['gallery']['main_entry'] = true; /* enable gallery on main category wise */


/* sub category */


/* brands */


/* main slider */


/* news & events */


/* posts */


/* page */
cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");
