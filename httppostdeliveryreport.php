<?php
//********************************************************************
// PSWinCom SMS Gateway Delivery Report simple PHP example
// Receiving Delivery Report with HTTP POST from PSWinCom Gateway
// DR details forwarded by email, would normally be stored in database
// Disclaimer: Code initially written in PHP4, but tested on PHP5 
//********************************************************************
$sendernumber = $_POST["SND"];
$receivernumber = $_POST["RCV"];
$state = $_POST["STATE"];
$deliverytime = $_POST["DELIVERYTIME"];
$reference = $_POST["REF"];

$to = "email@example.com";
$subject = "PSWinCom Delivery receipt by HTTP POST in PHP";
 
$body = "Message was sent from mobile number: $sendernumber \n\n";
$body .= "Message was sent to access number: $receivernumber \n\n";
$body .= "Delivery report was generated at: $deliverytime \n\n";
$body .= "Reported final state is: $state \n\n";
$body .= "PSWinCom Gateway reference: $reference \n\n";

mail($to, $subject, $body)
?>