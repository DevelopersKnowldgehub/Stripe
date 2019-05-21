<?php 
session_start(); 
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Live Example Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
</head>
<body>

<div class="container">
<?php

if(isset($_REQUEST['msg']))
{
?>
<div class="alert alert-success">
			<strong>Success!</strong>		
		<?php
		echo $_REQUEST['msg'];
		?>
</div>  
<?php
}
?>

<?php
if(!isset($_REQUEST['msg']))
{
?>
  <h2>ACCOUNT VERIFICATION FORM</h2>
  <form action="" method="POST">
    <div class="form-group">
      <label for="account_holder_name">ACCOUNT HOLDER NAME:</label>
      <input type="text" class="form-control" id="account_holder_name" placeholder="ENTER ACCOUNT HOLDER NAME" name="account_holder_name" required>
    </div>
	 <div class="form-group">
      <label for="account_holder_type">ACCOUNT HOLDER TYPE:</label>
      <select name="account_holder_type">
      	<option value="company">Company</option>
      	<option value="individual">Individual</option>
      </select>
      <!-- <input type="text" class="form-control" id="account_holder_type" placeholder="ENTER ACCOUNT HOLDER TYPE" name="account_holder_type" required> -->
    </div>
    <div class="form-group">
      <label for="routing_number">ROUTING NUMBER:</label>
      <input type="text" class="form-control" id="routing_number" placeholder="ENTER ROUTING NUMBER" name="routing_number" required>
    </div>
	<div class="form-group">
      <label for="account_number">ACCOUNT NUMBER:</label>
      <input type="text" class="form-control" id="account_number" placeholder="ENTER ACCOUNT NUMBER" name="account_number" required>
    </div>
    <button type="submit" class="btn btn-success" name="verify_form">Submit</button>
  </form>
<?php
}
else
{
	?>
		  <h2>ACCOUNT VERIFICATION FORM</h2>
		  <form action="" method="POST">
			<div class="form-group">
			  <label for="ammount1">AMMOUNT 1:</label>
			  <input type="text" class="form-control" id="ammount1" placeholder="ENTER AMMOUNT 1" name="ammount1" required>
			</div>
			 <div class="form-group">
			  <label for="ammount2">AMMOUNT 2:</label>
			  <input type="text" class="form-control" id="ammount2" placeholder="ENTER AMMOUNT 2" name="ammount2" required>
			</div>
			<button type="submit" class="btn btn-success" name="verify_amount">Submit</button>
		  </form>
	
	<?php
}
?>

</div>

</body>
</html>


<?php 
if(isset($_POST['verify_amount']) || isset($_POST['verify_form'])){
	require_once './vendor/autoload.php';
\Stripe\Stripe::setApiKey("".$stripe_secret_key.""); /*for test*/
}



	if(isset($_POST['verify_form']))
	{

		$account_holder_name = $account_holder_type = $routing_number = $account_number = '';
		$account_holder_name = $_POST['account_holder_name'];
		$account_holder_type = $_POST['account_holder_type'];
		$routing_number = $_POST['routing_number'];
		$account_number = $_POST['account_number'];
		

		
			try {
				
						$bank_token = \Stripe\Token::create(array(
						  "bank_account" => array(
							"country" => "US",
							"currency" => "USD",
							"account_holder_name" => $account_holder_name,
							"account_holder_type" => $account_holder_type,
							"routing_number" => $routing_number,
							"account_number" => $account_number
						  )
						));
					$b_token = $bank_token->__toJSON();
					$obj_token = json_decode($b_token, TRUE);
					$b_tok = $obj_token['id'];
					$bank_account_id = $obj_token['bank_account']['id'];
					$_SESSION['bankaccid'] = $bank_account_id;
					
					$sp = \Stripe\Customer::create(array(
					  "source" => $b_tok,
					  "description" => $account_holder_name
					));
					
					$customer_json = $sp->__toJSON();
					$obj = json_decode($customer_json, TRUE);
					$cust_id =  $obj['id'];
					$_SESSION['custid'] = $cust_id;

					if($sp)
					{


						header('location: without_plaid.php?msg=We have diposited two Microammount into your Bank Account.');
					}				
			} catch(\Stripe\Error\Card $e) {
				echo "Error:-".$e->getMessage();
			} catch (\Stripe\Error\RateLimit $e) {
				echo "Error:-".$e->getMessage();
			} catch (\Stripe\Error\InvalidRequest $e) {
				echo "Error:-".$e->getMessage();
			} catch (\Stripe\Error\Authentication $e) {
				echo "Error:-".$e->getMessage();
			} catch (\Stripe\Error\ApiConnection $e) {
				echo "Error:-".$e->getMessage();
			} catch (Exception $e) {
				echo "Error:-".$e->getMessage();
			}
	}
		if(isset($_POST['verify_amount']))
	{

				try {
		$ammount1 = $_POST['ammount1'];
		$ammount2 = $_POST['ammount2'];
		$customer = \Stripe\Customer::retrieve($_SESSION['custid']);
		$bank_account = $customer->sources->retrieve($_SESSION['bankaccid']);
		$bank_account->verify(array('amounts' => array($ammount1, $ammount2)));
		if($bank_account)
		{

$charge = \Stripe\Charge::create([
  'amount' => 1500,
  'currency' => 'usd',
  'customer' => $_SESSION['custid'], // Previously stored, then retrieved
]);

			header('location: without_plaid.php?msg=Your Bank Account has been verified and payment has been made successfully!');
		}
	}catch(\Stripe\Error\Card $e) {
				echo "Error:-".$e->getMessage();
		}

	}

?>
<script type="text/javascript">
	Stripe.setPublishableKey("<?php echo $stripe_publication_key; ?>");
</script>