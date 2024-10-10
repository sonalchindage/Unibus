<?php
session_start();
include('includes/config.php');
include('../includes/enc.php');
include('includes/checklogin.php');
check_login();

if(isset($_GET['del']))
{
	$id=intval($_GET['del']);
	$adn="delete from transactionhistory where transactionId=?";
    $stmt= $mysqli->prepare($adn);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt->close();	   
}

if (isset($_GET['status'])) {
    $transactionId = $_GET['status'];
    $userPrn = $_GET['userPrn'];
    $year = $_GET['year'];
    // $timeStamp = $_GET['timeStamp'];

    // echo $transactionId." ".$userPrn." ".$year;
    // print_r($transactionId);

    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare the SQL query to check if the user exists
    $checkQuery = "SELECT * FROM transactionhistory WHERE transactionId=? AND userPrn=? AND academicYear=?";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param('iss', $transactionId, $userPrn, $year);
    $checkStmt->execute();
    $res = $checkStmt->get_result();

    // Check if the user transaction exists
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $statusT = $row['status'];
        $payType = $row['payType'];
        $paymentDate = $row['paymentDate'];
        $receiptTokenId = $row['receiptTokenId'];
        $receiptFile = $row['receiptFile'];
        $emailid = $row['emailid'];
        $contactno = $row['contactno'];
        $paidAmountT = $row['paidAmount'];

        //Check user payment exist or not
        $checkUserQuery = "SELECT * FROM payments WHERE userPrn=? and year=?";
        $userStmt= $mysqli->prepare($checkUserQuery);
        $userStmt->bind_param('ss',$userPrn, $year);
        $userStmt->execute();
        $userRes = $userStmt->get_result();
        if($userRes->num_rows > 0){
            $userRow = $userRes->fetch_assoc();
            // Toggle the statusT
            $totalPaidAmount = intval($userRow['paidAmount']);
            // $status = $userRow['statusV'];
            $paidStatus = $userRow['paidStatus'];
            $feespm = $userRow['feespm'];

            if ($statusT === "pending") {
                $totalPaidAmount = $totalPaidAmount + $paidAmountT;
                 $statusT = "verified";
            } else {
                $totalPaidAmount = $totalPaidAmount - $paidAmountT;
                $statusT = "pending";
            }

            if($totalPaidAmount <= 0){
                $paymentStatus = "Not Paid";
            }elseif ($totalPaidAmount < $roomFeesphy) {
                $paymentStatus = "Partially Paid";
            }elseif ($totalPaidAmount == $roomFeesphy) {
                $paymentStatus = "Full Paid";
            }elseif ($totalPaidAmount > $roomFeesphy) {
                $paymentStatus = "Over Paid";
            }

            if($totalPaidAmount > $feespm){
                echo "<script>alert('The Student is overpaid OR Check again any paid amount is incorrect AND Update the transaction amount');</script>";
             }else{

                $userQuery = "UPDATE report SET  reportTotalAmount=?, paymentStatus=?, remainingAmount=? WHERE userPrn=? AND academicYear=?";
                $userStmt = $mysqli->prepare($userQuery);
                $userStmt->bind_param('dsdss',$totalPaidAmount, $paymentStatus, $remainingAmount, $userPrn, $academicYear);
                if ($userStmt->execute()) {
                    echo "<script>alert('Total payment for academicYear " . $academicYear . " is " . $paymentStatus . " of student RegNo." . $userPrn . " successfully');</script>";
                }
                $updateQuery = "UPDATE transactionhistory SET  status=?, comment=?, paymentDateCheck=?, receiptTokenidCheck=?, paidAmountCheck=?, receiptFileCheck=?, alluserDetailsCheck=?, payTypeCheck=?, verifiedBy=?, statusUpdationDate=? WHERE transactionId=?";
                $updateStmt = $mysqli->prepare($updateQuery);
                $updateStmt->bind_param('ssiiiiiiisi', $status, $remark, $paymentDateCheck, $receiptTokenidCheck, $paidAmountCheck, $receiptFileCheck, $alluserDetailsCheck, $payTypeCheck, $id, $statusUpdationDate, $transactionId);
                if ($updateStmt->execute()) {
                    echo "<script>alert('Receipt " . $status . " successfully');</script>";
                }

                $userStmt->close();
                $updateStmt->close();

             }

        }

    } else {
        // If user does not exist, return an error message
        echo "<script>alert(' Recipt not exist ');</script>";
    }

    // Close the initial statement and the connection
    $checkStmt->close();
    // $mysqli->close();
}

$from_date = '';
$to_date = '';
$status_filter = 'All';

if (isset($_POST['filter'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $status_filter = $_POST['status_filter'];
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
	<title>Transaction History</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @media print {
            .action-column {
                display: none;
            }
            .print-hide {
                display: none;
            }
            a {
                color: black;
                text-decoration: none;
            }
            a::after {
                content: none !important;
            }
        }
    </style>

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

        function CallPrint() {
            const hideColumn = document.querySelector('.action-column');
			hideColumn.style.display = 'none';
            var printContent = document.getElementById("print-content").innerHTML;
            var printHeader = document.getElementById("print-header").innerHTML;
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write('<html><head><title>Print</title>');
            WinPrint.document.write('<link rel="stylesheet" href="css/bootstrap.min.css">');
            WinPrint.document.write('<style>@media print { .action-column { display: none; } a { color: black; text-decoration: none; } a::after { content: none !important; } }</style>');
            WinPrint.document.write('</head><body>');
            WinPrint.document.write(printHeader);
            WinPrint.document.write(printContent);
            WinPrint.document.write('</body></html>');
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            hideColumn.style.display = 'table-cell';
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
						<h2 class="page-title">Transaction History Management</h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Room Details</div>
							<div class="panel-body">
                                <div class="action_file">
                                    <span style="float:left" class="print-hide"><i class="fa fa-print action_icon" aria-hidden="true" onclick="CallPrint()" style="cursor:pointer" title="Print the Report"></i></span>
                                    <span style="float:left" class="print-hide"><i class="fa-solid fa-file-arrow-down action_icon" onclick="exportExcel()" aria-hidden="true"  style="cursor:pointer" title="Download the Report"></i></span>
                                </div> 
                            <div id="print-header" style="display:none;">
                                <h2>Transaction History</h2>
                                </div>
                                <form style="display:flex; justify-content:center;" method="post" action="">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>From Date</label>
                                            <input type="date" name="from_date" value="<?php echo $from_date; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label>To Date</label>
                                            <input type="date" name="to_date" value="<?php echo $to_date; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Status</label>
                                            <select name="status_filter" class="form-control">
                                                <option value="All" <?php echo ($status_filter == 'All') ? 'selected' : ''; ?>>All</option>
                                                <option value="pending" <?php echo ($status_filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="verified" <?php echo ($status_filter == 'verified') ? 'selected' : ''; ?>>Verified</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 25px;">
                                            <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
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
											<th class="action-column">Action</th>
										</tr>
									</thead>
									
									<tbody>
                                    <?php	
                                    // $aid=$_SESSION['id'];
                                    // $ret="select * from transactionhistory";
                                    $query = "SELECT * FROM transactionhistory WHERE 1";
                                    $params = array();
                                    $types = '';
        
                                    if (!empty($from_date) && !empty($to_date)) {
                                        $query .= " AND paymentDate BETWEEN ? AND ?";
                                        $params[] = $from_date;
                                        $params[] = $to_date;
                                        $types .= 'ss';
                                    }
        
                                    if ($status_filter != 'All') {
                                        $query .= " AND status = ?";
                                        $params[] = $status_filter;
                                        $types .= 's';
                                    }
        
                                    $stmt = $mysqli->prepare($query);
                                    if (!empty($types)) {
                                        $stmt->bind_param($types, ...$params);
                                    }
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    $total_paid_amount = 0;
        
                                    while ($row = $res->fetch_object()) {
                                        $total_paid_amount += $row->paidAmount;                                
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
                                                    <a href="../uploads/<?php echo $cdoc;?>" target="blank">File</a>
                                                <?php	endif;
                                                ?>
                                            </td>
                                            <td><?php echo $row->paidStatus;?></td>
                                            <td>
                                                <b style="color:<?php echo ($row->status === 'verified') ? 'rgb(106, 221, 106)' : 'tomato'; ?>;">
                                                <?php echo $row->status;?>
                                                </b>
                                            </td>
                                            <td class="action-column">
                                                <a href="transaction-details.php?transactionView=<?php echo encrypt($row->transactionId);?>" title="View Full Details"><i class="fa fa-desktop"></i></a>&nbsp;&nbsp;
                                                <!-- <a href="transaction-history.php?del=<?php //echo $row->transactionId;?>" title="Delete Record" onclick="return confirm('Do you want to delete');"><i class="fa fa-close"></i></a> -->
                                            </td>
										</tr>
									<?php
                                    $cnt=$cnt+1;
									 } ?>
										
									</tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7" style="text-align:right"><b>Total Paid Amount:</b></td>
                                            <td colspan="5"><b><?php echo $total_paid_amount; ?></b></td>
                                        </tr>
                                    </tfoot>
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
    <script type="" src="js/table2excel.js"></script>
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
	
	function printTable() {
        // Hide the action column
        $('.action-column').hide();

        // Print the page
        window.print();

        // Show the action column again
        $('.action-column').show();
    }
    </script>
</body>

</html>
