<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
check_status();

// if(isset($_GET['del']))
// {
// 	$id=intval($_GET['del']);
// 	$adn="delete from transactionhistory where transactionId=?";
//     $stmt= $mysqli->prepare($adn);
//     $stmt->bind_param('i',$id);
//     $stmt->execute();
//     $stmt->close();	   
// }

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
	<title>Transaction Record</title>
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
						<h2 class="page-title" >Manage Transaction Record</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Transaction Details</div>
							<div class="panel-body">
                                <span style="float:left" class="print-hide"><i class="fa fa-print fa-2x" aria-hidden="true" onclick="CallPrint()" style="cursor:pointer" title="Print the Report"></i></span>
                                <div id="print-header" style="display:none;">
                                    <h2>List of Registered Students</h2>
                                </div>
                                <div class="row" id="print-content">
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <!-- <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>				 -->
										<thead>
											<tr>
												<th>Sno.</th>
												<th>Student Name</th>
												<th>User PRN</th>
												<th>Pay type</th>
												<th>For year</th>
												<th>Payment date</th>
												<th>Payment token</th>
												<th>Paid amount</th>
												<th>Reciept file</th>
												<th>Paid status</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
									<tbody>
                                    <?php	
                                    $regNo = $_SESSION['userPrn'];
                                    $ret="select * from transactionhistory where userPrn = ?";
                                    $stmt= $mysqli->prepare($ret) ;
                                    $stmt->bind_param('s',$regNo);
                                    $stmt->execute() ;//ok
                                    $res=$stmt->get_result();
                                    $cnt=1;
                                    while($row=$res->fetch_object())
                                        {
// SELECT `transactionId`, `timeStamp`, `paymentDate`, `receiptTokenId`, `paidAmount`, `receiptFile`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `feespm`, `payType`, `academicYear`, `paidStatus`, `status`, `skimAmount`, `comment`, `paymentDateCheck`, `receiptTokenidCheck`, `paidAmountCheck`, `receiptFileCheck`, `alluserDetailsCheck`, `payTypeCheck`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `statusUpdationDate`, `hostelName`, `clgName` FROM `transactionhistory` WHERE 1
                                            ?>
                                        <tr>
                                            <td><?php echo $cnt;?></td>
                                            <td><?php echo $row->fullName;?></td>
                                            <td><?php echo $row->userPrn;?></td>
                                            <td><?php echo $row->payType;?></td>
                                            <td><?php echo $row->academicYear;?></td>
                                            <td><?php echo $row->paymentDate;?></td>
                                            <td><?php echo $row->receiptTokenId;?></td>
                                            <td><?php echo $row->paidAmount;?></td>
                                            <td>
                                                <?php $cdoc=$row->receiptFile;
                                                    if($cdoc==''):
                                                        echo "NA";
                                                    else: ?>
                                                    <a href="./uploads/<?php echo $cdoc;?>" target="blank">File</a>
                                                <?php	endif;
                                                ?>
                                            </td>
                                            <td><?php echo $row->paidStatus;?></td>
                                            <td>
                                                <span 
                                                    id="statusV"
                                                    style="background:<?php echo ($row->status === 'verified') ? 'rgb(106, 221, 106)' : 'tomato'; ?>"
                                                    >
                                                    <?php echo $row->status;?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="transaction-details.php?transactionView=<?php echo $row->transactionId;?>" title="View Full Details"><i class="fa fa-desktop"></i></a>&nbsp;&nbsp;
												<?php 
													if($row->status == 'pending'){
												?>
                                                <!-- <a href="transaction-history.php?del=<?php //echo $row->transactionId;?>" title="Delete Record" onclick="return confirm('Do you want to delete');"><i class="fa fa-close"></i></a> -->
												<?php 
													}
												?>
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
