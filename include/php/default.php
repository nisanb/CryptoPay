<?php
$title = "Homepage";
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>
    <script>



';


//Create a new address
//echo $bitcoin->getnewaddress($account);
$arr = Linda::getWalletsByAccount($_SESSION['UserID']);

$content = '

';
foreach($arr as $tmp)
{
    $content .= $tmp."<br />";
}


