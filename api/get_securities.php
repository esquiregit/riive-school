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
        $result = Security::read_school_securities($schoolCode, $conn);

        foreach ($result as $row) {
            array_push($response, array(
                "id"          => $row->id,
                "schoolCode"  => $row->schoolCode,
                "name"        => Methods::strtocapital($row->name),
                "contact"     => $row->contact,
                "schoolname"  => Methods::strtocapital($row->schoolname),
                "accountType" => Methods::strtocapital($row->accountType)
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
