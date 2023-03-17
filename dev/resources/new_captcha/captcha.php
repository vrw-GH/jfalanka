<?php
// https://code-boxx.com/simple-php-captcha-script/#sec-download

//* NOTE: **$iteration** is used for multiple captchas in single "form" - ie for seperate categories

// RELOADING THE CAPTCHA
// “Move” the captcha creation to a separate mycap.php script – <?php require "1-captcha.php"; $PHPCAP->prime(); $PHPCAP->draw();
// Modify 2-form.php, replace the captcha with an empty <div id="cap">
// Use Javascript fetch to load/reload the captcha.
// var cap = () => fetch("mycap.php").then(res=>res.text()).then(txt=>document.getElementById("cap").innerHTML = txt);
// window.addEventListener("load", cap);
// <input type="button" value="Reload Captcha" onclick="cap()">

class Captcha
{
   // (A) CAPTCHA SETTINGS
   public $length; // default 4 if none passed in
   private $capW = 200; // captcha width
   private $capH = 80; // captcha height
   private $capF = __DIR__ . DIRECTORY_SEPARATOR . "TuffyBold.ttf"; // captcha font
   private $capFS = 24; // captcha font size   
   private $capB = __DIR__ . DIRECTORY_SEPARATOR . "CapBack.jpg"; // captcha background
   private $period = 600; // Validity period in ms
   private $char = "abcdefghijkmnpqrstuvwxyz0123456789ABCDEFGHJKLMNPQRSTUVWXYZ"; // ohne loIO

   // (B) AUTO START SESSION
   function __construct($value = 4)
   {
      if (session_status() == PHP_SESSION_DISABLED) {
         exit("Sessions disabled");
      }
      if (session_status() == PHP_SESSION_NONE) {
         session_start();
      }
      $this->length = ($value < 2) ? 1 : $value;
   }

   // (C) PRIME THE CAPTCHA - GENERATE RANDOM STRING IN SESSION
   function prime($iteration = 0)
   {

      $max = strlen($this->char) - 1;
      $_SESSION["cexpire" . $iteration] = strtotime("now") + $this->period;
      $_SESSION["captcha" . $iteration] = "";
      for ($i = 1; $i <= $this->length; $i++) {
         $_SESSION["captcha" . $iteration] .= substr($this->char, rand(0, $max), 1);
      }
   }

   // (D) DRAW THE CAPTCHA IMAGE
   function draw($iteration = 0, $mode = 0)
   {
      // (D1) FUNKY BACKGROUND IMAGE
      if (!isset($_SESSION["captcha" . $iteration])) {
         exit("Captcha not primed");
      };
      $captcha = imagecreatetruecolor($this->capW, $this->capH);
      list($bx, $by) = getimagesize($this->capB);
      $bx = ($bx - $this->capW) < 0 ? 0 : rand(0, ($bx - $this->capW));
      $by = ($by - $this->capH) < 0 ? 0 : rand(0, ($by - $this->capH));
      imagecopy($captcha, imagecreatefromjpeg($this->capB), 0, 0, $bx, $by, $this->capW, $this->capH);

      // (D2) RANDOM TEXTBOX POSITION
      $ta = rand(-20, 20); // random angle
      $tc = imagecolorallocate($captcha, rand(120, 255), rand(120, 255), rand(120, 255)); // random text color
      $ts = imagettfbbox($this->capFS, $ta, $this->capF, $_SESSION["captcha" . $iteration]);
      if ($ta >= 0) {
         $tx = rand(abs($ts[6]), $this->capW - (abs($ts[2]) + abs($ts[6])));
         $ty = rand(abs($ts[5]), $this->capH);
      } else {
         $tx = rand(0, $this->capW - (abs($ts[0]) + abs($ts[4])));
         $ty = rand(abs($ts[7]), abs($ts[7]) + $this->capH - (abs($ts[3]) + abs($ts[7])));
      }
      imagettftext($captcha, $this->capFS, $ta, $tx, $ty, $tc, $this->capF, $_SESSION["captcha" . $iteration]);

      // (D3) OUTPUT CAPTCHA
      if ($mode == 0) {
         ob_start();
         imagepng($captcha);
         $ob = base64_encode(ob_get_clean());
         echo "<img src='data:image/png;base64,$ob'>";
      } else {
         header("Content-type: image/png");
         imagepng($captcha);
         imagedestroy($captcha);
      }
   }

   // (E) VERIFY CAPTCHA
   function verify($check, $iteration = 0)
   {
      if (!isset($_SESSION["captcha" . $iteration])) {
         exit("Captcha not primed");
      }

      cLog($_SESSION["captcha" . $iteration]);
      cLog($check);

      // if ($check == $_SESSION["captcha"]) {
      if (($_SESSION["cexpire" . $iteration] > strtotime("now")) && $check == $_SESSION["captcha" . $iteration]) {
         unset($_SESSION["captcha" . $iteration]);
         return true;
      } else {
         return false;
      }
   }
}

// (F) CAPTCHA OBJECT
// $PHPCAP = new Captcha();

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");
