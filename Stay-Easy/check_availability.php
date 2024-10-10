<?php
require_once("includes/config.php");
include 'includes/enc.php';

//For Email
if(!empty($_POST["emailid"])) {
	$email= $_POST["emailid"];
	if (filter_var($email, FILTER_VALIDATE_EMAIL)===false) {

		echo "error : You did not enter a valid email.";
	}
	else {
		$result ="SELECT count(*) FROM userRegistration WHERE email=?";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$email);
		$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
if($count>0)
{
echo "<span style='color:red'> Email already exist. Please try again.</span>";
}
}
}
// For Registration Number
if(!empty($_POST["userPrn"])) {
	$userPrn= trim($_POST["userPrn"]);

		$result ="SELECT count(*) FROM userRegistration WHERE userPrn=?";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$userPrn);
		$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
if($count>0)
{
echo "<span style='color:red'> Registration number already exist. Please try again .</span>";
}

}


// For old Password
if(!empty($_POST["oldpassword"]) && !empty($_POST["userid"])) 
{
	$userid = $_POST["userid"];
	$pass=encrypt($_POST["oldpassword"]);
	$result ="SELECT password FROM userregistration WHERE id=?";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('i',$userid);
	$stmt->execute();
	$stmt -> bind_result($result);
	$stmt -> fetch();
	$stmt->close();

	if($result === $pass){
		echo "<span style='color:green'> Password  matched .</span>";
	}else{
		echo "<span style='color:red'> Password Not matched</span>";
	}
}

// For Recipt toekn validation
if(!empty($_POST["token"])) 
{
	$token=trim($_POST["token"]);
	// Check if receiptTokenId exists
	$stmt = $mysqli->prepare("SELECT receiptTokenId FROM transactionhistory WHERE receiptTokenId = ? AND (status = 'verified' OR status = 'pending')");
	// $stmt = $mysqli->prepare($result);
	$stmt->bind_param('s', $token);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo "<span style='color:red'> Token is already exist, Please try another Reciept </span>";
	}else{
		echo "<span style='color:green'> Recipt Token is Unique .</span>";
	}
	$stmt->close();
}

// For room availbilty
if(!empty($_POST["roomno"]) && !empty($_POST["gender"])) 
{
	$roomno=$_POST["roomno"];
	$gender = $_POST["gender"];
	$hostelName = $_POST["hostelName"];
	$result = "SELECT count(*) FROM registration WHERE hostelName=? AND roomno=? AND gender=? AND status='verified'";
	// $result ="SELECT count(*) FROM registration WHERE roomno=? AND gender=?";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('sss',$hostelName,$roomno,$gender);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();
	// $avSeat = $seater - $count;
	
	// if($avSeat === $seater){
	// 	echo "<span style='color:red'>All Seats are Available</span>";
	// }elseif($avSeat === 0){
	// 	echo "<span style='color:red'>No seat is Available.</span>";
	// }else{
	// 	echo "<span style='color:green'>$avSeat Seat are Available.</span>";
	// }
	if($count > 0)
		echo $count;
	else
		echo 0;
}
?>