<div class="container-fluid">
    <div class="row">
        <nav class="navbar navbar-inverse square navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand hidden-sm" href="dashboard.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown <?php
                        if ($pid == 1) {
                            echo('active');
                        }
                        ?> ">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span>&nbsp; Basic Entries/Options <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['system_logged_mem_type_id'] != 03 && $_SESSION['system_logged_mem_type_id'] != 04 && $_SESSION['system_logged_mem_type_id'] != 06) { ?>
                                    <?php if ($website['config']['main_cat'] == true) { ?>
                                        <li class="<?php
                                        if ($subid == 1) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=1">Main Entries</a>
                                        </li>
                                    <?php } if ($website['config']['sub_cat'] == true) { ?>
                                        <li class="<?php
                                        if ($subid == 2) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=2">Sub Entries</a>
                                        </li>
                                    <?php } if ($website['config']['brands'] == true) { ?>
                                        <li role="separator" class="divider"></li>
                                        <li class="<?php
                                        if ($subid == 3) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=3">Brands</a>
                                        </li>
                                    <?php } if ($website['config']['units'] == true) { ?>
                                        <li role="separator" class="divider"></li>
                                        <li class="<?php
                                        if ($subid == 4) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=4">Units</a>
                                        </li>
                                    <?php } if ($website['config']['apps'] == true) { ?>
                                        <li role="separator" class="divider"></li>
                                        <li class="<?php
                                        if ($subid == 5) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=5">Appearance</a>
                                        </li>
                                    <?php } if ($website['config']['main_slider'] == true) { ?>
                                        <li role="separator" class="divider"></li>
                                        <li class="<?php
                                        if ($subid == 6) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=6">Slider Images</a>
                                        </li>
                                    <?php } if ($website['config']['news'] == true) { ?>
                                        <li role="separator" class="divider"></li>
                                        <li class="<?php
                                        if ($subid == 8) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=8">News & Events</a>
                                        </li>
                                    <?php } ?>
                                    <li class="<?php
                                    if ($subid == 7) {
                                        echo('active');
                                    }
                                    ?>">
                                        <a href="dashboard.php?page=master_entries&subpage=7">Contact Info</a>
                                    </li>
                                    <?php if ($website['config']['comments'] == true) { ?>
                                        <li class="<?php
                                        if ($subid == 9) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=9">Comment List</a>
                                        </li>
                                    <?php } if ($website['config']['pages'] == true) { ?>
                                        <li class="<?php
                                        if ($subid == 10) {
                                            echo('active');
                                        }
                                        ?>">
                                            <a href="dashboard.php?page=master_entries&subpage=10">Pages</a>
                                        </li>
                                    <?php } ?>
                                    <li class="<?php
                                    if ($subid == 11) {
                                        echo('active');
                                    }
                                    ?>">
                                        <a href="dashboard.php?page=master_entries&subpage=11">SEO</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="dropdown <?php
                        if ($pid == 2) {
                            echo('active');
                        }
                        ?> ">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-pushpin"></span>&nbsp; Posts <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['system_logged_mem_type_id'] != 03 && $_SESSION['system_logged_mem_type_id'] != 04 && $_SESSION['system_logged_mem_type_id'] != 06) { ?>
                                    <li class="<?php
                                    if ($subid == 1) {
                                        echo('active');
                                    }
                                    ?>">
                                        <a href="dashboard.php?page=post_entries&subpage=1"><span class="glyphicons glyphicons-list-alt"></span>&nbsp; Post Entries</a>
                                    </li>
                                    <li class="<?php
                                    if ($subid == 2) {
                                        echo('active');
                                    }
                                    ?>">
                                        <a href="dashboard.php?page=post_entries&subpage=2"><span class="glyphicons glyphicons-search"></span>&nbsp; Post Search</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li><a href="../uploads/.index.php" class="iframe"><span class="glyphicon glyphicon-file"></span>&nbsp; File Manager</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp; Log Out</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span>&nbsp; Settings <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="my_account.php" class="iframe"><span class="glyphicons glyphicons-user"></span>&nbsp; My Account</a></li>
                                <li><a href="my_password.php" class="iframe"><span class="glyphicons glyphicons-user-lock"></span>&nbsp; Change Password</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="logout.php"><span class="glyphicons glyphicons-log-out"></span>&nbsp; Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>