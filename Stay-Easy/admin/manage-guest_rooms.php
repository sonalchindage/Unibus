<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Fetch all guest rooms
$query = "SELECT id, room_no, active FROM guest_rooms";
$result = $mysqli->query($query);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $active = $_POST['active'];

    // // Fetch current status
    // $stmt = $mysqli->prepare("SELECT active FROM guest_rooms WHERE id = ?");
    // $stmt->bind_param('i', $id);
    // $stmt->execute();
    // $stmt->bind_result($active);
    // $stmt->fetch();
    // $stmt->close();

    // Toggle status
    $newStatus = $active ? 0 : 1;

    // Update status in the database
    $update_stmt = $mysqli->prepare("UPDATE guest_rooms SET active = ? WHERE id = ?");
    $update_stmt->bind_param('ii', $newStatus, $id);
    $update_stmt->execute();
    $update_stmt->close();

    // Return the new status
    echo $newStatus;
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
    <title>Manage Guest Rooms</title>
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
                        <h2 class="page-title" >Manage Guest Rooms</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Guest Rooms</div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Room Number</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room-list">
                                    <?php
                                    $cnt = 1;
                                    while($row = $result->fetch_assoc()) {
                                        $buttonText = $row['active'] ? 'Set Inactive' : 'Set Active';
                                        $buttonClass = $row['active'] ? 'btn-success' : 'btn-warning';
                                    ?>
                                    <tr id="room-no<?php echo $row['id']; ?>">
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['room_no']; ?></td>
                                        <td id="status-<?php echo $row['id']; ?>"><?php echo $row['active'] ? 'Yes' : 'No'; ?></td>
                                        <td>
                                            <button class="btn <?php echo $buttonClass; ?> toggle-status" data-room="<?php echo $row['id']; ?>" data-active="<?php echo $row['active']; ?>"><?php echo $buttonText; ?></button>
                                        </td>
                                    </tr>
                                    <?php
                                        $cnt++;
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
    
    <script src="js/jquery.min.js"></script>
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
            var button = $(this);
            var id = button.data('room');
            var active = button.data('active');

            $.ajax({
                url: 'toggle_status.php',
                type: 'POST',
                data: {
                    id: id,
                    active: active
                },
                success: function(response) {
                    // Ensure response is treated as an integer
                    response = parseInt(response);

                    if (response === 1) {
                        button.text('Set Inactive').removeClass('btn-warning').addClass('btn-success');
                        $('#status-' + id).text('Yes');
                        button.data('active', 1);
                    } else {
                        button.text('Set Active').removeClass('btn-success').addClass('btn-warning');
                        $('#status-' + id).text('No');
                        button.data('active', 0);
                    }
                    alert('The room status has been updated.');
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
