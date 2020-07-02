<?php
	require_once 'conn.php';

	class Visitor {

		public function create_visitor($country_code, $country_name, $conn){
			try{
				$query = $conn->prepare('INSERT INTO visitor(`country_code`, `country_name`, `status`) VALUES(:country_code, :country_name, :status)');
                $query->execute([':country_code' => strtoupper($country_code), ':country_name' => Methods::strtocapital($country_name), ':status' => 'Active']);

				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

		public function read_visitors($conn){
			try{
				$query = $conn->prepare('SELECT * FROM visitor');
				$query->execute();

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public function read_visitor_by_id($id, $conn){
			try{
				$query = $conn->prepare('SELECT * FROM visitor INNER JOIN school ON visitor.schoolID = school.School_id INNER JOIN countries ON school.country = countries.id INNER JOIN security ON visitor.securityPersonId = security.id WHERE visitor.id = :id');
				$query->execute([':id' => $id]);

				return $query->fetch(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

        public function read_visitors_by_school($schoolID, $conn){
            try{
                $query = $conn->prepare('SELECT v.id, v.visitorName, v.visitorNumber, v.personToVisit, v.securityPersonId, v.clockInTime, v.clockOutTime, v.purposeOfVisit, v.schoolID, v.imagePath, v.image, sec.name FROM visitor v INNER JOIN security sec ON v.securityPersonId = sec.id WHERE v.schoolID = :schoolID');
                $query->execute([':schoolID' => $schoolID]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        /*public function read_visitors_by_school($schoolID, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM visitor WHERE schoolID = :id');
                $query->execute([':schoolID' => $schoolID]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }*/

        public function read_visitor_by_name($name, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM visitor WHERE visitorName LIKE :name AND schoolID = :schoolID');
                $query->execute([':name' => '%' . $name . '%', ':schoolID' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public function read_visitor_by_date($date, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM visitor WHERE (clockInTime LIKE :date OR clockOutTime LIKE :date) AND schoolID = :schoolID');
                $query->execute([':date' => '%' . $date . '%', ':schoolID' => $_SESSION['riive_school_id']]);

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

        public function read_schools($conn){
            try{
                $query = $conn->prepare('SELECT * FROM school');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public function read_school_by_id($id, $conn){
			try{
				$query = $conn->prepare('SELECT schoolname FROM school WHERE School_id = :id');
				$query->execute([':id' => $id]);

				return $query->fetch(PDO::FETCH_OBJ)->schoolname;
			}catch(PDOException $ex){}
		}

		public function read_security_by_id($id, $conn){
			try{
				$query = $conn->prepare('SELECT name FROM security WHERE id = :id');
				$query->execute([':id' => $id]);

				return $query->fetch(PDO::FETCH_OBJ)->name;
			}catch(PDOException $ex){}
		}

	}

?>