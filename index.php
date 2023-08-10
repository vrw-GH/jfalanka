<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="../../resources/favicon-apache.png" type="image/png">
   <link rel="stylesheet" href="../../common/htmlstyles.css">
   <!-- <link rel="stylesheet" href="../../common/style.php" media="screen"> -->

   <title>PROJ: JFA Lanka</title>

   <style>
   iframe[name=siteView] {
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f1e2e250;
      border: 1px solid #f1e2e230;
      border-radius: 0 7px 0 0;
      margin: 3px 3px 0;
      padding: 0;
      width: calc(100% - 8px);
      height: calc(100vh - 1.7rem - 4px);
      min-height: min-content;
      min-width: 280px;
      max-width: calc(100% - 8px);
      resize: horizontal;
   }

   #frametitle {
      position: absolute;
      margin-top: -0.15rem;
      right: 2rem;
      background-color: rgba(255, 255, 255, 0.5);
      padding: 0 2px 0;
      border-radius: 8px;
      font: italic 0.4rem arial;
      color: grey;
   }

   ul {
      display: inline-flex;
      min-width: max-content;
      list-style-position: inside;
      list-style-type: " ☼ ";
      padding: 0;
      margin: 0;
   }

   .live,
   .live>a {
      color: blue;
   }
   </style>

</head>

<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/includes/Parsedown.php';
$Parser = new Parsedown(); // should be in local-docroot/includes
$readfile = file_get_contents('docs/README.md');
$readmeMD = $Parser->text($readfile);
$readmeMD = '<div style="display:flex; justify-content:center; "><div>' . $readmeMD . '</div></div>';
// echo $readmeMD;
?>

<?php
$subdir = glob('*', GLOB_ONLYDIR);
$site = ["JFA Lanka", "jfalanka.com"];
?>

<body>
   <span>
      <em>Project: </em><img src="./dev/resources/images/favicon.png" height=14px><strong><?= $site[0] ?></strong>
      &emsp;
      <a href="data:text/html;charset=utf-8,<html><body>
         <?= htmlspecialchars($readmeMD) ?>
         </body></html>" target="siteView" title="README.md Page">👁️‍🗨️</a>

      <ul>
         <?php
         foreach ($subdir as $dir) {
            echo '<li><a href="' . $dir . '" target="siteView" title="View Folder">' . $dir . '</a></li>';
         };
         ?>
&emsp;&emsp;
         <li class="live"><a href="http://www.<?= $site[1] ?>" title="<?= $site[1] ?>💡Ctrl-click - new page"
               target="siteView" rel="noopener"><small>🌎</small></a></li>
      </ul>
   </span>


   <div id="frametitle">Site View</div>
   <iframe name="siteView" src="docs/README.md" loading="lazy" title="siteView" srcdocxx="Loading..."
      srcdoc='<?= $readmeMD ?>' onload="this.removeAttribute('srcdocxx')" ondblclick="{
      this.style.width = 'calc(100% - 8px)';
      }">
   </iframe>


</body>

</html>