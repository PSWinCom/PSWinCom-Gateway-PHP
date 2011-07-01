<?php
//*******************************************************
// PSWinCom SMS Gateway HTTP simple message interface
// PHP Example without using curl
//*******************************************************
$url = "http://sms.pswin.com/http4sms/send.asp";
$data = "USER=username&PW=password&RCV=4712345678&TXT=Please+send+me+a+copy+of+the+datamodel.+Rgds,+John";

$params = array('http' => array(
              'method' => 'POST',
              'header'=> "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
              'content' => $data
            ));
            
$ctx = stream_context_create($params);
$fp = @fopen($url, 'rb', false, $ctx);

if (!$fp) 
    throw new Exception("Problem with $url, $php_errormsg");

$response = @stream_get_contents($fp);
  
if ($response === false) 
    throw new Exception("Problem reading data from $url, $php_errormsg");

echo $response;
?>