<?php
require "Objects/Transaction.php";
require "Objects/Wallet.php";
require "Objects/Currency.php";

require_once '_jsonrpc2.php';

/**
 * CryptoSell API
 * @sk8r
 * (c) CryptoSell 2018
 */

class Logger
{
    public static function log($message = "")
    {
        
        $date = date("d/m/Y H:i:s");
        $file = Logger::getLogFile(GlobalParams::$logger);
        @file_put_contents($file, $date."\t".debug_backtrace()[2]['function']."->".debug_backtrace()[1]['function']."\t:\t".$message."\n", FILE_APPEND | LOCK_EX);
    }
    
    public static function getLogFile($fileName)
    {
        $containing_dir = basename(dirname(__FILE__));
        if($containing_dir == "php")
        {
            $fileName = "../../".$fileName;
        } 
        
        return $fileName;
    }
}

class GlobalParams
{

    public static $logger = "debug.log";
    
    public static $walletInfoFile = "info.wallet";
    
    // local sql parms
    public static $server = "localhost";

    public static $user = "root";

    public static $pass = "";

    public static $db = "CryptoSell";
    
    // renote ip
    public static $SERVER_IP = "40.87.133.89";
}

class Bitcoin
{

    private static $_CONN;

    /**
     * Will return the username by an email
     *
     * @param unknown $email            
     * @return string
     */
    public static function getEmailPrefix($email)
    {
        $toReturn = substr($email, 0, strpos($email, "@"));
        return $toReturn;
    }

    /**
     * Grants access to the current RPC Connection
     *
     * @return jsonRPCClient
     */
    public static function RPC($currency = "Linda")
    {
        
        $currency = CryptoSQL::getCurrency($currency);
        $id = $currency["id"];

        if (! isset(self::$_CONN[$id]) || self::$_CONN[$id] == null) {
            self::$_CONN[$id] = new jsonRPCClient($currency["rpcUser"], $currency["rpcPass"], $currency["rpcIP"], $currency["rpcPort"]);
        }
        return self::$_CONN[$id];
    }

    /**
     * Returns transactions associated by an account
     *
     * @param unknown $account            
     * @return unknown
     */
    public static function getTransactionsByAccount($account)
    {
        $toReturn = array();
        Logger::log("Returning transactions by account ".$account);
        
        foreach (self::RPC()->listtransactions($account, 99999999999) as $trans) {
            if (self::isValidAddress($trans["address"])) {
                array_push($toReturn, $trans);
            }
        }
        
        return $toReturn;
    }

    /**
     * Returns TRUE if a wallet is valid
     *
     * @param unknown $id            
     * @return number
     */
    public static function isValidWalletID($id)
    {
        Logger::log("Checking if ".$id." is a valid wallet");
        /**
         * Temporary fix
         */
        if ($id == "skin-config.html") {
            die();
        }
        // if (! preg_match("/^[0-9]{0}$/", $id))
        // throw new Exception("Invalid Wallet Address supplied - " . $id);
        
        return true;
    }

    /**
     * Returns if a given string consists only from chars and numbers
     *
     * @param unknown $str            
     * @return number
     */
    public static function validateStringNumber($str, $includeDots = false)
    {
        return $includeDots == false ? preg_match("/^[a-zA-Z0-9 ]+$/", $str) : preg_match("/^[a-zA-Z0-9 .]+$/", $str);
    }

    public static function RandomString($amount = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = "";
        for ($i = 0; $i < $amount; $i ++) {
            if ($i % 10 == 0)
                $randstring .= "-";
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

    /**
     * Sends cash to a wallet
     *
     * @param unknown $from            
     * @param unknown $to            
     * @param unknown $amount            
     * @param real $fee            
     */
    public static function sendCash($currency, $from, $to, $amount, $fee = 0.0001)
    {
        // Validate wallet addresses
        if (! self::isValidAddress($to)) {
            throw new Exception("Invalid address given -> " . $to);
        }
      
        if(!CryptoSQL::verifyOwner($_SESSION['UserID'], $from))
        {
           throw new Exception("Don't steal! :(");
        }
        
        if ($amount <= 0 || $fee <= 0) {
            throw new Exception("Transaction amounts cannot be negative.");
        }

        $balance = CryptoSQL::getBalanceByWallet($from, $currency);
        $totalAmount = $amount + $fee;
      
        if($balance < $totalAmount)
        {
            throw new Exception("Insufficient balance!");
        }
    

        if(intval($currency) == 0)
        {
            $currency = CryptoSQL::getCurrency($currency)["id"];
        }
        $conn = CryptoSQL::getConn();
        $sql = "update userbalances set balance=balance-".$totalAmount." where walletID=".$from." and currencyID=".$currency;
        echo $sql;
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not update user balance.");
        }
        
        // Send the real transaction
        $result = Bitcoin::RPC()->sendtoaddress($to, $amount);
     
        
    }

    /**
     * TRUE only if an address is valid
     *
     * @param unknown $addr            
     * @return boolean
     */
    public static function isValidAddress($addr = NULL)
    {
        Logger::log("Checking if given address " . $addr . " is valid");
        if (is_null($addr) || ! Bitcoin::isValidWalletID($addr))
            return false;
        
        return self::RPC()->validateaddress($addr)["isvalid"];
    }

    /**
     * Returns amount of coins received by an account
     * 
     * @param unknown $account            
     * @return unknown
     */
    public static function getReceivedByAccount($account)
    {
        return self::RPC()->getreceivedbyaccount($account);
    }

    /**
     * Retreive general wallet information
     */
    public static function getCurrencyInformation()
    {
        
        $timeout = 60 * 5; // 5 minutes
        $walletFile = Logger::getLogFile(GlobalParams::$walletInfoFile);
        $fileUpdatedTime = (time() - @filemtime($walletFile)) . "seconds ago";
        
        // JSON already created
        if ($fileUpdatedTime > $timeout) {
            // Create new JSON
            $fp = fopen($walletFile, 'w');
            
            $currencies = CryptoSQL::getCurrencies();
            $currArray = "";
            foreach ($currencies as $curr) {
                // Get price
                $fp2 = strtolower(file_get_contents($curr->cmc));
                $currArray .= strtoupper($curr->currencyPair) . ",";
                $jsonObject = json_decode($fp2)->data->quote;
                $data[strtolower($curr->currencyPair)] = (($jsonObject));
            }
            
            $currArray .= "USD";
            
            // One more for USD
            $url = "https://sandbox-api.coinmarketcap.com/v1/tools/price-conversion?CMC_PRO_API_KEY=6b5e103d-6f52-4b0f-a887-58c4c3a39f7b&amount=1&symbol=USD&convert=".$currArray;
            $fp2 = strtolower(file_get_contents($url));
            $jsonObject = json_decode($fp2)->data->quote;
            $data["usd"] = $jsonObject;
            
            
            
            $jsonData = (json_encode($data));
            fwrite($fp, CryptoSQL::indent($jsonData));
            fclose($fp);
        }
        
        $fp = file_get_contents($walletFile);
        $arr = json_decode($fp, true);
        return $arr;
    }

    public static function bd_nice_number($n)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));
        
        // is this a number?
        if (! is_numeric($n))
            return false;
            
            // now filter it;
        if ($n > 1000000000000)
            return round(($n / 1000000000000), 1) . ' trillion';
        else 
            if ($n > 1000000000)
                return round(($n / 1000000000), 1) . ' billion';
            else 
                if ($n > 1000000)
                    return round(($n / 1000000), 1) . ' million';
                else 
                    if ($n > 1000)
                        return round(($n / 1000), 1) . ' thousand';
        
        return number_format($n);
    }

    public static function getGUID()
    {
        mt_srand((double) microtime() * 10000); // optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = chr(123) . // "{"
substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125); // "}"
        return $uuid;
    }
}

class CryptoSQL
{

    private static $server = "localhost";

    private static $user = "root";

    private static $pass = "";

    private static $db = "CryptoSell";

    private static $timeout = 31;

    /**
     * Verifies if a given key and domain belong to an existing wallet
     * 
     * @param unknown $key            
     * @param unknown $domain            
     * @throws Exception
     * @return boolean
     */
    public static function verifyAPIKey($key, $domain)
    {
        $key = CryptoSQL::trim_where($key);
        $domain = CryptoSQL::trim_where($domain);
        $conn = CryptoSQL::getConn();
        $sql = "SELECT count(*) as num_results FROM wallets WHERE walletAPI in (\"{$key}\") AND domain like (\"%{$domain}%\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive API key information.");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        return $row["num_results"] > 0;
    }

    public static function updateReceivedTransaction($trans, $received)
    {
        $conn = CryptoSQL::getConn();
        
        // Update transaction object
        $trans = CryptoSQL::getTransactionByTXID($trans->id);
        Logger::log("Checking transaction " . $trans->id . " received " . $received);
        if ($trans->requiredAmount <= $received) {
            // Transaction fully received
            Logger::log("Updating transaction " . $trans ." - Fully received!");
            if($trans->iStatus == 2)
                return;
            $sql = "UPDATE transactions set istatus = 2, receivedamount=requiredamount where txid in (\"{$trans->id}\")";
            if (! $result = $conn->query($sql)) {
                // Oh no! The query failed.
                throw new Exception("Could not update transaction " . $trans->id);
            }
        } else {
            // Partial Payment
            Logger::log("Updating transaction " . $trans . " - Partially received!");
            $sql = "UPDATE transactions set iStatus = 1, receivedAmount = {$received} where txid in (\"{$trans->id}\")";
            if (! $result = $conn->query($sql)) {
                // Oh no! The query failed.
                throw new Exception("Could not update transaction " . $trans->id);
            }
            return;
        }
        
        if(intval($trans->currency) == 0)
        {
            $trans->currency = CryptoSQL::getCurrency($trans->currency)["id"];
        }
        
        
        $sql = "SELECT count(*) as num_results from userbalances where walletID in (\"{$trans->creditWallet}\") AND currencyID = {$trans->currency}";
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not query userbalances for wallet " . $trans->creditWallet . " - " . $sql);
        }
        
        $row = mysqli_fetch_assoc($result);
        
        $userID = CryptoSQL::getUserByWallet($trans->creditWallet);
        
        // Transaction was already added -> do attempt to add again
        if ($row["num_results"] == 0) {
            $sql = "INSERT INTO userbalances values (\"{$userID}\", {$trans->creditWallet}, {$trans->currency}, 0)";
            if (! $result = $conn->query($sql)) {
                // Oh no! The query failed.
                throw new Exception("Could not insert new row to userbalances " . $trans->creditWallet . " " . $trans->currency);
            }
        }
        Logger::log("Updating wallet " . $trans->creditWallet . " with + " . $trans->requiredAmount);
        $sql = "UPDATE userbalances set balance=balance + {$trans->requiredAmount} where walletID = {$trans->creditWallet} AND currencyID = {$trans->currency}";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not update userbalances " . $trans->creditWallet . " " . $trans->currency . " " . $trans->requiredAmount);
        }
        $conn->close();
    }

    public static function getUserByWallet($walletID)
    {
        $walletID = CryptoSQL::trim_where($walletID);
        $conn = CryptoSQL::getConn();
        
        $sql = "select account from wallets where id = {$walletID}";
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not retreive transaction information.");
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        $conn->close();
        return $row["account"];
    }
    
    public static function getTransactionByTXID($txid)
    {
        $txid = CryptoSQL::trim_where($txid);
        $conn = CryptoSQL::getConn();
        
        $sql = "select T.*, C.currencyName from transactions as T inner join currencies as C on T.currency=C.id where txid in (\"{$txid}\")";
        if (! $result = $conn->query($sql))
        {
            throw new Exception("Could not retreive transaction information (txid: ".$txid.").");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        if(@count($row) == 0)
        {
           // No rows returned - tx doesn't exist
           return false;
        }
        $conn->close();
        return new Transaction($row);
    }
    
    
    public static function getTransactionByAddress($address)
    {
        $address = CryptoSQL::trim_where($address);
        $conn = CryptoSQL::getConn();
        /**
         * iStatus
         * 0 = Pending Transaction
         * 1 = Partly Received
         * 2 = Successfully accounted for
         */
        $sql = "select T.*, C.currencyName from transactions as T inner join currencies as C on T.currency=C.id where T.creditWalletAddress in (\"{$address}\")";
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not retreive transaction information.");
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        $conn->close();
        return new Transaction($row);
    }

    public static function getPandingTransactionAccounts()
    {
        $conn = CryptoSQL::getConn();
        
        /**
         * iStatus
         * 0 = Pending Transaction
         * 1 = Partly Received
         * 2 = Successfully accounted for
         */
        $sql = "select * from transactions where istatus != 2";
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not retreive transaction information.");
            exit();
        }
        $arr = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, new Transaction($row));
        }
        
        return $arr;
    }

    public static function generateTXID()
    {
        do
        {
            $tx = Bitcoin::RandomString(35);
            $verify = CryptoSQL::getTransactionByTXID($tx);
        } while ($verify);
        
        return $tx;
        
    }
    /**
     * Attempts to add a transaction to the database
     * Also generates a new wallet receive address
     * 
     * @param unknown $key            
     * @param unknown $clientIP            
     * @param unknown $itemID            
     * @param unknown $currency            
     * @throws Exception
     */
    public static function addTransaction($key, $clientIP, $itemID, $currency, $price)
    {
        // Verify inputs
        $txid = CryptoSQL::generateTXID();
        $key = CryptoSQL::trim_where($key);
        $clientIP = CryptoSQL::trim_where($clientIP);
        $itemID = intval($itemID);
        $currency = CryptoSQL::getCurrency(CryptoSQL::trim_where($currency))["id"];
        
        $conn = CryptoSQL::getConn();
        $sql = "SELECT creditWalletAddress from transactions where iStatus!=2 AND 
                clientIP in (\"{$clientIP}\") AND
                itemID = {$itemID}
                AND currency = {$currency}";

        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive transaction information." + $conn->error);
            exit();
        }
 
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) > 0) {
            return $row["creditWalletAddress"];
        }
        
        // Get wallet ID via item ID
        $sql = "select id from wallets where walletAPI in (\"{$key}\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("API Key is wrong");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        $walletID = $row["id"];
        
        // Generate wallet address
        $creditWalletAccount = "V2_" . time() . Bitcoin::RandomString();
        $creditWalletAddress = Bitcoin::RPC($currency)->getnewaddress($creditWalletAccount);

        // Attempt to add the transaction to the database
        $sql = "insert into transactions (txid, istatus, creditWallet, creditWalletAccount, creditWalletAddress, clientIP, requiredAmount, itemID, currency)
                VALUES (\"{$txid}\", 0, {$walletID}, \"{$creditWalletAccount}\", \"{$creditWalletAddress}\", \"{$clientIP}\", {$price}, {$itemID}, {$currency})";

         if (! $result = $conn->query($sql)) {
            throw new Exception("Could not insert a new transaction information.");
            exit();
        } 
        
        $conn->close();
        return $creditWalletAddress;
    }

    public static function getUserBalancesByAccount($email)
    {
        $balances["BTC"] = 0;
        $balances["Linda"] = 0;
        
        $email = self::trim_where($email);
        
        $sql = "SELECT c.currencyPair as currency, sum(ub.balance) as sum FROM userbalances as ub inner join currencies as c on ub.currencyID = c.id where ub.user in (\"{$email}\")";
        if (! $result = CryptoSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account balances.<br />" . CryptoSQL::getConn()->error);
        }
        
        while ($row = mysqli_fetch_assoc($result)) {
            $balances[$row["currency"]] = $row["sum"];
        }
        return $balances;
    }
    
    /**
     * Returns balance by a given account
     *
     * @param unknown $account            
     * @return number
     */
    public static function getWalletsByAccount($email)
    {
        $wallets = array();
        $email = self::trim_where($email);
        
        $sql = "SELECT * FROM wallets where account=\"$email\"";
        if (! $result = CryptoSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.<br />" . CryptoSQL::getConn()->error);
        }
        
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($wallets, new Wallet($row));
        }
        return $wallets;
    }

    public static function getCurrency($currency)
    {
        $currency = CryptoSQL::trim_where($currency);
        $conn = CryptoSQL::getConn();
        if(intval($currency) > 0)
        {
            $sql = "select * from currencies where id = " . $currency;
        } else {
            $sql = "select * from currencies where currencyPair in (\"{$currency}\") or currencyName in (\"{$currency}\")";
        }
        
        
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not query currencies - " . $conn->error);
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    /**
     * Check if user submitted an action in the last 31 seconds
     *
     * @return boolean
     */
    public static function checkUserTimeout()
    {
        
        // If there was no POST at all during this session
        if (! isset($_SESSION['timeout'])) {
            $_SESSION['timeout'] = time();
            return;
        }
        $bypass = 1;
        if ($bypass == 1)
            return;
        $waitTime = self::$timeout - (time() - $_SESSION['timeout']);
        
        if ($waitTime > 0) {
            throw new Exception("You are doing it too fast! Please wait " . $waitTime . " more seconds..");
        }
        
        $_SESSION['timeout'] = time();
        return;
    }

    public static function getWalletInformation($walletID)
    {
        $walletID = self::trim_where($walletID);
        
        $sql = "select * from wallets where id = {$walletID}";
        if (! $result = CryptoSQL::getConn()->query($sql)) {
            throw new Exception("Could not retreive wallet information.");
        }
        $row = mysqli_fetch_assoc($result);
        
        if ($row["account"] != $_SESSION['UserID']) {
            throw new Exception("You tried to view a wallet that does not belong to you.");
        }
        
        $wallet = new Wallet($row);
        
        $transactions = array();
        $sql = "SELECT c.currencyName, t.* FROM transactions t JOIN currencies c ON (t.currency = c.id) where creditWallet = {$walletID} order by t.timeStarted";
        if ($result = CryptoSQL::getConn()->query($sql)) {
            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($transactions, new Transaction($row));
            }
        } else {
            throw new Exception("Could not retreive wallet information for address " . $walletID);
        }
        $wallet->transactions = $transactions;
        
        return $wallet;
    }

    public static function convertCurrency($from, $to, $value)
    {}

    /**
     * Returns an array with wallet IDs associated with an account
     *
     * @param unknown $account            
     * @return unknown
     */
    public static function getWalletInfoTableByAccount($account)
    {
        $email = self::trim_where($account);
        $sql = "select * from wallets where account=\"$email\"";
        if (! $result = CryptoSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.<br />" . CryptoSQL::getConn()->error);
        }
        
        $wallets = array();
        if ($result = CryptoSQL::getConn()->query($sql)) {
            
            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result)) {
                $tmpWallet = array();
                array_push($tmpWallet, $row["account"]);
                array_push($tmpWallet, $row["walletLabel"]);
                array_push($tmpWallet, $row["domain"]);
                array_push($wallets, $tmpWallet);
            }
            
            /* free result set */
            mysqli_free_result($result);
        }
        
        return $wallets;
    }

    public static function getAmountOfWalletsByUser($email)
    {
        $email = self::trim_where($email);
        $sql = "select count(*) as num_results from wallets where account in (\"{$email}\")";
        if (! $result = CryptoSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.<br />" . CryptoSQL::getConn()->error);
        }
        
        $result = @mysqli_fetch_assoc($result);
        
        return $result["num_results"];
    }

    /**
     * Register user to database if he does not exists yet
     *
     * @param unknown $email            
     * @return string
     */
    private static function init($email)
    {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $email = self::trim_insert($email);
        $conn = self::getConn();
        $authKey = $ga->createSecret();
        $domain_name = substr(strrchr($email, "@"), 1);
        
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! checkdnsrr($domain_name, 'MX')) {
            // invalid emailaddress
            throw new CryptoException("Please use a valid email address.");
        }
        
        $sql = "INSERT INTO users VALUES (\"$email\", \"$authKey\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not generate a new account");
        }
        
        $conn->close();
        
        return $authKey;
    }

    /**
     * Returns TRUE if the account sent is the owner of the wallet received
     *
     * @param unknown $account            
     * @param unknown $wallet            
     * @throws Exception
     * @return boolean
     */
    public static function verifyOwner($account, $wallet)
    {
        $account = self::trim_where($account);
        $wallet = self::trim_where($wallet); 
        
        $conn = CryptoSQL::getConn();
        
        $sql = 'SELECT account FROM wallets WHERE id= '.$wallet;
        
        if (!$result = $conn->query($sql)) {
            //Oh no! The query failed.
            throw new Exception("Could not retreive account information (" . $account . " - " . $wallet . ").");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        return $account == $row["account"];
    }
    
    /**
     * This function will add a new wallet to the database
     *
     * @param unknown $email            
     * @param unknown $walletID            
     */
    public static function addWallet($email, $label = "Default Wallet", $domain = "domain")
    {
        if (! Bitcoin::validateStringNumber($label)) {
            throw new Exception("Invalid label provided.");
        }
        
        if (! Bitcoin::validateStringNumber($domain, true)) {
            throw new Exception("Invalid domain provided.");
        }
        
        if (CryptoSQL::getAmountOfWalletsByUser($email) > 15) {
            throw new Exception("You have maxed out your wallets!");
        }
        
        $_SESSION['UserID'] = isset($_SESSION['UserID']) == true ? $_SESSION['UserID'] : $email;
        
        if (! self::verifyEmpty("select count(*) as num_rows from wallets where walletLabel in (\"{$label}\")
                                AND account in (\"{$_SESSION['UserID']}\")"))
            throw new Exception("You already created a wallet with this label.");
        
        $email = self::trim_insert($email);
        $label = self::trim_insert($label);
        $domain = self::trim_insert($domain);
        
        $walletAPI = Bitcoin::RandomString(25);
        
        $conn = CryptoSQL::getConn();
        $sql = "INSERT INTO wallets (account, walletLabel, domain, walletAPI) VALUES (\"{$email}\",\"{$label}\",\"{$domain}\", \"{$walletAPI}\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not generate a new wallet.<br />" . $sql);
        }
        
        return true;
    }

    public static function verifyEmpty($sql)
    {
        $conn = CryptoSQL::getConn();
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not query for empty verification " . $sql);
        }
        $row = mysqli_fetch_row($result);
        return $row[0] == 0;
    }

    public static function verify($email, $authKey)
    {
        $conn = CryptoSQL::getConn();
        $email = self::trim_where($email);
        $ga = new PHPGangsta_GoogleAuthenticator();
        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        
        return true;
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        $key = $row['2fa'];
        $secret = $ga->getCode($key);
        $conn->close();
        return $secret == $authKey;
    }

    public static function getAuth($email)
    {
        $conn = CryptoSQL::getConn();
        $email = self::trim_where($email);
        
        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        $arr["key"] = $row['2fa'];
        
        $ga = new PHPGangsta_GoogleAuthenticator();
        
        $arr["img"] = $ga->getQRCodeGoogleUrl("CryptoSell-" . Bitcoin::getEmailPrefix($email), $arr["key"]);
        
        $conn->close();
        
        return $arr;
    }

    /**
     * Occures when a user attempts to log in using his email
     *
     * @param unknown $email            
     * @throws Exception
     * @return true if the user was created, false otherwise (already exists)
     */
    public static function login($email)
    {
        $conn = CryptoSQL::getConn();
        $email = self::trim_where($email);
        
        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit();
        }
        
        $authKey = "";
        
        // Check if email is found in DB
        // If not - create a new wallet
        if ($result->num_rows === 0) {
            
            $authKey = self::init($email);
            CryptoSQL::addWallet($email);
            
            return true;
        }
        
        return false;
        
        $conn->close();
    }

    /*
     * Use trim_input when a variable is in INSERT clause
     */
    private static function trim_insert($var)
    {
        $conn = CryptoSQL::getConn();
        $var = $conn->real_escape_string($var);
        $conn->close();
        return $var;
    }

    /*
     * Use trim_output when a variable is in WHERE clause
     */
    private static function trim_where($var)
    {
        return strip_tags($var);
    }

    /**
     * Returns an active MySQLI Connection
     *
     * @return mysqli
     */
    public static function getConn()
    {
        $mysqli = new mysqli(self::$server, self::$user, self::$pass, self::$db);
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        
        /*** THIS! ***/
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        // In case SQL Connection did not work
        if ($mysqli->connect_errno) {
            throw new Exception("#00001 - Could not connect to server database");
        }
        
        return $mysqli;
    }

    public static function checkLockScreenTimeout()
    {
        if (! @isset($_SESSION["last_action"])) {
            $_SESSION["last_action"] = time();
            return;
        }
        
        $timeout = 60 * 3; // 3 minute
        
        $timepassed = time() - $_SESSION["last_action"];
        if ($timepassed > $timeout) {
            Logger::log("User timed out. Moving to lock screen");
            $_SESSION["lock"] = 1;
            header("Location ./lock");
        }
        
        $_SESSION["last_action"] = time();
    }

    public static function getBalanceByWallet($wallet, $currency)
    {
       
        $conn = CryptoSQL::getConn();
        if(intval($currency) == 0)
        {
            $currency = CryptoSQL::getCurrency($currency)["id"];
        }
        
        $sql = "select balance from userbalances where walletID=".$wallet." and currencyID=".$currency;
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
           
            throw new Exception("Could not retreive account information.");
            exit();
        }
   
        $balance = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            return $row["balance"];
        }
    }
    
    public static function getTotalBalaceOfWallet($walletId = 0)
    {
        $conn = CryptoSQL::getConn();
        $email = self::trim_where($_SESSION["UserID"]);
        Logger::log("???");
        $sql = '';
        if ($walletId > 0) {
            
            $sql = "select ub.*, c.currencyPair, sum(ub.balance) as sum from userbalances as ub inner join currencies c on ub.currencyID = c.id where walletID=".$walletId." group by currencyID";
            
        } else {
            $sql = "select ub.*, c.currencyPair, sum(ub.balance) as sum from userbalances as ub inner join currencies c on ub.currencyID = c.id where user in (\"{$email}\") group by currencyID";
        }
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit();
        }
        
        // read currencies exchance values
        $obj = Bitcoin::getCurrencyInformation();
        
        $balance = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $balance += CryptoSQL::convert($row["currencyPair"], "BTC", $row["sum"]);
        }
        
        return number_format($balance, 8);
    }
    
    public static function getTotalPending($walletId = 0)
    {
        $conn = CryptoSQL::getConn();
        $email = self::trim_where($_SESSION["UserID"]);
        Logger::log("???");
        $sql = "select c.currencyPair, sum(requiredAmount) as sum from transactions as t inner join wallets as w on t.creditWallet = w.id inner join currencies as c on c.id = t.currency where w.account in (\"{$_SESSION['UserID']}\") and istatus != 2 group by currency";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit();
        }
    
        // read currencies exchance values
        $obj = Bitcoin::getCurrencyInformation();
    
        $balance = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $balance += CryptoSQL::convert($row["currencyPair"], "BTC", $row["sum"]);
        }
    
        return number_format($balance, 8);
    }
    
    public static function convert($convertFrom, $currency, $amount, $format=false)
    {
        if (! $amount)
            $amount = 1;
        
        $currency = strtolower($currency);
        $convertFrom = strtolower($convertFrom);
            
            // read currencies exchance values

        $obj = Bitcoin::getCurrencyInformation();
        $ratio = $obj[$convertFrom][$currency]["price"];
        if (! $ratio) {
            return 0;
        }
        
        if($format)
        {
            return number_format($ratio*$amount, 8);
        }
        return $ratio * $amount;
    }
    
    // validate json file
    public static function isValidJsonFile($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param string $json
     *            The original JSON string to process.
     *            
     * @return string Indented version of the original JSON string.
     */
    public static function indent($json)
    {
        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;
        
        for ($i = 0; $i <= $strLen; $i ++) {
            
            // Grab the next character in the string.
            $char = substr($json, $i, 1);
            
            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = ! $outOfQuotes;
                
                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else 
                if (($char == '}' || $char == ']') && $outOfQuotes) {
                    $result .= $newLine;
                    $pos --;
                    for ($j = 0; $j < $pos; $j ++) {
                        $result .= $indentStr;
                    }
                }
            
            // Add the character to the result string.
            $result .= $char;
            
            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
                
                for ($j = 0; $j < $pos; $j ++) {
                    $result .= $indentStr;
                }
            }
            
            $prevChar = $char;
        }
        
        return $result;
    }

    public static function getCurrencies()
    {
        $conn = CryptoSQL::getConn();
        
        $sql = "select * from currencies";
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not retreive currencies information.");
            exit();
        }
        $arr = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, new Currency($row));
        }
        
        return $arr;
    }
    
    
    
    public static function getCurrenciesAsJson()
    {
        $conn = CryptoSQL::getConn();
    
        $sql = "SELECT `id`, `currencyName`, `currencyPair` FROM `currencies";
        if (! $result = $conn->query($sql)) {
            throw new Exception("Could not retreive currencies information.");
            exit();
        }
        
        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }

        return json_encode($rows);
    }
}


