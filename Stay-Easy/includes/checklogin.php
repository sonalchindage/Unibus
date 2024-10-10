<?php
	function check_login(){

		if(strlen($_SESSION['id']) == 0 || !isset($_SESSION['login'])){	
			
			$_SESSION["id"]="";
			header("Location: logout.php");
		}

	}
	function check_status(){
		// ini_set('session.cookie_lifetime',60);
		// ini_set('session.gc_lifetime',60);

		if($_SESSION['status'] == 'pending'){	
			
			header("Location: dashboard.php");
		}
	}
	function check_logOut(){
		if(isset($_SESSION['login'])){	
			header("Location: dashboard.php");
		}elseif(isset($_SESSION['adminId'])){
			header("Location: admin/dashboard.php");
		}
	}
?>