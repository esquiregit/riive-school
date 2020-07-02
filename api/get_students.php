<?php
	header('Content-Type: application/json');
    require "classes/attendance.php";

    $conn      = $pdo->open();
	$data 	   = file_get_contents("php://input");
	$request   = json_decode($data);//die(print_r($request));
	$school_id = Methods::validate_string($request->school_id);
	@$class    = Methods::validate_string($request->class);
	$response  = array();
    Attendance::after_nine_attedance_marking($school_id, $conn);

	if($school_id) {
		if($school_id && $class) {
			$result = Student::read_teacher_students($school_id, $class, $conn);
		} else {
			$result = Student::read_students_by_school_id($school_id, $conn);
		}

		foreach ($result as $student) {
			array_push($response, array(
				'studentid'   => $student->studentid,
				'School_id'   => $student->School_id,
				'firstname'   => $student->firstname,
				'lastname'    => $student->lastname,
				'othernames'  => $student->othernames,
				'gender'      => $student->gender,
				'dob'         => $student->dob,
				'class'       => $student->class,
				'studentCode' => $student->studentCode,
				'imagePath'   => $student->imagePath,
				'image'       => $student->image,
				'name'        => $student->othernames ? $student->firstname.' '.$student->othernames.' '.$student->lastname : $student->firstname.' '.$student->lastname,
			));
		}
	}

    $pdo->close();
    echo json_encode($response);
?>