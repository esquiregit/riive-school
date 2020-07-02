<?php
	header('Content-Type: application/json');
	require "classes/audit_trail.php";
	require "classes/attendance.php";
    require 'classes/school.php';

	$conn     	      = $pdo->open();
	$response         = array();
	$school_id   	  = Methods::validate_string(Methods::strtocapital($_POST['id']));
	$name   	      = Methods::validate_string(Methods::strtocapital($_POST['name']));
	$email   	      = Methods::validate_email(strtolower($_POST['email']));
	$phone   	      = Methods::validate_string(Methods::strtocapital($_POST['phone']));
	$region   	      = Methods::validate_string(Methods::strtocapital($_POST['region']));
	$username         = Methods::validate_string($_POST['username']);
	$location         = Methods::validate_string($_POST['location']);
	$website          = Methods::validate_string($_POST['website']);
	$password         = Methods::validate_string($_POST['password']);
	$confirm_password = Methods::validate_string($_POST['confirm_password']);
	$access_level     = Methods::validate_string($_POST['access_level']);
	@$profile_picture = Methods::validate_string($_FILES['image']['name']);
    @$image_size   	  = Methods::validate_string($_FILES['image']['size']);
    @$image_type   	  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    Attendance::after_nine_attedance_marking($school_id, $conn);

	if(!isset($school_id) || !isset($name) || !isset($email) || !isset($phone) || !isset($region) || !isset($username) || !isset($location) || !isset($website) || !isset($access_level)) {
		array_push($response, array(
			"status"  => "Error",
			"message" => "Couldn't Update Profile. Please Try Again...."
		));
	} else {
		if(empty($name)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Name...."
			));
		} else if(empty($email)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Email Address...."
			));
		} else if(!Methods::valid_email_format($email)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Invalid Email Address Format...."
			));
		} else if(Methods::is_this_email_address_taken($school_id, $email, $conn)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Email Address \"" . $email . "\" Already Used. Please Choose Another...."
			));
		} else if(empty($region)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Select Region...."
			));
		} else if(empty($username)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Username...."
			));
		} else if(Methods::is_this_username_taken($school_id, $username, $conn)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Username\"".$username."\" Already Used. Please Choose Another...."
			));
		} else if(empty($location)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In location...."
			));
		} else if(empty($website)) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Please Fill In Website...."
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
		} else if(!empty($password) && strlen($password) < 8) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Password Must Contain At Least 8 Characters...."
			));
		} else if(!empty($password) && !empty($confirm_password) && $password !== $confirm_password) {
			array_push($response, array(
				"status"  => "Warning",
				"message" => "Passwords Don't Match...."
			));
		} else {
			if(empty($profile_picture)) {
				if(empty($password)) {
					if(School::update_school_with_no_password_and_no_image($school_id, $name, $email, $location, $phone, $region, $website, $username, $conn)) {
						array_push($response, array(
							"user"    => School::read_school_after_profile_update($school_id, $conn),
							"status"  => "Success",
							"message" => "Profile Updated Successfully...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Updated Profile Details', $conn);
					} else {
						array_push($response, array(
							"status"  => "Error",
							"message" => "Profile Could Not Be Updated. Please Try Again...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Tried To Update Profile Details', $conn);
					}
				} else {
					if(School::update_school_with_password_and_no_image($school_id, $name, $email, $location, $phone, $region, $website, $username, $password, $conn)) {
						array_push($response, array(
							"user"    => School::read_school_after_profile_update($school_id, $conn),
							"status"  => "Success",
							"message" => "Profile Updated Successfully...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Updated Profile Details', $conn);
					} else {
						array_push($response, array(
							"status"  => "Error",
							"message" => "Profile Could Not Be Updated. Please Try Again...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Tried To Update Profile Details', $conn);
					}
				}
			} else  {
				$picture_upload = str_replace(' ', '_', $name).'_'.Date('Y_m_d_H_i_s').'_'.rand(100, 999).'.'.$image_type;
				if(empty($password)) {
					if(School::update_school_with_no_password_and_image($school_id, $name, $email, $location, $phone, $region, $website, $username, 'pictures/'.$picture_upload, $conn)) {
						move_uploaded_file($_FILES["image"]["tmp_name"], 'pictures/'.$picture_upload);
						array_push($response, array(
							"user"    => School::read_school_after_profile_update($school_id, $conn),
							"status"  => "Success",
							"message" => "Profile Updated Successfully...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Updated Profile Details', $conn);
					} else {
						array_push($response, array(
							"status"  => "Error",
							"message" => "Profile Could Not Be Updated. Please Try Again...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Tried To Update Profile Details', $conn);
					}
				} else {
					if(School::update_school_with_password_and_image($school_id, $name, $email, $location, $phone, $region, $website, $username, $password, 'pictures/'.$picture_upload, $conn)) {
						move_uploaded_file($_FILES["image"]["tmp_name"], 'pictures/'.$picture_upload);
						array_push($response, array(
							"user"    => School::read_school_after_profile_update($school_id, $conn),
							"status"  => "Success",
							"message" => "Profile Updated Successfully...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Updated Profile Details', $conn);
					} else {
						array_push($response, array(
							"status"  => "Error",
							"message" => "Profile Could Not Be Updated. Please Try Again...."
						));
	        			Audit_Trail::create_log($school_id, '', 'Tried To Update Profile Details', $conn);
					}
				}
			}
	    }

	    $pdo->close();
	}
	echo json_encode($response);
?>