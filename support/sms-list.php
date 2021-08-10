<?php
$title = "پیام های آماده";
require_once __DIR__ . "/app/functions.php";
$action = new Action();


// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for remove , change status
$list_url = "sms-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->sms_list();
// ----------- get data ------------------------------------------------------------------------------------------------

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

    $command = true;

    while ($row = $result->fetch_object()) {
        $slug = $row->slug;
        $text = $action->request($slug);

        var_dump($slug);
        var_dump($text);
        if (! $action->sms_edit($slug, $text))
            $command = false;
    }

    // check errors
    if ($command) {
        $_SESSION['error'] = 0;
    } else {
        $_SESSION['error'] = 1;
    }

    // bye bye :)
    header("Location: $list_url");

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
                        <h1 class="m-0 text-dark">پیام های آماده</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">پیام های آماده</li>
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
                                <h3 class="card-title">ویرایش</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" action="" method="post">
                                <div class="card-body">

                                    <?php while ($row = $result->fetch_object()) { ?>

                                        <div class="form-group">
                                            <label><?= $row->name ?></label>
                                            <input type="text" class="form-control" name="<?= $row->slug ?>"
                                                   value="<?= $row->text ?>">
                                        </div>

                                    <?php } ?>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
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