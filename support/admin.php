<?php
$title = "کنترل مدیر";
require_once __DIR__ . "/app/functions.php";
$action = new Action();

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

// ----------- get data from database when action is edit --------------------------------------------------------------
$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $action->request('edit');
    $row = $action->admin_get($id);
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
    $username = $action->request('username');
    $password = $action->request('password');
    $access = 0;
    $status = 1;
    // send query
    if ($edit) {
        $command = $action->admin_edit($id, $first_name, $last_name, $phone, $username, $password, $status, $access);
    } else {
        $command = $action->admin_add($first_name, $last_name, $phone, $username, $password, $status, $access);
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
                        <h1 class="m-0 text-dark">مدیران</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">مدیران</li>
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
                                        <label>نام کاربری</label>
                                        <input type="text" class="form-control" name="username"
                                               value="<?= ($edit)?$row->username:'' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>رمز عبور</label>
                                        <input type="text" class="form-control" name="password"
                                               value="<?= ($edit)?$row->password:'' ?>">
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