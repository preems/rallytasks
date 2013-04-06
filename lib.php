<?php



function fgc($url)
{

$username = $_POST['username'];
$password = $_POST['password'];

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