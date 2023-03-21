<?php

$input_string = readline('Enter a string: '); // for cmd line use
if ($input_string) encryptor($input_string);

function encryptor($simple_string)
{
   if ($simple_string) {
      if (strlen($simple_string) < 6) {
         echo "minimum 8 characters.";
         exit();
      }
      $ciphering = "BF-CBC"; // Store cipher method
      $encryption_key = openssl_digest(php_uname(), 'MD5', TRUE); // len 16
      $encryption_iv = random_bytes(openssl_cipher_iv_length($ciphering));
      // Encryption of string process starts
      $encryption = openssl_encrypt(
         $simple_string,
         $ciphering,
         $encryption_key,
         $options = 0,
         $encryption_iv
      );
      $encryption2 = bin2hex($encryption_key) . $encryption . bin2hex($encryption_iv);
      echo "\n<br/>Encrypted: " . $encryption2;
      include_once "decryptor.php";
      echo "\n<br/>Decrypted!: " . decryptor($encryption2);
      return $encryption2;
   };
}

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.');