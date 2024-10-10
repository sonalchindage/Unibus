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
    <title>Hostel Summary</title>
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
                        <h2 class="page-title">Hostel Summary</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Hostel Summary</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Hostel Name</th>
                                            <th>Total Rooms</th>
                                            <th>Hostel Capacity</th>
                                            <th>Occupied Seats</th>
                                            <th>Empty Seats</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // $query = "SELECT 
                                            //             h.hostelName, 
                                            //             h.active,
                                            //             SUM(r.seater) AS HostelCapacity, 
                                            //             SUM(r.occupiedSeats) AS OccupiedRooms, 
                                            //             COUNT(r.room_no) AS TotalRooms, 
                                            //             SUM(r.seater - r.occupiedSeats) AS EmptyRooms 
                                            //           FROM 
                                            //             hostel h
                                            //           LEFT JOIN 
                                            //             rooms r 
                                            //           ON 
                                            //             r.hostelName = h.hostelName
                                            //           GROUP BY 
                                            //             h.hostelName, h.active";
                                            $query = "SELECT 
                                                        h.hostelName, 
                                                        h.active, 
                                                        COUNT(r.hostelName) AS room_count,
                                                        SUM(r.seater) AS HostelCapacity,
                                                        COUNT(rg.roomId) AS Occupied
                                                    FROM 
                                                        hostel h 
                                                    LEFT JOIN 
                                                        rooms r 
                                                    ON 
                                                        r.hostelName = h.hostelName 
                                                    LEFT JOIN 
                                                        registration rg 
                                                    ON 
                                                        r.id = rg.roomId AND rg.status = 'verified' 
                                                    GROUP BY 
                                                        h.hostelName";
                                        $stmt = $mysqli->prepare($query);
                                        $stmt->execute();
                                        $stmt->bind_result($hostelName, $active, $TotalRooms, $HostelCapacity, $OccupiedRooms);

                                        while ($stmt->fetch()) {
                                            $buttonText = $active ? 'Set Inactive' : 'Set Active';
                                            $buttonClass = $active ? 'btn-success' : 'btn-warning';
                                            $totalCapacity = $totalCapacity + $HostelCapacity;
                                            $totalOccupied = $totalOccupied + $OccupiedRooms;
                                            $EmptyRooms = $HostelCapacity - $OccupiedRooms;
                                            $totalRooms = $totalRooms + $TotalRooms;
                                            $totalEmpty = $totalEmpty + $EmptyRooms;
                                            echo "<tr style='font-weight: 700;'>
                                                    <td>$hostelName</td>
                                                    <td>$TotalRooms</td>
                                                    <td>$HostelCapacity</td>
                                                    <td style='color: limegreen'>$OccupiedRooms</td>
                                                    <td style='color: tomato'>$EmptyRooms</td>
                                                    <td>
                                                        <button class='btn $buttonClass toggle-status' data-hostel='$hostelName' data-occupied='$OccupiedRooms' data-active='$active'>$buttonText</button>
                                                    </td>
                                                  </tr>";
                                        }
                                        $stmt->close();
                                        ?>
                                        <tfoot>
                                            <tr>
                                                <th style="font-weight: 700;">Total</th>
                                                <th id="totalRooms"><?php echo $totalRooms?></th>
                                                <th id="totalCapacity"><?php echo $totalCapacity?></th>
                                                <th id="totalOccupied"><?php echo $totalOccupied?></th>
                                                <th id="totalEmpty"><?php echo $totalEmpty?></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
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
    $(document).ready(function() {
        $('.toggle-status').on('click', function() {
            var hostelName = $(this).data('hostel');
            var occupied = $(this).data('occupied');
            var active = $(this).data('active');
            var button = $(this);
            

            if (occupied > 0) {
                alert('Status cannot be changed. There are occupied seats.');
                return;
            }

            $.ajax({
                url: 'toggle_status.php',
                type: 'POST',
                data: {hostelName: hostelName},
                success: function(response) {
                    var newStatus = response == 1 ? 'Set Inactive' : 'Set Active';
                    var newClass = response == 1 ? 'btn-success' : 'btn-warning';
                    button.text(newStatus).removeClass('btn-success btn-warning').addClass(newClass);
                    button.data('active', response);
                    alert('The hostel is now ' + (response == 1 ? 'Active' : 'Inactive') + '.');
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