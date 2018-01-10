<?php
require_once 'jsonrpc.php';

$bitcoin = new jsonRPCClient('http://k0bsP:tTaA4XCUmcZZ867@127.0.0.1:33821/');

echo "<pre>\n";
//$balance = $bitcoin->getbalance("LcTMs6x6XFFfJ2B71RCzDq8DgX53abnXov");
//print_r("Balance: " + $balance);
print_r($bitcoin->getstakinginfo());
print_r($bitcoin->checkwallet()); echo "\n";
echo "</pre>";
/**
 RPC details
 rpcuser=k0bsP
 rpcpassword=tTaA4XCUmcZZ867
 rpcport=33821
 rpcallowip=127.0.0.1
 */
