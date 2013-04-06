<?php
//$username = $_POST['username'];
//$password = $_POST['password'];
session_start();

if(!$_POST)
	die('Cannot access the page directly via browser');

$username = $_SESSION['username'];
$password = $_SESSION['password'];
function fgc($url)
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
}
$url = 'https://rally1.rallydev.com/slm/webservice/1.40/task/'.$_POST['objectid'].'.js';

$data = fgc($url);

//Start 

//$data = str_replace("Defined","Completed",$data);

$json = json_decode($data);
$json->{'Task'}->{'ToDo'}=$_POST['todo'];
$data=json_encode($json);


$ch = curl_init($url);
	
if($ch === false)
{
	die('0');
}

curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_HEADER, false);
curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt ($ch, CURLOPT_USERPWD, $username . ":" . $password);  
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($ch));

if( (count($response->{'OperationResult'}->{'Errors'})==0)  && ($response->{'OperationResult'}->{'Object'}->{'ToDo'}==$_POST['todo']))
	echo 'success';
else
	{
		echo '0';
		$log->logInfo('ajax-todo.php: Todo update failed',$response);
	}
	

curl_close($ch);

	?>