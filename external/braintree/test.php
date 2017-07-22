<?php

require_once ('/var/www/braintree-php-3.23.1/lib/Braintree.php');

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('8zjpcspnhczxp758');
Braintree_Configuration::publicKey('rzfj5p7m55h6rwf5');
Braintree_Configuration::privateKey('54ceda8228b4e7e8ecdd94f005eef832');

$result = Braintree_Transaction::sale([
  'amount' => '12.00',
  'paymentMethodNonce' => 'fake-valid-nonce',
  'options' => [
    'submitForSettlement' => True
  ]
]);

var_dump($result);

?>
