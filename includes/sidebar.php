<nav class="ts-sidebar">
	<ul class="ts-sidebar-menu" style="padding-bottom: 50px;">
	
		<li class="ts-label">Main</li>
		<?PHP
			$sQuery= "SELECT status FROM registration WHERE userPrn =?";
			$stmt=$mysqli->prepare($sQuery);
			$stmt->bind_param('s',$_SESSION['userPrn']);
			$stmt->execute();
			$stmt -> bind_result($status);
			$rs=$stmt->fetch();
			$stmt->close();

			$_SESSION['status'] = $status;
			if(isset($_SESSION['id']) && isset($_SESSION['login']))
			{ ?>
			<li><a href="dashboard.php"><i class="fa fa-desktop"></i>Dashboard</a></li>
			<li><a href="book-hostel.php"><i class="fa fa-bed"></i> Book Bus</a></li>
		<?php 
		if($_SESSION['status'] == 'leave' || $_SESSION['status'] == 'rejected'){
		?>
			<li><a href="rebook-hostel.php"><i class="fa fa-bed"></i>Re-Book Bus</a></li>
		<?php 
			}
			if($_SESSION['status'] == 'verified'){
		?>
		
		</li>
			<li><a href="#"><i class="fa fa-credit-card"></i>Payments</a>
			<ul>
				<li><a href="transaction-history.php">Transaction History</a></li>
				<li><a href="payment-form.php">Make Payment</a></li>
			</ul>
		</li>
		<li><a href="payment-manage.php"><i class="fa fa-graduation-cap"></i>Report Details</a></li>

		<li><a href="room-details.php"><i class="fa fa-rss"></i> Bus Details</a></li>
		<li><a href="guest_rooms.php"><i class="fa fa-street-view" ></i> Guest Room</a></li>

		<li><a href="#"><i class="fa fa-building" ></i> Gate Pass Forms</a>
			<ul>
				<li><a href="student-gate.php">Gate Entry Form</a></li>
				<li><a href="student-gate-list.php">Student Entry History</a></li>
				<li><a href="guest_gate.php">Visitor Form</a></li>
				<li><a href="guest-gate-list.php">Visit History</a></li>			
			</ul>
		</li>

		<li><a href="register-complaint.php"><i class="fa fa-exclamation-triangle"></i> Complaint Registration</a></li>
		<li><a href="my-complaints.php"><i class="fa fa-files-o"></i>Registered Complaints </a></li>
		<li><a href="feedback.php"><i class="fa fa-file"></i> Feedback </a></li>
		<?php
			}
		?>
		
		<li><a href="change-password.php"><i class="fa fa-key"></i>Change Password</a></li>
		<li><a href="access-log.php"><i class="fa fa-bug"></i> Access log</a></li>
		<!-- <li><a href="access-log.php"><i class="fa fa-file-o"></i> Access log</a></li> -->
		<li><a href="my-profile.php"><i class="fa fa-user"></i> My Profile </a></li>
		<li><a href="logout.php"><i class="fa fa-arrow-left"></i> Log Out </a></li>
		<?php } else { ?>
		
		<li><a href="registration.php"><i class="fa fa-user-plus"></i>User Registration</a></li>
		<li><a href="index.php"><i class="fa fa-arrow-right"></i>User Login</a></li>
		<li><a href="admin"><i class="fa fa-user"></i>Admin Login</a></li>
		<?php } ?>

	</ul>
</nav>