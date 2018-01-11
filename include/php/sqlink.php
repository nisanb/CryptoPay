<?php
// Let's pass in a $_GET variable to our example, in this case
// it's aid for actor_id in our Sakila database. Let's make it
// default to 1, and cast it to an integer as to avoid SQL injection
// and/or related security problems. Handling all of this goes beyond
// the scope of this simple example. Example:
//   http://example.org/script.php?aid=42

require_once './auth/GoogleAuthenticator.php';

class LindaSQL{
    
    private static $server  = "localhost";
    private static $user    = "root";
    private static $pass    = "";
    private static $db      = "lindawallet";
    
   
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
        echo "Checking for email: " . $email . "<br />";
        $sql = "SELECT 2fa FROM users WHERE email in (\"$email\")";
        if (!$result = $conn->query($sql)) {
            // Oh no! The query failed.
            throw new Exception("Could not retreive account information.");
            exit;
        }
        $row = mysqli_fetch_assoc($result);
        $key = $row['2fa'];
        $secret = $ga->getCode($key);
    echo "Key is: ".$key."<br />Secret is ".$secret."<br />givenAuthKey = ".$authKey;
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
        echo "Stripping tags for ".$var." <br />";
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
            die('#00001 - Could not connect to server database');
        }
        
        return $mysqli;
    }
    
    
    
}


/*
<?php
// Let's pass in a $_GET variable to our example, in this case
// it's aid for actor_id in our Sakila database. Let's make it
// default to 1, and cast it to an integer as to avoid SQL injection
// and/or related security problems. Handling all of this goes beyond
// the scope of this simple example. Example:
//   http://example.org/script.php?aid=42
if (isset($_GET['aid']) && is_numeric($_GET['aid'])) {
    $aid = (int) $_GET['aid'];
} else {
    $aid = 1;
}

// Connecting to and selecting a MySQL database named sakila
// Hostname: 127.0.0.1, username: your_user, password: your_pass, db: sakila
$mysqli = new mysqli('127.0.0.1', 'your_user', 'your_pass', 'sakila');

// Oh no! A connect_errno exists so the connection attempt failed!
if ($mysqli->connect_errno) {
    // The connection failed. What do you want to do? 
    // You could contact yourself (email?), log the error, show a nice page, etc.
    // You do not want to reveal sensitive information

    // Let's try this:
    echo "Sorry, this website is experiencing problems.";

    // Something you should not do on a public site, but this example will show you
    // anyways, is print out MySQL error related information -- you might log this
    echo "Error: Failed to make a MySQL connection, here is why: <br />";
    echo "Errno: " . $mysqli->connect_errno . "<br />";
    echo "Error: " . $mysqli->connect_error . "<br />";
    
    // You might want to show them something nice, but we will simply exit
    exit;
}

// Perform an SQL query
$sql = "SELECT actor_id, first_name, last_name FROM actor WHERE actor_id = $aid";
if (!$result = $mysqli->query($sql)) {
    // Oh no! The query failed. 
    echo "Sorry, the website is experiencing problems.";

    // Again, do not do this on a public site, but we'll show you how
    // to get the error information
    echo "Error: Our query failed to execute and here is why: <br />";
    echo "Query: " . $sql . "<br />";
    echo "Errno: " . $mysqli->errno . "<br />";
    echo "Error: " . $mysqli->error . "<br />";
    exit;
}

// Phew, we made it. We know our MySQL connection and query 
// succeeded, but do we have a result?
if ($result->num_rows === 0) {
    // Oh, no rows! Sometimes that's expected and okay, sometimes
    // it is not. You decide. In this case, maybe actor_id was too
    // large? 
    echo "We could not find a match for ID $aid, sorry about that. Please try again.";
    exit;
}

// Now, we know only one result will exist in this example so let's 
// fetch it into an associated array where the array's keys are the 
// table's column names
$actor = $result->fetch_assoc();
echo "Sometimes I see " . $actor['first_name'] . " " . $actor['last_name'] . " on TV.";

// Now, let's fetch five random actors and output their names to a list.
// We'll add less error handling here as you can do that on your own now
$sql = "SELECT actor_id, first_name, last_name FROM actor ORDER BY rand() LIMIT 5";
if (!$result = $mysqli->query($sql)) {
    echo "Sorry, the website is experiencing problems.";
    exit;
}

// Print our 5 random actors in a list, and link to each actor
echo "<ul><br />";
while ($actor = $result->fetch_assoc()) {
    echo "<li><a href='" . $_SERVER['SCRIPT_FILENAME'] . "?aid=" . $actor['actor_id'] . "'><br />";
    echo $actor['first_name'] . ' ' . $actor['last_name'];
    echo "</a></li><br />";
}
echo "</ul><br />";

// The script will automatically free the result and close the MySQL
// connection when it exits, but let's just do it anyways
$result->free();
$mysqli->close();
?>
*/