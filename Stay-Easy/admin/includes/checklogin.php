<?php
function check_login()
{
	// || isset($_SESSION['clgName'])
	if(strlen($_SESSION['adminId']) == 0 || !isset($_SESSION['clgName'])){	
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra="index.php";		
		unset($_SESSION['adminId']);
		header("Location: http://$host/Stay-Easy/$extra");
	}
}
?>