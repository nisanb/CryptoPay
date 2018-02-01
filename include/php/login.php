<?php
$publickey = "6Lc6mUMUAAAAAENV6xs4VO460pZMpOpFNX1oaf-m";
$privatekey = "6Lc6mUMUAAAAAFKxJzg-hgGw2I0rwckv7RanJgXH";

//Bypass login
$bypass = false;

//$_POST['email'] - User submitted an email
//$_POST['authKey'] - User submitted authentication key

if(@isset($_POST['email']) && !@isset($_POST['authKey']))
{
    $email = $_POST['email'];
    if(@$bypass)
    {
        $_SESSION['UserID'] = $email;
        header("Location: ./");
    }
    try{

        if(!@isset($_POST['continue']))
        {
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if(!$responseData->success)
            {
                throw new LindaException("Please use Google Captcha");
            }
            
        }

        if(@$_POST['betapass'] == "sk8rbeta")
            LindaSQL::login($email) == true ? $displayFirstAuth=true : $displayAuth=true;
        else
            throw new LindaException("Beta Test password is incorrect");
    }
    catch(LindaException $e)
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
    
    try{
    if(LindaSQL::verify($email, $authKey) || $bypass == true)
    {
        $_SESSION['UserID'] = $email;
        header("Location: ./");
    }
    else{
        $displayAuth = true;
        throw new LindaException("Google authentication key is incorrect, please try again.");
    }
    }
    catch(LindaException $e)
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
<script src='https://www.google.com/recaptcha/api.js'></script>

</head>



<body class="gray-bg" >


    <div class="middle-box text-center loginscreen animated fadeInDown" >
        <div>
            <div>
                <h1 class="logo-name">
                <img src="./include/img/linda_logo.png" style="width: 120px; height: 120px;" />
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
                                <input type="email" class="form-control" placeholder="Email" name="email" required><br />
                                <input type="password" class="form-control" placeholder="Beta Test Password" name="betapass" required>
<br />
<center>

                   
                    <div class="g-recaptcha" data-sitekey="6Lc6mUMUAAAAAENV6xs4VO460pZMpOpFNX1oaf-m"></div>
                    
                    

</center>
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
<small>We provide Google 2 Factor Authentication protocol in order to secure your account.<br />
Please download Google Authenticator 
(<a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en">Android</a> / 
<a target="_blank" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8">iOS</a>) and scan the barcode below:
</small>
<br />
<img src="'.$arr["img"].'" />
<br >
<strong>**Save The Authentication Key: '. $arr["key"] .'**</strong>


                        <form class="m-t" role="form" action="./?act=login" method="POST">
                            <div class="form-group">

<input type="hidden" name="email" value="'.$_POST['email'].'" />
<input type="checkbox" name="continue" required> I installed the app and scanned the barcode.
</div>
  <div class="form-group">
<input type="checkbox" name="2" required> I saved the Auth Key.
                                <input type="hidden" name="email" value="'.$_POST['email'].'" />
                                <input type="hidden" name="betapass" value="'.$_POST['betapass'].'" />
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
                                <input type="hidden" name="betapass" value="'.$_POST['betapass'].'" />
                            </div>
                
                            <button type="submit" class="btn btn-primary block full-width m-b">Authorize</button>
                
                
                
                        </form>
';
              }
            
            ?>
            <p class="m-t"> <small>Linda Web Wallet &copy; 2018<br /></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="./include/js/jquery-2.1.1.js"></script>
    <script src="./include/js/bootstrap.min.js"></script>

</body>

</html>
