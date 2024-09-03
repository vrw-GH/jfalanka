<!-- care to include where needed, could be hidden by previous divs -->
<?php
$app['info']['developer']['lead'] = "Victor Wright";
$app['info']['developer']['web'] = ["wrightsdesk", "http://wrightsdesk.com"];
$app['info']['developer']['descr'] = "Redesign(2023): The Leisure Co.";
$app['info']['developer']['email'] = "developer@wrightsdesk.com";
$app['info']['developer']['phone'] = "+49 176 4677 4278";
?>

<style>
   .devinfo {
      display: none;
      z-index: 999;
      position: absolute;
      right: 30px;
      bottom: 100px;
      width: max-content;
      max-width: 40%;
      height: auto;
      background-color: rgba(45, 45, 45, 0.45);
      color: greenyellow;
      font-size: smaller;
      padding: 10px;
      border-radius: 5px;
      cursor: alias;
   }

   .devinfo>a {
      color: greenyellow;
   }
</style>

<div class="devinfo" onclick="{document.getElementsByClassName('devinfo')[0].style.display='none'; }">
   Version:&nbsp;<?= $app['info']['version'] ?>/<?= $pageinfo['mode'] ?>
   <br>
   License:&nbsp;<?= $app['info']['licence'] ?>/<?= $app['info']['title'] ?>
   <br>
   👨‍💻&nbsp;<?= $app['info']['developer']['lead'] ?>
   <br>
   🕸&nbsp;&nbsp;<a href="<?= $app['info']['developer']['web'][1] ?>" target="_blank" title="visit <?= $app['info']['developer']['web'][1] ?>"><?= $app['info']['developer']['web'][0] ?></a>
   <br>
   📧&nbsp;<a href="mailto:<?= $app['info']['developer']['email'] ?>" title="open in email app"><?= $app['info']['developer']['email'] ?></a>
   <br>
   📞&nbsp;<a href="call:<?= $app['info']['developer']['phone'] ?>" title="open in phone app"><?=
                                                                                                $app['info']['developer']['phone'] ?></a>
</div>