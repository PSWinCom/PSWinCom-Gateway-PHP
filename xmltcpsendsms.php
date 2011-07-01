<?php
//********************************************************************
// PSWinCom SMS Gateway XML interface with PHP
// Sending SMS with XML over TCP
// Disclaimer: Code initially written in PHP4, but tested on PHP5
//********************************************************************

// Writing XML Document
$xml[] = "<?xml version=\"1.0\"?>";
$xml[] = "<!DOCTYPE SESSION SYSTEM \"pswincom_submit.dtd\">";
$xml[] = "<SESSION>";
$xml[] = "<CLIENT>USERNAMEHERE</CLIENT>";
$xml[] = "<PW>PASSWORDHERE</PW>";
$xml[] = "<SD>Customer tag</SD>";
$xml[] = "<MSGLST>";

$xml[] = "<MSG>";
$xml[] = "<TEXT>A message text</TEXT>";
$xml[] = "<RCV>4712345678</RCV>";
$xml[] = "<SND>4741716100</SND>";
$xml[] = "<RCPREQ>Y</RCPREQ>";
$xml[] = "</MSG>";

$xml[] = "<MSG>";
$xml[] = "<TEXT>Another message text</TEXT>";
$xml[] = "<RCV>4747272237</RCV>";
$xml[] = "<SND>PSWinCom</SND>";
$xml[] = "<RCPREQ>Y</RCPREQ>";
$xml[] = "</MSG>";

$xml[] = "</MSGLST>";
$xml[] = "</SESSION>";
$xmldocument = join("\r\n", $xml)."\r\n\r\n";

// Address of the PSWinCom SMS Gateway
$host="sms.pswin.com";
$port = 1111;

// Opens a connection to the gateway
$pswincomsmsgateway = fsockopen ($host, $port, $errno, $errstr);

// Errormessage if connection fails
if (!$pswincomsmsgateway) { $result = "Error: could not open socket connection"; }
else
{
	// Put the xml document to the gateway
	fputs ($pswincomsmsgateway, $xmldocument);

	// Receives XML back from the gateway, stores as $result
	while ( ($response = fgets($pswincomsmsgateway)) != false ) 
	{
  	$response = trim($response);
		global $result;
		$result .= $response;
	}
}
  
// Closes the connection
fclose ($pswincomsmsgateway);

// Prints result to screen
echo $result;
?>