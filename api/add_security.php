<?php
	header('Content-Type: application/json');
	require_once "classes/conn.php";
	require_once "classes/attendance.php";
	require_once "classes/audit_trail.php";
	require_once "classes/security.php";
	require_once "classes/student.php";

	$conn 		= $pdo->open();
	$response   = array();
	$data 	    = file_get_contents("php://input");
	$request    = json_decode($data);//die(print_r($request));
	$firstname  = Methods::validate_string(Methods::strtocapital($request->firstname));
	$othername  = Methods::validate_string(Methods::strtocapital($request->othername));
	$lastname   = Methods::validate_string(Methods::strtocapital($request->lastname));
	$contact    = Methods::validate_string($request->contact);
	$username   = Methods::validate_string($request->username);
	$schoolCode = Methods::validate_string($request->schoolCode);
	$name       = $firstname . ' ' . $othername . ' ' . $lastname;
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

	if($firstname || $contact || $username || $schoolCode) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Add Security Details. Please Try Again...."
		));
	} else{
		if(empty($contact)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Contact Number Required"
			));
		} else if(!ctype_digit($contact)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Contact Number Must Contain Only Numbers"
			));
		}
		// else if(strlen($contact) != 10) {
		// 	array_push($response, array(
		// 		"status"  => "Warning",
		// 		"message" => "Contact Number Must Contain Ten (10) Digits"
		// 	));
		// }
		else if(empty($username)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Username Required"
			));
		} else if(Methods::is_username_taken($username, $conn)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Username \"" . $username . "\" Already Used. Please Choose Another...."
			));
		} else {
			$school_name = Student::read_school_name_by_id($schoolCode, $conn);
			$username    = Student::read_school_username_by_id($schoolCode, $conn);
			if(Security::create_security($name, $contact, $username, $schoolCode, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Security Added Successfully...."
				));
	        	Audit_Trail::create_log($schoolCode, '', 'Added Security "'.$firstname.' '.$lastname.'"', $conn);
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Security Could Not Be Added. Please Try Again...."
				));
	        	Audit_Trail::create_log($schoolCode, '', 'Tried To Add Security "'.$firstname.' '.$lastname.'"', $conn);
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>