<?php

include 'KLoggerinit.php';

if($_POST)
{
	
	session_start();
	$_SESSION['logined']=true;
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['password'] = $_POST['password'];
	$log->logInfo('Login.php: Received POST data. Redirecting to index.php');
	header("Location: index.php");	
}
else if( isset($_SESSION['logined'])  && ($_SESSION['username']!=null) )
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$log->logInfo('Login.php: Session found in login.php. Redirecting to index.php');
	header("Location: index.php");
}
else
{
	$log->logInfo('Login.php: Prompted user for credentials');
}
?>
<!DOCTYPE html>
<head>
<title>Rally Tasks</title>

<link rel="stylesheet" type="text/css" href="modern.css">
<link rel="stylesheet" type="text/css" href="modern-responsive.css">
<script language="javascript" src="jquery.js" type="text/javascript"></script>
</head>

<body class = "metrouicss">
	<?php 
		if(isset($_GET['reason']) && ($_GET['reason']=='auth'))
		echo '<div class="error-bar fg-color-white" style="min-height:0"><p>Invalid Credentials. Pls try again</p> </div>';
		?>
<div class="page">

    <div class="page-header">
    <div class="page-header-content">
 
    </div>
    </div>
     
    <div class="page-region">
    <div class="page-region-content">
	<div class="grid">
	<div class=" span5" style="margin: 0 auto">
		<form method="post" action="">
		<div class="row">
			<h3>Login with your rally Credentials</h3>
		</div>
		<div class = "row">
			<div class = "span2">
				<h5>Username:</h5>
			</div>
			<div class = "span3 input-control text">
				<input class="" type="text" name="username"></input>
			</div>
		</div>
		<div class = "row">
			<div class = "span2">
				<h5>Password:</h5>
			</div>
			<div class = "span3 input-control password">
				<input class="" type="password" name="password"></input>
			</div>
		</div>
		<div class = "row">
			<div class = "span3 offset2">
				<input type="submit" value="Login" ></input>
			</div>
		</div>
		</form>
	</div>
	</div>
	
	</div>
</div>
</div>
</body>