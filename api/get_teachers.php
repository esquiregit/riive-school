<?php
	header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/teacher.php";

    $conn      = $pdo->open();
	$data 	   = file_get_contents("php://input");
	$request   = json_decode($data);
	$school_id = Methods::validate_string($request->school_id);
	$response  = array();
    Attendance::after_nine_attedance_marking($school_id, $conn);

	if($school_id) {
		$result = Teacher::read_teachers_by_school_id($school_id, $conn);

		foreach ($result as $teacher) {
			array_push($response, array(
				"id"    	   => $teacher->id,
				"name"   	   => $teacher->name,
				"contact"      => $teacher->contact,
				"email"        => $teacher->email,
				"country_id"   => $teacher->country_id,
				"country_name" => $teacher->country_name,
				"school_name"  => $teacher->schoolname,
				"username"     => $teacher->username,
				"school_id"    => $teacher->schoolCode,
				"access_level" => $teacher->accountType,
				"status"       => $teacher->status,
				"reset_code"   => $teacher->reset_code,
				"image"        => (empty($teacher->image)) ? "pictures/avatar.png" : $teacher->image
			));
		}
	} 

	$pdo->close();
    echo json_encode($response);
?>