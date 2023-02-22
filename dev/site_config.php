<?php

if (!isset($_SESSION)) {
    session_start();
}

/* ------------------------------------------------------------------------------
 * Site-wide DEFINITIONS  
 */
/* To Use Local (Dev) 
	* - do not copy this folder to online site
	*/
if (file_exists('.localDevOnly/dev-definitions.php')) {
    include_once '.localDevOnly/dev-definitions.php';
}
/* Site URL for .htaccess UrlReWrite (without end /) */
if (!defined('WEB_HOST'))     define('WEB_HOST', 'http://www.jfalanka.com');
if (!defined('SITE_DEV'))    define('SITE_DEV', ["http://slwebcreations.com", "SL Web Creations"]);
if (!defined('SITE1'))         define('SITE1', ["http://tropicalhomes.lk", "TROPICAL Homes"]);
if (!defined('SITE2'))         define('SITE2', ["http://sunbird.lk", "SUNBIRD Technology"]);
if (!defined('SITE3'))         define('SITE3', ["http://sensepv.lk", "SENSE PV Systems"]);
if (!defined('SEND_EMAIL'))    define('SEND_EMAIL', 'noreply@jfalanka.com'); /* use accountname@host.phenomhost.com */
if (!defined('REC_EMAIL'))    define('REC_EMAIL', $website['email']);

/*
 * -----------------------------------------------------------------------------
 * include admin configuration file
 */
if (!defined('ADMIN')) define('ADMIN', 'admin');
if (file_exists(ADMIN . '/admin_config.php')) {
    include_once ADMIN . '/admin_config.php';
} else if (file_exists('../' . ADMIN . '/admin_config.php')) {
    include_once '../' . ADMIN . '/admin_config.php';
} else if (file_exists('../../' . ADMIN . '/admin_config.php')) {
    include_once '../../' . ADMIN . '/admin_config.php';
} else {
    include_once WEB_HOST . '/' . ADMIN . '/admin_config.php';
}

/*
 * -----------------------------------------------------------------------------
 * Jqury & Boostrap URLS
 */
$website['boostrap_folder'] = 'resources/bootstrap-3.3.7';
$website['jquery_min_js'] = 'resources/js/jquery-3.1.1.min.js';
$website['jquery_migrate_js'] = 'resources/js/jquery-migrate-3.0.0.min.js';
$website['jquery_migrate_lower_js'] = 'resources/js/jquery-migrate-1.4.1.min.js';
$website['jquery_ui_css'] = 'resources/css/jquery-ui.min.css';
$website['jquery_ui_js'] = 'resources/js/jquery-ui.min.js';
$website['jquery_ui_theme_css'] = 'resources/css/jquery-ui.theme.min.css';
$website['jquery_ui_structure_css'] = 'resources/css/jquery-ui.structure.min.css';
/*
 * -----------------------------------------------------------------------------
 * Database Connection
 */
if (file_exists('./models/dbConfig.php')) {
    include_once './models/dbConfig.php';
} else if (file_exists('../models/dbConfig.php')) {
    include_once '../models/dbConfig.php';
} else {
    include_once WEB_HOST . '/models/dbConfig.php';
}
$myCon = new dbConfig();
$myCon->connect();

/*
 * -----------------------------------------------------------------------------
 * Encryption
 */
if (file_exists('./models/encryption.php')) {
    include_once './models/encryption.php';
} else if (file_exists('../models/encryption.php')) {
    include_once '../models/encryption.php';
} else {
    include_once WEB_HOST . '/models/encryption.php';
}
$encObj = new encryption();

/*
 * -----------------------------------------------------------------------------
 * Saving Contact Data
 */
$query = "SELECT * FROM company_info CROSS JOIN seo LIMIT 1";
$result = $myCon->query($query);
while ($row = mysqli_fetch_assoc($result)) {
    $website['site_name'] = $row['comp_name'];
    $website['title'] = $row['comp_web_title']; /* Page Title */
    $website['address'] = $row['comp_address'];
    $website['hotline'] = $row['comp_hotline'];
    $website['phone'] = $row['comp_phone'];
    $website['fax'] = $row['comp_fax'];
    $website['email'] = $row['comp_email'];
    $website['email2'] = $row['comp_email2'];
    $website['domain'] = $row['comp_domain']; /* without www and http */
    $website['google_map'] = $row['comp_google_map'];
    $website['google_map_size'] = $row['comp_google_map_size'];
    $website['comp_logo'] = $row['comp_logo'];
    $website['skype'] = $row['comp_skype'];
    $website['fb'] = $row['comp_fb'];
    $website['tw'] = $row['comp_tw'];
    $website['gplus'] = $row['comp_gplus'];
    $website['yt'] = $row['comp_yt'];
    $website['pint'] = $row['comp_pint'];
    /* SEO Data */
    $config['seo']['seo_title'] = $row['seo_title'];
    $config['seo']['seo_dscp'] = $row['seo_dscp'];
    $config['seo']['seo_keywords'] = $row['seo_keywords'];
    $config['seo']['fb_id'] = $row['fb_id'];
    $config['seo']['og_type'] = $row['og_type'];
    $config['seo']['og_img'] = $row['og_img'];
    $config['seo']['og_site_name'] = $row['og_site_name'];
    $config['seo']['og_tw_dscp'] = $row['og_tw_dscp'];
    $config['seo']['tw_site'] = $row['tw_site'];
    $config['seo']['tw_creator'] = $row['tw_creator'];
    $config['seo']['tw_img'] = $row['tw_img'];
    $config['seo']['google_publisher'] = $row['google_publisher'];
    $config['seo']['google_analytics'] = $row['google_analytics'];
}
$myCon->closeCon();

/*
 * -----------------------------------------------------------------------------
 * og prefix
 */
define('SITE_SEO', $encObj->decode(SITE_SEO_KEY));


/*
 * -----------------------------------------------------------------------------
 * og prefix
 */
define('OG_PRIFIX', 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"');

/*
 * -----------------------------------------------------------------------------
 * Directory URLS
 */
$base_dir = __DIR__; /* Absolute path to your installation, ex: /var/www/mywebsite  or C:\wamp\www\mywebsite */
$doc_root = preg_replace("!{$_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']);  /* ex: /var/www */
$base_url = preg_replace("!^{$doc_root}!", '', $base_dir); /* '' or '/mywebsite' */
$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain = $_SERVER['SERVER_NAME'];
$full_url = "$protocol://{$domain}{$disp_port}{$base_url}"; /* Ex: 'http://example.com', 'https://example.com/mywebsite', etc. */
	// echo $base_dir;echo "<br>";
	// echo $doc_root;echo "<br>";
	// echo $base_url;echo "<br>";
	// echo $protocol;echo "<br>";
	// echo $port;echo "<br>";
	// echo $disp_port;echo "<br>";
	// echo $domain;echo "<br>";