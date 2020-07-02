<?php
	if($_SESSION['riive_school_access_level'] == 'Teacher') {
		if(Date('l') != 'Sunday' && Date('l') != 'Saturday') {
			if(Date('H') >= 9){
				require_once "classes/conn.php";
				require_once "classes/attendance.php";
				require_once "classes/student.php";
				require_once "classes/teacher.php";

				$conn           = $pdo->open();
			    $students_array = Student::read_attendance_absent_students_by_class($_SESSION['riive_school_teacher_class'], $conn);
				$teacher_class  = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);

				if($students_array) {
					Attendance::mark_absent_attendance($students_array, $_SESSION['riive_school_user_id'], $teacher_class, $conn)
				}
			

				$pdo->close();
			}
		}
	}
?>