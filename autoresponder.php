<?
//***********************************************************************************
// PSWinCom SMS Gateway PHP Example
// Receive MO SMS by HTTP POST - https://wiki.pswin.com/Gateway%20HTTP%20API.ashx 
// Send MT SMS by XML over TCP - https://wiki.pswin.com/Gateway%20XML%20API.ashx     
//
// This example demonstrates how easy it is to pick values from an incming HTTP POST
// request from the PSWinCom Gateway, and apply these when sending a reply SMS.
//***********************************************************************************

// Receiving request from PSWinCom Gateway
$sendernumber =  $_REQUEST['SND'];
$receivernumber =  $_REQUEST['RCV'];
$messagetext = $_REQUEST['TXT'];


// Writing XML Document
$xml[] = "<?xml version=\"1.0\"?>";
$xml[] = "<!DOCTYPE SESSION SYSTEM \"pswincom_submit.dtd\">";
$xml[] = "<SESSION>";
$xml[] = "<CLIENT>USERNAME HERE</CLIENT>";
$xml[] = "<PW>PASSWORD HERE</PW>";
$xml[] = "<MSGLST>";
$xml[] = "<MSG>";
$xml[] = "<TEXT>The following text was received at " . $receivernumber . ": " . $messagetext . "</TEXT>";
$xml[] = "<RCV>" . $sendernumber . "</RCV>";
$xml[] = "<SND>PSWinCom</SND>";
$xml[] = "</MSG>";
$xml[] = "</MSGLST>";
$xml[] = "</SESSION>";
$xmldocument = join("\r\n", $xml)."\r\n\r\n";

// Address of the PSWinCom SMS Gateway instance you want to use.
// More info about different endpoints is found at wiki.pswin.com
$host = "sms.pswin.com";
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