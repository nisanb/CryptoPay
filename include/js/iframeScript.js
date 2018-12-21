var marchantId = $("userId").val(); 
var key = $("#key").val(); 
var itemID = "";
var itemName = "";
var price = "";
var currency = '';

var selectedIconName = '1';
var icon = "1.svg";
var iconHight = 50;
var iconWidth = 50;
var iconColor = "black";
var buttonClass = "btn btn-w-m btn-default";
var buttonWidth = 100;
var buttonHight = 50;
var buttonText = "";

var currenciesArray = ["BTC", "Linda"];

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


function isValidItemDetails(){
	itemID = $("#itemId").val();
	itemName = $("#itemName").val();
	itemPrice = $("#itemPrice").val();
	
	if (itemID.trim().length < 1) {
		$("#itemId").css("background-color", "#FFC1C5");
		return false;
	}
	else {
		$("#itemId").css("background-color", "white");
	}
	
	if (itemName.trim().length < 1) {
		$("#itemName").css("background-color", "#FFC1C5");
		return false;
	}
	else {
		$("#itemName").css("background-color", "white");
	}
	
	if (itemPrice.trim().length < 1 && !$.isNumeric(itemPrice)) {
		$("#itemPrice").css("background-color", "#FFC1C5");
		return false;
	}
	else {
		$("#itemPrice").css("background-color", "white");
	}
	
	if (!currenciesArray.includes(currency)){
		$("#coinType").css("background-color", "#FFC1C5");
		return false;
	}
	else{
		$("#coinType").css("background-color", "white");
	}

	return true;
}


function itemIdChanged(){
	itemID = $("#itemId").val();
	if (itemID.trim().length < 1) {
		$("#itemId").css("background-color", "#FFC1C5");
		return false;
	}
	else {
		$("#itemId").css("background-color", "white");
	}
	buildIcon();
}

function itemNameChanged(){
	itemName = $("#itemName").val();
	if (itemName.trim().length < 1) {
		$("#itemName").css("background-color", "#FFC1C5");
		return false;
	}
	else {
		$("#itemName").css("background-color", "white");
	}
	buildIcon();
	
} 

function itemIPriceChanged(){
	itemPrice = $("#itemPrice").val();
	if (itemPrice.trim().length < 1 && !$.isNumeric(itemPrice)) {
		$("#itemPrice").css("background-color", "#FFC1C5");
		return false;
	}
	else {
		$("#itemPrice").css("background-color", "white");
	}
	buildIcon();
} 


function currenyChanged(){
	currency = $('#coinType option:selected').val();
	if (!currenciesArray.includes(currency)){
		$("#coinType").css("background-color", "#FFC1C5");
		return false;
	}
	else{
		$("#coinType").css("background-color", "white");
	}
	buildIcon();
	
    
	//dycmically add options to select
//	var option;  
//    var select1 = document.getElementById("coinType");
//    for (i = maxYear; i > 1991; i--) {
//        option = document.createElement('option');
//        option.text = option.value = i;
//        select1.add(option, i);
//    }
}




function buildIcon() {
    icon = selectedIconName + ".svg";
    $("#preview").empty();
    $("#preview").append('<button type="button" class="' + getButtonCodeToColor(buttonClass) + '" style="font-size: 2em; width:' + buttonWidth +'px;" >' + buttonText + 
							'<img src="./include/img/iframeImages/' + icon + '" style="width:' + iconWidth  +'px; padding-right:10px" /></Button>');
	//$("#code").empty();
    if (isValidItemDetails()){
    	$("#code").val('<iframe style="border: 0px;" id="CryptoSell" src="http://localhost/CryptoSell/api.php?img='+selectedIconName+'&iw='+iconWidth+'&ic='+iconColor+
				'&bw=' + buttonWidth + '&bc='+ buttonClass +'&bt='+ buttonText +'&key='+ key +'&iid='+ itemID +'&in=' +itemName+
				'&prc=' + itemPrice + '&crnc='+ currency +'"><iframe>');
    }
    else{
    	$("#code").val("Error: Please insert valid item details");
    }
}




function copy(){
	  var copyText = document.getElementById("code");
	  copyText.select();
	  document.execCommand("copy");
}

$(document).ready(function (){
	var toAppend = '';
	for (var i=1; i <= 28; i++){
        toAppend += '<div class="infont col-md-3 col-sm-4" ><div style="pointer: cursor;" onclick="getSelectedIcon('+i+')"><img src="./include/img/iframeImages/'+i+'.svg"/> </div></div>';
    }
	for (var i=1; i <= 28; i++){
        toAppend += '<div class="infont col-md-3 col-sm-4" ><div style="pointer: cursor;" onclick="getSelectedIcon('+i+')"><img src="./include/img/iframeImages/'+i+'.svg"/> </div></div>';
    }                
	toAppend += '<div class="clearfix"></div>';
	$("#imageContainer").append(toAppend);
});






//not working
function iconColorChanged(){
	console.log("changed");
		jQuery('img.svg').each(function () {
			var $img = jQuery('#' + icon + '.svg');
			var imgID = $img.attr('id');
			var imgClass = $img.attr('class');
			var imgURL = $img.attr('src');
			
			
			jQuery.get(imgURL, function (data) {
				
				// Get the SVG tag, ignore the rest

				var $svg = jQuery(data).find('svg');
				// Add replaced image's ID to the new SVG
				if (typeof imgID !== 'undefined') {
					$svg = $svg.attr('id', imgID);

				}

				// Add replaced image's classes to the new SVG
				if (typeof imgClass !== 'undefined') {
					$svg = $svg.attr('class', imgClass + ' replaced-svg');
				}

				// Remove any invalid XML tags as per http://validator.w3.org
				$svg = $svg.removeAttr('xmlns:a');
				$svg.css("fill", '#29abe2');
				// Replace image with new SVG
				$img.replaceWith($svg);
			}, 'xml');

		});
	}

function redirectToPayment(){
	
	
	location.href = 'www.walla.co.il';
	//window.open(document.URL, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}

function getButtonCodeToColor(code){
	if (code == 1) return "btn btn-w-m btn-default";
	else if (code == 2) return "btn btn-w-m btn-primary";
	else if (code == 3) return "btn btn-w-m btn-success";
	else if (code == 4) return "btn btn-w-m btn-info";
	else if (code == 5) return "btn btn-w-m btn-warning";
	else if (code == 6) return "btn btn-w-m btn-danger";
}