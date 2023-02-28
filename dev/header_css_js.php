<?php
// $thisRoot = WEB_ROOT;
$thisRoot = ".";
?>

<?php if (SITE_SEO != 'basic' && $seo_social == true) {
   // echo '<script>console.log("Site Seo (' . SITE_SEO . ')' . $seo_social . '")</script>';
?>

   <!-- Canonical URL -->
   <link rel="canonical" href="<?php echo $canonical_url ?>">

   <!-- FB sharing START -->
   <meta property="fb:profile_id" content="<?php echo $row['fb_id']; ?>">
   <meta property="og:type" content="website">
   <meta property="og:title" content="<?php echo $config['seo']['seo_title']; ?>">
   <meta property="og:image" content="<?php echo $config['seo']['og_img'] ?>">
   <meta property="og:description" content="<?php $config['seo']['og_tw_dscp']; ?>">
   <meta property="og:url" content="<?php echo $canonical_url ?>">
   <meta property="og:site_name" content="<?php echo $config['seo']['og_site_name']; ?>">
   <!-- END -->

   <!-- Twitter Card -->
   <meta name="twitter:card" content="summary_large_image">
   <meta name="twitter:site" content="<?php echo $config['seo']['tw_site']; ?>">
   <meta name="twitter:creator" content="<?php echo $config['seo']['tw_creator']; ?>">
   <meta name="twitter:title" content="<?php echo $config['seo']['seo_title']; ?>">
   <meta name="twitter:description" content="<?php echo $config['seo']['og_tw_dscp']; ?>">
   <meta name="twitter:image" content="<?php echo $config['seo']['og_img']; ?>">
   <!-- END -->

   <!-- Google+ Publisher -->
   <link rel="publisher" href="<?php echo $config['seo']['google_publisher']; ?>">

<?php } ?>

<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/bootstrap-3.3.7/css/bootstrap.min.css" type="text/css" media="all">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css">

<!-- Error css -->
<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/css/Errors/validationEngine.jquery.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/css/Errors/template.css" type="text/css" />

<!-- Jquery core plugins -->
<script type="text/javascript" src="<?php echo $thisRoot ?>/<?php echo $website['jquery_min_js']; ?>" charset="utf-8">
</script>
<script type="text/javascript" src="<?php echo $thisRoot ?>/<?php echo $website['jquery_migrate_js']; ?>" charset="utf-8">
</script>
<script type="text/javascript" src="<?php echo $thisRoot ?>/<?php echo $website['jquery_migrate_lower_js']; ?>" charset="utf-8">
</script>

<!-- Jquery UI plugins -->
<!-- <link rel="stylesheet" href="<?php echo $thisRoot ?>/<?php echo $website['jquery_ui_css']; ?>" /> -->
<!-- <link rel="stylesheet" href="<?php echo $thisRoot ?>/<?php echo $website['jquery_ui_theme_css']; ?>" /> -->
<script src="<?php echo $thisRoot ?>/<?php echo $website['jquery_ui_js']; ?>" charset="utf-8"></script>

<!-- Boostrap Js -->
<script type="text/javascript" src="<?php echo $thisRoot ?>/<?php echo $website['boostrap_folder']; ?>/js/bootstrap.min.js" charset="utf-8">
</script>
<!-- Handle jQuery plugin naming conflict between jQuery UI and Bootstrap  -->
<script>
   $.widget.bridge('uibutton', $.ui.button);
   $.widget.bridge('uitooltip', $.ui.tooltip);
</script>

<!-- Error Js -->
<script src="<?php echo $thisRoot ?>/resources/js/Languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo $thisRoot ?>/resources/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
</script>

<!-- Pagination Default -->
<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/pagination_static/css/pagination.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/pagination_static/css/grey.css" type="text/css" />

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo $thisRoot ?>/resources/fancybox/lib/jquery.mousewheel-3.0.6.pack.js">
</script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo $thisRoot ?>/resources/fancybox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $thisRoot ?>/resources/fancybox/source/jquery.fancybox.css" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo $thisRoot ?>/resources/fancybox/source/helpers/jquery.fancybox-buttons.css" />
<script type="text/javascript" src="<?php echo $thisRoot ?>/resources/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>

<!-- Body Items Animation CSS -->
<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/css3-animate-it-master/css/animations.css" type="text/css">

<!--[if lte IE 9]>
      <link rel='stylesheet' href='<?php echo $thisRoot ?>/resources/css3-animate-it-master/css/animations-ie-fix.css'>
<![endif]-->

<!-- hover effects -->
<link rel="stylesheet" href="<?php echo $thisRoot ?>/resources/hover-master/css/hover-min.css" media="all">


<?php
if (SITE_SEO != 'basic' && $seo_google == true) {
   echo $config['seo']['google_analytics'];
}
?>

<script>
   $(document).ready(function() {
      $(document).on('click', '.mega-dropdown', function(e) {
         e.stopPropagation();
      });
   });
   $(function() {
      /* Fancy Box  */
      $('.fancybox').fancybox({
         helpers: {
            overlay: {
               closeClick: false,
               /* prevents closing when clicking Oouside fancybox */
               locked: false,
               /* prevents scroll */
               'autoScale': true,
               'autoDimensions': true,
               type: 'iframe'
            }
         }
      });
      $('.fancybox-buttons').fancybox({
         openEffect: 'none',
         closeEffect: 'none',
         prevEffect: 'none',
         nextEffect: 'none',
         closeBtn: false,
         helpers: {
            overlay: {
               locked: false
            },
            title: {
               type: 'inside'
            },
            buttons: {}
         },
         afterLoad: function() {
            this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' +
               this.title : '');
         }
      });
      /* Tooltip */
      $('[data-toggle="tooltip"]').tooltip();

      /* scroll button START ------- */
      $('body').append(
         '<div id="toTop" class="btn btn-info"><span class="glyphicon glyphicon-chevron-up"></span></div>');
      $(window).scroll(function() {
         if ($(this).scrollTop() >= 100) {
            $('#toTop').fadeIn();
         } else {
            $('#toTop').fadeOut();
         }
      });
      $('#toTop').click(function() {
         $("html, body").animate({
            scrollTop: 0
         }, 1000);
         return false;
      });
      /* scroll button END --------- */

   });

   /* menu top fix if the menu on bottom of the header  --------- */
   $(function() {
      var nav = $('.menu-top');
      var conta = $('#container');
      $(window).scroll(function() {
         if ($(this).scrollTop() > 200) {
            nav.addClass("navbar-fixed-top");
            conta.addClass("menu-margin");
         } else {
            nav.removeClass("navbar-fixed-top");
            conta.removeClass("menu-margin");
         }
      });
   });
   /* menu top fix End  --------- */
</script>

<script>
   // console.log('header_css_js loaded.');
</script>