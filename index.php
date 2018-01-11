<?php
session_start();

/* Index Page - Website Manager */

$includePage = "";
$includeTemplate = true;
switch(@$_GET['act']){
  case "register":
  case "login":
    $includePage = $_GET['act'];
    $includeTemplate = false;
  break;


  case "mailbox":
  case "following":
  case "profile":
    $includePage = $_GET['act'];
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
    echo "class=\"active\"";
  }
}

include "./include/php/".$includePage.".php";
if($includeTemplate){
  //Meaning user it attempting to get into the Website
  //Verify user is logged in
  if(!isset($_SESSION['UserID'])){
    //Redirect
    header("Location: ./?act=login");
    return;
  }
  include "./include/php/template.php";
}

