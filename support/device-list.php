<?php
$title = "دستگاه ها";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 13;

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "device.php";
// main url for remove , change status
$list_url = "device-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->device_list();
// ----------- get data ------------------------------------------------------------------------------------------------

// ----------- delete --------------------------------------------------------------------------------------------------
if (isset($_GET['remove'])) {
    $id = $action->request('remove');
    $_SESSION['error'] = !$action->device_remove($id);
    header("Location: $list_url");
    return;
}
// ----------- delete --------------------------------------------------------------------------------------------------

// ----------- change status -------------------------------------------------------------------------------------------
if (isset($_GET['status'])) {
    $id = $action->request('status');
    $_SESSION['error'] = !$action->device_status($id);
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
                        <h1 class="m-0 text-dark">دستگاه ها</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">دستگاه ها</li>
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
                        <a href="<?= $main_url ?>" class="btn btn-danger float-left m-1">ثبت دستگاه</a>
                        <table id="example" class="table table-striped">
                            <thead>
                            <tr class="text-center">
                                <th>ردیف</th>
                                <th>دستگاه</th>
                                <th>مشترک</th>
                                <th>گارانتی</th>
                                <th>پشتیبانی</th>
                                <th>برند</th>
                                <th>سریال</th>
                                <th>کنترل</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center"><?= $row->name ?></td>
                                    <td class="text-center">
                                        <?= $action->user_get($row->user_id)->first_name . " " . $action->user_get($row->user_id)->last_name ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->warranty) {
                                            if ($row->warranty > time()) {
                                                echo '<span class="badge bg-success">';
                                                echo $action->time_to_shamsi($row->warranty);
                                                echo '</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">';
                                                echo $action->time_to_shamsi($row->warranty);
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">ندارد</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->support) {
                                            if ($row->support > time()) {
                                                echo '<span class="badge bg-success">';
                                                echo $action->time_to_shamsi($row->support);
                                                echo '</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">';
                                                echo $action->time_to_shamsi($row->support);
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">ندارد</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?= $row->brand ?></td>


                                    <td class="text-center"><?= $row->serial ?></td>

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