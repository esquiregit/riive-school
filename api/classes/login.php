<?php
    require_once 'conn.php';
    require_once 'audit_trail.php';
	require_once 'methods.php';

	class Login {
        public $username;
        public $password;
        private $db;
        private $conn;

        public function __construct($username, $password, $conn){
            $this->username = Methods::validate_string($username);
            $this->password = Methods::validate_string($password);
            $this->db       = new Database();
            $this->conn     = $this->db->open();

            if(empty($this->username) || empty($this->password)){
                $GLOBALS['error']         = true;
                $GLOBALS['error_message'] = 'All Fields Required';
            } else {
                $username = $password = $error = $error_message = '';
                self::login_user($conn);
            }
        }

        private function login_user($conn){
            try{
                $query = $this->conn->prepare('SELECT * FROM admin WHERE username = :username AND password = :password');
                $query->execute([':username' => $this->username, ':password' => md5($this->password)]);

                if($query->rowCount()){
                    $result = $query->fetchAll(PDO::FETCH_OBJ);
                    foreach($result as $record){
                        $_SESSION['id']           = $record->id;
                        $_SESSION['username']     = $record->username;
                        $_SESSION['password']     = $record->password;
                        $_SESSION['firstname']    = $record->firstname;
                        $_SESSION['lastname']     = $record->lastname;
                        $_SESSION['photo']        = (empty($record->photo)) ? "pictures/avatar.png" : $record->photo;
                        $_SESSION['created_on']   = $record->created_on;
                        $_SESSION['access_level'] = $record->access_level;
                        $_SESSION['name']         = $_SESSION['firstname'] . " " . $_SESSION['lastname'];

                        $_SESSION['last_login']   = ($record->last_login === '0000-00-00 00:00:00') ? date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') : date_format(date_create($record->last_login), 'd F Y \a\t H:i:s');
                    }
                    
                    $query = $this->conn->prepare('UPDATE admin SET last_login = NOW() WHERE id = :id');
                    $query->execute([':id' => $_SESSION['id']]);
                    echo "<script>location = 'dashboard.php';</script>";
                    Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Logged In', $conn);
                } else {
                    $GLOBALS['error']         = true;
                    $GLOBALS['error_message'] = 'Invalid Login Credentials';
                }
            }catch(PDOException $ex){}
        }

    }