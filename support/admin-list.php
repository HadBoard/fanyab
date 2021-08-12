<?php
$title = "مدیران";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 3;

// check admin access
if (!$action->admin()->access) {
    header("Location: index.php");
    return 0;
}

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "admin.php";
// main url for remove , change status
$list_url = "admin-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->admin_list();
// ----------- get data ------------------------------------------------------------------------------------------------

// ----------- delete --------------------------------------------------------------------------------------------------
if (isset($_GET['remove'])) {
    $id = $action->request('remove');
    $_SESSION['error'] = !$action->admin_remove($id);
    header("Location: $list_url");
    return;
}
// ----------- delete --------------------------------------------------------------------------------------------------

// ----------- change status -------------------------------------------------------------------------------------------
if (isset($_GET['status'])) {
    $id = $action->request('status');
    $_SESSION['error'] = !$action->admin_status($id);
    header("Location: $list_url");
    return;
}
// ----------- change status -------------------------------------------------------------------------------------------

// ----------- check error ---------------------------------------------------------------------------------------------
$error = false;
if (isset($_SESSION['error'])) {
    $error = 1;
    $error_val = $_SESSION['error'];
    unset($_SESSION['error']);
}
// ----------- check error ---------------------------------------------------------------------------------------------

// ----------- start html :) ------------------------------------------------------------------------------------------
require_once __DIR__ . "/templates/header.php";
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">مدیران</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">مدیران</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <?php if ($error) {
                    if ($error_val) { ?>
                        <div class="alert alert-danger">
                            عملیات ناموفق بود .
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-success text-right">
                            عملیات موفق بود .
                        </div>
                    <?php }
                } ?>

                <!-- Small boxes (Stat box) -->
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="excel.php?admin" class="btn btn-secondary float-left m-1">دریافت خروجی</a>
                        <a href="<?= $main_url ?>" class="btn btn-danger float-left m-1">ثبت مدیر</a>
                        <table id="example" class="table table-striped">
                            <thead>
                            <tr  class="text-center">
                                <th>ردیف</th>
                                <th>نام و نام خانوادگی</th>
                                <th>نام کاربری</th>
                                <th>نوع</th>
                                <th>آخرین بازدید</th>
                                <th>وضعیت</th>
                                <th>کنترل</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center"><?= $row->first_name . " " . $row->last_name ?></td>
                                    <td class="text-center"><?= $row->username ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->access) echo '<span class="badge bg-info">مدیرکل</span>';
                                        else echo '<span class="badge bg-secondary">مدیرجزء</span>';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?= ($row->last_login > 0) ? $action->time_to_shamsi($row->last_login) : "عدم ورود" ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $list_url ?>?status=<?= $row->id ?>">
                                            <?php
                                            if ($row->status) echo '<span class="badge bg-success">فعال</span>';
                                            else echo '<span class="badge bg-danger">غیرفعال</span>';
                                            ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $main_url ?>?edit=<?= $row->id ?>">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        |
                                        <a href="<?= $list_url ?>?remove=<?= $row->id ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

<?php
require_once __DIR__ . "/templates/footer.php";
?>