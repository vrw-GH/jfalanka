<?php

function decryptor($passphraze)
{
   // $ciphering = "BF-CBC"; // Select same cipher method   
   $ciphering = "aes-128-cbc"; // Select same cipher method      
   $iv_length = openssl_cipher_iv_length($ciphering);
   // $decryption_key = openssl_digest(php_uname(), 'MD5', TRUE); // len 16
   $decryption_key = hex2bin(substr($passphraze, 0, 32));
   $decryption_iv = hex2bin(substr($passphraze, - ($iv_length * 2)));
   $decryptor = substr($passphraze, 32, strlen($passphraze) - 32 - ($iv_length * 2));
   // Descrypt the string
   $decryption = openssl_decrypt(
      $decryptor,
      $ciphering,
      $decryption_key,
      $options = 0,
      $decryption_iv
   );
   return $decryption;
}

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.');