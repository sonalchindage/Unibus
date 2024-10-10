<?php session_start();
error_reporting(0);
include('includes/config.php');
include('includes/checklogin.php');
check_login();
//code for add courses
if(isset($_POST['submit']))
{
	$hostelName = $_POST['hostelName'];
	$gender=$_POST['gender'];

	$sql="SELECT hostelName FROM hostel where hostelName=?";
	$stmt1 = $mysqli->prepare($sql);
	$stmt1->bind_param('s',$hostelName);
	$stmt1->execute();
	$stmt1->store_result(); 
	$row_cnt=$stmt1->num_rows;;
	$stmt1->close();
	if($row_cnt>0){
		echo"<script>alert('Hostel alreadt exist');</script>";
	}else{
		$rooms = 0;
		$active = 1;
		$capacity = 0;
// INSERT INTO `hostel`(`id`, `hostelName`, `rooms`, `gender`, `capacity`, `active`) VALUES (?,?,?,?,?,?)
		$query="INSERT INTO `hostel`( `hostelName`, `rooms`, `gender`, `capacity`, `active`) VALUES (?,?,?,?,?)";
		$stmt = $mysqli->prepare($query);
		$rc=$stmt->bind_param('sisii',$hostelName,$rooms, $gender, $capacity, $active);
		if($stmt->execute()){
			echo"<script>alert('Hostel has been added successfully');</script>";
		}
		
		$stmt->close();
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
	<title>Create Room</title>
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
</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Add New Hostel </h2>
	
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Add New Hostel</div>
									<div class="panel-body">
									<?php if(isset($_POST['submit']))
									{ ?>
									<p style="color: red"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg']=""); ?></p>
									<?php } ?>
										<form method="post" class="form-horizontal">

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Hostel Name</label>
                                            <div class="col-sm-8">
                                            <input type="text" class="form-control" name="hostelName" id="hostelName" value="" required="required">
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Gender </label>
                                            <div class="col-sm-8">
                                            <select name="gender" class="form-control" required="required">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="others">Others</option>
                                            </select>
                                            </div>
                                            </div>

                                            <div class="col-sm-8 col-sm-offset-2">
                                            <input class="btn btn-primary" type="submit" name="submit" value="Create Hostel ">
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

</body>

</html>