
<?php
	// if(!defined('MYSITE')){
	// 	header('location:../index.php');
	// 	die();
	// }
 if(isset($_SESSION['id']))
{ ?><div class="brand clearfix">
		<a href="#" class="logo" style="font-size:16px; color:#fff !important">UniBus <span class="subTitle" style="font-size: 12px;">| The Campus Connection</span></a>
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<ul class="ts-profile-nav">
			<li class="ts-account">
			<!-- SELECT `settingId`, `option`, `val`, `fromDate`, `toDate`, `enableOp` FROM `settings` WHERE 1 -->
				<?php 
					$option = 'register';
					if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified'){
						$option = 'renew';
					}
					$sQuery= "SELECT * FROM `settings` WHERE option = ?";
					$stmtOption = $mysqli->prepare($sQuery);
					$stmtOption->bind_param('s', $option);
					$stmtOption->execute();

					$result = $stmtOption->get_result();
					$option = $result->fetch_object();

					$stmtOption->close();
					$currYear = date("Y");
					$_SESSION['fromDate'] = $option->fromDate;
					$_SESSION['toDate'] = $option->toDate;
					$_SESSION['option'] = $option->option;
					
					// if ($option) {
					// 	// Process the retrieved settings
					// 	echo "Option: " . $option->option . "<br>";
					// 	echo "Value: " . $option->val . "<br>";
					// 	echo "From Date: " . $option->fromDate . "<br>";
					// 	echo "To Date: " . $option->toDate . "<br>";
					// 	echo "Enabled: " . ($option->enableOp ? 'Yes' : 'No') . "<br>";
					// } else {
					// 	echo "No settings found for the option.";
					// }
				?>
				<a href="#" style="width: 65px; padding: 17px 20px;"><i class="fa fa-bell" style="font-size: x-large;"></i></a>
				<ul>
					<li><a href="">Hostel <?php echo $option->option?> date open: <?php echo $_SESSION['fromDate']; ?> <br> close: <?php echo $_SESSION['toDate']; ?></a></li>
				</ul>
			</li>
			<li class="ts-account">
				<a href="#" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;"><img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt=""> Account <i class="fa fa-angle-down hidden-side"></i></a>
				<ul > 
					<li><a href="my-profile.php">My Account</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
	</div>

<?php
} else { ?>
<div class="brand clearfix">
	<a href="#" class="logo" style="font-size:16px; color:#fff !important">UniBus <span class="subTitle" style="font-size: 12px;">| The Campus Connection</span></a>
	<span class="menu-btn"><i class="fa fa-bars"></i></span>
		
	</div>
	<?php }
	?>