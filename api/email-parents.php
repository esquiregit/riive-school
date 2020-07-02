<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/audit_trail.php";
    require_once "classes/email.php";
    require_once "classes/methods.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Email';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];

    $conn           = $pdo->open();
    @$teacher_class = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);
    $pdo->close();

    if(!$teacher_class && $_SESSION['riive_school_access_level'] == 'Teacher') {
        echo "<script>alert('You Have Not Been Assigned A Class');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $error = $error_message = $success = $success_message = $subject = $message = '';
        $parent_array           = array();
        $parent_array_string    = '';

        if(isset($_POST['submit'])) {
            $subject            = Methods::validate_string($_POST['subject']);
        	$message            = Methods::validate_string($_POST['message']);

            if(empty($subject) || empty($message)) {
            	$error 		     = true;
            	$error_message   = 'At Least Subject And Message Required';
            } else {
        		$parents_contacts = ($_SESSION['riive_school_access_level'] == 'School Admin') ? Email::get_parents_emails($conn) : Email::read_class_students_parents($conn);
                if($parents_contacts){
            		foreach($parents_contacts as $parent) {
            			array_push($parent_array, $parent->email);
                        $parent_array_string .= $parent->email . ', ';
                    }

                    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                        if(Email::send_bulk_email_attachment($parent_array, $subject, $message, $message, $_FILES['attachment']['tmp_name'], $_FILES['attachment']['name'])) {
                            $success         = true;
                            $error           = false;
                            $success_message = "Email Sent Successfully";
                            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Sent Email <strong>(' . $message . ')</strong> to Multiple Receivers - <strong>[' . trim($parent_array_string) . ']</strong>', $conn);
                            $error           = $error_message = $subject = $message = '';
                            $contact_number2 = array();
                        } else {
                            $error           = true;
                            $error_message   = 'Email Sending Failed. Please Check Your Internet Try Again....';
                            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Send Email <strong>(' . $message . ')</strong> to Multiple Receivers - <strong>[' . trim($parent_array_string) . ']</strong>', $conn);
                        }
                    } else {
                        if(Email::send_bulk_email($parent_array, $subject, $message, $message)) {
                            $success         = true;
                            $error           = false;
                            $success_message = "Email Sent Successfully";
                            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Sent Email <strong>(' . $message . ')</strong> to Parents - <strong>[' . trim($parent_array_string) . ']</strong>', $conn);
                            $error           = $error_message = $subject = $message = '';
                            $contact_number2 = array();
                        } else {
                            $error           = true;
                            $error_message   = 'Email Sending Failed. Please Check Your Internet Try Again....';
                            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Send Email <strong>(' . $message . ')</strong> to Parents - <strong>[' . trim($parent_array_string) . ']</strong>', $conn);
                        }
                    }
                }
            }
        }
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Email Parents</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form" enctype="multipart/form-data">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="send-email">
                        <strong>Sending Email. Please Wait....</strong>
                    </div>
            		<div class="row medium-form-0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" class="form-control" name="subject" value="<?php echo $subject; ?>" placeholder="Subject" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachment</label>
                                <input type="file" class="form-control" name="attachment" value="<?php echo $attachment; ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" name="message" value="<?php echo $message; ?>" placeholder='<?php echo "Type Email Here..."; ?>'><?php echo $message; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button type="submit" name="emailparents" id="emailparents" class="btn btn-rounded btn-info"><i class="fa fa-paper-plane"></i> Send Email</button>
                            </div>
                        </div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>