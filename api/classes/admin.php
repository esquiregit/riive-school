<?php
	require_once 'conn.php';

	class Admin {

		public function create_admin($username, $password, $firstname, $lastname, $image_name, $access_level, $conn){
            $now = Date('Y-m-d H:i:s');

			try{
				$query = $conn->prepare('INSERT INTO admin(`username`, `password`, `firstname`, `lastname`, `photo`, `created_on`, `access_level`, `last_login`, `status`) VALUES(:username, :password, :firstname, :lastname, :photo, :created_on, :access_level, :last_login, :status)');
                $query->execute([':username' => strtolower($username), ':password' => md5($password), ':firstname' => Methods::strtocapital($firstname), ':lastname' => Methods::strtocapital($lastname), ':photo' => $image_name, ':created_on' => $now, ':access_level' => $access_level, ':last_login' => '0000-00-00 00:00:00', ':status' => 'Active']);

				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

		public function read_active_admins($conn){
			try{
				$query = $conn->prepare('SELECT * FROM admin WHERE status = :status ORDER BY access_level');
				$query->execute([':status' => 'Active']);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

        public function read_blocked_admins($conn){
            try{
                $query = $conn->prepare('SELECT * FROM admin WHERE status = :status ORDER BY access_level');
                $query->execute([':status' => 'Inactive']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_all_admins($conn){
            try{
                $query = $conn->prepare('SELECT * FROM admin ORDER BY access_level');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public function read_admins_id($conn){
			try{
				$query = $conn->prepare('SELECT id FROM admin');
				$query->execute();

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public function read_admin($id, $conn){
			try{
				$query = $conn->prepare('SELECT * FROM admin WHERE id = :id');
				$query->execute([':id' => $id]);

				return $query->fetch(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public function block_admin($id, $conn){
			try{                
                $query = $conn->prepare('UPDATE admin SET status = :status WHERE id = :id');
			    $query->execute([':id' => $id, ':status' => 'Inactive']);

				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

        public function unblock_admin($id, $conn){
            try{                
                $query = $conn->prepare('UPDATE admin SET status = :status WHERE id = :id');
                $query->execute([':id' => $id, ':status' => 'Active']);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public function update_admin_with_password($username, $password, $firstname, $lastname, $photo, $id, $conn){
            try{                
                $query = $conn->prepare('UPDATE admin SET username = :username, password = :password, firstname = :firstname, lastname = :lastname, photo = :photo WHERE id = :id');
                $query->execute([':username' => $username, ':password' => md5($password), ':firstname' => $firstname, ':lastname' => $lastname, ':photo' => $photo, ':id' => $id]);

                return true;
            }catch(PDOException $ex){die($ex);
                return false;
            }
        }

        public function update_admin_without_password($username, $firstname, $lastname, $photo, $id, $conn){
            try{                
                $query = $conn->prepare('UPDATE admin SET username = :username, firstname = :firstname, lastname = :lastname, photo = :photo WHERE id = :id');
                $query->execute([':username' => $username, ':firstname' => $firstname, ':lastname' => $lastname, ':photo' => $photo, ':id' => $id]);

                return true;
            }catch(PDOException $ex){die($ex);
                return false;
            }
        }

        public function is_username_taken($id, $username, $conn){
            try{
                $query  = $conn->prepare('SELECT * FROM admin WHERE username = :username');
                $query->execute([':username' => $username]);
                $result = $query->fetch(PDO::FETCH_OBJ);

                if($result) {
                    return ($result->id == $id) ? 0 : 1;
                }

                return 0;
            }catch(PDOException $e){}
        }

	}