<?php
$_USER['key'] = @isset($_POST['key']) ? $_POST['key'] : @$_GET['key'];

if(!isset($_USER['key']))
{
    die('?');   
}

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$width = @isset($_GET['width']) ? $_GET['width'] : "128";
$height = @isset($_GET['height']) ? $_GET['height'] : "32";

?>

<form method="POST" action="">
	<input type="hidden" name="key" value="<?=$_USER['key'];?>" />
	<input type="hidden" name="ipClient" value="<?=$ip;?>" />
	<input type="image" name="submit_blue" value="blue" alt="blue" style="width: <?=$width;?>px; height: <?=$height;?>px;" src="https://www.atvzone.ca/product_images/uploaded_images/paynow.png">
</form>

