<?php
	require_once 'conn.php';

	class Student {

        public static function create_student($School_id, $school_name, $firstname, $lastname, $othernames, $gender, $dob, $class, $image, $conn){
        	$studentCode = self::get_student_code($school_name, $conn);

            try{
                $query = $conn->prepare('INSERT INTO student(School_id, firstname, lastname, othernames, gender, dob, class, studentCode, imagePath, image) VALUES(:School_id, :firstname, :lastname, :othernames, :gender, :dob, :class, :studentCode, :imagePath, :image)');
                $query->execute([':School_id' => $School_id, ':firstname' => $firstname, ':lastname' => $lastname, ':othernames' => $othernames, ':gender' => $gender, ':dob' => $dob, ':class' => $class, ':studentCode' => $studentCode, ':imagePath' => 'pictures', ':image' => $image]);

                return true;
            }catch(PDOException $ex){
            	return false;
            }
        }

        public static function read_students($conn){
            try{
                $query = $conn->prepare('SELECT st.studentid, st.School_id, st.firstname, st.othernames, st.lastname, st.dob, st.gender, st.class, st.studentCode, st.imagePath, st.image, sc.School_id, sc.schoolname FROM student st INNER JOIN school sc ON st.School_id = sc.School_id ORDER BY st.class asc, st.firstname asc, st.studentid asc');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_students_by_school_id($school_id, $conn){
            try{
                $query = $conn->prepare('SELECT st.studentid, st.School_id, st.firstname, st.othernames, st.lastname, st.dob, st.gender, st.class, st.studentCode, st.imagePath, st.image, sc.School_id FROM student st INNER JOIN school sc ON st.School_id = sc.School_id WHERE st.School_id = :School_id ORDER BY st.firstname asc, st.class asc, st.studentid asc');
                $query->execute([':School_id' => $school_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_students_name_and_ids($conn){
            try{
                $query = $conn->prepare('SELECT studentid, firstname, othernames, lastname FROM student WHERE School_id = :School_id ORDER BY lastname');
                $query->execute([':School_id' => $school_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_assessment_students_by_class($class, $conn){
            try{
                $query = $conn->prepare('SELECT studentid, firstname, othernames, lastname FROM student WHERE class = :class AND School_id = :School_id ORDER BY lastname');
                $query->execute([':class' => $class, ':School_id' => $school_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_schools($conn){
            try{
                $query = $conn->prepare('SELECT * FROM school ORDER BY schoolname');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_student($studentid, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM student INNER JOIN school ON student.School_id = school.School_id WHERE studentid = :studentid');
                $query->execute([':studentid' => $studentid]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public static function read_student_name($school_id, $student_id, $conn){
			try{
				$query  = $conn->prepare('SELECT firstname, othernames, lastname FROM student WHERE School_id = :School_id AND studentid = :studentid');
				$query->execute([':School_id' => $school_id, ':studentid' => $student_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);

				return empty($result->othernames) ? $result->firstname . ' ' . $result->lastname : $result->firstname . ' ' . $result->othernames . ' ' . $result->lastname;
			}catch(PDOException $ex){}
		}

		public static function read_students_by_gender($gender, $conn){
			try{
				$query = $conn->prepare('SELECT * FROM student INNER JOIN school ON student.School_id = school.School_id WHERE student.School_id = :School_id AND gender = :gender');
				$query->execute([':School_id' => $school_id, ':gender' => $gender]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

        public static function read_students_by_class($class, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM student INNER JOIN school ON student.School_id = school.School_id WHERE student.School_id = :School_id AND class = :class');
                $query->execute([':School_id' => $school_id, ':class' => $class]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public static function read_teacher_students($School_id, $class, $conn){
			try{
				$query = $conn->prepare('SELECT * FROM student WHERE School_id = :School_id AND class = :class ORDER BY firstname asc');
				$query->execute([':School_id' => $School_id, ':class' => $class]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

        public function read_school_name_by_id($school_id, $conn){
            try{
                $query  = $conn->prepare('SELECT schoolname FROM school WHERE School_id = :school_id');
                $query->execute([':school_id' => $school_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->schoolname : $result;
            }catch(PDOException $ex){}
        }

        public function read_school_username_by_id($school_id, $conn){
            try{
                $query  = $conn->prepare('SELECT username FROM school WHERE School_id = :school_id');
                $query->execute([':school_id' => $school_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->username : $result;
            }catch(PDOException $ex){}
        }

        public static function read_attendance_students_by_class($School_id, $class, $conn) {
            $today = Date('Y-m-d');

            try{
                $query = $conn->prepare('SELECT studentid, firstname, othernames, lastname FROM student WHERE School_id = :School_id AND class = :class AND studentid NOT IN (SELECT student_id FROM attendance WHERE schoolCode = :schoolCode AND date LIKE :date) ORDER BY lastname;');
                $query->execute([':School_id' => $School_id, ':class' => $class, ':schoolCode' => $School_id, ':date' => '%' . $today . '%']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $ex){}
        }

        public static function read_attendance_absent_students($schoolCode, $conn) {
            $today = Date('Y-m-d');

            try{
                $query = $conn->prepare('SELECT studentid FROM student WHERE studentid NOT IN (SELECT student_id FROM attendance WHERE date LIKE :date) ORDER BY lastname');
                $query->execute([':schoolCode' => $schoolCode, ':schoolCode' => $schoolCode, ':date' => '%' . $today . '%']);

                // $query = $conn->prepare('SELECT studentid FROM student WHERE School_id = :schoolCode AND studentid NOT IN (SELECT student_id FROM attendance WHERE schoolCode = :schoolCode AND date LIKE :date) ORDER BY lastname');
                // $query->execute([':schoolCode' => $schoolCode, ':schoolCode' => $schoolCode, ':date' => '%' . $today . '%']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $ex){}
        }

        public static function update_student($id, $school_id, $firstname, $lastname, $othernames, $gender, $dob, $class, $image, $conn) {
            try{
                $query = $conn->prepare('UPDATE student SET firstname = :firstname, othernames = :othernames, lastname = :lastname, gender = :gender, dob = :dob, class = :class, imagePath = :imagePath, image = :image WHERE studentid = :id AND School_id = :School_id');
                $query->execute([':firstname' => $firstname, ':othernames' => $othernames, ':lastname' => $lastname, ':gender' => $gender, ':dob' => $dob, ':class' => $class, ':imagePath' => 'pictures', ':image' => $image, ':id' => $id, ':School_id' => $school_id]);

                return true;
            }catch(PDOException $ex){
            	return false;
            }
        }

        public static function update_student_without_image($id, $school_id, $firstname, $lastname, $othernames, $gender, $dob, $class, $conn) {
            try{
                $query = $conn->prepare('UPDATE student SET firstname = :firstname, othernames = :othernames, lastname = :lastname, gender = :gender, dob = :dob, class = :class WHERE studentid = :id AND School_id = :School_id');
                $query->execute([':firstname' => $firstname, ':lastname' => $lastname, ':othernames' => $othernames, ':gender' => $gender, ':dob' => $dob, ':class' => $class, ':id' => $id, ':School_id' => $school_id]);

                return true;
            }catch(PDOException $ex){
            	return false;
            }
        }

        public static function get_classes() {
            $array = array(
            				'Creche', 'Nursery 1', 'Nursery 2', 'Kindergarten',
            				'1', '2', '3', '4',
            				'5', '6', 'JHS 1', 'JHS 2', 'JHS 3'
                        );

            return $array;
        }

		public static function get_student_code($school_name, $conn) {
			$alphabets  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			$characters = '01234567890!@#$%^&*()_-+=?><';
			$total      = 1;
            $rand       = rand(0, 99);
            $rand       = ($rand < 10) ? '0' . $rand : $rand;

			try {
				$query  = $conn->prepare("SELECT COUNT(*) as total FROM student");
				$query->execute();
				$result = $query->fetch(PDO::FETCH_OBJ);
				$total  = $total + $result->total;

				//return substr(str_shuffle($alphabets), 0, 2) . $total . substr(str_shuffle($alphabets), 0, 2) . rand(10, 99);
				// substr(str_shuffle($alphabets), 0, 1) . substr(str_shuffle($characters), 0, 3) . $total . substr(str_shuffle($alphabets), 0, 1) . rand(10, 99);
                //ESQ/190 000
                return strtoupper(substr($school_name, 0, 3)) . '/' . substr(Date('Y'), 2, 3) . '0' . $rand . $total;
			} catch(PDOException $ex){}
		}

        public static function get_academic_years() {
            $array        = array();
            $current_year = Date('Y');
            $start_year   = date_format(date_sub(date_create($current_year), date_interval_create_from_date_string('5 years')), 'Y');
            $end_year     = date_format(date_add(date_create($current_year), date_interval_create_from_date_string('5 years')), 'Y');

            for($year = $start_year; $year <= $current_year; $year++) {
                array_push($array, $year . '/' . ($year + 1));
            }
            return $array;
        }

	}