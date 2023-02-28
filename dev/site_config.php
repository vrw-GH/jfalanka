<?php
/* --------------------------------------------------------------------
 * Site-wide DEFINITIONS  
 */

if (!isset($_SESSION)) {
    session_start();
}

/* --------------------------------------------------------------------
 * Production and Dev Customizations
 */
if (file_exists('.localDevOnly/dev-definitions.php')) {
    /* Use Local (Dev)  
     ! (keep here, even it is called in index.php due to iframe)
     * This file sets up "DEV environment" on the local system only
     * but should not affect LIVE server.
     ! - do **NOT** copy ".localDevOnly" folder to LIVE site!
     */
    include_once '.localDevOnly/dev-definitions.php';
}
// include .ENV file
// * (wont overwrite if already defined)
include_once 'modules/get-dotenvs.php';
// 
define(
    'SITE_DEV',
    ["http://wrightsdesk.com", "Redesign(2023): The Leisure Co."]
);
if (!defined('ADMIN'))     define('ADMIN', 'admin');
if (!defined('WEB_HOST'))  define('WEB_HOST', 'http://www.jfalanka.com');
/* Site URLs for .htaccess UrlReWrite (without end /) */
define('LOCAL',  $_SERVER['SERVER_NAME'] == "localhost"); /* true/false */

/* --------------------------------------------------------------------
 * include admin configuration file
 */
if (file_exists(ADMIN . '/admin_config.php')) {
    include_once ADMIN . '/admin_config.php';
} else if (file_exists('../' . ADMIN . '/admin_config.php')) {
    include_once '../' . ADMIN . '/admin_config.php';
} else if (file_exists('../../' . ADMIN . '/admin_config.php')) {
    include_once '../../' . ADMIN . '/admin_config.php';
} else {
    include_once WEB_HOST . '/' . ADMIN . '/admin_config.php';
}

/* --------------------------------------------------------------------
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

/* --------------------------------------------------------------------
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

/* --------------------------------------------------------------------
 * Saving Contact Data
 */
$query = "SELECT * FROM company_info CROSS JOIN seo LIMIT 1";
$result = $myCon->query($query);
while ($row = mysqli_fetch_assoc($result)) {
    $website['site_name']       = $row['comp_name'];
    $website['abbrev']          = "JFA";
    $website['title']           = $row['comp_web_title']; /* Page Title */
    $website['address']         = $row['comp_address'];
    $website['hotline']         = $row['comp_hotline'];
    $website['phone']           = $row['comp_phone'];
    $website['fax']             = $row['comp_fax'];
    $website['email']           = $row['comp_email'];
    $website['email2']          = $row['comp_email2'];
    $website['domain']          = $row['comp_domain']; /* without www and http */
    $website['profile']         = $row['comp_profile'];
    $website['vision']          = $row['comp_vision'];
    $website['mission']         = $row['comp_mission'];
    $website['google_map']      = $row['comp_google_map'];
    $website['google_map_size'] = $row['comp_google_map_size'];
    $website['logo']            = $row['comp_logo'];
    $website['skype']           = $row['comp_skype'];
    $website['fb']              = $row['comp_fb'];
    $website['tw']              = $row['comp_tw'];
    $website['gplus']           = $row['comp_gplus'];
    $website['yt']              = $row['comp_yt'];
    $website['pint']            = $row['comp_pint'];
    /* SEO Data */
    $config['seo']['seo_title'] = $row['seo_title'];
    $config['seo']['seo_dscp']  = $row['seo_dscp'];
    $config['seo']['seo_keywords'] = $row['seo_keywords'];
    $config['seo']['fb_id']     = $row['fb_id'];
    $config['seo']['og_type']   = $row['og_type'];
    $config['seo']['og_img']    = $row['og_img'];
    $config['seo']['og_site_name'] = $row['og_site_name'];
    $config['seo']['og_tw_dscp'] = $row['og_tw_dscp'];
    $config['seo']['tw_site']   = $row['tw_site'];
    $config['seo']['tw_creator'] = $row['tw_creator'];
    $config['seo']['tw_img']    = $row['tw_img'];
    $config['seo']['google_publisher'] = $row['google_publisher'];
    $config['seo']['google_analytics'] = $row['google_analytics'];
}
$myCon->closeCon();

/* Emailing */
if (!defined('SEND_EMAIL')) define('SEND_EMAIL', 'noreply@' . $website['domain']); /* use accountname@host.phenomhost.com */
if (!defined('REC_EMAIL'))  define('REC_EMAIL', $website['email']);

/* --------------------------------------------------------------------
 * og prefix
 */
define('SITE_SEO', $encObj->decode(SITE_SEO_KEY));

/* --------------------------------------------------------------------
 * og prefix
 */
define('OG_PRIFIX', 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"');

/* --------------------------------------------------------------------
 * Jqury & Boostrap URLS
 */
$website['boostrap_folder']         = 'resources/bootstrap-3.3.7';
$website['jquery_min_js']           = 'resources/js/jquery-3.1.1.min.js';
$website['jquery_migrate_js']       = 'resources/js/jquery-migrate-3.0.0.min.js';
$website['jquery_migrate_lower_js'] = 'resources/js/jquery-migrate-1.4.1.min.js';
$website['jquery_ui_css']           = 'resources/css/jquery-ui.min.css';
$website['jquery_ui_js']            = 'resources/js/jquery-ui.min.js';
$website['jquery_ui_theme_css']     = 'resources/css/jquery-ui.theme.min.css';
$website['jquery_ui_structure_css'] = 'resources/css/jquery-ui.structure.min.css';

/* --------------------------------------------------------------------
 * Domain info/URLS
 */
$domain['base_dir']
    = __DIR__; /* Absolute path to your installation, ex: /var/www/mywebsite  or C:\wamp\www\mywebsite */
$domain['doc_root']
    = preg_replace("!{$_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']);  /* ex: /var/www */
$f = "/" . basename($_SERVER['SCRIPT_FILENAME']);
$a = preg_replace("!^{$domain['doc_root']}!", '', $_SERVER['SCRIPT_FILENAME']);
$domain['base_url']
    = preg_replace("!{$f}$!", '', $a); /* '' or '/mywebsite' */
$domain['protocol']
    = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$domain['port']
    = $_SERVER['SERVER_PORT'];
$domain['disp_port']
    = ($domain['protocol'] == 'http' && $domain['port'] == 80 || $domain['protocol'] == 'https' && $domain['port'] == 443) ? '' : ":" . $domain['port'];
$domain['domain']
    = $_SERVER['SERVER_NAME'];
$domain['full_url']
    = $domain['protocol'] . "://" . $domain['domain'] . $domain['disp_port'] . $domain['base_url']; /* Ex: 'http://example.com', 'https://example.com/mywebsite', etc. */

// echo "<script>console.log('Site config loaded.');</script>";
// echo "<script>console.log('domain info/urls " . json_encode($domain) . "')</script>";