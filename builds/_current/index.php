<?php $pageinfo = [
   "title" => "JFA Home Page",
   "tagline" => null,
   "icon" => "resources/images/favicon.png",
   "mode" => "DEV",
   "webhost" => "www.wrightsdesk.com/JFA/jfalanka/",
];

/* To Use Local (Dev) 
   * This "index" file sets up the "DEV environment" on the local system,
   * but will not affect the LIVE server.
   ! - do **NOT** copy .localDevOnly folder &contents to LIVE site!
   */
// if (file_exists('.localDevOnly/dev-definitions.php')) {
//    include_once '.localDevOnly/dev-definitions.php';
// }

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
      background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text fill="%23FF0000" x="50%" y="10vh" font-family="\'Lucida Grande\', sans-serif" font-size="24" text-anchor="middle"><?= $pageinfo["title"]; ?> is being loaded. Please wait a few moments...</text><text fill="%11FF0000" x="50%" y="20vh" font-family="\'Lucida Grande\', sans-serif" font-size="20" text-anchor="middle"> is being loaded. Please wait a few moments...</text></svg>') 0px 0px no-repeat;
   }
   </style>

</head>

<body>
   <?php echo $pageinfo["tagline"]; ?>
   <iframe title="frame1"
      srcdoc="<div style='top:100;text-align:center'><img src='./resources/images/favicon.png' height=100px><p>Site is still loading...</p></div> "
      src='home.php?pageinfo=<?php echo json_encode($pageinfo) ?>' src1="home.php" onload="{this.removeAttribute('srcdoc'); window.document.title =
      this.contentDocument.title}"></iframe>
</body>

</html>