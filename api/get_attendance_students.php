<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";
    // require "classes/student.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $schoolCode = Methods::validate_string($request->schoolCode);
    $class      = Methods::validate_string($request->class);
    $response   = array();
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

    if($schoolCode && $class) {
        $result = Student::read_attendance_students_by_class($schoolCode, $class, $conn);

        foreach ($result as $student) {
            array_push($response, array(
                "studentid" => $student->studentid,
                "student"   => $student->othernames ? $student->firstname.' '.$student->othernames.' '.$student->lastname : $student->firstname.' '.$student->lastname,
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
