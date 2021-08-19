<?php
$title = "تراکنش ها";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 12;

// ----------- get data ------------------------------------------------------------------------------------------------
$result = $action->payment_list();
$counter = 1;
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
                        <h1 class="m-0 text-dark">تراکنش ها</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">تراکنش ها</li>
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
                            <tr class="text-center">
                                <th>ردیف</th>
                                <th>کاربر</th>
                                <th>مبلغ</th>
                                <th>کارت</th>
                                <th>کد پیگیری</th>
                                <th>تاریخ</th>
                                <th>وضعیت</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center">
                                        <?= $action->user_get($row->user_id)->first_name . " " . $action->user_get($row->user_id)->last_name ?>
                                    </td>
                                    <td class="text-center"><?= number_format($row->price) ?></td>
                                    <td class="text-center"><?= $row->cart ?></td>
                                    <td class="text-center"><?= $row->refcode ?></td>
                                    <td class="text-center">
                                        <?= $action->time_to_shamsi($row->created_at) ?>
                                        |
                                        <?= date("H:i:s", $row->created_at) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->status) echo '<span class="badge bg-success">موفق</span>';
                                        else echo '<span class="badge bg-danger">ناموفق</span>';
                                        ?>
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