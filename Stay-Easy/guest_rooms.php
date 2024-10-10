<?php

session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Fetch record where settingId = 3
$enableQuery = "SELECT enableop FROM settings WHERE settingId = 3 LIMIT 1";
$enableResult = $mysqli->query($enableQuery);
$enableRow = $enableResult->fetch_assoc();

if ($enableRow['enableop'] != 1) {
    echo "<script>alert('Bookings are currently closed.'); window.location.href='dashboard.php';</script>";
    exit();
}

if (isset($_POST['filter'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $query = "SELECT * FROM settings WHERE visit_date BETWEEN ? AND ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT * FROM guest_rooms_bookings";
    $result = $mysqli->query($query);
}

// Existing form processing logic...

if (isset($_POST['submit']) && isset($_FILES['image'])) {
    $userPrn = $_SESSION['userPrn'];
    $userPrn = trim($_POST['userPrn']);
    $guest_name = trim($_POST['parent_name']);
    $relation = trim($_POST['relation']);
    $room_no = $_POST['room_no'];
    $reason = trim($_POST['reason']);
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $leave_date = $_POST['leave_date'];
    $leave_time = $_POST['leave_time'];

    // Image file validation and upload logic (same as before)
    $guestId = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

    if ($error === 0) {
        if ($img_size > 5242880) {
            echo "<script>alert('Please upload an image smaller than 5MB');</script>";
        } else {
            $img_ex = pathinfo($guestId, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $guestIdFile = uniqid($userPrn . "-GID-", true) . '.' . $img_ex_lc;
                $img_upload_path = 'images/' . $guestIdFile;

                // Move uploaded file
                if (move_uploaded_file($tempname, $img_upload_path)) {
                    // Check if booking already exists
                    $checkQuery = "SELECT * FROM guest_rooms_bookings WHERE userPrn = ? AND room_no = ?";
                    $checkStmt = $mysqli->prepare($checkQuery);
                    $checkStmt->bind_param('ss', $userPrn, $room_no);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result();
                    if ($result->num_rows > 0) {
                        // Update existing booking
                        $query = "UPDATE guest_rooms_bookings 
                                  SET guest_name = ?, 
                                      relation = ?, 
                                      reason = ?, 
                                      visit_date = ?, 
                                      visit_time = ?, 
                                      leave_date = ?, 
                                      leave_time = ?, 
                                      guestIdFile = ?
                                  WHERE userPrn = ? AND room_no = ?";
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param('ssssssssss', 
                            $guest_name, 
                            $relation, 
                            $reason, 
                            $visit_date, 
                            $visit_time, 
                            $leave_date, 
                            $leave_time, 
                            $guestIdFile, 
                            $userPrn, 
                            $room_no
                        );
                    } else {
                        // Insert new booking
                        $query = "INSERT INTO guest_rooms_bookings ( userPrn, guest_name, relation, room_no, reason, visit_date, visit_time, leave_date, leave_time, guestIdFile ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param('ssssssssss', 
                            $userPrn,
                            $guest_name, 
                            $relation, 
                            $room_no, 
                            $reason, 
                            $visit_date, 
                            $visit_time, 
                            $leave_date, 
                            $leave_time, 
                            $guestIdFile, 
                        
                        );
                    }
                    
                    if($stmt->execute()){
                        echo "<script>alert('Booking details successfully saved!');</script>";
                    } else {
                        echo "<script>alert('Save failed. Please try again.');</script>";
                    }
                } else {
                    echo "<script>alert('Image upload failed.');</script>";
                }
            } else {
                echo "<script>alert('Invalid file format. Only JPG, JPEG, PNG allowed.');</script>";
            }
        }
    }
}

// Fetch available rooms from the guest_rooms table
$roomQuery = "SELECT room_no FROM guest_rooms WHERE active = 'Yes'";
$roomStmt = $mysqli->prepare($roomQuery);
$roomStmt->execute();
$roomResult = $roomStmt->get_result();
?>


<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Guest Room Booking Form</title>
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
                        <h2 class="page-title" >Guest Room Booking Form</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Fill the Room Booking Details for Parent</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                                                        
                                         <?php

                                        $email = $_SESSION['login'];
                                        $userPrn = $_SESSION['userPrn'];

                                        // Prepare the SQL query
                                        $query = "SELECT * FROM registration WHERE userPrn=?";
                                        $stmt = $mysqli->prepare($query);
                                        // Bind the parameters
                                        $stmt->bind_param('s', $userPrn);
                                        // Execute the statement
                                        $stmt->execute();
                                        // Get the result
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_object();
                                        // Close the statement
                                        $stmt->close();
                                        ?>
                                         
                                   
                                    <div class="form-group">
										<label class="col-sm-2 control-label">User Prn <span class="text-danger">*</span> </label>
										<div class="col-sm-8">
										<input type="text" name="userPrn" id="userPrn"  class="form-control" value="<?php echo $row->userPrn;?>" readonly >
										</div>
										</div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Guest Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="parent_name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Relation<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="relation" class="form-control" required>
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
                                    <textarea name="reason" class="form-control limitedText" oninput="validateInput(event)" maxlength="200" required></textarea>
                                    <span class="error text-danger"></span>
                                    <span class="text-muted charCount">200 characters remaining</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                        <label class="col-sm-2 control-label">Visit Date<span class="text-danger" onchange="validatevisitDates()">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" name="visit_date" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-sm-2 control-label">Visit Time<span class="text-danger" onchange="validatevisitDates()">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="visit_time" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Leave Date<span class="text-danger" onchange="validatevisitDates()">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" name="leave_date" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-sm-2 control-label">Leave Time<span class="text-danger" onchange="validatevisitDates()">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="time" name="leave_time" class="form-control" required>
                                        </div>
                                    </div>
                                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Parent Id<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="file" name="image" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-sm-offset-4">
                                        <button class="btn btn-primary" name="submit" type="submit">Submit</button>
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
        function validateInput(event) {
            const text = event.target.value;
            const charCount = document.querySelector('.charCount');
            charCount.innerText = (200 - text.length) + ' characters remaining';
        }
    </script>
    </body>
</html>
