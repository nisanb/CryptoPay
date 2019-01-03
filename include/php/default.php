<?php
$title = "Homepage";
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">
   <link href="./include/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
   <script>

var allowSubmit = true;
var withdrawJson = "";
var selectedWallet = -1;
    
    
function buildREF(a, b)
{
	$("#depoinput").val(a);
	
	
	if (b == "copy") {
	    var copyText = document.getElementById("depoinput");
	  
	    notify("success", "Copied the text: " + copyText.value);
	    notify("warning", "Copied the text: " + copyText.value);
	    notify("error", "Copied the text: " + copyText.value);
	
	}
	

}

function resetSendForm(walletId)
{
    selectedWallet = walletId;
    $("#walletSendLabel").val("");
    $("#walletSendAddress").val(walletId);
    $("#walletSendAmount").val("");

    $("#payment_amount").attr("max", 0);
    $("#payment_amount").attr("min", 0.0001);
    
    $("#withrawForm").hide();
    $("#chooseCoin").show();
    $("#sendError").hide();
    
    //console.log(selectedWallet);
    
}


function buildSendForm(walletLabel, walletId, balance)
{
    
    $("#walletSendLabel").val(walletLabel);
    $("#walletSendAddress").val(walletId);
    $("#walletSendAmount").val(balance);
    $("#payment_amount").attr("max", balance);
    
    allowSubmit = true;
}


function showWithdraw(currencyName){
    var jsonData;
    $("#sendError").hide();
    
    if (withdrawJson == null || withdrawJson.length < 1){
        $("#sendError").show();
    }
    
    try{
        jsonData = JSON.parse(withdrawJson);
    }
    catch{
        console.log("Wallet details error");
    }
    
    var walletLabel = "";
    var balance = -1;
    
    
    for (var i = 0; i < jsonData.length; i++){
        //console.log(jsonData[i]);
        if (jsonData[i].walletAddress == selectedWallet && jsonData[i].currency == currencyName){
            balance = jsonData[i].balance;
            walletLabel = jsonData[i].walletLabel;
            //console.log("Found in json: ",selectedWallet, walletLabel, balance);
        }
    }
        
    
    
    //rebuild the form (Linda or Bitcoin)
    if (walletLabel.length < 1 || selectedWallet < 1 || balance == -1){
        $("#sendError").show();
        return;
    }
    
    buildSendForm(walletLabel, selectedWallet, balance);
    $("#withrawForm").show();
    $("#chooseCoin").hide();
}



function handleForm(){
//	if (allowSubmit){
//		allowSubmit = false;
//		return true;
//	}
//	
//	return false;
//	
	
}
    
    
    </script>
';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>
<!-- Morris -->
    <script src="./include/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="./include/js/plugins/morris/morris.js"></script>
    <script type="text/javascript" src="./include/js/copy2clipboard.js"></script>
    <!-- <script src="./include/js/demo/morris-demo.js"></script> -->

<style>
        #payment_auth::-webkit-inner-spin-button, 
        #payment_auth::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
        }
</style>
';

$walletInformation = Bitcoin::getCurrencyInformation();

$content = '';
/**
 * A payment has been submitted
 */
if(@$_POST['payment_do'])
{
    
    $payment_from   =   $_POST['payment_from'];
    $payment_auth   =   $_POST['payment_auth'];
    $payment_to     =   str_replace(" ","", $_POST['payment_to']);

    $payment_amount =   floatval($_POST['payment_amount']);
    $payment_fee    =   floatval($_POST['payment_fee']);
    $payment_total  =   floatval($payment_amount + $payment_fee);
    
    
    try
    {
        //check user timeout
        CryptoSQL::checkUserTimeout();
        
        //Check for negativity
        if($payment_amount == 0)
            throw new Exception("You must send atleast 0.0001 linda.");
        
        if($payment_fee < 0)
            throw new Exception("Payment fee cannot be negative");
        
        if($payment_amount <= 0 || $payment_fee < 0 || $payment_total <= 0)
            throw new Exception("You may not enter negative values.");
            
        if(!Bitcoin::isValidAddress($payment_to))
            throw new Exception("The wallet you have tried to send is invalid (".$payment_to.").");
                
        //Verify the wallet sent from is the wallet of the session owner
        /*if(!CryptoSQL::verify($_SESSION['UserID'], $payment_auth))
            throw new Exception("Google Authentication key is incorrect. Please try again.");
        */
        //Verify that the wallet owner is the user logged in
        if(!CryptoSQL::verifyOwner($_SESSION['UserID'], $payment_from))
            throw new Exception("You attempted to send cash from a wallet which is not owned by you.");
        
        Bitcoin::sendCash("Linda", $payment_from, $payment_to, $payment_amount, $payment_fee);
        //     header( "refresh:3;url=./" );
        $message = "You have successfully transferred ".$payment_total." to address ".$payment_to;
        $include_footer .= "
        <script>
        notify('success', $message);
        </script>
        
        ";
    }
    catch(Exception $e)
    {
        $include_footer .= "
            <script>
                notify('error', '".$e->getMessage()."');
            </script>
";
       
    }
    

    
}

$_ACCOUNT['Wallets'] = CryptoSQL::getWalletInfoTableByAccount($_SESSION['UserID']);
$balance = CryptoSQL::getTotalBalaceOfWallet();
$pending = CryptoSQL::getTotalPending();
$include_footer.= '
    
<script>
$(function() {


    Morris.Donut({
        element: \'morris-donut-chart\',
        data: [{ label: "Available BTC", value: '.$balance.' },
            { label: "Pending Coins", value: '.$pending.' },
            ],
        resize: true,
        colors: [\'green\', \'orange\'],
    });

});
</script>
';

/**
 * Creation of a new wallet
 */
if(@$_POST['do_create'] == 1)
{
    try{
        CryptoSQL::checkUserTimeout();
        
        $swalCreationSuccess = CryptoSQL::addWallet($_SESSION['UserID'], $_POST['walletLabel'], $_POST['walletDomain']) ? "true" : "false";
        
      
        
        
    }
    catch(Exception $e)
    {
        $include_footer .= "
            <script>
                notify('error', '".$e->getMessage()."');
            </script>
";
    }
   
   
}

$selectedQR = null;
$lastDepDate = date("m/d/Y");
$lastDepValue = 0;
$lastWitDate = date("m/d/Y");
$lastWitValue = 0;
$tableContent = null;
$count = 1;
$qrVar = null;

foreach(CryptoSQL::getWalletsByAccount($_SESSION['UserID']) as $tmpWallet)
{
    
    $walletBalance = CryptoSQL::getTotalBalaceOfWallet($tmpWallet->id);
$tableContent .=
    '<tr>
    <td>'.$count++.'</td>
    
    <td>

    <a href="./wallet/'.$tmpWallet->id.'" id="w'.$count.'">'.$tmpWallet->walletLabel.'</a>
    </td>
    <td>'.$tmpWallet->domain.'</td>
    <!--<a data-toggle="modal" class="btn btn-primary pull-right" onclick="select_all_and_copy(document.getElementById(\'w'.$count.'\'))">Copy</a>-->
    </td>
    <td>'.$walletBalance.' BTC</td>
    <td>
    <a data-toggle="modal" class="btn btn-primary" href="#withdraw-form" onclick="resetSendForm(\''.$tmpWallet->id.'\')">withdraw</a>
    </td>
    <td>
    <a data-toggle="modal" class="btn btn-primary" href="./iframe/'.$tmpWallet->id.'" >Create API</a>      
    </td>
    </tr>';
}

$content .= '

<input type="text" hidden="true" value="Hello World" name="address" id="address">


<script>
    function createWallet(istrue) {
    if(istrue)
        swal("Success!", "A new wallet was created.", "success").then(function(result) {
        window.location.replace("./");
        });
    else
        swal("Error!", "Could not create wallet using the label provided ('.@$_POST['walletLabel'].').", "error");
    }';

if(@$swalCreationSuccess)
    {
       
        $include_footer .= '<script>createWallet('.$swalCreationSuccess.')</script>'; 
    }
    
    $content .= '
</script>
  <div class="row">
            <div class="col-lg-4 ">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        ';
                        $per = 100;
                       
                            $content .= '<span class="label label-success pull-right" style="background-color: green;">Fully Synced</span>';
                        $content .= '
                        <h5>Wallet Status</h5>
                    </div>

                    <div class="ibox-content">
                        <h1 class="no-margins">'.$balance.' BTC</h1>
<hr />
';
                        
$balances = CryptoSQL::getUserBalancesByAccount($_SESSION['UserID']);

$withdrawJson = CryptoSQL::getWalletBalancesJsonByAccount($_SESSION['UserID']);

$size = sizeof($balances);

$colors = ["Orange", "Green", "Red"];
$i=1;

foreach ($balances as $key=>$value)
{
    if(empty($key)){
        break;
    }
    $amount = number_format($value, 8);
    $content .= '<span class="label label-info pull-left" style="background-color: '.$colors[$i-1].';">'.$amount.'  '.$key.'</span>';
    if($i++ < $size)
    {
        $content .= '    <span class="label label-info pull-left" style="background-color: white; color: black;">&nbsp;</span>';
    }
}

$content.= '
<script>
    withdrawJson = \''. $withdrawJson .'\';
    
</script>


             
<br />

                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">Some Data</span>
                        <h5>Wallet Balances</h5>
                    </div>
                    <div class="ibox-content">
<p id="morris-donut-chart" style="height: 150px"></p>

                        <div class="stat-percent font-bold text-info">'.$lastDepValue.' Linda <i class="fa fa-level-down"></i></div>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">'.Bitcoin::getEmailPrefix($_SESSION['UserID']).'</span>
                        <h5>Wallet Information</h5>
                    </div>
                    <div class="ibox-content">
                    <table class="table">
                    <tbody>
                        <tr>
                            <td>
                        <a href="#" class="btn btn-sm btn-info">Total BTC: <span class="fa fa-btc"></span>'.number_format($balance, 8).'</a>
                            </td>
                            <td>
                        <a href="#" class="btn btn-sm btn-info">Total USD: <span class="fa fa-usd"></span>'.number_format(CryptoSQL::convert("BTC", "USD", $balance), 8).'</a>
                            </td>
                        </tr>
                            
                        <tr>                                                     
                           <td colspan="2" align="center">
                                 <a href="#" class="btn btn-sm btn-info">BTC/USD: <span class="fa fa-usd"></span>'.number_format(CryptoSQL::convert("BTC", "USD", 1), 8).'</button>
                            </td>
                        </tr>
               
                    </tbody>
                    </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">

        </div>

        <div class="row">

        <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>My Wallets</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#">Config option 1</a>
                    </li>
                    <li><a href="#">Config option 2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-3">
                    <a data-toggle="modal" class="btn btn-primary" href="#wallet-form">Add New Wallet</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>

                        <th>#</th>
                        <th>Label </th>
                        <th>Domain </th>
                        <th>Balance</th>
                        <th>Action </th>
                        <th>API </th>

                    </tr>
                    </thead>
                    <tbody>'.$tableContent.'</tbody>
                </table>
            </div>

        </div>
        </div>
        </div>

        </div>


        </div>

        </div>
        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <li class="active"><a data-toggle="tab" href="#tab-1">
                        Notes
                    </a></li>
                    <li><a data-toggle="tab" href="#tab-2">
                        Projects
                    </a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3">
                        <i class="fa fa-gear"></i>
                    </a></li>
                </ul>

                <div class="tab-content">


                    <div id="tab-1" class="tab-pane active">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-comments-o"></i> Latest Notes</h3>
                            <small><i class="fa fa-tim"></i> You have 10 new message.</small>
                        </div>

                        <div>

                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">

                                        There are many variations of passages of Lorem Ipsum available.
                                        <br>
                                        <small class="text-muted">Today 4:21 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">
                                    </div>
                                    <div class="media-body">
                                        The point of using Lorem Ipsum is that it has a more-or-less normal.
                                        <br>
                                        <small class="text-muted">Yesterday 2:45 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        Mevolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                        <br>
                                        <small class="text-muted">Yesterday 1:10 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">
                                    </div>

                                    <div class="media-body">
                                        Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the
                                        <br>
                                        <small class="text-muted">Monday 8:37 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">
                                    </div>
                                    <div class="media-body">

                                        All the Lorem Ipsum generators on the Internet tend to repeat.
                                        <br>
                                        <small class="text-muted">Today 4:21 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">
                                    </div>
                                    <div class="media-body">
                                        Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                                        <br>
                                        <small class="text-muted">Yesterday 2:45 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below.
                                        <br>
                                        <small class="text-muted">Yesterday 1:10 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/logo.png">
                                    </div>
                                    <div class="media-body">
                                        Uncover many web sites still in their infancy. Various versions have.
                                        <br>
                                        <small class="text-muted">Monday 8:37 pm</small>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div id="tab-2" class="tab-pane">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <ul class="sidebar-list">
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Business valuation</h4>
                                    It is a long established fact that a reader will be distracted.

                                    <div class="small">Completion with: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Contract with Company </h4>
                                    Many desktop publishing packages and web page editors.

                                    <div class="small">Completion with: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Meeting</h4>
                                    By the readable content of a page when looking at its layout.

                                    <div class="small">Completion with: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label label-primary pull-right">NEW</span>
                                    <h4>The generated</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="small">Completion with: 22%</div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Business valuation</h4>
                                    It is a long established fact that a reader will be distracted.

                                    <div class="small">Completion with: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Contract with Company </h4>
                                    Many desktop publishing packages and web page editors.

                                    <div class="small">Completion with: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Meeting</h4>
                                    By the readable content of a page when looking at its layout.

                                    <div class="small">Completion with: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label label-primary pull-right">NEW</span>
                                    <h4>The generated</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="small">Completion with: 22%</div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>

                        </ul>

                    </div>

                    <div id="tab-3" class="tab-pane">

                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> Settings</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <div class="setings-item">
                    <span>
                        Show notifications
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Disable Chat
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Enable history
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Show charts
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Offline users
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                                    <label class="onoffswitch-label" for="example5">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Global search
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                                    <label class="onoffswitch-label" for="example6">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Update everyday
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                    <label class="onoffswitch-label" for="example7">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content">
                            <h4>Settings</h4>
                            <div class="small">
                                I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                And typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.
                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div id="wallet-form" class="modal fade" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12"><h3 class="m-t-none m-b">Create Wallet</h3>
    
                                    <p>create a new wallet.</p>
    
                                    <form name="form" action="" method="post">
                                        <div class="form-group"><label>Label</label>
                                            <input type="text" name="walletLabel" id="walletLabel" class="form-control" REQUIRED>
                                        </div>
                                        <div class="form-group"><label>Domain</label>
                                            <input type="text" name="walletDomain" id="walletDomain" class="form-control" REQUIRED>
                                        </div>
                                        <input type="hidden" value="1" name="do_create" />
                                        <div class="input-group col-md-12">
                                            
                                            <span class="input-group-btn"> 
                                                <button href="./add" class="btn btn-primary">Create</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            <div id="withdraw-form" class="modal fade" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12"><h3 class="m-t-none m-b">Withdrawal</h3>
                                    <p>You may send coins to other wallets using the form below.<br />
                                    * Please make sure all input is correct before submitting!</p>
                                    <hr />
                                    <div id="chooseCoin">
                                        <center>                                        
                                        <Button id="chooseLinda" class="btn btn-sm btn-warning" onclick="showWithdraw(\'Linda\')">Linda</Button>
                                        <Button id="chooseBtc" class="btn btn-sm btn-success" onclick="showWithdraw(\'Bitcoin\')">Bitcoin</Button>
                                        </center>
                                    </div>                   
                        
                                    </br>
                                    <div id="sendError" style="color:red;" hidden="hidden">Error: Service is temporarily unavailable</div>
                                    <form id="withrawForm" method="POST" onsubmit="handleForm();" hidden="hidden">
                                        <center><label>Send From</label></center> 
                                        <div class="row">
                                             <div class="col-md-4">
                                                <span type="text" class="form-control">Wallet Label</span> 
                                            </div>
                                            <div class="col-md-8">
                                                <input disabled="true" type="text" class="form-control" REQUIRED id="walletSendLabel" /> 
                                            </div>
                                            <div class="col-md-4">
                                                <span type="text" class="form-control">Wallet Address</span> 
                                            </div>
                                            <div class="col-md-8">
                                                <input readonly="readonly" style="color: #787878;" type="text" type="text" class="form-control" value="test" name="payment_from" id="walletSendAddress" REQUIRED /> 
                                            </div>
                                            <div class="col-md-4">
                                                <span type="text" class="form-control" >Available Balance</span> 
                                            </div>
                                            <div class="col-md-8">
                                                <input disabled="true" type="text" class="form-control" REQUIRED id="walletSendAmount" /> 
                                            </div>
                                        </div>
                                        <hr />
';

//Withdraw Form Values
if(!@isset($_POST['payment_do']))
{
    $_POST['payment_amount'] = "0";
    $_POST['payment_fee'] = "0.0001";
    $_POST['payment_to'] = "";
}


$content .= '

                                        
                                        <center><label>Send To</label></center> 
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter address" value="'.@$_POST['payment_to'].'" class="form-control" name="payment_to" REQUIRED>
                                        </div>    
                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Linda</span> 
                                            <input type="number" id="payment_amount" min="0" max="" step="0.0001" name="payment_amount" value="'.@$_POST['payment_amount'].'" class="form-control" REQUIRED> 
                                            <span disabled="true" class="input-group-addon">
                                                <a onclick="$(\'#payment_amount\').val(($(\'#walletSendAmount\').val() - $(\'#payment_fee\').val()).toFixed(4));"><i class="fa fa-arrow-circle-up"></i> Max</a>
                                            </span>
                                        </div>    
                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Fee</span> 
                                            <input type="number" id="payment_fee" name="payment_fee" value="'.@$_POST['payment_fee'].'" min="0.0000" step="0.0001" value="0.0001" class="form-control"> 
                                        </div>   

                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon"><img style="width: 35px; height: 35x;" src="./include/img/gauth.png" /></span> 
                                            <input type="number" class="form-control" placeholder="Google Auth key" style="height: 50px;" name="payment_auth" id="payment_auth" required>
                                        </div>
                                        </br>                                    
                                        <div>
                                            <input type="hidden" name="payment_do" value="1" />
                                            <a data-toggle="modal" class="btn btn-sm btn-danger pull-left m-t-n-xs" href="#withdraw-form" onclick="buildSendForm(\''.@$tmpWallet->id.'\',\''.@$tmpWallet->walletLabel.'\', \''.$balance.'\')">Cancel</a>
                                            <button id="btnWithdraw" class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Withdraw</strong></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
';
