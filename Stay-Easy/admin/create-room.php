<?php session_start();
error_reporting(0);
include('includes/config.php');
include('includes/checklogin.php');
check_login();
//code for add courses
if(isset($_POST['submit']))
{
	$seater=$_POST['seater'];
	$parts = explode(",", $_POST['hostelName']);
	$hostelName = $parts[0];

	$roomno=$_POST['rmno'];
	$fees=$_POST['fee'];
	$gender=$_POST['gender'];
	$clgName=$_SESSION['clgName'];
	// echo $clgName;

	$sql="SELECT room_no FROM rooms where clgName = ? and hostelName=? and room_no=? and gender=?";
	$stmt1 = $mysqli->prepare($sql);
	$stmt1->bind_param('ssss',$clgName,$hostelName,$roomno,$gender);
	$stmt1->execute();
	$stmt1->store_result(); 
	$row_cnt=$stmt1->num_rows;;
	$stmt1->close();
	if($row_cnt>0){
		echo"<script>alert('Room alreadt exist');</script>";
	}else{
		echo $gender;
		echo $fees;
		echo $clgName;
		echo $roomno;
		echo $seater;
		$occupiedSeats = 0;
		$halfyearlyAmount = 0;
// INSERT INTO `rooms`(`id`, `seater`, `room_no`, `yearlyFees`, `halfyearlyAmount`, `gender`, `posting_date`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]')
		$query="insert into  rooms (seater,room_no,yearlyFees,halfyearlyAmount,gender,clgName,hostelName,seatsAvaibility, occupiedSeats) values(?,?,?,?,?,?,?,?,?)";
		$stmt = $mysqli->prepare($query);
		$rc=$stmt->bind_param('isddsssii',$seater,$roomno,$fees,$halfyearlyAmount, $gender, $clgName,$hostelName, $seater, $occupiedSeats);
		if($stmt->execute()){
			echo"<script>alert('Room has been added successfully');</script>";
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
					
						<h2 class="page-title">Add New Room </h2>
	
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading"> Institute: <?php echo $_SESSION['clgName']; ?></div>
									<div class="panel-body">
									<?php if(isset($_POST['submit']))
									{ ?>
									<p style="color: red"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg']=""); ?></p>
									<?php } 
									?>
										<form method="post" class="form-horizontal">
											
											<div class="hr-dashed"></div>

												<div class="form-group">
												<label class="col-sm-2 control-label">Hostel Name<span class="text-danger">*</span> </label>
												<div class="col-sm-8">
												<select class="form-control" id="hostelName" name="hostelName" onchange="setGender(this.value)" required> 
												<option value="">Select Room</option>
												<?php
												$query = "SELECT * FROM hostel where active = 1";
												$stmt2 = $mysqli->prepare($query);
												$stmt2->execute();
												$res=$stmt2->get_result();
												while($row=$res->fetch_object())
												{?>
												<option value="<?php echo $row->hostelName.",".$row->gender?>" > <?php echo $row->clgName." ".$row->hostelName; ?> </option>
												<?php } ?>
												</select> 
												<span id="room-availability-status" style="font-size:12px;"></span>
												</div>
												</div>

												<!-- <input type="text" id="hostelName" name="hostelName" hidden> -->

												<div class="form-group">
												<label class="col-sm-2 control-label">Select Seater<span class="text-danger">*</span> </label>
												<div class="col-sm-8">
												<Select name="seater" class="form-control" required>
												<option value="">Select Seater</option>
												<option value="1">Single Seater</option>
												<option value="2">Two Seater</option>
												<option value="3">Three Seater</option>
												<option value="4">Four Seater</option>
												<option value="5">Five Seater</option>
												</Select>
												</div>
												</div>

												<div class="form-group">
												<label class="col-sm-2 control-label">Room No.<span class="text-danger">*</span></label>
												<div class="col-sm-8">
												<input type="text" class="form-control" name="rmno" id="rmno" value="" required="required">
												</div>
												</div>

												<div class="form-group">
												<label class="col-sm-2 control-label">Fee(Per Student)<span class="text-danger">*</span> </label>
												<div class="col-sm-8">
												<input type="number" pattern="[0-9]" class="form-control" min="1" name="fee" id="fee" value="" required="required">
												</div>
												</div>
<!--											<div class="form-group">
												<label class="col-sm-2 control-label">Half Year Fee </label>
												<div class="col-sm-8">
												<input type="number" pattern="[0-9]" class="form-control" min="1" name="fee" id="fee" value="" required="required">
												</div>
												</div> -->
												
												<div class="form-group">
												<label class="col-sm-2 control-label">Gender </label>
												<div class="col-sm-8">
												<input type="text" class="form-control" name="gender" id="gender" readonly required="required">
												</div>
												</div>

												<div class="col-sm-8 col-sm-offset-2">
												<input class="btn btn-primary" type="submit" name="submit" value="Create Room ">
												</div>
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
		
		function setGender(val){
			const gender = document.getElementById('gender');
			// const hostelName = document.getElementById('hostelName');
			let parts = val.split(",");
			let hostel = parts[0];
			let genderData = parts[1];

			gender.value = genderData;
			// hostelName.value = hostel;
		}
	</script>

</body>

</html>