<?php
    header('Content-Type: application/json');
    require "classes/email.php";
    require "classes/methods.php";

    $conn          = $pdo->open();
    $data          = file_get_contents("php://input");
    $request       = json_decode($data);//die(print_r($request));
    $email_address = Methods::validate_string($request->email_address);
    $response      = array();

    if($email_address) {
        $result    = Email::get_user_details($email_address, $conn);

        if($result) {
            $id      = @$result['accountType'] ? $result['id'] : $result['School_id'];
            $s_id    = @$result['schoolCode']  ? $result['schoolCode'] : 'zzzzz';
            $name    = @$result['accountType'] ? $result['name'] : $result['schoolname'];
            $type    = @$result['accountType'] ? $result['accountType'] : '';
            $message = Email::get_password_reset_message($id, $s_id, $name, $result['reset_code'], $type);

            if(Email::send_email($email_address, 'Password Reset Link', $message, $message)) {
                array_push($response, array(
                    "status"  => "Success",
                    "message" => "Password Reset Link Has Been Sent To \"$email_address\""
                ));
            } else {
                array_push($response, array(
                    "status"  => "Warning",
                    "message" => "Couldn't Send Password Reset Link. Please Try Again...."
                ));
            }
        } else {
            array_push($response, array(
                "status"  => "Error",
                "message" => "Email Address \"$email_address\" Doesn Not Exist...."
            ));
        }
    }

    $pdo->close();
    echo json_encode($response);
?>
