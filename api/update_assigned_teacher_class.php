<?php
    header('Content-Type: application/json');
    require_once "classes/methods.php";
    require_once "classes/teacher.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $id         = Methods::validate_string($request->id);
    $teacher_id = Methods::validate_string($request->teacher_id);
    $school_id  = Methods::validate_string($request->school_id);
    $class      = Methods::validate_string($request->class);
    $response   = array();

    if($id && $school_id && $teacher_id && $class) {
        $full_class = ($class == 1 || $class == 2 || $class == 3 || $class == 4 || $class == 5 || $class == 6) ? 'Class ' . $class : $class;
        if(Teacher::has_class_been_assigned($school_id, $class, $conn) && !Teacher::has_this_class_been_assigned($id, $school_id, $class, $conn)) {
            array_push($response, array(
                "status"  => "warning",
                "message" => $full_class . " Has Been Assigned To Another Teacher"
            ));
        } else {
            if(Teacher::update_assigned_teacher_class($id, $school_id, $teacher_id, $class, $conn)) {
                array_push($response, array(
                    "status"  => "success",
                    "message" => "Teacher/Class Assignment Updated Successfully...."
                ));
            } else {
                array_push($response, array(
                    "status"  => "error",
                    "message" => "Teacher/Class Assignment Could Not Be Updated. Please Try Again...."
                ));
            }
        }
    }
    $pdo->close();//die(print_r($response));
    echo json_encode($response);
?>