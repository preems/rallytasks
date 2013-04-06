<?php
//$username = $_POST['username'];
//$password = $_POST['password'];

if(!$_POST)
	die('Cannot access the page directly via browser');


session_start();
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

$data = str_replace("Defined","Completed",$data);
$data = str_replace("In-Progress","Completed",$data);

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

if( (count($response->{'OperationResult'}->{'Errors'})==0)  && ($response->{'OperationResult'}->{'Object'}->{'State'}=="Completed"))
	echo 'success';
else
	{
		echo '0';
		$log->logInfo('ajax-post.php: Complete failed',$response);
	}

curl_close($ch);

	?>