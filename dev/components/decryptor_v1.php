<?php

// cmdline use:
if ( !empty($argv[1]) ) {
//  $string = parse_str($argv[1], $_GET);
#  var_dump($argv[1]);
  echo "\nDecrypted: ".decryptor($argv[1])."\n";
};


// -----------

function decryptor($cryptext)
{

   // for testing:        //! disable in PROD
   if (PHP_SAPI !== 'cli' && !defined("APP_MODE") && !defined("APP_NAME") ) {
      echo "<br>Decryptor is in Test Mode. (Must disable for PROD)<br>";
      define("APP_MODE","TEST");  // testdata
      define("APP_NAME","app1");  // testdata (see encryptor)
   };

   $key = hex2bin(substr($cryptext, 0, 32));
   if (defined("APP_MODE") || defined("APP_NAME")) {  // running within an app
      $siteHost = htmlspecialchars($_SERVER['HTTP_HOST']);
      if (isset($_SERVER['HTTP_REFERER'])) $siteHost = htmlspecialchars($_SERVER['HTTP_REFERER']);
      $siteHost = explode("/",$siteHost)[2];  // root of website
      $keytext = $siteHost . "*" . APP_NAME; // ie: "www.site.com*appname"
#      echo "<br />\nkeytext: " .$keytext;
      $key = openssl_digest($keytext, 'MD5', true); // paded/truncated=16
   }

   $cipher = "aes-128-cbc"; // Select same cipher method   
   in_array($cipher, openssl_get_cipher_methods()) or die("cipher error");
   $iv = hex2bin(substr($cryptext, - (openssl_cipher_iv_length($cipher) * 2)));

   $decryptor = substr($cryptext, 32, strlen($cryptext) - 32 - (openssl_cipher_iv_length($cipher) * 2));

   $decryption = openssl_decrypt(
      $decryptor,
      $cipher,
      $key,
      $options = 0,
      $iv
   );

   if (!$decryption) die("\n  ****  Decrypt failed!  **** " . " ($keytext)");

   return $decryption;
}

function_exists("cLog") ? cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.') : null;