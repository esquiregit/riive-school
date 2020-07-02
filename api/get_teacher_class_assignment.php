<?php
    header('Content-Type: application/json');
    require_once "classes/attendance.php";
    require_once "classes/teacher.php";

    $conn      = $pdo->open();
    $data      = file_get_contents("php://input");
    $request   = json_decode($data);//die(print_r($request));
    $school_id = Methods::validate_string($request->school_id);
    $response  = array();
    Attendance::after_nine_attedance_marking($school_id, $conn);

    if($school_id) {
        $response = Teacher::read_teacher_class_assignment($school_id, $conn);
    }
    $pdo->close();
    echo json_encode($response);
?>