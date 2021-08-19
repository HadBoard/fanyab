<?php
$active = (isset($_SESSION['active'])?$_SESSION['active']:0)
// last_active -> 13
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://hadboard.ir" target="_blank" class="brand-link">
        <img src="public/img/HadBoard.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr">
        <div style="direction: rtl">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="public/img/logo.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="https://fanyab.com/" target="_blank" class="d-block">فن یاب</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="index." class="nav-link <?= ($active == 1)?"active":"" ?>">
                            <i class="nav-icon fa fa-dashboard"></i>
                            <p>داشبورد</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="admin-list.php" class="nav-link <?= ($active == 3)?"active":"" ?>">
                            <i class="nav-icon fa fa-user-secret"></i>
                            <p>مدیران</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="admin-log-list.php" class="nav-link <?= ($active == 4)?"active":"" ?>">
                            <i class="nav-icon ion ion-stats-bars"></i>
                            <p>لاگ مدیران</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="user-list.php" class="nav-link <?= ($active == 5)?"active":"" ?>">
                            <i class="nav-icon fa fa-user"></i>
                            <p>کاربران</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="request-list.php" class="nav-link <?= ($active == 6)?"active":"" ?>">
                            <i class="nav-icon fa fa-hand-spock-o"></i>
                            <p>
                                در خواست ها
                                <?php
                                if (isset($action) && $counter = $action->request_status_counter(1)) {
                                    echo '<span class="right badge badge-danger">'.$counter.'</span>';
                                }
                                ?>
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="status-list.php" class="nav-link <?= ($active == 7)?"active":"" ?>">
                            <i class="nav-icon fa fa-star"></i>
                            <p>وضعیت ها</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="user-log-list.php" class="nav-link <?= ($active == 8)?"active":"" ?>">
                            <i class="nav-icon fa fa-tasks"></i>
                            <p>لاگ کاربران</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="device-list.php" class="nav-link <?= ($active == 13)?"active":"" ?>">
                            <i class="nav-icon fa fa-deviantart"></i>
                            <p>
                                دستگاه ها
                                <?php
                                if (isset($action) && $counter = $action->table_counter('tbl_device')) {
                                    echo '<span class="right badge badge-secondary">'.$counter.'</span>';
                                }
                                ?>
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="sms-list.php" class="nav-link <?= ($active == 9)?"active":"" ?>">
                            <i class="nav-icon fa fa-list"></i>
                            <p>پیام های آماده</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="sms-log-list.php" class="nav-link <?= ($active == 10)?"active":"" ?>">
                            <i class="nav-icon fa fa-send"></i>
                            <p>پیام های ارسالی</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="movie-list.php" class="nav-link <?= ($active == 11)?"active":"" ?>">
                            <i class="nav-icon fa fa-file-movie-o"></i>
                            <p>
                                فیلم های آموزشی
                                <?php
                                if (isset($action) && $counter = $action->table_counter('tbl_movie')) {
                                    echo '<span class="right badge badge-secondary">'.$counter.'</span>';
                                }
                                ?>
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="payment-list.php" class="nav-link <?= ($active == 12)?"active":"" ?>">
                            <i class="nav-icon fa fa-dollar"></i>
                            <p>تراکنش ها</p>
                        </a>
                    </li>



                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
    </div>
    <!-- /.sidebar -->
</aside>