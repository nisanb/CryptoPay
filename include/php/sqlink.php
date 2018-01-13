<?php
// Let's pass in a $_GET variable to our example, in this case
// it's aid for actor_id in our Sakila database. Let's make it
// default to 1, and cast it to an integer as to avoid SQL injection
// and/or related security problems. Handling all of this goes beyond
// the scope of this simple example. Example:
//   http://example.org/script.php?aid=42

require_once './auth/GoogleAuthenticator.php';
require_once './include/php/_jsonrpc.php';
class Linda{
    private static $rpcuser     =   "k0bsP";
    private static $rpcpass     =   "tTaA4XCUmcZZ867";
    private static $rpcip       =   "127.0.0.1";
    private static $rpcport     =   "33821";


    private static $rpcconn;



    public static function getEmailPrefix($email)
    {
        return substr($email, 0, strpos($email, "@"));
    }


    public static function RPC()
    {
        if(!isset(self::$rpcconn) || self::$rpcconn == null)
        {
            self::$rpcconn = new jsonRPCClient('http://'.self::$rpcuser.':'.self::$rpcpass.'@'.self::$rpcip.':'.self::$rpcport.'/');
        }
        return self::$rpcconn;
    }


    public static function getWalletsByAccount($account)
    {
        $i = 0;
        $arr = self::RPC()->getaddressesbyaccount($account);
        foreach($arr as $tmp)
        {

            $toReturn[$i++] = $tmp;

        }
        return $toReturn;
    }

    public static function getLindaIncomeByAccount($account)
    {
        return self::RPC()->getreceivedbyaccount($account);

    }
    public static function getBalanceByAccount($account)
    {
        return self::RPC()->getbalance($account);

    }

}

class LindaSQL{

    private static $server      =   "localhost";
    private static $user        =   "root";
    private static $pass        =   "";
    private static $db          =   "linda";


    /**
     * Register user to database if he does not exists yet
     * @param unknown $email
     * @return string
     */
    private static function init($email)
    {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $email = self::trim_input($email);
        $conn = self::getConn();
        $authKey = $ga->createSecret();
        $domain_name = substr(strrchr($email, "@"), 1);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !checkdnsrr($domain_name, 'MX')) {
            // invalid emailaddress
            throw new Exception("Please use a valid email address.");
        }

        $sql = "INSERT INTO users VALUES (\"$email\", \"$authKey\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not generate a new account");
        }

        //Submit to database
        //$conn->query($sql);

        $conn->close();

        return $authKey;



    }

    public static function verify($email, $authKey)
    {

        $conn = LindaSQL::getConn();
        $email = self::trim_output($email);
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
        $email = self::trim_output($email);

        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit;
        }

        $row = mysqli_fetch_assoc($result);
        $arr["key"] = $row['2fa'];


        $ga = new PHPGangsta_GoogleAuthenticator();
        $arr["img"] = $ga->getQRCodeGoogleUrl("LindaWallet", $arr["key"]);



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
        $email = self::trim_output($email);

        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit;
        }

        $authKey = "";
        //Check if email is found in DB
        if ($result->num_rows === 0) {
           $authKey = self::init($email);
           return true;
        }

        return false;

        $conn->close();
    }

    /*
     * Use trim_input when a variable is in INSERT clause
     */
    private static function trim_input($var)
    {
        $conn = LindaSQL::getConn();
        $var = $conn->real_escape_string($var);
        $conn->close();
        return $var;
    }

    /*
     * Use trim_output when a variable is in WHERE clause
     */
    private static function trim_output($var)
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
