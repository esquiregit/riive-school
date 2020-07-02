<?php
	require_once "conn.php";

	class Dashboard {
		public static function get_profile_image($School_id, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM school WHERE School_id = :School_id");
				$query->execute([':School_id' => $School_id]);
                
                $_SESSION['riive_school_image'] = $query->fetch(PDO::FETCH_OBJ)->image;
			} catch(PDOException $ex) {
				return 0;
			}
		}

		public static function get_total_students($School_id, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM student WHERE School_id = :School_id");
				$query->execute([':School_id' => $School_id]);

				return $query->rowCount();
			} catch(PDOException $ex) {
				return 0;
			}
		}

		public static function get_total_teachers($School_id, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM teacher WHERE schoolCode = :schoolCode");
				$query->execute([':schoolCode' => $School_id]);

				return $query->rowCount();
			} catch(PDOException $ex) {
				return 0;
			}
		}

		public static function get_total_security($School_id, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM security WHERE schoolCode = :schoolCode");
				$query->execute([':schoolCode' => $School_id]);

				return $query->rowCount();
			} catch(PDOException $ex) {
				return 0;
			}
		}

		public static function get_total_students_by_gender($School_id, $gender, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM student WHERE School_id = :School_id AND gender = :gender");
				$query->execute([':School_id' => $School_id, ':gender' => $gender]);

				return $query->rowCount();
			} catch(PDOException $ex) {
				return 0;
			}
		}

		public static function get_total_students_by_class_and_gender($School_id, $class, $gender, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM student WHERE School_id = :School_id AND class = :class AND gender = :gender");
				$query->execute([':School_id' => $School_id, ':class' => $class, ':gender' => $gender]);

				return $query->rowCount();
			} catch(PDOException $ex) {
				return 0;
			}
		}

		public static function get_total_students_by_class($School_id, $class, $conn) {
			try {
				$query = $conn->prepare("SELECT * FROM student WHERE School_id = :School_id AND class = :class");
				$query->execute([':School_id' => $School_id, ':class' => $class]);

				return $query->rowCount();
			} catch(PDOException $ex) {
				return 0;
			}
		}
	}

?>