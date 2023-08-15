<?php

/* app_info */
$app['info']['version'] = "23.1.5";
$app['info']['title'] = "JFALanka";
$app['info']['licence'] = "TLC/" . date('Y');
// see also devinfo



// define(
//     'SITE_DEV',
//     ["", ""]
// );

/* page_info */
$pageinfo['mode'] = defined("APP_MODE") ? APP_MODE : "PROD";
if ($pageinfo['mode'] == "PROD") {
    $pageinfo['title'] = $config['seo']['seo_title'];
} else {
    $pageinfo['tagline'] =
        '<div id="devtagline">' .
        'Mode: ' . strtoupper($pageinfo["mode"]) . ', ' .
        'Ver: ' . $app['info']['version'] . ', ' .
        'Host: ' . WEB_HOST . ', ' .
        'DB: ' . DB_HOST . ', ' .
        'Em: ' . EMAIL_TO .
        '</div>';
}

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");
