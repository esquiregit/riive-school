<?php
	header('Content-Type: application/json');
	require_once "classes/conn.php";
	require_once "classes/audit_trail.php";
	require_once "classes/methods.php";
	require_once "classes/student.php";
	require_once "classes/teacher.php";

	$response   = array();
	$data 	    = file_get_contents("php://input");
	$request    = json_decode($data);//die(print_r($request));
	$teacher_id = Methods::validate_string($request->teacher_id);
	$schoolCode = Methods::validate_string($request->schoolCode);
	$class   	= Methods::validate_string($request->class);
	$response 	= array();

	if(!isset($request->teacher_id) || !isset($request->class)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Assign Teacher To Class. Please Try Again...."
		));
	} else{
		$conn        = $pdo->open();
		$school_name = Student::read_school_name_by_id($schoolCode, $conn);
		$username    = student::read_school_username_by_id($schoolCode, $conn);
		$name        = Teacher::read_teacher($schoolCode, $teacher_id, $conn)->name;
		$full_class  = ($class == 1 || $class == 2 || $class == 3 || $class == 4 || $class == 5 || $class == 6) ? 'Class ' . $class : $class;

		if(Teacher::has_class_been_assigned($schoolCode, $class, $conn)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "\"" . $full_class . "\" Has Been Assigned Already...."
			));
        	Audit_Trail::create_log($schoolCode, '', 'Tried To Assign Teacher "'.$name.'" To "'.$full_class.'" But The Class Is Assigned', $conn);
		} else {
			if(Teacher::assign_teacher($schoolCode, $teacher_id, $class, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Teacher Assigned To Class Successfully...."
				));
        		Audit_Trail::create_log($schoolCode, '', 'Assigned Teacher "'.$name.'" To "'.$full_class.'"', $conn);
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Teacher Could Not Be Assigned To Class. Please Try Again...."
				));
        		Audit_Trail::create_log($schoolCode, '', 'Tried To Assign Teacher "'.$name.'" To "'.$full_class.'"', $conn);
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>