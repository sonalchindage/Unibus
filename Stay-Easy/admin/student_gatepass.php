<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
    <title>Gate Pass History</title>
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
                    <div class="col-md-12">
                        <h2 class="page-title">Gate Pass History</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">List of Gate Pass</div>
                            <div class="panel-body">
                            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Registration Number</th>
                                            <th>Reason</th>
                                            <th>Out Date</th>
                                            <th>Out Time</th>
                                            <th>In Date</th>
                                            <th>In Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM student_gatepass";
                                        $result = $mysqli->query($query);
                                        if ($result->num_rows > 0) {
                                            $count = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                        <td>{$count}</td>
                                                        <td>{$row['userPrn']}</td>
                                                        <td>{$row['reason']}</td>
                                                        <td>{$row['out_date']}</td>
                                                        <td>{$row['out_time']}</td>
                                                        <td>{$row['in_date']}</td>
                                                        <td>{$row['in_time']}</td>
                                                    </tr>";
                                                $count++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>No records found</td></tr>";
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

        $(document).ready(function() {
            // Add a verify button dynamically to each cell in the Status column
            $('#dataTables-example tbody').on('click', '.verify-button', function() {
                var id = $(this).data('id');
                // Perform AJAX request to verify the record
                $.ajax({
                    url: 'admin_dashboard.php',
                    type: 'GET',
                    data: { verify: 'true', id: id },
                    success: function(response) {
                        alert('Gate Pass request verified successfully!');
                        window.location.reload(); // Reload the page to update the status
                    },
                    error: function() {
                        alert('Error verifying the request. Please try again.');
                    }
                });
            });
        });
    </script>
</body>

</html>
