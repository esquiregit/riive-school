<?php
    header('Content-Type: application/json');
    require "classes/audit_trail.php";
    require "classes/attendance.php";

    $conn         = $pdo->open();
    $data         = file_get_contents("php://input");
    $request      = json_decode($data);
    $School_id    = Methods::validate_string($request->school_id);
    $teacher_id   = Methods::validate_string($request->teacher_id);
    $access_level = Methods::validate_string($request->access_level);
    $recipient    = Methods::validate_array($request->recipient);
    $message      = Methods::validate_string($request->message);
    $response     = array();
    Attendance::after_nine_attedance_marking($School_id, $conn);

	if(!isset($recipient) || !isset($message)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "SMS Could Not Be Sent. Please Try Again....."
		));
	} else{
		$recipient_string = implode(', ', $recipient);
		$retVal           = SMS::send_sms($message, $recipient);

		if($retVal === 200) {
        	array_push($response, array(
				"status"  => "Success",
				"message" => "SMS Sent Successfully...."
			));
            Audit_Trail::create_log($School_id, $teacher_id, 'Sent SMS "' . $message . '" to "' . $recipient_string . '"', $conn);
        } else if($retVal === 400) {
            array_push($response, array(
				"status"  => "Error",
				"message" => "SMS Sending Failed. Please Check Internet Connectivity And Try Again...."
			));
            Audit_Trail::create_log($School_id, $teacher_id, 'Tried To Send SMS "' . $message . '" to "' . $recipient_string . '" But There Was No Internet', $conn);
        } else if($retVal === 500) {
            array_push($response, array(
				"status"  => "Error",
				"message" => "SMS Sending Failed. Insuffucient SMS Balance...."
			));
            Audit_Trail::create_log($School_id, $teacher_id, 'Tried To Send SMS "' . $message . '" to "' . $recipient_string . '" But There Was Insuffucient SMS Balance', $conn);
        }
	}

    $pdo->close();
    echo json_encode($response);
?>