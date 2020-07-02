<?php
	require_once "conn.php";
	require_once "audit_trail.php";
	require_once "methods.php";
	require_once "school.php";
	require_once "teacher.php";

	if(!isset($_POST['user_id']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email'])) {
		header("Location: ../logout.php");
	} else {
		$conn     	      = $pdo->open();
	    $user_id 		  = Methods::validate_string($_POST['user_id']);
		$response 		  = array();

		if($_SESSION['riive_school_access_level'] == 'School Admin') {
			$user_id   	      = Methods::validate_string($_POST['user_id']);
			$schoolname   	  = Methods::validate_string(Methods::strtocapital($_POST['schoolname']));
			$email            = Methods::validate_email(strtolower($_POST['email']));
			$location         = Methods::validate_string(Methods::strtocapital(strtolower($_POST['location'])));
			$phone            = Methods::validate_string(strtolower($_POST['phone']));
			$region           = Methods::validate_string(Methods::strtocapital(strtolower($_POST['region'])));
			$website          = Methods::validate_string(strtolower($_POST['website']));
			$username   	  = Methods::validate_string($_POST['username']);
			$password   	  = Methods::validate_string($_POST['password']);
			$confirm_password = Methods::validate_string($_POST['confirm_password']);
			$profile_picture  = Methods::validate_string($_FILES['profile-image']['name']);
		    $image_size   	  = $_FILES['profile-image']['size'];
		    $image_type   	  = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);

			if(empty($schoolname) && empty($email) && empty($location) && empty($phone) && (empty($region) || $region == 'default') && empty($website) && empty($username)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "All Fields Required"
				));
			} else if(empty($schoolname)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Name Of School Required"
				));
			} else if(empty($email)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Email Address Required"
				));
			} else if(!Methods::valid_email_format($email)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Invalid Email Address Format Entered"
				));
			} else if(empty($location)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Location Required"
				));
			} else if(empty($phone)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Phone Number Required"
				));
			} else if(!ctype_digit($phone)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Phone Number Must Contain Only Digits"
				));
			} else if(strlen($phone) != 10) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Phone Number Must Contain TEN (10) Digits"
				));
			} else if(!Methods::is_prefix_valid($phone)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Phone Number Has Invalid Prefix"
				));
			} else if(empty($region) || $region == 'default') {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Region Required"
				));
			} else if(empty($username)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Username Required"
				));
			} else if(empty($username)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Username Required"
				));
			} else if(!empty($password) && strlen($password) < 8) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Password Must Contain At Least Eight (8) Characters"
				));
			} else if(!empty($password) && empty($confirm_password)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Please Re-enter Password"
				));
			} else if(empty($password) && !empty($confirm_password)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Please Enter Password First"
				));
			} else if(!empty($password) && $password != $confirm_password) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Passwords Do Not Match"
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
				$image_name = $_SESSION['riive_school_name'] .'.'.$image_type;
				if(!empty($password)) {
					if(!empty($profile_picture)) {
						if(School::update_school_with_password_and_image($_SESSION['riive_school_id'], $schoolname, $email, $location, $phone, $region, $website, $username, $password, 'pictures/'.$image_name, $conn)) {
	                		move_uploaded_file($_FILES['profile-image']['tmp_name'], '../pictures/' . $image_name);
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
							$_SESSION['riive_school_name']  = School::read_school_name_by_id($_SESSION['riive_school_id'], $conn);
							$_SESSION['riive_school_image'] = School::read_school_image_by_id($_SESSION['riive_school_id'], $conn);
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					} else {
						if(School::update_school_with_password_and_no_image($_SESSION['riive_school_id'], $schoolname, $email, $location, $phone, $region, $website, $username, $password, $conn)) {
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
							$_SESSION['riive_school_name']  = School::read_school_name_by_id($_SESSION['riive_school_id'], $conn);
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					}
				} else {
					if(!empty($profile_picture)){
						if(School::update_school_with_no_password_and_image($_SESSION['riive_school_id'], $schoolname, $email, $location, $phone, $region, $website, $username, 'pictures/'.$image_name, $conn)) {
	                		move_uploaded_file($_FILES['profile-image']['tmp_name'], '../pictures/' . $image_name);
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
							$_SESSION['riive_school_name']  = School::read_school_name_by_id($_SESSION['riive_school_id'], $conn);
							$_SESSION['riive_school_image'] = School::read_school_image_by_id($_SESSION['riive_school_id'], $conn);
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					} else {
						if(School::update_school_with_no_password_and_no_image($_SESSION['riive_school_id'], $schoolname, $email, $location, $phone, $region, $website, $username, $conn)) {
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
							$_SESSION['riive_school_name']  = School::read_school_name_by_id($_SESSION['riive_school_id'], $conn);
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					}
				}
		    }
		} else if($_SESSION['riive_school_access_level'] == 'Teacher') {
			$user_id   	      = Methods::validate_string($_POST['user_id']);
			$name   	      = Methods::validate_string(Methods::strtocapital($_POST['name']));
			$email            = Methods::validate_email(strtolower($_POST['email']));
			$contact          = Methods::validate_email(strtolower($_POST['contact']));
			$username   	  = Methods::validate_string($_POST['username']);
			$password   	  = Methods::validate_string($_POST['password']);
			$confirm_password = Methods::validate_string($_POST['confirm_password']);
			$profile_picture  = Methods::validate_string($_FILES['profile-image']['name']);
		    $image_size   	  = $_FILES['profile-image']['size'];
		    $image_type   	  = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);

			if(empty($name) && empty($contact) && empty($email) && empty($username)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "All Fields Required"
				));
			} else if(empty($name)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Name Required"
				));
			} else if(!Methods::is_name_valid($name)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Name Must Contain At Least Two Parts"
				));
			} else if(empty($email)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Email Address Required"
				));
			} else if(!Methods::valid_email_format($email)) {
		        array_push($response, array(
					"status"  => "Warning",
					"message" => "Invalid Email Address Format Entered"
				));
		    } else if(empty($contact)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Contact Number Required"
				));
			} else if(!ctype_digit($contact)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Contact Number Must Contain Only Digits"
				));
			} else if(strlen($contact) != 10) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Contact Number Must Contain TEN (10) Digits"
				));
			} else if(!Methods::is_prefix_valid($contact)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Contact Number Has Invalid Prefix"
				));
			} else if(empty($username)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Username Required"
				));
			} else if(!empty($password) && strlen($password) < 8) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Password Must Contain At Least Eight (8) Characters"
				));
			} else if(!empty($password) && empty($confirm_password)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Please Re-enter Password"
				));
			} else if(empty($password) && !empty($confirm_password)) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Please Enter Password First"
				));
			} else if(!empty($password) && $password != $confirm_password) {
				array_push($response, array(
					"status"  => "Warning",
					"message" => "Passwords Do Not Match"
				));
			} else {
				$image_name = $_SESSION['riive_school_name'] .'.'.$image_type;
				if(!empty($password)) {
					if(!empty($profile_picture)){
						if(Teacher::update_teacher_with_password_and_image($_SESSION['riive_school_user_id'], $_SESSION['riive_school_id'], $name, $email, $contact, $username, $password, 'pictures/'.$image_name, $conn)) {
	                		move_uploaded_file($_FILES['profile-image']['tmp_name'], '../pictures/' . $image_name);
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					} else {
						if(Teacher::update_teacher_with_password_and_no_image($_SESSION['riive_school_user_id'], $_SESSION['riive_school_id'], $name, $email, $contact, $username, $password, $conn)) {
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					}
				} else {
					if(!empty($profile_picture)){
	                	move_uploaded_file($_FILES['profile-image']['tmp_name'], '../pictures/' . $image_name);
						if(Teacher::update_teacher_with_no_password_and_image($_SESSION['riive_school_user_id'], $_SESSION['riive_school_id'], $name, $email, $contact, $username, 'pictures/'.$image_name, $conn)) {
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					} else {
						if(Teacher::update_teacher_with_no_password_and_no_image($_SESSION['riive_school_user_id'], $_SESSION['riive_school_id'], $name, $email, $contact, $username, $conn)) {
							array_push($response, array(
								"status"  => "Success",
								"message" => "Profile Updated Successfully"
							));
						} else {
							array_push($response, array(
								"status"  => "Error",
								"message" => "Profile Could Not Be Updated. Please Try Again"
							));
						}
					}
				}
		    }
		}

	    $pdo->close();
	    echo json_encode($response);
	}
?>