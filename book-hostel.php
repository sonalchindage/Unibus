<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// $currentYear = 2025;
$currentYear = date("Y");
$currentDate = date("Y-m-d");
$academicYear = $currentYear."-".$currentYear+1;
$collegName = $_SESSION['clgName'];

//code for registration
if(isset($_POST['submit']) && isset($_POST['renew'])){

	$timestamp = date('Y-m-d H:i:s');

	$userPrn = $_SESSION['userPrn'];
	// $currentYear = $_POST['renew'];
	$stayfrom =$_POST['stayf'];
	// $foodstatus=$_POST['foodstatus'];
	$updationDate = date("Y-m-d");
	$clgName=$_POST['clgName'];
	$hostelName=$_POST['hostelName'];
	$classY=$_POST['classY'];
	// $roomId=$_POST['roomId'];
	
	$roomno=$_POST['roomno'];
	$feespm=$_POST['fpm'];
	$fname=$_POST['fname'];
	$mname=$_POST['mname'];
	$lname=$_POST['lname'];
	$fullName = $fname." ".$mname." ".$lname;
	$contactno=$_POST['contact'];
	$emailid=$_POST['email'];

	// Assuming $mysqli is your database connection
    $checkQuery = "SELECT * FROM report WHERE userPrn = ? AND status = 'pending'";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param('i', $userPrn);  // Assuming userPrn is a string, change to 'i' if it's an integer
    $checkStmt->execute();
    $result = $checkStmt->get_result();
	$row = $result->fetch_object();
	// print_r($row);

    if ($result->num_rows > 0) {
        // User has pending payments
		echo "<script>alert('You have pending payments.');</script>";
		
    } else {
		// echo "null";
        // No pending payments found
		$updateQuery = "UPDATE `registration` SET stayfrom= ?, academicYear= ?, class=?, postingDate= ?,updationDate= ? WHERE userPrn=?";
		$stmt = $mysqli->prepare($updateQuery);
		$rc=$stmt->bind_param('ssssss',$stayfrom,$academicYear,$classY,$timestamp,$updationDate,$userPrn);
		if($stmt->execute()) {
			echo "<script>
					alert('Success! Your Form has been renewed and is up to date');</script>";
			// Insert a new record
			$reportTotalAmount = 0;
			$paymentStatus = "Not Paid";
			$status = "pending";
			// INSERT INTO `report`(`reportID`, `stayFrom`, `receiptTokenId`, `reportTotalAmount`, `receiptFile`, `class`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `roomFeesphy`, `paymentType`, `academicYear`, `paymentStatus`, `status`, `skimAmount`, `comment`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`, `statusUpdationDate`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]','[value-13]','[value-14]','[value-15]','[value-16]','[value-17]','[value-18]','[value-19]','[value-20]','[value-21]','[value-22]','[value-23]','[value-24]','[value-25]','[value-26]')
			$queryPay = "INSERT INTO report ( stayFrom, reportTotalAmount, class, fullName, userPrn, emailid, contactno, roomno, roomFeesphy, academicYear, paymentStatus, status, remainingAmount, clgName, hostelName ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$payStmt = $mysqli->prepare($queryPay);
				$payStmt->bind_param('sdssssisdsssdss',$stayfrom, $reportTotalAmount, $classY, $fullName, $userPrn, $emailid, $contactno, $roomno, $feespm, $academicYear, $paymentStatus, $status, $feespm, $clgName, $hostelName);

			if ($payStmt->execute()) {
				echo "<script>window.location.href='payment-form.php';</script>";
			} else {
				echo "<script>alert('Error: ". $payStmt->error . "');</script>";
			}

			$payStmt->close();
		
		}else{
			echo "<script>alert('Error: ". $stmt->error . "');</script>";
		}
		$stmt->close();
	}
	$checkStmt->close();
}
if(isset($_POST['submit']) && isset($_POST['room'])){


	$room=$_POST['room'];
	$clgName=$_POST['clgName'];
	$hostelName=$_POST['hostelName'];
	$parts = explode(',', $room);
	$roomno = $parts[0];
	$seater=$_POST['seater'];
	$gender = $_POST["gender"];
	$result ="SELECT count(*) FROM registration WHERE hostelName=? AND roomno=? AND gender=? AND status='verified'";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('sss',$hostelName,$roomno,$gender);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	if($count >= $seater){
		echo "<script>alert('No Seat Available for room ".$roomno."');</script>";
	}
	else{
		$timestamp = date('Y-m-d H:i:s');

		$seater=$_POST['seater'];
		$feespm=$_POST['fpm'];
		$roomId=$_POST['roomId'];
		// echo $roomId;
		// $foodstatus=$_POST['foodstatus']; //not exist
		$stayfrom=$_POST['stayf'];
		$academicYear=$_POST['academicYear'];
		$course=$_POST['course'];
		$classY=$_POST['classY'];
		$userPrn=$_POST['userPrn'];
		$fname=$_POST['fname'];
		$mname=$_POST['mname'];
		$lname=$_POST['lname'];
		$fullName = $fname." ".$mname." ".$lname;

		$gender=$_POST['gender'];
		$contactno=$_POST['contact'];
		$emailid=$_POST['email'];
		$emcntno=trim($_POST['econtact']);
		$gurname=trim($_POST['gname']);
		$gurrelation=trim($_POST['grelation']);
		$gurcntno=trim($_POST['gcontact']);
		$caddress=$_POST['address'];
		$ccity=$_POST['city'];
		$cstate=$_POST['state'];
		$cpincode=trim($_POST['pincode']);
		$paddress=$_POST['paddress'];
		$pcity=$_POST['pcity'];
		$pstate=$_POST['pstate'];
		$ppincode=trim($_POST['ppincode']);
		$status= "pending";

		// Prepare the SQL query
		$checkquery = "SELECT * FROM registration WHERE  userPrn=?";
		$checkstmt = $mysqli->prepare($checkquery);
		// Bind the parameters
		$checkstmt->bind_param('s', $userPrn);
		// Execute the statement
		$checkstmt->execute();
		// Get the result
		$result = $checkstmt->get_result();
		$row = $result->fetch_object();
		// Close the statement
		$checkstmt->close();
		if($result->num_rows > 0){
			echo "<script>alert('Student Application Exits');</script>";
		}else{
// INSERT INTO `registration`(`id`, `roomno`, `seater`, `feespm`, `foodstatus`, `stayfrom`, `academicYear`, `course`, `class`, `userPrn`, `firstName`, `middleName`, `lastName`, `gender`, `contactno`, `emailid`, `egycontactno`, `guardianName`, `guardianRelation`, `guardianContactno`, `corresAddress`, `corresCIty`, `corresState`, `corresPincode`, `pmntAddress`, `pmntCity`, `pmnatetState`, `pmntPincode`, `postingDate`, `updationDate`, `status`, `comment`, `verifiedBy`, `clgName`, `hostelName`, `statusUpdationDate`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]','[value-13]','[value-14]','[value-15]','[value-16]','[value-17]','[value-18]','[value-19]','[value-20]','[value-21]','[value-22]','[value-23]','[value-24]','[value-25]','[value-26]','[value-27]','[value-28]','[value-29]','[value-30]','[value-31]','[value-32]','[value-33]','[value-34]','[value-35]','[value-36]')
			$query="insert into  registration(
			roomno,
			seater,
			feespm,
			roomId,
			stayfrom,
			academicYear,
			course,
			class,
			userPrn,
			firstName,
			middleName,
			lastName,
			gender,
			contactno,
			emailid,
			egycontactno,
			guardianName,
			guardianRelation,
			guardianContactno,
			corresAddress,
			corresCIty,
			corresState,
			corresPincode,
			pmntAddress,
			pmntCity,
			pmnatetState,
			pmntPincode,
			postingDate,
			status,
			clgName,
			hostelName) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sidisssssssssisississsisssissss',$roomno,$seater,$feespm,$roomId,$stayfrom,$academicYear,$course,$classY,$userPrn,$fname,$mname,$lname,$gender,$contactno,$emailid,$emcntno,$gurname,$gurrelation,$gurcntno,$caddress,$ccity,$cstate,$cpincode,$paddress,$pcity,$pstate,$ppincode,$timestamp,$status,$clgName,$hostelName);
			if ($stmt->execute()) {
				echo "<script>alert('Student Succssfully register');</script>";
				// Assuming $mysqli is your database connection
				$checkQuery = "SELECT * FROM report WHERE userPrn = ? AND status = 'pending'";
				$checkStmt = $mysqli->prepare($checkQuery);
				$checkStmt->bind_param('i', $userPrn);  // Assuming userPrn is a string, change to 'i' if it's an integer
				$checkStmt->execute();
				$result = $checkStmt->get_result();
				$row = $result->fetch_object();
				// print_r($row);
			
				if ($result->num_rows > 0) {
					// User has pending payments
					echo "<script>alert('You have pending payments.');</script>";
					
				}else{
					// Insert a new record
					$reportTotalAmount = 0;
					$paymentStatus = "Not Paid";
					$status = "pending";
					$queryPay = "INSERT INTO report ( stayFrom, reportTotalAmount, class, fullName, userPrn, emailid, contactno, roomno, roomFeesphy, academicYear, paymentStatus, status, remainingAmount, clgName, hostelName ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$payStmt = $mysqli->prepare($queryPay);
					$payStmt->bind_param('sdssssisdsssdss',$stayfrom, $reportTotalAmount, $classY, $fullName, $userPrn, $emailid, $contactno, $roomno, $feespm, $academicYear, $paymentStatus, $status, $feespm, $clgName, $hostelName);

					if ($payStmt->execute()) {
						echo "<script>window.location.href='payment-form.php';</script>";
					} else {
						echo "<script>alert('Error: ". $payStmt->error . "');</script>";
					}
					$payStmt->close();
				}
			}else{
				echo "<script>alert('Error: ". $stmt->error . "');</script>";
			}
			$stmt->close();
		}
	}
	
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	<title>Student Bus Registration</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Registration </h2>

						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-heading">Fill all Info</div>
									<div class="panel-body">
										<form method="post" action="" class="form-horizontal">
										<?php

										$email = $_SESSION['login'];
										$userPrn = $_SESSION['userPrn'];
										// echo $email." ".$userPrn;

										// Prepare the SQL query
										$query = "SELECT * FROM registration WHERE userPrn=?";
										$stmt = $mysqli->prepare($query);
										// Bind the parameters
										$stmt->bind_param('s', $userPrn);
										// Execute the statement
										$stmt->execute();
										// Get the result
										$result = $stmt->get_result();
										$row = $result->fetch_object();
										// Close the statement
										$stmt->close();
										if($result->num_rows > 0){ 
											$prevAcademicYear = $row->academicYear;
											$parts = explode('-', $prevAcademicYear);
											$preYear = $parts[0];

											if($currentYear <= $preYear){
											?>
											<h3 style="color: red" align="center">Bus already booked by you</h3>
											<div align="center">
												<div class="col-md-4">&nbsp;</div>
												<div class="col-md-4">
													<div class="panel panel-default">
														<div class="panel-body bk-success text-light">
															<div class="stat-panel text-center">

																<div class="stat-panel-number h1 ">My Bus Status <?php echo $row->status;?></div>
																
															</div>
														</div>
														<a href="room-details.php" class="block-anchor panel-footer text-center">See All &nbsp; <i class="fa fa-arrow-right"></i></a>
													</div>
												</div>
											</div>
										<?php }else{
											$openDate = $_SESSION['fromDate'];
											$expDate = $_SESSION['toDate'];
											$msg = $_SESSION['option'];
	
											if(isset($_SESSION['option']) && $currentDate >= $openDate && $currentDate < $expDate){
										?>

										<form method="post" action="" class="form-horizontal">

											<div class="form-group">
											<label class="col-sm-2 control-label">Bus No<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="roomno" id="roomno"  class="form-control" value="<?php echo $row->roomno;?>" readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Fees Per Year<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="fpm" id="fpm" value="<?php echo $row->feespm;?>"  class="form-control" readonly="true">
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Institute<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="clgName" id="userPrn"  class="form-control" value="<?php echo $row->clgName;?>" readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Driver Name<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="hostelName" id="userPrn"  class="form-control" value="<?php echo $row->hostelName;?>" readonly>
											</div>
											</div>
											
											<div class="form-group">
											<label class="col-sm-2 control-label">Registration No<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="userPrn" id="userPrn"  class="form-control" value="<?php echo $row->userPrn;?>" readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">First Name<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="fname" id="fname"  class="form-control" value="<?php echo $row->firstName;?>" readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Middle Name<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="mname" id="mname"  class="form-control" value="<?php echo $row->middleName;?>"  readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Last Name<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="lname" id="lname"  class="form-control" value="<?php echo $row->lastName;?>" readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Contact No<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="text" name="contact" id="contact" value="<?php echo $row->contactno;?>"  class="form-control" readonly>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Email id<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="email" name="email" id="email"  class="form-control" value="<?php echo $row->emailid;?>"  readonly>
											</div>
											</div>
											
											<div class="form-group">
												<label class="col-sm-2 control-label">Class<span class="text-danger">*</span> : </label>
												<div class="col-sm-8">
													<input type="radio" value="First-Year" name="classY" checked="checked"> First-Year <br>
													<input type="radio" value="Second-Year" name="classY"> Second-Year <br>
													<input type="radio" value="Third-Year" name="classY" > Third-Year <br>
													<input type="radio" value="Fourth-Year" name="classY"> Fourth-Year <br>
												</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Start From<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<input type="date" name="stayf" id="stayf" min="<?php echo date('Y-m-d'); ?>" class="form-control" required>
											</div>
											</div>

											<div class="form-group">
											<label class="col-sm-2 control-label">Academic Year<span class="text-danger">*</span> : </label>
											<div class="col-sm-8">
											<?php
												echo "<input type='text' name='renew' id='academicYear'  class='form-control' value=\"$academicYear\" readonly>";
											?>
											</div>
											</div>

											<div class="col-sm-6 col-sm-offset-4">
											<button class="btn btn-default" type="submit">Cancel</button>
											<input type="submit" name="submit" Value="Renew" class="btn btn-primary">
											</div>

										</form>
									<?php }else{
											?>
												<h1><b>Join Us!</b><br><br>Bus <?php echo $msg?> is Start from <?php echo $openDate?> through <?php echo $expDate?>.</h1>
											<?php
										}
										}
									}else{
										$openDate = $_SESSION['fromDate'];
										$expDate = $_SESSION['toDate'];
										$msg = $_SESSION['option'];

										if(isset($_SESSION['option']) && $_SESSION['option'] =='register' && $currentDate >= $openDate && $currentDate <= $expDate){
												
									?>			
										<div class="form-group">
										<label class="col-sm-4 control-label"><h4 style="color: green" align="left">Bus Related Information </h4> </label>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Bus no.<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="room" id="room"class="form-control" gender="<?php echo $_SESSION['gender'];?>" onChange="checkRoomAvailability(this.value)"  required> 
										<option value="">Select Bus</option>
										<?php
										$gender = $_SESSION['gender'];
										// $query = "SELECT * FROM rooms WHERE gender = ? AND clgName = ? ";
										$query = "SELECT r.* FROM rooms r LEFT JOIN hostel h ON r.hostelName = h.hostelName WHERE r.gender = ? AND r.clgName = ? AND h.active = 1 ";
										$stmt2 = $mysqli->prepare($query);
										$stmt2->bind_param('ss',$gender, $collegName);
										$stmt2->execute();
										$res=$stmt2->get_result();
										while($row=$res->fetch_object())
										{
											?>
											<option value="<?php echo $row->room_no.",".$row->gender.",".$row->seater.",".$row->yearlyFees.",".$row->hostelName.",".$row->clgName.",".$row->id?>" > <?php echo $row->clgName." -> ".$row->hostelName." -> ".$row->room_no; ?> </option>
										<?php } ?>
										</select> 
										<span id="room-availability-status" style="font-size:12px;"></span>
										</div>
										</div>

										<input type="number" id="roomId" name="roomId" require hidden>
																					
										<div class="form-group">
										<label class="col-sm-2 control-label">College Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="clgName" id="clgName"  class="form-control" readonly="true">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Route  Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="hostelName" id="hostelName"  class="form-control" readonly="true">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Seater<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="seater" id="seater"  class="form-control" readonly="true">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Fees Per Year<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="fpm" id="fpm"  class="form-control" readonly="true">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Stay From<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="date" name="stayf" id="stayf" min="<?php echo $currentDate; ?>" class="form-control" required>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Academic Year<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<?php
											echo "<input type='text' name='academicYear' id='academicYear'  class='form-control' value=\"$academicYear\" readonly>";
										?>
										</div>
										</div>



										<div class="form-group">
										<label class="col-sm-2 control-label"><h4 style="color: green" align="left">Personal Information </h4> </label>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Course<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="course" id="course" class="form-control" required> 
										<option value="">Select Course</option>

										<?php
										$queryCourse = "SELECT * FROM courses WHERE clgName = ? ";
										$stmtCourse = $mysqli->prepare($queryCourse);
										$stmtCourse->bind_param('s', $collegName);
										// $query ="SELECT * FROM courses";
										// $stmt2 = $mysqli->prepare($query);
										$stmtCourse->execute();
										$res=$stmtCourse->get_result();
										while($row=$res->fetch_object())
										{
										?>
										<option value="<?php echo $row->course_fn;?>"><?php echo $row->course_fn;?>&nbsp;&nbsp;(<?php echo $row->course_sn;?>)</option>
										<?php } ?>
										</select> </div>
										</div>

										<div class="form-group">
											<label class="col-sm-2 control-label"> Class <span class="text-danger">*</span> :</label>
											<div class="col-sm-8">
												<input type="radio" value="First-Year" name="classY" checked="checked"> First-Year <br>
												<input type="radio" value="Second-Year" name="classY"> Second-Year <br>
												<input type="radio" value="Third-Year" name="classY" > Third-Year <br>
												<input type="radio" value="Fourth-Year" name="classY"> Fourth-Year <br>
											</div>
										</div>

										<?php	
										$aid=$_SESSION['id'];
											$ret="select * from userregistration where id=?";
												$stmt= $mysqli->prepare($ret) ;
											$stmt->bind_param('i',$aid);
											$stmt->execute() ;//ok
											$res=$stmt->get_result();
											//$cnt=1;
											while($row=$res->fetch_object())
											{
												?>
										<div class="form-group">
										<label class="col-sm-2 control-label">Registration No <span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="userPrn" id="userPrn"  class="form-control" value="<?php echo $row->userPrn;?>" readonly >
										</div>
										</div>


										<div class="form-group">
										<label class="col-sm-2 control-label">First Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="fname" id="fname"  class="form-control" value="<?php echo $row->firstName;?>" readonly>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Middle Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="mname" id="mname"  class="form-control" value="<?php echo $row->middleName;?>"  readonly>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Last Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="lname" id="lname"  class="form-control" value="<?php echo $row->lastName;?>" readonly>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Gender<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="gender" value="<?php echo $row->gender;?>" class="form-control" readonly>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Contact No<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="contact" id="contact" value="<?php echo $row->contactNo;?>"  class="form-control" readonly>
										</div>
										</div>


										<div class="form-group">
										<label class="col-sm-2 control-label">Email id<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="email" name="email" id="email"  class="form-control" value="<?php echo $row->email;?>"  readonly>
										</div>
										</div>
										<?php } ?>
										<div class="form-group">
										<label class="col-sm-2 control-label">Emergency Contact<span class="text-danger">*</span>: </label>
										<div class="col-sm-8">
										<input type="text" name="econtact" id="econtact" pattern="[0-9]{10}" minlength="10" maxlength="10" class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Guardian Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="gname" id="gname" 
											pattern="[A-Za-z\s]+" 
											title="Please enter a valid full name with letters and spaces only" 
											class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Guardian  Relation<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="grelation" id="grelation" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Guardian Contact no<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="gcontact" id="gcontact" pattern="[0-9]{10}" minlength="10" maxlength="10" class="form-control" required="required">
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-3 control-label"><h4 style="color: green" align="left">Current Address<span class="text-danger">*</span> </h4> </label>
										</div>


										<div class="form-group">
										<label class="col-sm-2 control-label">Address<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<textarea  rows="5" name="address"  id="address" class="form-control" required="required"></textarea>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">City<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="city" id="city"  class="form-control" required="required">
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-2 control-label">State<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="state" id="state"class="form-control" required> 
										<option value="">Select State</option>
										<?php $query ="SELECT * FROM states";
										$stmt2 = $mysqli->prepare($query);
										$stmt2->execute();
										$res=$stmt2->get_result();
										while($row=$res->fetch_object())
										{
										?>
										<option value="<?php echo $row->State;?>" ><?php echo $row->State;?></option>
										<?php } ?>
										</select> </div>
										</div>							

										<div class="form-group">
										<label class="col-sm-2 control-label">Pincode<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="pincode" id="pincode" pattern="[0-9]{6}"  class="form-control" minlength="6" maxlength="6" required="required">
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-3 control-label"><h4 style="color: green" align="left">Permanent Address </h4> </label>
										</div>


										<div class="form-group">
										<label class="col-sm-5 control-label">Permanent Address same as Current address<span class="text-danger">*</span> : </label>
										<div class="col-sm-4">
										<input type="checkbox" name="adcheck" value="1"/>
										</div>
										</div>


										<div class="form-group">
										<label class="col-sm-2 control-label">Address<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<textarea  rows="5" name="paddress"  id="paddress" class="form-control" required="required"></textarea>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">City<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="pcity" id="pcity"  class="form-control" required="required">
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-2 control-label">State<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="pstate" id="pstate"class="form-control" required> 
										<option value="">Select State</option>
										<?php $query ="SELECT * FROM states";
										$stmt2 = $mysqli->prepare($query);
										$stmt2->execute();
										$res=$stmt2->get_result();
										while($row=$res->fetch_object())
										{
										?>
										<option value="<?php echo $row->State;?>"><?php echo $row->State;?></option>
										<?php } ?>
										</select> </div>
										</div>							

										<div class="form-group">
										<label class="col-sm-2 control-label">Pincode<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="ppincode" id="ppincode"  class="form-control" required="required">
										</div>
										</div>

										<div class="col-sm-6 col-sm-offset-4">
										<button class="btn btn-default" type="submit">Cancel</button>
										<input type="submit" id="submit_Btn" name="submit" Value="Register" class="btn btn-primary">
										</div>
										</form>
										<?php }else{
											?>
												<h1><b>Join Us!</b><br><br>Hostel <?php echo $msg?> is open from <?php echo $openDate?> through <?php echo $expDate?>.</h1>
											<?php
										}
										} ?>

										</div>
										</div>
									</div>
								</div>
							</div>
								</div>
							</div>
						</div>
					</div> 	
				</div>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap-select.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
		<script src="js/Chart.min.js"></script>
		<script src="js/fileinput.js"></script>
		<script src="js/chartData.js"></script>
		<script src="js/main.js"></script>
	</body>
	<script type="text/javascript">
		const roomAvail = document.getElementById("room-availability-status");
		// const roomEle = document.getElementById("room");
		let submitBtn = document.getElementById("submit_Btn");
		let userExist = document.getElementById("user_exist");

		$(document).ready(function(){
			$('input[type="checkbox"]').click(function(){
				if($(this).prop("checked") == true){
					$('#paddress').val( $('#address').val() );
					$('#pcity').val( $('#city').val() );
					$('#pstate').val( $('#state').val() );
					$('#ppincode').val( $('#pincode').val() );
				} 
				
			});
		});

		function checkRoomAvailability(val) {
			// console.log(val);
			const parts = val.split(',');
			// console.log(parts);

			let room = parts[0];
			let gender = parts[1];
			let seater = parts[2];
			$('#seater').val(parts[2]);
			$('#fpm').val(parts[3]);
			$('#hostelName').val(parts[4]);
			$('#clgName').val(parts[5]);
			$('#roomId').val(Number(parts[6]));
			// $('#roomId').val(parts[6]);
			// let roomId = parts[6];
			// let available = parts[7];
			let hostelName = parts[4];

			submitBtn.disabled = true;
			

			jQuery.ajax({
			url: "check_availability.php",
			data:{
				roomno: room,
				gender: gender,
				hostelName: hostelName
			},
			type: "POST",
			success:function(occupied){
				// console.log(seater);
				// console.log(occupied);
				let available = seater-occupied;

				if (occupied >= seater) {
					roomAvail.style.color = "red";
					roomAvail.textContent = "All Seats Already Full.";
					// submitBtn.disabled = true;
				}else if(occupied > 0){
					roomAvail.style.color = "green";
					roomAvail.textContent = available+" Seat Available .";
					submitBtn.disabled = false;
				}else{
					roomAvail.style.color = "green";
					roomAvail.textContent = "All Seats Available.";
					submitBtn.disabled = false;
				}
			},
				error:function (){}
			});
			
		}

		// function checkAvailability(val) {
		// 	// const seat = document.getElementById('room').value;
		// 	// console.log(('#room').val());
		// 	const parts = val.split('.');

		// 	const id = parts[0];
		// 	const seater = parts[1];
		// $("#loaderIcon").show();

		// const gender = $("#room").attr("gender");
		// // console.log(gender);
		// jQuery.ajax({
		// url: "check_availability.php",
		// data:{
		// 	roomno: id,
		// 	gender: gender,
		// 	seat: seater
		// },
		// type: "POST",
		// success:function(data){
		// $("#room-availability-status").html(data);
		// $("#loaderIcon").hide();
		// },
		// error:function (){}
		// });
		// }
		</script>


		<script type="text/javascript">

		$(document).ready(function() {
			$('#academicYear').keyup(function(){
				var fetch_dbid = $(this).val();
				$.ajax({
				type:'POST',
				url :"ins-amt.php?action=userid",
				data :{userinfo:fetch_dbid},
				success:function(data){
				$('.result').val(data);
				}
				});
				

		})});
		</script>
</html>