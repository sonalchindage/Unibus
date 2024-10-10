<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
//code for payments
if(isset($_POST['submit']) && isset($_FILES['image']))
{
 // Get the form data
 $roomno = $_POST['roomno'];
 $feespm = $_POST['fpm'];
 $payType = $_POST['payType'];
//  $regno = $_POST['regno'];
 $fname = $_POST['fname'];
 $mname=$_POST['mname'];
 $lname=$_POST['lname'];

 $fullName = $fname." ".$mname." ".$lname;

 $contactno = $_POST['contact'];
 $emailid = $_POST['email'];
 $paymentDate = $_POST['payDate'];
 $receiptTokenId = $_POST['payId'];
 $year = $_POST['year'];
 $paidAmount = $_POST['payAmount'];
 $userPrn = $_POST['regno'];
 $statusV = "pending";

 if($feespm === $paidAmount){
    $paidStatus = "full paid";
 }else{
    $paidStatus = "parsially paid";
 }
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
        echo "<script>alert(' Please upload an image smaller than 5MB ');</script>";
        // echo $em;
        // header("Location: index.php?error=$em");
    }else {
        $img_ex = pathinfo($receiptFile, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);

        $allowed_exs = array("jpg", "jpeg", "png"); 

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid($userPrn."-IMG-", true).'.'.$img_ex_lc;
            $img_upload_path = '../uploads/'.$new_img_name;

            // Check if the userPrn and year already exist in the table
            $checkQuery = "SELECT * FROM payments WHERE userPrn = ? AND year = ?";
            $checkStmt = $mysqli->prepare($checkQuery);
            $checkStmt->bind_param('ss', $userPrn, $year);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                
                // Check if receiptTokenId exists
                $tokenChkStmt = $mysqli->prepare("SELECT receiptTokenId FROM transactionhistory WHERE receiptTokenId = ?");
                $tokenChkStmt->bind_param('s', $receiptTokenId);
                $tokenChkStmt->execute();
                $tokenChkStmt->store_result();
                if ($tokenChkStmt->num_rows > 0) {
                    echo "<script>alert('Receipt Token ID already exists');</script>";
                } else {
                    // Prepare the SQL query for transactionhistory table
                    $queryHistory = "INSERT INTO transactionhistory ( timeStamp, paymentDate, receiptTokenId, paidAmount, receiptFile, fname, userPrn, emailid, contactno, roomno, feespm, payType, year, paidStatus, statusV) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $transactionStmt = $mysqli->prepare($queryHistory);
                    $transactionStmt->bind_param('sssdssssisissss', $timeStamp, $paymentDate, $receiptTokenId, $paidAmount, $new_img_name, $fullName, $userPrn, $emailid, $contactno, $roomno, $feespm, $payType, $year, $paidStatus, $statusV);

                    if(move_uploaded_file($tempname, $img_upload_path)){
                        echo "<script>alert(' Recipt image uploaded successfully');</script>";
                    }else{
                        echo "<script>alert(' Recipt image failed to upload');</script>";
                    }

                    
                    if ($transactionStmt->execute()) {
                        echo "<script>alert('Transaction history successfully recorded');</script>";
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
	<title>Student Hostel Registration</title>
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

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Registration No<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="text" name="regno" id="regno" class="form-control" required="required" onBlur="checkRegnoAvailability()">
                                            <!-- <input type="text" name="regno" id="regno" class="form-control" required="required" onBlur="checkRegnoAvailability()"> -->
                                            <span id="user-reg-availability" style="font-size:12px;"></span>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Room No<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="text" name="roomno" id="roomno"  class="form-control" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Fees Per Year<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="number" name="fpm" id="fpm" class="form-control" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">First Name<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="text" name="fname" id="fname" class="form-control" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Middle Name<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="text" name="mname" id="mname"  class="form-control" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Last Name<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="text" name="lname" id="lname" class="form-control" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Contact No.<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="tel" name="contact" id="contact"  class="form-control" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Email id<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="email" name="email" id="email"  class="form-control" readonly>
                                            </div>
                                            </div>
                                            
                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Payment Type<span class="text-danger">*</span> : </label>
                                            <div class="col-sm-8">
                                            <input type="radio" value="offline" name="payType" checked="checked"> Offline <br>
                                            <input type="radio" value="online" name="payType"> Online
                                            </div>
                                            </div>

                                            <!-- payment details below-->
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label"><h4 style="color: green" align="left">Payment Details </h4> </label>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Payment Date<span class="text-danger">*</span> : </label>
                                                    <div class="col-sm-8">
                                                    <input type="date" name="payDate" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="year" class="col-sm-2 control-label">For Year<span class="text-danger">*</span> : </label>
                                                    <div class="col-sm-8">
                                                    <input type="text" id="year" name="year" pattern="\d{4}-\d{2}" class="form-control" placeholder="e.g. 2023-24" title="Please enter the academic year in the format YYYY-YY, e.g., 2023-24" required="required" readonly>
                                                </div>
                                            </div>																
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Recipt token Id<span class="text-danger">*</span> : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="payId" id="reciptToken" onblur="checkAvailability()" class="form-control" required="required" placeholder="Recipt token No/Id">
                                                    <span id="token-availability-status" class="help-block m-b-none" style="font-size:12px;"></span> 
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Paid amount<span class="text-danger">*</span> : </label>
                                                <div class="col-sm-8">
                                                    <input type="number" name="payAmount" id="pAmount" class="form-control" required="required" placeholder="Enter Paid amount of recipt">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Upload Recipt<span class="text-danger">*</span> : <span style="color: red;">Note: Size below 50kb</span></label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="image" accept="image/*" class="form-control" required="required">
                                                </div>
                                            </div>
                                            <!-- payment details above -->
                                            <div class="col-sm-6 col-sm-offset-4">
                                                <button class="btn btn-default" type="submit">Cancel</button>
                                                <input type="submit" name="submit" Value="Register" class="btn btn-primary">
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
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
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
        function checkRegnoAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: { regno: $("#regno").val() },
                type: "POST",
                success: function(data) {
                    const result = JSON.parse(data);
                    // console.log(result);
                    if (result) {
                        $('#fname').val(result.firstName);
                        $('#mname').val(result.middleName);
                        $('#lname').val(result.lastName);
                        $('#email').val(result.emailid);
                        $('#contact').val(result.contactno);
                        $('#roomno').val(result.roomno);
                        $('#fpm').val(result.feespm);
                        $('#year').val(result.duration);
                        document.getElementById('pAmount').setAttribute('max', result.feespm);
                    } else {
                        alert('Registration number not found.');
                    }
                    $("#loaderIcon").hide();
                },
                error: function() {
                    event.preventDefault();
                    alert('An error occurred.');
                    $("#loaderIcon").hide();
                }
            });
        }

        // function checkRegnoAvailability() {
        //     $("#loaderIcon").show();
        //     jQuery.ajax({
        //         url: "check_availability.php",
        //         data:'regno='+$("#regno").val(),
        //         type: "POST",
        //         success:function(data){
        //             const result = JSON.parse(data);
        //             console.log(data);
        //             console.log(result);
        //             $('#fname').val(result.firstName);
        //             // $('#fpm').val(result.fees);
        //             $("#loaderIcon").hide();
        //         },
        //             error:function ()
        //         {
        //             event.preventDefault();
        //             alert('error');
        //         }
        //     });
        // }
        </script>
        <script type="text/javascript">

            $(document).ready(function() {
                $('#duration').keyup(function(){
                    var fetch_dbid = $(this).val();
                    $.ajax({
                    type:'POST',
                    url :"ins-amt.php?action=userid",
                    data :{userinfo:fetch_dbid},
                    success:function(data){
                    $('.result').val(data);
                    }
                    });
                    

            })});
        </script>
    </body>
</html>