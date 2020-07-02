<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/audit_trail.php";
    require_once "classes/methods.php";
    require_once "classes/sms.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'SMS';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];

    $conn           = $pdo->open();
    @$teacher_class = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);
    $pdo->close();

    if(!$teacher_class && $_SESSION['riive_school_access_level'] == 'Teacher') {
        echo "<script>alert('You Have Not Been Assigned A Class');</script>";
        echo "<script>location = '$previous_page';</script>";
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">SMS Parents</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="send-sms">
                        <strong>Sending SMS. Please Wait....</strong>
                    </div>
            		<div class="row small-form-0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" name="message" value="<?php echo $message; ?>" placeholder='<?php echo "Type SMS Here..."; ?>'></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button type="submit" name="smsparents" id="smsparents" class="btn btn-rounded btn-info"><i class="fa fa-paper-plane"></i> Send SMS</button>
                            </div>
                        </div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>