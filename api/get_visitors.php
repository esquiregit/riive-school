<?php
    header('Content-Type: application/json');
    require "classes/attendance.php";
    require "classes/visitor.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $schoolID   = Methods::validate_string($request->schoolID);
    $response   = array();
    Attendance::after_nine_attedance_marking($schoolID, $conn);

    if($schoolID) {
        $result = Visitor::read_visitors_by_school($schoolID, $conn);

        foreach ($result as $row) {
            array_push($response, array(
                "clockInTime"      => $row->clockInTime  === '0000-00-00 00:00:00' ? 'Not Yet' : date_format(date_create($row->clockInTime), 'l d F Y \a\t H:m:s'),
                "clockOutTime"     => $row->clockOutTime === '0000-00-00 00:00:00' ? 'Not Yet' : date_format(date_create($row->clockOutTime), 'l d F Y \a\t H:m:s'),
                "id"               => $row->id,
                "image"            => $row->image,
                "imagePath"        => $row->imagePath,
                "name"             => Methods::strtocapital($row->name),
                "personToVisit"    => Methods::strtocapital($row->personToVisit),
                "purposeOfVisit"   => Methods::strtocapital($row->purposeOfVisit),
                "schoolID"         => $row->schoolID,
                "securityPersonId" => $row->securityPersonId,
                "visitorName"      => Methods::strtocapital($row->visitorName),
                "visitorNumber"    => $row->visitorNumber,
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
