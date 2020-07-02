<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $id         = Methods::validate_string($request->id);
    $student_id = Methods::validate_string($request->student_id);
    $schoolCode = Methods::validate_string($request->schoolCode);
    $status     = Methods::validate_string($request->status);
    $response   = array();

    if($id && $schoolCode && $student_id && $status) {
        if(Attendance::update_attendance($id, $schoolCode, $student_id, $status, $conn)) {
            array_push($response, array(
                "status"  => "success",
                "message" => "Attendance Updated Successfully...."
            ));
        } else {
            array_push($response, array(
                "status"  => "error",
                "message" => "Attendance Could Not Be Updated. Please Try Again...."
            ));
        }
    }
    $pdo->close();
    echo json_encode($response);
?>