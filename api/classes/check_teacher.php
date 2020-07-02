<?php
	@session_start();
	$previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];
	
	if($_SESSION['riive_school_access_level'] != "Teacher")  {
		echo "<script>alert('Operation Failed. This Operation Is Restricted To Only Teachers');</script>";
		echo "<script>location = '$previous_page';</script>";
	}
?>