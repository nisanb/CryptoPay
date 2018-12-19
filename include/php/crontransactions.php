<?php
/**
 * Linda Wallet API
 * Cron API for analyzing transactions
 * Will ask RPC client to each wallet transactions incoming if a transaction has been sent
**/

require "sqlink.php";

$trans = CryptoSQL::getTransactionByAddress($_POST['address']);

$accountAddress = $trans->creditWalletAccount;
$received = Bitcoin::getReceivedByAccount($accountAddress);
$txid = $trans->id;
if($received > 0)
{
    CryptoSQL::updateReceivedTransaction($trans, $received);
}
$toReturn["received"] = Bitcoin::getReceivedByAccount($accountAddress);
$toReturn["required"] = $trans->requiredAmount;
$toReturn["currency"] = CryptoSQL::getCurrency($trans->currency);
$toReturn["status"] = $trans->iStatus;

echo json_encode($toReturn);
