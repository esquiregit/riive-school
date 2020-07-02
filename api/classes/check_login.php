<?php
	@session_start();

	if(!isset($_SESSION['riive_school_id'])) {
		header('location: index.php');
	}
?>