<?php include 'sub_menu_var.php'; ?>
<?php if ($website['config']['posts'] == true) { ?>
    <div class="col-xs-12 col-sm-3 col-xs-pdn-both-0">
        <div id="left-menu">
            <ul class="listnone">
                <?php if ($_SESSION['system_logged_mem_type_id'] != 03 && $_SESSION['system_logged_mem_type_id'] != 04 && $_SESSION['system_logged_mem_type_id'] != 06) { ?>
                    <li class="<?php
                    if ($subid == 1) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=1">Post Entries</a>
                    </li>
                    <li class="<?php
                    if ($subid == 2) {
                        echo('active');
                    }
                    ?>">
                        <a href="dashboard.php?page=<?php echo $_GET['page']; ?>&subpage=2">Post Search</a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>
    <div class="col-xs-12 voffset-2 visible-xs">&nbsp;</div>
    <div class="col-xs-12 col-sm-9 col-xs-pdn-both-0">
        <div id="right-panel">
            <h1>Posts</h1>
            <div id="ajaxbox">
                <?php
                if ($subid == 1) {
                    include 'post.php';
                } else if ($subid == 2) {
                    include 'post_search.php';
                } else {
                    include 'post.php';
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>