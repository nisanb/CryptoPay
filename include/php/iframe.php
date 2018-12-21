<?php
$userId ='';
$walletID = 'empty';
if (isset($_SESSION['UserID'])){
    $userId = $_SESSION['UserID'];
}

if (isset($_GET['wid'])){
    $walletID = $_GET['wid'];
}

if (!CryptoSQL::verifyOwner($userId, $walledId)){
    new Exception("Wllet does not belong to user");
}

    


$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">
                    <script scr="./include/js/SVGJS.js"> </script>';

$include_footer = '  <!-- FooTable -->
<script src="./include/js/iframeScript.js"> </script>
';
$content = "";



// $income = CryptoSQL::getTotalBalaceOfAccount($walletID);
$title = "View Wallet - ".$wallet->walletLabel;
    
// $lastDepDate = date("m/d/Y");
// $lastDepValue = 0;
// $lastWitDate = date("m/d/Y");
// $lastWitValue = 0;

// $tableContent = null;
// $tranCount = 1;
// $tranStatus = 1;
// $tranDate = null;
// $tranType = "";
// $tranOwner = "";
// $tranAmount = 0;
// $transactions = array_reverse($wallet->transactions);

$content .='
            <div id="ifarmeGeneratorContent" class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <h2>Icon</h2>
                        Please pick the color of your icon
                        <input id="cp" type="color" class="form-control input-lg" value=""/>
						Please select the size of your icon (20 - 200)
                        <input id="iconWidth" maxlength="3" class="form-control" value="50" onchange="changeIconWidth()"/>
                    </div>
					<div class="ibox float-e-margins">
						<h2>Button</h2>
						Please select the size of your button (20 - 500)
						<input id="buttonWidth" maxlength="3" class="form-control" value="100" onchange="changeButtonWidth()"/>
						Select button text
						<input id="buttonText" maxlength="20" class="form-control" value="" onchange="changeButtonText()"/>		
					Select button type
						<p>
							<button type="button" class="btn btn-w-m btn-default" onclick="setSeletedClass(1)">Default</button>
							<button type="button" class="btn btn-w-m btn-primary" onclick="setSeletedClass(2)">Green</button>
							<button type="button" class="btn btn-w-m btn-success" onclick="setSeletedClass(3)">Blue</button>
							<button type="button" class="btn btn-w-m btn-info" onclick="setSeletedClass(4)">Turquoise</button>
							<button type="button" class="btn btn-w-m btn-warning" onclick="setSeletedClass(5)">Yellow</button>
							<button type="button" class="btn btn-w-m btn-danger" onclick="setSeletedClass(6)">Red</button>					
						</p>	
					</div>
                    <div class="ibox float-e-margins">
                        <h2>Item</h2>
                        Item ID
                        <input id="itemId" maxlength="40" class="form-control" value="" onfocusout="itemIdChanged()"/>
						Item name
                        <input id="itemName" maxlength="20" class="form-control" value="" onfocusout="itemNameChanged()"/>
                        Item Price
                        <input id="itemPrice" type="number" class="form-control" value="" onfocusout="itemIPriceChanged()"/>
                        Curreny
                        <select id="coinType" class="form-control" onchange="currenyChanged()">
                            <option value="">- Select Currency - </option>
                            <option value="BTC">Bitcoin</option>
                            <option value="Linda">Linda</option>
                        </select>
                    </div>    
			
                </div>
			

                <div class="col-lg-9">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>All icons <small class="m-l-sm">All icons in collection - <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome</a> </small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="ibox-content icons-box">



                            <div id="imageContainer">
                                <style>
                                    img {
                                        width: 40px;                                       
                                    }

                                    div {
                                        padding-top: 5px;
                                    }
                                </style>


                                <h3> Select Icon </h3>

                            </div>
                        </div>
                    </div>

                </div>
                <div>
                    <div>
                        <h3>Icon Preview</h3>
                        <div id="preview"

						</div>

                    </div>
                    <h3>Get your iframe code</h3>
                    <button id="copy" class="btn btn-white" onclick="copy()"><i class="fa fa-copy"></i> Copy</button>

                    <div class="m-t">
                        <strong>HTML iframe code</strong>
                    </div>
                    <input id="code" class="form-control">
                        
                    </pre>
                </div>
            </div>
        </div>
    
    
    <input id="key" hidden="hidden" value="' . $walletID . '"/>
    <input id="userId" hidden="hidden" value="' .$userId. '" />
';


