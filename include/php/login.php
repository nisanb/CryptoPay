<?php
//Check if the form is submitted
//include "gauth.php";
include "./include/php/sqlink.php";

if(@isset($_POST['email']) && !@isset($_POST['authKey']))
{
    $email = $_POST['email'];
    try{
        LindaSQL::login($email) == true ? $displayFirstAuth=true : $displayAuth=true;
    }
    catch(Exception $e)
    {
        $error = $e->getMessage();
        $displayLogin = true;
    }
  
}

/**
 * Used to verify user login by entering the auth key from google app
 */
else if(@isset($_POST['authKey']))
{
    $authKey = $_POST['authKey'];
    $email = $_POST['email'];
    echo "Sending verification with email " . $email . " <br />";
    try{
    if(LindaSQL::verify($email, $authKey))
    {
        $_SESSION['UserID'] = $email;
        throw new Exception("YAY!");
    }
    else{
        throw new Exception("Google authentication key is incorrect!");
    }
    }
    catch(Exception $e)
    {
        $error = $e->getMessage();
    }
    
}
else if(@isset($_POST['continue']))
{
    $displayAuth = true;
}
else{
    $displayLogin = true;
}

?>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LindaWallet | Login</title>
    <link rel="icon" href="./include/img/linda_icon.png" />
    <link href="./include/css/bootstrap.min.css" rel="stylesheet">
    <link href="./include/css/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="./include/css/animate.css" rel="stylesheet">
    <link href="./include/css/style.css" rel="stylesheet">

</head>



<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown" style="width: 50%">
        <div>
            <div>
                <h1 class="logo-name">
                <img src="./include/img/linda_logo.png" />
                </h1>

            </div>
            <h3>Welcome to Linda Web Wallet</h3>
            
            <?php
                if(isset($error)){
                  echo "<p style=\"color: red;\">".$error."</p>";
                }
                
                if(@$displayLogin)
                {
                    echo '
<p>Log-In to see it in action!</p>
                        <form class="m-t" role="form" action="./?act=login" method="POST">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" name="email" required>
                            </div>
                        
                            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                        
                        
                        
                        </form>
                     ';
                }
                else if(@$displayFirstAuth)
                {
                    $arr = LindaSQL::getAuth($email);
                    echo '
<strong>Stronger security for your Linda Wallet!</strong><br />
We provide Google 2 Factor Authentication protocol in order to secure your account.<br />
Please download Google Authenticator 
(<a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en">Android</a> / 
<a target="_blank" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8">iOS</a>) and scan the barcode below:
<br />
<img src="'.$arr["img"].'" />
<br >
<strong>**Save The Authentication Key: '. $arr["key"] .'**</strong>


                        <form class="m-t" role="form" action="./?act=login" method="POST">
                            <div class="form-group">
Will Send email through
<input type="hidden" name="email" value="'.$_POST['email'].'" />
<input type="checkbox" name="continue" required> '.$_POST['email'].' I installed the app and scanned the barcode.
</div>
  <div class="form-group">
<input type="checkbox" name="2" required> I saved the Auth Key.
                                
                            </div>
        
                            <button type="submit" class="btn btn-primary block full-width m-b">Continue</button>
        
        
        
                        </form>
';
                }
              else if(@$displayAuth)
              {
                  echo '
                        <form class="m-t" role="form" action="./?act=login" method="POST">
                            <div class="form-group">
                                <input type="number" class="form-control" placeholder="Google Auth key" name="authKey" required>
                                <input type="hidden" name="email" value="'.$_POST['email'].'" />
                            </div>
                
                            <button type="submit" class="btn btn-primary block full-width m-b">Authorize</button>
                
                
                
                        </form>
';
              }
            
            ?>
            <p class="m-t"> <small>Linda Web Walet &copy; 2018<br /></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="./include/js/jquery-2.1.1.js"></script>
    <script src="./include/js/bootstrap.min.js"></script>

</body>

</html>
