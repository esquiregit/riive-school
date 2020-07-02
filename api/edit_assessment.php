<?php
	header('Content-Type: application/json');
	require_once "classes/conn.php";
	require_once "classes/attendance.php";
	require_once "classes/assessment.php";
	require_once "classes/audit_trail.php";
	require_once "classes/student.php";
	require_once "classes/teacher.php";

	$conn 			    = $pdo->open();
	$response           = array();
	$data 	            = file_get_contents("php://input");
	$request            = json_decode($data);//die(print_r($request));
	$a_id               = Methods::validate_string(Methods::strtocapital($request->a_id));
	$class_tests        = Methods::validate_string(Methods::strtocapital($request->class_tests));
	$assignments        = Methods::validate_string(Methods::strtocapital($request->assignments));
	$interim_assessment = Methods::validate_string(Methods::strtocapital($request->interim_assessment));
	$attendance_mark    = Methods::validate_string(Methods::strtocapital($request->attendance_mark));
	$exams_score        = Methods::validate_string(Methods::strtocapital($request->exams_score));
	$total_score        = Methods::validate_string(Methods::strtocapital($request->total_score));
	$grade              = Methods::validate_string(Methods::strtocapital($request->grade));
	$remarks            = Methods::validate_string(Methods::strtocapital($request->remarks));
	$student            = Methods::validate_string(Methods::strtocapital($request->student));
	$academic_year      = Methods::validate_string(Methods::strtocapital($request->academic_year));
	$term               = Methods::validate_string(Methods::strtocapital($request->term));
	$schoolCode         = Methods::validate_string(Methods::strtocapital($request->school_id));
	$teacher_id         = Methods::validate_string(Methods::strtocapital($request->teacher_id));
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

	if(!isset($class_tests) || !isset($assignments) || !isset($interim_assessment) || !isset($attendance_mark) || !isset($exams_score) || !isset($total_score) || !isset($grade) || !isset($remarks) || !isset($schoolCode) || !isset($teacher_id) || !isset($student) || !isset($academic_year) || !isset($term)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Assessment Could Not Be Updated. Please Try Again....."
		));
	} else {
		if(!$class_tests || $class_tests < 0 || $class_tests > 10) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Class Tests Mark Must Be Between 0 And 10...."
			));
		} else if(!$assignments || $assignments < 0 || $assignments > 5) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Assignments Mark Must Be Between 0 And 5...."
			));
		} else if(!$interim_assessment || $interim_assessment < 0 || $interim_assessment > 10) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Interim Assessment Marks Must Be Between 0 And 10...."
			));
		} else if(!$attendance_mark || $attendance_mark < 0 || $attendance_mark > 5) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Attendance Marks Must Be Between 0 And 5...."
			));
		} else if(!$exams_score || $exams_score < 0 || $exams_score > 70) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Examination Marks Must Be Between 0 And 70...."
			));
		} else if(!$total_score || $total_score < 0 || $total_score > 100) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Total Marks Must Be Between 0 And 100...."
			));
		} else if(strtolower($grade) !== 'a' && strtolower($grade) !== 'b+' && strtolower($grade) !== 'b' && strtolower($grade) !== 'c+' && strtolower($grade) !== 'c' && strtolower($grade) !== 'd+' && strtolower($grade) !== 'd' && strtolower($grade) !== 'e+' && strtolower($grade) !== 'e' && strtolower($grade) !== 'f') {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Invalid Grade Entered...."
			));
		} else if(strtolower($remarks) !== 'fail' && strtolower($remarks) !== 'very poor' && strtolower($remarks) !== 'poor' && strtolower($remarks) !== 'pass' && strtolower($remarks) !== 'average' && strtolower($remarks) !== 'above average' && strtolower($remarks) !== 'good' && strtolower($remarks) !== 'very good' && strtolower($remarks) !== 'excellent' && strtolower($remarks) !== 'outstanding') {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Invalid Remarks Entered...."
			));
		} else {
			$school_name = Student::read_school_name_by_id($schoolCode, $conn);
			$username    = Teacher::read_teacher_username_by_id($teacher_id, $conn);
			if(Assessment::update_assessment($class_tests, $assignments, $interim_assessment, $attendance_mark, $exams_score, $total_score, $grade, $remarks, $a_id, $conn)) {
				array_push($response, array(
					"status"  => "Success",
					"message" => "Assessment Updated Successfully....."
				));
	        	Audit_Trail::create_log($schoolCode, $teacher_id, 'Updated "'.$academic_year.' '.$term.'" Assessment For "'.$student.'"', $conn);
			} else {
				array_push($response, array(
					"status"  => "Error",
					"message" => "Assessment Could Not Be Updated. Please Try Again....."
				));
	        	Audit_Trail::create_log($schoolCode, $teacher_id, 'Tried To Update "'.$academic_year.' '.$term.'" Assessment For "'.$student.'"', $conn);
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>