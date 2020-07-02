<?php
    require_once 'conn.php';
    require_once 'session_variables.php';
    @session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'src/PHPMailer.php';
    require_once 'src/SMTP.php';
    require_once 'src/Exception.php';

    define('EMAIL_ACCOUNT', 'riiveschoolsmanagement@gmail.com');
    define('EMAIL_PASSWORD', 'pa$$word123456');
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SECURE_SOCKET', 'ssl');
    define('PORT', 465);
    
    class Email {

        public static function send_email($email_address, $subject, $html_message, $message){
            try{
                $mail             = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = EMAIL_ACCOUNT;
                $mail->Password   = EMAIL_PASSWORD;
                $mail->SMTPSecure = SECURE_SOCKET;
                $mail->Port       = PORT;

                $mail->setFrom(EMAIL_ACCOUNT, 'RiiVe');
                $mail->addAddress($email_address, $email_address);

                $mail->isHTML(true);
                $mail->Subject    = $subject;
                $mail->Body       = $html_message;
                $mail->AltBody    = $message;

                $mail->send();
                return  true;
            }catch(Exception $ex){
                return false;
            }
        }

        public static function send_bulk_email($address_array, $subject, $html_message, $message){
            try{
                $mail              = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host        = SMTP_HOST;
                $mail->SMTPAuth    = true;
                $mail->Username    = EMAIL_ACCOUNT;
                $mail->Password    = EMAIL_PASSWORD;
                $mail->SMTPSecure  = SECURE_SOCKET;
                $mail->Port        = PORT;

                $mail->setFrom(EMAIL_ACCOUNT, 'RiiVe');
                $mail->isHTML(true);
                $mail->Subject     = $subject;
                $mail->Body        = $html_message;
                $mail->AltBody     = $message;

                foreach ($address_array as $email_address) {
                    $mail->addAddress($email_address, $email_address);

                    $mail->send();
                }

                return  true;
            }catch(Exception $ex){die($ex);
                return false;
            }
        }

        public static function send_email_attachment($email_address, $subject, $html_message, $message, $attachment_tmp, $attachment_name){
            try{
                $mail             = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = EMAIL_ACCOUNT;
                $mail->Password   = EMAIL_PASSWORD;
                $mail->SMTPSecure = SECURE_SOCKET;
                $mail->Port       = PORT;

                $mail->setFrom(EMAIL_ACCOUNT, 'RiiVe');
                $mail->addAddress($email_address, $email_address);

                $mail->isHTML(true);
                $mail->Subject    = $subject;
                $mail->Body       = $html_message;
                $mail->AltBody    = $message;
                $mail->addAttachment($attachment_tmp, $attachment_name);

                $mail->send();
                return  true;
            }catch(Exception $ex){die($ex->getMessage());
                return false;
            }
        }

        public static function send_bulk_email_attachment($address_array, $subject, $html_message, $message, $attachment_tmp, $attachment_name){
            try{
                $mail              = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host        = SMTP_HOST;
                $mail->SMTPAuth    = true;
                $mail->Username    = EMAIL_ACCOUNT;
                $mail->Password    = EMAIL_PASSWORD;
                $mail->SMTPSecure  = SECURE_SOCKET;
                $mail->Port        = PORT;

                $mail->setFrom(EMAIL_ACCOUNT, 'RiiVe');
                $mail->isHTML(true);
                $mail->Subject     = $subject;
                $mail->Body        = $html_message;
                $mail->AltBody     = $message;
                $mail->addAttachment($attachment_tmp, $attachment_name);

                foreach ($address_array as $email_address) {
                    $mail->addAddress($email_address, $email_address);

                    $mail->send();
                }

                return  true;
            }catch(Exception $ex){die($ex);
                return false;
            }
        }

        static function get_user_details($email_address, $conn) {

            try{
                $query  = $conn->prepare('SELECT School_id, schoolname, reset_code FROM school WHERE email = :email_address');
                $query->execute([':email_address' => $email_address]);
                $result1 = $query->fetch();

                $query  = $conn->prepare('SELECT id, name, schoolCode, reset_code, accountType FROM teacher WHERE email = :email_address');
                $query->execute([':email_address' => $email_address]);
                $result2 = $query->fetch();

                return $result1 ? $result1 : $result2;
            }catch(PDOException $ex){}
        }

        public static function get_password_reset_message($id, $s_id, $name, $reset_code, $type) {
            $type     = strtolower($type) === 'teacher' ? 't' : 's';
            $message  = "Good day <strong>$name</strong><br /><br />";
            $message .= "You requested a password reset of your account on <strong>RiiVe</strong>.<br />";
            $message .= "Click this <a target='_target' href='http://localhost:3000/password-change/$id/$reset_code/$type/$s_id/'>Link</a> to reset your password. If you didn't request this action please ignore this message.";
            $message .= "<br /><br /><br /><br />The <strong>RiiVe</strong> Support Team";

            return $message;
        }

        public static function get_parents_emails($conn) {
            try{
                $query = $conn->prepare('SELECT parent.parentid, parent.fullname, parent.email, parent_child.parentID, parent_child.studentID, student.School_id, student.studentid, student.firstname, student.othernames, student.lastname, school.School_id FROM parent, parent_child, student, school WHERE student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                $query->execute([':School_id' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        /*public static function get_parents_emails($conn) {
            try {
                $query = $conn->prepare('SELECT email FROM `parent` WHERE status = :status AND childid IN (SELECT `studentid` FROM student WHERE School_id = :School_id)');
                $query->execute([':status' => 'Active', ':class' => $_SESSION['riive_school_teacher_class'], ':School_id' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }*/

        public function read_class_students_parents($conn){
            try{
                $query = $conn->prepare('SELECT parent.parentid, parent.fullname, parent.phone, parent.email, parent.location, parent.longitude, parent.latitude, parent.regCountry, parent.occupation, parent.relation, parent.dor, parent.lastLocUpdate, parent.status, parent_child.parentID, parent_child.studentID, student.School_id, student.studentid, student.firstname, student.othernames, student.lastname, student.class, school.School_id FROM parent, parent_child, student, school WHERE student.class = :class AND student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                $query->execute([':class' => $_SESSION['riive_school_teacher_class'], ':School_id' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function get_parents_emails_and_names($conn) {
            try {
                $query = $conn->prepare('SELECT fullname, email FROM `parent` WHERE status = :status AND childid IN (SELECT `studentid` FROM student WHERE class = :class AND School_id = :School_id)');
                $query->execute([':status' => 'Active', ':class' => $_SESSION['riive_school_teacher_class'], ':School_id' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }

        public static function get_schools_emails($conn) {
            try {
                $query = $conn->prepare("SELECT email FROM school WHERE status = :status");
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }

        public static function get_schools_emails_and_names($conn) {
            try {
                $query = $conn->prepare("SELECT schoolname, email FROM school WHERE status = :status");
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }
        
    }
?>