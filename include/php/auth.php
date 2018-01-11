<?php
//Check if the form is submitted
//include "gauth.php";
include "./include/php/sqlink.php";
if(@isset($_POST['email']))
{
    $email = $_POST['email'];
    try{
        LindaSQL::login($email);
        header("./auth");
    }
    catch(Exception $e)
    {
        $error = $e->getMessage();
    }
    
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

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">
                <img src="./include/img/linda_logo.png" />
                </h1>

            </div>
            <h3>Welcome to Linda Web Wallet</h3>
            <p>Log-In to see it in action!</p>
            <?php
            if(isset($error)){
              echo "<p style=\"color: red;\">".$error."</p>";
            }
             ?>
            <form class="m-t" role="form" action="./?act=login" method="POST">
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

  
              
            </form>
            <p class="m-t"> <small>Linda Web Walet &copy; 2018<br /></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="./include/js/jquery-2.1.1.js"></script>
    <script src="./include/js/bootstrap.min.js"></script>

</body>

</html>
