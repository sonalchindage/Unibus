<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
check_status();
$total_paid_amount = 0;
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
	<title>Bus Details</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">

<script language="javascript" type="text/javascript">
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
 if(popUpWin)
{
if(!popUpWin.closed) popUpWin.close();
}
popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+510+',height='+430+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

</script>

</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
			<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row" id="print">


					<div class="col-md-12">
						<h2 class="page-title" style="margin-top:3%">Bus Details</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Bus Details</div>
							<div class="panel-body">
			<table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">
									
						 <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>			
									<tbody>
<?php	
$aid=$_SESSION['login'];
	$ret="select * from registration where (emailid=? || userPrn	=?)";
$stmt= $mysqli->prepare($ret) ;
$stmt->bind_param('ss',$aid,$aid);
$stmt->execute() ;
$res=$stmt->get_result();
$cnt=1;
while($row=$res->fetch_object())
	  {
	  	?>
<tr>
<td colspan="6" style="color:red"><h4>Personal Information</h4></td>
</tr>

<tr>
	<td><b>Reg No.(User PRN) :</b></td>
	<td><?php echo $row->userPrn;?></td>
	<td><b>Full Name :</b></td>
	<td><?php echo $row->firstName;?> <?php echo $row->middleName;?> <?php echo $row->lastName;?></td>
	<td><b>Email :</b></td>
	<td><?php echo $row->emailid;?></td>
</tr>


<tr>
<td><b>Contact No. :</b></td>
<td><?php echo $row->contactno;?></td>
<td><b>Gender :</b></td>
<td><?php echo $row->gender;?></td>
<td><b>Course :</b></td>
<td><?php echo $row->course;?></td>
</tr>


<tr>
<td><b>Emergency Contact No. :</b></td>
<td><?php echo $row->egycontactno;?></td>
<td><b>Guardian Name :</b></td>
<td><?php echo $row->guardianName;?></td>
<td><b>Guardian Relation :</b></td>
<td><?php echo $row->guardianRelation;?></td>
</tr>

<tr>
<td><b>Guardian Contact No. :</b></td>
<td colspan="6"><?php echo $row->guardianContactno;?></td>
</tr>

<tr>
<td colspan="6" style="color:blue"><h4>Addresses</h4></td>
</tr>
<tr>
<td><b>Current Address</b></td>
<td colspan="2">
<?php echo $row->corresAddress;?><br />
<?php echo $row->corresCIty;?>, <?php echo $row->corresPincode;?><br />
<?php echo $row->corresState;?>


</td>
<td><b>Permanent Address</b></td>
<td colspan="2">
<?php echo $row->pmntAddress;?><br />
<?php echo $row->pmntCity;?>, <?php echo $row->pmntPincode;?><br />
<?php echo $row->pmnatetState;?>	

</td>
</tr>

<tr>
<td colspan="6" style="text-align:center; color:blue"><h3>Bus Related Information</h3></td>
</tr>
<tr>
	<th>Registration No. (User PRN) :</th>
<td><?php echo $row->userPrn;?></td>
<th>Apply Date :</th>
<td colspan="3"><?php echo $row->postingDate;?></td>
</tr>

<?php
// SELECT `reportID`, `stayFrom`, `class`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `roomFeesphy`, `academicacademicYear`, `paymentStatus`, `status`, `skimAmount`, `comment`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`, `statusUpdationDate` FROM `report` WHERE 1
$userPrn = $row->userPrn;
$ret="SELECT * FROM report WHERE userPrn=?";
$stmt= $mysqli->prepare($ret) ;
$stmt->bind_param('s',$userPrn);
$stmt->execute() ;//ok
$res=$stmt->get_result();


while($prow=$res->fetch_object()){
	$total_paid_amount += $prow->reportTotalAmount;
?>

<tr>
<td colspan="6" style="text-align:center; color:blue"><?php echo $prow->academicYear;?></td>
</tr>

<tr>
<td><b>Institute:</b></td>
<td><?php echo $prow->clgName;?></td>
<td><b>Hostel Name :</b></td>
<td><?php echo $prow->hostelName;?></td>
<td><b>Class :</b></td>
<td ><?php echo $prow->class;?></td>
</tr>

<tr>
<td><b>Academic Year:</b></td>
<td><?php echo $prow->academicYear;?></td>
<td><b>Room no :</b></td>
<td><?php echo $prow->roomno;?></td>
<td><b>Stay From :</b></td>
<td ><?php echo $prow->stayFrom;?></td>
</tr>

<tr>
<td><b>Paid Status:</b></td>
<td><?php echo $prow->paymentStatus;?></td>
<td><b>Fees PY :</b></td>
<td><?php echo $prow->roomFeesphy;?></td>
<td><b>Remaining Amount :</b></td>
<td><?php echo $prow->remainingAmount;?></td>

</tr>

<tr><th>Annual Fee Settlement:</th>
<td><?php echo $prow->reportTotalAmount;?></td>
<th>Status:</th>
<td colspan="3"><?php echo $prow->status;?></td>
</tr>


<?php
}
$cnt=$cnt+1;
} ?>
<tr>
<th>Total Recieved Fee :</th>
<th colspan="5"><?php echo $total_paid_amount;?></th>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

	<!-- Loading Scripts -->
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
$(function () {
$("[data-toggle=tooltip]").tooltip();
    });
function CallPrint(strid) {
var prtContent = document.getElementById("print");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
}
</script>
</body>

</html>
