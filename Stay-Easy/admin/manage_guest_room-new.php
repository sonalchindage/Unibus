<?php 
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
    <title>Manage Guest Rooms</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Manage Guest Rooms</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Guest Rooms</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Room Number</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT id, room_no, active FROM guest_rooms";
                                        $stmt = $mysqli->prepare($query);
                                        $stmt->execute();
                                        $stmt->bind_result($id, $room_no, $active);
                                        $cnt = 1;
                                        while ($stmt->fetch()) {
                                            $buttonText = $active ? 'Set Inactive' : 'Set Active';
                                            $buttonClass = $active ? 'btn-success' : 'btn-warning';
                                            echo "<tr style='font-weight: 700;'>
                                                    <td>$cnt</td>
                                                    <td>$room_no</td>
                                                    <td style='color: limegreen'>$active</td>
                                                    <td>
                                                        <button class='btn $buttonClass ' data-id='$id'  data-active='$active' onclick='togleStatus()'>$buttonText</button>
                                                    </td>
                                                  </tr>";
                                            $cnt++;
                                        }
                                        $stmt->close();
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

        function togleStatus (){
            var id = $(this).data('id');
            var button = $(this);

            $.ajax({
                url: 'toggle_status.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    var newStatus = response == 1 ? 'Set Inactive' : 'Set Active';
                    var newClass = response == 1 ? 'btn-success' : 'btn-warning';
                    button.text(newStatus).removeClass('btn-success btn-warning').addClass(newClass);
                    button.data('active', response);
                    alert('The room is now ' + (response == 1 ? 'Active' : 'Inactive') + '.');
                },
                error: function() {
                    alert('Error: Unable to update status.');
                }
            });
        }
    $(document).ready(function() {
        $('.toggle-status').on('click', function() {
            var id = $(this).data('id');
            var button = $(this);

            $.ajax({
                url: 'toggle_status.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    var newStatus = response == 1 ? 'Set Inactive' : 'Set Active';
                    var newClass = response == 1 ? 'btn-success' : 'btn-warning';
                    button.text(newStatus).removeClass('btn-success btn-warning').addClass(newClass);
                    button.data('active', response);
                    alert('The room is now ' + (response == 1 ? 'Active' : 'Inactive') + '.');
                },
                error: function() {
                    alert('Error: Unable to update status.');
                }
            });
        });
    });
    </script>
</body>
</html>