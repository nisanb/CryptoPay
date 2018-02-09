<?php
session_start();
require_once './include/php/sqlink.php';
require_once './include/php/LindaException.php';
require_once './auth/GoogleAuthenticator.php';
require_once './include/php/_jsonrpc2.php';

if(!@isset($_SESSION['UserID']) && @$_GET['act'] != "login")
{
    //Need to log - in
    header("Location: ./login");
}

if(@$_SESSION["lock"] == 1 && @isset($_SESSION['UserID']))
{
    header("Location: ./lock");
}

if(@isset($_SESSION['UserID']))
    LindaSQL::checkLockScreenTimeout();


/* Index Page - Website Manager */

$includePage = "";
$includeTemplate = true;
switch(@$_GET['act']){
  case "login":
    $includePage = $_GET['act'];
    $includeTemplate = false;
  break;

  case "wallet":
      $includePage = $_GET['act'];
      $includeTemplate = true;
  break; 
      
      
  case "logout":
    session_destroy();
    header("Location: ./");
  break;

  default:
    $includePage = "default";
  break;
    }


function active($menuItem){
    
  if($menuItem == @$_GET['act'] || ($menuItem == "default" && !isset($_GET['act']))){
    return "class=\"active\"";
  }
  if(@isset($_GET['wid']))
  {
      if($menuItem == $_GET['wid'])
          return "class=\"active\"";
  }
}
try{
    include "./include/php/".$includePage.".php";
    
    if($includeTemplate){
        //Meaning user it attempting to get into the Website
        //Verify user is logged in
        if(!isset($_SESSION['UserID'])){
            //Redirect
            header("Location: ./login");
            return;
        }
        
        include "./include/php/template.php";
    }
}
catch(Exception $e)
{
    $_SESSION["err"] = $e->getMessage();
    header("Location: /error");
}



