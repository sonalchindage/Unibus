<?php
session_start();
include('includes/config.php');
include('includes/enc.php');
include('includes/checklogin.php');
// check_login();
// check_status();
if(isset($_POST['update']))
{
	$email = trim($_POST['email']);
	$password = encrypt($_POST['password']);
	$cpassword = encrypt($_POST['cpassword']);
	if($password == $cpassword){
		$stmt=$mysqli->prepare("UPDATE userregistration SET password=? WHERE email=?");
		$stmt->bind_param('ss',$password,$email);
		if($stmt->execute()){
			echo "<script>alert('Password Changed Succesfully');
				window.location.href='index.php';</script>";

			// echo "<script>alert('Password Changed Succesfully');</script>";
		}else{
			echo "<script>alert('Invalid Email/Contact no or password');</script>";
		}
		unset($_POST);
	}else{
		echo "<script>alert('Password and Confirm Password is not same');</script>";
	}
	// echo $_POST;
	// print_r($_POST);
	// echo $password;
	// SELECT `id`, `userPrn`, `clgName`, `course`, `firstName`, `middleName`, `lastName`, `gender`, `contactNo`, `email`, `password`, `regDate`, `updationDate`, `passUdateDate` FROM `userregistration` WHERE 1
	// UPDATE `userregistration` SET `id`='[value-1]',`userPrn`='[value-2]',`clgName`='[value-3]',`course`='[value-4]',`firstName`='[value-5]',`middleName`='[value-6]',`lastName`='[value-7]',`gender`='[value-8]',`contactNo`='[value-9]',`email`='[value-10]',`password`='[value-11]',`emailValidate`='[value-12]',`regDate`='[value-13]',`updationDate`='[value-14]',`passUdateDate`='[value-15]' WHERE 1
	// $stmt=$mysqli->prepare("UPDATE userregistration SET password=? WHERE email=?");
	// $stmt->bind_param('ss',$password,$email);
	// if($stmt->execute()){
	// 	echo "<script>alert('Password Changed Succesfully');
	// 		window.location.href='index.php';</script>";

	// 	// echo "<script>alert('Password Changed Succesfully');</script>";
	// }else{
	// 	echo "<script>alert('Invalid Email/Contact no or password');</script>";
	// }
	// unset($_POST);
	// $_POST = array();

	// print_r($_POST);
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

	<title>User Forgot Password</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript">
		function valid()
		{
		if(document.registration.password.value!= document.registration.cpassword.value)
		{
		alert("Password and Re-Type Password Field do not match  !!");
		document.registration.cpassword.focus();
		return false;
		}
		return true;
		}
	</script>
</head>
<body>
	
	<div class="login-page bk-img" style="background-image: url(./img/download.jpeg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold text-light mt-4x">Forgot Password</h1>
						<div class="well row pt-2x pb-2x bk-light">
							<div class="col-md-12">
							<?php if(isset($_POST['login']) && isset($pwd))
							{ ?>
							<p>Your Password is <?php echo $pwd;?><br> Change the Password After login</p>
							<?php }  ?>
								<form action="" class="mt" method="post" class="form-horizontal" onSubmit="return valid();">
									<div class="form-group mb-2x " id="email_container">
										<label class="col-md-12 text-uppercase control-label">Email id<span class="text-danger">*</span></label>
										<div class="col-md-9">
											<input type="email" name="email" id="emailInput" maxlength="30" class="form-control" required="required">
											<span id="user-email-status" style="font-size:12px;"></span>
										</div>
										<div class="input-group-append col-md-3">
											<button class="btn btn-outline-secondary" type="button" id="action_send_otp">Send OTP</button>
										</div>
									</div>
									
									<div class="form-group" id="otp_container">
										<label class="col-md-12 text-uppercase control-label" for="otpInput">OTP<span class="text-danger">*</span></label>
										<div class="col-md-9">
											<input type="number" id="otpVerify" hidden>
											<input type="number" name="otp" id="otpInput" maxlength="10" class="form-control" disabled required="required">
											<span id="user-otp-status" style="font-size:12px;"></span>
										</div>
										<div class="input-group-append col-md-3">
											<button class="btn btn-outline-secondary" type="button" id="action_verify_otp" disabled>Verify OTP</button>
										</div>
									</div>

									<div id="password_container">
										<div class="form-group">
											<label class="col-md-12 control-label">Password<span class="text-danger">*</span> : </label>
											<div class="col-md-12 passwordInputGrp">
												<input type="password" name="password" id="password" minlength="8"
													title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
													class="form-control" required="required" disabled>
												<div class="input-group-append passwordInputEye">
													<button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('password', this)">
														<i class="fa fa-eye"></i>
													</button>
												</div>
											</div>
										</div>

										<!-- Confirm Password Field -->
										<div class="form-group">
											<label class="col-md-12 control-label">Confirm Password<span class="text-danger">*</span> : </label>
											<div class="col-md-12 passwordInputGrp">
												<input type="password" name="cpassword" id="cpassword" class="form-control" required="required" disabled>
												<div class="input-group-append passwordInputEye">
													<button class="btn btn-outline-secondary" type="button" id="toggleCPassword" onclick="togglePasswordVisibility('cpassword', this)">
														<i class="fa fa-eye"></i>
													</button>
												</div>
											</div>
										</div>
										<div class="col-md-8 col-md-offset-2">
											<input type="submit" name="update" id="btn-in" class="btn btn-primary btn-block" value="update" disabled>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="text-center text-light">
							<a href="login.php" class="text-light" style="color:#0000FF">Sign in?</a>
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
	<script>

		$(document).on('click', "#action_send_otp", function(e) {
			e.preventDefault();
			sendOtp();
		});

		function verify(){
			
			let otpVerify = document.getElementById('otpVerify');
			let otpInput = document.getElementById('otpInput');
			let otpBtn = document.getElementById('action_verify_otp');
			let otpContainer = document.getElementById('otp_container');
			let passContainer = document.getElementById('password_container');
			let emailContainer = document.getElementById('email_container');
			// let btnIn = document.getElementById('btn-in');
			if(otpVerify.value === otpInput.value ){
				console.log("True otp");
				otpInput.disabled = true;
				otpBtn.disabled =true;
				otpContainer.style.display = "none";
				// Enable password field if OTP matches
				document.getElementById('password').disabled = false;
				document.getElementById('cpassword').disabled = false;
				document.getElementById('btn-in').disabled = false;
				passContainer.style.display = "block";
				$("#user-otp-status").html("<strong>OTP verified successfully!</strong>");
			} else {
				let emailEle = document.getElementById('emailInput');
				let sendEmail = document.getElementById('action_send_otp');
				console.log("false otp");
				sendEmail.disabled = false;
				emailEle.readOnly = false;
				emailContainer.style.display = "block";
				otpInput.disabled = true;
				otpBtn.disabled =true;
				otpContainer.style.display = "none";
				otpInput.value = null;
				$("#user-email-status").html("<strong>OTP did not match. Please try again.</strong>");
				// $("#user-otp-status").html("<strong>OTP did not match. Please try again.</strong>");
			}
			
		}

		function sendOtp() {
			let otpVerify = document.getElementById('otpVerify');
			let sendEmail = document.getElementById('action_send_otp');
			let emailEle = document.getElementById('emailInput');
			let otpInput = document.getElementById('otpInput');
			let otpBtn = document.getElementById('action_verify_otp');
			let emailContainer = document.getElementById('email_container');
			let otpContainer = document.getElementById('otp_container');
			sendEmail.disabled = true;
			emailEle.readOnly = true;
			let email = emailEle.value.trim();
			const r = Math.floor(Math.random() * 900000) + 100000; // Generate a 6-digit OTP
			otpVerify.value = r;
			console.log(r);
			console.log(email);


			jQuery.ajax({
            url: "response.php",
            data:{
					emailOtp: email,
					otp: r
				},
            type: "POST",
            success:function(data){
				// Manually parse if dataType is not "json"
				var parsedData = JSON.parse(data);
				console.log(parsedData.status);
				console.log(parsedData.message);

				otpInput.disabled = false;
				otpBtn.disabled = false;
				emailContainer.style.display = "none";
				otpContainer.style.display = "block";
				
				$("#user-email-status").html("<strong>" + parsedData.status + "</strong>: " + parsedData.message);
				// Start 60 seconds timer
				let timeLeft = 60;
				const timerInterval = setInterval(function() {
					if (timeLeft > 0) {
						$("#user-otp-status").html("<strong>Time left: " + timeLeft + " seconds</strong>");
						timeLeft--;
						$(document).on('click', "#action_verify_otp", function(e) {
							e.preventDefault();
							clearInterval(timerInterval);
							verify();
						});
					} else {
						clearInterval(timerInterval);
						otpInput.disabled = true;
						otpBtn.disabled = true;
						otpContainer.style.display = "none";
						sendEmail.disabled = false;
						emailEle.readOnly = false;
						emailContainer.style.display = "block";
					}
				}, 1000); // Update every 1 second
            },
            error:function ()
            {
            event.preventDefault();
            alert('error');
            }
            });
		}

	</script>
</body>
</html>