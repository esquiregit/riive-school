<?php
	@session_start();
	$previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];
	
	if($_SESSION['riive_school_access_level'] != "School Admin") {
		echo "<script>alert('Operation Failed. This Operation Is Restricted To Only School Admins');</script>";
		echo "<script>location = '$previous_page';</script>";
	}
?>