<?php
session_start();
include('includes/config.php');
include('../includes/enc.php');
include('../includes/phpMailer/mail.php');
include('includes/checklogin.php');
check_login();
$total_paid_amount = 0;
$statusNew = '';
if(isset($_POST['submit']))
{
// Posted Values
$cid=decrypt($_GET['userPrn']);
// $academicYear=$_GET['academicYear'];
$cstatus=$_POST['cstatus'];
$remark=$_POST['remark'];
$id=$_SESSION['adminId'];

 // Prepare the SQL query to check if the user exists
//  SELECT `id`, `roomno`, `seater`, `feespm`, `roomId`, `stayfrom`, `academicYear`, `course`, `class`, `userPrn`, `firstName`, `middleName`, `lastName`, `gender`, `contactno`, `emailid`, `egycontactno`, `guardianName`, `guardianRelation`, `guardianContactno`, `corresAddress`, `corresCIty`, `corresState`, `corresPincode`, `pmntAddress`, `pmntCity`, `pmnatetState`, `pmntPincode`, `postingDate`, `updationDate`, `status`, `comment`, `verifiedBy`, `clgName`, `hostelName`, `statusUpdationDate` FROM `registration` WHERE 1
 $checkQuery = "SELECT * FROM registration WHERE userPrn=?";
 $checkStmt = $mysqli->prepare($checkQuery);
 $checkStmt->bind_param('s', $cid);
 $checkStmt->execute();
 $res = $checkStmt->get_result();
 // Check if the user transaction exists
 if ($res->num_rows > 0) {
	$row = $res->fetch_assoc();
	$academicYear = $row['academicYear'];
	$hostelName = $row['hostelName'];
	$roomno = $row['roomno'];
	$gender = $row['gender'];
	$seater = $row['seater'];

	$firstName = $row['firstName'];
	$lastName = $row['lastName'];
	$fullName = $firstName . " " . $lastName;
	$recipientName = $fullName;
	$recipientEmail = $row['emailid'];
	$hostelName = $row['hostelName'];
	$clgName = $row['clgName'];
	$stayfrom = $row['stayfrom'];

	$result = "SELECT count(*) FROM registration WHERE hostelName=? AND roomno=? AND gender=? AND status='verified'";
	// $result ="SELECT count(*) FROM registration WHERE roomno=? AND gender=?";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('sss',$hostelName,$roomno,$gender);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	// if($count > 0)
	// 	echo $count;
	// else
	// 	echo 0;
	if($count >= $seater){
		echo "<script>alert('No Seat Available for room ".$roomno."');</script>";
	}
	else{
		$query1="update registration set status=?,comment=?, verifiedBy=?  where userPrn=?";
		$stmt1 = $mysqli->prepare($query1);
		$stmt1->bind_param('ssis',$cstatus,$remark, $id,$cid);
		if($stmt1->execute()){

			$subject = "Hostel Admission Confirmation - Room $roomno";
			$body = "<p><strong>Subject:</strong> Hostel Admission Confirmation - Room $roomno</p>
					<p>Dear $fullName,</p>
					<p>We are pleased to confirm your admission to the hostel $hostelName at $clgName. You have been allotted Room No. <strong>$roomno</strong> as per your request.</p>
					<p>Please ensure that you complete the check-in formalities by <strong>$stayfrom</strong>.</p>
					<p>Welcome to your new home at $clgName!</p>
					<p>Best regards,<br>
					Stay Easy<br>
					Stay Safe Stay Comfortable<br>
					$clgName</p>";

			if($cstatus == 'leave'){
				$subject = "Hostel Leave Confirmation - $clgName";
				$body = "<p><strong>Subject:</strong> Hostel Leave Confirmation - $clgName</p>
						<p>Dear $fullName,</p>
						<p>This is to confirm that we have received your request to leave the hostel at $clgName. Your request has been processed.</p>
						<p>Please ensure that all personal belongings are removed and that your room is returned to its original condition by this date. Any outstanding dues should be cleared before you leave.</p>
						<p>We wish you all the best in your future endeavors. If you need any further assistance during this transition, do not hesitate to contact us.</p>
						<p>Best regards,<br>
						Stay Easy<br>
						Stay Safe Stay Comfortable<br>
						$clgName</p>";
			}
			
			$altBody = '';
			$bcc = "";
			$cc = "";
			
			if (newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)){
				unset($_POST);
				$_POST = array();
				echo "<script>alert('Application Is Updated');</script>";
			}
		}else{
			unset($_POST);
			$_POST = array();
			echo "<script>alert('Get Some Error In Update Application');</script>";
		}

		if($cstatus == "rejected"){
			$query2="DELETE from report where userPrn=? AND academicYear=?";
			$stmt2 = $mysqli->prepare($query2);
			$stmt2->bind_param('ss',$cid,$academicYear);
			if($stmt2->execute()){

				$subject = "Hostel Admission Status - $clgName";
				$body = "<p><strong>Subject:</strong> Hostel Admission Status - $clgName</p>
						<p>Dear $fullName,</p>
						<p>We regret to inform you that your application for hostel admission at $clgName has not been successful.</p>
						<p>Due to $remark, we are unable to accommodate your request at this time. We understand this may be disappointing and encourage you to explore alternative arrangements.</p>
						<p>If you have any questions or need further assistance, please feel free to contact us.</p>
						<p>We wish you the best in your academic endeavors at $clgName.</p>
						<p>Best regards,<br>
						Stay Easy<br>
						Stay Safe Stay Comfortable<br>
						$clgName</p>";
				$altBody = '';
				$bcc = "";
				$cc = "";
				
				if (newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)){
					unset($_POST);
					$_POST = array();
					echo "<script>alert('Application Is Rejected');</script>";
				}
			}else{
				unset($_POST);
				$_POST = array();
				echo "<script>alert('Get Some Error In Update Application');</script>";
			}	
		}
	}
 }
 unset($_POST);
 $_POST = array();
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
	<title>Room Details</title>
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
						<h2 class="page-title" style="margin-top:3%">Rooms Details</h2>
						<div class="panel panel-default">
							<div class="panel-heading">Student Details</div>
							<div class="panel-body">
			<table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">
									
						 <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>			
									<tbody>
<?php	
$aid=decrypt($_GET['userPrn']);
	$ret="select * from registration where (emailid=? || userPrn=?)";
$stmt= $mysqli->prepare($ret) ;
$stmt->bind_param('ss',$aid,$aid);
$stmt->execute() ;
$res=$stmt->get_result();
$cnt=1;
while($row=$res->fetch_object())
	  {
		$statusNew = $row->status;
	  	?>
<tr>
<td colspan="6" style="color:red"><h4>Personal Information</h4></td>
</tr>
<input type="hidden" id="status" value="<?php echo $row->status;?>">

<tr>
	<td><b>User PRN. :</b></td>
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
<td colspan="6" style="text-align:center; color:blue"><h3>Room Related Information</h3></td>
</tr>
<tr>
	<th>Registration Number :</th>
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
<th>Payment Status:</th>
<td colspan="3"><?php echo $prow->status;?></td>
</tr>

<tr>
<td colspan="6" style="text-align:center; color:blue"><?php echo "Transaction History of ".$prow->academicYear;?></td>
</tr>
<?php 
$userPrn = $prow->userPrn;
$ret="SELECT * FROM transactionhistory WHERE userPrn=? AND academicYear=?";
$stmtTransaction= $mysqli->prepare($ret) ;
$stmtTransaction->bind_param('ss',$userPrn,$prow->academicYear);
$stmtTransaction->execute() ;//ok
$tran=$stmtTransaction->get_result();

$tranCount = 1;
while($transaction=$tran->fetch_object()){
	?>
		<tr>
		<td><b><?php echo $tranCount.".";?> Pay type:</b></td>
		<td><?php echo $transaction->payType;?></td>
		<td><b>Payment date:</b></td>
		<td><?php echo $transaction->paymentDate;?></td>
		<td><b>Paid amount:</b></td>
		<td ><?php echo $transaction->paidAmount;?></td>
		</tr>

		<tr>
		<td><b>Payment token:</b></td>
		<td><?php echo $transaction->receiptTokenId;?></td>
		<td><b>Reciept file:</b></td>
		<td>
			<?php $cdoc=$transaction->receiptFile;
				if($cdoc==''):
					echo "NA";
				else: ?>
				<a href="../uploads/<?php echo $cdoc;?>" target="blank">File</a>
			<?php	endif;
			?>
		</td>
		<td><b>Status:</b></td>
		<td ><?php echo $transaction->status;?></td>
		</tr>
		
	<?php
	$tranCount++;
}
}
$cnt=$cnt+1;

?>
<tr>
<th>Total Recieved Fee :</th>
<th colspan="5"><?php echo $total_paid_amount;?></th>
</tr>
<?php 

if(isset($row->comment)){
?>
<tr>
<th>Remark :</th>
<th colspan="5"><?php echo $row->comment;?></th>
</tr>
<?php 
	}
if(isset($row->verifiedBy)){
	$stmtAdmin=$mysqli->prepare("SELECT username,email,clgName,adminName FROM admin WHERE adminid =?");
	$stmtAdmin->bind_param('i',$row->verifiedBy);
	$stmtAdmin->execute();
	$stmtAdmin -> bind_result($username,$email,$clgName,$adminName );
	$rs=$stmtAdmin->fetch();
	$stmtAdmin->close();
?>
<tr>
<td colspan="6" style="text-align:center; color:blue"><?php echo ucfirst($row->status);?> By</td>
</tr>
		<tr>
			<td><b>Username:</b></td>
			<td><?php echo $username;?></td>
			<td><b>User Email:</b></td>
			<td><?php echo $email;?></td>
			<td><b>User Full Name:</b></td>
			<td ><?php echo $adminName;?></td>
		</tr>
<?php
}
} ?>

</tbody>
</table>
<button type="button" class="btn btn-info subm" data-toggle="modal" data-target="#myModal">Authorize</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Take Action</h4>
		</div>
		<form method="post">
			<div class="modal-body">
				<p>
					<select name="cstatus" class="form-control" required>
						<option value="">Select Addmission Status</option>
					</select>
				</p>
				<p>
					<textarea name="remark" id="remark" placeholder="Remark or Message" rows="6" class="form-control limitedText" oninput="validateInput(event)" maxlength="200"></textarea>
					<span class="error text-danger"></span>
					<span class="text-muted charCount">200 characters remaining</span>
				</p>
				<p>
					<input type="submit" name="submit" value="Submit" class="btn btn-primary sub" disabled>
				</p>
			</div>
		</form>

		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
	const sub = document.querySelector('.subm');
	sub.style.display = 'none';
	var prtContent = document.getElementById("print");
	var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
	WinPrint.document.write(prtContent.innerHTML);
	WinPrint.document.close();
	WinPrint.focus();
	WinPrint.print();
	sub.style.display = 'table-cell';
}

document.addEventListener("DOMContentLoaded", function() {
	const status = document.getElementById("status").value;
	const select = document.querySelector("select[name='cstatus']");

	if (status === "verified") {
		select.innerHTML += '<option value="leave">Leave</option>';
	} else if(status === "pending") {
		select.innerHTML += '<option value="verified">Admission Confirm</option>';
		select.innerHTML += '<option value="rejected">Admission Rejected</option>';
	}
});
</script>
</body>

</html>
