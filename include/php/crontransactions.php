<?php
/**
 * Linda Wallet API
 * Cron API for analyzing transactions
 * Will ask RPC client to each wallet transactions incoming if a transaction has been sent
**/

require "sqlink.php";

$trans = LindaSQL::getTransactionByAddress($_POST['address']);

$accountAddress = $trans->creditWalletAccount;
$received = Linda::getReceivedByAccount($accountAddress);
$txid = $trans->id;
if($received > 0)
{
    LindaSQL::updateReceivedTransaction($trans, $received);
}
$toReturn["received"] = Linda::getReceivedByAccount($accountAddress);
$toReturn["required"] = $trans->requiredAmount;
$toReturn["currency"] = LindaSQL::getCurrency($trans->currency);
$toReturn["status"] = $trans->iStatus;

echo json_encode($toReturn);
