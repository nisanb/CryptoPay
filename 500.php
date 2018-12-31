<?php 
session_start();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CryptoSell | Error Occured</title>
<link rel="icon" href="./include/img/logo.png" />
    <link href="./include/css/bootstrap.min.css" rel="stylesheet">
    <link href="./include/css/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="./include/css/animate.css" rel="stylesheet">
    <link href="./include/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">


    <div class="middle-box text-center animated fadeInDown">
        <h1>Oops!</h1><br />
        <h3 class="font-bold">Internal Wallet Error</h3>

        <div class="error-desc">
            The server encountered something unexpected that didn't allow it to complete the request. 
            <br />
            The following are details regarding the error:<br /><br />
            
            <strong>
            <?=$_SESSION["err"];?>
            </strong>
            <br /><br /><br />
            You can go back to main page: <br/><a href="./" class="btn btn-primary m-t">CryptoSell</a>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="./include/js/jquery-2.1.1.js"></script>
    <script src="./include/js/bootstrap.min.js"></script>

</body>

</html>
