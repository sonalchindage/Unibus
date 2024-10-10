<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Handle form submission for adding new guest bookings
if(isset($_POST['submit']) && isset($_FILES['image'])){
   
    $admin = $_SESSION['adminId'];
    $guest_name = trim($_POST['guest_name']);
    $room_no = trim($_POST['room_no']);
    $reason = trim($_POST['reason']);
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $leave_date = $_POST['leave_date'];
    $leave_time = $_POST['leave_time'];

    // Image file validation and upload logic
    $guestIdFile = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

    if ($error === 0) {
        if ($img_size > 5242880) { // 5MB
            echo "<script>alert('Please upload an image smaller than 5MB');</script>";
        } else {
            $img_ex = pathinfo($guestIdFile, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $guestIdFile = uniqid("GID-", true).'.'.$img_ex_lc;
                $img_upload_path = '../images/'.$guestIdFile;

                // Check if images directory exists
                // if (!file_exists('../images')) {
                //     mkdir('../images', 0777, true); // Create the directory if it doesn't exist
                // }

                // Try to move uploaded file
                if (move_uploaded_file($tempname, $img_upload_path)) {
                    // Prepare and bind parameters
                    $query = "INSERT INTO guest_rooms_bookings (userPrn, guest_name, room_no, reason, visit_date, visit_time, leave_date, leave_time, guestIdFile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param('sssssssss', 
                        $admin,
                        $guest_name, 
                        $room_no, 
                        $reason, 
                        $visit_date, 
                        $visit_time, 
                        $leave_date, 
                        $leave_time, 
                        $guestIdFile
                    );

                    if ($stmt->execute()) {
                        echo "<script>alert('New guest booking added successfully!');</script>";
                    } else {
                        echo "<script>alert('Insertion failed. Please try again.');</script>";
                    }
                } else {
                    echo "<script>alert('Failed to move uploaded file. Check directory permissions.');</script>";
                }
            } else {
                echo "<script>alert('Invalid file format. Only JPG, JPEG, PNG allowed.');</script>";
            }
        }
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }
}

// Fetch available rooms from the guest_rooms table
$roomQuery = "SELECT room_no FROM guest_rooms WHERE active = 'Yes'";
$roomStmt = $mysqli->prepare($roomQuery);
$roomStmt->execute();
$roomResult = $roomStmt->get_result();

// Fetch all guest room bookings
// $query = "SELECT id, guest_name, room_no, reason, visit_date, visit_time, leave_date, leave_time, guestIdFile FROM guest_rooms_bookings";
// $result = $mysqli->query($query);
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
    <title>Special Guest Bookings</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
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
                        <h2 class="page-title" >Special Guest Bookings</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Add New Guest Booking</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Guest Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="guest_name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Select Room<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="room_no" class="form-control" required>
                                                <?php while($room = $roomResult->fetch_assoc()) { ?>
                                                    <option value="<?php echo $room['room_no']; ?>"><?php echo $room['room_no']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Reason<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <textarea name="reason" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Visit Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" name="visit_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Visit Time<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="visit_time" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Leave Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" name="leave_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Leave Time<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="leave_time" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Upload Guest ID<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="file" name="image" accept="image/*" class="form-control" required>
                                            <span style="color: red;">Note: Size below 5MB</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button class="btn btn-primary" name="submit" type="submit">Add Booking</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/Chart.min.js"></script>
<script src="js/fileinput.js"></script>
<script src="js/chartData.js"></script>
<script src="js/main.js"></script>
<script>
    function validateInput(event) {
        const text = event.target.value;
        const charCount = document.querySelector('.charCount');
        charCount.innerText = (200 - text.length) + ' characters remaining';
    }
</script>
</html>
