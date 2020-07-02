<?php
    header('Content-Type: application/json; charset=utf-8');
    require "classes/attendance.php";
    require "classes/audit_trail.php";
    require "classes/email.php";

    $conn         = $pdo->open();
    $School_id    = Methods::validate_string($_POST['school_id']);
    $teacher_id   = Methods::validate_string($_POST['teacher_id']);
    $access_level = Methods::validate_string($_POST['access_level']);
    $recipient    = Methods::validate_array(explode(',', $_POST['recipient']));
    $subject      = Methods::validate_string($_POST['subject']);
    $message      = Methods::validate_string($_POST['message']);
    $response     = array();
    Attendance::after_nine_attedance_marking($School_id, $conn);

	if(!isset($recipient) || !isset($message)) {
        array_push($response, array(
            "status"  => "Error",
            "message" => "Email Could Not Be Sent. Please Try Again....."
        ));
    } else {
        $recipient_string = implode(', ', $recipient);

		if(@$_FILES['attachment'] && @$_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            if(Email::send_bulk_email_attachment($recipient, $subject, $message, $message, $_FILES['attachment']['tmp_name'], $_FILES['attachment']['name'])) {
                array_push($response, array(
                    "status"  => "Success",
                    "message" => "Email Sent Successfully...."
                ));
                Audit_Trail::create_log($School_id, $teacher_id, 'Sent Email "' . $message . '" to Multiple Receivers - "' . trim($recipient_string) . '"', $conn);
                $recipient = array();
            } else {
                array_push($response, array(
                    "status"  => "Error",
                    "message" => "Email Sending Failed. Please Check Internet Connectivity And Try Again...."
                ));
                Audit_Trail::create_log($School_id, $teacher_id, 'Tried To Send Email "' . $message . '" to Multiple Receivers - "' . trim($recipient_string) . '"', $conn);
            }
        } else {
            if(Email::send_bulk_email($recipient, $subject, $message, $message)) {
                array_push($response, array(
                    "status"  => "Success",
                    "message" => "Email Sent Successfully...."
                ));
                Audit_Trail::create_log($School_id, $teacher_id, 'Sent Email "' . $message . '" to Multiple Receivers - "' . trim($recipient_string) . '"', $conn);
                $recipient = array();
            } else {
                array_push($response, array(
                    "status"  => "Error",
                    "message" => "Email Sending Failed. Please Check Internet Connectivity And Try Again...."
                ));
                Audit_Trail::create_log($School_id, $teacher_id, 'Tried To Send Email "' . $message . '" to Multiple Receivers - "' . trim($recipient_string) . '"', $conn);
            }
        }
	}
    
    $pdo->close();
    echo json_encode($response);
?>