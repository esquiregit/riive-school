<?php
	require_once 'audit_trail.php';
	require_once 'email.php';

	class Password {

        public static function send_recovery_link($email, $name, $conn) {
            try{
                $query = $conn->prepare('SELECT * FROM school WHERE email = :email');
                $query->execute([':email' => $email]);

                if($query->rowCount()){
                    $result 	  = $query->fetch(PDO::FETCH_OBJ);
                    $reset_url    = "<a href='http://riveshoolsapp.shenencosmetics.com/school/password-change.php?tg4F7rdr=$result->reset_code&hYtg65l=$result->School_id&Uyh67Yhgt=m'>Link</a>";
                    $html_message = 'Hello <strong>' . $name . '</strong>! Click on this ' . $reset_url . ' to reset your password.<br />If you did not request a password change, please ignore this message<br /><br /><br />Thank you and have a nice day.';
                    
                    if(Email::send_email($email, $name, 'Password Reset Link', $html_message, $html_message))
	                    return true;
                    else
                        return false;
                } else {
                    $query = $conn->prepare('SELECT * FROM teacher WHERE email = :email');
                    $query->execute([':email' => $email]);

                    if($query->rowCount()){
                        $result       = $query->fetch(PDO::FETCH_OBJ);
                        $reset_url    = "<a href='http://localhost/projects/riive/school/password-change.php?tg4F7rdr=$result->reset_code&hYtg65l=$result->id&Uyh67Yhgt=r'>Link</a>";
                        $html_message = 'Hello <strong>' . $name . '</strong>! Click on this ' . $reset_url . ' to reset your password.<br />If you did not request a password change, please ignore this message<br /><br /><br />Thank you and have a nice day.';
                        
                        if(Email::send_email($email, $name, 'Password Reset Link', $html_message, $html_message))
                            return true;
                        else
                            return false;
                    }
                }
            } catch (PDOException $ex){
                return false;
            }
        }

        public static function check_reset_code($user_id, $reset_code, $conn) {
            try{
                $query  = $conn->prepare('SELECT * FROM school WHERE School_id = :School_id AND reset_code = :reset_code');
                $query->execute([':School_id' => $user_id, ':reset_code' => $reset_code]);
                $result = $query->fetch(PDO::FETCH_OBJ);

                if($result) {
                    return true;
                } else {
                    $query  = $conn->prepare('SELECT * FROM teacher WHERE id = :id AND reset_code = :reset_code');
                    $query->execute([':id' => $user_id, ':reset_code' => $reset_code]);
                    $result = $query->fetch(PDO::FETCH_OBJ);

                    if($result)
                        return true;
                    else
                        return false;
                }
            } catch (PDOException $ex){
                return false;
            }
        }

        public static function change_school_password($password, $School_id, $reset_code, $conn) {
            try{
                $query = $conn->prepare('UPDATE school SET password = :password WHERE School_id = :School_id AND reset_code = :reset_code');
                $query->execute([':password' => md5($password), ':School_id' => $School_id, ':reset_code' => $reset_code]);

                return true;
            } catch (PDOException $ex){die($ex);
                return false;
            }
        }

        public static function change_teacher_password($password, $id, $reset_code, $conn) {
            try{
                $query = $conn->prepare('UPDATE teacher SET password = :password WHERE id = :id AND reset_code = :reset_code');
                $query->execute([':password' => md5($password), ':id' => $id, ':reset_code' => $reset_code]);

                return true;
            } catch (PDOException $ex){
                return false;
            }
        }

    }

?>