<?php
/**
 * Linda Wallet API
 * Cron API for analyzing transactions
 * Will ask RPC client to each wallet transactions incoming if a transaction has been sent
**/

require "sqlink.php";
echo "Querying for transactions..<br />";

$array = LindaSQL::getPandingTransactionAccounts();

if(!is_array($array))
    exit();
foreach($array as $trans)
{
    $accountAddress = $trans->creditWalletAccount;
    $received = Linda::getReceivedByAccount($accountAddress);
    $txid = $trans->id;
    echo "Transaction required: " . $trans->requiredAmount ." Received: ". Linda::getReceivedByAccount($accountAddress) ."<br />";
    if($received > 0)
    {
        LindaSQL::updateReceivedTransaction($trans, $received);
    }
}