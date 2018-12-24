<?php
require "sqlink.php";

$txid = @$_GET['txid'];
$sql = "";

$toReturn["type"] = "json";

if(!isset($txid))
{
    $toReturn["txid"]="invalid";
}

$trans = CryptoSQL::getTransactionByTXID($txid);



if($trans == false)
{
    $toReturn["status"] = 0;
}
else{
    $toReturn["status"] = 1;
    $toReturn["details"] = $trans;    
}


$myJSON = CryptoSQL::indent(json_encode($toReturn));

echo $myJSON;



