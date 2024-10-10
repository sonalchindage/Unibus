<?php
session_start();
include('includes/config.php');
if(isset($_POST['login']))
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	$stmt=$mysqli->prepare("SELECT username,email,password,adminid,clgName   FROM admin WHERE (userName=?|| email=?) and password=? ");
	$stmt->bind_param('sss',$username,$username,$password);
	$stmt->execute();
	$stmt -> bind_result($username,$email,$password,$adminid, $clgName );
	$rs=$stmt->fetch();
	$stmt->close();
	$_SESSION['adminId']=$adminid ;
	$_SESSION['clgName']=$clgName ;
	// echo $_SESSION['adminId'];
	// echo $_SESSION['clgName'];
	// $uip=$_SERVER['REMOTE_ADDR'];
	$uip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	// $ldate=date('d/m/Y h:i:s', time());
	if($rs)
	{
		//  $insert="INSERT into adminlog(adminid,ip)VALUES($id,$uip)";
		// $stmtins = $mysqli->prepare($insert);
		// $stmtins->bind_param('sH',$id,$uip);
		// $res=$stmtins->execute();
		header("location:dashboard.php");
	}

	else
	{
		echo "<script>alert('Invalid Username/Email or password');</script>";
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

	<title>Admin login</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
	
	<div class="login-page bk-img" style="background-image: url(img/login-bg.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3" style="margin-top:4%">
						<h1 class="text-center text-bold text-light mt-4x">UniBus Management System</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
							
								<form action="" class="mt" method="post">
									<label for="" class="text-uppercase text-sm">Your Username or Email<span style="color: red;">(Admin)</span></label>
									<input type="text" placeholder="Username" name="username" class="form-control mb">
									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Password" name="password" class="form-control mb">
									

									<input type="submit" name="login" class="btn btn-primary btn-block" value="login" >
								</form>
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
</html>