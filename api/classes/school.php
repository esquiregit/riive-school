<?php
	require_once 'conn.php';

	class School {

        public static function login_school($username, $password, $conn){
            try{
                $query    = $conn->prepare('SELECT sc.School_id, sc.schoolname, sc.email, sc.location, sc.phone, sc.country, sc.region, sc.website, sc.username, sc.status, sc.reset_code, sc.image, c.country_name FROM school sc INNER JOIN countries c ON sc.country = c.id WHERE (sc.username = :username || sc.email = :username) and password = :password');
                $query->execute([':username' => $username, ':password' => $password]);
                
                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_country_name_by_id($id, $conn){
            try{
                $query = $conn->prepare('SELECT country_name FROM countries WHERE id = :id');
                $query->execute([':id' => $id]);

                return $query->fetch(PDO::FETCH_OBJ)->country_name;
            }catch(PDOException $ex){}
        }

        public static function read_schools($conn){
            try{
                $query = $conn->prepare('SELECT * FROM school ORDER BY schoolname ');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_school($School_id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM school WHERE School_id = :School_id');
                $query->execute([':School_id' => $School_id]);

                return $query->fetch(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_school_after_profile_update($School_id, $conn){
            $user = array();
            try{
                $query = $conn->prepare('SELECT sc.School_id, sc.schoolname, sc.email, sc.location, sc.phone, sc.country, sc.region, sc.website, sc.username, sc.status, sc.reset_code, sc.image, c.country_name FROM school sc INNER JOIN countries c ON sc.country = c.id WHERE sc.School_id = :School_id');
                $query->execute([':School_id' => $School_id]);
                $school = $query->fetch(PDO::FETCH_OBJ);

                if($school) {
                    array_push($user, array(
                        "id"           => $school->School_id,
                        "name"         => $school->schoolname,
                        "email"        => $school->email,
                        "location"     => $school->location,
                        "phone"        => $school->phone,
                        "country_code" => $school->country,
                        "country_name" => $school->country_name,
                        "region"       => $school->region,
                        "website"      => $school->website,
                        "username"     => $school->username,
                        "access_level" => "School",
                        "status"       => $school->status,
                        "reset_code"   => $school->reset_code,
                        "image"        => (empty($school->image)) ? "pictures/avatar.png" : $school->image
                    ));
                }

                return $user;
            }catch(PDOException $ex){}
        }

        public static function read_countries($conn){
            try{
                $query = $conn->prepare('SELECT * FROM countries ORDER BY country_name ASC');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_active_countries($conn){
            try{
                $query = $conn->prepare('SELECT * FROM countries WHERE status = :status ORDER BY country_name ASC');
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_regions_by_country_id($country_id, $conn){
            try{
                $query = $conn->prepare('SELECT * FROM region_state WHERE countryID = :country_id ORDER BY regionName ASC');
                $query->execute([':country_id' => $country_id]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function read_school_name_by_id($school_id, $conn){
            try{
                $query  = $conn->prepare('SELECT schoolname FROM school WHERE School_id = :school_id');
                $query->execute([':school_id' => $school_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->schoolname : $result;
            }catch(PDOException $ex){}
        }

        public static function read_school_image_by_id($school_id, $conn){
            try{
                $query  = $conn->prepare('SELECT image FROM school WHERE School_id = :school_id');
                $query->execute([':school_id' => $school_id]);
                $result = $query->fetch(PDO::FETCH_OBJ);
                
                return ($result) ? $result->image : $result;
            }catch(PDOException $ex){}
        }

        public static function read_regions($conn){
            try{
                $query  = $conn->prepare('SELECT * FROM region_state WHERE countryID = :country_id ORDER BY regionName ASC');
                $query->execute([':country_id' => $_SESSION['riive_school_country']]);
                
                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }


        public static function update_school_with_password_and_image($School_id, $schoolname, $email, $location, $phone, $region, $website, $username, $password, $image, $conn){
            try{
                $query  = $conn->prepare('UPDATE school SET schoolname = :schoolname, email = :email, location = :location, phone = :phone, region = :region, website = :website, username = :username, password = :password, image = :image WHERE School_id = :school_id');
                $query->execute([':schoolname' => $schoolname, ':email' => $email, ':location' => $location, ':phone' => $phone, ':region' => $region, ':website' => $website, ':username' => $username, ':password' => $password, ':school_id' => $School_id, ':image' => $image]);
                
                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_school_with_no_password_and_image($School_id, $schoolname, $email, $location, $phone, $region, $website, $username, $image, $conn){
            try{
                $query  = $conn->prepare('UPDATE school SET schoolname = :schoolname, email = :email, location = :location, phone = :phone, region = :region, website = :website, username = :username, image = :image WHERE School_id = :school_id');
                $query->execute([':schoolname' => $schoolname, ':email' => $email, ':location' => $location, ':phone' => $phone, ':region' => $region, ':website' => $website, ':username' => $username, ':school_id' => $School_id, ':image' => $image]);
                
                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_school_with_password_and_no_image($School_id, $schoolname, $email, $location, $phone, $region, $website, $username, $password, $conn){
            try{
                $query  = $conn->prepare('UPDATE school SET schoolname = :schoolname, email = :email, location = :location, phone = :phone, region = :region, website = :website, username = :username, password = :password WHERE School_id = :school_id');
                $query->execute([':schoolname' => $schoolname, ':email' => $email, ':location' => $location, ':phone' => $phone, ':region' => $region, ':website' => $website, ':username' => $username, ':password' => $password, ':school_id' => $School_id]);
                
                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function update_school_with_no_password_and_no_image($School_id, $schoolname, $email, $location, $phone, $region, $website, $username, $conn){
            try{
                $query  = $conn->prepare('UPDATE school SET schoolname = :schoolname, email = :email, location = :location, phone = :phone, region = :region, website = :website, username = :username WHERE School_id = :school_id');
                $query->execute([':schoolname' => $schoolname, ':email' => $email, ':location' => $location, ':phone' => $phone, ':region' => $region, ':website' => $website, ':username' => $username, ':school_id' => $School_id]);
                
                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

        public static function change_school_password($School_id, $password, $reset_code, $conn){
            try{
                $query  = $conn->prepare('UPDATE school SET password = :password WHERE School_id = :School_id AND reset_code = :reset_code');
                $query->execute([':password' => $password, ':School_id' => $School_id, ':reset_code' => $reset_code]);
                
                return true;
            }catch(PDOException $ex){
                return false;
            }
        }

	}

?>