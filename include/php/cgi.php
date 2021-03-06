<?php
require_once ("sqlink.php");
/* decide what the content should be up here .... */
/* AJAX check */

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    /* special ajax here */
    // die();
}

$toReturn["status"] = "0";
try {
    if (! isset($_POST['itemCurrency']) || ! isset($_POST['currency']) || ! isset($_POST['itemPrice']) || ! isset($_POST['itemName']) || ! isset($_POST['itemPrice']) || ! isset($_POST['key'])) {
        $toReturn["status"] = "0";
        $toReturn["body"] = "Something went wrong!";
        Logger::log("CGI -> one of the fields are missing!");
        throw new Exception();
    }
    foreach ($_POST as $key => $value) {
        $toReturn[$key] = $value;
    }
    
    if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
        $_POST['clientIP'] = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $_POST['clientIP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $_POST['clientIP'] = $_SERVER['REMOTE_ADDR'];
    }
    
    if (! CryptoSQL::verifyAPIKey($_POST['key'], $_POST['domain'])) {
        $toReturn["status"] = "0";
        $toReturn["body"] = "Could not verify domain ownership";
        throw new Exception();
    }
    
    $originalCurrency = $_POST['itemCurrency'];
    
    
    $_ITEM['price'] = CryptoSQL::convert($originalCurrency, $_POST['currency'], $_POST['itemPrice']);
    Logger::log("CGI: " . $_POST['itemPrice'] . " " . $originalCurrency . " -> " . $_ITEM['price'] . " " .$_POST['currency']);
    if ($_ITEM['price'] == 0) {
        $toReturn["status"] = "0";
        $toReturn["body"] = "Item price is invalid!";
        throw new Exception();
    }
    
    try {
        Logger::log("Attempting to add a new transaction: " . $_ITEM['price'] ." " .$_POST['currency']);
        $address = CryptoSQL::addTransaction($_POST['key'], $_POST['clientIP'], $_POST['itemID'], $_POST['currency'], $_ITEM['price']);
    } catch (Exception $e) {
        $toReturn["status"] = "0";
        $toReturn["body"] = $e->getMessage();
        throw new Exception();
    }
    
    $toReturn["status"] = "1";
    $toReturn["body"] = $address;
} catch (Exception $e) {
    Logger::log($e->getMessage());
    if (! @isset($toReturn["status"])) {
        $toReturn["status"] = "0";
        $toReturn["body"] = "Could not connect to wallet. - " . $e->getMessage();
    }
}
Logger::log("Returning json: " . json_encode($toReturn));
echo json_encode($toReturn);
die();
?>