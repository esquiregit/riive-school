<?php
	require_once 'includes/connection.php';

	class Alert {
		/* CRUD OPEARTIONS */
		public static function create_alert($member_id, $subject, $alert){
			try{
				$query = $GLOBALS['conn']->prepare('INSERT INTO alerts(member_id, subject, alert, date, read_date, account_status, read_status) VALUES(:member_id, :subject, :alert, NOW(), :read_date, :account_status, :read_status)');
				$query->execute([':member_id' => $member_id, ':subject' => ucfirst($subject), ':alert' => ucfirst($alert), ':read_date' => '0000-00-00 00:00:00', ':account_status' => 'Active', ':read_status' => 'False']);
				return true;
			}catch(PDOException $ex){
				return false;
			}
		}

		public static function read_all_alerts(){
			try{
				$query = $GLOBALS['conn']->prepare('SELECT * FROM alerts ORDER BY date DESC');
				$query->execute();

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public static function read_alert($id){
			try{
				$query = $GLOBALS['conn']->prepare('SELECT * FROM alerts WHERE id = :id');
				$query->execute([':id' => $id]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public static function read_admin_alerts($member_id){
			try{
				$query = $GLOBALS['conn']->prepare('SELECT * FROM alerts WHERE (member_id = :member_id OR member_id = :system_id) ORDER BY read_date, date DESC');
				$query->execute([':member_id' => $member_id, ':system_id' => 'MEM0000']);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public static function read_member_alerts($member_id){
			try{
				$query = $GLOBALS['conn']->prepare('SELECT * FROM alerts WHERE member_id = :member_id ORDER BY read_date, date DESC');
				$query->execute([':member_id' => $member_id]);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public static function read_new_admin_alerts($member_id){
			try{
				$query = $GLOBALS['conn']->prepare('SELECT * FROM alerts WHERE (member_id = :member_id OR member_id = :system_id) AND read_status = :read_status ORDER BY date DESC');
				$query->execute([':member_id' => $member_id, ':system_id' => 'MEM0000', ':read_status' => 'False']);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public static function read_new_member_alerts($member_id){
			try{
				$query = $GLOBALS['conn']->prepare('SELECT * FROM alerts WHERE member_id = :member_id AND read_status = :read_status ORDER BY date DESC');
				$query->execute([':member_id' => $member_id, ':read_status' => 'False']);

				return $query->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

		public static function update_alert_status($id){
			try{
				$query = $GLOBALS['conn']->prepare('UPDATE alerts SET read_status = :read_status, read_date = NOW() WHERE id = :id');
				$query->execute([':read_status' => 'True', ':id' => $id]);

				return true;
			}catch(PDOException $ex){
				return false;
			}
		}
		
		/* END OF CRUD OPEARTIONS */
	}