<?php
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">
                    <script scr="./include/js/SVGJS.js"> </script>';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>
            <script>
            var marchantId = \'mickey.shalev@gmail.com\';
			var selectedIconName = \'1\';
            var icon = \'1.svg\';
            var iconHight = 50;
            var iconWidth = 50;
            var iconColor = "black";
			var buttonClass = "btn btn-w-m btn-default";
			var buttonWidth = 100;
			var buttonHight = 50;
			var buttonText = "";
			
            function getSelectedIcon(num) {
                selectedIconName = num;
                buildIcon();
            }

            function changeIconWidth(){
                iconWidth = $("#iconWidth").val();
				if (!$.isNumeric(iconWidth) || iconWidth < 20 || iconWidth > 500){
					return;
				}
                buildIcon();
            }
			
			function changeColor(){
				iconColor = $("#iconColor").val();
				buildIcon();
			}


			function setSeletedClass(selectedClass){
				buttonClass = selectedClass;
				buildIcon();
			}
			
			function changeButtonWidth(){
				buttonWidth = $("#buttonWidth").val();
				if (!$.isNumeric(buttonWidth) || buttonWidth < 20 || buttonWidth > 500){
					return;
				}				
				buildIcon();
			}
			
			function changeButtonText(){
				buttonText = $("#buttonText").val();
				buildIcon();
			}
			
            function buildIcon() {
				console.log($(\'1.svg\').toString());
                console.log(\'Icon -> \' + icon);
                console.log(\'Width -> \' + iconWidth);
                console.log(\'Color -> \' + iconColor);
				console.log(\'Button Class -> \' + buttonClass);
                icon = selectedIconName + ".svg";
                $("#preview").empty();
                $("#preview").append(\'<button type="button" class="\' + buttonClass + \'" style="font-size: 2em; width:\' + buttonWidth +\'px;" >\' + buttonText + 
										\'<img src="iframeImages/\' + icon + \'" style="width:\' + iconWidth  +\'px; padding-right:10px" /></Button>\');
				//$("#code").empty();
				$("#code").val(\'<iframe id="cryptopay" src="cryptopay/pay.php?key=\'+marchantId+\'&img=\'+selectedIconName+\'&iw=\'+iconWidth+\'&ic=\'+iconColor+\' +
								\'bw=\' + buttonWidth + \'&bc=\'+ buttonClass +\'&bt=\'+ buttonText +\'/>\');
            }
			
			function copy(){
				  var copyText = document.getElementById("code");
				  copyText.select();
				  document.execCommand("copy");
			}
			
			$(document).ready(function (){
				var toAppend = \'\';
				for (var i=1; i <= 28; i++){
					toAppend += \'<div class="infont col-md-3 col-sm-4" ><div style="pointer: cursor;" onclick="getSelectedIcon(\'+i+\')"><img src="iframeImages/\'+i+\'.svg"/> </div></div>\';
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
							<button type="button" class="btn btn-w-m btn-default" onclick="setSeletedClass(\'btn btn-w-m btn-default\')">Default</button>
							<button type="button" class="btn btn-w-m btn-primary" onclick="setSeletedClass(\'btn btn-w-m btn-primary\')">Green</button>
							<button type="button" class="btn btn-w-m btn-success" onclick="setSeletedClass(\'btn btn-w-m btn-success\')">Blue</button>
							<button type="button" class="btn btn-w-m btn-info" onclick="setSeletedClass(\'btn btn-w-m btn-info\')">Turquoise</button>
							<button type="button" class="btn btn-w-m btn-warning" onclick="setSeletedClass(\'btn btn-w-m btn-warning\')">Yellow</button>
							<button type="button" class="btn btn-w-m btn-danger" onclick="setSeletedClass(\'btn btn-w-m btn-danger\')">Red</button>					
						</p>	
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
';


