<?php // Site-wide DEFINITIONS & info

if (!isset($_SESSION)) {
    session_start();
}
$systemdate = date('Y-m-d');
$pid = 1;

include_once '../components/domaininfo.php';
include_once '../admin/admin_config_master.php';
include_once '../' . $website["admin_folder"] . '/admin_config.php';  // additional admin configs
if (file_exists('../.localDevOnly/dev-definitions.php')) { // Use Local (Dev) Customizations
    /* This file sets up "DEV environment" on the local system only
     * but should not affect LIVE server.
     * Also loads the DEBUGGING (cLog) system.
     ! do **NOT** copy this ".localDevOnly" folder to LIVE site!
     */
    include_once '../.localDevOnly/dev-definitions.php';
}
function cLog($a)
{
    (function_exists("cLogger")) ? cLogger($a) : null;
};

/* include .ENV file --------------------------------------------------
 * (wont overwrite if already defined) - ie: in dev-definitions.php
 */
include_once 'get-dotenvs.php';

/* --------------------------------------------------------------------
 * ENV fallbacks and additionals
 */
define('LOCAL',  $_SERVER['SERVER_NAME'] == "localhost"); /* true/false */
if (!defined('WEB_HOST'))       define('WEB_HOST', $domain['full_url']);
if (!defined('CAPTCHA_LEN'))    define('CAPTCHA_LEN', 5);
if (!defined('EMAIL_ISSMTP'))   define('EMAIL_ISSMTP', 1); // 1/"true" or 0/"" ( boolval() )
define(
    'SITE_DEV',
    ["http://wrightsdesk.com", "Redesign(2023): The Leisure Co."]
);

/* --------------------------------------------------------------------
 * DB & Encryption
 */
include_once '../components/encryption.php';
$encObj = new encryption();
include_once '../' . $website['db1_php'];
$myCon = new dbConfig1();

/* --------------------------------------------------------------------
 * Saving Contact Data
 */
$myCon->connect();
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

/* -----------------------------------------------------------------------------
 * SEO Type [basic, ultimate, enterprise] 
 * og prefix
 */
define('SITE_SEO',   $encObj->decode(SITE_SEO_KEY)); // ! decryption??
define('OG_PRIFIX',  'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"');

$canonical_url = WEB_HOST;
$pageinfo['mode'] = defined("APP_MODE") ? APP_MODE : null;
$pageinfo['title'] = ($pageinfo['mode'] != "live") ? $pageinfo['title'] : $config['seo']['seo_title'];
$pageinfo['tagline'] =
    '<div id="devtagline">' .
    'Mode: ' . strtoupper($pageinfo["mode"]) . ', ' .
    'Host: >>' . $pageinfo["webhost"] . '<< ' .
    '</div>';

/* ---end of configs-------------------------------------------------- */

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");
$constants = get_defined_constants(true)['user'];
cLog($constants);
cLog($website);
cLog($config);
cLog($domain);
cLog($pageinfo);
