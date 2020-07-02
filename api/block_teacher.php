<?php
	header('Content-Type: application/json');
	require "classes/conn.php";
	require "classes/audit_trail.php";
	require "classes/methods.php";
	require "classes/student.php";
	require "classes/teacher.php";

	$response   = array();
	$data 	    = file_get_contents("php://input");
	$request    = json_decode($data);
	$schoolCode = Methods::validate_string($request->schoolCode);
	$teacher_id = Methods::validate_string($request->teacher_id);

	if(!isset($request->teacher_id) || !isset($request->schoolCode)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Block Teacher. Please Try Again...."
		));
	} else {
		$conn        = $pdo->open();
		$school_name = Student::read_school_name_by_id($schoolCode, $conn);
		$username    = student::read_school_username_by_id($schoolCode, $conn);
		$name        = Teacher::read_teacher($schoolCode, $teacher_id, $conn)->name;

		if(Teacher::change_teacher_status($teacher_id, $schoolCode, 'Inactive', $conn)) {
			array_push($response, array(
				"status"  => "Success",
				"message" => "Teacher Blocked Successfully....."
			));
	        Audit_Trail::create_log($schoolCode, '', 'Blocked Teacher "'.$name.'"', $conn);
		} else {
			array_push($response, array(
				"status"  => "Error",
				"message" => "Teacher Could Not Be Blocked. Please Try Again....."
			));
	        Audit_Trail::create_log($schoolCode, '', 'Tried To Block Teacher "'.$name.'"', $conn);
		}

	    $pdo->close();
	    echo json_encode($response);
	}
?>