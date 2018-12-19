<?php
require_once("sqlink.php");
/* decide what the content should be up here .... */
/* AJAX check  */
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	/* special ajax here */
	//die();
}

$toReturn["status"] = "0";

if(!isset($_POST['currency'])
    || !isset($_POST['itemPrice'])
    || !isset($_POST['itemName'])
    || !isset($_POST['itemPrice'])
    || !isset($_POST['key']))
{
    $toReturn["status"] = "0";
    $toReturn["body"] = "Something went wrong!";
}
foreach($_POST as $key=>$value)
{
    $toReturn[$key] = $value;
}

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_POST['clientIP'] = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_POST['clientIP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $_POST['clientIP'] = $_SERVER['REMOTE_ADDR'];
}

if(!CryptoSQL::verifyAPIKey($_POST['key'], $_POST['domain']))
{
    $toReturn["status"] = "0";
    $toReturn["body"] = "Could not verify domain ownership";
}

$address = CryptoSQL::addTransaction($_POST['key'], $_POST['clientIP'], $_POST['itemID'], $_POST['currency'], $_POST['itemPrice']);

$toReturn["status"] = "1";
$toReturn["body"] = $address;

echo json_encode($toReturn);
?>