<?php
	require_once "conn.php";
	require_once "audit_trail.php";
	require_once "methods.php";
	require_once "teacher.php";

	if(!isset($_POST['id']) ||!isset($_POST['teacher_id']) || !isset($_POST['classs'])) {
		header("Location: ../logout.php");
	} else{
		$conn     	= $pdo->open();
		$id         = Methods::validate_string($_POST['id']);
		$teacher_id = Methods::validate_string($_POST['teacher_id']);
		$class   	= Methods::validate_string($_POST['classs']);
		$response 	= array();
		$full_class = ($class == 1 || $class == 2 || $class == 3 || $class == 4 || $class == 5 || $class == 6) ? 'Class ' . $class : $class;

		if($teacher_id == 'default' && $class == 'default') {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "All Fields Required"
			));
		} else if($teacher_id == 'default') {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Select Teacher"
			));
		} else if($class == 'default') {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Select Class"
			));
		} else if(Teacher::has_class_been_assigned($class, $conn) && !Teacher::has_this_class_been_assigned($id, $class, $conn)) {
			array_push($response, array(
				"status"  => "Failed",
				"message" => "<strong>" . $full_class . "</strong> Has Been Assigned To Another Teacher"
			));
		} else {
			if(Teacher::update_assigned_teacher_class($id, $teacher_id, $class, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Teacher/Class Assignment Updated Successfully"
				));
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Teacher/Class Assignment Could Not Be Updated. Please Try Again"
				));
			}
	    }

	    $pdo->close();
	    echo json_encode($response);
	}
?>