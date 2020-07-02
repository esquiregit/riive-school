<?php
	require_once 'conn.php';

	class Payment {

        public function read_payments($conn){
            try{
                $query = $conn->prepare('SELECT * FROM payment INNER JOIN parent ON payment.ParentId = parent.parentid');
                $query->execute();

                return $query->fetchAll(PDO::FETCH_OBJ);
            }catch(PDOException $ex){}
        }

		public function read_payment($id, $conn){
			try{
                $query = $conn->prepare('SELECT * FROM payment INNER JOIN parent ON payment.ParentId = parent.parentid WHERE id = :id');
				$query->execute([':id' => $id]);

				return $query->fetch(PDO::FETCH_OBJ);
			}catch(PDOException $ex){}
		}

	}