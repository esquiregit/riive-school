<?php
	if($_SESSION['riive_school_access_level'] == 'Teacher') {
    	require_once "classes/conn.php";
    	require_once "classes/teacher.php";
    	
    	$conn   = $pdo->open();
		$query  = $conn->prepare('SELECT * FROM teacher WHERE id = :id');
	    $query->execute([':id' => $_SESSION['riive_school_user_id']]);
	    $result = $query->fetch(PDO::FETCH_OBJ);

	    if($result) {
			$_SESSION['riive_school_username']      = $result->username;
			$_SESSION['riive_school_password']      = md5($result->password);	
			$_SESSION['riive_school_name']          = $result->name;
			$_SESSION['riive_school_access_level']  = 'Teacher';
			$_SESSION['riive_school_image']         = $result->image;
			$_SESSION['riive_school_teacher_class'] = Teacher::read_assigned_class($result->id, $conn);
		}
    	
    	$pdo->close();
	}
?>