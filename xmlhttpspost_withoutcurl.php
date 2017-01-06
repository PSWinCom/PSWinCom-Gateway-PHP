<?php
//*******************************************************
// PSWinCom SMS Gateway XML HTTPS POST
// PHP Example without using curl
// Docs: https://wiki.pswin.com/Gateway%20XML%20API.ashx
//*******************************************************

// Please refer to https://wiki.pswin.com for the different gateway endpoint urls
$url = "https://xml.pswin.com";

// Writing XML Document
$xml[] = "<?xml version=\"1.0\"?>";
$xml[] = "<!DOCTYPE SESSION SYSTEM \"pswincom_submit.dtd\">";
$xml[] = "<SESSION>";
$xml[] = "<CLIENT>USERNAMEHERE</CLIENT>";
$xml[] = "<PW>PASSWORDHERE</PW>";
$xml[] = "<SD>gw2xmlhttpspost</SD>";
$xml[] = "<MSGLST>";

$xml[] = "<MSG>";
$xml[] = "<TEXT>A message text</TEXT>";
$xml[] = "<RCV>4712345678</RCV>";
$xml[] = "<SND>4741716100</SND>";
$xml[] = "<RCPREQ>Y</RCPREQ>";
$xml[] = "</MSG>";

$xml[] = "</MSGLST>";
$xml[] = "</SESSION>";
$xmldocument = join("\r\n", $xml)."\r\n\r\n";


$params = array('http' => array(
              'method' => 'POST',
              'header'=> "Content-type: application/xml\r\n" . "Content-Length: " . strlen($xmldocument) . "\r\n",
              'content' => $xmldocument
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
