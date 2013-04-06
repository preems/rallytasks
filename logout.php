<?php

include 'KLoggerinit.php';

	session_start();
	
	$log->logInfo('Logout.php: Logging out');
	
	unset($_SESSION['logined']);
	$_SESSION = array();
	session_unset();
	session_destroy();
	header("Location: login.php");
?>