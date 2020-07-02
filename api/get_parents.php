<?php
    header('Content-Type: application/json');
    require "classes/methods.php";
    require "classes/parent.php";
    require "classes/student.php";

    $conn      = $pdo->open();
    $data      = file_get_contents("php://input");
    $request   = json_decode($data);//die(print_r($request));
    $school_id = Methods::validate_string($request->school_id);
    @$class    = Methods::validate_string($request->class);
    $response  = array();

    if($school_id) {
        $result = Parents::read_class_students_parents($school_id, $class, $conn);

        foreach ($result as $row) {
            array_push($response, array(
                "School_id"     => $row->School_id,
                "class"         => strtoupper($row->class),
                "email"         => strtolower($row->email),
                "parent"        => Methods::strtocapital($row->fullname),
                "location"      => Methods::strtocapital($row->location),
                "occupation"    => Methods::strtocapital($row->occupation),
                "parentID"      => $row->parentID,
                "phone"         => $row->phone,
                "relation"      => Methods::strtocapital($row->relation),
                "status"        => Methods::strtocapital($row->status),
                "studentID"     => $row->studentID,
                "student"       => Methods::strtocapital($row->othernames ? $row->firstname.' '.$row->othernames.' '.$row->lastname : $row->firstname.' '.$row->lastname),
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>