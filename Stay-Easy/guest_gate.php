<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
check_status();
if(isset($_POST['submit']) && isset($_FILES['image'])){
    $userPrn = $_SESSION['userPrn'];
    $guest_name = trim($_POST['guest_name']);
    $gcount = $_POST['gcount'];
    $reason = trim($_POST['reason']);
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $leave_date = $_POST['leave_date'];
    $leave_time = $_POST['leave_time'];

    //  image file validation
    $guestId = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

    if ($error === 0) {
        if ($img_size > 5242880) {
            $em = "Sorry, your file is too large.";
            echo "<script>alert(' Please upload an image smaller than 5MB ');</script>";
            // header("Location: index.php?error=$em");
        }else {
            $img_ex = pathinfo($guestId, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png"); 

            if (in_array($img_ex_lc, $allowed_exs)){
                $new_img_name = uniqid($userPrn."-GID-", true).'.'.$img_ex_lc;
                $img_upload_path = 'images/'.$new_img_name;

                $query = "INSERT INTO guest_gatepass (guest_name, guestIdFile, guestCount, userPrn, reason, visit_date, visit_time, leave_date, leave_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('sssssssss', $guest_name, $new_img_name, $gcount, $userPrn, $reason, $visit_date, $visit_time, $leave_date, $leave_time);
                
                // if($stmt->affected_rows > 0) {
                //     echo "<div class='alert alert-success'>Guest Gate Pass request submitted successfully!</div>";
                // } else {
                //     echo "<div class='alert alert-danger'>Error submitting the request. Please try again.</div>";
                // }

                if($stmt->execute() && move_uploaded_file($tempname, $img_upload_path)){
                    echo "<script>alert(' Guest request submitted and image uploaded successfully');</script>";
                }else{
                    echo "<script>alert(' Guest failed to submit');</script>";
                }
            }
        }
    }else{
        echo "<script>alert(' Uploaded file has some error try another ');</script>";
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
    <title>Guest Gate Pass Form</title>
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
                        <h2 class="page-title" style="margin-top:4%">Visitor Gate Pass Form</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Fill the Gate Pass Details for Guest</div>
                            <div class="panel-body">
                                <?php  ?>
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Guest Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                pattern="[A-Za-z\s]+" 
                                                title="Please enter a valid full name with letters and spaces only" 
                                                name="guest_name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Guest Count<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" name="gcount" pattern="[0-9]{2}" min="1" max="20" id="gcount" class="form-control" required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Upload Guest Id<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="file" name="image" accept="image/*" class="form-control" required="required">
                                            <span style="color: red;">Note: Size below 5MB</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Reason<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                        <textarea name="reason" class="form-control limitedText" oninput="validateInput(event)" maxlength="200" required></textarea>
                                        <span class="error text-danger"></span>
                                        <span class="text-muted charCount">200 characters remaining</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Visit Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" min="<?php echo date("Y-m-d");?>" name="visit_date" id="visit_date" class="form-control" onchange="validatevisitDates()" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Visit Time<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="visit_time" id="visit_time" class="form-control" onchange="validatevisitDates()" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Leave Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" min="<?php echo date("Y-m-d");?>" name="leave_date" id="leave_date" class="form-control" onchange="validatevisitDates()" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Leave Time<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="leave_time" id="leave_time" class="form-control" onchange="validatevisitDates()" required>
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
</body>

</html>
