<?php
require "sqlink.php";

$transactionId = "Empty Value";
$sql = "";
$myObj = $res = new \stdClass();

if (isset($_GET['transID'])){
    $transactionId = $_GET['transID'];
    $sql = "SELECT * FROM transactions WHERE id = " .$transactionId;
}

//echo "TransactionId => " .$transactionId . "</br>";

$conn = LindaSQL::getConn();
if (!$result = $conn->query($sql)){
    echo "Could not find deatils of transaction " .$transactionId;
    exit();
}

$row = mysqli_fetch_assoc($result);
$paidAmount = $row["receivedAmount"];

$myObj->tranaction = $transactionId;
$myObj->marchantid = "xxxxxxxxxxxxx";
$myObj->recieved = $paidAmount;

$myJSON = json_encode($myObj);

echo $myJSON;