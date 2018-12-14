<?php
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">';
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
                $("#preview").append(\'<img src="./includes/img/iframeImages/\' + icon + \'" style="width:\' + width  +\'px;" />\');
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
					toAppend += \'<div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(\'+i+\')"><img src="iframeImages/\'+i+\'.svg" /> </div></div>\';
				}
				toAppend += \'<div class="clearfix"></div>\';
				//$("#imageContainer").append(toAppend);
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
                        <input id="cp" type="color" class="form-control input-lg" value="">
                    </div>
                    <div class="ibox float-e-margins">
                        <h2>Select Size</h2>
                        Please select the size of your icon (20 - 500)
                        <input id="width" maxlength="3" class="form-control" value="50" onchange="changeWidth()">
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

                            <div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(1)"><img src="./include/img/iframeImages/1.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(2)"><img src="./include/img/iframeImages/2.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(3)"><img src="./include/img/iframeImages/3.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(4)"><img src="./include/img/iframeImages/4.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(5)"><img src="./include/img/iframeImages/5.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(6)"><img src="./include/img/iframeImages/6.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(7)"><img src="./include/img/iframeImages/7.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(8)"><img src="./include/img/iframeImages/8.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(9)"><img src="./include/img/iframeImages/9.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(10)"><img src="./include/img/iframeImages/10.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(11)"><img src="./include/img/iframeImages/11.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(12)"><img src="./include/img/iframeImages/12.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(13)"><img src="./include/img/iframeImages/13.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(14)"><img src="./include/img/iframeImages/14.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(15)"><img src="./include/img/iframeImages/15.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(16)"><img src="./include/img/iframeImages/16.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(17)"><img src="./include/img/iframeImages/17.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(18)"><img src="./include/img/iframeImages/18.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(19)"><img src="./include/img/iframeImages/19.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(20)"><img src="./include/img/iframeImages/20.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(21)"><img src="./include/img/iframeImages/21.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(22)"><img src="./include/img/iframeImages/22.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(23)"><img src="./include/img/iframeImages/23.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(24)"><img src="./include/img/iframeImages/24.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(25)"><img src="./include/img/iframeImages/25.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(26)"><img src="./include/img/iframeImages/26.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(27)"><img src="./include/img/iframeImages/27.svg"> </div></div><div class="infont col-md-3 col-sm-4"><div onclick="getSelectedIcon(28)"><img src="./include/img/iframeImages/28.svg"> </div></div><div class="clearfix"></div></div>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <h3>Icon Preview</h3>
                        <div id="preview"><img src="./include/img/iframeImages/25.svg" style="width:300px;"></div>

                    </div>
                    <h3>Get your iframe code</h3>
                    <button id="copy" class="btn btn-white" onclick="copy()"><i class="fa fa-copy"></i> Copy</button>

                    <div class="m-t">
                        <strong>HTML iframe code</strong>
                    </div>
                    <input id="code" class="form-control">
                        
                    
                </div>
            </div>
        </div>


';


