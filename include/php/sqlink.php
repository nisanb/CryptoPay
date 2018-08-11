<?php

require "Objects/Transaction.php";
require "Objects/Wallet.php";
require_once '_jsonrpc2.php';

/**
 * Linda Web Wallet API
 * @sk8r
 * (c) LindaProject 2017
 */
class Linda
{

    private static $rpcuser = "asdasd";

    private static $rpcpass = "asdasdasd";

    private static $rpcip = "127.0.0.1";

    private static $rpcport = "33821";

    private static $rpcconn;

    /**
     * Will return the username by an email
     * 
     * @param unknown $email            
     * @return string
     */
    public static function getEmailPrefix($email)
    {
        return substr($email, 0, strpos($email, "@"));
    }

    /**
     * Grants access to the current RPC Connection
     * 
     * @return jsonRPCClient
     */
    public static function RPC()
    {
        if (! isset(self::$rpcconn) || self::$rpcconn == null) {
            // self::$rpcconn = new jsonRPCClient('http://'.self::$rpcuser.':'.self::$rpcpass.'@'.self::$rpcip.':'.self::$rpcport.'/');
            self::$rpcconn = new jsonRPCClient(self::$rpcuser, self::$rpcpass, self::$rpcip, self::$rpcport);
        }
        return self::$rpcconn;
    }

    /**
     * TODO
     * 
     * @param unknown $account            
     * @param unknown $trans            
     */
    public static function getTransactionDetails($account, $trans)
    {}

    /**
     * Returns transactions associated by an account
     * 
     * @param unknown $account            
     * @return unknown
     */
    public static function getTransactionsByAccount($account)
    {
        $toReturn = array();
        
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
        /**
         * Temporary fix
         */
        if ($id == "skin-config.html") {
            die();
        }
        //if (! preg_match("/^[0-9]{0}$/", $id))
            //throw new Exception("Invalid Wallet Address supplied - " . $id);
        
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
    public static function sendCash($from, $to, $amount, $fee = 0.0001)
    {
        
        // Validate wallet addresses
        if (! self::isValidAddress($to)) {
            throw new Exception("Invalid address given -> " . $to);
        }
        
        if (! self::isValidAddress($from)) {
            throw new Exception("Invalid address given -> " . $from);
        }
        
        if ($amount <= 0 || $fee <= 0) {
            throw new Exception("Transaction amounts cannot be negative.");
        }
        
        $walletData = LindaSQL::getWalletData($from);
        $walletHash = $walletData["walletHash"];
        
        // Check balance with fees
        $total = $amount + $fee;
        
        $diff = Linda::RPC()->getbalance($walletHash) - $total;
        
        if ($diff < 0)
            throw new Exception("Transaction could not be commited due to insufficient amount (missing " . $diff . ")");
        
        // All input is verified
        
        // 1. move funds to stealth account
        // 2. sendfrom stealthaccount to address - amount without fees with min conf 5
        // 3. done
        
        $stealth = $walletHash . "-stealth";
        
        // Transfer funds to stealth wallet
        if (! Linda::RPC()->move($walletHash, $stealth, $total, 3))
            throw new Exception("Could not transfer funds to stealth wallet.");
        
        // Send the real transaction
        $result = Linda::RPC()->sendfrom($stealth, $to, $amount, 3);
        
        $newStealthBalance = abs(Linda::RPC()->getbalance($stealth));
        
        if (! Linda::RPC()->move("", $stealth, $newStealthBalance))
            throw new Exception("Could not transfer funds back to stealth wallet.");
    }


    /**
     * TRUE only if an address is valid
     * 
     * @param unknown $addr            
     * @return boolean
     */
    public static function isValidAddress($addr = NULL)
    {
        if (is_null($addr) || ! Linda::isValidWalletID($addr))
            return false;
        
        return self::RPC()->validateaddress($addr)["isvalid"];
    }
    
    /**
     * Returns amount of coins received by an account
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
    public static function getWalletInformation()
    {
        $walletFile = "info.wallet";
        
        $timeout = 60 * 5; // 5 minutes
        
        $fileUpdatedTime = (time() - filemtime($walletFile)) . "seconds ago";
        
        // JSON already created
        if ($fileUpdatedTime < $timeout) {
            $fp = file_get_contents($walletFile);
            $arr = json_decode($fp, true);
            
            return $arr;
        }
        
        // Create new JSON
        $fp = fopen($walletFile, 'w');
        $arr = self::RPC()->getinfo();
        
        array_push($arr, self::RPC()->getstakinginfo());
        array_push($arr, self::RPC()->getmininginfo());
        array_push($arr, self::RPC()->getconnectioncount());
        
        // Get price
        $fp2 = file_get_contents("https://api.coinmarketcap.com/v1/ticker/linda/?convert=usd");
        array_push($arr, json_decode($fp2, true));
        $fp2 = file_get_contents("https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=usd");
        array_push($arr, json_decode($fp2, true));
     
        $arr["moneysupply"] = self::bd_nice_number($arr["moneysupply"]);
     
        
        fwrite($fp, json_encode($arr));
        fclose($fp);
        
        return $arr;
    }
    
    public static function getWalletData(){
        $fp = file_get_contents("http://localhost/linda_wallet/info.wallet");
        $jsonDecode = json_decode($fp, true);
        echo "<pre>";
        print_r($jsonDecode);
        echo "</pre>";
    }
    
    //TODO - Convert 
    public static function getPriceInBTC($price)
    {
        return "10";
        $fp = file_get_contents("http://localhost/linda_wallet/info.wallet");
        $jsonDecode = json_decode($fp, true);
        $btcPrice = $jsonDecode[4][0]["price_usd"];
        $price = $price / $btcPrice;
        echo "Price: " . $price;
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
        else if ($n > 1000000000)
            return round(($n / 1000000000), 1) . ' billion';
        else if ($n > 1000000)
            return round(($n / 1000000), 1) . ' million';
        else if ($n > 1000)
            return round(($n / 1000), 1) . ' thousand';
        
        return number_format($n);
    }
}

class LindaSQL
{

    private static $server = "localhost";

    private static $user = "root";

    private static $pass = "";

    private static $db = "linda";

    private static $timeout = 31;

    /**
     * Verifies if a given key and domain belong to an existing wallet
     * @param unknown $key
     * @param unknown $domain
     * @throws Exception
     * @return boolean
     */
    public static function verifyAPIKey($key, $domain)
    {
        $key    = LindaSQL::trim_where($key);
        $domain = LindaSQL::trim_where($domain);
        
        $conn = LindaSQL::getConn();
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
        $conn = LindaSQL::getConn();
        
        if($trans->requiredAmount <= $received)
        {
            $sql = "UPDATE transactions set istatus = 2 where id = {$trans->id}";
            if (! $result = $conn->query($sql)) {
                // Oh no! The query failed.
                throw new Exception("Could not update transaction ".$trans->id);
            }
        }
        else
        {
            //Partial Payment
            $sql = "UPDATE transactions set receivedAmount = {$received} where id = {$trans->id}";
            if (! $result = $conn->query($sql)) {
                // Oh no! The query failed.
                throw new Exception("Could not update transaction ".$trans->id);
            }
            return;
        }

        $sql = "SELECT count(*) as num_results from userbalances where walletID in (\"{$trans->creditWallet}\") AND currencyID = {$trans->currency}";
        if (!$result = $conn->query($sql)) {
            throw new Exception("Could not query userbalances for wallet " . $trans->creditWallet);
        }
        
        $row = mysqli_fetch_assoc($result);
        
        //Transaction was already added -> do attempt to add again
        if($row["num_results"] == 0)
        {
            $sql = "INSERT INTO userbalances values (\"{$_SESSION['UserID']}\",\"{$trans->creditWallet}\", {$trans->currency}, 0)";
            if (! $result = $conn->query($sql)) {
                // Oh no! The query failed.
                throw new Exception("Could not insert new row to userbalances ".$trans->creditWallet." ".$trans->currency);
            }
        }
        
        $sql = "UPDATE userbalances set balance=balance + {$trans->requiredAmount} where walletID = {$trans->creditWallet} AND currencyID = {$trans->currency}";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not update userbalances ".$trans->creditWallet." ".$trans->currency." ".$trans->requiredAmount);
        }
        
        $conn->close();
        
    }
    
    public static function getPandingTransactionAccounts()
    {
        $conn = LindaSQL::getConn();
        
        /**
         * iStatus
         * 0 = Pending Transaction
         * 1 = Partly Received
         * 2 = Successfully accounted for
         */
        $sql = "select * from transactions where istatus != 2";
        if (!$result = $conn->query($sql))
        {
            throw new Exception("Could not retreive transaction information.");
            exit();
        }
        $arr = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, new Transaction($row));
        }
        
        return $arr;
    }
    
    /**
     * Attempts to add a transaction to the database
     * Also generates a new wallet receive address
     * @param unknown $key
     * @param unknown $clientIP
     * @param unknown $itemID
     * @param unknown $currency
     * @throws Exception
     */
    public static function addTransaction($key, $clientIP,$itemID, $currency, $price)
    {
        //Verify inputs
        $key = LindaSQL::trim_where($key);
        $clientIP = LindaSQL::trim_where($clientIP);
        $itemID = intval($itemID);
        $currency = LindaSQL::trim_where($currency);

        $conn = LindaSQL::getConn();
        $sql = "SELECT count(*) as num_results from transactions where iStatus=0 AND 
                clientIP in (\"{$clientIP}\") AND
                itemID = {$itemID}";
        
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive transaction information.");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        
        //Transaction was already added -> do attempt to add again
        if($row["num_results"] > 0)
            return;
        
        //Acquire currency ID
        $currency = LindaSQL::getCurrency($currency);
        
        //Get wallet ID via item ID
        $sql = "select id from wallets where walletAPI in (\"{$key}\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("API Key is wrong");
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        $walletID = $row["id"];
        
        //Generate wallet address
        $creditWalletAccount = time().Linda::RandomString();
        $creditWalletAddress = Linda::RPC()->getnewaddress($creditWalletAccount);
        //Attempt to add the transaction to the database
        $sql = "insert into transactions (istatus, creditWallet, creditWalletAccount, creditWalletAddress, clientIP, requiredAmount, itemID, currency)
VALUES (0, {$walletID}, \"{$creditWalletAccount}\", \"{$creditWalletAddress}\", \"{$clientIP}\", {$price}, {$itemID}, {$currency})";
        
        if(!$result = $conn->query($sql))
        {
            echo $sql."<br />";
            throw new Exception("Could not insert a new transaction information.");
            exit();
        }
        
        $conn->close();
        
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
        if (! $result = LindaSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.<br />".LindaSQL::getConn()->error);
        }
        
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($wallets, new Wallet($row));
        }
        return $wallets;
    }
    
    public static function getCurrency($currency)
    {
        $currency = LindaSQL::trim_where($currency);
        $conn = LindaSQL::getConn();
        $sql = "select id from currencies where currencyPair in (\"{$currency}\")";
        if(!$result = $conn->query($sql))
        {
            throw new Exception("Could not query currencies - ".$conn->error);
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        return $row["id"];
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
        if($bypass == 1)
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
        // Validate
        Linda::isValidAddress($walletID);
        $walletID = self::trim_where($walletID);

        $sql = "select * from wallets where id = {$walletID}";
        if (!$result = LindaSQL::getConn()->query($sql)) {
            throw new Exception("Could not retreive wallet information.");
        }
        $row = mysqli_fetch_assoc($result);

        if($row["account"] != $_SESSION['UserID']){
            throw new Exception("You tried to view a wallet that does not belong to you.");
        }
        
        $wallet = new Wallet($row);
        
        $transactions = array();
        $sql = "select * from transactions where creditWallet = {$walletID}";
        if ($result = LindaSQL::getConn()->query($sql)) {
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
        if (! $result = LindaSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.<br />".LindaSQL::getConn()->error);
        }
        
        $wallets = array();
        if ($result = LindaSQL::getConn()->query($sql)) {
            
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
        if (! $result = LindaSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.<br />".LindaSQL::getConn()->error);
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
            throw new LindaException("Please use a valid email address.");
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
        
        $conn = LindaSQL::getConn();
        
        $sql = "SELECT account FROM wallets WHERE walletAddress in (\"{$wallet}\")";
        
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
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
        if (! Linda::validateStringNumber($label)) {
            throw new Exception ("Invalid label provided.");
        }
        
        if(! Linda::validateStringNumber($domain, true)) {
            throw new Exception ("Invalid domain provided.");
        }
        
        if(LindaSQL::getAmountOfWalletsByUser($email) > 15){
            throw new Exception ("You have maxed out your wallets!");
        }
        
        $_SESSION['UserID'] = isset($_SESSION['UserID']) == true ? $_SESSION['UserID'] : $email;
        
        if(!self::verifyEmpty("select count(*) as num_rows from wallets where walletLabel in (\"{$label}\")
                                AND account in (\"{$_SESSION['UserID']}\")"))
            throw new Exception ("You already created a wallet with this label.");
        
        
        $email = self::trim_insert($email);
        $label = self::trim_insert($label);
        $domain = self::trim_insert($domain);
        
        $walletAPI = Linda::RandomString(25);
        
        $conn = LindaSQL::getConn();
        $sql = "INSERT INTO wallets (account, walletLabel, domain, walletAPI) VALUES (\"{$email}\",\"{$label}\",\"{$domain}\", \"{$walletAPI}\")";
        if (! $result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not generate a new wallet.<br />".$sql);
        }
        
        return true;
    }
    
    public static function verifyEmpty($sql)
    {
        $conn = LindaSQL::getConn();
        if (!$result = $conn->query($sql)){
            throw new Exception("Could not query for empty verification " .$sql);
        }
        $row = mysqli_fetch_row($result);
        return $row[0]==0;
    }

    public static function verify($email, $authKey)
    {
        $conn = LindaSQL::getConn();
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
        $conn = LindaSQL::getConn();
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
        
        $arr["img"] = $ga->getQRCodeGoogleUrl("LindaWallet-" . Linda::getEmailPrefix($email), $arr["key"]);
        
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
        $conn = LindaSQL::getConn();
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
            LindaSQL::addWallet($email);
            
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
        $conn = LindaSQL::getConn();
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
    private static function getConn()
    {
        $mysqli = new mysqli(self::$server, self::$user, self::$pass, self::$db);
        
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
            $_SESSION["lock"] = 1;
            header("Location ./lock");
        }
        
        $_SESSION["last_action"] = time();
    }
}
