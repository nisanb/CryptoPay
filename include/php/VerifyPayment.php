<?php
require "sqlink.php";

$transactionId = "Empty Value";
$sql = "";


if (isset($_Get['TranactionId'])){
    $transactionId = $_GET['TranactionId'];
    $sql = "SELECT * FROM transactions WHERE id = " .$transactionId;
}

echo "TransactionId => " .$transactionId;

$conn = LindaSQL::getConn();
if (!$result = $conn->query($sql)){
    echo "Could not find deatils of transaction " .$transactionId;
    exit();
}

$row = mysqli_fetch_assoc($result);
$paidAmount = $row["receivedAmount"];
echo $paidAmount;