<!DOCTYPE html>
<html lang="en">

<head>
    <title>RiiVe - School Login</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/logo.png">
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/toastr/toastr.min.css" rel="stylesheet">
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body id="body" class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <div id="main-wrapper">
        <div class="page-wrappe">
            <div class="container-fluid">
                <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="loggin">
                    <strong><i class='fa fa-spinner fa-spin'></i> Logging You In. Please Wait....</strong>
                </div>
                <div class="row">
                    <div class="form-div">
                        <div class="card border-dark">
                            <div class="card-title">
                                <h3>Log In</h3>
                                <hr />
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" placeholder="Username" name="username" id="username" />
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" name="login" id="login" class="btn btn-info btn-rounded">Log In</button>
                                            <a href="password-recovery.php" class="btn btn-link">Forgotten Password?</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/lib/toastr/toastr.min.js"></script>
    <script src="js/lib/toastr/toastr.init.js"></script>
    <script src="js/convs.js"></script>
    <script src="js/login.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>