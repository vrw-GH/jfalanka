<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="robots" content="index,follow">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="resources/images/favicon.ico" type="image/png">
   <title><?= $_SERVER['HTTP_REFERER']; ?></title>

   <style>
   body {
      margin: 0;
      padding: 0;
   }

   iframe {
      margin: 0;
      padding: 0;
      width: 100%;
      height: calc(100vh - 1rem - 4px);
      border: none;
      background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text fill="%23FF0000" x="50%" y="10vh" font-family="\'Lucida Grande\', sans-serif" font-size="24" text-anchor="middle">Site is being loaded.</text></svg>') 0px 0px no-repeat;
   }
   </style>

</head>

<body>
   <iframe title="frame1"
      srcdoc="<div style='margin-top:100px;text-align:center'><img src='./resources/images/favicon.png' height=100px><p>please wait a few moments...</p></div>"
      src='modules/home.php'
      onload="{this.removeAttribute('srcdoc'); window.document.title = this.contentDocument.title}">
   </iframe>
</body>

</html>