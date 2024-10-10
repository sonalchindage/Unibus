<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
check_status();
if(isset($_POST['submit'])){

    $userPrn = $_SESSION['userPrn'];
    $reason = $_POST['reason'];
    $out_date = $_POST['out_date'];
    $out_time = $_POST['out_time'];
    $in_date = $_POST['in_date'];
    $in_time = $_POST['in_time'];
    $noOfDays = $_POST['noOfDays'];
    
    // INSERT INTO `student_gatepass`(`id`, `userPrn`, `reason`, `out_date`, `out_time`, `in_date`, `in_time`, `noOfDays`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]')
    
    $query = "INSERT INTO student_gatepass (userPrn, reason, out_date, out_time, in_date, in_time, noOfDays) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssssd', $userPrn, $reason, $out_date, $out_time, $in_date, $in_time, $noOfDays);
    // $stmt->execute();
    if($stmt->execute()) {
        echo "<script>alert(' Gate pass request submitted successfully!');</script>";
    } else {
        echo "<script>alert(' Gate pass request failed to submit!');</script>";
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
    <title>Student Gate Pass Form</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Student Gate Pass Form</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Fill the Gate Pass Details</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" >

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Reason<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                        <textarea name="reason" class="form-control limitedText" oninput="validateInput(event)" maxlength="200" required></textarea>
                                        <span class="error text-danger"></span>
                                        <span class="text-muted charCount">200 characters remaining</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Out Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" min="<?php echo date("Y-m-d");?>" name="out_date" id="out_date" class="form-control" required onchange="calculateDays()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Out Time<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="out_time" id="out_time" class="form-control" required onblur="calculateDays()" onchange="validateDates()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">In Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" min="<?php echo date("Y-m-d");?>" name="in_date" id="in_date" class="form-control" required onblur="calculateDays()" onchange="validateDates()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">In Time<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="in_time" id="in_time" class="form-control" required onblur="calculateDays()" onchange="validateDates()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Number of Days</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="number_of_days" name="noOfDays" class="form-control" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button class="btn btn-primary sub" name="submit" type="submit">Submit</button>
                                    </div>
                                </form>
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
        // document.getElementById('limitedText').addEventListener('input', function () {
        //     const textArea = this;
        //     const errorDiv = document.getElementById('error');
        //     const regex = /^[A-Za-z.,\s]*$/; // Only letters, periods, and spaces
        //     const words = textArea.value.trim().split(/\s+/);
        //     const sub = document.getElementById('sub');
        //     sub.disabled = false;
        //     // Check for invalid characters
        //     if (!regex.test(textArea.value)) {
        //         errorDiv.textContent = 'Only letters and periods are allowed.';
        //         sub.disabled = true;
        //         return;
        //     }

        //     // Check for word limit
        //     if (words.length > 50) {
        //         errorDiv.textContent = 'You can only enter up to 50 words.';
        //         sub.disabled = true;
        //         return;
        //     }

        //     errorDiv.textContent = ''; // Clear error message if everything is fine
        // });
    
        // function validateDates() {
        //     const outDate = document.getElementById('out_date').value;
        //     const outTime = document.getElementById('out_time').value;
        //     const inDate = document.getElementById('in_date').value;
        //     const inTime = document.getElementById('in_time').value;

        //     if (outDate && inDate) {
        //         const outDateTime = new Date(`${outDate}T${outTime}`);
        //         const inDateTime = new Date(`${inDate}T${inTime}`);

        //         if (inDateTime < outDateTime) {
        //             alert("In Date and Time cannot be earlier than Out Date and Time.");
        //             document.getElementById('in_date').value = '';
        //             document.getElementById('in_time').value = '';
        //             document.getElementById('number_of_days').value = '';
        //             return;
        //         }
        //     }

        //     calculateDays();
        // }

        // function calculateDays() {
        //     const outDate = document.getElementById('out_date').value;
        //     const outTime = document.getElementById('out_time').value;
        //     const inDate = document.getElementById('in_date').value;
        //     const inTime = document.getElementById('in_time').value;

        //     if (outDate && outTime && inDate && inTime) {
        //         const outDateTime = new Date(`${outDate}T${outTime}`);
        //         const inDateTime = new Date(`${inDate}T${inTime}`);

        //         const differenceInTime = inDateTime - outDateTime;
        //         const differenceInDays = differenceInTime / (1000 * 3600 * 24);

        //         document.getElementById('number_of_days').value = differenceInDays.toFixed(2);
        //     }
        // }
    </script>
</body>

</html>
