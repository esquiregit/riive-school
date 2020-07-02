<?php
	require_once "session_variables.php";
	require_once "methods.php";

	class Assessment {

		public static function create_assessment($students_array, $School_id, $class, $term, $academic_year, $subject, $teacher_id, $class_test_array, $assignments_array, $interim_assessment_array, $attendance_array, $exams_array, $conn) {
			try {
				for($index = 0; $index < count($students_array); $index++) {
					$student_id         = $students_array[$index];
					$class_tests        = $class_test_array[$index];
					$assignments        = $assignments_array[$index];
					$interim_assessment = $interim_assessment_array[$index];
					$attendance_mark    = $attendance_array[$index];
					$exams_score        = $exams_array[$index];
					$total_score        = $class_tests + $assignments + $interim_assessment + $attendance_mark + $exams_score;
					$grade   			= self::get_grade($total_score);
					$remarks 			= self::get_remarks(self::get_grade($total_score));

					$query = $conn->prepare('INSERT INTO assessments(student_id, School_id, class, term, academic_year, subject, teacher_id, class_tests, assignments, interim_assessment, attendance_mark, exams_score, total_score, grade, remarks) VALUES(:student_id, :School_id, :class, :term, :academic_year, :subject, :teacher_id, :class_tests, :assignments, :interim_assessment, :attendance_mark, :exams_score, :total_score, :grade, :remarks)');
					$query->execute([':student_id' => $student_id, ':School_id' => $School_id, ':class' => $class, ':term' => $term, ':academic_year' => $academic_year, ':subject' => Methods::strtocapital($subject), ':teacher_id' => $teacher_id, ':class_tests' => $class_tests, ':assignments' => $assignments, ':interim_assessment' => $interim_assessment, ':attendance_mark' => $attendance_mark, ':exams_score' => $exams_score, ':total_score' => $total_score, ':grade' => $grade, ':remarks' => $remarks]);
				}

				return true;
			} catch (PDOException $ex) {
				return false;
			}
		}

        public static function read_assessments_for_admin($conn) {
            try{
                $query = $conn->prepare('SELECT * FROM assessments WHERE School_id = :School_id ORDER BY academic_year DESC, a_id ASC');
                $query->execute([':School_id' => $_SESSION['riive_school_id']]);

            	return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $ex){}
        }

        public static function read_assessments_for_teacher($conn, $teacher_id) {
            try{
                /*$query = $conn->prepare('SELECT * FROM assessments WHERE teacher_id = :teacher_id AND School_id = :School_id AND class = :class ORDER BY academic_year DESC, a_id ASC');
                $query->execute([':teacher_id' => $teacher_id, ':School_id' => $_SESSION['riive_school_id'], ':class' => $_SESSION['riive_school_teacher_class']]);*/
                $query = $conn->prepare('SELECT * FROM assessments WHERE School_id = :School_id AND class = :class ORDER BY academic_year DESC, a_id ASC');
                $query->execute([':School_id' => $_SESSION['riive_school_id'], ':class' => $_SESSION['riive_school_teacher_class']]);

            	return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $ex){}
        }

		public static function read_assessments_for_school($School_id, $class, $conn) {
			try{
				if($class) {
					$query = $conn->prepare('SELECT ass.a_id, ass.student_id, ass.class, ass.term, ass.academic_year, ass.subject, ass.class_tests, ass.assignments, ass.interim_assessment, ass.attendance_mark, ass.exams_score, ass.total_score, ass.grade, ass.remarks, ass.date_entered, ass.last_edit_date, st.firstname, st.lastname, st.othernames FROM assessments ass INNER JOIN student st WHERE ass.student_id = st.studentid AND ass.School_id = :School_id AND ass.class = :class ORDER BY academic_year DESC, class desc, term desc, total_score desc, a_id ASC');
                	$query->execute([':School_id' => $School_id, ':class' => $class]);
				} else {
					$query = $conn->prepare('SELECT ass.a_id, ass.student_id, ass.class, ass.term, ass.academic_year, ass.subject, ass.class_tests, ass.assignments, ass.interim_assessment, ass.attendance_mark, ass.exams_score, ass.total_score, ass.grade, ass.remarks, ass.date_entered, ass.last_edit_date, st.firstname, st.lastname, st.othernames FROM assessments ass INNER JOIN student st WHERE ass.student_id = st.studentid AND ass.School_id = :School_id ORDER BY academic_year DESC, class desc, term desc, total_score desc, a_id ASC');
                	$query->execute([':School_id' => $School_id]);
				}

				return $query->fetchAll(PDO::FETCH_OBJ);
			} catch (PDOException $ex){}
		}

		public static function read_assessment($a_id, $conn) {
			try{
				$query = $conn->prepare('SELECT * FROM assessments WHERE a_id = :a_id');
				$query->execute([':a_id' => $a_id]);

				return $query->fetch(PDO::FETCH_OBJ);
			} catch (PDOException $ex){}
		}

		public static function read_assessments_by_teacher_id($teacher_id, $conn) {
			try{
				$query = $conn->prepare('SELECT * FROM assessments INNER JOIN staff ON assessments.teacher_id = staff.staff_id WHERE assessments.teacher_id = :teacher_id');
				$query->execute([':teacher_id' => $teacher_id]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			} catch (PDOException $ex){}
		}

		public static function read_assessments_by_subject($subject, $conn) {
			try{
				$query = $conn->prepare('SELECT * FROM assessments INNER JOIN subjects ON assessments.subject = subjects.subject WHERE assessments.subject = :subject');
				$query->execute([':subject' => $subject]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			} catch (PDOException $ex){}
		}

		public static function update_assessment($class_tests, $assignments, $interim_assessment, $attendance_mark, $exams_score, $total_score, $grade, $remarks, $a_id, $conn) {
			try{
				// $query = $conn->prepare('UPDATE assessments SET class_tests = :class_tests, assignments = :assignments, interim_assessment = :interim_assessment, attendance_mark = :attendance_mark, exams_score = :exams_score, total_score = :total_score, grade = :grade, remarks = :remarks WHERE a_id = :a_id');
				// $query->execute([':class_tests' => $class_tests, ':assignments' => $assignments, ':interim_assessment' => $interim_assessment, ':attendance_mark' => $attendance_mark, ':exams_score' => $exams_score, ':total_score' => $total_score, ':grade' => $grade, ':remarks' => $remarks, ':a_id' => $a_id]);

				// $query = $conn->prepare('UPDATE assessments SET last_edit_date = NOW() WHERE a_id = :a_id');
				// $query->execute([':a_id' => $a_id]);
				$query = $conn->prepare('UPDATE assessments SET class_tests = :class_tests, assignments = :assignments, interim_assessment = :interim_assessment, attendance_mark = :attendance_mark, exams_score = :exams_score, total_score = :total_score, grade = :grade, remarks = :remarks, last_edit_date = NOW() WHERE a_id = :a_id');
				$query->execute([':class_tests' => $class_tests, ':assignments' => $assignments, ':interim_assessment' => $interim_assessment, ':attendance_mark' => $attendance_mark, ':exams_score' => $exams_score, ':total_score' => $total_score, ':grade' => $grade, ':remarks' => $remarks, ':a_id' => $a_id]);				

				return true;
			} catch (PDOException $ex){
				return false;
			}
		}

		public static function does_assessment_exist($class, $subject, $term, $academic_year, $conn) {
			try{
				$query = $conn->prepare('SELECT * FROM assessments WHERE class = :class AND subject = :subject AND term = :term AND academic_year = :academic_year');
				$query->execute([':class' => $class, ':subject' => $subject, ':term' => $term, ':academic_year' => $academic_year]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			} catch (PDOException $ex){
				return false;
			}
		}

		public static function is_any_element_empty($array) {
			foreach ($array as $element) {
				if($element == '') {
					return true;
				}
			}

			return false;
		}

		public static function is_any_class_test_element_invalid($array) {
			foreach ($array as $element) {
				if($element < 0 || $element > $_SESSION['riive_school_class_tests_max']) {
					return true;
				}
			}

			return false;
		}

		public static function is_any_assignment_element_invalid($array) {
			foreach ($array as $element) {
				if($element < 0 || $element > $_SESSION['riive_school_assignments_max']) {
					return true;
				}
			}

			return false;
		}

		public static function is_any_interim_assessment_element_invalid($array) {
			foreach ($array as $element) {
				if($element < 0 || $element > $_SESSION['riive_school_interim_assessment_max']) {
					return true;
				}
			}

			return false;
		}

		public static function is_any_attendance_element_invalid($array) {
			foreach ($array as $element) {
				if($element < 0 || $element > $_SESSION['riive_school_attendance_mark_max']) {
					return true;
				}
			}

			return false;
		}

		public static function is_any_exams_element_invalid($array) {
			foreach ($array as $element) {
				if($element < 0 || $element > $_SESSION['riive_school_exams_score_max']) {
					return true;
				}
			}

			return false;
		}

		public static function get_grade($total_score) {
			$grade = '';

			if($total_score >= 80) {
	            $grade = 'A';
	        } else if($total_score >= 75) {
	            $grade = 'B+';
	        } else if($total_score >= 70) {
	            $grade = 'B';
	        } else if($total_score >= 65) {
	            $grade = 'C+';
	        } else if($total_score >= 60) {
	            $grade = 'C';
	        } else if($total_score >= 55) {
	            $grade = 'D+';
	        } else if($total_score >= 50) {
	            $grade = 'D';
	        } else if($total_score >= 45) {
	            $grade = 'E+';
	        } else if($total_score >= 40) {
	            $grade = 'E';
	        } else {
	            $grade = 'F';
	        }

	        return $grade;
		}

		public static function get_remarks($grade) {
			$remarks = '';

			if($grade == 'A') {
	            $remarks = 'Outstanding';
	        } else if($grade == 'B+') {
	            $remarks = 'Excellent';
	        } else if($grade == 'B') {
	            $remarks = 'Very Good';
	        } else if($grade == 'C+') {
	            $remarks = 'Good';
	        } else if($grade == 'C') {
	            $remarks = 'Above Average';
	        } else if($grade == 'D+') {
	            $remarks = 'Average';
	        } else if($grade == 'D') {
	            $remarks = 'Pass';
	        } else if($grade == 'E+') {
	            $remarks = 'Poor';
	        } else if($grade == 'E') {
	            $remarks = 'Very Poor';
	        } else {
	            $remarks = 'Fail';
	        }

			return $remarks;
		}

	}

?>