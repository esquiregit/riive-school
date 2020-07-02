<?php
	header('Content-Type: application/json');
	require "classes/audit_trail.php";
	require "classes/attendance.php";
    require 'classes/school.php';

	$conn     	      = $pdo->open();
	$response         = array();
	$school_id   	  = Methods::validate_string(Methods::strtocapital($_POST['school_id']));
	$teacher_id   	  = Methods::validate_string(Methods::strtocapital($_POST['teacher_id']));
	$studentid   	  = Methods::validate_string(Methods::strtocapital($_POST['studentid']));
	$name   	      = Methods::validate_string(Methods::strtocapital($_POST['name']));
	$firstname   	  = Methods::validate_string(Methods::strtocapital($_POST['firstname']));
	$othernames   	  = Methods::validate_string(Methods::strtocapital($_POST['othernames']));
	$lastname   	  = Methods::validate_string(Methods::strtocapital($_POST['lastname']));
	$gender   		  = Methods::validate_string($_POST['gender']);
	$class   		  = Methods::validate_string($_POST['class']);
	$dob              = Methods::validate_string($_POST['dob']);
	$access_level     = Methods::validate_string($_POST['access_level']);
	@$profile_picture = Methods::validate_string($_FILES['image']['name']);
    @$image_size   	  = Methods::validate_string($_FILES['image']['size']);
    @$image_type   	  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    Attendance::after_nine_attedance_marking($school_id, $conn);

	if(!isset($firstname) || !isset($lastname) || !isset($school_id) || !isset($name) || !isset($gender) || !isset($dob) || !isset($access_level) || !isset($othernames)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Add Student Details. Please Try Again...."
		));
	} else {
		$conn     	     = $pdo->open();
		$todays_date	 = date("Y-m-d", strtotime(Date("Y-m-d")));

		if(empty($firstname) && empty($lastname) && empty($gender) && empty($class) && empty($dob) && empty($profile_picture)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "All Fields Required"
			));
		} else if(empty($firstname)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Student's First Name"
			));
		} else if(empty($lastname)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Student's Last Name"
			));
		} else if(empty($gender)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Select Student's Gender"
			));
		} else if(empty($class)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Select Student's Class"
			));
		} else if(empty($dob)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Student's Date of Birth"
			));
		} else if($dob > $todays_date) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Date Of Birth Cannot Be In The Future"
			));
		} else if(!empty($profile_picture) && $image_size > (1048576)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Image Size Must Be 1mb Or Less"
			));
		} else if(!empty($profile_picture) && strtolower($image_type) != 'png' && strtolower($image_type) != 'jpeg' && strtolower($image_type) != 'jpg' ) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Image Must Be PNG Or JPG/JPEG"
			));
		} else {
			$school_name = School::read_school_name_by_id($school_id, $conn);
			if(empty($profile_picture)) {
				if(Student::update_student_without_image($studentid, $school_id, $firstname, $lastname, $othernames, $gender, $dob, $class, $conn)) {
					array_push($response, array(
						"status"  => "Success",
						"message" => "Student Details Updated Successfully...."
					));
	        		Audit_Trail::create_log($school_id, $teacher_id, 'Updated Details Of Student "'.$firstname.' '.$lastname.'"', $conn);
				} else {
					array_push($response, array(
						"status"  => "Error",
						"message" => "Student Details Could Not Be Updated. Please Try Again...."
					));
	        		Audit_Trail::create_log($school_id, $teacher_id, 'Tried To Update Details Of Student "'.$firstname.' '.$lastname.'"', $conn);
				}
			} else  {
				$picture_upload = str_replace(' ', '_', $school_name).'_'.Date('Y_m_d_H_i_s').'_'.rand(100, 999).'.'.$image_type;
				if(Student::update_student($studentid, $school_id, $firstname, $lastname, $othernames, $gender, $dob, $class, $picture_upload, $conn)) {
	                move_uploaded_file($_FILES["image"]["tmp_name"], 'pictures/'.$picture_upload);
					array_push($response, array(
						"status"  => "Success",
						"message" => "Student Details Updated Successfully...."
					));
	        		Audit_Trail::create_log($school_id, $teacher_id, 'Updated Details Of Student "'.$firstname.' '.$lastname.'"', $conn);
				} else {
					array_push($response, array(
						"status"  => "Error",
						"message" => "Student Details Could Not Be Updated. Please Try Again...."
					));
	        		Audit_Trail::create_log($school_id, $teacher_id, 'Tried To Update Details Of Student "'.$firstname.' '.$lastname.'"', $conn);
				}
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>