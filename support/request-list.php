<?php
$title = "درخواست ها";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 6;

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "request.php";
// main url for remove , change status
$list_url = "request-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->request_list();
// ----------- get data ------------------------------------------------------------------------------------------------

// ----------- delete --------------------------------------------------------------------------------------------------
if (isset($_GET['remove'])) {
    $id = $action->request('remove');
    $_SESSION['error'] = !$action->request_remove($id);
    header("Location: $list_url");
    return;
}
// ----------- delete --------------------------------------------------------------------------------------------------

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
                        <h1 class="m-0 text-dark">درخواست ها</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">درخواست ها</li>
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
                        <table id="example" class="table table-striped">
                            <thead>
                            <tr  class="text-center">
                                <th>ردیف</th>
                                <th>درخواست دهنده</th>
                                <th>تاریخ درخواست</th>
                                <th>نوع درخواست</th>
                                <th>گارانتی</th>
                                <th>پشتیبانی</th>
                                <th>وضعیت</th>
                                <th>کنترل</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center">
                                        <?= $action->user_get($row->user_id)->first_name . " " . $action->user_get($row->user_id)->last_name ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $action->time_to_shamsi($row->date) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $action->request_type_get($row->type) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($action->user_get($row->user_id)->warranty) {
                                            if($action->user_get($row->user_id)->warranty > time()) {
                                                echo '<span class="badge bg-success">';
                                                echo $action->time_to_shamsi($action->user_get($row->user_id)->warranty);
                                                echo '</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">';
                                                echo $action->time_to_shamsi($action->user_get($row->user_id)->warranty);
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">ندارد</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($action->user_get($row->user_id)->support) {
                                            if($action->user_get($row->user_id)->support > time()) {
                                                echo '<span class="badge bg-success">';
                                                echo $action->time_to_shamsi($action->user_get($row->user_id)->support);
                                                echo '</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">';
                                                echo $action->time_to_shamsi($action->user_get($row->user_id)->support);
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">ندارد</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?= $action->status_get($row->status_id)->title ?></td>
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