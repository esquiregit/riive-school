<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/audit_trail.php";

    $conn      = $pdo->open();
    $data      = file_get_contents("php://input");
    $request   = json_decode($data);//die(print_r($request));
    $school_id = Methods::validate_string($request->school_id);
    $response  = array();
    Attendance::after_nine_attedance_marking($school_id, $conn);

    if($school_id) {
        $result = Audit_Trail::read_school_logs($school_id, $conn);

        foreach ($result as $log) {
            array_push($response, array(
                "name"         => $log->name ? $log->name : $log->schoolname,
                "access_level" => $log->name ? 'Teacher' : 'School',
                "activity"     => $log->activity,
                "date"         => date_format(date_create($log->date), 'l d F Y \a\t H:i:s'),
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
