<?php
require_once __DIR__ . "/app/functions.php";
$action = new Action();


// check admin access
if ($action->auth()) {
    header("Location: index.php");
    return 0;
}

// ----------- check error ---------------------------------------------------------------------------------------------
$error = 0;
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

$alarm = 0;
if (isset($_SESSION['alarm'])) {
    $alarm = $_SESSION['alarm'];
    unset($_SESSION['alarm']);
}
// ----------- check error ---------------------------------------------------------------------------------------------

// ----------- check login ---------------------------------------------------------------------------------------------
if (isset($_POST['login'])) {

    // get fields
    $username = $action->request('username');
    $password = $action->request('password');

    // send query
    $command = $action->admin_login($username, $password);

    // check errors
    if (!$command) {
        $_SESSION['error'] = 1;
        header("Location: login.php");
    }

    // bye bye :)
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>پنل مدیریت | ورود</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="public/css/adminlte.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="public/plugins/iCheck/square/blue.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="public/css/bootstrap-rtl.min.css">
    <!-- template rtl version -->
    <link rel="stylesheet" href="public/css/custom-style.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <div class="image" >
            <img src="public/img/suppport.png" width="100px" alt="User Image">
        </div>
        <b>فن یاب</b>
    </div>

    <?php if ($error == 1) { ?>
        <div class="alert alert-warning">
            نام کاربری و یا پسورد درست وارد نشده است .
        </div>
    <?php } ?>

    <?php if ($alarm == 1) { ?>
        <div class="alert alert-success">
            شما از سیستم خارج شدید :)
        </div>
    <?php } ?>

    <!-- /.login-logo -->
    <div class="card">

        <div class="card-body login-card-body">
            <p class="login-box-msg">فرم زیر را تکمیل کرده و ورود را بزنید . </p>

            <form method="post" novalidate>
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="نام کاربری" required value="">
                    <div class="input-group-append">
                        <span class="fa fa-user input-group-text"></span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="رمز عبور" value=""
                           required>
                    <div class="input-group-append">
                        <span class="fa fa-lock input-group-text"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <input name="remember" type="checkbox"> مرا به خاطر بسپار !
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" name="login" class="btn btn-danger btn-block btn-flat">ورود</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- iCheck -->
<script src="public/plugins/iCheck/icheck.min.js"></script>
</body>
</html>
