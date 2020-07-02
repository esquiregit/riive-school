<?php
    @session_start();

    class Methods {

        public static function read_school_name($School_id, $conn) {
            try{
                $query = $conn->prepare('SELECT schoolname FROM school WHERE School_id = :School_id');
                $query->execute([':School_id' => $School_id]);

                return $query->fetch(PDO::FETCH_OBJ)->schoolname;
            }catch(PDOException $ex){}
        }

        static function is_email_address_taken($email_address, $conn) {
            try{
                $query  = $conn->prepare('SELECT * FROM school WHERE email = :email_address');
                $query->execute([':email_address' => $email_address]);
                $result1 = $query->rowCount();

                $query  = $conn->prepare('SELECT * FROM teacher WHERE email = :email_address');
                $query->execute([':email_address' => $email_address]);
                $result2 = $query->rowCount();

                // $query  = $conn->prepare('SELECT * FROM security WHERE email = :email_address');
                // $query->execute([':email_address' => $email_address]);
                // $result3 = $query->rowCount();

                return ($result1 + $result2) ? true : false;
            }catch(PDOException $ex){}
        }

        static function is_this_email_address_taken($id, $email_address, $conn) {
            try{
                $query  = $conn->prepare('SELECT * FROM school WHERE email = :email_address AND School_id != :id');
                $query->execute([':email_address' => $email_address, ':id' => $id]);
                $result1 = $query->rowCount();

                $query  = $conn->prepare('SELECT * FROM teacher WHERE email = :email_address AND id != :id');
                $query->execute([':email_address' => $email_address, ':id' => $id]);
                $result2 = $query->rowCount();

                // $query  = $conn->prepare('SELECT * FROM security WHERE email = :email_address');
                // $query->execute([':email_address' => $email_address]);
                // $result3 = $query->rowCount();

                return ($result1 + $result2) ? true : false;
            }catch(PDOException $ex){}
        }

        static function is_username_taken($username, $conn) {
            try{
                $query  = $conn->prepare('SELECT * FROM school WHERE username = :username');
                $query->execute([':username' => $username]);
                $result1 = $query->rowCount();

                $query  = $conn->prepare('SELECT * FROM teacher WHERE username = :username');
                $query->execute([':username' => $username]);
                $result2 = $query->rowCount();

                $query  = $conn->prepare('SELECT * FROM security WHERE username = :username');
                $query->execute([':username' => $username]);
                $result3 = $query->rowCount();

                return ($result1 + $result2 + $result3) ? true : false;
            }catch(PDOException $ex){}
        }

        static function is_this_username_taken($id, $username, $conn) {
            try{
                $query  = $conn->prepare('SELECT * FROM school WHERE username = :username AND School_id != :id');
                $query->execute([':username' => $username, ':id' => $id]);
                $result1 = $query->rowCount();

                $query  = $conn->prepare('SELECT * FROM teacher WHERE username = :username AND id != :id');
                $query->execute([':username' => $username, ':id' => $id]);
                $result2 = $query->rowCount();

                $query  = $conn->prepare('SELECT * FROM security WHERE username = :username AND id != :id');
                $query->execute([':username' => $username, ':id' => $id]);
                $result3 = $query->rowCount();

                return ($result1 + $result2 + $result3) ? true : false;
            }catch(PDOException $ex){}
        }
        
        static function strtocapital($string){
            $array      = explode(" ", $string);
            $new_string = '';

            foreach($array as $word){
                $first_letter = substr($word, 0, 1);
                $remaining    = substr($word, 1, strlen($word));
                $new_string  .= strtoupper($first_letter) . strtolower($remaining) . ' ';
            }

            return trim($new_string);
        }

        // method to check if name is made up of more than one part - e.g kofi manu instaed of manu
        static function is_name_valid($name){
            $names = explode(" ", $name);
            $count = 0;

            foreach ($names as $name) {
                if(!empty($name))
                    $count++;
            }

            return $count == 1 ? false : true;
        }
        
        // method to verify the first three numbers of phone number
        static function is_prefix_valid($number){
            $valid_values = array('020', '023', '024', '026', '027', '028', '050', '054', '055', '056', '057');
            $first_three = substr($number, 0, 3);

            foreach($valid_values as $value){
                if($value == $first_three){
                    return true;
                }
            }

            return false;
        }

        // method to validate and sanitize email value
        static function validate_email($value){
            return filter_var(htmlspecialchars(stripslashes(strip_tags(trim($value)))), FILTER_SANITIZE_EMAIL);
        }
        
        // method to check if email has valid format
        static function valid_email_format($value){
            return (filter_var($value, FILTER_VALIDATE_EMAIL));
        }

        // method to validate and sanitize string value
        static function validate_string($value){
            return filter_var(htmlspecialchars(stripslashes(strip_tags(trim($value)))), FILTER_SANITIZE_STRING);
        }

        // method to validate and sanitize string value
        static function validate_array($values){
            $new_array = array();
            foreach($values as $value){
                array_push($new_array, filter_var(htmlspecialchars(stripslashes(strip_tags(trim($value)))), FILTER_SANITIZE_STRING));
            }
            return $new_array;
        }
        
        // method to check if url has valid format
        static function valid_url_format($value){
            return !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value);
        }

    }

?>