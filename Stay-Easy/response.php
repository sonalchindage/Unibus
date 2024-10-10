<?php

include_once('includes/config.php');
include('includes/enc.php');
include('includes/phpMailer/mail.php');
// output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
$action = isset($_POST['action']) ? $_POST['action'] : "";

    if (isset($_POST['emailOtp'])) {
        require_once 'includes/phpMailer/mail.php';
        $email = $_POST['emailOtp'];
        $otp = $_POST['otp'];

        // echo $email." ".$otp;

        $stmt = $mysqli->prepare("SELECT email, firstName, lastName FROM userregistration WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($email, $firstName, $lastName);
        $rs = $stmt->fetch();

        if ($rs) {
            $fullName = $firstName . " " . $lastName;
            $recipientName = $fullName;
            $recipientEmail = $email;
            $subject = 'Password Change';
            $body = "Please confirm your account registration by entering the following OTP:<br><b>$otp</b>";
            $altBody = 'This is the body in plain text for non-HTML mail clients';
            $bcc = "";
            $cc = "";
            // $error = newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc);
            // echo $error;
            if (newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)) {
                echo json_encode(array(
                    'status' => 'Success',
                    'message' => "OTP has been successfully sent to $email"
                ));
            } else {
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => "There has been an error sending the email, please try again."
                ));
            }
        } else {
            echo json_encode(array(
                'status' => 'Error',
                'message' => "The email address $email is not registered."
            ));
        }
    }

    if(isset($_POST['validate'])){
		$emailid=trim($_POST['validate']);
		$result ="SELECT count(*) FROM userRegistration WHERE email=? ";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$emailid);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();

        $otp = rand(111111, 999999);
        $encEmail = encrypt($emailid);
        $ev = 0;
        $recipientName = $emailid;
        $recipientEmail = $emailid;
        $subject = 'Email verification';
        $body = "Please confirm your account registration by entering the following OTP:<br><b>$otp</b>";
        $altBody = 'This is the body in plain text for non-HTML mail clients';
        $bcc = "";
        $cc = "";

        if(newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)){
                
            if($count>0){
        // UPDATE `userregistration` SET `id`='[value-1]',`userPrn`='[value-2]',`clgName`='[value-3]',`otp`='[value-4]',`firstName`='[value-5]',`middleName`='[value-6]',`lastName`='[value-7]',`gender`='[value-8]',`contactNo`='[value-9]',`email`='[value-10]',`password`='[value-11]',`emailValidate`='[value-12]',`regDate`='[value-13]',`updationDate`='[value-14]',`passUdateDate`='[value-15]' WHERE 1
                $query="UPDATE userRegistration SET otp = ? WHERE email = ?";
                $stmt = $mysqli->prepare($query);
                $rc=$stmt->bind_param('is',$otp,$emailid);
                if($stmt->execute()){
                    echo "yes";
                }else{
                    echo "no";
                }
            }else{
                $query="INSERT into  userRegistration(otp,email,emailValidate) values(?,?,?)";
                $stmt = $mysqli->prepare($query);
                $rc=$stmt->bind_param('isi',$otp,$emailid, $ev);
                if($stmt->execute()){
                    echo "yes";
                }else{
                    echo "no";
                }
            }	
        }else{
            echo "Error in sending otp to email, check your email or try after some time";
            // echo "emailError";
        }
	}

    if(isset($_POST['login'])){
		$emailid=trim($_POST['login']);
		$otpValidate=trim($_POST['otpValidate']);
        $emailValidate = 1;
		// $result ="SELECT count(*) FROM userRegistration WHERE email=? ";
		// $stmt = $mysqli->prepare($result);
		// $stmt->bind_param('s',$emailid);
		// $stmt->execute();
		// $stmt->bind_result($count);
		// $stmt->fetch();
		// $stmt->close();

        // if($count>0){
        // UPDATE `userregistration` SET `id`='[value-1]',`userPrn`='[value-2]',`clgName`='[value-3]',`otp`='[value-4]',`firstName`='[value-5]',`middleName`='[value-6]',`lastName`='[value-7]',`gender`='[value-8]',`contactNo`='[value-9]',`email`='[value-10]',`password`='[value-11]',`emailValidate`='[value-12]',`regDate`='[value-13]',`updationDate`='[value-14]',`passUdateDate`='[value-15]' WHERE 1
            $query="UPDATE  userRegistration SET emailValidate = ? WHERE email = ? AND otp = ?";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param('isi',$emailValidate,$emailid,$otpValidate);
            if ($stmt->execute()) {
                // Check if any rows were affected
                if ($stmt->affected_rows > 0) {
                    echo "yes";
                } else {
                    echo "no";
                }
            } else {
                echo "no"; // In case of query failure
            }
            
            $stmt->close();
        // }else{
        //     $query="INSERT into  userRegistration(otp,email,emailValidate) values(?,?,?)";
        //     $stmt = $mysqli->prepare($query);
        //     $rc=$stmt->bind_param('isi',$otp,$emailid, $ev);
        //     if($stmt->execute()){
        //         echo "yes";
        //     }else{
        //         echo "no";
        //     }
        // }	
        
	}



?>