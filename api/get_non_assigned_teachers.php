<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/teacher.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);
    $schoolCode = Methods::validate_string($request->schoolCode);
    $response   = array();
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

    if($schoolCode) {
        $result = Teacher::read_non_assigned_teachers_ids_and_names($schoolCode, $conn);

        foreach ($result as $teacher) {
            array_push($response, array(
                "label" => $teacher->name,
                "value" => $teacher->id
            ));
        }
    } else {
        array_push($response, array(
            "status"  => "Failed",
            "message" => "Couldn't Fetch Teachers. Please Try Again...."
        ));
    }

    $pdo->close();
    echo json_encode($response);
?>