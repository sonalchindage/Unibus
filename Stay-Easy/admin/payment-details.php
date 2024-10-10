<?php
session_start();
include('includes/config.php');
include('../includes/enc.php');
include('../includes/phpMailer/mail.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['refund'])) {

    // print_r($_POST);

    if($_POST['remainingAmountCheckRefund'] == 1){
        $reportID = intval(decrypt($_GET['reportId']));
        $id=$_SESSION['adminId'];

        $statusUpdationDate = date('Y-m-d H:i:s');
        $remark = $_POST['remarkRefund']; 
        $refund = $_POST['refundAmount'];
        $remainingAmountCheck = $_POST['remainingAmountCheckRefund'];

        // Prepare the SQL query to check if the user exists
        $checkQuery = "SELECT * FROM report WHERE reportID=?";
        $checkStmt = $mysqli->prepare($checkQuery);
        $checkStmt->bind_param('i', $reportID);
        $checkStmt->execute();
        $res = $checkStmt->get_result();
        // Check if the user transaction exists
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $statusT = $row['status'];
            $fullName = $row['fullName'];
            $emailid = $row['emailid'];
            $clgName = $row['clgName'];
            $reportTotalAmount = $row['reportTotalAmount'];
            $remainingAmount = $row['remainingAmount'];

            if($statusT == "pending"){
                $paymentStatus = "Refund";
                $statusT = "verified";

                if($reportTotalAmount < $refund){
                    echo "<script>alert(' Refund can not be greater than Total Paid Amount ');</script>";
                }else{
                    $reportTotalAmount = $reportTotalAmount - $refund;
                    $remainingAmount = $remainingAmount + $refund;
                    // Prepare the SQL query to update the status in transaction hestory table
                    // UPDATE `report` SET `reportID`='[value-1]',`stayFrom`='[value-2]',`roomId`='[value-3]',`reportTotalAmount`='[value-4]',`profile`='[value-5]',`class`='[value-6]',`fullName`='[value-7]',`userPrn`='[value-8]',`emailid`='[value-9]',`contactno`='[value-10]',`roomno`='[value-11]',`roomFeesphy`='[value-12]',`refund`='[value-13]',`academicYear`='[value-14]',`paymentStatus`='[value-15]',`status`='[value-16]',`skimAmount`='[value-17]',`comment`='[value-18]',`remainingAmountCheck`='[value-19]',`remainingAmount`='[value-20]',`verifiedBy`='[value-21]',`clgName`='[value-22]',`hostelName`='[value-23]',`seatsAvaibility`='[value-24]',`occupiedSeats`='[value-25]',`statusUpdationDate`='[value-26]' WHERE 1
                    $updateQuery = "UPDATE `report` SET reportTotalAmount=?, refund=?, paymentStatus=?, status=?, comment=?, remainingAmountCheck=?, remainingAmount=?, verifiedBy=?, statusUpdationDate=? WHERE reportID=?";
                    $updateStmt = $mysqli->prepare($updateQuery);
                    $updateStmt->bind_param('ddsssidisi',$reportTotalAmount, $refund, $paymentStatus, $statusT, $remark, $remainingAmountCheck, $remainingAmount, $id, $statusUpdationDate, $reportID);
                    if($updateStmt->execute()){

                        $recipientName = $fullName;
                        $recipientEmail = $emailid;
                        $subject = "Hostel Fee Refund Processed - $fullName";

                        $body = "
                        <p>Dear $fullName,</p>

                        <p>We are pleased to inform you that your refund request for the hostel fee has been processed successfully.</p>

                        <p><strong>Refund Details:</strong></p>
                        <ul>
                            <li><strong>Refund Amount:</strong> $refund</li>
                            <li><strong>Request Date:</strong> $statusUpdationDate</li>
                        </ul>

                        <p>Please collect your refunded amount from the account department or admin at your earliest convenience.</p>

                        <p>Thank you for your patience and understanding.</p>

                        <p>Best regards,<br>
                        $clgName<br>
                        Stay Easy, Stay Safe, Stay Comfortable</p>
                        ";
                        $altBody = '';
                        $bcc = "";
                        $cc = "";
            
                        if(newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)){
                            echo "<script>alert(' Student ".$statusT." succesfully ');</script>";
                        }
                    }else{
                        echo "<script>alert(' Get Error In Refund ');</script>";
                    }
                    // Close the update statement
                    $updateStmt->close();
                }
            }else{
                echo "<script>alert(' Can Not Change Status Of Verified ');</script>";
            }
        } else {
            // If user does not exist, return an error message
            echo "<script>alert(' Student not exist ');</script>";
        }

    }else{
        echo "<script>alert(' Mark Remianing Amount check Yes Then Submit Form ');</script>";
    }
}

if (isset($_POST['submit']) && $_POST['remainingAmountCheck'] == 1) {
    
    $reportID = intval(decrypt($_GET['reportId']));
    // $userPrn = $_GET['userPrn'];
    // $academicYear = $_GET['academicYear'];
    $statusUpdationDate = date('Y-m-d H:i:s');
    $id=$_SESSION['adminId'];
    $remark = $_POST['remark']; 
    $status = $_POST['status']; 
    $remainingAmountCheck = $_POST['remainingAmountCheck']; 
    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare the SQL query to check if the user exists
    $checkQuery = "SELECT * FROM report WHERE reportID=?";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param('i', $reportID);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    // Check if the user transaction exists
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $remainingAmount = $row['remainingAmount'];
        $academicYear = $row['academicYear'];
        $roomFeesphy = $row['roomFeesphy'];
        $userPrn = $row['userPrn'];
        $paymentStatus = $row['paymentStatus'];
        $statusT = $row['status'];
        $fullName = $row['fullName'];
        $emailid = $row['emailid'];
        $clgName = $row['clgName'];
        $reportTotalAmount = $row['reportTotalAmount'];

        if($statusT == "pending"){
            // Toggle the statusT
            if ($statusT === "pending") {
                $statusT = "verified";
                
            } else {
                $statusT = "pending";
            }

            if($remainingAmount <= 0){
                $paymentStatus = 'Full Paid';
            }elseif($remainingAmount >= $roomFeesphy){
                if($status == 'pending'){
                    $paymentStatus = 'Concession';
                }else{
                    $paymentStatus = 'Not Paid';
                }
            }elseif($remainingAmount > 0){
                if($status == 'pending'){
                    $paymentStatus = 'Concession';
                }else{
                    $paymentStatus = 'Partial Paid';
                }
            }
            
            // Prepare the SQL query to update the status in transaction hestory table
            $updateQuery = "UPDATE `report` SET paymentStatus=?, status=?, comment=?, remainingAmountCheck=?, verifiedBy=?, statusUpdationDate=? WHERE reportID=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('sssiisi',$paymentStatus, $statusT, $remark, $remainingAmountCheck, $id, $statusUpdationDate, $reportID);
            if($updateStmt->execute()){

                $recipientName = $fullName;
                $recipientEmail = $emailid;
                $subject = "Academic Payment Status Update - $clgName";
                $body = "<div style='max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                            <p><strong>Subject:</strong> Payment Status Update - $clgName</p>
                            
                            <p>Dear $fullName,</p>
                            
                            <p>We would like to inform you about the status of your payment for the academic year $academicYear. Below are the details of your payment status:</p>
                            
                            <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                                <tr>
                                    <td style='padding: 8px; border: 1px solid #ddd;'><strong>Total Payment</strong></td>
                                    <td style='padding: 8px; border: 1px solid #ddd;'>$reportTotalAmount</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px; border: 1px solid #ddd;'><strong>Payment Status</strong></td>
                                    <td style='padding: 8px; border: 1px solid #ddd;'>$paymentStatus</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px; border: 1px solid #ddd;'><strong>Verification Status</strong></td>
                                    <td style='padding: 8px; border: 1px solid #ddd;'>$statusT</td>
                                </tr>
                            </table>
                            
                            <p>If you have any questions regarding your payment or need further assistance, please do not hesitate to contact our finance office.</p>
                            
                            <p>Best regards,<br>
                            Stay Easy<br>
                            Stay Safe Stay Comfortable<br>
                            $clgName</p>
                        </div>";
                $altBody = '';
                $bcc = "";
                $cc = "";

                if(newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)){
                    echo "<script>alert(' Student ".$statusT." succesfully ');</script>";
                }
            }

            // Close the update statement
            $updateStmt->close();
        }else{
            echo "<script>alert(' Can Not Change Status Of Verified ');</script>";
        }

    } else {
        // If user does not exist, return an error message
        echo "<script>alert(' Student not exist ');</script>";
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
	<title>Payment Overview</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
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

<style>
    /* Base styles for refund labels */
    .refund-label {
        padding: 5px;
        color: #777;
    }

    /* Style for disabled radio buttons */
    input[type="radio"]:disabled + .refund-label {
        cursor: not-allowed;
    }

    /* Highlight the checked disabled radio buttons */
    input[type="radio"]:disabled:checked + .refund-label {
        color: #fff;
        background-color: #007bff; /* Bright color (Bootstrap primary color) */
        border-radius: 5px;
        padding: 5px;
    }
</style>

</head>

<body>
	<?php include('includes/header.php');?>

        <div class="ts-main-content">
                <?php include('includes/sidebar.php');?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row" id="print">
                        <?php	

                            $cid = decrypt($_GET['reportId']);
                            $ret="SELECT * from report where (reportID =?)";
                            $stmt= $mysqli->prepare($ret) ;
                            $stmt->bind_param('i',$cid);
                            $stmt->execute() ;
                            $res=$stmt->get_result();
                            $cnt=1;
                            while($row=$res->fetch_object())
                                {
                            ?>
                            <div class="col-md-12">
                                <h2 class="page-title" style="margin-top:3%">#<?php echo $row->userPrn;?> Details</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading">#<?php echo $row->userPrn;?> Details</div>
                                    <div class="panel-body">
                                        <form action="" method="post">
                                            <table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">        
                                                <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>			
                                                <tbody>
                                                    <tr>
                                                    <td colspan="6" style="text-align:center; color:blue"><h4>Payment Status Overview</h4></td>
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
                                                        <td id="roomFeeText"><?php echo $row->roomFeesphy;?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Stay From: </th>
                                                        <td id="stayfText"><?php echo $row->stayFrom;?></td>
                                                        <th>Class: </th>
                                                        <td><?php echo $row->class;?></td>
                                                    </tr>

                                                    <tr>
                                                        <th>Paid Amount: </th>
                                                        <td id="totalAmountText"><?php echo $row->reportTotalAmount;?></td>
                                                        <th>For Year: </th>
                                                        <td><?php echo $row->academicYear;?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Paid Status: </th>
                                                        <td <?php echo $row->refund? "": "colspan='3'";?>><?php echo $row->paymentStatus;?></td>
                                                        <?php echo $row->refund? "<th>Refund Amount: </th>
                                                        <td>$row->refund</td>" : "";?>
                                                        
                                                    </tr>
                                                        <tr>
                                                        <th>Remaining Amount: </th>
                                                        <td colspan="3" id="remainingFeeText"><?php echo $row->remainingAmount; ?></td>
                                                    </tr>
                                                    <tr class="danger">
                                                        <th>Remaining Amount Confirm </th>
                                                        <td colspan="3">
                                                            <input type="radio" value="1" id="remainingAmountCheckYes" name="remainingAmountCheck" <?php echo $row->remainingAmountCheck ? 'checked' : ''; ?>> Yes
                                                            <input type="radio" value="0" id="remainingAmountCheckNo" name="remainingAmountCheck" <?php echo !$row->remainingAmountCheck ? 'checked' : ''; ?>> No
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Status </th>
                                                        <td  colspan="3">
                                                            <b style="color:<?php echo ($row->status === 'verified') ? 'rgb(106, 221, 106)' : 'tomato'; ?>;">
                                                                <input type="text" name="status" value="<?php echo $row->status; ?>" hidden>
                                                                <?php echo $row->status; ?>
                                                            </b>
                                                        </td>
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
                                                        <td colspan="6" style="text-align:center; color:blue">Verified By</td>
                                                        </tr>
                                                                <tr>
                                                                    <td><b>Admin Email:</b></td>
                                                                    <td><?php echo $email;?></td>
                                                                    <td><b>Admin Full Name:</b></td>
                                                                    <td ><?php echo $adminName;?></td>
                                                                </tr>
                                                        <?php
                                                        }
                                                    ?>
                                                    
                                                </tbody>
                                            </table>
                                            <div class="action-column">
                                                <textarea name="remark" id="remark" placeholder="Remark or Message" rows="6" class="form-control limitedText" oninput="validateInput(event)" maxlength="200" required></textarea>
                                                <span class="error text-danger"></span>
                                                <span class="text-muted charCount">200 characters remaining</span>
                                            </div>
                                            <button type="button" class="btn btn-info subm" data-toggle="modal" data-target="#myModal">Refund</button>
                                            <input type="submit" name="submit" value="Submit" class="btn btn-primary sub">
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

        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Take Action</h4>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div>
                            <label for="stayf" class="control-label">Stay From<span class="text-danger">*</span> : </label>
                            <input type="date" name="stayf" id="stayf" class="form-control" readonly>
                        </div>
                        <div>
                            <label for="roomFee" class="control-label">Room Fee<span class="text-danger">*</span> : </label>
                            <input type="number" name="roomFee" id="roomFee" class="form-control" readonly>
                        </div>
                        <div>
                            <label for="totalAmount" class="control-label">Total Paid<span class="text-danger">*</span> : </label>
                            <input type="number" name="totalAmount" id="totalAmount" class="form-control" readonly>
                        </div>
                        <div>
                            <label for="remainingFee" class="control-label">Remaining Fee<span class="text-danger">*</span> : </label>
                            <input type="number" name="remainingFee" id="remainingFee" class="form-control" readonly>
                        </div>
                        <div>                            
                            <input type="radio" value="1" name="remainingAmountCheckRefund" id="remainingAmountCheckRefundYes" hidden>
                            <input type="radio" value="0" name="remainingAmountCheckRefund" id="remainingAmountCheckRefundNo" hidden>
                        </div>
                        <p>
                            <label for="refund" class="control-label">Refund : <span class="text-danger">*</span></label>
                            <input type="number" name="refundAmount" id="refund" class="form-control">
                        </p>
                        <p>
                            <textarea name="remarkRefund" id="remarkRefund" hidden></textarea>
                        </p>
                        
                        <div>
                            <input type="submit" name="refund" value="Refund" class="btn btn-primary sub" >
                        </div>
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
                const hideRefund = document.querySelector('.subm');
                const hideColumn = document.querySelector('.action-column');
                const sub = document.querySelector('.sub');
                hideColumn.style.display = 'none';
                sub.style.display = 'none';
                hideRefund.style.display = 'none';
                var prtContent = document.getElementById("print");
                var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
                hideColumn.style.display = 'block';
                sub.style.display = 'table-cell';
                hideRefund.style.display = 'table-cell';
            }

            document.addEventListener("DOMContentLoaded", function() {
                const stayfText =document.getElementById('stayfText').innerText;
                const roomFeeText =document.getElementById('roomFeeText').innerText;
                const totalAmountText =document.getElementById('totalAmountText').innerText;
                const remainingFeeText =document.getElementById('remainingFeeText').innerText;

                const remainingAmountCheckYes = document.getElementById('remainingAmountCheckYes');
                const remainingAmountCheckNo = document.getElementById('remainingAmountCheckNo');
                const remainingAmountCheckRefundYes = document.getElementById('remainingAmountCheckRefundYes');
                const remainingAmountCheckRefundNo = document.getElementById('remainingAmountCheckRefundNo');
                const remark = document.getElementById('remark');
                const remarkRefund = document.getElementById('remarkRefund');

                const stayf =document.getElementById('stayf');
                const roomFee =document.getElementById('roomFee');
                const totalAmount =document.getElementById('totalAmount');
                const remainingFee =document.getElementById('remainingFee');

                // Function to update remainingAmountCheckRefund
                function updateRemainingAmountCheckRefund() {
                    remarkRefund.value = remark.value;
                    if (remainingAmountCheckYes.checked) {
                        remainingAmountCheckRefundYes.checked = true;
                    } else if (remainingAmountCheckNo.checked) {
                        remainingAmountCheckRefundNo.checked = true;
                    }
                }

                // Add event listeners to detect changes
                remainingAmountCheckYes.addEventListener('change', updateRemainingAmountCheckRefund);
                remainingAmountCheckNo.addEventListener('change', updateRemainingAmountCheckRefund);
                remark.addEventListener('change', updateRemainingAmountCheckRefund);

                // Initial call to set the correct value based on initial selection
                updateRemainingAmountCheckRefund();

                stayf.value = stayfText;
                roomFee.value = Number(roomFeeText);
                totalAmount.value = Number(totalAmountText);
                remainingFee.value = Number(remainingFeeText);

            });

            function validateInput(event) {
                const textarea = event.target;
                const maxLength = textarea.getAttribute('maxlength');
                const charCount = textarea.value.length;
                const remainingChars = maxLength - charCount;
                document.querySelector('.charCount').textContent = `${remainingChars} characters remaining`;
            }
        </script>
    </body>

</html>