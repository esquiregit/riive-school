<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $schoolCode = Methods::validate_string($request->schoolCode);
    @$class     = Methods::validate_string($request->class);
    $response   = array();
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

    if($schoolCode) {
        if(!empty($class)) {
            $result = Attendance::read_attendance_by_class($schoolCode, $class, $conn);
        } else {
            $result = Attendance::read_attendance_by_school_id($schoolCode, $conn);
        }

        foreach ($result as $row) {
            array_push($response, array(
                "id"             => $row->id,
                "status"         => $row->status,
                "student_id"     => $row->student_id,
                "schoolCode"     => $row->schoolCode,
                "clock_in_time"  => $row->clock_in_time === '00:00:00' ? '--:--:--' : $row->clock_in_time,
                "clock_out_time" => $row->clock_out_time === '00:00:00' ? '--:--:--' : $row->clock_out_time,
                "class"          => strtoupper($row->class),
                "date"           => date_format(date_create($row->date), 'l d F Y'),
                "pickUpCode"     => $row->pickUpCode,
                "image"          => $row->image,
                "imagePath"      => $row->imagePath,
                "student"        => Methods::strtocapital($row->othernames ? $row->firstname.' '.$row->othernames.' '.$row->lastname : $row->firstname.' '.$row->lastname),
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
