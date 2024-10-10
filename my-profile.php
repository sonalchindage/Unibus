<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$aid=$_SESSION['id'];
if(isset($_POST['update']))
{

$fname=trim($_POST['fname']);
$mname=trim($_POST['mname']);
$lname=trim($_POST['lname']);
$gender=$_POST['gender'];
$_SESSION['gender'] = $gender;
$contactno=$_POST['contact'];
$udate = date('d-m-Y h:i:s', time());
// $email = $_SESSION != $_POST['email']?$ ;
$email = $_POST['email'];
$query="update  userRegistration set firstName=?,middleName=?,lastName=?,gender=?,contactNo=?,updationDate=? where id=?";
$stmt = $mysqli->prepare($query);
$rc=$stmt->bind_param('ssssisi',$fname,$mname,$lname,$gender,$contactno,$udate,$aid);
if($stmt->execute()){

echo"<script>alert('Profile updated Succssfully');</script>";
}
$stmt->close();
// if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
// 	$flag = false;
// 	if($email !== $_SESSION['login']){
// 		$result ="SELECT count(*) FROM userRegistration WHERE email=?";
// 		$checkstmt = $mysqli->prepare($result);
// 		$checkstmt->bind_param('s',$email);
// 		$checkstmt->execute();
// 		$checkstmt->bind_result($count);
// 		$checkstmt->fetch();
// 		$checkstmt->close();
// 		if($count>0){
// 			$flag = true;
// 		}
// 	}
// 	if($flag){
		
// 	}
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
	<title>Profile Updation</title>
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
	<?php	
$aid=$_SESSION['id'];
$udate = date('d-m-Y h:i:s', time());
	$ret="select * from userregistration where id=?";
		$stmt= $mysqli->prepare($ret) ;
	 $stmt->bind_param('i',$aid);
	 $stmt->execute() ;//ok
	 $res=$stmt->get_result();
	 //$cnt=1;
	   while($row=$res->fetch_object())
	  {
	  	?>	
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-title"><?php echo $row->firstName;?>'s&nbsp;Profile </h2>

						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-heading">

Last Updation date : &nbsp; <?php echo $row->updationDate;?> 
</div>
									

<div class="panel-body">
<form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">
								
								

<div class="form-group">
<label class="col-sm-2 control-label"> Registration No<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<input type="text" name="regno" id="regno"  class="form-control" required="required" value="<?php echo $row->userPrn;?>" readonly="true">
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">First Name<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<input type="text" name="fname" id="fname" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" 
title="Please enter a valid first name without spaces" class="form-control" value="<?php echo $row->firstName;?>"   required="required" >
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Middle Name<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<input type="text" name="mname" id="mname" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" 
title="Please enter a valid middle name without spaces" class="form-control" value="<?php echo $row->middleName;?>"  >
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Last Name<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<input type="text" name="lname" id="lname" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)" 
title="Please enter a valid last name without spaces" class="form-control" value="<?php echo $row->lastName;?>" required="required">
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Gender<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<select name="gender" class="form-control" required="required">
<option value="male" <?php echo $row->gender == "male" ? "selected" : "";?>>Male</option>
<option value="female" <?php echo $row->gender == "female" ? "selected" : "";?>>Female</option>
<option value="others" <?php echo $row->gender == "others" ? "selected" : "";?>>Others</option>

</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Contact No<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<input type="text" name="contact" id="contact" pattern="[0-9]{10}" class="form-control" minlength="10" maxlength="10" value="<?php echo $row->contactNo;?>" required="required">
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">Email id<span class="text-danger">*</span> : </label>
<div class="col-sm-8">
<input type="email" id="email" name="email" class="form-control" 
               title="Please enter a valid Gmail address (e.g., user@gmail.com)" 
			   pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
			   value="<?php echo $row->email;?>"
               readonly>
<span id="user-availability-status" style="font-size:12px;"></span>
</div>
</div>
<?php } ?>

						



<div class="col-sm-6 col-sm-offset-4">

<input type="submit" name="update" Value="Update Profile" class="btn btn-primary">
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
</script>
	<script>
function checkAvailability(val) {
	const session = val;
const email = $('#email').val();
console.log(email);
console.log(session);
if(email !== session){
	$("#loaderIcon").show();
	jQuery.ajax({
	url: "check_availability.php",
	data:'emailid='+email,
	type: "POST",
	success:function(data){
	$("#user-availability-status").html(data);
	$("#loaderIcon").hide();
	},
	error:function (){}
	});
	}
}
</script>

</html>