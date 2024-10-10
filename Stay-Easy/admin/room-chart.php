<?php
session_start();
include('includes/config.php');
include('../includes/enc.php');
include('includes/checklogin.php');
check_login();

$hostel_filter = 'All';

if (isset($_POST['filter'])) {
    // $from_date = $_POST['from_date'];
    // $to_date = $_POST['to_date'];
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
	
	<title>DashBoard</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> -->
</head>

<body>
<?php include("includes/header.php");?>

	<div class="ts-main-content">
		<?php include("includes/sidebar.php");?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Room Details Chart</h2>
						
						<div class="row">
                            <form method="post" action="">
                                <div class="col-md-4">
                                    <label>Status</label>
                                    <select name="hostel_filter" class="form-control">
                                        <option value="All" <?php echo ($hostel_filter == 'All') ? 'selected' : ''; ?>>All</option>
                                        <?php
                                            $query = "SELECT * FROM hostel";
                                            $stmt3 = $mysqli->prepare($query);
                                            $stmt3->execute();
                                            $res=$stmt3->get_result();
                                            while($row=$res->fetch_object())
                                            {?>
                                                <option style="<?php echo $row->active? "":"color : tomato;";?>" value="<?php echo $row->hostelName; ?>" <?php echo ($hostel_filter == $row->hostelName) ? 'selected' : ''; ?> > <?php echo $row->hostelName; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4" style="margin-top: 25px;">
                                    <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
							<div class="col-md-12" style="margin-top: 20px;">
                            <?php
                                // $ret = "SELECT * FROM rooms WHERE 1";
                                // // $stmt = $mysqli->prepare($ret);
                                // // $stmt->execute();
                                // $params = array();
                                // $types = '';

                                // if ($hostel_filter != 'All') {
                                //     $ret .= " AND hostelName = ?";
                                //     $params[] = $hostel_filter;
                                //     $types .= 's';
                                // }
                                // $ret .= "ORDER BY hostelName";
                                // $stmt = $mysqli->prepare($ret);
                                // if (!empty($types)) {
                                //     $stmt->bind_param($types, ...$params);
                                // }
                                // $stmt->execute();
                                // $res = $stmt->get_result();
                                // while ($row = $res->fetch_object()) {
                                    $ret = "SELECT * FROM rooms WHERE 1";

                                    $params = array();
                                    $types = '';
                                    
                                    if ($hostel_filter != 'All') {
                                        $ret .= " AND hostelName = ?";
                                        $params[] = $hostel_filter;
                                        $types .= 's';
                                    }
                                    $ret .= " ORDER BY hostelName";
                                    
                                    $stmt = $mysqli->prepare($ret);
                                    
                                    if ($stmt === false) {
                                        die("Failed to prepare statement: " . $mysqli->error);
                                    }
                                    
                                    if (!empty($types)) {
                                        $stmt->bind_param($types, ...$params);
                                    }
                                    
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    
                                    while ($row = $res->fetch_object()){
                                    $hostel = $row->hostelName;
                                    $room = $row->room_no;
                                ?>
                                    <div>
                                        <b><?php echo $row->hostelName; ?></b>
                                    </div>
                                    <div>
                                        <b><?php echo $row->room_no; ?> :</b>
                                    </div>
                                    <div class="row">
                                        <?php
                                        $ret2 = "SELECT * FROM registration WHERE roomno = ? AND hostelName = ? AND status = 'verified';";
                                        $stmt2 = $mysqli->prepare($ret2);
                                        $stmt2->bind_param('ss', $room, $hostel);
                                        $stmt2->execute();
                                        $regRes = $stmt2->get_result();
                                        $count = 1;
                                        while ($regRow = $regRes->fetch_object()) {
                                        ?>
                                            <div class="col-md-2">
                                                <div class="panel panel-default">
                                                    <div class="panel-body bk-success text-light">
                                                        <div class="stat-panel text-center">											
                                                            <div class="stat-panel-number h4 "><?php echo $regRow->userPrn;?></div>
                                                            <!-- <h5 class="card-title"><?php //echo $regRow->userPrn; ?></h5> -->
                                                            <section class="card-text text-uppercase" style="font-size: 12px;"><?php echo $regRow->firstName." ".$regRow->middleName." ".$regRow->lastName; ?></section>
                                                            <section class="card-text" style="font-size: 12px;"><?php echo $regRow->class; ?></section>
                                                            <section class="card-text" style="font-size: 12px;"><?php echo $regRow->emailid; ?></section>
                                                            <!-- <div class="stat-panel-title text-uppercase">In Process Complaints</div> -->
                                                        </div>
                                                    </div>
                                                    <a href="student-details.php?userPrn=<?php echo encrypt($regRow->userPrn);?>" target="blank" class="block-anchor panel-footer text-center">See All Details &nbsp; <i class="fa fa-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        <?php
                                        $count++;
                                        }
                                        $stmt2->close();
                                        while($count <= $row->seater){

                                        
                                        ?>
                                        <div class="col-md-2">
                                                <div class="panel panel-default">
                                                    <div class="panel-body bk-warning text-light">
                                                        <div class="stat-panel text-center">											
                                                            <div class="stat-panel-number h4 ">Bed <?php echo $count;?></div>
                                                        </div>
                                                    </div>
                                                    <!-- <a href="#" target="blank" class="block-anchor panel-footer text-center">See All &nbsp; <i class="fa fa-arrow-right"></i></a> -->
                                                </div>
                                            </div>
                                            <?php 
                                                $count++; 
                                                }
                                            ?>
                                    </div>
                                    <?php
                                        }
                                        $stmt->close();
                                    ?>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> -->
	<script>
		
	window.onload = function(){
    
		// Line chart from swirlData for dashReport
		var ctx = document.getElementById("dashReport").getContext("2d");
		window.myLine = new Chart(ctx).Line(swirlData, {
			responsive: true,
			scaleShowVerticalLines: false,
			scaleBeginAtZero : true,
			multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
		}); 
		
		// Pie Chart from doughutData
		var doctx = document.getElementById("chart-area3").getContext("2d");
		window.myDoughnut = new Chart(doctx).Pie(doughnutData, {responsive : true});

		// Dougnut Chart from doughnutData
		var doctx = document.getElementById("chart-area4").getContext("2d");
		window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, {responsive : true});

	}
	</script>

</body>

</html>