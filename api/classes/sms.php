<?php
    require_once 'conn.php';
    require_once ('ZenophSMSGH/lib/ZenophSMSGH.php');
    define('ACCOUNT_LOGIN', 'treenology@gmail.com');
    define('ACCOUNT_PASSWORD', 'treetech');
    
    class SMS {

        public static function send_message($message, $contact){
            try{
                $zs = new ZenophSMSGH();
                $zs->setUser(ACCOUNT_LOGIN);
                $zs->setPassword(ACCOUNT_PASSWORD);

                $zs->setSenderId('RiiVe');
                $zs->setMessage($message);
                $zs->setMessageType(ZenophSMSGH_MESSAGETYPE::TEXT);

                $zs->addDestination($contact, false);

                $response = $zs->sendMessage();
                return true;
            } catch (ZenophSMSGH_Exception $ex){
                $errmessage   = $ex->getMessage();
                $responsecode = $ex->getResponseCode();
            } catch (Exception $ex) {
                return $errmessage = $ex;//->getMessage();
            }
        }

        public static function send_bulk_message($message, $contacts){
            try{
                $zs = new ZenophSMSGH();
                $zs->setUser(ACCOUNT_LOGIN);
                $zs->setPassword(ACCOUNT_PASSWORD);

                $zs->setSenderId('RiiVe');
                $zs->setMessage($message);
                $zs->setMessageType(ZenophSMSGH_MESSAGETYPE::TEXT);

                foreach ($contacts as $contact) {
                    $zs->addDestination($contact, false);
                }
                //$zs->addDestination($contact, false);

                $response = $zs->sendMessage();
            } catch (ZenophSMSGH_Exception $ex){
                $errmessage   = $ex->getMessage();
                $responsecode = $ex->getResponseCode();

                switch ($response){
                    case ZenophSMSGH_RESPONSECODE::ERR_AUTH:
                        // authentication failed.
                        break;
                    
                    case ZenophSMSGH_RESPONSECODE::ERR_INSUFF_CREDIT:
                        // balance is insufficient to send message to all destinations.
                        break;
                }
            } catch (Exception $ex) {
                return $errmessage = $ex->getMessage();
            }
        }

        public static function get_sms_balance(){
            try{
                $zs = new ZenophSMSGH();
                $zs->setUser(ACCOUNT_LOGIN);
                $zs->setPassword(ACCOUNT_PASSWORD);

                return $zs->getBalance();
            } catch(ZenophSMSGH_Exception $ex){
                return 0;
            } catch(Exception $ex){
                return false;
            }
        }

        public static function send_sms($message, $contacts) {
            if(self::get_sms_balance()) {
                try {
                    foreach ($contacts as $contact) {
                        self::send_message($message, $contact);
                    }

                    return 200; // sending succeeded
                } catch(Exception $ex){die('catch '.$ex);
                    return 400; // sending failed
                }
            } else {
                return 500; // insufficient balance
            }
        }

        public static function send_bulk_sms($message, $contacts){
            if(self::get_sms_balance()){
                try{
                    self::send_bulk_message($message, $contacts);

                    return true;
                }catch(Exception $ex){ return false; }
            } else {
                return false;
            }
        }

        public static function get_parents_contacts($conn) {
            try {
                $query = $conn->prepare("SELECT phone FROM parent WHERE status = :status");
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }

        public function read_class_students_parents($conn){
            try{
                $query = $conn->prepare('SELECT parent.parentid, parent.fullname, parent.phone, parent.email, parent.location, parent.longitude, parent.latitude, parent.regCountry, parent.occupation, parent.relation, parent.dor, parent.lastLocUpdate, parent.status, parent_child.parentID, parent_child.studentID, student.School_id, student.studentid, student.firstname, student.othernames, student.lastname, student.class, school.School_id FROM parent, parent_child, student, school WHERE student.class = :class AND student.School_id = :School_id AND parent.parentid = parent_child.parentID AND student.studentid = parent_child.studentID AND student.School_id = school.School_id');
                $query->execute([':class' => $_SESSION['riive_school_teacher_class'], ':School_id' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

        public static function get_class_students_parents_contacts($conn) {
            try {
                $query = $conn->prepare('SELECT phone FROM `parent` WHERE status = :status AND childid IN (SELECT `studentid` FROM student WHERE class = :class AND School_id = :School_id)');
                $query->execute([':status' => 'Active', ':class' => $_SESSION['riive_school_teacher_class'], ':School_id' => $_SESSION['riive_school_id']]);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }

        public static function get_parents_contacts_and_names($conn) {
            try {
                $query = $conn->prepare("SELECT fullname, phone FROM parent WHERE status = :status");
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }

        public static function get_schools_contacts($conn) {
            try {
                $query = $conn->prepare("SELECT phone FROM school WHERE status = :status");
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }

        public static function get_schools_contact_and_names($conn) {
            try {
                $query = $conn->prepare("SELECT schoolname, phone FROM school WHERE status = :status");
                $query->execute([':status' => 'Active']);

                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch(PDOException $ex) {}
        }
        
    }
?>