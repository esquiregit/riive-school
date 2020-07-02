<?php
    require_once 'conn.php';
	require_once 'methods.php';

	class Parents {

        public function read_parents($School_id, $conn){
            try{
                $query = $conn->prepare('SELECT parent.parentid, parent.fullname, parent.phone, parent.email, parent.location, parent.longitude, parent.latitude, parent.regCountry, parent.occupation, parent.relation, parent.dor, parent.lastLocUpdate, parent.status, parent_child.parentID, parent_child.studentID, student.School_id, student.studentid, student.firstname, student.othernames, student.lastname, school.School_id FROM parent, parent_child, student, school WHERE student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                $query->execute([':School_id' => $School_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_parent($School_id, $parentid, $conn){
            try{
                $query = $conn->prepare('SELECT parent.parentid, parent.fullname, parent.phone, parent.email, parent.location, parent.longitude, parent.latitude, parent.regCountry, parent.occupation, parent.relation, parent.dor, parent.lastLocUpdate, parent.status, parent_child.parentID, parent_child.studentID, parent_child.payment_model, student.School_id, student.studentid, student.firstname, student.othernames, student.lastname, school.School_id, school.schoolname FROM parent, parent_child, student, school WHERE parent.parentid = :parentid AND student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                $query->execute([':parentid' => $parentid, ':School_id' => $School_id]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_class_students_parents($School_id, $class, $conn){
            try{
                if(!empty($class)) {
                    $query = $conn->prepare('SELECT parent.fullname, parent.phone, parent.email, parent.location, parent.occupation, parent.relation, parent.status, parent_child.parentID, parent_child.studentID, student.School_id, student.firstname, student.othernames, student.lastname, student.class, school.School_id FROM parent, parent_child, student, school WHERE student.class = :class AND student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                    $query->execute([':class' => $class, ':School_id' => $School_id]);
                } else {
                    $query = $conn->prepare('SELECT parent.fullname, parent.phone, parent.email, parent.location, parent.occupation, parent.relation, parent.status, parent_child.parentID, parent_child.studentID, student.School_id, student.firstname, student.othernames, student.lastname, student.class, school.School_id FROM parent, parent_child, student, school WHERE student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                    $query->execute([':School_id' => $School_id]);
                }

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){die($ex);}
        }

        public function read_parents_by_class($School_id, $class, $conn){
            try{
                $query = $conn->prepare('SELECT parent.parentid, parent.fullname, parent.phone, parent.email, parent.location, parent.longitude, parent.latitude, parent.regCountry, parent.occupation, parent.relation, parent.dor, parent.lastLocUpdate, parent.status, parent_child.parentID, parent_child.studentID, student.School_id, student.studentid, student.firstname, student.othernames, student.lastname, student.class, school.School_id FROM parent, parent_child, student, school WHERE student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id AND student.class = :class');
                $query->execute([':School_id' => $School_id, ':class' => $class]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_parent_by_student_id($student_id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM parent_child WHERE studentID = :student_id');
                $query->execute([':student_id' => $student_id]);

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

        public function read_parent_name($student_id, $conn){
            try{
                $query = $conn->prepare('SELECT fullname FROM `parent` WHERE parentid = (SELECT parentID From parent_child WHERE studentID = (SELECT studentid From student WHERE studentid =  :studentid))');
                $query->execute([':studentid' => $student_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);

                return ($result) ? $result->fullname : '';
            }catch(PDOException $ex){}
        }

        public function read_parent_contact($student_id, $conn){
            try{
                $query = $conn->prepare('SELECT phone FROM `parent` WHERE parentid = (SELECT parentID From parent_child WHERE studentID = (SELECT studentid From student WHERE studentid =  :studentid))');
                $query->execute([':studentid' => $student_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return $result ? $result->phone : '';
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

        public function read_student_id_by_parent_id($parent_id, $conn){
            try{
                $query  = $conn->prepare('SELECT studentID FROM parent_child WHERE parentID = :parent_id');
                $query->execute([':parent_id' => $parent_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return $result->studentID;
            }catch(PDOException $ex){}
        }

        public function read_school_id_by_student_id($student_id, $conn){
            try{
                $query  = $conn->prepare('SELECT School_id FROM student WHERE studentid = :student_id');
                $query->execute([':student_id' => $student_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return $result->School_id;
            }catch(PDOException $ex){}
        }

        public static function get_students_classes() {
            $array = array(
                            'Creche', 'Nursery 1', 'Nursery 2', 'Kindergarten',
                            '1', '2', '3', '4',
                            '5', '6', 'JHS 1', 'JHS 2', 'JHS 3'
                        );

            return $array;
        }

        public function read_parents_for_school_message($School_id, $conn){
            try{
                $query = $conn->prepare('SELECT pa.parentid, pa.fullname, pa.phone, pa.email, pa.relation, pc.studentID, pc.parentID, st.firstname, st.othernames, st.lastname FROM parent pa INNER JOIN parent_child pc ON pa.parentid = pc.parentID INNER JOIN student st ON pc.studentID = st.studentid INNER JOIN school sc ON st.School_id = sc.School_id WHERE sc.School_id = :School_id ORDER BY st.firstname');
                $query->execute([':School_id' => $School_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_parents_for_teacher_message($School_id, $class, $conn){
            try{
                $query = $conn->prepare('SELECT pa.parentid, pa.fullname, pa.phone, pa.email, pa.relation, pc.studentID, pc.parentID, st.firstname, st.othernames, st.lastname FROM parent pa INNER JOIN parent_child pc ON pa.parentid = pc.parentID INNER JOIN student st ON pc.studentID = st.studentid INNER JOIN school sc ON st.School_id = sc.School_id WHERE sc.School_id = :School_id AND st.class = :class ORDER BY st.firstname');
                $query->execute([':School_id' => $School_id, ':class' => $class]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

	}