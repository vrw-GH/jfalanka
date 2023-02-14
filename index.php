<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="../../resources/favicon-apache.png" type="image/x-icon">
   <link rel="stylesheet" href="../../common/htmlstyles.css">
   <!-- <link rel="stylesheet" href="../../common/style.php" media="screen"> -->

   <title>JFA Lanka</title>

   <style>
      /* a {
         text-decoration: none;
      } */

      iframe[name=siteView] {
         background-color: #f1e2e250;
         border: 1px solid #f1e2e230;
         margin: 3px 3px 0;
         padding: 0;
         width: calc(100% - 8px);
         height: calc(100vh - 1.9rem);
         min-height: min-content;
      }

      #frametitle {
         position: absolute;
         margin-top: -0.5rem;
         right: 2rem;
         background-color: white;
         padding: 2px;
         border-radius: 8px;
         font: italic 0.7rem arial;
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
$subdir = glob('*', GLOB_ONLYDIR);
$site = ["JFA Lanka", "jfalanka.com"];
?>

<body>
   <span><em>Project:</em> <strong> <?= $site[0] ?></strong>
      <!-- &emsp; -->
      <a href="README.md" target="siteView" title="Readme Page">👁️‍🗨️</a>
      &emsp;
      <!-- <small><em>Local:</em></small> -->
      <ul>
         <!-- <em><small>Online: </em></small> -->

         <?php
         foreach ($subdir as $dir) {
            echo '<li><a href="' . $dir . '" target="siteView" title="View Folder">' . $dir . '</a></li>';
         };
         ?>
         <span class="live">&nbsp; ( <small><em>Live:&nbsp;</em></small></span>
         <li class="live"><a href="http://www.<?= $site[1] ?>" title="💡Ctrl-click - new page" target="siteView" rel="noopener"><?= $site[1] ?></a></li>
         <span class="live">&nbsp;)</span>
      </ul>
   </span>


   <div id="frametitle">Site View</div>
   <iframe name="siteView" src="README.md" loading="lazy" title="siteView"></iframe>

</body>

</html>