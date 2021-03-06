<?php

require 'Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('23nd25g4kn7gnqbb');
Braintree_Configuration::publicKey('8552x2ym5bvhsycp');
Braintree_Configuration::privateKey('17f3279171d4fd90ee9cd5256be17abf');

$token = Braintree_ClientToken::generate(array(
));

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Shopping Cart</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="assets/css/custom.css"/>
	</head>

	<body>

		<nav class="navbar">
			<div class="container">
				<h1 class="col-md-12 col-sm-12 main">IoT Commerce Demo </h1>
				<div class="col-md-12 col-sm-12 sub">
					<small>Follow us at @Braintree_Dev</small>
				</div>
			</div>
		</nav>

		<div class="container-fluid breadcrumbBox text-center">

		</div>

		<div class="container text-center">

			<div class="col-md-5 col-sm-12">
				<div class="bigcart"></div>
				<h1>Arduino Yun Powered Printer</h1>
				<p>
					Using an Arduino Yun to power a thermal receipt printer that retrieves new orders from the Braintree <i>Braintree_Transaction</i> SDK call, orders are stored in the custom order variable which needs to be setup via the sandbox and can be populated with JSON order data at time of order.
				</p>
				<p>
					The Git for all this code is located <a href="https://github.com/developersteve/IoTCart" target="blank">here</a>
				</p>
			</div>

			<div class="col-md-7 col-sm-12 text-left">
				<div class="finalcart">
					<ul>
						<li class="row">
							<span class="itemName"></span>
						</li>
					</ul>
				</div>
				<form id="cart">
					<ul>
						<li class="row list-inline columnCaptions">
							<span>QTY</span>
							<span>ITEM</span>
							<span>Price</span>
						</li>
						<li class="row item">
							<span class="quantity">1</span>
							<span class="itemName">Arduino Yun</span>
							<span class="popbtn"><a class="arrow"></a></span>
							<span class="price">$70.00</span>
						</li>
						<li class="row item">
							<span class="quantity">1</span>
							<span class="itemName">Thermal Printer</span>
							<span class="popbtn"><a class="arrow"></a></span>
							<span class="price">$40.00</span>
						</li>
						<li class="row item">
							<span class="quantity">1</span>
							<span class="itemName">Cardboard box</span>
							<span class="popbtn"><a class="arrow"></a></span>
							<span class="price">$0.99</span>
						</li>
						<li class="row totals">
							<span class="price">$110.99</span>
							<span class="itemName">Total:</span>
						</li>
						<li class="row boxcheckout">
							<div id="checkout"></div>
						</li>
						<li class="row orders">
					    	<!-- <span class="order"><a class="text-center">ORDER</a></span> -->
							<input class="btn btn-default order" type="submit" value="Submit">
						</li>
					</ul>
				</form>
			</div>

		</div>

		<!-- The popover content -->

		<div id="popover" style="display: none">
			delete in here
<!-- 			<a href="#"><span class="glyphicon glyphicon-pencil"></span></a>
			<a href="#"><span class="glyphicon glyphicon-remove"></span></a> -->
		</div>

		<!-- JavaScript includes -->

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/customjs.js"></script>

	    <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
	    <script>
			braintree.setup("<?php echo $token;?>", 'dropin', {
	        	container: 'checkout',
				paymentMethodNonceReceived: function (event, nonce) {

	    		var items = [];

	    		$(".item").each(function() {
				    items.push({
				        qty: $(this).find(".quantity").text(),
				        itemName: $(this).find(".itemName").text(),
				        price: $(this).find(".price").text().replace(/\$/g,''),
				    });
				});

				var totalPrice = $(".totals .price").text().replace(/\$/g,'');

				items.push({
					total: totalPrice
				});

				var jString = JSON.stringify(items);

				console.log("cart="+jString+"&amt="+totalPrice+"&nonce="+nonce);

				$.ajax({
				  url: "proc.php",
				  type: "POST",
				  data: "cart="+jString+"&amt="+totalPrice+"&nonce="+nonce,
				  cache: false,
				  success: function(data){
				    if(data=="failed"){
				    	alert("Payment Failed");
				    }
				    else{
				    	$(".finalcart ul li span").html("<p>Your order has been placed</p><p>Transaction id is "+data+"</p>");
				    	$(".finalcart").show();
				    	$("#cart").remove();
				    }
				  }
				});
			}
        });
	    </script>
	</body>
</html>
