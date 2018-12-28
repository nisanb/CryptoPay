<?php
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>
';
$content = "";

$walletID = @$_GET['wid'];

$wallet = CryptoSQL::getWalletInformation($walletID);
$income = CryptoSQL::getTotalBalaceOfWallet($walletID);
Logger::log("UPDATING Wallet.php");
$title = "View Wallet - " . $wallet->walletLabel;

$lastDepDate = date("m/d/Y");
$lastDepValue = 0;
$lastWitDate = date("m/d/Y");
$lastWitValue = 0;

$tableContent = null;
$tranCount = 1;
$tranStatus = 1;
$tranDate = null;
$tranType = "";
$tranOwner = "";
$tranAmount = 0;
$transactions = array_reverse($wallet->transactions);

foreach ($transactions as $trans) {
    $color = "green";
    $tranStatus = ($trans->receivedAmount / $trans->requiredAmount) * 10;
    $tranDate = date('m/d/Y G:i:s', strtotime($trans->timeStarted));
    if($tranStatus == 10)
    {
        $color = "green";
        $received = "Received";
    } else if($tranStatus > 0)
    {
        $color = "orange";
        $received = "Partially Received";
    }
    else {
        $color = "black";
        $received = "Pending";
    }

    $tableContent .= '<tr>
    <td>' . $tranCount ++ . '</td>
<td>' . $trans->id . '</td>
    <td aling="center" title="Total Confirmations: ' . $tranStatus . '">
        <span class="pie" style="display: none;">' . $tranStatus . ',' . (10 - $tranStatus) . '</span>
    </td>
    <td>' . $tranDate . '</td>
    
    <td aling="center"><span style="color: ' . $color . '">'.$received.'</span></td>
    <td><span style="color: ' . $color . '">' . number_format($trans->requiredAmount, 8) . '</span> ' . $trans->currency . '</td>
    </tr>';
}
$content .= '
    
<input type="text" hidden="true" value="Hello World" id="address">
    
    
<script>
    function createWallet() {
        swal("Success!", "A new wallet was created.", "success");
    }
</script>
    
  <div class="row">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">Updated</span>
                        <h5>Total Income</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">' . $income . ' BTC</h1>
                        <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                        <small>Total views</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">Annual</span>
                        <h5>Wallet Information</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            Display information..</h1>
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
                        <h1 class="no-margins"><button type="button" class="btn btn-sm btn-primary">
                            Withdraw Now</button></h1>
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
            <h5>My Transactions</h5>
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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                            
                        <th>#</th>
                        <th>Transaction ID </th>
                        <th>Status </th>
                        <th>Date </th>
                        <th>Type </th>
                        <th>Amount </th>
                            
                    </tr>
                    </thead>
                    <tbody>' . $tableContent . '</tbody>
                </table>
            </div>
                        
        </div>
        </div>
        </div>
                        
        </div>
                        
                        
        </div>
                        
        </div>
     
';


