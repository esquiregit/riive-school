<?php
	header('Content-Type: application/json');
	require "classes/conn.php";
	require "classes/audit_trail.php";
	require "classes/attendance.php";
	require "classes/teacher.php";

	$conn         = $pdo->open();
	$response     = array();
	$data 	      = file_get_contents("php://input");
	$request      = json_decode($data);//die(print_r($request));
	$id 		  = Methods::validate_string($request->id);
	@$class       = Methods::validate_string($request->class);
	$name         = Methods::validate_string($request->name);
	$student      = Methods::validate_string($request->student);
	$student_id   = Methods::validate_string($request->student_id);
	$pickup_code  = Methods::validate_string($request->pickUpCode);
	$access_level = Methods::validate_string($request->access_level);
	$teacher_id   = Methods::validate_string($request->teacher_id);
	$schoolCode   = Methods::validate_string($request->schoolCode);
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

	if(!isset($id) || !isset($schoolCode) || !isset($student_id) || !isset($access_level)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Clock Student Out. Please Try Again...."
		));
	} else {

		if(Attendance::clock_out_student($id, $schoolCode, $student_id, $pickup_code, $conn)) {
			array_push($response, array(
				"status"  => "Success",
				"message" => "Student Clocked Out Successfully....."
			));
	        Audit_Trail::create_log($schoolCode, $teacher_id, 'Clocked "'.$student.'" Out', $conn);
		} else {
			array_push($response, array(
				"status"  => "Error",
				"message" => "Student Could Not Be Clocked Out. Please Try Again....."
			));
	        Audit_Trail::create_log($schoolCode, $teacher_id, 'Tried To Clock "'.$student.'" Out', $conn);
		}

	    $pdo->close();
	}
	echo json_encode($response);
?>