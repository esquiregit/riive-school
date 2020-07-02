<?php
	header('Content-Type: application/json');
    require 'classes/conn.php';
    require 'classes/audit_trail.php';
    require 'classes/methods.php';

    $conn       = $pdo->open();
	$data 	    = file_get_contents("php://input");
	$request    = json_decode($data);
	$school_id  = Methods::validate_string($request->school_id);
	$teacher_id = Methods::validate_string($request->teacher_id);
	$response   = array();

	Audit_Trail::create_log($school_id, $teacher_id, 'Logged Out', $conn);

	$pdo->close();
    echo json_encode($response);
?>