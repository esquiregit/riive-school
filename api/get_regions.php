<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/country.php";

    $conn      = $pdo->open();
    $data      = file_get_contents("php://input");
    $request   = json_decode($data);//die(print_r($request));
    $school_id = Methods::validate_string($request->school_id);
    $countryID = Methods::validate_string($request->country_code);
    $response  = array();
    Attendance::after_nine_attedance_marking($school_id, $conn);

    if($countryID) {
        $result = Country::read_regions($countryID, $conn);

        foreach ($result as $row) {
            array_push($response, array(
                "label" => Methods::strtocapital($row->regionName),
                "value" => $row->regionID,
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
