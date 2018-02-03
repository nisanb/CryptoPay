<?php
/**
 * Linda Web Wallet API
 * @sk8r
 * (c) LindaProject 2017
 */
require_once './auth/GoogleAuthenticator.php';
require_once './include/php/_jsonrpc2.php';

class Linda{
    
    
    private static $rpcuser     =   "k0bsP";
    private static $rpcpass     =   "tTaA4XCUmcZZ867";
    private static $rpcip       =   "127.0.0.1";
    private static $rpcport     =   "33821";
    

    private static $rpcconn;
    
    /**
     * Will return the username by an email
     * @param unknown $email
     * @return string
     */
    public static function getEmailPrefix($email)
    {
        return substr($email, 0, strpos($email, "@"));
    }

    /**
     * Grants access to the current RPC Connection
     * @return jsonRPCClient
     */
    public static function RPC()
    {
        if(!isset(self::$rpcconn) || self::$rpcconn == null)
        {
            //self::$rpcconn = new jsonRPCClient('http://'.self::$rpcuser.':'.self::$rpcpass.'@'.self::$rpcip.':'.self::$rpcport.'/');
            self::$rpcconn = new jsonRPCClient(self::$rpcuser, self::$rpcpass, self::$rpcip, self::$rpcport);
        }
        return self::$rpcconn;
    }
    
    /**
     * TODO
     * @param unknown $account
     * @param unknown $trans
     */
    public static function getTransactionDetails($account, $trans)
    {
        
    }
    
    /**
     * Returns transactions associated by an account
     * @param unknown $account
     * @return unknown
     */
    public static function getTransactionsByAccount($account)
    {
        $toReturn = array();
        
        foreach(self::RPC()->listtransactions($account, 99999999999) as $trans)
        {
            if(self::isValidAddress($trans["address"]))
            {
                array_push($toReturn, $trans);
            }
        }
        
        return $toReturn;
    }
    
    /**
     * Returns received transactions made by an account and wallet
     * @param unknown $account
     * @param unknown $wallet
     * @return array
     */
    public static function getTransactionsByWallet($wallet, $from=0)
    {
        $toReturn = array();
        if(!@isset($from))
            $from = 0;
        
            
        $returnedList =  self::RPC()->listtransactions($wallet["walletHash"], 10, $from);
        
        $tranList = array();
        
        foreach($returnedList as $trans)
        {
            if($trans["category"] == "receive")
                if(!isset($tranList[$trans["time"]])){
                    $tranList[$trans["time"]] = $trans;
            }
                else {
                    print_r($trans);
                }
        }
        
        $returnedList = self::RPC()->listtransactions($wallet["walletHash"]."-stealth", 10, $from);
        foreach($returnedList as $trans)
        {
            if($trans["category"] == "send")
                if(!isset($tranList[$trans["time"]])){
                    $trans["amount"] += $trans["fee"];
                    $tranList[$trans["time"]] = $trans;
                }
        }

        return $tranList;
    }
    
    /**
     * Returns TRUE if a wallet is valid
     * @param unknown $id
     * @return number
     */
    public static function isValidWalletID($id)
    {
        if(!preg_match("/^[a-zA-Z0-9]{34}$/", $id))
            throw new Exception("Invalid Wallet Address supplied - ".$id);
        
        return true;
    }
    
    public static function getBalanceByWallet($wallet, $minconf = 1)
    {
        /**
         * Build the JSON Array for Listunspent
         * @var array $arr
         * TODO Put security
         */
        return self::RPC()->getbalance($wallet, $minconf);
    }
    
    /**
     * Returns if a given string consists only from chars and numbers
     * @param unknown $str
     * @return number
     */
    public static function validateStringNumber($str)
    {
        return preg_match("/^[a-zA-Z0-9 ]+$/", $str);
    }
    
    public static function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = "";
        for ($i = 0; $i < 30; $i++) {
            if($i%10==0)
                $randstring .= "-";
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }
    
    /**
     * Sends cash to a wallet
     * @param unknown $from
     * @param unknown $to
     * @param unknown $amount
     * @param real $fee
     */
    public static function sendCash($from, $to, $amount, $fee = 0.0001)
    {
     
        //Validate wallet addresses
        if(!self::isValidAddress($to))
        {
            throw new Exception("Invalid address given -> ".$to);
        }
        
        if(!self::isValidAddress($from))
        {
            throw new Exception("Invalid address given -> ".$from);
        }
        
        if($amount <= 0 || $fee <= 0)
        {
            throw new Exception("Transaction amounts cannot be negative.");
        }
        
        $walletData = LindaSQL::getWalletData($from);
        $walletHash = $walletData["walletHash"];
        
        //Check balance with fees
        $total = $amount + $fee;
        
        $diff = Linda::RPC()->getbalance($walletHash) - $total;
        
        if($diff < 0)
            throw new Exception("Transaction could not be commited due to insufficient amount (missing ".$diff.")");
        
        //All input is verified
        
            //1. move funds to stealth account
            //2. sendfrom stealthaccount to address - amount without fees with min conf 5
            //3. done
            
        $stealth = $walletHash."-stealth";
        
        //Transfer funds to stealth wallet
        if(!Linda::RPC()->move($walletHash, $stealth, $total, 3))
            throw new Exception("Could not transfer funds to stealth wallet.");
        
        //Send the real transaction
        $result = Linda::RPC()->sendfrom($stealth, $to, $amount, 3);
        
        $newStealthBalance = abs(Linda::RPC()->getbalance($stealth));
        
        if(!Linda::RPC()->move("", $stealth, $newStealthBalance))
            throw new Exception("Could not transfer funds back to stealth wallet.");
            
        
        
        
        
        
        
        
        
    }
    
    /**
     * Creates a wallet by a given account ID
     * @param unknown $account
     * @return number
     */
    public static function createWallet($account, $label = "Default Wallet")
    {
        
        if(!self::validateStringNumber($label))
        {
            return false;
        }
        
        //Generate wallet label
        $walletHash = $account.Linda::RandomString();

        
        //Check if not maxed out
       /* if(LindaSQL::getAmountOfWalletsByUser($account) > 0)
            throw new Exception ("You have maxed out your wallets!");
      */
        //Generate address
        $walletID = self::RPC()->getnewaddress($walletHash);
        
        //Generate stealth wallet
        self::RPC()->getnewaddress($walletHash."-stealth");
        
        //Add to SQL
        LindaSQL::addWallet($account, $walletID, $walletHash, $label);
        
        //Verify wallet was created
        self::isValidWalletID($walletID);
        
        return true;
        
    }
    
    /**
     * Returns balance by a given account
     * @param unknown $account
     * @return number
     */
    public static function getBalanceByAccount($account, $minconf = 1)
    {
        $totalBalance = 0;
        
        $wallets = LindaSQL::getWalletsByAccount($account);
            foreach($wallets as $wallet)
            {
                $totalBalance += self::getBalanceByWallet($wallet, $minconf);
            }
            return $totalBalance;
    }
    
    /**
     * TRUE only if an address is valid
     * @param unknown $addr
     * @return boolean
     */
    public static function isValidAddress($addr = NULL)
    {
        if(is_null($addr) || !Linda::isValidWalletID($addr))
            return false;
        
        return self::RPC()->validateaddress($addr)["isvalid"];
    }
    


}

class LindaSQL{

    private static $server      =   "localhost";
    private static $user        =   "root";
    private static $pass        =   "";
    private static $db          =   "linda";
    private static $timeout     =   31;
    
    /**
     * Check if user submitted an action in the last 31 seconds
     * @return boolean
     */
    public static function checkUserTimeout()
    {
        
        //If there was no POST at all during this session
        if(!isset($_SESSION['timeout']))
        {
            $_SESSION['timeout'] = time();
            return;
        }
        
        $waitTime = self::$timeout - (time() - $_SESSION['timeout']);

        if($waitTime > 0)
        {
            throw new Exception("You are doing it too fast! Please wait ".$waitTime." more seconds..");
        }
        
        $_SESSION['timeout'] = time();
        return;        
    }
    
    public static function getWalletData($walletID)
    {
        //Validate
        Linda::isValidAddress($walletID);
        $walletID = self::trim_where($walletID);
        $tmpWallet = array();
        
        
        $sql = "select * from wallets where walletAddress = \"$walletID\"";
        if ($result = LindaSQL::getConn()->query($sql)) {
            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }
            
        }
        
        throw new Exception("Could not retreive wallet information for address ".$walletID);
        
    }
    
    /**
     * Returns an array with wallet IDs associated with an account
     * @param unknown $account
     * @return unknown
     */
    public static function getWalletInfoTableByAccount($account)
    {
        $email = self::trim_where($account);
        $sql = "select * from wallets where account=\"$email\"";
        if (!$result = LindaSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.");
        }
        
        $wallets = array();
        if ($result = LindaSQL::getConn()->query($sql)) {
            
            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result)) {
                $tmpWallet = array();
                array_push($tmpWallet, $row["account"]);
                array_push($tmpWallet, $row["walletHash"]);
                array_push($tmpWallet, $row["walletLabel"]);
                array_push($tmpWallet, $row["walletAddress"]);
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
        $sql = "select count(*) from wallets where account=\"$email\"";
        if (!$result = LindaSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.");
        }
        
        $result = @mysqli_fetch_assoc($result);
        
        return $result;
        
    }
    
    public static function getWalletsByAccount($email)
    {
        $email = self::trim_where($email);
        
        $sql = "SELECT walletHash FROM wallets where account=\"$email\"";
        if (!$result = LindaSQL::getConn()->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not query for account wallets.");
        }
        
        $arr = mysqli_fetch_assoc($result);
        
        return $arr;
    }
    
    /**
     * Register user to database if he does not exists yet
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

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !checkdnsrr($domain_name, 'MX')) {
            // invalid emailaddress
            throw new LindaException("Please use a valid email address.");
        }

        $sql = "INSERT INTO users VALUES (\"$email\", \"$authKey\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not generate a new account");
        }

        $conn->close();

        return $authKey;



    }
    
    /**
     * Returns TRUE if the account sent is the owner of the wallet received
     * @param unknown $account
     * @param unknown $wallet
     * @throws Exception
     * @return boolean
     */
    public static function verifyOwner($account, $wallet)
    {
        $account    = self::trim_where($account);
        $wallet     = self::trim_where($wallet);
        
        $conn = LindaSQL::getConn();
        
        $sql = "SELECT account FROM wallets WHERE walletAddress in (\"{$wallet}\")";

        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information (".$account ." - ".$wallet.").");
            exit;
        }
        
        $row = mysqli_fetch_assoc($result);
        
        return $account == $row["account"];
        
    }

    /**
     * This function will add a new wallet to the database
     * @param unknown $email
     * @param unknown $walletID
     */
    public static function addWallet($email, $walletID, $walletHash, $label)
    {
        $email = self::trim_insert($email);
        $label = self::trim_insert($label);
        $walletHash = self::trim_insert($walletHash);
        $walletID = self::trim_insert($walletID);
        
        Linda::isValidWalletID($walletID);
        
        $conn = LindaSQL::getConn();
        $sql = "INSERT INTO wallets VALUES (\"$email\",\"$walletHash\",\"$label\",\"$walletID\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not generate a new wallet");
        }
        
        return true;
        
    }
    
    public static function verify($email, $authKey)
    {

        $conn = LindaSQL::getConn();
        $email = self::trim_where($email);
        $ga = new PHPGangsta_GoogleAuthenticator();
        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit;
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
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit;
        }

        $row = mysqli_fetch_assoc($result);
        $arr["key"] = $row['2fa'];


        $ga = new PHPGangsta_GoogleAuthenticator();


        $arr["img"] = $ga->getQRCodeGoogleUrl("LindaWallet-".Linda::getEmailPrefix($email), $arr["key"]);
        
        
        $conn->close();

        return $arr;
    }

    /**
     * Occures when a user attempts to log in using his email
     * @param unknown $email
     * @throws Exception
     * @return true if the user was created, false otherwise (already exists)
     */
    public static function login($email)
    {
        $conn = LindaSQL::getConn();
        $email = self::trim_where($email);

        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit;
        }

        $authKey = "";
        
        //Check if email is found in DB
        //If not - create a new wallet
        if ($result->num_rows === 0) {
            
           $authKey = self::init($email);
           Linda::createWallet($email);
           
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
     * @return mysqli
     */
    private static function getConn()
    {
        $mysqli = new mysqli(self::$server, self::$user, self::$pass, self::$db);

        //In case SQL Connection did not work
        if ($mysqli->connect_errno) {
            throw new Exception("#00001 - Could not connect to server database");
        }

        return $mysqli;
    }



}
