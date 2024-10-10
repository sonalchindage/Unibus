<?php
	session_start();
	include('includes/config.php');
	include('includes/checklogin.php');
	check_login();
	//code for registration
	// $userExist = false; 
	$currentYear = date("Y");
	$currentDate = date("Y-m-d");
	$year = $currentYear."-".$currentYear+1;
	if(isset($_POST['submit'])){
		$room=$_POST['userExist'];

		$room=$_POST['room'];
		$parts = explode('.', $room);
		$roomno = $parts[0];

		$seater=$_POST['seater'];
		$feespm=$_POST['fpm'];
		$foodstatus=$_POST['foodstatus'];
		$stayfrom=$_POST['stayf'];
		$duration=$_POST['duration'];
		$course=$_POST['course'];
		$regno=$_POST['regno'];
		$fname=$_POST['fname'];
		$mname=$_POST['mname'];
		$lname=$_POST['lname'];
		$fullName = $fname." ".$mname." ".$lname;

		$gender=$_POST['gender'];
		$contactno=$_POST['contact'];
		$emailid=$_POST['email'];
		$emcntno=$_POST['econtact'];
		$gurname=$_POST['gname'];
		$gurrelation=$_POST['grelation'];
		$gurcntno=$_POST['gcontact'];
		$caddress=$_POST['address'];
		$ccity=$_POST['city'];
		$cstate=$_POST['state'];
		$cpincode=$_POST['pincode'];
		$paddress=$_POST['paddress'];
		$pcity=$_POST['pcity'];
		$pstate=$_POST['pstate'];
		$ppincode=$_POST['ppincode'];
		$status= "pending";

		$result ="SELECT count(*) FROM userRegistration WHERE email=? || regNo=?";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('ss',$email,$regno);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
		if($userExist)
		{
		echo"<script>alert('Registration number or email id already registered.');</script>";
		}else{
			// add user
			$query1="insert into  userregistration(regNo,firstName,middleName,lastName,gender,contactNo,email,password) values(?,?,?,?,?,?,?,?)";
			$stmt1= $mysqli->prepare($query1);
			$stmt1->bind_param('sssssiss',$regno,$fname,$mname,$lname,$gender,$contactno,$emailid,$contactno);
			if($stmt1->execute()){
				echo"<script>alert('Student Succssfully register');</script>";
			}
			$stmt1->close();
			
		}

		//check for hostel booking
		$checkquery = "SELECT * FROM registration WHERE  regno=?";
		$checkstmt = $mysqli->prepare($checkquery);
		// Bind the parameters
		$checkstmt->bind_param('s', $regno);
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
			$query="insert into  registration(roomno,seater,feespm,foodstatus,stayfrom,duration,course,regno,firstName,middleName,lastName,gender,contactno,emailid,egycontactno,guardianName,guardianRelation,guardianContactno,corresAddress,corresCIty,corresState,corresPincode,pmntAddress,pmntCity,pmnatetState,pmntPincode,status) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$regStmt = $mysqli->prepare($query);
			$rc=$regStmt->bind_param('siiissssssssisississsisssis',$roomno,$seater,$feespm,$foodstatus,$stayfrom,$duration,$course,$regno,$fname,$mname,$lname,$gender,$contactno,$emailid,$emcntno,$gurname,$gurrelation,$gurcntno,$caddress,$ccity,$cstate,$cpincode,$paddress,$pcity,$pstate,$ppincode,$status);
			if ($regStmt->execute()) {
				echo "<script>
						alert('Student Succssfully register');
						window.location.href='payment-form.php?id=';
						</script>";
	
				// Insert a new record
				$paidAmount = 0;
				$paidStatus = "Not Paid";
				$status = "pending";
				$queryPay = "INSERT INTO payments ( paidAmount, fname, userPrn, emailid, contactno, roomno, feespm, year, paidStatus, statusV) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$payStmt = $mysqli->prepare($queryPay);
				$payStmt->bind_param('dsssssisss', $paidAmount, $fullName, $regno, $emailid, $contactno, $roomno, $feespm, $duration, $paidStatus, $status);
	
				if ($payStmt->execute()) {
					echo "<script>alert('Payment successfully recorded');
						window.location.href='payment-form.php';</script>";
				} else {
					echo "<script>alert('Error: ". $stmt->error . "');</script>";
				}
				$payStmt->close();
			}else{
				echo "<script>alert('Error: ". $regStmt->error . "');</script>";
			}
			$regStmt->close();
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
	<title>Student Hostel Registration</title>
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
											
										
										<div class="form-group">
										<label class="col-sm-4 control-label"><h4 style="color: green" align="left">Room Related info </h4> </label>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Room No.<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="room" id="room"class="form-control"  onChange="getSeater(this.value);" onBlur="checkRoomAvailability(this.value)" required> 
										<option value="">Select Room</option>
										<?php $query ="SELECT * FROM rooms";
										$stmt2 = $mysqli->prepare($query);
										$stmt2->execute();
										$res=$stmt2->get_result();
										while($row=$res->fetch_object())
										{
										?>
										<option value="<?php echo $row->room_no.".".$row->gender.".".$row->seater.".".$row->fees;?>"><?php echo $row->room_no;?> | <?php echo $row->gender; ?></option>
										<?php } ?>
										</select> 
										<span id="room-availability-status" style="font-size:12px;"></span>

										</div>
										</div>
																					
										<div class="form-group">
										<label class="col-sm-2 control-label">Seater<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="seater" id="seater"  class="form-control" readonly="true"  >
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Fees Per Year<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="fpm" id="fpm"  class="form-control" readonly="true">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Food Status<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="radio" value="0" name="foodstatus" checked="checked"> Without Food
										<input type="radio" value="1" name="foodstatus"> With Food(Rs 2000.00 Per Month Extra)
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-2 control-label">Stay From<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="date" name="stayf" id="stayf" min="<?php echo $currentDate; ?>" class="form-control" >
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Year<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<?php
											echo "<input type='text' name='duration' id='duration'  class='form-control' value=\"$year\" readonly>";
										?>
										</div>
										</div>


										<div class="form-group">
										<label class="col-sm-2 control-label"><h4 style="color: green" align="left">Personal info </h4> </label>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Course<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="course" id="course" class="form-control" required> 
										<option value="">Select Course</option>
										<?php $query ="SELECT * FROM courses";
										$stmt2 = $mysqli->prepare($query);
										$stmt2->execute();
										$res=$stmt2->get_result();
										while($row=$res->fetch_object())
										{
										?>
										<option value="<?php echo $row->course_fn;?>"><?php echo $row->course_fn;?>&nbsp;&nbsp;(<?php echo $row->course_sn;?>)</option>
										<?php } ?>
										</select> </div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Registration No<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="regno" id="regno"  class="form-control" required="required"  onBlur="checkRegnoAvailability()">
										<span id="user-reg-availability" style="font-size:12px;"></span>
										</div>
										</div>

										<input type="hidden" id="user_exist" name="userExist" >


										<div class="form-group">
										<label class="col-sm-2 control-label">First Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="fname" id="fname" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" class="form-control" required="required" >
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Middle Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="mname" id="mname" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" class="form-control">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Last Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="lname" id="lname" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Gender<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<select name="gender" id="gender" class="form-control" required="required">
										<option value="">Select Gender</option>
										<option value="male">Male</option>
										<option value="female">Female</option>
										<option value="others">Others</option>
										</select>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Contact<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="contact" id="contact" pattern="[0-9]{10}" minlength="10" maxlength="10" class="form-control" required="required" >
										</div>
										</div>


										<div class="form-group">
										<label class="col-sm-2 control-label">Email id<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="email" name="email" id="email"  class="form-control" onBlur="checkAvailability()" required="required">
										<span id="user-availability-status" style="font-size:12px;"></span>
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Emergency Contact<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="econtact" id="econtact" pattern="[0-9]{10}" minlength="10" maxlength="10" class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Guardian Name<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="gname" id="gname" pattern="^[A-Za-z]+(\s[A-Za-z]+)*$" class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Guardian Relation<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="grelation" id="grelation" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" class="form-control" required="required">
										</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Guardian Contact<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="gcontact" id="gcontact" pattern="[0-9]{10}" minlength="10" maxlength="10" class="form-control" required="required">
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-3 control-label"><h4 style="color: green" align="left">Current Address</h4> </label>
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
										<option value="<?php echo $row->State;?>"><?php echo $row->State;?></option>
										<?php } ?>
										</select> </div>
										</div>							

										<div class="form-group">
										<label class="col-sm-2 control-label">Pincode<span class="text-danger">*</span> : </label>
										<div class="col-sm-8">
										<input type="text" name="pincode" id="pincode"  class="form-control" required="required">
										</div>
										</div>	

										<div class="form-group">
										<label class="col-sm-3 control-label"><h4 style="color: green" align="left">Permanent Address</h4> </label>
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
										<input type="submit" name="submit" id="submit_Btn" Value="Register" class="btn btn-primary">
										</div>
										</form>

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
		// function getSeater(val) {
		// 	const parts = val.split('.');

		// 	const id = parts[0];
		// 	const gender = parts[1]

		// 	$.ajax({
		// 		type: "POST",
		// 		url: "get_seater.php",
		// 		data: {
		// 			roomid: id,
		// 			gender: gender
		// 		},
		// 		success: function(data) {
		// 			const result = JSON.parse(data);
		// 			$('#seater').val(result.seater);
		// 			$('#fpm').val(result.fees);
		// 		}
		// 	});
		// }
		function checkRoomAvailability(val) {
			const parts = val.split('.');
			let room = parts[0];
			let gender = parts[1];
			let seater = parts[2];
			$('#seater').val(parts[2]);
			$('#fpm').val(parts[3]);
			submitBtn.disabled = true;

		$("#loaderIcon").show();
		jQuery.ajax({
		url: "check_availability.php",
		data:{
			roomno : room,
			gender : gender
		},
		type: "POST",
		success:function(data){
			$("#room-availability-status").html(data);
			$("#loaderIcon").hide();
			const result = JSON.parse(data);
			// console.log(data);
			
			let roomAvail = document.getElementById("room-availability-status");
			
			if (data >= seater) {
				roomAvail.style.color = "red";
				roomAvail.textContent = "All Seats Already Full.";
				// submitBtn.disabled = true;
			}else if(data > 0){
				roomAvail.style.color = "green";
				roomAvail.textContent = data+" Seat Already Full.";
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
		</script>

			<script>
		function checkAvailability() {

		$("#loaderIcon").show();
		jQuery.ajax({
		url: "check_availability.php",
		data:'emailid='+$("#email").val(),
		type: "POST",
		success:function(data){
		$("#user-availability-status").html(data);
		$("#loaderIcon").hide();
		},
		error:function ()
		{
		event.preventDefault();
		alert('error');
		}
		});
		}
		</script>
			<script>
		function checkRegnoAvailability() {

			let fname = document.getElementById("fname");
			let mname = document.getElementById("mname");
			let lname = document.getElementById("lname");
			let gender = document.getElementById("gender");
			let email = document.getElementById("email");
			let contact = document.getElementById("contact");

			$("#loaderIcon").show();
			jQuery.ajax({
				url: "check_availability.php",
				data:'regno='+$("#regno").val(),
				type: "POST",
				success:function(data){
					const result = JSON.parse(data);
					// console.log(result);
					let userReg = document.getElementById("user-reg-availability");
					if (result) {
						userReg.style.color = "red";
						userReg.textContent = "Student already Registered Room";
					} else {
						userReg.style.color = "green";
						userReg.textContent = "New Registration No.";
					}
					$("#loaderIcon").hide();
				},
				error:function (){
					event.preventDefault();
					alert('error');
				}
			});

			jQuery.ajax({
				url: "check_availability.php",
				data:'regNo='+$("#regno").val(),
				type: "POST",
				success:function(data){
					const result = JSON.parse(data);
					// console.log(result);
					let userReg = document.getElementById("user-reg-availability");
					if (result) {
						console.log(result);
						// console.log(userExist.value);
						userExist.value = true;
						console.log(userExist.value);
						
						fname.value = result.firstName;
						lname.value = result.lastName;
						mname.value = result.middleName;
						gender.value = result.gender;
						email.value = result.email;
						contact.value = result.contactNo;
					}else{
						userExist.value = false;
					}
					// $("#loaderIcon").hide();
				},
				error:function (){
					event.preventDefault();
					alert('error');
				}
			});
		}
	</script>

</html>