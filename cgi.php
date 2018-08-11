<?php
/* decide what the content should be up here .... */

/* AJAX check  */
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	/* special ajax here */
	die();
}



if(!isset($_POST['currency'])
    || !isset($_POST['priceUSD'])
    || !isset($_POST['itemName'])
    || !isset($_POST['itemPrice'])
    || !isset($_POST['key']))
{
    $status = "0";
    $message = "Something went wrong!";
}
foreach($_POST as $key=>$value)
{
    $toReturn[$key] = $value;
}
$status = "1";
$message = "yay";

$toReturn["status"] = $status;
$toReturn["body"] = $message;

echo json_encode($toReturn);
?>