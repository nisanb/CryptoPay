/**
 * 
 */
var allowSubmit = true;
    
    
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


function buildSendForm(a, b, c)
{
    
    $("#walletSendLabel").val(a);
    $("#walletSendAddress").val(b);
    $("#walletSendAmount").val(5);

    $("#payment_amount").attr("max", 5);
    $("#payment_amount").attr("min", 0.0001);
    
    $("#withrawForm").hide();
    $("#chooseCoin").show();
    
    allowSubmit = true;
}


function showWithdraw(currenyName){
    $("#withrawForm").show();
    $("#chooseCoin").hide();
    
    //rebuild the form (Linda or Bitcoin)
    //buildSendForm();
}



function handleForm(){
	if (allowSubmit){
		allowSubmit = false;
		return true;
	}
	
	return false;
	
	
}