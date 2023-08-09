<?php

/* app_info */
$app['info']['version'] = "23.1.5";
$app['info']['title'] = "JFALanka";
$app['info']['lead'] = "Victor Wright";
$app['info']['licence'] = "TLC/2023";
$app['info']['developer']['web'] = "http://wrightsdesk.com";
$app['info']['developer']['descr'] = "Redesign(2023): The Leisure Co.";
$app['info']['developer']['email'] = "!";
$app['info']['developer']['phone'] = "!";

// define(
//     'SITE_DEV',
//     ["", ""]
// );

/* page_info */
$pageinfo['mode'] = defined("APP_MODE") ? APP_MODE : "PROD";
$pageinfo['title'] =
    ($pageinfo['mode'] != "PROD")
    ? $pageinfo['title']
    : $config['seo']['seo_title'];
$pageinfo['tagline'] =
    '<div id="devtagline">' .
    'Mode: ' . strtoupper($pageinfo["mode"]) . ', ' .
    'Host: ' . WEB_HOST . ', ' .
    'DB: ' . DB_HOST . ', ' .
    'Ver: ' . $app['info']['version'] .
    '</div>';


cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");
