<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
if(isset($_POST['submit']))
{
// Posted Values
$cstatus=$_POST['cstatus'];
$redproblem=$_POST['remark'];
$remainingAmountCheck=$_POST['remainingAmountCheck'];
// $redproblem=$_POST['remark'];
echo $redproblem;
echo $remainingAmountCheck;
echo $cstatus;
}
if (isset($_GET['transactionView']) && isset($_GET['userPrn']) && isset($_GET['year'])) {

    $transactionId = $_GET['transactionView'];
    $userPrn = $_GET['userPrn'];
    $year = $_GET['year'];
    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare the SQL query to check if the user exists
    $checkQuery = "SELECT * FROM transactionhistory WHERE transactionId=? AND userPrn=? AND year=?";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param('iis', $transactionId, $userPrn, $year);
    $checkStmt->execute();
    $res = $checkStmt->get_result();

    // Check if the user transaction exists
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $statusT = $row['statusV'];
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
        $userStmt->bind_param('is',$userPrn, $year);
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
                if($totalPaidAmount >= $feespm){
                    $paidStatus = "Over Paid";
                }elseif($totalPaidAmount === $feespm){
                    $paidStatus = "Full Paid";
                }
                $statusT = "verified";
                
            } else {
                $totalPaidAmount = $totalPaidAmount - $paidAmountT;
                if($totalPaidAmount < $feespm){
                    $paidStatus = "parsially paid";
                }
                $statusT = "pending";
            }
            // echo $totalPaidAmount;
            // echo $paidStatus;
            // echo $statusT;

            // Update the existing payment record
            $userQuery = "UPDATE payments SET paymentDate = ?, receiptTokenId = ?,paidAmount = ?, receiptFile = ?, emailid = ?, contactno = ?, payType = ?, paidStatus = ? WHERE userPrn = ? AND year = ?";
            $userStmt = $mysqli->prepare($userQuery);
            $userStmt->bind_param('ssdssissis', $paymentDate, $receiptTokenId, $totalPaidAmount, $receiptFile, $emailid, $contactno, $payType, $paidStatus, $userPrn, $year);
            if($userStmt-> execute()){
                echo "<script>alert(' Total payment for year ".$year." is ".$paidStatus." of student RegNo.".$userPrn." succesfully ');</script>";
            }
            // Prepare the SQL query to update the status in transaction hestory table
            $updateQuery = "UPDATE `transactionhistory` SET statusV=? WHERE transactionId=? AND userPrn=? AND year=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('siis', $statusT, $transactionId, $userPrn, $year);
            if($updateStmt->execute()){
                echo "<script>alert(' Recipt ".$statusT." succesfully ');</script>";
            }

            // Close the update statement
            $userStmt->close();
            $updateStmt->close();

        }

    } else {
        // If user does not exist, return an error message
        echo "<script>alert(' Recipt not exist ');</script>";
    }

    // Close the initial statement and the connection
    $checkStmt->close();
    // $mysqli->close();
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
	<title>Transaction Details</title>
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
                        <?php	

                            $cid=$_GET['transactionView'];
                            $ret="select * from transactionhistory where (transactionId =?)";
                            $stmt= $mysqli->prepare($ret) ;
                            $stmt->bind_param('i',$cid);
                            $stmt->execute() ;
                            $res=$stmt->get_result();
                            $cnt=1;
                            while($row=$res->fetch_object())
                                {
                            ?>
                            <input type="hidden" id="status" value="<?php echo $row->status;?>">

                            <div class="col-md-12">
                                <h2 class="page-title" style="margin-top:3%">#<?php echo $row->receiptTokenId;?> Details</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading">#<?php echo $row->receiptTokenId;?> Details</div>
                                    <div class="panel-body">
                                    <table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">
                                            
                                    <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>	
                                    <form action="" method="post">
                                        <tbody>
                                                <tr>
                                                <td colspan="6" style="text-align:center; color:blue"><h4>Transaction Realted Info</h4></td>
                                                </tr>

                                                <tr>
                                                    <th>Full name: </th>
                                                    <td><?php echo $row->fullName;?></td>
                                                    <th>Student regNo: </th>
                                                    <td><?php echo $row->userPrn;?></td>
                                                </tr>
                                                <tr>
                                                    <th>Email: </th>
                                                    <td><?php echo $row->emailid;?></td>
                                                    <th>Contact No: </th>
                                                    <td><?php echo $row->contactno;?></td>
                                                </tr>
                                                <tr>
                                                    <th>Staying in Room: </th>
                                                    <td><?php echo $row->roomno;?></td>
                                                    <th>Room Fee: </th>
                                                    <td><?php echo $row->feespm;?></td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Date: </th>
                                                    <td><?php echo $row->paymentDate;?></td>
                                                    <th>Receipt No: </th>
                                                    <td><?php echo $row->receiptTokenId;?></td>
                                                </tr>

                                                <tr>
                                                    <th>Paid Amount: </th>
                                                    <td><?php echo $row->paidAmount;?></td>
                                                    <th>For Year: </th>
                                                    <td><?php echo $row->academicYear;?></td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Type </th>
                                                    <td><?php echo $row->payType;?></td>
                                                    <th>File (if any)</th>
                                                    <td>
                                                        <?php $cdoc=$row->receiptFile;
                                                        if($cdoc==''):
                                                            echo "NA";
                                                        else: ?>
                                                        <a href="../uploads/<?php echo $cdoc;?>" target="blank">File</a>
                                                        <?php	endif;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Paid Status: </th>
                                                    <td><?php echo $row->paidStatus;?></td>
                                                    <th>Reciept Uploaded on: </th>
                                                    <td><?php echo $row->timeStamp;?></td>
                                                </tr>

                                                <tr>
                                                    <th>Payment Status </th>
                                                    <td  colspan="3">
                                                        <b style="color:<?php echo ($row->status === 'verified') ? 'rgb(106, 221, 106)' : 'tomato'; ?>;">
                                                            <?php echo $row->status;?>
                                                        </b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Remaining Amount Confirm </th>
                                                    <td  colspan="3">
                                                        <input type="radio" value="1" name="remainingAmountCheck"> Yes
                                                        <input type="radio" value="0" name="remainingAmountCheck" checked="checked"> No 
                                                    </td>
                                                </tr>       
                                            </tbody>
                                        </table>
                                        <div>
                                            <select name="cstatus" class="form-control" required>
                                                <option value="">Select Addmission Status</option>
                                            </select>
                                        </div>
                                        <div>
                                            <textarea name="remark" id="remark" placeholder="Remark or Message" rows="6" class="form-control limitedText" oninput="validateInput(event)" maxlength="200"></textarea>
                                            <span class="error text-danger"></span>
                                            <span class="text-muted charCount">200 characters remaining</span>
                                        </div>
                                        <input type="submit" name="submit" value="Submit" class="btn btn-primary sub">
                                        <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Authorize</button> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                            $cnt=$cnt+1;
                            } ?>
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

            document.addEventListener("DOMContentLoaded", function() {
                const status = document.getElementById("status").value;
                const select = document.querySelector("select[name='cstatus']");

                if(status === "pending") {
                    select.innerHTML += '<option value="verified">Transaction Verified</option>';
                    select.innerHTML += '<option value="rejected">Transaction Rejected</option>';
                }
            });
        </script>
    </body>

</html>
