<?php 
  require_once('config.php');
	require_once './vendor/autoload.php';

\Stripe\Stripe::setApiKey("".$stripe_secret_key.""); /*for test*/


	if(isset($_POST['action']) && $_POST['action']=="get_client")
	{
$headers[] = 'Content-Type: application/json';
$params = [
   'client_id' => ''.$plaid_client.'',
   'secret' => ''.$plaid_secret.'',
   'public_token' => ''.$_POST['token'].'',
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://sandbox.plaid.com/item/public_token/exchange");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 80);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

if(!$result = curl_exec($ch)) {
   trigger_error(curl_error($ch));
}
curl_close($ch);

$jsonParsed = json_decode($result);
$btok_params = [
   'client_id' => ''.$plaid_client.'',
   'secret' => ''.$plaid_secret.'',
   'access_token' => $jsonParsed->access_token,
   'account_id' => ''.$_POST['account_id'].''
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://sandbox.plaid.com/processor/stripe/bank_account_token/create");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($btok_params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 80);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

if(!$result = curl_exec($ch)) {
   trigger_error(curl_error($ch));
}
curl_close($ch);
$btok_parsed = json_decode($result);
$sp = \Stripe\Customer::create(array(
            "source" => ''.$btok_parsed->stripe_bank_account_token.'',
            "description" => 'adding client'
          ));

    $result = \Stripe\Charge::create([
    'amount' => 324*100,
    'currency' => 'usd',
    'description' => 'Example charge',
    'customer' => $sp->sources->data[0]['customer'],
  ]);/*This charge can be made here as well or once the customer has been created you can save it in database or in hidden input field and can make charge later*/

$arr=array('name'=>$sp->sources->data[0]['account_holder_name'],'acount_no'=>$sp->sources->data[0]['last4'],'routing_no'=>$sp->sources->data[0]['routing_number'],'bank_status'=>$sp->sources->data[0]['status'],'id'=>$sp->sources->data[0]['id'],'customer'=>$sp->sources->data[0]['customer']);
/*These are few details which can be fetched once customer has been created and can be used in payment form or stripe form also client id can be saved in session and on click pay \Stripe\Charge::create function can be used*/
json_encode($arr);
exit();
}
?>