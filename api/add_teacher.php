<?php
	header('Content-Type: application/json');
	require_once "classes/attendance.php";
	require_once "classes/audit_trail.php";
	require_once "classes/teacher.php";

	$conn 		= $pdo->open();
	$response   = array();
	$data 	    = file_get_contents("php://input");
	$request    = json_decode($data);//die(print_r($request));
	$firstname  = Methods::validate_string(Methods::strtocapital($request->firstname));
	$othername  = Methods::validate_string(Methods::strtocapital($request->othernames));
	$lastname   = Methods::validate_string(Methods::strtocapital($request->lastname));
	$email      = Methods::validate_email(strtolower($request->email));
	$contact    = Methods::validate_string($request->contact);
	$username   = Methods::validate_string($request->username);
	$country_id = Methods::validate_string($request->country_id);
	$schoolCode = Methods::validate_string($request->schoolCode);
	$name       = $firstname . ' ' . $othername . ' ' . $lastname;
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

	if(!isset($request->firstname) || !isset($request->contact) || !isset($request->username) || !isset($request->email)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Add Teacher Details. Please Try Again...."
		));
	} else{
		if(empty($firstname) && empty($lastname) && empty($email) && empty($contact) && empty($username)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "All Fields Required"
			));
		} else if(empty($firstname)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "First Name Required"
			));
		} else if(empty($lastname)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Last Name Required"
			));
		} else if(empty($email)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Email Address Required"
			));
		} else if(!Methods::valid_email_format($email)) {
	        array_push($response, array(
				"status"  => "Warning",
				"message" => "Invalid Email Address Format Entered"
			));
	    } else if(Methods::is_email_address_taken($email, $conn)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Email Address \"" . $email . "\" Already Used. Please Choose Another...."
			));
		} else if(empty($contact)) {
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
		// else if(!Methods::is_prefix_valid($contact)) {
		// 	array_push($response, array(
		// 		"status"  => "Warning",
		// 		"message" => "Contact Number Has Invalid Prefix"
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
			if(Teacher::create_teacher($name, $email, $contact, $country_id, $username, $schoolCode, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Teacher Added Successfully...."
				));
	        	Audit_Trail::create_log($schoolCode, '', 'Added Teacher "'.$firstname.' '.$lastname.'"', $conn);
	        	//Audit_Trail::create_log($school_id, $teacher_id, $activity, $conn)
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Teacher Could Not Be Added. Please Try Again...."
				));
	        	Audit_Trail::create_log($schoolCode, '', 'Tried To Add Teacher "'.$firstname.' '.$lastname.'"', $conn);
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>