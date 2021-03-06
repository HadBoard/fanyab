<?php
$title = "کنترل کاربر";
require_once __DIR__ . "/app/functions.php";
$action = new Action();

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "user.php";
// main url for remove , change status
$list_url = "user-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data from database when action is edit --------------------------------------------------------------
$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $action->request('edit');
    $row = $action->user_get($id);
}
// ----------- get data from database when action is edit --------------------------------------------------------------

// ----------- check error ---------------------------------------------------------------------------------------------
$error = false;
if (isset($_SESSION['error'])) {
    $error = true;
    $error_val = $_SESSION['error'];
    unset($_SESSION['error']);
}
// ----------- check error ---------------------------------------------------------------------------------------------

// ----------- add or edit ---------------------------------------------------------------------------------------------
if (isset($_POST['submit'])) {

    // get fields
    $first_name = $action->request('fname');
    $last_name = $action->request('lname');
    $phone = $action->request('phone');
    $password = $action->request('password');
    $province = $action->request('province');
    $payment = $action->request('payment');
    $warranty = $action->request_date('warranty');
    $support = $action->request_date('support');
    $status = 1;

    // send query
    if ($edit) {
        $command = $action->user_edit($id, $first_name, $last_name, $phone, $password, $province, $payment, $warranty, $support, $status);
    } else {
        $command = $action->user_add($first_name, $last_name, $phone, $password, $province, $payment, $warranty, $support, $status);
    }

    // check errors
    if ($command) {
        $_SESSION['error'] = 0;
    } else {
        $_SESSION['error'] = 1;
    }

    // bye bye :)
    header("Location: $main_url?edit=$command");

}
// ----------- add or edit ---------------------------------------------------------------------------------------------


// ----------- start html :) ------------------------------------------------------------------------------------------
require_once __DIR__ . "/templates/header.php";
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">کاربران</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">کاربران</li>
                            <li class="breadcrumb-item active"><?= ($edit)?"ویرایش":"ثبت" ?></li>
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
                    <!-- left column -->
                    <div class="col-md-6">

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

                        <!-- general form elements -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><?= ($edit)?"ویرایش":"ثبت" ?></h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" action="" method="post" >
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>نام</label>
                                        <input type="text" class="form-control" name="fname"
                                               value="<?= ($edit) ? $row->first_name : '' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>نام خانوادگی</label>
                                        <input type="text" class="form-control" name="lname"
                                               value="<?= ($edit) ? $row->last_name : '' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>شماره تماس</label>
                                        <input type="text" class="form-control" name="phone"
                                               value="<?= ($edit)?$row->phone:'' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>رمز عبور</label>
                                        <input type="text" class="form-control" name="password"
                                               value="<?= ($edit)?$row->password:'' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>استان</label>
                                        <input type="text" class="form-control" name="province"
                                               value="<?= ($edit)?$row->province_id:'' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>وضعیت پرداخت</label>
                                        <select class="form-control" name="payment">
                                            <option value="0" <?= ($edit && $row->payment==0)?"selected":"" ?> >بدهکار</option>
                                            <option value="1" <?= ($edit && $row->payment==1)?"selected":"" ?> >تسویه</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>گارانتی</label>
                                        <input type="text" class="form-control" name="warranty" id="date"
                                               value="<?= ($edit && $row->warranty)?$action->time_to_shamsi($row->warranty):'' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>ضمانت</label>
                                        <input type="text" class="form-control" name="support" id="date1"
                                               value="<?= ($edit && $row->support)?$action->time_to_shamsi($row->support):'' ?>">
                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <a href="<?= $list_url ?>" class="btn btn-secondary">بازگشت به لیست</a>
                                    <button type="submit" name="submit" class="btn btn-primary">ثبت</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->

                    </div>
                    <!--/.col (left) -->
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

<?php
require_once __DIR__ . "/templates/footer.php";
?>