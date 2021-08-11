<?php
$title = "پیام های ارسالی";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 10;

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->sms_log_list();
// ----------- get data ------------------------------------------------------------------------------------------------

// ----------- start html :) ------------------------------------------------------------------------------------------
require_once __DIR__ . "/templates/header.php";
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">پیام های ارسالی</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">پیام های ارسالی</li>
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
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example" class="table table-striped">
                            <thead>
                            <tr  class="text-center">
                                <th>ردیف</th>
                                <th>فرستنده</th>
                                <th>گیرنده</th>
                                <th>متن</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center">
                                        <?= $action->admin_get($row->admin_id)->first_name . " " . $action->admin_get($row->admin_id)->last_name ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $action->user_get($row->user_id)->first_name . " " . $action->user_get($row->user_id)->last_name ?>
                                    </td>
                                    <td class="text-center"><?= $row->text ?></td>
                                    <td class="text-center">
                                        <?= $action->time_to_shamsi($row->send_at) . " | " . date("H:i:s", $row->send_at) ?>
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