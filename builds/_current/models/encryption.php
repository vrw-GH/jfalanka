<?php

class encryption
{

    var $skey = "abc25dfsdgh2374e"; // you can change it	
    var $cipher = "aes-128-gcm";

    public function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    //64 bit encode
    public function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    //encode function
    public function encode($value)
    {
        if (!$value) {
            return false;
        }
        $text = $value;
        // $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        // $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        // $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        $iv_size = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_size);
        $crypttext = openssl_encrypt($text, $this->cipher, $this->skey, $options = 0, $iv); //, $tag

        return trim($this->safe_b64encode($crypttext));
    }

    //decode function
    public function decode($value)
    {
        if (!$value) {
            return false;
        }
        $crypttext = $this->safe_b64decode($value);
        // $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        // $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        // $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);

        $iv_size = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_size);
        $decrypttext = openssl_decrypt($crypttext, $this->cipher, $this->skey, $options = 0, $iv); //, $tag

        return trim($decrypttext);
    }


    /* md5----------------------------------------------------------------- */

    private $key = 'd540rGsdgbhB1xGsdsd78413';

    public function md5encode($value)
    {
        if (!$value) {
            return false;
        }
        $encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->key), $value, MCRYPT_MODE_CBC, md5(md5($this->key))));
        /*this encoded value may containing '+' marks. Php cannot get($_GET[]) '+' marks via URL */
        $encoded = urlencode($encoded);
        return trim($encoded);
    }

    public function md5decode($value)
    {
        if (!$value) {
            return false;
        }
        $decoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->key), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($this->key))), "\0");
        return trim($decoded);
    }
}

cLog(pathinfo(__FILE__, PATHINFO_FILENAME) . " loaded.");
