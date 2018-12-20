<?php
session_start();

echo "<a href='http://localhost/CryptoSell/api?key=-qPDzCsmEPF-3bjPwY04FV-KO&price=10&currency=linda&itemName=test&itemID=5&my_email=nisan@gmail.com'>click</a>";
/**
 * API Class 
 * @var Ambiguous $_API
 */
require_once "sqlink.php";
$_API['key'] = @isset($_POST['key']) ? $_POST['key'] : @$_GET['key'];

$_API['price'] = @$_GET['price'];
$_API['itemID'] = @$_GET['itemID'];
$_API['itemName'] = @$_GET['itemName'];
$_API['currency'] = @$_GET['currency']; //BTC, LTC, Linda
Bitcoin::getPriceInBTC($_API['price']);
$_API['domain'] = $_SERVER['HTTP_HOST'];


/**
 * Analyze hidden user fields
 * 
 **/
$getArray = explode('&',$_SERVER["QUERY_STRING"]);
$hiddenFields = "";

foreach (preg_grep("/^my\_.*/", $getArray) as $tmpValue)
{
    $vars = explode('=',$tmpValue);
    $key = $vars[0];
    $value = $vars[1];
    $hiddenFields .= "<input type=\"hidden\" name=\"$key\" value=\"$value\" />\n";
}
$_SESSION['fields'] = $hiddenFields;
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_API['clientIP'] = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_API['clientIP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $_API['clientIP'] = $_SERVER['REMOTE_ADDR'];
}

$_USER['domain'] = $_SERVER['HTTP_HOST'];
if(!isset($_API['key']))
{
    die('Error #1511 - Missing API Key.');   
}

if(!CryptoSQL::verifyAPIKey($_API['key'], $_API['domain']))
{
    die('Error #1512 - Could not verify domain ownership.');
}



$width = @isset($_GET['width']) ? $_GET['width'] : "128";
$height = @isset($_GET['height']) ? $_GET['height'] : "32";

?>

<form method="POST" action="pay">
	<input type="hidden" name="key" value="<?=$_API['key'];?>" />
	<input type="hidden" name="domain" value="<?=$_API['domain'];?>" />
	<input type="hidden" name="ipClient" value="<?=$_API['clientIP'];?>" />
	<input type="hidden" name="itemPrice" value="<?=$_API['price'];?>" />
	<input type="hidden" name="itemName" value="<?=$_API['itemName'];?>" />
	<input type="hidden" name="itemID" value="<?=$_API['itemID']?>" />
	<?=$hiddenFields;?>
	%ITEM%
	<!-- frame id="CryptoSell" src="CryptoSell/pay.php?img=1&iw=50&ic=black&bw=100&bc=btn btn-w-m btn-primary&bt=&march=mickey.shalev@gmail.com&wallet=25/-->
	<button class="<?_API['bc'] ?>" style="font-size: 2em; width:<?$_API['bw']?>;">
		<img src="./include/img/iframeImages/<?$_API['img'] ?>.svg" style="width:<?$_API['iw'] ?>; padding-right:10px">
	</button>
	
	
	<input type="image" name="submit_blue" value="blue" alt="blue" style="width: <?=$width;?>px; height: <?=$height;?>px;" src="https://www.atvzone.ca/product_images/uploaded_images/paynow.png">
</form>

