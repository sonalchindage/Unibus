<?php
session_start();
include('includes/config.php');
include('../includes/enc.php');
include('../includes/phpMailer/mail.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    // Posted Values
    $statusUpdationDate = date('Y-m-d H:i:s');
    $id=$_SESSION['adminId'];
    $receiptFileCheck = $_POST['receiptFileCheck'];
    $payTypeCheck = $_POST['payTypeCheck'];
    $paidAmountCheck = $_POST['paidAmountCheck'];
    $receiptTokenidCheck = $_POST['receiptTokenidCheck'];
    $paymentDateCheck = $_POST['paymentDateCheck'];
    $alluserDetailsCheck = $_POST['alluserDetailsCheck'];
    $remark = $_POST['remark'];    
    $transactionId = $_POST['transactionId'];

    
    
    

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $checkQuery = "SELECT * FROM transactionhistory WHERE transactionId=?";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param('i', $transactionId);
    $checkStmt->execute();
    $res = $checkStmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $userPrn = $row['userPrn'];
        $academicYear = $row['academicYear'];
        $paidAmount = $row['paidAmount'];
        $status = $row['status'];
        $receiptTokenId = $row['receiptTokenId'];
        $paymentDate = $row['paymentDate'];

        if($status == 'rejected'){
            echo "<script>alert(`Rejected transaction can not verified`);</script>";
        }else{
            $checkUserQuery = "SELECT * FROM report WHERE userPrn=? AND academicYear=?";
            $userStmt = $mysqli->prepare($checkUserQuery);
            $userStmt->bind_param('ss', $userPrn, $academicYear);
            $userStmt->execute();
            $userRes = $userStmt->get_result();
            // SELECT `reportID`, `stayFrom`, `receiptTokenId`, `reportTotalAmount`, `receiptFile`, `class`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `roomFeesphy`, `paymentType`, `academicYear`, `paymentStatus`, `status`, `skimAmount`, `comment`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`, `statusUpdationDate` FROM `report` WHERE 1
            if ($userRes->num_rows > 0) {
                $userRow = $userRes->fetch_assoc();
                $totalPaidAmount = $userRow['reportTotalAmount'];
                $paymentStatus = $userRow['paymentStatus'];
                $roomFeesphy = $userRow['roomFeesphy'];
                $remainingAmount = $userRow['remainingAmount'];
                $emailid = $userRow['emailid'];
                $fullName = $userRow['fullName'];
                $clgName = $userRow['clgName'];

                if ($status == 'pending') {
                    if ($receiptFileCheck == 1 && $payTypeCheck == 1 && $paidAmountCheck == 1 && $receiptTokenidCheck == 1 && $paymentDateCheck == 1 && $alluserDetailsCheck == 1) {
                        $status = 'verified';
                        $totalPaidAmount += $paidAmount;
                        
                    } else {
                        $status = 'rejected';
                    }

                }elseif($status == 'verified') {
                    if ($receiptFileCheck == 1 && $payTypeCheck == 1 && $paidAmountCheck == 1 && $receiptTokenidCheck == 1 && $paymentDateCheck == 1 && $alluserDetailsCheck == 1) {
                        $status = 'verified';
                    } else {
                        $status = 'rejected';
                        $totalPaidAmount -= $paidAmount;
                    }
                }

                // if ($status == "verified") {
                //     $totalPaidAmount += $paidAmount;
                // } elseif($status == "rejected") {
                //     $totalPaidAmount -= $paidAmount;
                // }
                // if($totalPaidAmount < 0){
                //     $totalPaidAmount = 0;
                // }

                $remainingAmount = $roomFeesphy - $totalPaidAmount;

                if($totalPaidAmount <= 0){
                    $paymentStatus = "Not Paid";
                }elseif ($totalPaidAmount < $roomFeesphy) {
                    $paymentStatus = "Partially Paid";
                }elseif ($totalPaidAmount == $roomFeesphy) {
                    $paymentStatus = "Full Paid";
                }elseif ($totalPaidAmount > $roomFeesphy) {
                    $paymentStatus = "Over Paid";
                }

                if($totalPaidAmount > $roomFeesphy){
                    echo "<script>alert('Student transaction getting over paid check all transactions of student');</script>";
                }else{
                    $userQuery = "UPDATE report SET  reportTotalAmount=?, paymentStatus=?, remainingAmount=? WHERE userPrn=? AND academicYear=?";
                    $userStmt = $mysqli->prepare($userQuery);
                    $userStmt->bind_param('dsdss',$totalPaidAmount, $paymentStatus, $remainingAmount, $userPrn, $academicYear);
                    if ($userStmt->execute()) {
                        unset($_POST);
                        $_POST = array();
                        echo "<script>alert('Total payment for academicYear " . $academicYear . " is " . $paymentStatus . " of student RegNo." . $userPrn . " successfully');</script>";
                    }
                    $updateQuery = "UPDATE transactionhistory SET  status=?, comment=?, paymentDateCheck=?, receiptTokenidCheck=?, paidAmountCheck=?, receiptFileCheck=?, alluserDetailsCheck=?, payTypeCheck=?, verifiedBy=?, statusUpdationDate=? WHERE transactionId=?";
                    $updateStmt = $mysqli->prepare($updateQuery);
                    $updateStmt->bind_param('ssiiiiiiisi', $status, $remark, $paymentDateCheck, $receiptTokenidCheck, $paidAmountCheck, $receiptFileCheck, $alluserDetailsCheck, $payTypeCheck, $id, $statusUpdationDate, $transactionId);
                    if ($updateStmt->execute()) {
                        $subject = "Transaction Declined - $receiptTokenId";
                        $body = "<p><strong>Subject:</strong> Transaction Declined - $receiptTokenId</p>  
                                    <p>Dear $fullName,</p>
                                    <p>We regret to inform you that your recent transaction could not be processed.</p>
                                    <p><strong>Transaction Details:</strong></p>
                                    <ul>
                                        <li><strong>Transaction ID:</strong> $receiptTokenId</li>
                                        <li><strong>Amount:</strong> $paidAmount</li>
                                        <li><strong>Date:</strong> $paymentDate</li>
                                    </ul>
                                    <p>Possible reasons for the decline could include insufficient funds, incorrect payment details, or issues with your bank. We recommend you review the information provided and attempt the transaction again.</p>
                                    <p>We apologize for any inconvenience this may have caused.</p>
                                    <p>Best regards,<br>
                                    Stay Easy<br>
                                    Stay Safe Stay Comfortable<br>
                                    $clgName</p>";
                        if($status == 'verified'){
                            $subject = "Transaction Confirmation - $receiptTokenId";
                            $body = "<p><strong>Subject:</strong> Transaction Confirmation - $receiptTokenId</p>
                                    <p>Dear $fullName,</p>
                                    <p>We are pleased to inform you that your recent transaction has been successfully processed.</p>
                                    <p><strong>Transaction Details:</strong></p>
                                    <ul>
                                        <li><strong>Transaction ID:</strong> $receiptTokenId</li>
                                        <li><strong>Amount:</strong> $paidAmount</li>
                                        <li><strong>Date:</strong> $paymentDate</li>
                                    </ul>
                                    <p>Thank you for your business!</p>
                                    <p>Best regards,<br>
                                    Stay Easy<br>
                                    Stay Safe Stay Comfortable<br>
                                    $clgName</p>";
                        }

                        $recipientName = $fullName;
                        $recipientEmail = $emailid;
                        $altBody = '';
                        $bcc = "";
                        $cc = "";
                        if(newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc)){
                            unset($_POST);
                            $_POST = array();
                            echo "<script>alert('Receipt " . $status . " successfully');</script>";
                        }
                    }

                    $userStmt->close();
                    $updateStmt->close();
                }
            }else{
                echo "<script>alert('Student does not exist');</script>";
            }
        }
    } else {
        echo "<script>alert('Receipt does not exist');</script>";
    }

    $checkStmt->close();
    
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
    var popUpWin = 0;
    function popUpWindow(URLStr, left, top, width, height) {
        if (popUpWin) {
            if (!popUpWin.closed) popUpWin.close();
        }
        popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width=' + 510 + ',height=' + 430 + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
    }
    </script>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row" id="print">
                    <?php
                    $cid = decrypt($_GET['transactionView']);
                    $ret = "SELECT t.*,r.reportTotalAmount, r.remainingAmount from transactionhistory t LEFT JOIN report r ON t.userPrn = r.userPrn AND t.academicYear = r.academicYear WHERE t.transactionId = ? ORDER BY t.transactionId;";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('i', $cid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                    ?>
                        <div class="col-md-12">
                            <h2 class="page-title" style="margin-top:3%">#<?php echo $row->receiptTokenId; ?> Details</h2>
                            <div class="panel panel-default">
                                <div class="panel-heading">#<?php echo $row->receiptTokenId; ?> Details</div>
                                <div class="panel-body">
                                    <form action="" method="post">
                                        <input type="hidden" name="transactionId" id="transactionId" value="<?php echo $cid; ?>">
                                        <table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">
                                            <span style="float:left"><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>
                                            <tbody>
                                                <tr>
                                                    <td colspan="6" style="text-align:center; color:blue">
                                                        <h4>Transaction Related Info</h4>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Full name: </th>
                                                    <td><?php echo $row->fullName; ?></td>
                                                    <th>Student PRN: </th>
                                                    <td><?php echo $row->userPrn; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Email: </th>
                                                    <td><?php echo $row->emailid; ?></td>
                                                    <th>Contact No: </th>
                                                    <td><?php echo $row->contactno; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Staying in Room: </th>
                                                    <td><?php echo $row->roomno; ?></td>
                                                    <th>Room Fee: </th>
                                                    <td><?php echo $row->feespm; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Verified Total Amount: </th>
                                                    <td><?php echo $row->reportTotalAmount; ?></td>
                                                    <th>Remaining Amount: </th>
                                                    <td><?php echo $row->remainingAmount; ?></td>
                                                </tr>

                                                <tr class="danger">
                                                    <th >Student Details Confirm </th>
                                                    <td colspan="3">
                                                        <input type="radio" value="1" name="alluserDetailsCheck" <?php echo $row->alluserDetailsCheck ? 'checked' : ''; ?>> Yes
                                                        <input type="radio" value="0" name="alluserDetailsCheck" <?php echo !$row->alluserDetailsCheck ? 'checked' : ''; ?>> No
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Payment Date: </th>
                                                    <td colspan="3"><?php echo $row->paymentDate; ?></td>
                                                </tr>
                                                <tr class="danger">
                                                    <th>Payment Date Confirm </th>
                                                    <td colspan="3">
                                                        <input type="radio" value="1" name="paymentDateCheck" <?php echo $row->paymentDateCheck ? 'checked' : ''; ?>> Yes
                                                        <input type="radio" value="0" name="paymentDateCheck" <?php echo !$row->paymentDateCheck ? 'checked' : ''; ?>> No
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Receipt No: </th>
                                                    <td colspan="3"><?php echo $row->receiptTokenId; ?></td>
                                                </tr>
                                                <tr class="danger">
                                                    <th>Receipt Nunmber/ID Confirm </th>
                                                    <td colspan="3">

                                                        <input type="radio" value="1" name="receiptTokenidCheck" <?php echo $row->receiptTokenidCheck ? 'checked' : ''; ?>> Yes
                                                        <input type="radio" value="0" name="receiptTokenidCheck" <?php echo !$row->receiptTokenidCheck ? 'checked' : ''; ?>> No
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Paid Amount: </th>
                                                    <td colspan="3"><?php echo $row->paidAmount; ?></td>
                                                </tr>
                                                <tr class="danger">
                                                    <th>Paid Amount Confirm </th>
                                                    <td colspan="3">

                                                        <input type="radio" value="1" name="paidAmountCheck" <?php echo $row->paidAmountCheck ? 'checked' : ''; ?>> Yes
                                                        <input type="radio" value="0" name="paidAmountCheck" <?php echo !$row->paidAmountCheck ? 'checked' : ''; ?>> No
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Payment Type </th>
                                                    <td colspan="3"><?php echo $row->payType; ?></td>
                                                </tr>
                                                <tr class="danger">
                                                    <th>Payment Type Confirm </th>
                                                    <td colspan="3">

                                                        <input type="radio" value="1" name="payTypeCheck" <?php echo $row->payTypeCheck ? 'checked' : ''; ?>> Yes
                                                        <input type="radio" value="0" name="payTypeCheck" <?php echo !$row->payTypeCheck ? 'checked' : ''; ?>> No
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>File (if any)</th>
                                                    <td colspan="3">
                                                        <?php $cdoc = $row->receiptFile;
                                                        if ($cdoc == '') :
                                                            echo "NA";
                                                        else : ?>
                                                            <a href="../uploads/<?php echo $cdoc; ?>" target="blank">File</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr class="danger">
                                                    <th>Reciept File Confirm </th>
                                                    <td colspan="3">
                                                        <input type="radio" value="1" name="receiptFileCheck" <?php echo $row->receiptFileCheck ? 'checked' : ''; ?>> Yes
                                                        <input type="radio" value="0" name="receiptFileCheck" <?php echo !$row->receiptFileCheck ? 'checked' : ''; ?>> No

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Paid Status: </th>
                                                    <td colspan="3"><?php echo $row->paidStatus; ?></td>
                                                </tr>

                                                <tr>
                                                    <th>Payment Status </th>
                                                    <td colspan="3">
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
                                                <tr>
                                                    <th>For academicYear: </th>
                                                    <td><?php echo $row->academicYear; ?></td>
                                                    <th>Receipt Uploaded on: </th>
                                                    <td><?php echo $row->timeStamp; ?></td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        <div class="action-column">
                                            <textarea name="remark" id="remark" placeholder="Remark or Message" rows="6" class="form-control limitedText" oninput="validateInput(event)" maxlength="200" required></textarea>
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
                        $cnt = $cnt + 1;
                    } ?>
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
        $(function () {
            $("[data-toggle=tooltip]").tooltip();
        });

        function CallPrint(strid) {
            const hideColumn = document.querySelector('.action-column');
            const sub = document.querySelector('.sub');
			hideColumn.style.display = 'none';
			sub.style.display = 'none';
            var prtContent = document.getElementById("print");
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            hideColumn.style.display = 'table-cell';
			sub.style.display = 'table-cell';
        }

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
