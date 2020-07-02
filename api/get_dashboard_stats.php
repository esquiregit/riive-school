<?php
	header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/dashboard.php";

    $conn      = $pdo->open();
    $data      = file_get_contents("php://input");
    $request   = json_decode($data);//die(print_r($request));
    $School_id = Methods::validate_string($request->School_id);
	$response  = array();
    Attendance::after_nine_attedance_marking($School_id, $conn);

	array_push($response, array(
		'total_students'      => Dashboard::get_total_students($School_id, $conn),
		'total_teachers'      => Dashboard::get_total_teachers($School_id, $conn),
		'total_security'      => Dashboard::get_total_security($School_id, $conn),
		'creche_male'         => Dashboard::get_total_students_by_class_and_gender($School_id, 'creche', 'male', $conn),
		'creche_female'       => Dashboard::get_total_students_by_class_and_gender($School_id, 'creche', 'female', $conn),
		'nursery_1_male'      => Dashboard::get_total_students_by_class_and_gender($School_id, 'nursery 1', 'male', $conn),
		'nursery_1_female'    => Dashboard::get_total_students_by_class_and_gender($School_id, 'nursery 1', 'female', $conn),
		'nursery_2_male'      => Dashboard::get_total_students_by_class_and_gender($School_id, 'nursery 2', 'male', $conn),
		'nursery_2_female'    => Dashboard::get_total_students_by_class_and_gender($School_id, 'nursery 2', 'female', $conn),
		'kindergarten_male'   => Dashboard::get_total_students_by_class_and_gender($School_id, 'kindergarten', 'male', $conn),
		'kindergarten_female' => Dashboard::get_total_students_by_class_and_gender($School_id, 'kindergarten', 'female', $conn),
		'one_male'            => Dashboard::get_total_students_by_class_and_gender($School_id, '1', 'male', $conn),
		'one_female'          => Dashboard::get_total_students_by_class_and_gender($School_id, '1', 'female', $conn),
		'two_male'            => Dashboard::get_total_students_by_class_and_gender($School_id, '2', 'male', $conn),
		'two_female'          => Dashboard::get_total_students_by_class_and_gender($School_id, '2', 'female', $conn),
		'three_male'          => Dashboard::get_total_students_by_class_and_gender($School_id, '3', 'male', $conn),
		'three_female'        => Dashboard::get_total_students_by_class_and_gender($School_id, '3', 'female', $conn),
		'four_male'           => Dashboard::get_total_students_by_class_and_gender($School_id, '4', 'male', $conn),
		'four_female'         => Dashboard::get_total_students_by_class_and_gender($School_id, '4', 'female', $conn),
		'five_male'           => Dashboard::get_total_students_by_class_and_gender($School_id, '5', 'male', $conn),
		'five_female'         => Dashboard::get_total_students_by_class_and_gender($School_id, '5', 'female', $conn),
		'six_male'            => Dashboard::get_total_students_by_class_and_gender($School_id, '6', 'male', $conn),
		'six_female'          => Dashboard::get_total_students_by_class_and_gender($School_id, '6', 'female', $conn),
		'jhs_1_male'          => Dashboard::get_total_students_by_class_and_gender($School_id, 'JHS 1', 'male', $conn),
		'jhs_1_female'        => Dashboard::get_total_students_by_class_and_gender($School_id, 'JHS 1', 'female', $conn),
		'jhs_2_male'          => Dashboard::get_total_students_by_class_and_gender($School_id, 'JHS 2', 'male', $conn),
		'jhs_2_female'        => Dashboard::get_total_students_by_class_and_gender($School_id, 'JHS 2', 'female', $conn),
		'jhs_3_male'          => Dashboard::get_total_students_by_class_and_gender($School_id, 'JHS 3', 'male', $conn),
		'jhs_3_female'        => Dashboard::get_total_students_by_class_and_gender($School_id, 'JHS 3', 'female', $conn),
	));

    $pdo->close();
    echo json_encode($response);
?>