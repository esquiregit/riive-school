<?php
    header('Content-Type: application/json');
    require "classes/methods.php";
    require "classes/school.php";
    require "classes/teacher.php";

    $conn             = $pdo->open();
    $data             = file_get_contents("php://input");
    $request          = json_decode($data);//die(print_r($request));
    $id               = Methods::validate_string($request->id);
    $sid              = Methods::validate_string($request->sid);
    $type             = Methods::validate_string($request->type);
    $password         = Methods::validate_string($request->password);
    $reset_code       = Methods::validate_string($request->code);
    $confirm_password = Methods::validate_string($request->confirm_password);
    $response         = array();

    if($password && $confirm_password && $id && $reset_code && $type) {
         if(strlen($password) < 8) {
            array_push($response, array(
                "status"  => "Warning",
                "message" => "Password Must Contain At Least 8 Characters...."
            ));
        } else if($password !== $confirm_password) {
            array_push($response, array(
                "status"  => "Warning",
                "message" => "Passwords Don't Match...."
            ));
        } else {
            if($type === 's') {
                $result = School::change_school_password($id, $password, $reset_code, $conn);
            } else {
                $result = Teacher::change_teacher_password($id, $password, $reset_code, $conn);
            }

            if($result) {
                array_push($response, array(
                    "status"  => "Success",
                    "message" => "Password Changed Successfully. You Can Log In Now...."
                ));
            } else {
                array_push($response, array(
                    "status"  => "Error",
                    "message" => "Couldn't Update Password. Please Try Again...."
                ));
            }
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
