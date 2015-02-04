<?php

require 'Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('23nd25g4kn7gnqbb');
Braintree_Configuration::publicKey('8552x2ym5bvhsycp');
Braintree_Configuration::privateKey('17f3279171d4fd90ee9cd5256be17abf');

if ($_POST['amt'] && $_POST['nonce'] && $_POST['cart']) {

	$result = Braintree_Transaction::sale(array(
		'amount' => $_POST['amt'],
		'paymentMethodNonce' => $_POST['nonce'],
		'customFields' => array(
			'cart' => $_POST['cart'],
		),
	));

	if ($result->success == 1) {echo $result->transaction->id;} else {echo "failed";}

}

?>
