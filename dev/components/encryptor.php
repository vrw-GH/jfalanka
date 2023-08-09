<?php

// Encryptor is primarily for cmd line use --------------
$input_string = readline('Enter a string: ');
if ($input_string) encryptor($input_string);
// -----------

function encryptor($plaintext)
{
   if ($plaintext) {
      if (strlen($plaintext) < 6) {
         echo "minimum 8 characters.";
         exit();
      }

      // $cipher = "bf-cbc"; // no longer in print_r(openssl_get_cipher_methods());!!!!
      $cipher = "aes-128-cbc";
      in_array($cipher, openssl_get_cipher_methods()) or die("cipher error");

      $key = openssl_digest(php_uname(), 'MD5', true); // len will be pad/trunc=16       (therefore this must be generated at system where php will run)

      // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
      $iv = random_bytes(openssl_cipher_iv_length($cipher));

      $encryption = openssl_encrypt(
         $plaintext,
         $cipher,
         $key,
         $options = 0,
         $iv
      );

      // echo  bin2hex($key) . "\n-" . $encryption . "-\n" . bin2hex($iv) . "\n";
      $cryptext = bin2hex($key) . $encryption . bin2hex($iv);
      echo "\n<br/>Encrypted: " . $cryptext;

      include_once "decryptor.php";
      echo "\n<br/>Decrypted!: " . decryptor($cryptext);

      return $cryptext;
   };
}

function_exists("cLog") ? cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.') : null;