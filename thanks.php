<?php
if(!$_POST)
{
    die("You cannot enter this page directly.");
}
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
 
  <!-- Basic page needs ================================================== -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <style>
  @media screen and (max-width: 480px){
  	.hero__title {
  		'font-size':"33px"
  	}
  	.hero__subtitle {
		'font-size':"12px"
  	}
  	.site-header__logo.large--left {
		'font-size': '16px'
  	}
  	
  }
  </style>

  

  <!-- Title and description ================================================== -->
  <title>
  Your Shopping Cart &ndash; One Page Checkout for Shopify
  </title>

  

  <!-- Helpers ================================================== -->
  <!-- /snippets/social-meta-tags.liquid -->




  <meta property="og:type" content="website">
  <meta property="og:title" content="Your Shopping Cart">
  <meta property="og:url" content="https://one-page-checkout.com/cart">
  


<meta property="og:site_name" content="One Page Checkout for Shopify">




<meta name="twitter:card" content="summary">



  <link rel="canonical" href="https://one-page-checkout.com/cart">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#99ffcc">

  <!-- CSS ================================================== -->
  <link href="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/timber.scss.css?10711349972873934541" rel="stylesheet" type="text/css" media="all" />
  <link href="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/theme.scss.css?10711349972873934541" rel="stylesheet" type="text/css" media="all" />
  
  
  
  <link href="//fonts.googleapis.com/css?family=Montserrat:400" rel="stylesheet" type="text/css" media="all" />


  


  



  <!-- Header hook for plugins ================================================== -->
  <meta id="shopify-digital-wallet" name="shopify-digital-wallet" content="/11945066/digital_wallets/dialog">
<script id="shopify-features" type="application/json">{"accessToken":"1982d7ac3399bca5f73cb43ef11b3291","betas":[],"domain":"one-page-checkout.com","shopId":11945066,"smart_payment_buttons_url":"https:\/\/cdn.shopifycloud.com\/payment-sheet\/assets\/latest\/spb.js"}</script>
<script>var Shopify = Shopify || {};
Shopify.shop = "exclusive-checkout-demo.myshopify.com";
Shopify.currency = {"active":"EUR","rate":"1.0"};
Shopify.theme = {"name":"Exclusive Checkout theme","id":111663495,"theme_store_id":null,"role":"main"};
Shopify.theme.handle = "null";
Shopify.theme.style = {"id":null,"handle":null};</script>
<script id="__st">var __st={"a":11945066,"offset":-18000,"reqid":"cc34e36f-b280-4268-b6fd-4e0d452f8f9d","pageurl":"one-page-checkout.com\/cart?view=terms","t":"prospect","u":"0fcc7e8a2abe"};</script>
<script>window.ShopifyPaypalV4VisibilityTracking = true;</script>
<script>window.Shopify = window.Shopify || {};
window.Shopify.Checkout = window.Shopify.Checkout || {};
window.Shopify.Checkout.apiHost = "exclusive-checkout-demo.myshopify.com";</script>
<script>window.ShopifyAnalytics = window.ShopifyAnalytics || {};
window.ShopifyAnalytics.meta = window.ShopifyAnalytics.meta || {};
window.ShopifyAnalytics.meta.currency = 'EUR';
var meta = {"page":{}};
for (var attr in meta) {
  window.ShopifyAnalytics.meta[attr] = meta[attr];
}</script>
<script>window.ShopifyAnalytics.merchantGoogleAnalytics = function() {
  
};
</script>
<script class="analytics">(function () {
  var customDocumentWrite = function(content) {
    var jquery = null;

    if (window.jQuery) {
      jquery = window.jQuery;
    } else if (window.Checkout && window.Checkout.$) {
      jquery = window.Checkout.$;
    }

    if (jquery) {
      jquery('body').append(content);
    }
  };

  var trekkie = window.ShopifyAnalytics.lib = window.trekkie = window.trekkie || [];
  if (trekkie.integrations) {
    return;
  }
  trekkie.methods = [
    'identify',
    'page',
    'ready',
    'track',
    'trackForm',
    'trackLink'
  ];
  trekkie.factory = function(method) {
    return function() {
      var args = Array.prototype.slice.call(arguments);
      args.unshift(method);
      trekkie.push(args);
      return trekkie;
    };
  };
  for (var i = 0; i < trekkie.methods.length; i++) {
    var key = trekkie.methods[i];
    trekkie[key] = trekkie.factory(key);
  }
  trekkie.load = function(config) {
    trekkie.config = config;
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.onerror = function(e) {
      (new Image()).src = '//v.shopify.com/internal_errors/track?error=trekkie_load';
    };
    script.async = true;
    script.src = 'https://cdn.shopify.com/s/javascripts/tricorder/trekkie.storefront.min.js?v=2017.09.05.1';
    var first = document.getElementsByTagName('script')[0];
    first.parentNode.insertBefore(script, first);
  };
  trekkie.load(
    {"Trekkie":{"appName":"storefront","development":false,"defaultAttributes":{"shopId":11945066,"isMerchantRequest":null,"themeId":111663495,"themeCityHash":5866965342732068948}},"Performance":{"navigationTimingApiMeasurementsEnabled":true,"navigationTimingApiMeasurementsSampleRate":1.0},"Session Attribution":{}}
  );

  var loaded = false;
  trekkie.ready(function() {
    if (loaded) return;
    loaded = true;

    window.ShopifyAnalytics.lib = window.trekkie;
    

    var originalDocumentWrite = document.write;
    document.write = customDocumentWrite;
    try { window.ShopifyAnalytics.merchantGoogleAnalytics.call(this); } catch(error) {};
    document.write = originalDocumentWrite;

    
        window.ShopifyAnalytics.lib.page(
          null,
          {}
        );
      
    
  });

  
      var eventsListenerScript = document.createElement('script');
      eventsListenerScript.async = true;
      eventsListenerScript.src = "//cdn.shopify.com/s/assets/shop_events_listener-76ce6d7f3e50d4b8c05874c34d2ea1340c45e5babba61276dadcaeed488ca16a.js";
      document.getElementsByTagName('head')[0].appendChild(eventsListenerScript);
    
})();</script>
<script integrity="sha256-0xEZ/rW+4SEKTFurork0jfwbI2U45wPxBFoNkttkWrA=" defer="defer" src="//cdn.shopify.com/s/assets/storefront/express_buttons-d31119feb5bee1210a4c5baba2b9348dfc1b236538e703f1045a0d92db645ab0.js" crossorigin="anonymous"></script>
<script integrity="sha256-wVO039M3uMymMFjKDcSW90f3TAT7vRyPziQZqsSnEpc=" defer="defer" src="//cdn.shopify.com/s/assets/storefront/features-c153b4dfd337b8cca63058ca0dc496f747f74c04fbbd1c8fce2419aac4a71297.js" crossorigin="anonymous"></script>


  <!-- /snippets/oldIE-js.liquid -->


<!--[if lt IE 9]>
<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js" type="text/javascript"></script>
<script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/respond.min.js?10711349972873934541" type="text/javascript"></script>
<link href="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/respond-proxy.html" id="respond-proxy" rel="respond-proxy" />
<link href="//one-page-checkout.com/search?q=25b8f703de1662c8041f633bea0f01ab" id="respond-redirect" rel="respond-redirect" />
<script src="//one-page-checkout.com/search?q=25b8f703de1662c8041f633bea0f01ab" type="text/javascript"></script>
<![endif]-->


<!--[if (lte IE 9) ]><script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/match-media.min.js?10711349972873934541" type="text/javascript"></script><![endif]-->


  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
  <script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/modernizr.min.js?10711349972873934541" type="text/javascript"></script>

  
  

</head>


<body id="your-shopping-cart" class="template-cart">

  <div id="NavDrawer" class="drawer drawer--left">
    <div class="drawer__fixed-header">
      <div class="drawer__header">
        <div class="drawer__close drawer__close--left">
          <button type="button" class="icon-fallback-text drawer__close-button js-drawer-close">
            <span class="icon icon-x" aria-hidden="true"></span>
            <span class="fallback-text">Close menu</span>
          </button>
        </div>
      </div>
    </div>
    <div class="drawer__inner">

      

      

      <!-- begin mobile-nav -->
      <ul class="mobile-nav">
        
          
          
          
            <li class="mobile-nav__item">
              <a href="/" class="mobile-nav__link">Home</a>
            </li>
          
        
          
          
          
            <li class="mobile-nav__item">
              <a href="/products/one-page-checkout-shopify" class="mobile-nav__link">Catalog</a>
            </li>
          
        
          
          
          
            <li class="mobile-nav__item">
              <a href="http://microapps.com/shopify-experts/shopify-customizations/" class="mobile-nav__link">Extensions</a>
            </li>
          
        

        
        <li class="mobile-nav__spacer"></li>

        
        
          
            <li class="mobile-nav__item mobile-nav__item--secondary">
              <a href="/account/login" id="customer_login_link">Log In</a>
            </li>
            
            <li class="mobile-nav__item mobile-nav__item--secondary">
              <a href="/account/register" id="customer_register_link">Create Account</a>
            </li>
            
          
        
        
          <li class="mobile-nav__item mobile-nav__item--secondary"><a href="/search">Search</a></li>
        
          <li class="mobile-nav__item mobile-nav__item--secondary"><a href="/pages/about-us">About us</a></li>
        
      </ul>
      <!-- //mobile-nav -->
    </div>
  </div>
  <div id="CartDrawer" class="drawer drawer--right drawer--has-fixed-footer">
    <div class="drawer__fixed-header">
      <div class="drawer__header">
        <div class="drawer__title">Your cart</div>
        <div class="drawer__close">
          <button type="button" class="icon-fallback-text drawer__close-button js-drawer-close">
            <span class="icon icon-x" aria-hidden="true"></span>
            <span class="fallback-text">Close Cart</span>
          </button>
        </div>
      </div>
    </div>
    <div class="drawer__inner">
      <div id="CartContainer" class="drawer__cart"></div>
    </div>
  </div>

  <div id="PageContainer" class="is-moved-by-drawer">

    <div class="header-wrapper">
      <header class="site-header" role="banner">
        <div class="wrapper">
          <div class="grid--full grid--table">
            <div class="grid__item large--hide one-quarter">
              <div class="site-nav--mobile">
                <button type="button" class="icon-fallback-text site-nav__link js-drawer-open-left" aria-controls="NavDrawer">
                  <span class="icon icon-hamburger" aria-hidden="true"></span>
                  <span class="fallback-text">Site navigation</span>
                </button>
              </div>
            </div>
            <div class="grid__item large--one-third medium-down--one-half">
              
              
              
                <div class="h1 site-header__logo large--left" itemscope itemtype="http://schema.org/Organization">
              
                
                  <a href="/" itemprop="url">One Page Checkout for Shopify</a>
                
              
                </div>
              
            </div>
            <div class="grid__item large--two-thirds large--text-right medium-down--hide">
              
              <!-- begin site-nav -->
              <ul class="site-nav" id="AccessibleNav">
                <li class="site-nav__item site-nav--compress__menu">
                  <button type="button" class="icon-fallback-text site-nav__link site-nav__link--icon js-drawer-open-left" aria-controls="NavDrawer">
                    <span class="icon icon-hamburger" aria-hidden="true"></span>
                    <span class="fallback-text">Site navigation</span>
                  </button>
                </li>
                
                
                
                  
                    
                    
                      <li class="site-nav__item site-nav__expanded-item">
                        <a href="/" class="site-nav__link">Home</a>
                      </li>
                    
                  
                    
                    
                      <li class="site-nav__item site-nav__expanded-item">
                        <a href="/products/one-page-checkout-shopify" class="site-nav__link">Catalog</a>
                      </li>
                    
                  
                    
                    
                      <li class="site-nav__item site-nav__expanded-item">
                        <a href="http://microapps.com/shopify-experts/shopify-customizations/" class="site-nav__link">Extensions</a>
                      </li>
                    
                  

                
                
                
                  <li class="site-nav__item site-nav__expanded-item">
                    <a class="site-nav__link site-nav__link--icon" href="/account">
                      <span class="icon-fallback-text">
                        <span class="icon icon-customer" aria-hidden="true"></span>
                        <span class="fallback-text">
                          
                            Log In
                          
                        </span>
                      </span>
                    </a>
                  </li>
                
                <label class="currency-picker__wrapper">
  <span class="currency-picker__label"></span>
  <select class="currency-picker" name="currencies" style="display: inline; width: auto; vertical-align: inherit;">
  
  
  <option value="EUR" selected="selected">EUR</option>
  
    
    <option value="GBP">GBP</option>
    
  
    
    <option value="USD">USD</option>
    
  
    
  
  </select>
</label>

                

                
				 
                <li class="site-nav__item">
                  <a href="/cart" class="site-nav__link site-nav__link--icon cart-link js-drawer-open-right" aria-controls="CartDrawer">
                    <span class="icon-fallback-text">
                      <span class="icon icon-cart" aria-hidden="true"></span>
                      <span class="fallback-text">Cart</span>
                      <span id="numero" style="font-size: 0.8em;color:black;    position: relative;left: -7px;">5</span>
                    </span>
                      
              <!--      <span class="cart-link__bubble cart-link__bubble--visible"></span> -->
                  </a>
                </li>
                 

              </ul>
              <!-- //site-nav -->
            </div>
            <div class="grid__item large--hide one-quarter">
              <div class="site-nav--mobile text-right">
                <a href="/cart" class="site-nav__link cart-link js-drawer-open-right" aria-controls="CartDrawer">
                  <span class="icon-fallback-text">
                    <span class="icon icon-cart" aria-hidden="true"></span>
                    <span class="fallback-text">Cart</span>
                  </span>
                  <span class="cart-link__bubble cart-link__bubble--visible"></span>
                </a>
              </div>
            </div>
         <div class="grid__item large--hide one-quarter">
              <div class="site-nav--mobile text-right">
                <a href="/cart?view=terms" class="site-nav__link cart-link js-drawer-open-right" aria-controls="CartDrawer">
                  <span class="icon-fallback-text">
                    <span class="icon icon-cart" aria-hidden="true"></span>
                    <span class="fallback-text">Cart</span>
                  </span>
                  <span class="cart-link__bubble cart-link__bubble--visible"></span>
                </a>
              </div>
            </div>
         
          
          </div>

          

          

        </div>
      </header>
    </div>

    <main class="main-content" role="main">
      <div class="wrapper">
        <!-- /templates/cart.liquid -->
<script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/underscore.js?10711349972873934541" type="text/javascript"></script>
<script src="//cdn.shopify.com/s/assets/themes_support/option_selection-ea4f4a242e299f2227b2b8038152223f741e90780c0c766883939e8902542bda.js" type="text/javascript"></script>
      
         
<style>
  .label_disabled{
  
      opacity:.3;
      text-decoration: line-through;
  
  }
</style>

<div id="load-screen">
  <span id='processing_text'>
   Processing order...
  </span>
</div>

<style>
    
  
  .single-option-radio input[type='radio']:checked+label {
  border:none;
  background: RGB(147, 230, 180);
    color: #fff;
    opacity: 1;
  
  }
  
  #processing_text{
  
  margin: 0 auto;
    display: block;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin-top: -9px;
    text-align: center;
  }
#load-screen {
    width: 100%;
    height: 100%;
    background:  #000;
      position: fixed;
    opacity: 0.7;
 	 top: 0;
    left: 0;
 	 z-index:10000;
  color:#fff;
  font-size:20px;
  font-weight:bold;
  display:none;
}
</style>

<div class="grid">
  <div class="grid__item large--five-sixths push--large--one-twelfth">
    
    <h1 >Thank you for purchasing!</h1>
    <p>Hurray! This a small cheer that you purchase went through!</p>
       <p>You can view your orders in "My Orders" page.</p>

    <p> Thank you for using CryptoSell LTD services as a payment gateway!
      <br> <br /><p>Below are returned transaction details from your recent purchase:
      <br /><br />
      <pre>
      <?php
      print_r($_POST);
      ?>
      </pre>
      
    </p>
        <div class="cart__row medium-down--hide cart__header-labels">
          <div class="grid--full">
            <div class="grid__item large--two-fifths push--large--three-fifths">
              <div class="grid--full">
                <div class="grid__item one-third medium-down--half text-center">
                  Quantity
                </div>
                <div class="grid__item two-thirds medium-down--half text-right">
                  Total
                </div>
              </div>
            </div>
          </div>
        </div>

       
        
          <div class="cart__row">
            <div class="grid--full cart__row--table-large">

              <div class="grid__item large--three-fifths">
                <div class="grid">

                  <div class="grid__item one-third">
                    <a href="/products/one-page-checkout-shopify?variant=17209448583" class="cart__image">

                      
                      <img src="//cdn.shopify.com/s/files/1/1194/5066/products/imac_medium.jpeg?v=1457347442" alt="iMac">
                    </a>
                  </div>

                  <div class="grid__item two-thirds">
                    <a href="/products/one-page-checkout-shopify?variant=17209448583" class="h4 cart__product-name">
                      iMac
                    </a>
                   
                    

                    
                    

                    <p class="cart__product-meta">
                      <a href="/cart/change?line=1&amp;quantity=0">
                      </a>
                    </p>
                  </div>

                </div>
              </div>

              <div class="grid__item large--two-fifths">
                <div class="grid--full cart__row--table">
                  <div class="grid__item one-third text-center">
                    <input type="number" name="updates[]" id="updates_17209448583" class="cart__product-qty" value="5" min="0">
                  </div>
                  <div class="grid__item two-thirds text-right">
                    <span class="cart__price"><span class=money>$<?=$_POST['itemPrice'];?></span></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        

        <div class="cart__row">
         
          <div class="grid--full cart__row--table-large">
            <div class="grid__item large--two-fifths text-center large--text-right push--large--three-fifths">
              <div class="grid--full cart__row--table">
                <div class="grid__item one-half large--two-fifths large--text-center">
                  <p class="cart__subtotal">Cart</p>
                  
                  <p class="cart__subtotal">Total</p>
                   
                </div>
                <div class="grid__item one-half large--two-thirds">
                  <p class="cart__subtotal"><span class=money>$<?=$_POST['itemPrice'];?></span></p>
                   
                  
                  <p class="cart__subtotal"><b>$<?=$_POST['itemPrice'];?></b></p>
                </div>
               </div>
			   <small style="opacity: .6; font-size: .7em">Shipping + Handling fees included</small>


              
          </div>

        </div>
        

    
   
      
<hr class="hr--large">
       <div style='text-align:center'>
         <h2> Want to buy again?</h2>

         
         <p><a class="btn--secondary" href="./order.php" target="_blank" title="Exclusive Wholesaler Checkout">
         Click here to buy it now
           </a></p>
         
     
    
    
    <script type="text/javascript">
      
      var update_cart = function(){
              var id = $('.js-qty__num').attr('id').split('_')[1]

                			console.log( $('.js-qty__num').val(),id,line )
                			$('.js-qty__num').disabled(true);

              jQuery.post('/cart/change.js', line,
                          function(d){ document.location = '/cart';	} ,
                          'json'
                         )

      
      
      }
       
      
</script>
    
   
    
<script>
  
    var line_items = []
  
  var my_line =  { "variant_id": 17209448583,
                  	"quantity": 1,
                  "name": 'q',
                  "title": 'iMac',
                  "price": 100000/100
                 }
  line_items.push(my_line)    
  
  
 var getNextHighestIndex = function (arr, value) {
    var i = arr.length;
    while (arr[--i].time > value);
    return ++i; 
}


function getUrlParameter(sParam)  {
          var sPageURL = window.location.search.substring(1);
          var sURLVariables = sPageURL.split('&');
          for (var i = 0; i < sURLVariables.length; i++) 
          {
              var sParameterName = sURLVariables[i].split('=');
              if (sParameterName[0] == sParam) 
              {
                  return sParameterName[1];
              }
          }
      }    
  
  
  $(document).on('click','.single-option-selector__radio',function(){
    console.log(":",$(this).data('time'),my_hour_tab)
    
    if (1* $(this).data('time') < 1* my_hour_tab){
    
    console.log("NOR")
    $(this).prop('checked', false);
     $('.single-option-selector__radio[data-time="'+my_hour_tab+'"]')
          .prop('checked', true) ;
    } else {
      
    console.log("YES")
   
    
    }
    
 
  
  })

  
  function go_order(order, callback){
 
  
  
 var  url = "https://wholesaler2.herokuapp.com/proxy/exclusive-checkout-demo/admin/orders.json" 
 
  
    $.ajax({type: "POST",
            url: url,
                   dataType: "json",
                     contentType: "application/json",
                    data:  JSON.stringify(order)
                    })
			.done(function(data){
     							// alert('Wholesaler response:\n'+JSON.stringify(data))
                        		// alert(" recibido:"+JSON.stringify(data));
                            callback() 
      
      		console.log('error ok:'+ JSON.stringify(data)) 
            
             var misdatos = JSON.parse(JSON.stringify(data.responseJSON).replace(/\\/g, ''))
               
                         if(misdatos.errors){
                            $('#txt_error_message').html('One or more errors have been found in the address we have:<br>')
									$.each(datos.errors,function(key,value){
 									  console.log(" error->:"+key+"||"+value);
 									  $('#txt_error_message').append(value)

                                  	})
 									$('#txt_error_message').show()
                                    $('#load-screen').hide()
                              
                                }  else {
 									  callback()
 								}
      
      
      
                              
                          }
                  ) 
    		.error(function(data){
    			  console.log('error ER:'+ JSON.stringify(data)) 
             $('#txt_error_message')
             	.html('One or more errors have been found in your address:')
             	//.append('Please contact <a href="/pages/contact-us">customer support.</a><br>')       
                  
            
              
               var misdatos = JSON.stringify(data.responseJSON)
              
            
              
              var misdatos2= misdatos.replace(/\\/g, '')
                                            
                console.log('misdatos2 ER:',misdatos2) 
                                           
              var misdatos3 = JSON.parse(misdatos2)
              
                            $('#txt_error_message')
									$.each(misdatos3.errors,function(key,value){
 									  console.log(" error->:"+key+"||"+value);
 									  $('#txt_error_message').append('<br>'+value)

                                  	})
                                  
                                    
                     $('#txt_error_message').show()
                     $('#load-screen').hide()
                     $('#checkout')
                     	.removeClass('btn--loading')  
                     	.text('Pay')
                               
              

                                      
              			 
                          }
                  )  
      
}
  
$(document).ready(function(){
   
    
   var order_ready = false 
    
   var check_order_ready = function(){
     
      $('#txt_error_message').html('')
       
       if ( validateForm() ){
				$('#checkout').prop('disabled',false)
			return true
			
       } else {
      		 $('#checkout').prop('disabled',true)
       	    return false
       }

   
   }
   
    
  check_order_ready()  
    
    
    $('.input-group-field,.single-option-selector__radio')
    	.on('change',check_order_ready);
  
    
   $('form').on('submit', function(e){
                        e.preventDefault();
                         $('#go_order').trigger('click')
             });
  
  
  $('#form_order').submit(function(e){
 		e.preventDefault()
 		$('#checkout').trigger('click')
	})
  
  
  $('#checkout').click(function(e){
  
  		 
  })
  
  
  $('#years_select,#months_select').change(function(){
  
  var d = new Date();
  
    console.log('year:', $(this).val(),d.getFullYear(),d.getMonth())
    
    if(1*$(this).val() == 1* d.getFullYear()){
    
     	var n = d.getMonth();
      
    
            
      var options = $('#months_select').find('option')
      
      options.each(function(){
        if(1*$(this).val() <= 1*n){
        	$(this) .attr("disabled", "disabled")
            
             var next_valid_month = 1*n+1
             
              
             if(next_valid_month <10){
              	$('#months_select').val('0'+next_valid_month) 
             } else if(next_valid_month == 10 || next_valid_month == 11 ){
             	$('#months_select').val(next_valid_month) 
             } else if(next_valid_month == 12 ){
            		$('#months_select').val('01') 
                    
                    $('#years_select').val(1* d.getFullYear()+1)
             }
             
            
        } else {
        
        	$(this).removeAttr("disabled"); 
        }
      
      })
      
       
      
    } else {
    
      var options = $('#months_select').find('option')
      options.each(function(){
     	 $(this).removeAttr("disabled"); 
      
      })
    }
    
    
     
  
  })
  
  
 $(document).on('.js-qty__adjust','click',function(e){
        //console.log( $('.js-qty__num').val())
 		 console.log( '+')
  });
  
  
   
   
   
   
}); </script>


    <script>
   
  var validateForm = function(){
    
    var cart_valid = true
    
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
          }
    
        function campos_rellenos(){
              if($('#nombre').val() =='' 
                // || $('#apellidos').val()==''
                 || $('#direccion').val()==''
                         
                  ){
                 return false;
              } else {
               return true;
              }
        }
        function isPhone(Phone) {
         var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        if (filter.test(Phone)) {
            return true;
        }
        else {
            return false;
        }
        }
    
    
     
    console.log( isEmail( $('#email').val() ),
                //isPhone( $('#phone').val() ),
                campos_rellenos() 
                 )

          if( isEmail( $('#email').val() )
            // && isPhone( $('#phone').val() )
             && campos_rellenos() 

            ){
            return true

          } else {
            return false
          }

  }
 
  
  
</script>
   <script>
    // jquery.jsonp 2.2.1 (c)2012 Julian Aubourg | MIT License
    // http://code.google.com/p/jquery-jsonp/
    (function(a){function b(){}function c(a){A=[a]}function d(a,b,c,d){try{d=a&&a.apply(b.context||b,c)}catch(e){d=!1}return d}function e(a){return/\?/.test(a)?"&":"?"}function D(l){function V(a){O++||(P(),I&&(y[K]={s:[a]}),E&&(a=E.apply(l,[a])),d(l.success,l,[a,t]),d(D,l,[l,t]))}function W(a){O++||(P(),I&&a!=u&&(y[K]=a),d(l.error,l,[l,a]),d(D,l,[l,a]))}l=a.extend({},B,l);var D=l.complete,E=l.dataFilter,F=l.callbackParameter,G=l.callback,H=l.cache,I=l.pageCache,J=l.charset,K=l.url,L=l.data,M=l.timeout,N,O=0,P=b,Q,R,S,T,U;return l.abort=function(){!(O++)&&P()},d(l.beforeSend,l,[l])===!1||O?l:(K=K||h,L=L?typeof L=="string"?L:a.param(L,l.traditional):h,K+=L?e(K)+L:h,F&&(K+=e(K)+encodeURIComponent(F)+"=?"),!H&&!I&&(K+=e(K)+"_"+(new Date).getTime()+"="),K=K.replace(/=\?(&|$)/,"="+G+"$1"),I&&(N=y[K])?N.s?V(N.s[0]):W(N):(v[G]=c,S=a(s)[0],S.id=k+z++,J&&(S[g]=J),C&&C.version()<11.6?(T=a(s)[0]).text="document.getElementById('"+S.id+"')."+n+"()":S[f]=f,p in S&&(S.htmlFor=S.id,S.event=m),S[o]=S[n]=S[p]=function(a){if(!S[q]||!/i/.test(S[q])){try{S[m]&&S[m]()}catch(b){}a=A,A=0,a?V(a[0]):W(i)}},S.src=K,P=function(a){U&&clearTimeout(U),S[p]=S[o]=S[n]=null,w[r](S),T&&w[r](T)},w[j](S,x),T&&w[j](T,x),U=M>0&&setTimeout(function(){W(u)},M)),l)}var f="async",g="charset",h="",i="error",j="insertBefore",k="_jqjsp",l="on",m=l+"click",n=l+i,o=l+"load",p=l+"readystatechange",q="readyState",r="removeChild",s="<script>",t="success",u="timeout",v=window,w=a("head")[0]||document.documentElement,x=w.firstChild,y={},z=0,A,B={callback:k,url:location.href},C=v.opera;D.setup=function(b){a.extend(B,b)},a.jsonp=D})(jQuery)
</script>

<script>
    
  console.log('note:'+window.localStorage.getItem('note'))
     
  if( window.localStorage.getItem('note')){
         $('#cart_note_label').show()
  		 $('#cart_note_text').text(window.localStorage.getItem('note')).show()
  }
  

  
      
$('#checkout').click(function(e){
      e.preventDefault()
         
        
        var shipping_address =   { "address1" : $('#direccion').val(),
          "address2" : '' ,
            "city" : $('#city').val(),
              "company" : "null" ,
              "country" : $('#country').val(),
             "first_name" : $('#nombre').val().split(' ')[0] ,
              "last_name" :$('#nombre').val().split(' ')[0] || 'Doe' ,
              "phone" : $('#phone').val() ,
              "province" : $('#province').val(),
              "zip" : $('#postal_code').val() ,
              "name" : $('#nombre-apellidos').val() ,
             //"country_code" : "ES" ,
             // "province_code" : "B" 
                                 }
        
        
     var order =  {
            "order": {
                    "email": $('#email').val(),
                    "fulfillment_status": "unfulfilled",
                    "line_items":  line_items,
                    "financial_status": "pending",
              		"send_receipt": "true",
                    "shipping_lines": [
                                 { "code" : "Envío"  ,
                                   "price" : window.shipping_cost/100 ,
                                   "source" : "Web" ,
                                   "title" : "Envío" } 
                              ] ,
                     "total_price" : total_order ,
                    "taxes_included" : true,
       				"shipping_address": shipping_address,
                    "note":window.localStorage.getItem('note'),
             		 "tags":'microapps_exclusive_wholesaler_checkout',
                             
                  } 
      		 
          }
     
                     
    console.log('order:'+JSON.stringify(order))                
    
		 $('#load-screen').show()
       go_order(order, function(){ 
        document.location = "https://one-page-checkout.com/pages/order-success"
       
       })
         
    
       
 })
       
    $('#update-cart').click(function(e){
        e.preventDefault()
        document.location = '/cart'

    })  
   


   
  
</script>

  
 
      </div>
    </main>

    
    

    <hr class="hr--large">
    <footer class="site-footer small--text-center" role="contentinfo">
      <div class="wrapper">

        <div class="grid-uniform">

          
          

          
          

          
          
          

          
          

          

          
            <div class="grid__item one-half small--one-whole">
              <ul class="no-bullets site-footer__linklist">
                

          
          

                  <li><a href="/search">Search</a></li>

                

          
          

                  <li><a href="/pages/about-us">About us</a></li>

                
              </ul>
            </div>
          

          

          <div class="grid__item one-half small--one-whole large--text-right">
            <p>&copy; 2019, <a href="/" title="">One Page Checkout for Shopify</a><br><a href="https://shopify.com/?ref=microapps">Powered by microapps</a> · <a href="http://microapps.com/shopify-experts/shopify-wholesale/" >Shopify Wholesale</a></p>
            
              <ul class="inline-list payment-icons">
                
                  <li>
                    <span class="icon-fallback-text">
                      <span class="icon icon-american_express" aria-hidden="true"></span>
                      <span class="fallback-text">american express</span>
                    </span>
                  </li>
                
                  <li>
                    <span class="icon-fallback-text">
                      <span class="icon icon-jcb" aria-hidden="true"></span>
                      <span class="fallback-text">jcb</span>
                    </span>
                  </li>
                
                  <li>
                    <span class="icon-fallback-text">
                      <span class="icon icon-master" aria-hidden="true"></span>
                      <span class="fallback-text">master</span>
                    </span>
                  </li>
                
                  <li>
                    <span class="icon-fallback-text">
                      <span class="icon icon-visa" aria-hidden="true"></span>
                      <span class="fallback-text">visa</span>
                    </span>
                  </li>
                
              </ul>
            
          </div>
        </div>

      </div>
    </footer>

  </div>


  <script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/fastclick.min.js?10711349972873934541" type="text/javascript"></script>
  <script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/timber.js?10711349972873934541" type="text/javascript"></script>
  <script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/theme.js?10711349972873934541" type="text/javascript"></script>

  
  <script>
    
    
  </script>

  

  
  
  
    
      <script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/handlebars.min.js?10711349972873934541" type="text/javascript"></script>
  
      <!-- /snippets/ajax-cart-template.liquid -->

  <script id="CartTemplate" type="text/template">
  
    <form action="/cart" method="post" novalidate class="cart ajaxcart">
      <div class="ajaxcart__inner ajaxcart__inner--has-fixed-footer">
        {{#items}}
        <div class="ajaxcart__product">
          <div class="ajaxcart__row" data-line="{{line}}">
            <div class="grid">
              <div class="grid__item one-quarter">
                <a href="{{url}}" class="ajaxcart__product-image"><img src="{{img}}" alt="{{name}}"></a>
              </div>
              <div class="grid__item three-quarters">
                <div class="ajaxcart__product-name--wrapper">
                  <a href="{{url}}" class="ajaxcart__product-name">{{name}}</a>
                  {{#if variation}}
                    <span class="ajaxcart__product-meta">{{variation}}</span>
                  {{/if}}
                  {{#properties}}
                    {{#each this}}
                      {{#if this}}
                        <span class="ajaxcart__product-meta">{{@key}}: {{this}}</span>
                      {{/if}}
                    {{/each}}
                  {{/properties}}
                </div>

                <div id="tabla" class="grid--full display-table">
                  <div class="grid__item display-table-cell one-half">
                    <div class="ajaxcart__qty">
                      <button type="button" class="ajaxcart__qty-adjust ajaxcart__qty--minus icon-fallback-text" data-id="{{id}}" data-qty="{{itemMinus}}" data-line="{{line}}">
                        <span class="icon icon-minus" aria-hidden="true"></span>
                        <span class="fallback-text">&minus;</span>
                      </button>
                      <input type="text" name="updates[]" class="ajaxcart__qty-num" value="1" min="0" data-id="1" data-line="1" aria-label="quantity" pattern="[0-9]*">
                      <button type="button" class="ajaxcart__qty-adjust ajaxcart__qty--plus icon-fallback-text" data-id="{{id}}" data-line="{{line}}" data-qty="{{itemAdd}}">
                        <span class="icon icon-plus" aria-hidden="true"></span>
                        <span class="fallback-text">+</span>
                      </button>
                    </div>
                  </div>
                  <div class="grid__item display-table-cell one-half text-right">
                    <span class="ajaxcart__price">
                      {{{price}}}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{/items}}

        
      </div>
      <div class="ajaxcart__footer ajaxcart__footer--fixed">
        <div class="grid--full">
          <div class="grid__item two-thirds">
            <p class="ajaxcart__subtotal">Subtotal</p>
          </div>
          <div class="grid__item one-third text-right">
            <p class="ajaxcart__subtotal">{{{totalPrice}}}</p>
          </div>
        </div>
                
        <div id="menos12"><p style="text-align:center;">El pedido mínimo es de 12€, añade mas platos al carrito para continuar.</p></div>
       <div id="mas12"> 
        <p class="text-center ajaxcart__note">Shipping and taxes calculated at checkout</p>
       <!--
       <button type="submit" class="btn--secondary btn--full cart__checkout" name="checkout">
          Check Out &rarr;
        </button>
        -->
        <a id="mas12" href='/pages/checkout' class="btn--secondary btn--full " >
          MONEI Checkout  &rarr;
        </a><br><br>
       <a id="mas12" href='/cart' class="btn--secondary btn--full " >
          OnePage Checkout  &rarr;
        </a><br><br>
    <a id="mas12" href='/cart?view=terms' class="btn--secondary btn--full " >
          Exclusive Wholesaler Checkout  &rarr;
        </a>
    </div>
        
     
        
        
      </div>
    </form>
  
  </script>
  <script id="AjaxQty" type="text/template">
  
    <div class="ajaxcart__qty">
      <button type="button" class="ajaxcart__qty-adjust ajaxcart__qty--minus icon-fallback-text" data-id="{{id}}" data-qty="{{itemMinus}}">
        <span class="icon icon-minus" aria-hidden="true"></span>
        <span class="fallback-text">&minus;</span>
      </button>
      <input type="text" class="ajaxcart__qty-num" value="{{itemQty}}" min="0" data-id="{{id}}" aria-label="quantity" pattern="[0-9]*">
      <button type="button" class="ajaxcart__qty-adjust ajaxcart__qty--plus icon-fallback-text" data-id="{{id}}" data-qty="{{itemAdd}}">
        <span class="icon icon-plus" aria-hidden="true"></span>
        <span class="fallback-text">+</span>
      </button>
    </div>
  
  </script>
  <script id="JsQty" type="text/template">
  
    <div class="js-qty">
      <button type="button" class="js-qty__adjust js-qty__adjust--minus icon-fallback-text" data-id="{{id}}" data-qty="{{itemMinus}}">
        <span class="icon icon-minus" aria-hidden="true"></span>
        <span class="fallback-text">&minus;</span>
      </button>
      <input type="text" class="js-qty__num" value="1" min="1" data-id="1" data-min="1" data-max="1" aria-label="quantity" pattern="[0-9]*" name="{{inputName}}" id="{{inputId}}" disabled>
      <button type="button" class="js-qty__adjust js-qty__adjust--plus icon-fallback-text" data-id="1" data-qty="1" disabled>
        <span class="icon icon-plus" aria-hidden="true"></span>
        <span class="fallback-text">+</span>
      </button>
    </div>
  
  </script>

      <script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/ajax-cart.js?10711349972873934541" type="text/javascript"></script>
      <script>
        jQuery(function($) {
          ajaxCart.init({
            formSelector: '#AddToCartForm',
            cartContainer: '#CartContainer',
            addToCartSelector: '#AddToCart',
            enableQtySelectors: true,
            moneyFormat: "\u003cspan class=money\u003e€{{amount}}\u003c\/span\u003e"
          });
        });
      </script>
    
  

  

   

  

  <script>
  jQuery(function(){jQuery("body").on("click",'[name="checkout"], [name="goto_pp"], [name="goto_gc"]',function(){var e=!0,r="Please fill this out and you will be able to check out.";return jQuery('[name^="attributes"], [name="note"]').filter('.required, [required]').each(function(){jQuery(this).removeClass("error"),e&&""==jQuery(this).val()&&(e=!1,r=jQuery(this).attr("data-error")||r,jQuery(this).addClass("error"))}),e?void jQuery(this).submit():(alert(r),!1)})}),jQuery(window).unload(function(){var e=jQuery('form[action="/cart"]');if(e.size()){var r={type:"POST",url:"/cart/update.js",data:e.serialize(),dataType:"json",async:!1};jQuery.ajax(r)}});
  </script>
  
  <script>
    
    if($(window).width() < 481){
    	$('.hero__title').eq(0).css({'font-size':"33px"})
    	$('.hero__subtitle').eq(0).css({'font-size':"12px"})
    	$('.site-header__logo.large--left').css({'font-size': '16px'})
        $('.icon.icon-cart').eq(1).hide()
    }
    
  </script>
  
  

<script src="//cdn.shopify.com/s/javascripts/currencies.js" type="text/javascript"></script>
<script src="//cdn.shopify.com/s/files/1/1194/5066/t/3/assets/jquery.currencies.min.js?10711349972873934541" type="text/javascript"></script>

<script>

Currency.format = 'money_with_currency_format';

var shopCurrency = 'EUR';

/* Sometimes merchants change their shop currency, let's tell our JavaScript file */
Currency.moneyFormats[shopCurrency].money_with_currency_format = "€{{amount}} EUR";
Currency.moneyFormats[shopCurrency].money_format = "€{{amount}}";
  
/* Default currency */
var defaultCurrency = 'EUR';
  
/* Cookie currency */
var cookieCurrency = Currency.cookie.read();

/* Fix for customer account pages */
jQuery('span.money span.money').each(function() {
  jQuery(this).parents('span.money').removeClass('money');
});

/* Saving the current price */
jQuery('span.money').each(function() {
  jQuery(this).attr('data-currency-EUR', jQuery(this).html());
});

// If there's no cookie.
if (cookieCurrency == null) {
  if (shopCurrency !== defaultCurrency) {
    Currency.convertAll(shopCurrency, defaultCurrency);
  }
  else {
    Currency.currentCurrency = defaultCurrency;
  }
}
// If the cookie value does not correspond to any value in the currency dropdown.
else if (jQuery('[name=currencies]').size() && jQuery('[name=currencies] option[value=' + cookieCurrency + ']').size() === 0) {
  Currency.currentCurrency = shopCurrency;
  Currency.cookie.write(shopCurrency);
}
else if (cookieCurrency === shopCurrency) {
  Currency.currentCurrency = shopCurrency;
}
else {
  Currency.convertAll(shopCurrency, cookieCurrency);
}

jQuery('[name=currencies]').val(Currency.currentCurrency).change(function() {
  var newCurrency = jQuery(this).val();
  Currency.convertAll(Currency.currentCurrency, newCurrency);
  jQuery('.selected-currency').text(Currency.currentCurrency);
});

var original_selectCallback = window.selectCallback;
var selectCallback = function(variant, selector) {
  original_selectCallback(variant, selector);
  Currency.convertAll(shopCurrency, jQuery('[name=currencies]').val());
  jQuery('.selected-currency').text(Currency.currentCurrency);
};

$('body').on('ajaxCart.afterCartLoad', function(cart) {
  Currency.convertAll(shopCurrency, jQuery('[name=currencies]').val());
  jQuery('.selected-currency').text(Currency.currentCurrency);  
});

jQuery('.selected-currency').text(Currency.currentCurrency);

</script>


  
</body>
</html>
 