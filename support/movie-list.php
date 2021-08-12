<?php
$title = "آموزش";
require_once __DIR__ . "/app/functions.php";
$action = new Action();
$_SESSION['active'] = 11;

// ----------- urls ----------------------------------------------------------------------------------------------------
// main url for add , edit
$main_url = "movie.php";
// main url for remove , change status
$list_url = "movie-list.php";
// ----------- urls ----------------------------------------------------------------------------------------------------

// ----------- get data ------------------------------------------------------------------------------------------------
$counter = 1;
$result = $action->movie_list();
// ----------- get data ------------------------------------------------------------------------------------------------

// ----------- delete --------------------------------------------------------------------------------------------------
if (isset($_GET['remove'])) {
    $id = $action->request('remove');
    $_SESSION['error'] = !$action->movie_remove($id);
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
                        <h1 class="m-0 text-dark">آموزش</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">فیلم های آموزشی</li>
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
                        <a href="<?= $main_url ?>" class="btn btn-danger float-left m-1">ثبت فیلم</a>
                        <table id="example" class="table table-striped">
                            <thead>
                            <tr class="text-center">
                                <th>ردیف</th>
                                <th>عنوان</th>
                                <th>مشاهده</th>
                                <th>تاریخ ایجاد</th>
                                <th>کنترل</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_object()) { ?>
                                <tr>
                                    <td class="text-center"><?= $counter++ ?></td>
                                    <td class="text-center"><?= $row->title ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->movie) {
                                                echo '<span class="badge bg-info">';
                                                echo '<a target="_blank" href="';
                                                echo $row->movie;
                                                echo '">مشاهده</a>';
                                                echo '</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $action->time_to_shamsi($row->created_at) ?>
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