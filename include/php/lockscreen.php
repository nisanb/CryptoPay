<?php
session_start();
require_once 'sqlink.php';
require_once '../../auth/GoogleAuthenticator.php';
require_once '_jsonrpc2.php';

if(@$_POST["lockScreenPass"])
{
    
    try{
        if(LindaSQL::verify($_SESSION['UserID'], $_POST['lockScreenPass']))
        {
            $_SESSION["lock"] = 0;
            header("Location: ./");
        }
        else
        {
            throw new Exception ("Incorrect pass phrase. Please try again.");
        }
    }
    catch(Exception $e)
    {
        $error = $e->getMessage();
    }
    //User submitted the form
  
}

?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | 404 Error</title>

    <link href="./include/css/bootstrap.min.css" rel="stylesheet">
    <link href="./include/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="./include/css/animate.css" rel="stylesheet">
    <link href="./include/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

<div class="lock-word animated fadeInDown">
    <span class="first-word">LOCKED</span><span>SCREEN</span>
</div>
    <div class="middle-box text-center lockscreen animated fadeInDown">
        <div>
            <div class="m-b-md">
            <img alt="image" class="img-circle circle-border" src="./include/img/linda_logo.png" style="width: 80%" />
            </div>
            <h3>WALLET LOCKED</h3>
            <p>In order to keep you safe, we are locking your wallet every 60 seconds you are inactive at.</p>
            <p>Please use the Google Authenticator in order to use the wallet again</p>
            <form class="m-t" role="form" method="post" action="./lock">
                <div class="form-group" >
                    <input type="password" class="form-control" placeholder="******" required="" name="lockScreenPass">
                </div>
                <br />
                <div style="width: 60%;" class="progress-bar"></div>
                <div class="form-group " style="font-color: red;">
                <?php 
                echo @$error;
                ?>
                </div>
                <button type="submit" class="btn btn-primary block full-width">Unlock</button>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="./include/js/jquery-2.1.1.js"></script>
    <script src="./include/js/bootstrap.min.js"></script>

</body>

</html>
