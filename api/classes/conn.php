<?php
	@session_start();
	
	Class Database {
 
 	// 	private $server   = "mysql:host=localhost;dbname=shenxmds_rive_school_app";
		// private $username = "shenxmds_rivescho_pro";
		// private $password = "DREAMSinn123...";
		private $server   = "mysql:host=localhost;dbname=riive";
		private $username = "root";
		private $password = "";
		private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
		protected $conn;
	 	
		public function open() {
	 		try{
	 			$this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
	 			return $this->conn;
	 		}catch (PDOException $e){
	 			die("Connection Problem: " . $e->getMessage());
	 		}
	 
	    }
	 
		public function close(){
	   		$this->conn = null;
	 	}
	 
	}

	$pdo  = new Database();
?>