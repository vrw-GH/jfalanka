<?php include 'sub_menu_var.php'; ?>
<div class="col-xs-12 col-sm-3 col-xs-pdn-both-0">
    <div id="left-menu">
        <ul class="listnone">
            <?php if ($_SESSION['system_logged_mem_type_id'] != 03 && $_SESSION['system_logged_mem_type_id'] != 04 && $_SESSION['system_logged_mem_type_id'] != 06) { ?>
                <?php if ($website['config']['main_cat'] == true) { ?>
                    <li class="<?php
                    if ($subid == 1) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=1">Main Entries</a>
                    </li>
                <?php } if ($website['config']['sub_cat'] == true) { ?>
                    <li class="<?php
                    if ($subid == 2) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=2">Sub Entries</a>
                    </li>
                <?php } if ($website['config']['brands'] == true) { ?>
                    <li class="<?php
                    if ($subid == 3) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=3">Brands</a>
                    </li>
                <?php } if ($website['config']['units'] == true) { ?>
                    <li class="<?php
                    if ($subid == 4) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=4">Units</a>
                    </li>
                <?php } if ($website['config']['apps'] == true) { ?>
                    <li class="<?php
                    if ($subid == 5) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=5">Appearance</a>
                    </li>
                <?php } if ($website['config']['main_slider'] == true) { ?>
                    <li class="<?php
                    if ($subid == 6) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=6">Slider Images</a>
                    </li>
                <?php } if ($website['config']['news'] == true) { ?>
                    <li class="<?php
                    if ($subid == 8) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=8">News & Events</a>
                    </li>
                <?php } ?>
                <li class="<?php
                if ($subid == 7) {
                    echo('active');
                }
                ?>">
                    <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=7">Contact Info</a>
                </li>
                <?php if ($website['config']['comments'] == true) { ?>
                    <li class="<?php
                    if ($subid == 9) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=9">Comment List</a>
                    </li>
                <?php } if ($website['config']['pages'] == true) { ?>
                    <li class="<?php
                    if ($subid == 10) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=10">Pages</a>
                    </li>
                <?php } ?>
                <li class="<?php
                if ($subid == 11) {
                    echo('active');
                }
                ?>">
                    <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=11">SEO</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="col-xs-12 voffset-2 visible-xs">&nbsp;</div>
<div class="col-xs-12 col-sm-9 col-xs-pdn-both-0">
    <div id="right-panel">
        <h1>Master Entries</h1>
        <div id="ajaxbox">
            <?php
            if ($website['config']['main_cat'] == true && $subid == 1) {
                include './item_main_category.php';
            } else if ($website['config']['sub_cat'] == true && $subid == 2) {
                include './item_sub_category.php';
            } else if ($website['config']['brands'] == true && $subid == 3) {
                include './item_brands.php';
            } else if ($website['config']['units'] == true && $subid == 4) {
                include './item_units.php';
            } else if ($website['config']['apps'] == true && $subid == 5) {
                include './item_appearance.php';
            } else if ($website['config']['main_slider'] == true && $subid == 6) {
                include 'slider_img.php';
            } else if ($subid == 7) {
                include './contact_info.php';
            } else if ($website['config']['news'] == true && $subid == 8) {
                include './news_events.php';
            } else if ($website['config']['comments'] == true && $subid == 9) {
                include './comment_list.php';
            } else if ($website['config']['pages'] == true && $subid == 10) {
                include './pages.php';
            } else if ($subid == 11) {
               include './seo.php';
            } else {
                include './contact_info.php';
            }
            ?>
        </div>
    </div>
</div>