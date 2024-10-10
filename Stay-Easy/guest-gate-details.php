<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
check_status();

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
	<title>Guest Details</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">

<script language="javascript" type="text/javascript">
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
 if(popUpWin)
{
if(!popUpWin.closed) popUpWin.close();
}
popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+510+',height='+430+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

</script>

</head>

<body>
	<?php include('includes/header.php');?>

        <div class="ts-main-content">
                <?php include('includes/sidebar.php');?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row" id="print">
                        <?php	

                            $cid=$_GET['guid'];
                            $user=$_SESSION['userPrn'];
                            // $ret="SELECT * FROM guest_gatepass WHERE id =? AND userPrn=?";
                            $ret="SELECT sg.*, r.firstName, r.middleName, r.lastName, r.class, r.hostelName, r.clgName FROM guest_gatepass sg LEFT JOIN registration r ON sg.userPrn = r.userPrn WHERE sg.id = ?";
                            $stmt= $mysqli->prepare($ret) ;
                            $stmt->bind_param('i',$cid);
                            $stmt->execute() ;
                            $res=$stmt->get_result();
                            $cnt=1;
                            while($row=$res->fetch_object())
                                {
// SELECT `id`, `guest_name`, `guestIdFile`, `guestCount`, `userPrn`, `reason`, `visit_date`, `visit_time`, `leave_date`, `leave_time` FROM `guest_gatepass` WHERE 1
							$image = './images/'.$row->guestIdFile;
                            ?>
                            <div class="col-md-12">
                                <h2 class="page-title" style="margin-top:3%"><?php echo $row->guest_name;?> Details</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Hostel :<?php echo $row->hostelName;?> </div>
                                    <div class="panel-body">
                                    <table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">
                                            
                                    <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>			
                                    <tbody>


                                        <tr>
                                        <td colspan="6" style="text-align:center; color:blue"><h4>Visitor Realted Info</h4></td>
                                        </tr>

                                        <tr>
                                            <th>Institute: </th>
                                            <td><?php echo $row->clgName;?></td>
                                            <th>Guest ID File (if any)</th>
                                            <td><?php $cdoc=$row->guestIdFile;
                                            if($cdoc==''):
                                                echo "NA";
                                            else: ?>
                                            <a href="images/<?php echo $cdoc;?>" target="blank">File</a>

                                            <?php	endif;
                                            ?></td>
                                        </tr>

                                        <tr>
                                            <th>Guest Full Name: </th>
                                            <td><?php echo $row->guest_name?></td>
                                            <th>Guest Count: </th>
                                            <td><?php echo $row->guestCount;?></td>
                                        </tr>
                                        <tr>
                                            <th>Full name: </th>
                                            <td><?php echo $row->firstName." ".$row->middleName." ".$row->lastName;?></td>
                                            <th>Student regNo: </th>
                                            <td><?php echo $row->userPrn;?></td>
                                        </tr>
                                        <tr>
                                            <th>Visit Date: </th>
                                            <td><?php echo $row->visit_date;?></td>
                                            <th>Visit Time: </th>
                                            <td><?php echo $row->visit_time;?></td>
                                        </tr>
                                        <tr>
                                            <th>Leave Date: </th>
                                            <td><?php echo $row->leave_date;?></td>
                                            <th>Leave Time: </th>
                                            <td><?php echo $row->leave_time;?></td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Reason: </th>
                                            <td colspan="3"><?php echo $row->reason;?></td>
                                        </tr>
                                            
                                        </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                        <?php
                            $cnt=$cnt+1;
                            } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Take Action</h4>
        </div>
        <form method="post">
            <div class="modal-body">
                <p><input type="text"></p>
                <p><input type="text"></p>
                <p><textarea name="remark" id="remark" placeholder="Remark or Messgae" rows="6" class="form-control"></textarea></p>
                <p><input type="submit" name="submit" Value="Submit" class="btn btn-primary"></p>
            </div>
        </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            function CallPrint(strid) {
                var prtContent = document.getElementById("print");
                var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
            }
        </script>
    </body>

</html>
