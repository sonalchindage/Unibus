<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Initialize variables
$from_date = '';
$to_date = '';

$query = "SELECT * FROM guest_rooms_bookings WHERE 1=1"; // Base query with a true condition

// Check if the filter is applied
if (isset($_POST['filter'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    if (!empty($from_date) && !empty($to_date)) {
        $query .= " AND visit_date BETWEEN ? AND ?";
    }
}

$stmt = $mysqli->prepare($query);

if (!empty($from_date) && !empty($to_date)) {
    $stmt->bind_param("ss", $from_date, $to_date);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $mysqli->error);
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
    <title>Guest Rooms Bookings</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" >Guest Rooms Bookings</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">List of Guest Room Bookings</div>

                            <form style="display:flex; justify-content:center;" method="post" action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php echo $from_date; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php echo $to_date; ?>" class="form-control">
                                    </div>
                                    
                                    <div class="col-md-4" style="margin-top: 25px;">
                                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <div class="panel-body">
                            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User PRN</th>
                                            <th>Guest Name</th>
                                            <th>Relation</th>
                                            <th>Room No</th>
                                            <th>Reason</th>
                                            <th>Visit Date</th>
                                            <th>Visit Time</th>
                                            <th>Leave Date</th>
                                            <th>Leave Time</th>
                                            <th>Guest ID File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['userPrn'] . "</td>";
                                            echo "<td>" . $row['guest_name'] . "</td>";
                                            echo "<td>" . $row['relation'] . "</td>";
                                            echo "<td>" . $row['room_no'] . "</td>";
                                            echo "<td>" . $row['reason'] . "</td>";
                                            echo "<td>" . $row['visit_date'] . "</td>";
                                            echo "<td>" . $row['visit_time'] . "</td>";
                                            echo "<td>" . $row['leave_date'] . "</td>";
                                            echo "<td>" . $row['leave_time'] . "</td>";
                                            echo "<td><a href='../images/" . $row['guestIdFile'] . "' target='_blank'>View</a></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
