<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
/*
// Check if the verify button is clicked
if(isset($_GET['verify']) && $_GET['verify'] == 'true' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE guest_gatepass SET status = 'Verified' WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($stmt->affected_rows > 0) {
        // Success message if the status is updated
        echo "<script>alert('Guest Gate Pass request verified successfully!');window.location.href='admin_visitor_list.php';</script>";
    } else {
        // Error message if the update fails
        echo "<script>alert('Error verifying the request. Please try again.');window.location.href='admin_visitor_list.php';</script>";
    }
}*/
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
    <title>Visiter Records</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.min.js"></script>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Visiter Records</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">List of Visiters</div>
                            <div class="panel-body">
                            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.NO</th>
                                            <th>Guest Name</th>
                                            <th>Guest Id</th>
                                            <th>Guest Count</th>
                                            <th>Student RegNo</th>
                                            <th>Reason</th>
                                            <th>Visit Date</th>
                                            <th>Visit Time</th>
                                            <th>Leave Date</th>
                                            <th>Leave Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM guest_gatepass";
                                        $result = $mysqli->query($query);
                                        if ($result->num_rows > 0) {
                                            $count = 1;
                                            while ($row = $result->fetch_object()) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $count;?></td>
                                                        <td><?php echo $row->guest_name;?></td>
                                                        <td><?php $cdoc=$row->guestIdFile;;
                                                            if($cdoc==''):
                                                                echo "NA";
                                                            else: ?>
                                                            <a href="../images/<?php echo $cdoc;?>" target="blank">File</a>

                                                            <?php	endif; ?>
                                                        </td>
                                                        
                                                        <td><?php echo $row->guestCount;?></td>
                                                        <td><?php echo $row->userPrn;?></td>
                                                        <td><?php echo $row->reason;?></td>
                                                        <td><?php echo $row->visit_date;?></td>
                                                        <td><?php echo $row->visit_time;?></td>
                                                        <td><?php echo $row->leave_date;?></td>
                                                        <td><?php echo $row->leave_time;?></td>
                                    
                                                    </tr>
                                            <?php
                                                $count++;
                                            }
                                            
                                        } else {
                                            echo "<tr><td colspan='10'>No records found</td></tr>";
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
        $(function () {
            $("[data-toggle=tooltip]").tooltip();
        });

    </script>
</body>

</html>
