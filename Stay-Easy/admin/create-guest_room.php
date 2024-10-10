<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Handle form submission for adding a new guest room
if(isset($_POST['submit'])){
    $room_no = trim($_POST['room_no']);
    $active = isset($_POST['active']) ? 1 : 0;

    $sql="SELECT `id`, `room_no`, `active` FROM `guest_rooms` where room_no=?";
	$stmt1 = $mysqli->prepare($sql);
	$stmt1->bind_param('s',$room_no);
	$stmt1->execute();
	$stmt1->store_result(); 
	$row_cnt=$stmt1->num_rows;;
	$stmt1->close();
	if($row_cnt>0){
		echo"<script>alert('Guest room alreadt exist');</script>";
	}else{
        $query = "INSERT INTO guest_rooms (room_no, active) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $room_no, $active);

        if ($stmt->execute()) {
            echo "<script>alert('New guest room added successfully!');</script>";
        } else {
            echo "<script>alert('Insertion failed. Please try again.');</script>";
        }
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
    <title>Add Guest Room</title>
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
                        <h2 class="page-title">Add New Guest Room</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Add Guest Room</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Room Number<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="room_no" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Active</label>
                                        <div class="col-sm-8">
                                            <input type="checkbox" name="active" checked>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button class="btn btn-primary" name="submit" type="submit">Add Room</button>
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


</html>
