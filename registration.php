<?php
	// session_start();
	include('includes/config.php');
	include('includes/enc.php');
	include('includes/phpMailer/mail.php');
    $msg = "";

	if(isset($_POST['submit']))
	{
		$clgName=trim($_POST['clgName']);
		$userPrn=trim($_POST['userPrn']);
		$fname=trim($_POST['fname']);
		$mname=trim($_POST['mname']);
		$lname=trim($_POST['lname']);
        $fullName = $fname." ".$mname." ".$lname;
        $ev = 1;
		$gender=$_POST['gender'];
		$contactno=trim($_POST['contact']);
		$emailid=trim($_POST['email']);
		$password=encrypt(trim($_POST['password']));
		$result = "SELECT COUNT(*) FROM userRegistration WHERE email = ? AND emailValidate = 1 AND userPrn IS NULL";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$emailid);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
        if($count>0){

            $query = "UPDATE userRegistration 
                    SET userPrn = ?, clgName = ?, firstName = ?, middleName = ?, lastName = ?, gender = ?, contactNo = ?, password = ? 
                    WHERE email = ? AND emailValidate = ?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssissi', $userPrn, $clgName, $fname, $mname, $lname, $gender, $contactno, $password, $emailid, $ev);


            // $query="insert into  userRegistration(userPrn,clgName,firstName,middleName,lastName,gender,contactNo,email,password,emailValidate) values(?,?,?,?,?,?,?,?,?,?)";
            // $stmt = $mysqli->prepare($query);
            // $rc=$stmt->bind_param('ssssssissi',$userPrn,$clgName,$fname,$mname,$lname,$gender,$contactno,$emailid,$password, $ev);
            if($stmt->execute()){
                echo "<script>window.location.href='index.php';</script>";
            }else{
                echo "<script>alert(' Some Error Occured Try After Some Time');</script>";
            }
			
		}else{
            echo"<script>alert('Registration number and email id already registered / Email is not valid');</script>";
        }
		// print_r($_POST);
        // echo "<script>alert('Some Error Occured Try After Some Time');</script>";
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/regStyle.css">
        <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-3/assets/css/registration-3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Document</title>

        <script type="text/javascript">
            
        </script>
    </head>
    <body>
        <?php include_once('includes/loader.php'); ?>
        <section class=" h-custom gradient-custom-2">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                    <form method="post" action="" name="registration" class="form-horizontal" onsubmit="return valid();">
                        <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                        <div class="card-body p-0">
                            <div class="row g-0">
                            <div class="col-lg-6">
                            
                                <div class="p-5">
                                <h3 class="fw-normal mb-4" style="color: #4835d4;">General Infomation</h3>

                                <div class="mb-2 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <select name="clgName" id="clgName" class="form-control form-control-mg required-field" required> 
                                            <option value="">Select Institute</option>
                                            <?php $query ="SELECT * FROM institute";
                                            $stmt2 = $mysqli->prepare($query);
                                            $stmt2->execute();
                                            $res=$stmt2->get_result();
                                            while($row=$res->fetch_object())
                                            {
                                            ?>
                                            <option value="<?php echo $row->clgName;?>" ><?php echo $row->clgName;?></option>
                                            <?php } ?>
                                        </select>
                                        <label class="form-label" for="clgName">Institute<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 mb-2 pb-2 pb-md-0">

                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" name="userPrn" id="userPrn"  class="form-control form-control-mg required-field" maxlength="14" required="required" onChange="checkRegnoAvailability()">
                                            <label class="form-label" for="userPrn">Registration No (User PRN)<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3 fw-medium">
                                        <span id="user-reg-availability" style="font-size:13px;"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2 pb-2">

                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" pattern="^[A-Za-z]+(\s[A-Za-z]+)*$" 
                                                title="Please enter a valid first name without spaces" 
                                                name="fname" id="fname"  class="form-control form-control-mg required-field" required="required" >
                                            <label class="form-label" for="fname">First name<span class="text-danger">*</span></label>
                                        </div>

                                    </div>

                                    <div class="col-md-4 mb-2 pb-2">

                                        <div data-mdb-input-init class="form-outline">
                                        <input type="text" pattern="([A-Z\s][a-z\s]*|[A-Z\s]+|[a-z\s]+)"
                                                        title="Please enter a valid middle name without spaces"
                                                        name="mname" id="mname" class="form-control form-control-mg required-field">
                                            <label class="form-label" for="mname">Middle name<span class="text-danger">*</span></label>
                                        </div>

                                    </div>
                                    <div class="col-md-4 mb-2 pb-2">

                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" pattern="([A-Z][a-z]*|[A-Z]+|[a-z]+)"
                                            title="Please enter a valid last name without spaces" name="lname" id="lname"  class="form-control form-control-mg required-field" required="required">
                                            <label class="form-label" for="lname">Last name<span class="text-danger">*</span></label>
                                        </div>

                                    </div>
                                </div>

                                <div class="mb-2 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <select data-mdb-select-init name="gender" class="form-control form-control-mg required-field" required="required">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="others">Others</option>
                                        </select>
                                        <label class="form-label" for="clgName">Gender<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-7 mb-2 pb-2">

                                    <div data-mdb-input-init class="form-outline form-white">
                                        <input type="tel" pattern="[0-9]{10}" name="contact" id="contact"  class="form-control form-control-mg required-field" minlength="10" maxlength="10" required="required">
                                        <label class="form-label" for="contact">Phone Number<span class="text-danger">*</span></label>
                                    </div>

                                </div>

                                </div>
                            </div>

                            <div class="col-lg-6 bg-indigo text-white">
                                <div class="p-5">
                                <h3 class="fw-normal mb-4">Email & Password Configuration</h3>

                                <div class="row">
                                    <div class="col-md-8 mb-2">
                                        <div data-mdb-input-init class="form-outline form-white">
                                            <input type="email" name="email" id="email" maxlength="30" class="form-control form-control-mg required-field" onBlur="checkAvailability()" required="required">
                                            <label class="form-label" for="email">Your Email<span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-2 pb-2 fw-medium">
                                        <span id="user-availability-status" style="font-size:13px;"></span>
                                    </div>
                                </div>
                                
                                <div class="mb-2 pb-2">
                                    <div data-mdb-input-init class="form-outline form-white">
                                        <div class="input-group">
                                            <input type="password" name="password" id="password" minlength="8" aria-describedby="basic-addon2"
                                                        title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                                        class="form-control form-control-mg required-field" required="required">
                                            <span class="input-group-text" id="basic-addon2">
                                                <button class="btn" type="button" id="togglePassword" onclick="togglePasswordVisibility('password', this)" style="width: 50px;">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <label class="form-label" for="password">Password<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="mb-2 pb-2">
                                    <div data-mdb-input-init class="form-outline form-white">
                                        <div class="input-group">
                                            <input type="password" name="cpassword" id="cpassword" class="form-control form-control-mg required-field" aria-describedby="basic-addon1" required="required">
                                            <span class="input-group-text" id="basic-addon1">
                                                    <button class="btn" type="button" id="toggleCPassword" onclick="togglePasswordVisibility('cpassword', this)" style="width: 50px;">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                            </span>
                                        </div>
                                        <label class="form-label" for="cpassword">Confirm Password<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="form-check d-flex justify-content-start mb-2 pb-3">
                                    <input class="form-check-input me-3 required-field" type="checkbox" id="terms" onclick="visibleButton()"/>
                                    <label class="form-check-label text-white" for="terms">
                                        I do accept the <a href="" class="text-white"><u>Terms and Conditions</u></a> of your
                                        site.
                                    </label>
                                </div>

                                <div class="h-20 mb-2 pb-3">
                                    <?php 
                                        // echo $msg;
                                        if(!empty($msg)){
                                            echo $msg;
                                        }
                                    ?>
                                </div>

                                <p class="m-0 text-secondary text-light fw-semibold text-end">Already have an account? <a href="index.php" class="link-secondary text-info-emphasis text-decoration-none">Sign in</a></p>

                                <button  type="reset" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-lg" data-mdb-ripple-color="dark">Reset</button>
                                <button  type="submit" name="submit" Value="Register" data-mdb-button-init data-mdb-ripple-init id="btn_sub" class=" btn btn-success btn-lg" data-mdb-ripple-color="dark" onclick="loading()" disabled>Register</button>
                                
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </section>

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
            function valid(){
                const btn_sub = document.getElementById('btn_sub');
                const loaderCotainer = document.querySelector('loader_cotainer');
                loaderCotainer.style.display = "flex";
                btn_sub.disabled = true;
                const requiredFields = document.querySelectorAll('.required-field');

                if(document.registration.password.value!= document.registration.cpassword.value)
                {
                    alert("Password and Re-Type Password Field do not match  !!");
                    document.registration.cpassword.focus();
                    loaderCotainer.style.display = "none";
                    return false;
                }
                requiredFields.forEach(field => {
                    if (field.getAttribute('type') === 'checkbox') {
                        if (!field.checked) {
                            loaderCotainer.style.display = "none";
                            return false;
                        }
                    } else {
                        if (field.value.trim() === '') {
                            loaderCotainer.style.display = "none";
                            return false;
                        }
                    }
                });

                return true;
            }

            document.addEventListener('DOMContentLoaded', function () {
                const btn_sub = document.getElementById('btn_sub');
                const requiredFields = document.querySelectorAll('.required-field');

                function checkFields() {
                    let allFilled = true;
                    requiredFields.forEach(field => {
                        if (field.getAttribute('type') === 'checkbox') {
                            if (!field.checked) {
                                allFilled = false;
                            }
                        } else {
                            if (field.value.trim() === '') {
                                allFilled = false;
                            }
                        }
                    });
                    btn_sub.disabled = !allFilled;
                }

                requiredFields.forEach(field => {
                    field.addEventListener('input', checkFields);
                });
            });

            function checkAvailability() {

                $("#loaderIcon").show();
                jQuery.ajax({
                url: "check_availability.php",
                data:'emailid='+$("#email").val(),
                type: "POST",
                success:function(data){
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
                },
                error:function ()
                {
                event.preventDefault();
                alert('error');
                }
                });
            }

            function checkRegnoAvailability() {

                $("#loaderIcon").show();
                jQuery.ajax({
                url: "check_availability.php",
                data:'userPrn='+$("#userPrn").val(),
                type: "POST",
                success:function(data){
                $("#user-reg-availability").html(data);
                $("#loaderIcon").hide();
                },
                error:function ()
                {
                event.preventDefault();
                alert('error');
                }
                });
            }

        </script>
    </body>
</html>