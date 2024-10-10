<?php 
session_start();
include('includes/config.php');
include('includes/checklogin.php');
include('../includes/phpMailer/mail.php');
check_login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailType = $_POST['email_type'];
    $subject = $emailType == 'remaining' ? '' : $_POST['subject'];
    $body = $_POST['body'];
    $attachment = isset($_FILES['attachment']) ? $_FILES['attachment'] : null;
    $altBody = 'This is a plain-text alternative body';

    if ($emailType == 'single') {
        $recipientEmail = $_POST['recipient_email'];
        $recipientName = $_POST['recipient_name'];

        if($recipientEmail && $recipientName){
            
            $bodyMain = "
            <p>Dear $recipientName,</p>

            <p>$body</p>

            <p>Best regards,<br>
            Stay Easy, Stay Safe, Stay Comfortable</p>
            ";

            $result = attchMailNew($recipientName, $recipientEmail, $subject, $bodyMain, $altBody, $cc = null, $bcc = null, $attachment);
            // $result = attchMail('Recipient Name', 'recipient@example.com', 'Test Subject', 'Test Body', 'Test Alt Body');
            $message = $result['message'];
            // echo $message;
            // echo $result['status'];

            // Escape the message to be used in JavaScript
            $escapedMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            $_POST = [];
            // Output JavaScript alert
            echo "<script>alert('$escapedMessage');</script>";
            // Check if the email was sent successfully
            // if ($result['status']) {
            //     // echo $result['message']; // Success message
            //     echo "<script>alert('$message');</script>";
            // } else {
            //     // echo $result['message']; // Error message
            //     echo "<script>alert($message);</script>";
            // }

            // echo "<script>alert('Email sent');</script>";
        }else{
            $_POST = [];
            echo "<script>alert('No email or name of user found.');</script>";
        }
    } elseif ($emailType == 'multiple') {
        $sql = "SELECT firstName, middleName, lastName, emailid, clgName FROM registration WHERE status = 'verified'";
        $checkStmt = $mysqli->prepare($sql);
        $checkStmt->execute();
        $res = $checkStmt->get_result();

        $subjectMain = "Important Notice : $subject";


        if ($res->num_rows > 0) {
            $totalEmails = $res->num_rows;
            $count = 0;
            while ($row = $res->fetch_assoc()) {
                $recipientName = $row['firstName'];
                $recipientName .= " ".$row['middleName'];
                $recipientName .= " ".$row['lastName'];
                $recipientEmail = $row['emailid'];
                $clgName = $row['clgName'];

                $bodyMain = "
                <p>Dear Students,</p>
                
                <p>We would like to bring the following important information to your attention:</p>
                
                <p>$body</p>
                
                <p>Please make sure to take note of the details mentioned above and act accordingly. Your cooperation is highly appreciated.</p>
                
                <p>If you have any questions or need further clarification, please feel free to reach out to the administration office.</p>
                
                <p>Thank you for your attention.</p>
                
                <p>Best regards,<br>
                $clgName<br>
                Stay Easy, Stay Safe, Stay Comfortable</p>
                ";

                // $mailResult = attchMail($recipientName, $recipientEmail, $subjectMain, $bodyMain, $altBody, $cc = null, $bcc = null, $attachment);

                $result = attchMailNew($recipientName, $recipientEmail, $subjectMain, $bodyMain, $altBody, $cc = null, $bcc = null, $attachment);
                if($result['status']){
                    $count++;
                }
                // $message = $result['message'];
                // $escapedMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
                // echo "<script>alert('$escapedMessage');</script>";
                // $count++;
                // $progress = ($count / $totalEmails) * 100;

                // Update the loader content dynamically
                // echo "<script>document.getElementById('progress-bar').value = $progress; document.getElementById('progress-text').innerText = '$count of $totalEmails emails sent';</script>";

                // ob_flush();
                // flush();
            }
            $_POST = [];
            echo "<script>alert('Emails sent successfully to all verified students $count of $totalEmails.');</script>";
        } else {
            $_POST = [];
            echo "<script>alert('No verified users found.');</script>";
        }
    } elseif ($emailType == 'remaining') {
        $sql = "SELECT fullName, emailid, remainingAmount, clgName FROM report WHERE status = 'pending' AND remainingAmount > 0";
        $checkStmt = $mysqli->prepare($sql);
        $checkStmt->execute();
        $res = $checkStmt->get_result();

        $subject = "Payment Reminder: Remaining Hostel Fee Due";

        if ($res->num_rows > 0) {
            $totalEmails = $res->num_rows;
            $count = 0;
            while ($row = $res->fetch_assoc()) {
                $recipientName = $row['fullName'];
                $recipientEmail = $row['emailid'];
                $remainingAmount = $row['remainingAmount'];
                $clgName = $row['clgName'];

                $bodyMain = "
                <p>Dear $recipientName,</p>
                
                <p>This is a friendly reminder that a balance of <strong>₹$remainingAmount</strong> is still due for your hostel fee. Please make the payment by the due date to avoid any late fees or disruption in your hostel services.</p>
                
                <p><strong>Payment Details:</strong></p>
                <ul>
                    <li><strong>Remaining Amount:</strong> ₹$remainingAmount</li>
                </ul>

                <p><strong>$body</strong></p>

                <p>If you have already made this payment, please disregard this notice. If you have any questions or need assistance, feel free to contact our support team.</p>
                
                <p>Thank you for your prompt attention to this matter.</p>
                
                <p>Best regards,<br>
                $clgName<br>
                Stay Easy, Stay Safe, Stay Comfortable</p>";

                // $mailResult = attchMail($recipientName, $recipientEmail, $subject, $bodyMain, $altBody, $cc = null, $bcc = null, $attachment);
                // $count++;
                $result = attchMailNew($recipientName, $recipientEmail, $subject, $bodyMain, $altBody, $cc = null, $bcc = null, $attachment);
                if($result['status']){
                    $count++;
                }
                // $progress = ($count / $totalEmails) * 100;

                // Update the loader content dynamically
                // echo "<script>document.getElementById('progress-bar').value = $progress; document.getElementById('progress-text').innerText = '$count of $totalEmails emails sent';</script>";

                // ob_flush();
                // flush();
            }
            $_POST = [];
            echo "<script>alert('Emails sent successfully to students $count of $totalEmails with remaining amounts.');</script>";
        } else {
            echo "<script>alert('No students with remaining amounts found.');</script>";
        }
    }
    $_POST = [];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Send Notice</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <?php include('includes/loader.php'); ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Send Notice</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Notice Details</div>
                            <div class="panel-body">
                                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

                                    <div class="form-group">
                                        <label><input type="radio" name="email_type" value="single" checked onclick="toggleEmailType()"> Email Single Student</label>
                                        <label><input type="radio" name="email_type" value="multiple" onclick="toggleEmailType()"> Email Multiple Users</label>
                                        <label><input type="radio" name="email_type" value="remaining" onclick="toggleEmailType()"> Email Remaining Amount Students</label>
                                    </div>

                                    <div id="single-user-form">
                                        <div class="form-group">
                                            <label for="recipient_name">To (Name):</label>
                                            <input type="text" id="recipient_name" name="recipient_name" class="form-control" >
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient_email">To (Email):</label>
                                            <input type="email" id="recipient_email" name="recipient_email" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group" id="subject-group">
                                        <label for="subject">Subject:</label>
                                        <input type="text" id="subject" name="subject" class="form-control" >
                                    </div>

                                    <div class="form-group">
                                        <label for="body">Body:</label>
                                        <textarea id="body" name="body" class="form-control" rows="5" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="attachment">Attachment:</label>
                                        <input type="file" id="attachment" name="attachment" class="form-control">
                                    </div>

                                    <button type="submit" id="sendButton" class="btn btn-primary" >Send</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>

    <script>

        function validateForm() {
            var loader = document.querySelector('.loader_cotainer');
            // console.log(loader);
            loader.style.display = 'flex';
            var emailType = document.querySelector('input[name="email_type"]:checked').value;
            var recipientName = document.getElementById('recipient_name').value.trim();
            var recipientEmail = document.getElementById('recipient_email').value.trim();
            var subject = document.getElementById('subject').value.trim();
            var body = document.getElementById('body').value.trim();

            if (emailType === 'single') {
                if (!recipientName || !recipientEmail || !subject || !body) {
                    alert('Please fill in all fields.');
                    return false;
                }
            } else if (emailType === 'multiple') {
                if (!subject || !body) {
                    alert('Please fill in all fields.');
                    return false;
                }
            } else if (emailType === 'remaining') {
                if (!body) {
                    alert('Please fill in the body text.');
                    return false;
                }
            }

            disableSendButton(); // Disable the button after validation
            return true; // Allow form submission
        }

        function disableSendButton() {
            document.getElementById('sendButton').disabled = true;
            document.getElementById('sendButton').innerText = 'Sending...';
        }

        function toggleEmailType() {
            var emailType = document.querySelector('input[name="email_type"]:checked').value;
            var singleUserForm = document.getElementById('single-user-form');
            var subjectGroup = document.getElementById('subject-group');

            if (emailType === 'single') {
                singleUserForm.style.display = 'block';
                subjectGroup.style.display = 'block';
                document.getElementById('progress-container').style.display = 'none';
            } else if (emailType === 'multiple') {
                singleUserForm.style.display = 'none';
                subjectGroup.style.display = 'block';
                document.getElementById('progress-container').style.display = 'block';
            } else if (emailType === 'remaining') {
                singleUserForm.style.display = 'none';
                subjectGroup.style.display = 'none';
                document.getElementById('progress-container').style.display = 'block';
            }
        }

        function updateProgressBar(count, totalEmails, progress) {
            var progressBar = document.getElementById('progress-bar');
            var progressText = document.getElementById('progress-text');
            progressBar.value = progress;
            progressText.textContent = count + " of " + totalEmails + " emails sent";
        }

        toggleEmailType(); // Call on page load to set the correct form visibility
    </script>

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
</html>