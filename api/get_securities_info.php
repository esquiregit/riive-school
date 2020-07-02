<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/security.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $schoolCode = Methods::validate_string($request->schoolCode);
    $response   = array();
    Attendance::after_nine_attedance_marking($schoolCode, $conn);

    if($schoolCode) {
        $result = Security::read_securities_info($schoolCode, $conn);

        foreach ($result as $row) {
            array_push($response, array(
                "value" => $row->id,
                "label" => Methods::strtocapital($row->name)
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
