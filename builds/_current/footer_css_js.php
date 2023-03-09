<!-- Masonry & imageload plugin -->

<?php
// $thisRoot = WEB_HOST;
$thisRoot = ".";
?>

<script src="<?php echo $thisRoot ?>/resources/js/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo $thisRoot ?>/resources/js/masonry.pkgd.min.js"></script>
<script>
$(document).ready(function() {
   /* may conflict with slider_thumb */
   var $grid = $('.m-grid').imagesLoaded(function() {
      /* init Masonry after all images have loaded */
      $grid.masonry({
         itemSelector: '.m-grid-item'
      });
   });
});
</script>
<!-- Body Items Animation JSS -->
<script src='<?php echo $thisRoot; ?>/resources/css3-animate-it-master/js/css3-animate-it.js'></script>
<script>
$(document).ready(function() {
   $.doTimeout(2500, function() {
      $('.repeat.go').removeClass('go');

      return true;
   });
   $.doTimeout(2520, function() {
      $('.repeat').addClass('go');
      return true;
   });

});
</script>
<!-- END -->
<script>
$(function() {
   /* jquery page scroll on same page with hash */
   $(".scrollme").click(function() {
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname ==
         this.hostname) {
         var target = $(this.hash);
         target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
         if (target.length) {
            $('html, body').animate({
               scrollTop: target.offset().top - 40
            }, 1000);
            return false;
         }
      }
   });
   /* --- End ----------- */
   /* jquery page scroll on new page with hash */
   $('html, body').animate({
      scrollTop: $(window.location.hash + "-anchor").offset().top - 80
   }, 2000);
   return false;
   /* --- End ----------- */
});
</script>
<!-- Match Height -->
<script type="text/javascript"
   src="<?php echo $thisRoot; ?>/resources/jquery-match-height-master/jquery.matchHeight.js"></script>
<script>
$(function() {
   $(function() {
      $('.match-item').matchHeight({
         byRow: true,
         property: 'height',
         target: null,
         remove: false
      });
   });
});
</script>
<!-- Custom Select -->
<link rel="stylesheet"
   href="<?php echo $thisRoot ?>/<?php echo $website['boostrap_folder']; ?>/custom_select/dist/css/bootstrap-select.min.css">
<script
   src="<?php echo $thisRoot ?>/<?php echo $website['boostrap_folder']; ?>/custom_select/dist/js/bootstrap-select.min.js"
   type="text/javascript" charset="utf-8"></script>

<!-- Go to www.addthis.com/dashboard to customize your tools -->
<!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58a01ca7b75fcd9c"></script> -->
<?= cLog(pathinfo(__FILE__, PATHINFO_FILENAME) . ' loaded.');?>