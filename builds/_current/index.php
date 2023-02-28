<?php $pageinfo = [
   "title" => "JFA Home Page",
   "tagline" => null,
   "icon" => "resources/images/favicon.png",
];
/* To Use Local (Dev) 
   * This "index" file sets up the "DEV environment" on the local system,
   * but will not affect the LIVE server.
   ! - do **NOT** copy .localDevOnly folder &contents to LIVE site!
   */
if (file_exists('.localDevOnly/dev-definitions.php')) {
   include_once '.localDevOnly/dev-definitions.php';
   $pageinfo["title"] = "DEV: " . $pageinfo["title"];
   $pageinfo["tagline"] = '<div id="devtagline">This is DEVELOPMENT SITE: host >>' . WEB_HOST . '<< / Local config loaded. </div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="robots" content="index,follow">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href=<?= $pageinfo["icon"]; ?> type="image/png">
   <title><?= $pageinfo["title"]; ?></title>

   <style>
   body {
      margin: 0;
      padding: 0;
   }

   #devtagline {
      width: 100%;
      height: 1rem;
      background-color: red;
      font-size: 0.7rem;
   }

   iframe {
      margin: 0;
      padding: 0;
      width: 100%;
      height: calc(100vh - 1rem - 4px);
      border: none;
      background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text fill="%23FF0000" x="50%" y="10%" font-family="\'Lucida Grande\', sans-serif" font-size="24" text-anchor="middle">JFALANKA.COM is being loaded. Please wait a few moments...</text></svg>') 0px 0px no-repeat;
   }
   </style>

</head>

<body>
   <?php echo $pageinfo["tagline"]; ?>
   <iframe title="frame1"
      srcdoc="<div style='text-align:center'><img src='./resources/images/favicon.png' height=100px><p>Site is still loading...</p></div> "
      src="home.php"
      onload="{this.removeAttribute('srcdoc'); window.document.title = this.contentDocument.title}"></iframe>
</body>

</html>