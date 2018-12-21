<?php
session_start();

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



function getButtonCodeToColor($code){
    if ($code == 1) return "btn btn-w-m btn-default";
    else if ($code == 2) return "btn btn-w-m btn-primary";
    else if ($code == 3) return "btn btn-w-m btn-success";
    else if ($code == 4) return "btn btn-w-m btn-info";
    else if ($code == 5) return "btn btn-w-m btn-warning";
    else if ($code == 6) return "btn btn-w-m btn-danger";
}
?>
<!-- bw=100	 	-> button width -->
<!-- bc=3 		-> button color   -->
<!-- bt=asd		-> button text -->
<!-- img=1		-> img name -->
<!-- iw=50		-> image width -->
<!-- ic=black	-> icon color -->
<!-- key=1		-> wallet id -->
<!-- iid=asd	-> item id -->
<!-- in=213		-> item name -->
<!-- prc=1000	-> item price -->


<!-- crnc=Linda	-> currency -->
	<link rel="icon" href="./include/img/linda_icon.png" />
    <link href="./include/css/bootstrap.min.css" rel="stylesheet">
	

<form method="POST" action="pay">
	<input type="hidden" name="key" value="<?=$_GET['key'];?>" />
	<input type="hidden" name="domain" value="<?=$_API['domain'];?>" />
	<input type="hidden" name="ipClient" value="<?=$_API['clientIP'];?>" />
	<input type="hidden" name="itemPrice" value="<?=$_GET['prc'];?>" />
	<input type="hidden" name="itemName" value="<?=$_GET['in'];?>" />
	<input type="hidden" name="itemID" value="<?=$_GET['iid']?>" />
	<?=$hiddenFields;?>
	<!-- frame id="CryptoSell" src="CryptoSell/pay.php?img=1&iw=50&ic=black&bw=100&bc=btn btn-w-m btn-primary&bt=&march=mickey.shalev@gmail.com&wallet=25/-->
	<button class="<?=getButtonCodeToColor($_GET['bc'])?>" style="font-size: 2em; width:<?=$_GET['bw']?>;"><?=$_GET['bt']?>
		<img src="./include/img/iframeImages/<?=$_GET['img'] ?>.svg" style="width:<?=$_GET['iw'] ?>; padding-right:10px">
		<br />
		
		<label style="font-size: 0.3em; padding-top:2px; font-weight: normal;">Powered by CryptoSell
			<img src="./include/img/iframeImages/copyright.png" style="width:15px;">
		</label>
		
	</button>
	
	
	<!--input type="image" name="submit_blue" value="blue" alt="blue" style="width: <?=$width;?>px; height: <?=$height;?>px;" src="https://www.atvzone.ca/product_images/uploaded_images/paynow.png"-->
</form>

