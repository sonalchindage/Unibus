<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
check_status();

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
	<title>Payment Status</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
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
					<div class="col-md-12">
						<h2 class="page-title" >Student Report Status</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Report Details</div>
							<div class="panel-body">
                            <span style="float:left" class="print-hide"><i class="fa fa-print fa-2x" aria-hidden="true" onclick="CallPrint()" style="cursor:pointer" title="Print the Report"></i></span>
							<div id="print-header" style="display:none;">
								<h2>Payment Record</h2>
							</div>
                            <div class="row" id="print-content">
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
                                            <th>Sno.</th>
											<th>User PRN</th>
											<th>Student</th>
											<th>Institute</th>
											<th>Class</th>
											<th>Academic year</th>
											<th>Hostel Fee</th>
											<th>Remaining Amount</th>
											<th>Paid Amount</th>
											<th>Paid status</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
                                    <?php	
                                    $userPrn = $_SESSION['userPrn'];
                                    $ret="SELECT * from report where userPrn=? ";
                                    $stmt= $mysqli->prepare($ret) ;
                                    $stmt->bind_param('s',$userPrn);
                                    $stmt->execute() ;//ok
                                    $res=$stmt->get_result();
                                    $cnt=1;
                                    while($row=$res->fetch_object())
                                        {
// SELECT `reportID`, `stayFrom`, `receiptTokenId`, `reportTotalAmount`, `receiptFile`, `class`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `roomFeesphy`, `paymentType`, `academicYear`, `paymentStatus`, `status`, `skimAmount`, `comment`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`, `statusUpdationDate` FROM `report` WHERE 1
                                            ?>
                                        <tr>
                                            <td><?php echo $cnt;?></td>
											<td><?php echo $row->userPrn;?></td>
                                            <td><?php echo $row->fullName;?></td>
                                            <td><?php echo $row->clgName;?></td>
											<td><?php echo $row->class;?></td>
                                            <td><?php echo $row->academicYear;?></td>
                                            <td><?php echo $row->roomFeesphy;?></td>
                                            <td><?php echo $row->remainingAmount;?></td>
                                            <td><?php echo $row->reportTotalAmount;?></td>
                                            <td><?php echo $row->paymentStatus;?></td>
                                            <td><?php echo $row->status;?></td>
                                            <td>
                                                <a href="payment-details.php?paymentView=<?php echo $row->reportID;?>" title="View Full Details"><i class="fa fa-desktop"></i></a>&nbsp;&nbsp;
                                            </td>
										</tr>
									<?php
                                    $cnt=$cnt+1;
									 } ?>
										
									</tbody>
								</table>
								</div>
							</div>
						</div>

					
					</div>
				</>

			

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
        
        function CallPrint() {
            var printContent = document.getElementById("print-content").innerHTML;
            var printHeader = document.getElementById("print-header").innerHTML;
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write('<html><head><title>Print</title>');
            WinPrint.document.write('<link rel="stylesheet" href="css/bootstrap.min.css">');
            WinPrint.document.write('</head><body>');
            WinPrint.document.write(printHeader);
            WinPrint.document.write(printContent);
            WinPrint.document.write('</body></html>');
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        }
	</script>

</body>

</html>
