<?php
    if(!isset($_GET['tg4F7rdr']) || !isset($_GET['hYtg65l']))
        header('Location: index.php');

    $reset_code = $_GET['tg4F7rdr'];
    $user_id    = $_GET['hYtg65l'];
    $access     = $_GET['Uyh67Yhgt'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>RiiVe - School Password Reset</title>

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
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4">
                        <div class="card border-dark">
                            <div class="card-title">
                                <h3>Password Reset</h3>
                                <hr />
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="New Password" />
                                            <input type="hidden" name="reset_code" id="reset_code" value="<?php echo $reset_code; ?>" />
                                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="mr" id="mr" value="<?php echo $access; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Confirm Password</label>
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter New Password" />
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" name="change" id="change" class="btn btn-info btn-rounded">Change</button>
                                            <a href="index.php" class="btn btn-link">Login</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="">
                        </div>
                    </div>
                    <div class="col-lg-4"></div>
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
    <script src="js/change_password.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>