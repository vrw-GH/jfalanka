<div class="ccc">&nbsp;</div>
<?php
/* define carousel type */
if ($caro_type == 'main_cat') {
    $caro_type = 'main_cat';
} else if ($caro_type == 'sub_cat') {
    $caro_type = 'sub_cat';
} else if ($caro_type == 'brand') {
    $caro_type = 'brand';
} else if ($caro_type == 'post_main_cat') {
    $caro_type = 'post_main_cat';
} else if ($caro_type == 'post_sub_cat') {
    $caro_type = 'post_sub_cat';
} else if ($caro_type == 'post_brand') {
    $caro_type = 'post_brand';
} else if ($caro_type == 'post_featured') {
    $caro_type = 'post_featured';
} else if ($caro_type == 'post_discounted') {
    $caro_type = 'post_discounted';
} else {
    $caro_type = 'post';
}
/* Define items */
if (empty($caro_lg_itms)) {
    $caro_lg_itms = 4;
}
if (empty($caro_md_itms)) {
    $caro_md_itms = 3;
}
if (empty($caro_sm_itms)) {
    $caro_sm_itms = 2;
}
if (empty($caro_xs_itms)) {
    $caro_xs_itms = 1;
}

/* Define variables */
if (empty($caro_details)) {
    $caro_details = false;
}
if (empty($caro_link)) {
    $caro_link = false;
}
if (empty($caro_link_text)) {
    $caro_link_text = 'View Prodcuts';
}
if (empty($caro_link_popup)) {
    $caro_link_popup = false;
}
if (empty($caro_link_popup_type)) {
    $caro_link_popup_type = '';
}
if (empty($caro_link_text_popup)) {
    $caro_link_text_popup = '';
}
if (empty($caro_structure)) {
    $caro_structure = 'product';
}

/* Define post types */
if (empty($caro_cat_code)) {
    $caro_cat_code = 0;
}
if (empty($caro_sub_cat_code)) {
    $caro_sub_cat_code = 0;
}
if (empty($caro_brand_code)) {
    $caro_brand_code = 0;
}
if (empty($post_name_limit)) {
    $post_name_limit = 60;
}
if (empty($post_details_limit)) {
    $post_details_limit = 250;
}
?>
<script>
    function findBootstrapEnvironment() {
        var envs = ['xs', 'sm', 'md', 'lg'];

        $el = $('<div>&nbsp</div>');
        $el.appendTo($('body'));

        for (var i = envs.length - 1; i >= 0; i--) {
            var env = envs[i];

            $el.addClass('hidden-' + env);
            if ($el.is(':hidden')) {
                $el.remove();
                return env
            }
        }
    }
    $(function () {
        ab = findBootstrapEnvironment();
        if (ab == 'xs') {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_xs.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_xs_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        } else if (ab == 'sm') {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_sm.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_sm_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        } else if (ab == 'md') {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_md.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_md_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        } else {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_lg.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_lg_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        }
    });
    $(window).resize(function () {
        ab = findBootstrapEnvironment();
        if (ab == 'xs') {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_xs.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_xs_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        } else if (ab == 'sm') {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_sm.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_sm_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        } else if (ab == 'md') {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_md.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_md_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        } else {
            $('.ccc').load('<?php echo WEB_HOST ?>/carousel_lg.php', {caro_type: '<?php echo $caro_type; ?>', caro_details: '<?php echo $caro_details; ?>', caro_link: '<?php echo $caro_link; ?>', caro_link_text: '<?php echo $caro_link_text; ?>', caro_link_popup: '<?php echo $caro_link_popup; ?>', caro_link_popup_type: '<?php echo $caro_link_popup_type; ?>', caro_link_text_popup: '<?php echo $caro_link_text_popup; ?>', caro_structure: '<?php echo $caro_structure; ?>', caro_items: '<?php echo $caro_lg_itms; ?>', caro_cat_code: <?php echo $caro_cat_code; ?>, caro_sub_cat_code: <?php echo $caro_sub_cat_code; ?>, caro_brand_code: <?php echo $caro_brand_code; ?>, post_name_limit:<?php echo $post_name_limit; ?>, post_details_limit:<?php echo $post_details_limit; ?>});
        }
    });
</script>
