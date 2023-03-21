<?php
/* --------------------------------------------------------------------
 * Domain info/URLS
 */
$domain['base_dir']
   = preg_replace("!\\\components!", '', __DIR__); /* Absolute path to your installation, ex: /var/www/mywebsite  or C:\wamp\www\mywebsite */
$domain['doc_root']
   = preg_replace("!{$_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']);  /* ex: /var/www */
$f = "/" . basename($_SERVER['SCRIPT_FILENAME']);
$a = preg_replace("!^{$domain['doc_root']}!", '', $_SERVER['SCRIPT_FILENAME']);
$domain['base_url']
   =  preg_replace("!{$f}$!", '', $a); /* '' or '/mywebsite' */
$domain['base_url'] = preg_replace("!/modules!", '', $domain['base_url']);
$domain['protocol']
   = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$domain['port']
   = $_SERVER['SERVER_PORT'];
$domain['disp_port']
   = ($domain['protocol'] == 'http' && $domain['port'] == 80 || $domain['protocol'] == 'https' && $domain['port'] == 443) ? '' : ":" . $domain['port'];
$domain['domain']
   = $_SERVER['SERVER_NAME'];
$domain['full_url']
   = $domain['protocol'] . "://" . $domain['domain'] . $domain['disp_port'] . $domain['base_url']; /* Ex: 'http://example.com', 'https://example.com/    mywebsite', etc. */


cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.');