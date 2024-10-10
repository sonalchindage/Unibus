<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/regStyle.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-3/assets/css/registration-3.css">
    <title>Document</title>
</head>
<body>
    <!-- Registration 3 - Bootstrap Brain Component -->
    <div class="login_cont">
        <section class="p-3 p-md-4 p-xl-5">
            <div id="contf">
                <div class="row" style="border-radius: 15px;overflow: hidden;">
                    <!-- Left section -->
                    <div class="col-12 col-md-6 text-dark-emphasis" id="im">
                        <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
                            <h3 class="m-0">Welcome!</h3>
                        </div>
                    </div>

                    <!-- Right section -->
                    <div class="col-12 col-md-6 bsb-tpl-bg-lotion">
                        <div class="p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h2 class="h3">Sign Up</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Enter your email to register</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- Email form -->
                            <form id="email-container" method="post">
                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" maxlength="30" class="form-control form-control-mg" onchange="checkAvailability()" required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="button" id="sendEmailbtn" class="btn bsb-btn-xl btn-primary" onclick="sendEmail()">Sign Up</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- OTP form -->
                            <form id="otp-container" method="post" style="display:none;">
                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="otp" class="form-label">OTP<span class="text-danger">*</span></label>
                                        <input type="text" name="otp" id="otp" maxlength="6" class="form-control form-control-mg" required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="button" id="verifybtn" class="btn bsb-btn-xl btn-primary" name="login" onclick="verifyOtp()" value="login">Verify OTP</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <p class="m-0 text-secondary text-end">Already Verified Email. <a href="registration.php" class="link-primary text-decoration-none">Register Now</a></p>
                                    <span id="user-availability-status" style="font-size:13px;"></span>
                                    <div class="">
                                        <?php 
                                            if(!empty($msg)){
                                                echo $msg;
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="js/jquery.min.js"></script>
    <script>
        // Send email using AJAX
        function sendEmail() {
            const sendEmailBtn = document.getElementById('sendEmailbtn');
            sendEmailBtn.disabled = true;
            sendEmailBtn.innerText = "Sending...";
            const email = document.getElementById('email').value;
            const emailContainer = document.getElementById('email-container');
            const otpContainer = document.getElementById('otp-container');

            $.ajax({
                url: "response.php",
                data: {validate: email},
                type: "POST",
                success: function(data) {
                    if(data.trim() === 'yes'){
                        emailContainer.style.display = 'none';
                        otpContainer.style.display = 'block';
                    } else if(data.trim() === 'no'){
                        $("#user-availability-status").html("Error in verification, try again later.");
                    } else {
                        $("#user-availability-status").html(data);
                    }
                },
                error: function() {
                    alert('Error in sending email. Please try again.');
                }
            });
        }
        
        function verifyOtp() {
            const verifybtn = document.getElementById('verifybtn');
            verifybtn.disabled = true;
            verifybtn.innerText = "Verifying...";
            const email = document.getElementById('email').value;
            const otp = Number(document.getElementById('otp').value);
            $.ajax({
                url: "response.php",
                data: {
                    login: email,
                    otpValidate: otp
                },
                type: "POST",
                success: function(data) {
                    if(data.trim() === 'yes'){
                        $("#user-availability-status").html("Correct OTP");
                        window.location.href='registration.php';
                    } else{
                        verifybtn.disabled = false;
                        verifybtn.innerText = "Verify Again";
                        $("#user-availability-status").html("Wrong OTP");
                    }
                    
                },
                error: function() {
                    alert('Error in sending email. Please try again.');
                }
            });
        }

        // Check email availability using AJAX
        function checkAvailability() {
            $.ajax({
                url: "check_availability.php",
                data: 'emailid=' + $("#email").val(),
                type: "POST",
                success: function(data) {
                    $("#user-availability-status").html(data);
                },
                error: function() {
                    alert('Error checking availability. Please try again.');
                }
            });
        }
    </script>
</body>
</html>
