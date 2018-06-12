<?php
/**
 * Payment API Class 
 * @var Ambiguous $_API
 */

if(!isset($_POST['key'])
    || !isset($_POST['domain'])
    || !isset($_POST['ipClient'])
    || !isset($_POST['itemID']))
{
    die("Payment details were not transferred.");
}

$_API['key']       = $_POST['key'];
$_API['domain']    = $_POST['domain'];
$_API['ipClient']  = $_POST['ipClient'];
$_API['itemID']    = $_POST['itemID'];


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

LindaSQL::addTransaction($_API['key'], $_API['clientIP'], $_API['itemID'], "linda");


$width = @isset($_GET['width']) ? $_GET['width'] : "128";
$height = @isset($_GET['height']) ? $_GET['height'] : "32";

?>

<form method="POST" action="http://localhost/linda_wallet/pay">
	<input type="hidden" name="key" value="<?=$_API['key'];?>" />
	<input type="hidden" name="domain" value="<?=$_API['domain'];?>" />
	<input type="hidden" name="ipClient" value="<?=$_API['clientIP'];?>" />
	<?=$hiddenFields;?>
	<input type="image" name="submit_blue" value="blue" alt="blue" style="width: <?=$width;?>px; height: <?=$height;?>px;" src="https://www.atvzone.ca/product_images/uploaded_images/paynow.png">
</form>

