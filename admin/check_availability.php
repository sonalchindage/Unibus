<?php 
session_start();
$aid=$_SESSION['id'];
require_once("includes/config.php");

// Old code for RoomNo check
// if(!empty($_POST["roomno"])) 
// {
// $roomno=$_POST["roomno"];
// $result ="SELECT count(*) FROM registration WHERE roomno=?";
// $stmt = $mysqli->prepare($result);
// $stmt->bind_param('i',$roomno);
// $stmt->execute();
// $stmt->bind_result($count);
// $stmt->fetch();
// $stmt->close();
// if($count>0)
// echo "<span style='color:red'>$count. Seats already full.</span>";
// else
// 	echo "<span style='color:red'>All Seats are Available</span>";
// }
// New code for RoomNo check
// For room availbilty
if(!empty($_POST["roomno"]) && !empty($_POST["gender"])) {

	$roomno=$_POST["roomno"];
	$gender = $_POST["gender"];
	$result ="SELECT count(*) FROM registration WHERE roomno=? AND gender=? AND status='verified'";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('ss',$roomno,$gender);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	if($count > 0)
		echo $count;
	else
		echo 0;

}

// Old For old Password
// if(!empty($_POST["oldpassword"])) 
// {
// $pass=$_POST["oldpassword"];
// $result ="SELECT password FROM userregistration WHERE password=?";
// $stmt = $mysqli->prepare($result);
// $stmt->bind_param('s',$pass);
// $stmt->execute();
// $stmt -> bind_result($result);
// $stmt -> fetch();
// $stmt->close();
// $opass=$result;
// if($opass==$pass) 
// echo "<span style='color:green'> Password  matched .</span>";
// else echo "<span style='color:red'> Password Not matched</span>";
// }
//New For old Password
if(!empty($_POST["oldpassword"]) && !empty($_POST["userid"])) 
{
	$userid = $_POST["userid"];
	$pass=$_POST["oldpassword"];
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
if(!empty($_POST["regno"])) {
    $regno = $_POST["regno"];

    $result = "SELECT * FROM registration WHERE regno=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $regno);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0) {
        $data = $res->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(null);
    }

    $stmt->close();
    // $mysqli->close();
}
// For Registration Number for auto fill
if(!empty($_POST["regNo"])) {
    $regno = $_POST["regNo"];

    $result = "SELECT * FROM userRegistration WHERE regNo=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $regno);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0) {
        $data = $res->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(null);
    }

    $stmt->close();
    // $mysqli->close();
}

// if(!empty($_POST["regno"])) {
// 	$regno= $_POST["regno"];

// 	$result ="SELECT firstName, middleName, lastName, gender, contactno, emailid FROM registration WHERE regno=?";
// 	$stmt = $mysqli->prepare($result);
// 	$stmt->bind_param('s',$regno);
// 	$stmt->execute();
// 	$res = $stmt->get_result();
// 	$stmt->bind_result($count);
// 	$stmt->fetch();
//     $data = $res->fetch_assoc();
// 	$stmt->close();
// 	if($count > 0)
// 	{
// 		echo json_encode($data);
// 	}

// }

// For Recipt toekn validation
if(!empty($_POST["token"])) 
{
	$token=$_POST["token"];
	// Check if receiptTokenId exists
	$stmt = $mysqli->prepare("SELECT receiptTokenId FROM transactionhistory WHERE receiptTokenId = ?");
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


?>
