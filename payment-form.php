<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// $currentYear = 2025;
$currentYear = date("Y");
$currentDate = date("Y-m-d");
$academicYear = $currentYear."-".$currentYear+1;
//code for report
if(isset($_POST['submit']) && isset($_FILES['image']))
{
    // Get the form data
    $clgName = $_POST['clgName'];
    $hostelName = $_POST['hostelName'];
    $roomno = $_POST['roomno'];
    $feespm = $_POST['fpm'];
    $payType = $_POST['payType'];
        //  $userPrn = $_POST['userPrn'];
    $fullName = $_POST['fullName'];
    $contactno = $_POST['contact'];
    $emailid = $_POST['email'];
    $paymentDate = $_POST['payDate'];
    $receiptTokenId = trim($_POST['payId']);
    $academicYearNew = $_POST['academicYear'];
    $paidAmount = $_POST['payAmount'];
    $remainingAmount = $_POST['remainingAmount'];
    $userPrn = $_POST['userPrn'];
    $status = "pending";

    //  image file validation
    $receiptFile = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];
    //  $folder = 'images/'.$receiptFile;

    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

 if ($error === 0) {
    if ($img_size > 5242880) {
        $em = "Sorry, your file is too large.";
        // echo $em;
        echo "<script>alert(' Please upload an image smaller than 5MB ');</script>";
        // header("Location: index.php?error=$em");
    }else {
        $img_ex = pathinfo($receiptFile, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);

        $allowed_exs = array("jpg", "jpeg", "png"); 

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid($userPrn."-IMG-", true).'.'.$img_ex_lc;
            $img_upload_path = 'uploads/'.$new_img_name;

            // Check if the userPrn and year already exist in the table
            $checkQuery = "SELECT * FROM report WHERE userPrn = ? AND academicYear = ?";
            $checkStmt = $mysqli->prepare($checkQuery);
            $checkStmt->bind_param('ss', $userPrn, $academicYearNew);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                
                // Check if receiptTokenId exists
                // $tokenChkStmt = $mysqli->prepare("SELECT receiptTokenId FROM transactionhistory WHERE receiptTokenId = ? && status ='verified' || status ='pending'");
                $tokenChkStmt = $mysqli->prepare("SELECT receiptTokenId FROM transactionhistory WHERE receiptTokenId = ? AND (status = 'verified' OR status = 'pending')");
                $tokenChkStmt->bind_param('s', $receiptTokenId);
                $tokenChkStmt->execute();
                $tokenChkStmt->store_result();
                if ($tokenChkStmt->num_rows > 0) {
                    echo "<script>alert('Receipt Token ID already exists');</script>";
                } else {
                    // Prepare the SQL query for transactionhistory table
// INSERT INTO `transactionhistory`(`transactionId`, `timeStamp`, `paymentDate`, `receiptTokenId`, `paidAmount`, `receiptFile`, `fname`, `userPrn`, `emailid`, `contactno`, `roomno`, `feespm`, `payType`, `academicYear`, `paidStatus`, `status`, `skimAmount`, `comment`, `paymentDateCheck`, `receiptTokenidCheck`, `paidAmountCheck`, `receiptFileCheck`, `alluserDetailsCheck`, `payTypeCheck`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `statusUpdationDate`, `hostelName`, `clgName`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]','[value-13]','[value-14]','[value-15]','[value-16]','[value-17]','[value-18]','[value-19]','[value-20]','[value-21]','[value-22]','[value-23]','[value-24]','[value-25]','[value-26]','[value-27]','[value-28]','[value-29]','[value-30]')
                        $paidStatus = "";
                        if($feespm == $paidAmount){
                            $paidStatus .= "Full Paid";
                        }else{
                            $paidStatus .= "Partial Paid";
                        }
                    $queryHistory = "INSERT INTO transactionhistory ( timeStamp, paymentDate, receiptTokenId, paidAmount, receiptFile, fullName, userPrn, emailid, contactno, roomno, feespm, payType, academicYear, paidStatus, status, remainingAmount, clgName, hostelName) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $transactionStmt = $mysqli->prepare($queryHistory);
                    $transactionStmt->bind_param('sssdssssisdssssdss', $timeStamp, $paymentDate, $receiptTokenId, $paidAmount, $new_img_name, $fullName, $userPrn, $emailid, $contactno, $roomno, $feespm, $payType, $academicYearNew, $paidStatus, $status, $remainingAmount, $clgName, $hostelName);

                    // if(move_uploaded_file($tempname, $img_upload_path)){
                    //     echo "<script>alert(' Recipt image uploaded successfully');</script>";
                    // }else{
                    //     echo "<script>alert(' Recipt image failed to upload');</script>";
                    // }

                    // Execute the query
                    if ($transactionStmt->execute() && move_uploaded_file($tempname, $img_upload_path)) {
                        echo "<script>alert('Recipt image uploaded successfully! Transaction history successfully recorded.');</script>";
                    } else {
                        echo "<script>alert('Error: " . $transactionStmt->error . "');</script>";
                    }

                    // Close the statement and the connection
                    $transactionStmt->close();
                }
                $tokenChkStmt->close();
            } 

            // Close the statement and the connection
            $checkStmt->close();

        }else {
            echo "<script>alert('Selected file is not type of jpg, jpeg, png');</script>";
        }

    }

}else {
    // $em = "unknown error occurred!";
    // header("Location: index.php?error=$em");
    echo "<script>alert('Image file has some issue select another image');</script>";

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
	<title>Make Payment</title>
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
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>

</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Proceed To Payment</h2>

						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-heading">Fill all Info</div>
                                        <div class="panel-body">
                                            <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                                                <?php
                                                $aid=$_SESSION['login'];
                                                $userPrnNew=$_SESSION['userPrn'];
                                                // $currentYear = date("Y");
                                                // $academicYear = $currentYear."-".$currentYear+1;
                                                $ret="SELECT * from registration where (emailid = ? || userPrn = ?) and academicYear =? and status ='verified'";
                                                $stmtRet= $mysqli->prepare($ret) ;
                                                $stmtRet->bind_param('sss',$aid,$userPrnNew,$academicYear);
                                                $stmtRet->execute() ;
                                                $res=$stmtRet->get_result();

                                                if ($res->num_rows > 0) {
                                                    $row=$res->fetch_object();
                                                    $postingDate = $row->postingDate;
                                                    $dateOnly = substr($postingDate, 0, 10); 
                                                    ?>
                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">College Name<span class="text-danger">*</span></label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="clgName" id="clgName" value="<?php echo $row->clgName;?>" class="form-control" readonly="true">
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Hostel Name<span class="text-danger">*</span></label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="hostelName" id="hostelName" value="<?php echo $row->hostelName;?>" class="form-control" readonly="true">
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Room No<span class="text-danger">*</span> : </label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="roomno" id="roomno"  class="form-control" value="<?php echo $row->roomno;?>" readonly>
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Fees Per Year<span class="text-danger">*</span> : </label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="fpm" id="fpm" value="<?php echo $row->feespm;?>"  class="form-control" readonly="true">
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Registration No<span class="text-danger">*</span> : </label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="userPrn" id="userPrn"  class="form-control" value="<?php echo $row->userPrn;?>" readonly>
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Full Name<span class="text-danger">*</span> : </label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="fullName" id="fname"  class="form-control" value="<?php echo $row->firstName." ".$row->middleName." ".$row->lastName;?>" readonly>
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Contact No<span class="text-danger">*</span> : </label>
                                                        <div class="col-sm-8">
                                                        <input type="text" name="contact" id="contact" value="<?php echo $row->contactno;?>"  class="form-control" readonly>
                                                        </div>
                                                        </div>

                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Email id<span class="text-danger">*</span> : </label>
                                                        <div class="col-sm-8">
                                                        <input type="email" name="email" id="email"  class="form-control" value="<?php echo $row->emailid;?>"  readonly>
                                                        </div>
                                                        </div>
                                                
                                                        
                                                        <div class="form-group">
                                                        <label class="col-sm-2 control-label">Payment Type<span class="text-danger">*</span> :</label>
                                                        <div class="col-sm-8">
                                                        <input type="radio" value="DD" name="payType" checked="checked"> DD <br>
                                                        <input type="radio" value="CASH" name="payType"> CASH <br>
                                                        <input type="radio" value="NFT" name="payType"> NFT <br>
                                                        <input type="radio" value="UPI" name="payType"> UPI <br>
                                                        </div>
                                                        </div>

                                                        <!-- payment details below-->
                                                        <div class="form-group">
                                                        <label class="col-sm-3 control-label"><h4 style="color: green" align="left">Payment Details </h4> </label>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Payment Date<span class="text-danger">*</span>: </label>
                                                                <div class="col-sm-8">
                                                                <input type="date" name="payDate" class="form-control" min="<?php echo $dateOnly; ?>" required="required">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">For Academic Year<span class="text-danger">*</span> : </label>
                                                                <div class="col-sm-8">
                                                                <input type="text" name="academicYear" value="<?php echo $row->academicYear; ?>" class="form-control" placeholder="e.g. 2023-24" title="Please enter the academic year in the format YYYY-YY, e.g., 2023-24" readonly required="required">
                                                            </div>
                                                        </div>																
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Recipt Token Id<span class="text-danger">*</span> : </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="payId" id="reciptToken" onblur="checkAvailability()" class="form-control" pattern="[A-Za-z0-9]+" required="required" placeholder="Recipt token No/Id">
                                                                <small class="form-text text-muted">Only letters (A-Z, a-z) and numbers (0-9) are allowed.</small>
                                                                <span id="token-availability-status" class="help-block m-b-none" style="font-size:12px;"></span> 
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Paid Amount<span class="text-danger">*</span> : </label>
                                                            <div class="col-sm-8">
                                                                <?php
                                                                    $userPrn = $_SESSION['userPrn'];
                                                                    $academicYearPrev = $row->academicYear;
                                                                    // $currentYear = date("Y");
                                                                    // $academicYear = $currentYear."-".$currentYear+1;
                                                                    $ret="SELECT * FROM report WHERE userPrn=? AND academicYear=? ";
                                                                    $stmt= $mysqli->prepare($ret) ;
                                                                    $stmt->bind_param('ss',$userPrn,$academicYearPrev);
                                                                    $stmt->execute() ;//ok
                                                                    $res=$stmt->get_result();
                                                                    $row=$res->fetch_object();
                                                                    $stmt->close();
                                                                ?>
                                                                <input type="number" name="remainingAmount" value="<?php echo $row->remainingAmount;?>" hidden>
                                                                <input type="number" name="payAmount"  class="form-control" required="required" min="0" max="<?php echo $row->remainingAmount;?>" placeholder="Enter your Remaining amount of <?php echo $row->remainingAmount;?>">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Upload Recipt<span class="text-danger">*</span>: <span style="color: red;">Note: Size below 5MB</span></label>
                                                            <div class="col-sm-8">
                                                                <input type="file" name="image" accept="image/*" class="form-control" required="required">
                                                            </div>
                                                        </div>
                                                        </div>																

                                                        <!-- payment details above -->
                                                        <div class="col-sm-6 col-sm-offset-4">
                                                        <button class="btn btn-default" type="submit">Cancel</button>
                                                        <input type="submit" name="submit" Value="Register" class="btn btn-primary">
                                                        </div>
                                                        <?php 
                                                                
                                                            }else{
                                                                echo "<script>
                                                                        alert('Your admission application is not confirmed yet Payment will enabled after admission confirmed ');
                                                                        window.location.href='dashboard.php';
                                                                    </script>";
                                                            }
                                                        ?>
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
<script type="text/javascript">
	$(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                $('#paddress').val( $('#address').val() );
                $('#pcity').val( $('#city').val() );
                $('#pstate').val( $('#state').val() );
                $('#ppincode').val( $('#pincode').val() );
            } 
            
        });
    });
</script>
	<script>
function checkAvailability() {
    $("#loaderIcon").show();
    console.log($("#reciptToken").val());
    jQuery.ajax({
        url: "check_availability.php",
        data:'token='+$("#reciptToken").val(),
        type: "POST",
        success:function(data){
        $("#token-availability-status").html(data);
        $("#loaderIcon").hide();
        },
        error:function (){}
        });
}
</script>


</html>