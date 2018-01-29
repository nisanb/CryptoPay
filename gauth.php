<?php
require_once './auth/GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();
//$secret = $ga->createSecret();
$secret = "7543NOBMVWV3SMK2";
echo "Secret is: ".$secret."<br /><br />";

$qrCodeUrl = $ga->getQRCodeGoogleUrl('asd', $secret);
echo "<img src=\"".$qrCodeUrl."\"/><br /><br />";


$oneCode = $ga->getCode($secret);
$oneCode = "517470";
echo "Checking Code '$oneCode' and Secret '$secret':<br />";

$checkResult = $ga->verifyCode($secret, $oneCode, 0);    // 2 = 2*30sec clock tolerance
if ($checkResult) {
    echo 'OK';
} else {
    echo 'FAILED';
}
