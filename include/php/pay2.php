<?php
/**
 * Payment API Class 
 * @var Ambiguous $_API
 */

if(!isset($_POST['key'])
    || !isset($_POST['domain'])
    || !isset($_POST['ipClient'])
    || !isset($_POST['itemID'])
    || !isset($_POST['itemPrice'])
    || !isset($_POST['itemName']))
{
    die("Payment details were not transfered.");
}

$_API['key']       = $_POST['key'];
$_API['domain']    = $_POST['domain'];
$_API['ipClient']  = $_POST['ipClient'];
$_API['itemID']    = $_POST['itemID'];
$_API['itemName']  = $_POST['itemName'];
$_API['itemPrice'] = $_POST['itemPrice'];

require "sqlink.php";

/**
 * Analyze hidden user fields
 * 
 **/
$getArray = $_POST;
print_r($_POST);
echo "<hr />";
$hiddenFields = "";
foreach (preg_grep("/^forward\_.*/", array_keys($_POST)) as $key)
{
    $hiddenFields .= "<input type=\"hidden\" name=\"$key\" value=\"$_POST[key]\" />\n";
}

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_API['clientIP'] = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_API['clientIP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $_API['clientIP'] = $_SERVER['REMOTE_ADDR'];
}

if(!isset($_API['key']))
{
    die('Error #1511 - Missing API Key.');   
}

if(!LindaSQL::verifyAPIKey($_API['key'], $_API['domain']))
{
    die('Error #1512 - Could not verify domain ownership.');
}

LindaSQL::addTransaction($_API['key'], $_API['clientIP'], $_API['itemID'], "linda", $_API['itemPrice']);



?>

