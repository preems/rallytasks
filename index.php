<?php 



include 'KLoggerinit.php';

session_start();
if( isset($_SESSION['logined'])  && ($_SESSION['username']!=null) )
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$log->logInfo('Index.php: Session found with username: '.$username.'. variables set');
	
	
	//Create context object.
	$context = stream_context_create(array(
    'http' => array(
    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    )
	));
	
	$url="https://rally1.rallydev.com/slm/webservice/1.40/task.js?workspace=https://rally1.rallydev.com/slm/webservice/1.40/workspace/294773690&query=".urlencode("((Owner.Name = ".$username.") and (State != Completed))")."&start=1&pagesize=20&fetch=true&order=Rank";


	$data = @file_get_contents($url, false, $context);

	if($data == false)
	{
		$log->logInfo('Index.php: Authentication failed. Redirecting to login.php?reason=auth');
		header("Location: login.php?reason=auth");
	}
	else
	{
		$log->logInfo('Index.php: Successfully received Task List');
	}

	$data = json_decode($data);
	
}
else
{
	session_destroy();
	$log->logInfo('Index.php: Session not found. Redirecting to login.php');
	header("Location: login.php");
}









?>

<!DOCTYPE html>
<head>
<title>Rally Tasks</title>
<link rel="stylesheet" type="text/css" href="modern.css">
<link rel="stylesheet" type="text/css" href="modern-responsive.css">
<style type="text/css">

.todotext
{
  background-color: #FFFFFF;
    border: 1px solid #BABABA;
    margin-right: 20px;
    min-height: 32px;
	margin-bottom:10px;
    outline: 0 none;
    padding: 4px 6px 6px 5px;
    position: relative;
    width: 40px;


}
.preloader
{
	margin-top:5px;
}

.actbutton , .delbutton
{

}

.todotext:focus
{
	border-color:black;

}
</style>
<script language="javascript" src="jquery.js" type="text/javascript"></script>
<script  language="javascript" type="text/javascript">

$(document).ready(function() {

	$('.preloader').hide();

	//Assigning Handler function to Complete button
	$('.actbutton').click(function() {
	$(this).next().next().show();
	var actbutton = $(this);
	$.post('ajax-post.php',{"action":"Complete","objectid":$(this).attr('value')}, function(result) {

		if(result=="success")
		{
			actbutton.addClass('bg-color-greenDark default');
			actbutton.next().next().hide();
		}
		else
		{
			alert('Could not mark Task as complete');
			actbutton.next().next().hide();
		}
	
		});
	});
	
	//Assigning Handler function to delete button
	$('.delbutton').click(function() {
	$(this).next().show();
	var delbutton = $(this);
	$.post('ajax-delete.php',{"objectid":$(this).attr('value')}, function(result) {  

		if(result=="success")
		{
			delbutton.addClass('bg-color-greenDark default');
			delbutton.next().hide();
			delbutton.parent().parent().hide();
		}
		else
		{
			alert('Could not Delete the task');
			delbutton.next().hide();

		}
	
		});
	});
	
	
	//Assigning the funtion to the 'change' event of the ToDo textbox  //Not complete
	$('.todotext').change(function() {
		//alert('change called');
		$(this).next().show();
		var todotext = $(this);
		$.post('ajax-todo.php',{"todo":$(this).val(),"objectid":$(this).attr('objectid')}, function(result) {
		
			//alert(result);
			if(result=="success")
			{
				
				todotext.next().hide();
			}
			else
			{
				alert('Could not Update the todo');
				todotext.next().hide();
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
<th style="width: 120px;" >To do</th>
<th style="width: 250px;">Actions</th>
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
echo '<input type="text"  class="todotext" objectid='.$task->{'ObjectID'}.' value="'.$task->{'ToDo'}.'"></input>';
echo '<img class="preloader" src="images/preloader-w8-cycle-black.gif" width="25px">';
echo '</td>';


echo '<td>';
echo '<button class="actbutton" value='.$task->{'ObjectID'}.'>Complete</button>';
echo '<button class="delbutton" value='.$task->{'ObjectID'}.'>Delete</button>';
echo '<img class="preloader" src="images/preloader-w8-cycle-black.gif" width="25px">';
echo '</td>';


echo '</tr>';
}
echo '</table>';

//var_dump($data->{'QueryResult'}->{'Results'}[0]);

?>
 </div>
</div>
<div class="nav-bar bg-color-greenDark">
<div class="nav-bar-inner padding10">
<span class="element">
2013, Rally Task Updater © by
<a class="fg-color-white" href="mailto:me@preetham.in">Preetham</a>
</span>
</div>
</div>
</div>
</body>