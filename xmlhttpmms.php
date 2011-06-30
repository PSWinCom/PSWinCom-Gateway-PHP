<?php
//***********************************************************************
//* PSWinCom Gateway code sample
//* MO MMS with XML over HTTP POST
//* 
//* Missing features: 
//* Does not take into consideration several files 
//* Does not take into consideration message text in zipped textfile
//* 
//***********************************************************************
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
    $postText = file_get_contents('php://input'); 

// Using SimpleXML to parse incoming request.
// Refer to gateway documentation for info about each xml parameter.
$xml = new SimpleXMLElement($postText);
$sendernumber = $xml->MSG[0]->SND;
$receivernumber = $xml->MSG[0]->RCV;
$messagetext = $xml->MSG[0]->TEXT;
$mmsencodedstring = $xml->MSG[0]->MMSFILE; 

// BASE64 decode the mmsfile string and save it in a temporary zip file
$tempmmsfilename = "mms.zip";
$mmszip = base64_decode($mmsencodedstring);
$filehandle = fopen($tempmmsfilename, 'w') or die("can't open file");
fwrite($filehandle, $mmszip);
fclose($filehandle);

// GET THE NAME OF THE IMAGE FILE IN THE TEMPORARY ZIP
$name = "";
$zip = zip_open($tempmmsfilename);

$entrycounter = 1;
if ($zip)
{ 	
		while ($zip_entry = zip_read($zip))
    {
    	if (strpos(zip_entry_name($zip_entry), ".jpg") > 0)
    		$name = zip_entry_name($zip_entry);
    }
zip_close($zip);
}

// EXTRACT THE FILE WITH THE NAME FOUND
$zip = new ZipArchive;
if ($zip->open($tempmmsfilename))
{
		$zip->extractTo("/", $name);
    $zip->close();
} 

// Response documentation: http://wiki.pswin.com/Receive%20message%20response%20XML.ashx
echo "<?xml version=\"1.0\"?>
<!DOCTYPE MSGLST SYSTEM \"pswincom_receive_response.dtd\"> 
<MSGLST> 
  <MSG> 
    <ID>1</ID> 
    <STATUS>OK</STATUS> 
  </MSG> 
</MSGLST>";

// Sending result as email for demonstration purposes, normally you would store to database
$to = "email@example.com";
$subject = "PSWinCom MO SMS XMLHTTP2 with PHP";
 
$body = "Messaged was received at:" . $datetime=date('y-m-d H:i:s');
$body .= "\n\nMessage was sent from mobile number: $sendernumber";
$body .= "\n\nMessage was received at access number: $receivernumber";
$body .= "\n\nMessage text was: $messagetext";
$body .= "\n\nMMS imagefile filename: $name";
mail($to, $subject, $body);
?>