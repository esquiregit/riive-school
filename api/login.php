<?php
	header('Content-Type: application/json');
	require_once "classes/conn.php";
	require_once "classes/audit_trail.php";
	require_once "classes/methods.php";
	require_once "classes/school.php";
	require_once "classes/teacher.php";

	$conn     = $pdo->open();
	$data 	  = file_get_contents("php://input");
	$request  = json_decode($data);//die(print_r($request));
	$username = Methods::validate_string($request->username);
	$password = Methods::validate_string($request->password);
	$user     = array();
	$status   = array();
	$response = array();

    if($username && $password) {
    	$result   = School::login_school($username, $password, $conn);
		
		if($result) {
			array_push($user, array(
				"id"           => $result->School_id,
				"name"  	   => $result->schoolname,
				"email"        => $result->email,
				"location"     => $result->location,
				"phone"        => $result->phone,
				"country_code" => $result->country,
				"country_name" => $result->country_name,
				"region"       => $result->region,
				"website"      => $result->website,
				"username"     => $result->username,
				"access_level" => "School",
				"status"       => $result->status,
				"reset_code"   => $result->reset_code,
				"image"        => (empty($result->image)) ? "pictures/avatar.png" : $result->image
			));
			array_push($status, array(
				"status"	   => "Success",
				"message"	   => "Login Success. Redirecting...."
			));

	        Audit_Trail::create_log($result->School_id, '', 'Logged In', $conn);
		} else {
		    $result = Teacher::login_teacher($username, $password, $conn);

		    if($result) {
				array_push($user, array(
					"id"    	   => $result->id,
					"name"   	   => $result->name,
					"class"   	   => @$result->class ? $result->class : '',
					"contact"      => $result->contact,
					"email"        => $result->email,
					"country_id"   => $result->id,
					"username"     => $result->username,
					"country_name" => $result->country_name,
					"school_id"    => $result->schoolCode,
					"school_name"  => $result->schoolname,
					"access_level" => $result->accountType,
					"status"       => $result->status,
					"reset_code"   => $result->reset_code,
					"image"        => (empty($result->image)) ? "pictures/avatar.png" : $result->image
				));
				array_push($status, array(
					"status"	   => "Success",
					"message"	   => "Login Success. Redirecting...."
				));

		        Audit_Trail::create_log($result->id, $result->id, 'Logged In', $conn);
			} else {
				array_push($status, array(
					"status"  => "Failure",
					"message" => "Invalid Login Credentials...."
				));
			}
		}

		array_push($response, array(
			"user"   => $user,
			"status" => $status
		));
	}

    $pdo->close();
    echo json_encode($response);
?>