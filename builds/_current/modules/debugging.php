<?php

function cLog($var = null)
{
   // escape if live site
   if (
      $_SERVER['REMOTE_ADDR'] != '127.0.0.1'
      && $_SERVER['REMOTE_ADDR'] != 'localhost'
      && $_SERVER['REMOTE_ADDR'] != '::1'
   ) {
      unset($var);
      return;
   };

   // else show debugging & console logs
   if (!$var) {
      // echo "isnull.";
      echo
      "<script>
         console.log('No var!');
         </script>";
      unset($var);
      return;
   };

   $varname = array_search($var, $GLOBALS, true);
   if ($varname == null) {
      echo
      "<script>      
         console.log('$var'); 
         </script>";
   } else {
      echo
      "<script>      
         console.log('var: \$$varname:', " . json_encode($var) . "); 
         </script>";
   };

   unset($var, $varname);
   return;
}

cLog(pathinfo(__FILE__, PATHINFO_FILENAME) . ' loaded. Remote-Addr: ' . $_SERVER['REMOTE_ADDR']);
