<?php
	header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/assessment.php";

    $conn      = $pdo->open();
	$data 	   = file_get_contents("php://input");
	$request   = json_decode($data);//die(print_r($request));
	$School_id = Methods::validate_string($request->School_id);
	@$class    = Methods::validate_string($request->class);
	$response  = array();
    Attendance::after_nine_attedance_marking($School_id, $conn);

	if($School_id) {
		$result = Assessment::read_assessments_for_school($School_id, $class, $conn);

		foreach ($result as $assessment) {
			array_push($response, array(
				'a_id'               => $assessment->a_id,
				'academic_year'      => $assessment->academic_year,
				'assignments'        => $assessment->assignments,
				'attendance_mark'    => $assessment->attendance_mark,
				'class'              => $assessment->class,
				'class_tests'        => $assessment->class_tests,
				'date_entered'       => date_format(date_create($assessment->date_entered), 'l d F Y \a\t H:m:s'),
				'exams_score'        => $assessment->exams_score,
				'grade'              => $assessment->grade,
				'interim_assessment' => $assessment->interim_assessment,
				'last_edit_date'     => date_format(date_create($assessment->last_edit_date), 'l d F Y \a\t H:m:s'),
				'remarks'            => $assessment->remarks,
				'subject'            => $assessment->subject,
				'term'               => $assessment->term,
				'total_score'        => $assessment->total_score,
				'student'            => $assessment->othernames ? $assessment->firstname.' '.$assessment->othernames.' '.$assessment->lastname : $assessment->firstname.' '.$assessment->lastname,
			));
		}
	}

    $pdo->close();
    echo json_encode($response);
?>