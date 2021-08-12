<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="https://fanyab.com/service" class="nav-link">پنل کاربران</a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3" action="https://www.google.com/search" method="get">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="جستجو در گوگل"
                   aria-label="Search" name="q">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-bell"></i>
                <?php
                if (isset($action) && $action->my_log_counter()) {
                    echo '<span class="badge badge-warning navbar-badge">';
                    echo $action->my_log_counter();
                    echo '</span>';
                }
                ?>

            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <span class="dropdown-item dropdown-header">
                    <?= $action->my_log_counter(); ?>
                    نوتیفیکیشن
                </span>
                <div class="dropdown-divider"></div>

                <?php
                if (isset($action) && $action->my_log_counter() ) {
                    $res = $action->my_log_list(3);
                    while ($roww = $res->fetch_object()) {
                        ?>

                        <a class="dropdown-item">
                            <i class="fa fa-file ml-2"></i>
                            <?php
                            echo $action->action_get($roww->action_id)->text;
                            if ($roww->variable)
                                echo " : " . ($roww->variable);
                            ?>
                            <span class="float-left text-muted text-sm">
                        <?= date("H:i", $roww->created_at) ?>
                    </span>
                        </a>

                    <?php }
                } ?>

                <div class="dropdown-divider"></div>
                <a href="admin-log-list.php?me" class="dropdown-item dropdown-footer">مشاهده همه نوتیفیکیشن</a>
            </div>
        </li>
        <!-- Logout icon -->
        <li class="nav-item">
            <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i></a>
        </li>

    </ul>
</nav>