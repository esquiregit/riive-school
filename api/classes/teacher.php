<?php
	require_once 'conn.php';

	class Teacher {

        public static function login_teacher($username, $password, $conn){
            try{
                $query    = $conn->prepare('SELECT t.id, t.name, t.contact, t.email, t.country_id, t.username, t.schoolCode, t.accountType, t.status, t.reset_code, t.image, c.country_name, sc.schoolname, tc.class FROM teacher t INNER JOIN school sc ON t.schoolCode = sc.School_id INNER JOIN countries c ON t.country_id = c.id INNER JOIN teacher_class tc ON t.id = tc.teacher_id WHERE (t.username = :username || t.email = :username) and t.password = :password AND t.status = :status');
                $query->execute([':username' => $username, ':password' => $password, ':status' => 'Active']);
                $result1 = $query->fetch(PDO::FETCH_OBJ);

                $query    = $conn->prepare('SELECT t.id, t.name, t.contact, t.email, t.country_id, t.username, t.schoolCode, t.accountType, t.status, t.reset_code, t.image, c.country_name, sc.schoolname FROM teacher t INNER JOIN school sc ON t.schoolCode = sc.School_id INNER JOIN countries c ON t.country_id = c.id WHERE (t.username = :username || t.email = :username) and t.password = :password AND t.status = :status');
                $query->execute([':username' => $username, ':password' => $password, ':status' => 'Active']);
                $result2 = $query->fetch(PDO::FETCH_OBJ);

                return $result1 ? $result1 : $result2;
            }catch(PDOException $ex){}
        }

        public static function create_teacher($name, $email, $contact, $country_id, $username, $schoolCode, $conn) {
            $reset_code = self::get_reset_code($conn);

            try{
                $query = $conn->prepare('INSERT INTO teacher(name, email, contact, country_id, username, password, schoolCode, accountType, reset_code, image) VALUES(:name, :email, :contact, :country_id, :username, :password, :schoolCode, :accountType, :reset_code, :image)');
                $query->execute([':name' => $name, ':email' => $email, ':contact' => $contact, ':country_id' => $country_id, ':username' => $username, ':password' => md5('12345678'), ':schoolCode' => $schoolCode, ':accountType' => 'Teacher', ':reset_code' => $reset_code, ':image' => 'pictures/avatar.png']);

                return true;
            } catch(PDOException $ex){die($ex);
                return false;
            }
        }

        public static function read_teachers($conn){
            try{
                $query = $conn->prepare('SELECT * FROM teacher WHERE teacher.schoolCode = :schoolCode');
                $query->execute([':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teachers_by_school_id($School_id, $conn){
            try{
                $query = $conn->prepare('SELECT t.id, t.name, t.contact, t.email, t.country_id, t.username, t.schoolCode, t.accountType, t.status, t.reset_code, t.image, sc.School_id, sc.schoolname, c.country_name FROM teacher t INNER JOIN school sc ON t.schoolCode = sc.School_id INNER JOIN countries c ON t.country_id = c.id WHERE t.schoolCode = :School_id ORDER BY t.status asc, t.name asc, t.id asc');
                $query->execute([':School_id' => $School_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teachers_contacts($conn){
            try{
                $query = $conn->prepare('SELECT name, contact FROM teacher WHERE teacher.schoolCode = :schoolCode');
                $query->execute([':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_non_assigned_teachers_ids_and_names($schoolCode, $conn){
            try{
                $query = $conn->prepare('SELECT id, name FROM teacher WHERE id NOT IN (SELECT teacher_id FROM teacher_class) AND teacher.schoolCode = :schoolCode ORDER BY name asc, id asc');
                $query->execute([':schoolCode' => $schoolCode]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teachers_ids_and_names($conn){
            try{
                $query = $conn->prepare('SELECT id, name FROM teacher WHERE teacher.schoolCode = :schoolCode');
                $query->execute([':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teacher($schoolCode, $id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM teacher WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':id' => $id, ':schoolCode' => $schoolCode]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teacher_after_profile_update($id, $conn){
            $user = array();

            try{
                $query    = $conn->prepare('SELECT t.id, t.name, t.contact, t.email, t.country_id, t.username, t.schoolCode, t.accountType, t.status, t.reset_code, t.image, c.country_name, sc.schoolname, tc.class FROM teacher t INNER JOIN school sc ON t.schoolCode = sc.School_id INNER JOIN countries c ON t.country_id = c.id INNER JOIN teacher_class tc ON t.id = tc.teacher_id WHERE t.id = :id');
                $query->execute([':id' => $id]);
                $result1 = $query->fetch(PDO::FETCH_OBJ);

                $query    = $conn->prepare('SELECT t.id, t.name, t.contact, t.email, t.country_id, t.username, t.schoolCode, t.accountType, t.status, t.reset_code, t.image, c.country_name, sc.schoolname FROM teacher t INNER JOIN school sc ON t.schoolCode = sc.School_id INNER JOIN countries c ON t.country_id = c.id WHERE t.id = :id');
                $query->execute([':id' => $id]);
                $result2 = $query->fetch(PDO::FETCH_OBJ);

                $teacher = $result1 ? $result1 : $result2;

                if($teacher) {
                    array_push($user, array(
                        "id"           => $teacher->id,
                        "name"         => $teacher->name,
                        "class"        => @$teacher->class ? $teacher->class : '',
                        "contact"      => $teacher->contact,
                        "email"        => $teacher->email,
                        "country_id"   => $teacher->id,
                        "username"     => $teacher->username,
                        "country_name" => $teacher->country_name,
                        "school_id"    => $teacher->schoolCode,
                        "school_name"  => $teacher->schoolname,
                        "access_level" => $teacher->accountType,
                        "status"       => $teacher->status,
                        "reset_code"   => $teacher->reset_code,
                        "image"        => (empty($teacher->image)) ? "pictures/avatar.png" : $teacher->image
                    ));
                }

                return $user;
            }catch(PDOException $ex){}
        }

        public function read_teacher_username_by_id($teacher_id, $conn){
            try{
                $query  = $conn->prepare('SELECT username FROM teacher WHERE id = :teacher_id');
                $query->execute([':teacher_id' => $teacher_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->username : $result;
            }catch(PDOException $ex){}
        }

        public static function read_teacher_two($id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM teacher WHERE id = :id AND teacher.schoolCode = :schoolCode');
                $query->execute([':id' => $id, ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public static function read_teacher_name($teacher_id, $conn) {
			try{
				$query = $conn->prepare('SELECT name FROM teacher WHERE id = :id AND schoolCode = :schoolCode');
				$query->execute([':id' => $teacher_id, ':schoolCode' => $_SESSION['riive_school_id']]);

				return $query->fetch(PDO::FETCH_OBJ)->name;
			}catch(PDOException $ex){}
		}

        public function read_country_name_by_id($id, $conn){
            try{
                $query  = $conn->prepare('SELECT country_name FROM countries WHERE id = :id');
                $query->execute([':id' => $id]);
                $result = $query->fetch(PDO::FETCH_OBJ);

                return ($result) ? $result->country_name : '';
            }catch(PDOException $ex){}
        }

        public static function update_teacher($id, $schoolCode, $name, $email, $contact, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET name = :name, email = :email, contact = :contact WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':name' => $name, ':email' => $email, ':contact' => $contact, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function change_teacher_status($id, $schoolCode, $status, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET status = :status WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':status' => $status, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_teacher_with_password_and_image($id, $schoolCode, $name, $email, $contact, $username, $password, $image, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET name = :name, email = :email, contact = :contact, username = :username, password = :password, image = :image WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':name' => $name, ':email' => $email, ':contact' => $contact, ':username' => $username, ':password' => $password, ':image' => $image, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_teacher_with_no_password_and_image($id, $schoolCode, $name, $email, $contact, $username, $image, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET name = :name, email = :email, contact = :contact, username = :username, image = :image WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':name' => $name, ':email' => $email, ':contact' => $contact, ':username' => $username, ':image' => $image, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_teacher_with_password_and_no_image($id, $schoolCode, $name, $email, $contact, $username, $password, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET name = :name, email = :email, contact = :contact, username = :username, password = :password WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':name' => $name, ':email' => $email, ':contact' => $contact, ':username' => $username, ':password' => $password, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_teacher_with_no_password_and_no_image($id, $schoolCode, $name, $email, $contact, $username, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET name = :name, email = :email, contact = :contact, username = :username WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':name' => $name, ':email' => $email, ':contact' => $contact, ':username' => $username, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function change_teacher_password($teacher_id, $password, $reset_code, $conn){
            try{
                $query  = $conn->prepare('UPDATE teacher SET password = :password WHERE id = :teacher_id AND reset_code = :reset_code');
                $query->execute([':password' => $password, ':teacher_id' => $teacher_id, ':reset_code' => $reset_code]);
                
                return true;
            }catch(PDOException $ex){die($ex);
                return false;
            }
        }

        public static function get_account_types() {
            $array = array(
                            'Teacher'
                        );

            return $array;
        }

        /*-- Teacher Class Assignment --*/
        public static function assign_teacher($schoolCode, $teacher_id, $class, $conn) {
            try{
                $query = $conn->prepare('INSERT INTO teacher_class(teacher_id, class, schoolCode) VALUES(:teacher_id, :class, :schoolCode)');
                $query->execute([':teacher_id' => $teacher_id, ':class' => $class, ':schoolCode' => $schoolCode]);

                return true;
            } catch(PDOException $ex){
                return false;
            }
        }

        public static function read_teacher_class_assignment($schoolCode, $conn){
            try{
                $query = $conn->prepare('SELECT tc.id, tc.teacher_id, tc.class, tc.schoolCode, sc.School_id, sc.schoolname, t.name FROM teacher_class tc INNER JOIN school sc ON tc.schoolCode = sc.School_id INNER JOIN teacher t ON tc.teacher_id = t.id WHERE tc.schoolCode = :schoolCode ORDER BY t.name asc, tc.class asc, t.id asc');
                $query->execute([':schoolCode' => $schoolCode]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teachers_class($schoolCode, $class, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM teacher_class WHERE schoolCode = :schoolCode');
                $query->execute([':schoolCode' => $schoolCode]);


                $query = $conn->prepare('SELECT tc.id, tc.teacher_id, tc.class, tc.schoolCode, sc.School_id, sc.schoolname, t.name FROM teacher_class tc INNER JOIN school sc ON tc.schoolCode = sc.School_id INNER JOIN teacher t ON tc.teacher_id = t.id WHERE tc.schoolCode = :schoolCode AND tc.class = :class ORDER BY t.name asc, t.id asc');
                $query->execute([':schoolCode' => $schoolCode, ':class' => $class]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_teacher_class($id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM teacher_class WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':id' => $id, ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_assigned_class($teacher_id, $conn){
            try{
                $query = $conn->prepare('SELECT class FROM teacher_class WHERE teacher_id = :teacher_id AND schoolCode = :schoolCode');
                $query->execute([':teacher_id' => $teacher_id, ':schoolCode' => $_SESSION['riive_school_id']]);
                $result = $query->fetch(PDO::FETCH_OBJ);

                return ($result) ? $result->class : '';
            }catch(PDOException $ex){}
        }

        public static function has_class_been_assigned($schoolCode, $class, $conn) {
            try{
                $query = $conn->prepare('SELECT * FROM teacher_class WHERE class = :class AND schoolCode = :schoolCode');
                $query->execute([':class' => $class, ':schoolCode' => $schoolCode]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function has_this_class_been_assigned($id, $schoolCode, $class, $conn) {
            try{
                $query = $conn->prepare('SELECT * FROM teacher_class WHERE id = :id AND class = :class AND schoolCode = :schoolCode');
                $query->execute([':id' => $id, ':class' => $class, ':schoolCode' => $schoolCode]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function has_teacher_been_assigned_to_different_class($teacher_id, $class, $conn) {
            try{
                $query = $conn->prepare('SELECT * FROM teacher_class WHERE teacher_id = :teacher_id AND class != :class AND schoolCode = :schoolCode');
                $query->execute([':teacher_id' => $teacher_id, ':class' => $class, ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function update_assigned_teacher_class($id, $schoolCode, $teacher_id, $class, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher_class SET teacher_id = :teacher_id, class = :class WHERE id = :id AND schoolCode = :schoolCode');
                $query->execute([':teacher_id' => $teacher_id, ':class' => $class, ':id' => $id, ':schoolCode' => $schoolCode]);

                return true;
            } catch (PDOException $ex){
                return false;
            }
        }

        public static function get_reset_code($conn) {
            $alphabets  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890';
            $total      = 1;

            try {
                $query  = $conn->prepare("SELECT COUNT(*) as total FROM teacher");
                $query->execute();
                $result = $query->fetch(PDO::FETCH_OBJ);
                $total  = $total + $result->total;

                return substr(str_shuffle($alphabets), 0, 4) . $total . rand(10, 99) . substr(str_shuffle($alphabets), 0, 4);
            } catch(PDOException $ex){}
        }

	}