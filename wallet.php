LKT9tDLhqMkfUhKLaj9DjuL2WK3cVhMLPa
addmultisigaddress <nrequired> <'["key","key"]'> [account]
addnode <node> <add|remove|onetry>
addredeemscript <redeemScript> [account]
backupwallet <destination>
checkkernel [{"txid":txid,"vout":n},...] [createblocktemplate=false]
checkwallet
createrawtransaction [{"txid":txid,"vout":n},...] {address:amount,...}
darksend <Lindaaddress> <amount>
decoderawtransaction <hex string>
decodescript <hex string>
dumpprivkey <Lindaaddress>
dumpwallet <filename>
getaccount <Lindaaddress>
getaccountaddress <account>
getaddednodeinfo <dns> [node]
getaddressesbyaccount <account>
getbalance [account] [minconf=1]
getbestblockhash
getblock <hash> [txinfo]
getblockbynumber <number> [txinfo]
getblockcount
getblockhash <index>
getblocktemplate [params]
getcheckpoint
getconnectioncount
getdifficulty
getinfo
getmininginfo
getnettotals
getnewaddress [account]
getnewpubkey [account]
getnewstealthaddress [label]
getpeerinfo
getrawmempool
getrawtransaction <txid> [verbose=0]
getreceivedbyaccount <account> [minconf=1]
getreceivedbyaddress <Lindaaddress> [minconf=1]
getstakesubsidy <hex string>
getstakinginfo
getsubsidy [nTarget]
gettransaction <txid>
getwork [data]
getworkex [data, coinbase]
help [command]
importprivkey <Lindaprivkey> [label] [rescan=true]
importstealthaddress <scan_secret> <spend_secret> [label]
importwallet <filename>
keepass <genkey|init|setpassphrase>
keypoolrefill [new-size]
listaccounts [minconf=1]
listaddressgroupings
listreceivedbyaccount [minconf=1] [includeempty=false]
listreceivedbyaddress [minconf=1] [includeempty=false]
listsinceblock [blockhash] [target-confirmations]
liststealthaddresses [show_secrets=0]
listtransactions [account] [count=10] [from=0]
listunspent [minconf=1] [maxconf=9999999] ["address",...]
makekeypair [prefix]
masternode <start|start-alias|start-many|stop|stop-alias|stop-many|list|list-conf|count|debug|current|winners|genkey|enforce|outputs> [passphrase]
move <fromaccount> <toaccount> <amount> [minconf=1] [comment]
ping
repairwallet
resendtx
reservebalance [<reserve> [amount]]
searchrawtransactions <address> [verbose=1] [skip=0] [count=100]
sendalert <message> <privatekey> <minver> <maxver> <priority> <id> [cancelupto]
sendfrom <fromaccount> <toLindaaddress> <amount> [minconf=1] [comment] [comment-to] [narration]
sendmany <fromaccount> {address:amount,...} [minconf=1] [comment]
sendrawtransaction <hex string>
sendtoaddress <Lindaaddress> <amount> [comment] [comment-to] [narration]
sendtostealthaddress <stealth_address> <amount> [comment] [comment-to] [narration]
setaccount <Lindaaddress> <account>
settxfee <amount>
signmessage <Lindaaddress> <message>
signrawtransaction <hex string> [{"txid":txid,"vout":n,"scriptPubKey":hex,"redeemScript":hex},...] [<privatekey1>,...] [sighashtype="ALL"]
spork <name> [<value>]
stop
submitblock <hex data> [optional-params-obj]
validateaddress <Lindaaddress>
validatepubkey <Lindapubkey>
verifymessage <Lindaaddress> <signature> <message>
walletlock
walletpassphrase <passphrase> <timeout> [stakingonly]
walletpassphrasechange <oldpassphrase> <newpassphrase>