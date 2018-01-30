<?php
include "./include/phpqrcode/qrlib.php";
$title = "Homepage";
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>

<script>
function buildREF(a, b)
{
    $("#depoinput").val(a);

    
    if (b == "copy") {
        var copyText = document.getElementById("depoinput");
        copyText.select();
        document.execCommand("Copy");
        alert("Copied the text: " + copyText.value);
    }
}

function buildSendForm(a, b, c)
{

    $("#walletSendLabel").val(a);
    $("#walletSendAddress").val(b);
    $("#walletSendAmount").val(c);

}
</script>
';



$content = '';

/**
 * A payment has been submitted
 */
if(@$_POST['payment_do'])
{
    $payment_from   =   $_POST['payment_from'];
    $payment_from = "LbjUNCvnSW7E7htbQ71HokB4KhmgaEYfy6";
    $payment_auth   =   $_POST['payment_auth'];
    $payment_to     =   str_replace(" ","", $_POST['payment_to']);
    $payment_ext    =   floatval($_POST['payment_amount_ext']);
    if($payment_ext >= 1) //Example: 500 -> 0.500
        $payment_ext = floatval("0.".$payment_ext);

    $payment_amount =   intval($_POST['payment_amount']) + $payment_ext;
    $payment_fee    =   floatval($_POST['payment_fee']);
    $payment_total  =   floatval($payment_amount + $payment_fee);
    
    
    try
    {
        //Check for negativity
        if($payment_amount <= 0 || $payment_fee <= 0 || $payment_total <= 0)
            throw new Exception("You may not enter negative values.");
            
        if(!Linda::isValidAddress($payment_to))
            throw new Exception("The wallet you have tried to send is invalid (".$payment_to.").");
                
        //Verify the wallet sent from is the wallet of the session owner
        /*if(!LindaSQL::verify($_SESSION['UserID'], $payment_auth))
            throw new Exception("Google Authentication key is incorrect. Please try again.");
        */
        //Verify that the wallet owner is the user logged in
        if(!LindaSQL::verifyOwner($_SESSION['UserID'], $payment_from))
            throw new Exception("You attempted to send cash from a wallet which is not owned by you.");
        
        Linda::sendCash($payment_from, $payment_to, $payment_amount, $payment_fee);
        
        
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    
    //done
}

$_ACCOUNT['Wallets'] = LindaSQL::getWalletInfoTableByAccount($_SESSION['UserID']);
//$money = Linda::getLindaByAccount($_SESSION['UserID']);
$balance = Linda::getBalanceByAccount($_SESSION['UserID']);

/**
 * Creation of a new wallet
 */
if(@$_POST['do_create'] == 1)
{
   $swalCreationSuccess = Linda::createWallet($_SESSION['UserID'], $_POST['walletLabel']) ? "true" : "false";
}

$selectedQR = null;
$lastDepDate = date("m/d/Y");
$lastDepValue = 0;
$lastWitDate = date("m/d/Y");
$lastWitValue = 0;
$tableContent = null;
$count = 1;
$qrVar = null;
foreach($_ACCOUNT['Wallets'] as $tmpWallet)
{
    $balance = Linda::getBalanceByWallet($tmpWallet[1]);  
    QRcode::png($tmpWallet[3], "./include/img/".$tmpWallet[2].".png");
    $selectedQR = $tmpWallet[2];
    
    $tableContent .=
    '<tr>
    <td>'.$count++.'</td>
    <td>'.$tmpWallet[2].'
    <td>
    <a href="./?act=wallet&wid='.$tmpWallet[3].'">'.$tmpWallet[3].'</a>
    <a data-toggle="modal" class="btn btn-primary pull-right" onclick="buildREF(\''.$tmpWallet[3].'\',\'copy\')">Copy</a>
    </td>
    <td>'.$balance.'</td>
    <td>
    <a data-toggle="modal" class="btn btn-primary" href="#deposit-form" onclick="buildREF(\''.$tmpWallet[3].'\')">deposit</a>
    <a data-toggle="modal" class="btn btn-primary" href="#withdraw-form" onclick="buildSendForm(\''.$tmpWallet[2].'\',\''.$tmpWallet[3].'\', \''.$balance.'\')">withdraw</a>
    </td>
    </tr>';
}
$content .= '

<input type="text" hidden="true" value="Hello World" name="address" id="address">


<script>
    function createWallet(istrue) {
    if(istrue)
        swal("Success!", "A new wallet was created.", "success");
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
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">Updated</span>
                        <h5>Total Income</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">'.$balance.' Linda</h1>
                        <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                        <small>Total views</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">Annual</span>
                        <h5>Deposit</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><button type="button" class="btn btn-sm btn-primary"> 
                            Deposit Now</button></h1>
                        <div class="stat-percent font-bold text-info">'.$lastDepValue.' Linda <i class="fa fa-level-down"></i></div>
                        <small>last deposit '.$lastDepDate.'</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">Annual</span>
                        <h5>Withdrawal</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><a data-toggle="modal" class="btn btn-primary" href="#modal-form">Withdraw Now</a></h1>
                        <div class="stat-percent font-bold text-info">'.$lastWitValue.' Linda <i class="fa fa-level-up"></i></div>
                        <small>last withdrawal '.$lastWitDate.'</small>
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
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
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
                        <th>Address </th>
                        <th>Balance</th>
                        <th>Action </th>

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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">

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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">
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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">

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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">
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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">
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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">
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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">

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
                                        <img alt="image" class="img-circle message-avatar" src="./include/img/linda_icon.png">
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
                                        <div class="form-group"><label>Label</label></div>
                                        <input type="hidden" value="1" name="do_create" />
                                        <div class="input-group col-md-12">
                                            <input type="text" name="walletLabel" id="walletLabel" class="form-control" REQUIRED>
                                            <span class="input-group-btn"> 
                                                <button href="./?action=add" class="btn btn-primary">Create</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="deposit-form" class="modal fade" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12"><h3 class="m-t-none m-b">Deposit</h3>
    
                                    <p>Send coins to this wallet.</p>
    
                                    <form role="form">
                                        <iframe style="border:0; width:50%; height:50%" src="./include/img/'.$selectedQR.'.png"></iframe>
                                        <div class="form-group"><label>Address</label></div>
                                        <div class="input-group col-md-12">
                                            <input type="text" id="depoinput" disabled="true" class="form-control">
                                            <span class="input-group-btn"> 
                                                <button type="button" class="btn btn-primary">Copy</button> 
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
    
                                    <p>You may send coins to other Linda Wallets using the form below.<br />
                                    * Please make sure all input is correct before submitting!</p>
    <hr />
                                    <form role="form" method="POST">


                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Wallet Label</span> 
                                            <input disabled="true" type="text" class="form-control" REQUIRED id="walletSendLabel" /> 
                                        </div>
<div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Wallet Address</span> 
                                            <input disabled="true" type="text" class="form-control" name="payment_from" REQUIRED id="walletSendAddress" /> 
                                        </div>
<div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Wallet Amount</span> 
                                            <input disabled="true" type="text" class="form-control" REQUIRED id="walletSendAmount" /> 
                                        </div>

<hr />


                                        <div class="form-group"><label>To</label> 
<input type="text" placeholder="Enter address" class="form-control" name="payment_to" REQUIRED></div>

                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Linda</span> 
                                            <input type="number" name="payment_amount" class="form-control" REQUIRED> 
                                            <span disabled="true" class="input-group-addon">.</span>
                                            <input type="number" name="payment_amount_ext" min="0.000000" max="0.999999" step="0.000001" value="0.000000" class="form-control"> 
                                        </div>    
                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon">Fee</span> 
                                            <input type="number" name="payment_fee" min="0.0000" step="0.0001" value="0.0001" class="form-control"> 
                                        </div>   
                                        <div class="input-group m-b">
                                            <span disabled="true" class="input-group-addon"><img style="width: 35px; height: 35x;" src="./include/img/gauth.png" /></span> 
                                            <input type="number" class="form-control" placeholder="Google Auth key" style="height: 50px;" name="payment_auth" required>
                                        </div>
                                        </br>                                    
                                        <div>
                                            <input type="hidden" name="payment_do" value="1" />
                                            <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Withdraw</strong></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
';