<?php
    require_once 'conn.php';
	require_once 'methods.php';

	class Pickup {

		public function create_pickup($school_code, $pick_up_person, $phone, $code, $student_id, $date, $image_path, $image, $sent_by, $conn){
            $now = Date('Y-m-d H:i:s');

			try{
				$query = $conn->prepare('INSERT INTO pickup(`schoolCode`, `pickUpPerson`, `phone`, `code`, `student_id`, `created_on`, `date`, `image_path`, `image`, `sentBy`) VALUES(:school_code, :pick_up_person, :phone, :code, :student_id, :created_on, :date, :image_path, :image, :sent_by)');
                $query->execute([':school_code' => $school_code, ':pick_up_person' => $pick_up_person, ':phone' => $phone, ':code' => $code, ':student_id' => $student_id, ':created_on' => $now, ':date' => $date, ':image_path' => '0000-00-00 00:00:00', ':image' => $image, ':sent_by' => $sent_by]);

				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

        public function read_pickups($schoolCode, $class, $conn){
            try{
                if(!empty($class)) {//die('schoolCode: '.$schoolCode.' = class: '.$class);
                    $query = $conn->prepare('SELECT p.id, p.schoolCode, p.pickUpPerson, p.pickUpType, p.phone, p.code, p.student_id, p.date, p.imagePath, p.image, p.sentBy, st.firstname, st.othernames, st.lastname, st.class, pa.fullname FROM pickup p INNER JOIN student st ON p.student_id = st.studentid INNER JOIN parent pa ON p.sentBy = pa.parentid WHERE p.schoolCode = :schoolCode AND st.class = :class ORDER BY p.date, p.pickUpPerson, st.firstname');
                    $query->execute([':schoolCode' => $schoolCode, ':class' => $class]);
                } else  {//die('schoolCode: '.$schoolCode.' = class: '.$class);
                    $query = $conn->prepare('SELECT p.id, p.schoolCode, p.pickUpPerson, p.pickUpType, p.phone, p.code, p.student_id, p.date, p.imagePath, p.image, p.sentBy, st.firstname, st.othernames, st.lastname, st.class, pa.fullname FROM pickup p INNER JOIN student st ON p.student_id = st.studentid INNER JOIN parent pa ON p.sentBy = pa.parentid WHERE p.schoolCode = :schoolCode ORDER BY p.date, p.pickUpPerson, st.firstname');
                    $query->execute([':schoolCode' => $schoolCode]);
                }

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        // public function read_pickups($schoolCode, $class, $conn){
        //     try{
        //         if(!empty($class)) {
        //             $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid WHERE schoolCode = :schoolCode');
        //             $query->execute([':schoolCode' => $schoolCode]);
        //         } else  {
        //             $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid WHERE schoolCode = :schoolCode');
        //             $query->execute([':schoolCode' => $schoolCode]);
        //         }

        //         return $query->fetchAll(PDO::FETCH_OBJ);
        //     }catch(PDOException $ex){}
        // }

        public function read_pickup($id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid INNER JOIN school ON pickup.schoolCode = school.School_id INNER JOIN countries ON school.country = countries.id WHERE pickup.id = :id');
                $query->execute([':id' => $id]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public function read_pickup_person($pickup_code, $conn){
			try{
				$query  = $conn->prepare('SELECT pickUpPerson FROM pickup WHERE code = :code');
				$query->execute([':code' => $pickup_code]);
                $result = $query->fetch(PDO::FETCH_OBJ);

				return ($result) ? Methods::strtocapital($result->pickUpPerson) : '';
			}catch(PDOException $ex){}
		}

        public function read_pickup_by_pickup_code($pickup_code, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid INNER JOIN school ON pickup.schoolCode = school.School_id WHERE (code = :pickup_code OR code LIKE :pickupcode) AND schoolCode = :schoolCode');
                $query->execute([':pickup_code' => $pickup_code, ':pickupcode' => '%' . $pickup_code . '%', ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_pickup_by_pickup_person($pickup_person, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid INNER JOIN school ON pickup.schoolCode = school.School_id WHERE (pickUpPerson = :pickup_person Or pickUpPerson LIKE :pickupperson) AND schoolCode = :schoolCode');
                $query->execute([':pickup_person' => $pickup_person, ':pickupperson' => '%' . $pickup_person . '%', ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_pickup_by_school_id($school_id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup WHERE schoolCode = :school_id');
                $query->execute([':school_id' => $school_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_pickup_by_student_id($student_id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid INNER JOIN school ON pickup.schoolCode = school.School_id WHERE student_id = :student_id');
                $query->execute([':student_id' => $student_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_pickup_by_date($date, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup INNER JOIN student ON pickup.student_id = student.studentid INNER JOIN parent ON pickup.sentBy = parent.parentid INNER JOIN school ON pickup.schoolCode = school.School_id WHERE pickup.date LIKE :date AND schoolCode = :schoolCode');
                $query->execute([':date' => '%' . $date . '%', ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_pickup_by_sent_by($sent_by, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM pickup WHERE sent_by LIKE :sent_by');
                $query->execute([':sent_by' => '%' . $sent_by . '%']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }


        public function read_pickup_persons($conn){
            try{
                $query = $conn->prepare('SELECT id, pickUpPerson FROM pickup');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_students($conn){
            try{
                $query = $conn->prepare('SELECT studentid, firstname, lastname, othernames FROM student');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_student_name_by_id($student_id, $conn){
            try{
                $query  = $conn->prepare('SELECT firstname, lastname, othernames FROM student WHERE studentid = :studentid');
                $query->execute([':studentid' => $student_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->lastname . ' ' . $result->firstname . ' ' . $result->othernames : $result;
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

        public function read_parent_name_by_id($parent_id, $conn){
            try{
                $query  = $conn->prepare('SELECT fullname FROM parent WHERE parentid = :parent_id');
                $query->execute([':parent_id' => $parent_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->fullname : $result;
            }catch(PDOException $ex){}
        }

        public function read_countries($conn){
            try{
                $query = $conn->prepare('SELECT id, country_name FROM countries WHERE status = :status ORDER BY country_name ASC');
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

	}