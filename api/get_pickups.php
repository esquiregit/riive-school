<?php
    header('Content-Type: application/json');
    require "classes/methods.php";
    require "classes/pickup.php";
    require "classes/teacher.php";

    $conn       = $pdo->open();
    $data       = file_get_contents("php://input");
    $request    = json_decode($data);//die(print_r($request));
    $schoolCode = Methods::validate_string($request->schoolCode);
    @$class     = Methods::validate_string($request->class);
    $response   = array();

    if($schoolCode) {
        $result = Pickup::read_pickups($schoolCode, $class, $conn);

        foreach ($result as $row) {
            array_push($response, array(
                "class"        => strtoupper($row->class),
                "code"         => $row->code,
                "date"         => date_format(date_create($row->date), 'l d F Y \a\t H:i:s'),
                "parent"       => Methods::strtocapital($row->fullname),
                "image"        => $row->image,
                "imagePath"    => $row->imagePath,
                "phone"        => $row->phone,
                "pickUpPerson" => Methods::strtocapital($row->pickUpPerson),
                "pickUpType"   => Methods::strtocapital($row->pickUpType),
                "student"      => Methods::strtocapital($row->othernames ? $row->firstname.' '.$row->othernames.' '.$row->lastname : $row->firstname.' '.$row->lastname),
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
