<?php
	session_start();
	unset($_SESSION['riive_school_id']);
	header('location: ../dashboard.php');
?>