<?php

	class Audit_Trail {

		public static function create_log($school_id, $teacher_id, $activity, $conn) {
			try{
				$query = $conn->prepare('INSERT INTO audit_trail(school_id, teacher_id, activity, date) VALUES(:school_id, :teacher_id, :activity, NOW())');
				$query->execute([':school_id' => $school_id, ':teacher_id' => $teacher_id, ':activity' => $activity]);
				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

		public static function read_school_logs($school_id, $conn) {
			try{
				$query = $conn->prepare('SELECT ad.id, ad.school_id, ad.teacher_id, ad.activity, ad.date, sc.schoolname, t.name FROM audit_trail ad INNER JOIN school sc ON ad.school_id = sc.School_id LEFT JOIN teacher t ON ad.teacher_id = t.id WHERE ad.school_id = :school_id ORDER BY ad.date desc, ad.id desc');
				$query->execute([':school_id' => $school_id]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		// public static function create_log($user_id, $name, $username, $access_level, $activity, $conn) {
		// 	try{
		// 		$query = $conn->prepare('INSERT INTO audit_trail(user_id, name, username, access_level, activity, date) VALUES(:user_id, :name, :username, :access_level, :activity, NOW())');
		// 		$query->execute([':user_id' => $user_id, ':name' => $name, ':username' => $username, ':access_level' => $access_level, ':activity' => $activity]);
		// 		return true;
		// 	}catch(PDOException $ex){
		// 		return false;
		// 	}
		// }

		// public static function read_school_logs($user_id, $conn) {
		// 	try{
		// 		$query = $conn->prepare('SELECT ad.id, ad.user_id, ad.access_level, ad.activity, ad.date, sc.schoolname, sc.username, t.name FROM audit_trail ad INNER JOIN school sc ON ad.user_id = sc.School_id INNER JOIN teacher t ON ad.user_id = t.id WHERE (ad.access_level = "School" OR ad.access_level = "Teacher") AND ad.user_id = :user_id ORDER BY ad.date desc, ad.id desc');
		// 		$query->execute([':user_id' => $user_id]);
		// 		$result1 = $query->fetchAll(PDO::FETCH_OBJ);

		// 		$query = $conn->prepare('SELECT ad.id, ad.user_id, ad.access_level, ad.activity, ad.date, sc.schoolname, sc.username FROM audit_trail ad INNER JOIN school sc ON ad.user_id = sc.School_id WHERE (ad.access_level = "School" OR ad.access_level = "Teacher") AND ad.user_id = :user_id ORDER BY ad.date desc, ad.id desc');
		// 		$query->execute([':user_id' => $user_id]);
		// 		$result2 = $query->fetchAll(PDO::FETCH_OBJ);

		// 		return $result1 ? $result1 : $result2;
		// 	}catch(PDOException $ex){}
		// }

	}