<?php
	header('Content-Type: application/json');
	// require_once "classes/conn.php";
	require_once "classes/attendance.php";
	require_once "classes/audit_trail.php";
	require_once "classes/security.php";
	require_once "classes/student.php";

	$conn 		= $pdo->open();
	$response   = array();
	$data 	    = file_get_contents("php://input");
	$request    = json_decode($data);//die(print_r($request));
	$id  		= Methods::validate_string(Methods::strtocapital($request->id));
	$firstname  = Methods::validate_string(Methods::strtocapital($request->firstname));
	$othername  = Methods::validate_string(Methods::strtocapital($request->othername));
	$lastname   = Methods::validate_string(Methods::strtocapital($request->lastname));
	$contact    = Methods::validate_string($request->contact);
	$schoolCode = Methods::validate_string($request->schoolCode);
	$name       = $firstname . ' ' . $othername . ' ' . $lastname;
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

	if(empty($firstname) || empty($contact) || empty($contact) || empty($schoolCode)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Update Security Details. Please Try Again...."
		));
	} else {
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
		/*else if(strlen($contact) != 10) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Contact Number Must Contain Ten (10) Digits"
			));
		}*/
		else {
			$school_name = Student::read_school_name_by_id($schoolCode, $conn);
			$username    = Student::read_school_username_by_id($schoolCode, $conn);
			if(Security::update_security($id, $schoolCode, $name, $contact, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Security Updated Successfully...."
				));
	        	Audit_Trail::create_log($schoolCode, '', 'Updated Security "'.$firstname.' '.$lastname.'"', $conn);
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Security Could Not Be Updated. Please Try Again...."
				));
	        	Audit_Trail::create_log($schoolCode, '', 'Tried To Update Security "'.$firstname.' '.$lastname.'"', $conn);
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>