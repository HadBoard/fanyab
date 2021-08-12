<?php
$title = "کنترل فیلم";
require_once __DIR__ . "/app/functions.php";
$action = new Action();

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "movie.php";
// main url for remove , change status
$list_url = "movie-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data from database when action is edit --------------------------------------------------------------
$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $action->request('edit');
    $row = $action->movie_get($id);

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
    $title = $action->request('title');
    $cover = $action->request('cover');
    $desc = $action->request('desc');
    $movie = $action->request('movie');

    // send query
    if ($edit) {
        $command = $action->movie_edit($id, $title, $cover, $desc, $movie);
    } else {
        $command = $action->movie_add($title, $cover, $desc, $movie);
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
                        <h1 class="m-0 text-dark">فیلم های آموزشی</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">فیلم های آموزشی</li>
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

                <div class="row">

                    <!-- left column -->
                    <div class="col-md-6">

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
                                        <label>عنوان</label>
                                        <input type="text" class="form-control" name="title"
                                               value="<?= ($edit) ? $row->title : '' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>لینک کاور</label>
                                        <input type="text" class="form-control" name="cover"
                                               value="<?= ($edit) ? $row->cover : '' ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>توضیحات</label>
                                        <textarea name="desc" class="form-control"><?= ($edit)?$row->description:'' ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>لینک فیلم</label>
                                        <input type="text" class="form-control" name="movie"
                                               value="<?= ($edit)?$row->movie:'' ?>">
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

                    <?php if ($edit && $row->cover) { ?>

                    <!-- right column -->
                    <div class="col-md-6">
                        <!-- general form elements -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">کاور</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <img width="100%" src="<?= $row->cover ?>" alt="">
                            </div>
                            <!-- /.card-body -->

                            <?php if ($row->movie) { ?>
                            <div class="card-footer">
                                <a href="<?= $row->movie ?>" class="btn btn-secondary">مشاهده فیلم</a>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->

                    <?php } ?>

                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

<?php
require_once __DIR__ . "/templates/footer.php";
?>