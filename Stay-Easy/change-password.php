<?php
session_start();
include('includes/config.php');
include 'includes/enc.php';
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$ai=$_SESSION['id'];
// code for change password
if(isset($_POST['changepwd']))
{
	$op=encrypt($_POST['oldpassword']);
	$np=encrypt($_POST['newpassword']);
	$udate=date('d-m-Y h:i:s', time());
	$sql="SELECT password FROM userregistration where password=?";
	$chngpwd = $mysqli->prepare($sql);
	$chngpwd->bind_param('s',$op);
	$chngpwd->execute();
	$chngpwd->store_result(); 
    $row_cnt=$chngpwd->num_rows;;
	if($row_cnt>0){
		$con="update userregistration set password=?,passUdateDate=?  where id=?";
		$chngpwd1 = $mysqli->prepare($con);
		$chngpwd1->bind_param('ssi',$np,$udate,$ai);
		$chngpwd1->execute();
		$_SESSION['msg']="Password Changed Successfully !!";
	}
	else{
		$_SESSION['msg']="Old Password not match !!";
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
	<title>Change Password</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript">
function valid()
{

if(document.changepwd.newpassword.value!= document.changepwd.cpassword.value)
{
alert("Password and Re-Type Password Field do not match  !!");
document.changepwd.cpassword.focus();
return false;
}
return true;
}
</script>

</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Change Password </h2>
	
						<div class="row">
	
								<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<?php $result ="SELECT passUdateDate FROM userregistration WHERE id=?";
										$stmt = $mysqli->prepare($result);
										$stmt->bind_param('i',$ai);
										$stmt->execute();
										$stmt -> bind_result($result);
										$stmt -> fetch(); 
										?>Last Updation Date:&nbsp;<?php echo $result; ?> </div>
									<div class="panel-body">
										<form method="post" class="form-horizontal" name="changepwd" id="change-pwd" onSubmit="return valid();">
											<?php if(isset($_POST['changepwd']))
										{ ?>
										<p style="color: red"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg']=""); ?></p>
                                            <?php } ?>
											<div class="hr-dashed"></div>
											
												<div class="form-group">
													<label class="col-sm-2 control-label">Old Password<span class="text-danger">*</span> : </label>
													<div class="col-sm-8 passwordInputGrp">
														<input type="password" name="oldpassword" id="oldpassword" minlength="8" userId="<?php echo $ai;?>" onBlur="checkpass()"
															title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
															class="form-control" required="required">
														<div class="input-group-append passwordInputEye">
															<button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('oldpassword', this)">
																<i class="fa fa-eye"></i>
															</button>
														</div>
														<span id="password-availability-status" class="help-block m-b-none" style="font-size:12px;"></span> 
													</div>
												</div>

												<div class="form-group">
														<label class="col-sm-2 control-label">Password<span class="text-danger">*</span> : </label>
														<div class="col-sm-8 passwordInputGrp">
															<input type="password" name="newpassword" id="newpassword" minlength="8"
																title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
																class="form-control" required="required">
															<div class="input-group-append passwordInputEye">
																<button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('newpassword', this)">
																	<i class="fa fa-eye"></i>
																</button>
															</div>
														</div>
													</div>

													<!-- Confirm Password Field -->
													<div class="form-group">
														<label class="col-sm-2 control-label">Confirm Password<span class="text-danger">*</span> : </label>
														<div class="col-sm-8 passwordInputGrp">
															<input type="password" name="cpassword" id="cpassword" class="form-control" required="required">
															<div class="input-group-append passwordInputEye">
																<button class="btn btn-outline-secondary" type="button" id="toggleCPassword" onclick="togglePasswordVisibility('cpassword', this)">
																	<i class="fa fa-eye"></i>
																</button>
															</div>
														</div>
													</div>



												<div class="col-sm-6 col-sm-offset-4">
													<button class="btn btn-default" type="reset">Cancel</button>
													<input type="submit" name="changepwd" Value="Change Password" class="btn btn-primary">
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
function checkpass() {
$("#loaderIcon").show();

const userId = $("#oldpassword").attr("userId");
console.log(userId);
jQuery.ajax({
url: "check_availability.php",
data:{
	oldpassword: $("#oldpassword").val(),
	userid: userId
},
// 'oldpassword='+$("#oldpassword").val(),
type: "POST",
success:function(data){
$("#password-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

	// function togglePasswordVisibility(inputId, button) {
    //     const inputField = document.getElementById(inputId);
    //     const icon = button.querySelector('i');
    //     if (inputField.type === 'password') {
    //         inputField.type = 'text';
    //         icon.classList.remove('fa-eye');
    //         icon.classList.add('fa-eye-slash');
    //     } else {
    //         inputField.type = 'password';
    //         icon.classList.remove('fa-eye-slash');
    //         icon.classList.add('fa-eye');
    //     }
	// }


</script>
</body>

</html>