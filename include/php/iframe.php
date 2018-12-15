<?php
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">
                    <script scr="./include/js/SVGJS.js"> </script>';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>
    
        <script>
            var selectedIconName = \'1\';
            var icon = \'1.svg\';
            var hight = 20;
            var width = 50;
            var marchantId = \'mickey.shalev@gmail.com\';
            var color = "black";

            function getSelectedIcon(num) {
                selectedIconName = num;
                buildIcon();
            }

            function changeWidth(){
                width = $("#width").val();
				if (!$.isNumeric(width) || width < 20 || width > 500){
					return;
				}
                buildIcon();
            }
			
			function changeColor(){
				color = $("#color").val();
				buildIcon();
			}


            function buildIcon() {
				console.log($(\'1.svg\').toString());
                console.log(\'Icon -> \' + icon);
                console.log(\'Width -> \' + width);
                console.log(\'Color ->\' + color)
                icon = selectedIconName + ".svg";
                $("#preview").empty();
                $("#preview").append(\'<img src="./include/img/iframeImages/\' + icon + \'" style="width:\' + width  +\'px;" />\');
				//$("#code").empty();
				$("#code").val(\'<iframe id="cryptopay" src="cryptopay/pay.php?key=\'+marchantId+\'&img=\'+selectedIconName+\'&w=\'+width+\'&c=\'+color+\'" />\');
            }
			
			function copy(){
				  var copyText = document.getElementById("code");
				  copyText.select();
				  document.execCommand("copy");
			}
			
			$(document).ready(function (){
				var toAppend = \'\';
				for (var i=1; i <= 28; i++){
					toAppend += \'<div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(\'+i+\')"><img src="./include/img/iframeImages/\'+i+\'.svg" /> </div></div>\';
				}    
				toAppend += \'<div class="clearfix"></div>\';
				$("#imageContainer").append(toAppend);
			});
    </script>
';
$content = "";



$income = LindaSQL::getTotalBalaceOfAccount($walletID);
$title = "View Wallet - ".$wallet->walletLabel;
    
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

$content .='
            <div id="ifarmeGeneratorContent" class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <h2>Select Color</h2>
                        Please pick the color of your icon
                        <input id="cp" type="color" class="form-control input-lg" value="" />
                    </div>
                    <div class="ibox float-e-margins">
                        <h2>Select Size</h2>
                        Please select the size of your icon (20 - 500)
                        <input id="width" maxlength="3" class="form-control" value="50" onchange="changeWidth()"/>
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
                        <div id="preview"></div>

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
    
';


