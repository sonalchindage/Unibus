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
	<title>Transaction Details</title>
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

                            $cid=$_GET['transactionView'];
                            $ret="SELECT t.*,r.reportTotalAmount, r.remainingAmount from transactionhistory t LEFT JOIN report r ON t.userPrn = r.userPrn AND t.academicYear = r.academicYear WHERE t.transactionId = ? ORDER BY t.transactionId;";
                            $stmt= $mysqli->prepare($ret) ;
                            $stmt->bind_param('i',$cid);
                            $stmt->execute() ;
                            $res=$stmt->get_result();
                            $cnt=1;
                            while($row=$res->fetch_object())
                                {
                            ?>
                            <div class="col-md-12">
                                <h2 class="page-title" >#<?php echo $row->fullName;?> Details</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Receipt Token :<?php echo $row->receiptTokenId;?> </div>
                                    <div class="panel-body">
                                    <table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">
                                            
                                    <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>			
                                    <tbody>


                                        <tr>
                                        <td colspan="6" style="text-align:center; color:blue"><h4>Transaction Realted Information</h4></td>
                                        </tr>

                                        <tr>
                                            <th>Full name: </th>
                                            <td><?php echo $row->fullName;?></td>
                                            <th>Student PRN: </th>
                                            <td><?php echo $row->userPrn;?></td>
                                        </tr>
                                        <tr>
                                            <th>Email: </th>
                                            <td><?php echo $row->emailid;?></td>
                                            <th>Contact No: </th>
                                            <td><?php echo $row->contactno;?></td>
                                        </tr>
                                        <tr>
                                            <th>College Name: </th>
                                            <td><?php echo $row->clgName;?></td>
                                            <th>Hostel Name: </th>
                                            <td><?php echo $row->hostelName;?></td>
                                        </tr>
                                        <tr>
                                            <th>Staying in Room: </th>
                                            <td><?php echo $row->roomno;?></td>
                                            <th>Room Fee: </th>
                                            <td><?php echo $row->feespm;?></td>
                                        </tr>
                                        <tr>
                                            <th>Verified Total Amount: </th>
                                            <td><?php echo $row->reportTotalAmount; ?></td>
                                            <th>Remaining Amount: </th>
                                            <td><?php echo $row->remainingAmount; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Payment Date: </th>
                                            <td><?php echo $row->paymentDate;
                                                echo $row->paymentDateCheck ? ' - accepted' : ' - declined'; ?></td>
                                            <th>Receipt No: </th>
                                            <td><?php echo $row->receiptTokenId;
                                                echo $row->receiptTokenidCheck ? ' - accepted' : ' - declined';?></td>
                                        </tr>

                                        <tr>
                                            <th>Paid Amount: </th>
                                            <td><?php echo $row->paidAmount;
                                            echo $row->paidAmountCheck ? ' - accepted' : ' - declined'; ?></td>
                                            <th>For Year: </th>
                                            <td><?php echo $row->academicYear;?></td>
                                        </tr>
                                        <tr>
                                            <th>Payment Type </th>
                                            <td><?php echo $row->payType;
                                            echo $row->payTypeCheck ? ' - accepted' : ' - declined'; ?></td>
                                            <th>File (if any)</th>
                                            <td>
                                                <?php $cdoc=$row->receiptFile;
                                                if($cdoc==''):
                                                    echo "NA";
                                                else: ?>
                                                <a href="./uploads/<?php echo $cdoc;?>" target="blank">File <?php echo $row->receiptFileCheck ? ' - accepted' : ' - declined'; ?></a>
                                                <?php	endif;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Paid Status: </th>
                                            <td><?php echo $row->paidStatus;?></td>
                                            <th>Reciept Uploaded on: </th>
                                            <td><?php echo $row->timeStamp;?></td>
                                        </tr>

                                        <tr>
                                            <th>Payment Status </th>
                                            <td  colspan="3">
                                                <b style="color:<?php echo ($row->status === 'verified') ? 'rgb(106, 221, 106)' : 'tomato'; ?>;">
                                                    <?php
                                                        $cstatus=$row->status;
                                                        if($cstatus==''):
                                                            echo "New";
                                                        else:
                                                        echo $cstatus;
                                                        endif;	
                                                    ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <?php
                                        if(isset($row->comment)){
                                            ?>
                                            <tr>
                                            <th>Remark :</th>
                                            <th colspan="5"><?php echo $row->comment;?></th>
                                            </tr>
                                        <?php 
                                                }
                                            if(isset($row->verifiedBy)){
                                                $stmtAdmin=$mysqli->prepare("SELECT username,email,clgName,adminName FROM admin WHERE adminid =?");
                                                $stmtAdmin->bind_param('i',$row->verifiedBy);
                                                $stmtAdmin->execute();
                                                $stmtAdmin -> bind_result($username,$email,$clgName,$adminName );
                                                $rs=$stmtAdmin->fetch();
                                                $stmtAdmin->close();
                                            
                                            ?>
                                            <tr>
                                            <td colspan="6" style="text-align:center; color:blue">Verified By</td>
                                            </tr>
                                                    <tr>
                                                        <td><b>Admin Email:</b></td>
                                                        <td><?php echo $email;?></td>
                                                        <td><b>Admin Full Name:</b></td>
                                                        <td ><?php echo $adminName;?></td>
                                                    </tr>
                                            <?php
                                            }
                                        ?>
                                            
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
