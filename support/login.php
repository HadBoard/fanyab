<?php
require_once __DIR__."/app/functions.php";
$action = new Action();


// check admin access
if ($action->auth()) {
    header("Location: index.php");
    return 0;
}

// ----------- check error ---------------------------------------------------------------------------------------------
$error = 0;
if (isset($_SESSION['error'])) {
    $error = 1;
    $error_val = $_SESSION['error'];
    unset($_SESSION['error']);
}
// ----------- check error ---------------------------------------------------------------------------------------------

// ----------- check login ---------------------------------------------------------------------------------------------
if (isset($_POST['sub1'])) {

    // get fields
    $user = $action->request('user');
    $pass = $action->request('pass');

    // send query
    $command = $action->admin_login($user, $pass);

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
        <a href="https://hadboard.ir" target="_blank"><b>هادبورد</b></a>
    </div>

    <?php
    if ($flash->hasMessages())
        $flash->display();
    ?>

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">فرم زیر را تکمیل کرده و ورود را بزنید . </p>

            <form method="post" novalidate>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="پست الکترونیک" required value="<?= old('email') ?>">
                    <div class="input-group-append">
                        <span class="fa fa-user input-group-text"></span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="رمز عبور" value="<?= old('password') ?>" required>
                    <div class="input-group-append">
                        <span class="fa fa-lock input-group-text"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <input name="remember" type="checkbox">    مرا به خاطر بسپار !
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
