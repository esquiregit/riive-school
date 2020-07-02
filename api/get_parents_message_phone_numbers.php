<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);
    $School_id  = Methods::validate_string($request->school_id);
    @$class     = Methods::validate_string($request->class);
    $response   = array();
    Attendance::after_nine_attedance_marking($School_id, $conn);

    if($School_id) {
        if($class) {
            $result = Parents::read_parents_for_teacher_message($School_id, $class, $conn);
        } else {
            // $classes = Parents::get_students_classes();

            // foreach ($classes as $class) {
            //     $class_arr = array();
            //     $result    = Parents::read_parents_for_teacher_message($School_id, $class, $conn);

            //     foreach ($result as $parent) {
            //         array_push($class_arr, array(
            //             "label" => $parent->othernames?$parent->firstname.' '.$parent->othernames.' '.$parent->lastname.'\'s '.Methods::strtocapital($parent->relation):$parent->firstname.' '.$parent->lastname.'\'s '.Methods::strtocapital($parent->relation),
            //             "value" => $parent->phone
            //         ));
            //     }

            //     array_push($response, array(
            //         $class => $class_arr,
            //     ));
            // }
            $result = Parents::read_parents_for_school_message($School_id, $conn);
        }

        foreach ($result as $parent) {
            array_push($response, array(
                "label" => $parent->othernames?$parent->firstname.' '.$parent->othernames.' '.$parent->lastname.'\'s '.Methods::strtocapital($parent->relation):$parent->firstname.' '.$parent->lastname.'\'s '.Methods::strtocapital($parent->relation),
                "value" => $parent->phone
            ));
        }
    } else {
        array_push($response, array(
            "status"  => "Failed",
            "message" => "Couldn't Fetch Parents Details. Please Try Again...."
        ));
    }

    $pdo->close();
    echo json_encode($response);
?>