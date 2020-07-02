<?php
	header('Content-Type: application/json');
	require "classes/conn.php";
	require "classes/attendance.php";

	$response       = array();
	$data 	        = file_get_contents("php://input");
	$request        = json_decode($data);//die(print_r($request));
	$schoolCode     = Methods::validate_string($request->schoolCode);
	$students_array = Methods::validate_array($request->students_array);

	if(!$students_array || !$schoolCode) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Mark Attendance. Please Try Again...."
		));
	} else {
		if(Date('l') != 'Sunday' && Date('l') != 'Saturday') {
			$conn = $pdo->open();

			if(Attendance::mark_attendance($schoolCode, $students_array, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Attendance Marked Successfully....."
				));
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Attendance Could Not Be Marked. Please Try Again....."
				));
			}

		    $pdo->close();
		} else {
			array_push($response, array(
				"status"  => "Error",
				"message" => "No Attendance Marking On Weekends...."
			));
		}
	}
	echo json_encode($response);
?>