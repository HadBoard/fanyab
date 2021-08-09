<?php
$title = "لاگ مدیران";
require_once __DIR__ . "/app/functions.php";
$action = new Action();

// check admin access
//if (!$action->admin()->access) {
//    header("Location: index.php");
//    return 0;
//}

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "log-admin.php";
// main url for remove , change status
$list_url = "log-admin.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->admin_log_list();
// ----------- get data ------------------------------------------------------------------------------------------------

// ----------- delete --------------------------------------------------------------------------------------------------
if (isset($_GET['view'])) {
    $id = $action->request('view');
    $_SESSION['error'] = !$action->admin_log_view($id);
    header("Location: $list_url");
    return;
}
// ----------- delete --------------------------------------------------------------------------------------------------

// ----------- delete all --------------------------------------------------------------------------------------------------
if (isset($_GET['all'])) {
    $_SESSION['error'] = !$action->admin_log_view_all();
    header("Location: $list_url");
    return;
}
// ----------- delete all --------------------------------------------------------------------------------------------------

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
                        <h1 class="m-0 text-dark">لاگ مدیران</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">لاگ مدیران</li>
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
                        <a href="log-admin.php?all" class="btn btn-danger float-left m-1">حذف همه</a>
                        <table id="example" class="table table-striped">
                            <thead>
                            <tr  class="text-center">
                                <th>ردیف</th>
                                <th>مدیر</th>
                                <th>نام کاربری</th>
                                <th>آی پی</th>
                                <th>فعالیت</th>
                                <th>تاریخ</th>
                                <th>خوانده شده</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center">
                                        <?= $action->admin_get($row->admin_id)->first_name . " " . $action->admin_get($row->admin_id)->last_name ?>
                                    </td>
                                    <td class="text-center"><?= $action->admin_get($row->admin_id)->username ?></td>
                                    <td class="text-center"><?= $row->ip ?></td>
                                    <td class="text-center">
                                        <?php
                                        echo $action->action_get($row->action_id)->text ;
                                        if($row->variable)
                                            echo " : " . ($row->variable);
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $action->time_to_shamsi($row->created_at) ?>
                                        |
                                        <?= date("H:i:s", $row->created_at) ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $list_url ?>?view=<?= $row->id ?>">
                                            <i class="fa fa-reply-all"></i>
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