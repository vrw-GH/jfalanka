<?php

function decryptor($cryptext)
{
   $cipher = "aes-128-cbc"; // Select same cipher method   
   in_array($cipher, openssl_get_cipher_methods()) or die("cipher error");
   $key = hex2bin(substr($cryptext, 0, 32));
   $iv = hex2bin(substr($cryptext, - (openssl_cipher_iv_length($cipher) * 2)));

   $decryptor = substr($cryptext, 32, strlen($cryptext) - 32 - (openssl_cipher_iv_length($cipher) * 2));

   $decryption = openssl_decrypt(
      $decryptor,
      $cipher,
      $key,
      $options = 0,
      $iv
   );

   // echo  $key . "\n" . $decryptor . "\n" . $iv . "\n";
   return $decryption;
}

function_exists("cLog") ? cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.') : null;