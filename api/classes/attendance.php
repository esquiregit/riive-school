<?php
    require 'conn.php';
    require 'methods.php';
    require 'parent.php';
    require 'pickup.php';
    require 'student.php';
	require 'sms.php';

	class Attendance {
        
        public static function mark_attendance($schoolCode, $students_array, $conn) {
            try {
                for($index = 0; $index < count($students_array); $index++) {
                    $clock_in_time = Date('H:i:s');
                    self::create_attendance($schoolCode, $students_array[$index], 'Present', $clock_in_time, '00:00:00', $conn);
                }

                return true;
            } catch (PDOException $ex) {
                return false;
            }
        }

        public static function after_nine_attedance_marking($schoolCode, $conn) {//die($schoolCode);
            if(Date('l') != 'Sunday' && Date('l') != 'Saturday') {
                if(Date('H') >= 9){
                    $students_array = Student::read_attendance_absent_students($schoolCode, $conn);

                    if($students_array) {die(print_r($students_array));
                        self::mark_absent_attendance($schoolCode, $students_array, $conn);
                    }
                }
            }
        }
        
        public static function mark_absent_attendance($schoolCode, $students_array, $conn) {
            try {
                foreach($students_array as $student) {;
                    $curr_element = $student->studentid;
                    self::create_attendance($schoolCode, $curr_element, 'Absent', '00:00:00', '00:00:00', $conn);
                }

                return true;
            } catch (PDOException $ex) {
                return false;
            }
        }

		public function create_attendance($schoolCode, $student_id, $status, $clock_in_time, $clock_out_time, $conn) {
            $pick_up_code = ($status == 'Present') ? self::get_pickup_code($conn) : '';

			try{
				$query = $conn->prepare('INSERT INTO attendance(`schoolCode`, `student_id`, `status`, `clock_in_time`, `clock_out_time`, `pickUpCode`) VALUES(:schoolCode, :student_id, :status, :clock_in_time, :clock_out_time, :pick_up_code)');
                $query->execute([':schoolCode' => $schoolCode, ':student_id' => $student_id, ':status' => $status, ':clock_in_time' => $clock_in_time, ':clock_out_time' => $clock_out_time, ':pick_up_code' => $pick_up_code]);

                $student = Methods::strtocapital(Student::read_student_name($schoolCode, $student_id, $conn));;
                $parent  = Methods::strtocapital(Parents::read_parent_name($student_id, $conn));
                $contact = Methods::strtocapital(Parents::read_parent_contact($student_id, $conn));
                $message = '';
                
                if(!empty($contact)) {
                    if($status == 'Present') {
                        $message = 'Hello ' . $parent . '. ' . $student . ' has arrived in school. You can access the pickup code on the mobile app. Thank you.';
                    } else if($status == 'Absent') {
                        $message = 'Hello ' . $parent . '. This is to inform you that ' . $student . ' has not arrived in school as at now. Is everything alright?';
                    }
                    SMS::send_sms($message, $contact);
                }

				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

		public function read_attendances($conn){
			try{
				$query = $conn->prepare('SELECT * FROM attendance INNER JOIN student ON attendance.student_id = student.studentid INNER JOIN school ON attendance.schoolCode = school.School_id');
				$query->execute();

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public function read_attendance_by_id($id, $conn){
			try{
				$query = $conn->prepare('SELECT * FROM attendance INNER JOIN student ON attendance.student_id = student.studentid INNER JOIN school ON attendance.schoolCode = school.School_id WHERE id = :id');
				$query->execute([':id' => $id]);

				return $query->fetch(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public function read_attendance_by_student_id($student_id, $conn){
			try{
				$query = $conn->prepare('SELECT * FROM attendance WHERE student_id = :student_id AND schoolCode = :schoolCode');
				$query->execute([':student_id' => $student_id, ':schoolCode' => $_SESSION['riive_school_id']]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

        public function read_attendance_by_class($schoolCode, $class, $conn){
            try{
                $query = $conn->prepare('SELECT at.id, at.Status as status, at.clock_in_time, at.clock_out_time, at.date, at.pickUpCode, st.firstname, st.othernames, st.lastname, st.class, at.student_id, at.schoolCode as schoolCode, st.imagePath, st.image FROM attendance at INNER JOIN student st ON at.student_id = st.studentid INNER JOIN school sc ON at.schoolCode = sc.School_id WHERE schoolCode = :schoolCode AND class = :class ORDER BY at.id desc, at.date desc, at.clock_in_time desc, st.firstname, st.class, at.id');
                $query->execute([':schoolCode' => $schoolCode, ':class' => $class]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_attendance_by_school_id($schoolCode, $conn){
            try{
                $query = $conn->prepare('SELECT at.id, at.Status as status, at.clock_in_time, at.clock_out_time, at.date, at.pickUpCode, st.firstname, st.othernames, st.lastname, st.class, at.student_id, at.schoolCode as schoolCode, st.imagePath, st.image FROM attendance at INNER JOIN student st ON at.student_id = st.studentid INNER JOIN school sc ON at.schoolCode = sc.School_id WHERE schoolCode = :schoolCode ORDER BY at.id desc, at.date desc, at.clock_in_time desc, st.firstname, st.class, at.id');
                $query->execute([':schoolCode' => $schoolCode]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_attendance_by_pickup_code($pickup_code, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM attendance INNER JOIN student ON attendance.student_id = student.studentid INNER JOIN school ON attendance.schoolCode = school.School_id INNER JOIN pickup ON attendance.pickUpCode = pickup.code WHERE pickUpCode = :pickup_code');
                $query->execute([':pickup_code' => $pickup_code]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_attendance_by_date($date, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM attendance WHERE (date like :date OR clock_in_time like :date OR clock_out_time like :date OR date like :date) AND schoolCode = :schoolCode');
                $query->execute([':date' => '%' . $date . '%', ':schoolCode' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_attendance_by_date_for_teacher($date, $class, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM attendance WHERE (date like :date OR clock_in_time like :date OR clock_out_time like :date OR date like :date) AND schoolCode = :schoolCode AND student_id IN (SELECT studentid FROM student WHERE class = :class)');
                $query->execute([':date' => '%' . $date . '%', ':schoolCode' => $_SESSION['riive_school_id'], ':class' => $class]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_countries($conn){
            try{
                $query = $conn->prepare('SELECT id, country_name FROM countries WHERE status = :status ORDER BY country_name ASC');
                $query->execute([':status' => 'Active']);

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

        public function read_schools($conn){
            try{
                $query = $conn->prepare('SELECT School_id, schoolname FROM school');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_student_name_by_id($student_id, $conn){
            try{
                $query  = $conn->prepare('SELECT firstname, lastname, othernames FROM student WHERE studentid = :studentid');
                $query->execute([':studentid' => $student_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return $result->lastname . ' ' . $result->firstname . ' ' . $result->othernames;
            }catch(PDOException $ex){}
        }

        public function read_school_name_by_id($school_id, $conn){
            try{
                $query  = $conn->prepare('SELECT schoolname FROM school WHERE School_id = :school_id');
                $query->execute([':school_id' => $school_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return $result->schoolname;
            }catch(PDOException $ex){}
        }

        public static function update_attendance($id, $schoolCode, $student_id, $status, $conn) {
            try{
                if(strtolower($status) === 'present') {
                    $query = $conn->prepare('UPDATE attendance SET status = :status, clock_in_time = NOW() WHERE id = :id AND schoolCode = :schoolCode AND student_id = :student_id');
                    $query->execute([':status' => $status, ':id' => $id, ':schoolCode' => $schoolCode, ':student_id' => $student_id]);
                } else  {
                    $query = $conn->prepare('UPDATE attendance SET status = :status, clock_in_time = :clock_in_time WHERE id = :id AND schoolCode = :schoolCode AND student_id = :student_id');
                    $query->execute([':status' => $status, ':clock_in_time' => '00:00:00', ':id' => $id, ':schoolCode' => $schoolCode, ':student_id' => $student_id]);
                }

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function clock_out_student($id, $schoolCode, $student_id, $pickup_code, $conn) {
            try{
                $query  = $conn->prepare('UPDATE attendance SET clock_out_time = NOW() WHERE id = :id');
                $query->execute([':id' => $id]);

                $student       = Methods::strtocapital(Student::read_student_name($schoolCode, $student_id, $conn));;
                $parent        = Methods::strtocapital(Parents::read_parent_name($student_id, $conn));
                $contact       = Methods::strtocapital(Parents::read_parent_contact($student_id, $conn));
                $pickup_person = Methods::strtocapital(Pickup::read_pickup_person($pickup_code, $conn));
                if(!empty($contact->phone) || !empty($pickup_person)) {
                    $message = 'Hello ' . $parent->fullname . '. ' . $student . ' has been picked up from school by ' . $pickup_person . '. Thank you.';
                    SMS::send_sms($message, $contact->phone);
                }

                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function get_pickup_code($conn) {
            $alphabets  = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789@#$%^&*()_-+=?><';
            $total      = 1;

            try {
                $query  = $conn->prepare("SELECT COUNT(*) as total FROM attendance");
                $query->execute();
                $result = $query->fetch(PDO::FETCH_OBJ);
                $total  = $total + $result->total;

                return substr(str_shuffle($alphabets), 0, 2) . substr(str_shuffle($alphabets), 0, 2) . $total . substr(str_shuffle($alphabets), 0, 2);
            } catch(PDOException $ex){}
        }

        /*public static function get_pickup_code($conn) {
            $alphabets  = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
            $characters = '123456789@#$%^&*()_-+=?><';
            $total      = 1;

            try {
                $query  = $conn->prepare("SELECT COUNT(*) as total FROM attendance");
                $query->execute();
                $result = $query->fetch(PDO::FETCH_OBJ);
                $total  = $total + $result->total;

                return substr(str_shuffle($alphabets), 0, 2) . substr(str_shuffle($characters), 0, 2) . $total . substr(str_shuffle($alphabets), 0, 2);
            } catch(PDOException $ex){}
        }*/

	}