<?php
$title = "داشبورد";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 1;
require_once __DIR__ . "/templates/header.php";
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">داشبورد</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">داشبورد</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $action->admin_counter() ?></h3>
                                <p>مدیران سیستم</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user-secret"></i>
                            </div>
                            <a href="admin-list.php" class="small-box-footer">اطلاعات بیشتر <i
                                        class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $action->user_counter() ?></h3>
                                <p>کاربران</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user"></i>
                            </div>
                            <a href="user-list.php" class="small-box-footer">اطلاعات بیشتر <i
                                        class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $action->request_counter() ?></h3>
                                <p>درخواست ها</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-hand-spock-o"></i>
                            </div>
                            <a href="request_list.php" class="small-box-footer">اطلاعات بیشتر <i
                                        class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>65</h3>

                                <p>بازدید جدید</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">اطلاعات بیشتر <i
                                        class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa fa-files-o"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">
                                درخواست های
                                <?= $action->status_get(2)->title ?>
                            </span>
                                <span class="info-box-number"><?= $action->request_status_counter(2) ?></span>
                            </div>
                            </a>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fa fa-files-o"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-text">
                                درخواست های
                                <?= $action->status_get(3)->title ?>
                            </span>
                                <span class="info-box-number"><?= $action->request_status_counter(3) ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-files-o"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-text">
                                درخواست های
                                <?= $action->status_get(4)->title ?>
                            </span>
                                <span class="info-box-number"><?= $action->request_status_counter(4) ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-files-o"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-text">
                                درخواست های
                                <?= $action->status_get(5)->title ?>
                            </span>
                                <span class="info-box-number"><?= $action->request_status_counter(5) ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                </div>
                <!-- /.row -->
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-secondary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">مدیریت فایل</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">اطلاعات مورد نیاز برای ورود به مدیر فایل :</p>

                                <div class="row">
                                    <div class="col">
                                        <p class="card-text">نام کاربری : superuser</p>
                                        <p class="card-text">رمز عبور : superuser</p>
                                    </div>
                                    <div class="col text-left">
                                        <a class="btn btn-app" href="https://fanyab.com/filemanager" target="_blank">
                                            <i class="fa fa-file-archive-o"></i> ورود
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card card-secondary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">وب اپلیکیشن</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">از این پس میتوانید برای دانلود وب اپلیکیشن از لینک زیر استفاده نمایید . ( نسخه 1.0.0 )</p>
                                <div class="text-left">
                                    <a href="Support.apk" class="btn btn-app" target="_blank" download="">
                                        <i class="fa fa-download"></i> دانلود
                                    </a>
                                </di>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

<?php include_once "templates/footer.php"; ?>