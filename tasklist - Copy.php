<?php 
//Redirect here if POST is not found.
//if(!$_POST)
//	header("Location: index.php");

session_start();
if( ($_SESSION['username']!=null) && isset($_SESSION['logined']))
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
}
else
{
	session_destroy();
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<head>
<title>Rally Tasks</title>
<link rel="stylesheet" type="text/css" href="modern.css">
<link rel="stylesheet" type="text/css" href="modern-responsive.css">
<script language="javascript" src="jquery.js" type="text/javascript"></script>
<script  language="javascript" type="text/javascript">

$(document).ready(function() {

$('.preloader').hide();


$('.actbutton').click(function() {
	$(this).next().show();
	var actbutton = $(this);
	$.post('ajax-post.php',{"action":"Complete","objectid":$(this).attr('value')}, function(result) {

		if(result=="success")
		{
			actbutton.addClass('bg-color-green');
			actbutton.next().hide();
		}
		else
		{
			alert('Could not mark Task as complete');
			actbutton.next().hide();
		}
	
		});



	});

});

</script>

<?php

/*function fgc($url)
{
global $username;
global $password;

$context = stream_context_create(array(
    'http' => array(
    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    )
));
$data = file_get_contents($url, false, $context);
return $data;
}*/

$context = stream_context_create(array(
    'http' => array(
    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    )
));

$url="https://rally1.rallydev.com/slm/webservice/1.40/task.js?workspace=https://rally1.rallydev.com/slm/webservice/1.40/workspace/294773690&query=".urlencode("((Owner.Name = ".$username.") and (State != Completed))")."&start=1&pagesize=20&fetch=true&order=Rank";
#$url="https://rally1.rallydev.com/slm/webservice/1.40/task.js?workspace=https://rally1.rallydev.com/slm/webservice/1.40/workspace/294773690&query=((Owner.Name%20%3D%20preems%40cisco.com)%20and%20(State%20!%3D%20Completed))&fetch=true&order=Rank&start=1&pagesize=20";

$data = @file_get_contents($url, false, $context);

if($data == false)
{
	header("Location: index.php?reason=auth");
}

$data = json_decode($data);

?>
</head>
<body class = "metrouicss">
<div class="page">
    <div class="page-header">
    <div class="page-header-content">
		<div class="grid">
			<div class="row">
				<div style="float:right;margin-right:25px;margin-top:5px">
					<h6><?php echo $data->{'QueryResult'}->{'Results'}[0]->{'Owner'}->{'_refObjectName'}; ?>
					<a href="logout.php">Log out</a></h6>
				</div>
			</div>
		</div>
    </div>
    </div>
     
    <div class="page-region">
    <div class="page-region-content">

<table class="striped bordered">
<thead>
<tr>
<th>Task ID</th>
<th >Name</th>
<th  style="width:300px">User Story</th>
<th >State</th>
<th >Estimate</th>
<th >To do</th>
<th style="width: 150px;">Actions</th>
</tr>
</thead>

<?php


foreach($data->{'QueryResult'}->{'Results'} as $task)
{
echo '<tr>';

echo '<td>';
echo $task->{'FormattedID'};
echo '</td>';

echo '<td>';
echo $task->{'Name'};
echo '</td>';

echo '<td>';
echo $task->{'WorkProduct'}->{'_refObjectName'};
echo '</td>';

echo '<td>';
echo $task->{'State'};
echo '</td>';

echo '<td>';
echo $task->{'Estimate'};
echo '</td>';

echo '<td>';
echo $task->{'ToDo'};
echo '</td>';


echo '<td>';
echo '<button class="actbutton" value='.$task->{'ObjectID'}.'>Complete</button>';
echo '<img class="preloader" src="images/preloader-w8-cycle-black.gif" width="25px">';
echo '</td>';


echo '</tr>';
}
echo '</table>';

//var_dump($data->{'QueryResult'}->{'Results'}[0]);

?>
 </div>
</div>
</div>
</body>