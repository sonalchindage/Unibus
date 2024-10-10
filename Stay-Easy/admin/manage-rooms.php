<?php
session_start();
include('includes/config.php');
include('../includes/enc.php');
include('includes/checklogin.php');
check_login();

if (isset($_GET['del'])) {
    $id = intval(decrypt($_GET['del']));
    $adn = "DELETE FROM rooms WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Data Deleted');</script>";
}

$hostel_filter = 'All';
if (isset($_POST['filter'])) {
    $hostel_filter = $_POST['hostel_filter'];
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
    <title>Manage Rooms</title>
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
                        <h2 class="page-title" style="margin-top: 4%">Manage Rooms</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Room Details</div>
                            <form method="post" action="">
                                <div class="row" style="margin: auto;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="hostel_filter">Select Hostel</label>
                                            <select name="hostel_filter" id="hostel_filter" class="form-control">
                                                <option value="All" <?php echo ($hostel_filter == 'All') ? 'selected' : ''; ?>>All</option>
                                                <?php
                                                $query = "SELECT active, hostelName FROM hostel";
                                                $stmt2 = $mysqli->prepare($query);
                                                $stmt2->execute();
                                                $res = $stmt2->get_result();
                                                while ($row = $res->fetch_object()) { ?>
                                                    <option style="<?php echo $row->active? "":"color : tomato;";?>" value="<?php echo $row->hostelName; ?>" <?php echo ($hostel_filter == $row->hostelName) ? 'selected' : ''; ?>><?php echo $row->hostelName; ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Hostel Name</th>
                                            <th>Gender</th>
                                            <th>Room No.</th>
                                            <th>Total Seats</th>
                                            <th>Fees (PM)</th>
                                            <th>Posting Date</th>
                                            <th>Occupants</th>
                                            <th>Available</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "SELECT rm.*, COUNT(r.roomId) AS verified_count FROM registration r RIGHT JOIN rooms rm ON r.roomId = rm.id AND r.status = 'verified' ";
                                    $params = array();
                                    $types = '';

                                    if ($hostel_filter != 'All') {
                                        $query .= " WHERE rm.hostelName = ?";
                                        $params[] = $hostel_filter;
                                        $types .= 's';
                                    }
                                    $query .= " GROUP BY rm.id ORDER BY rm.id";
                                    $stmt = $mysqli->prepare($query);
                                    if (!empty($types)) {
                                        $stmt->bind_param($types, ...$params);
                                    }
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        $available_seats = $row->seater - $row->verified_count;
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row->hostelName; ?></td>
                                            <td><?php echo ucfirst($row->gender); ?></td>
                                            <td><?php echo $row->room_no; ?></td>
                                            <td><?php echo $row->seater; ?></td>
                                            <td><?php echo $row->yearlyFees; ?></td>
                                            <td><?php echo $row->posting_date; ?></td>
                                            <td><?php echo $row->verified_count; ?></td>
                                            <td><?php echo $available_seats; ?></td>
                                            <td>
                                                <a href="edit-room.php?id=<?php echo encrypt($row->id); ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                                <a href="manage-rooms.php?del=<?php echo encrypt($row->id); ?>" onclick="return confirm('Do you want to delete');"><i class="fa fa-close"></i></a>
                                            </td>
                                        </tr>
                                    <?php
                                        $cnt = $cnt + 1;
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

</body>

</html>
