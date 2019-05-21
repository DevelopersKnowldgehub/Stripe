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

		  <h2>CLIENT ID VERIFICATION FORM</h2>
		  <form action="" method="POST">
			 <div class="form-group">
			  <label for="client_id">Client ID:</label>
			  <input type="text" class="form-control" id="client_id" placeholder="ENTER CLIENT ID" name="client_id" required>
			</div>
			<button type="submit" class="btn btn-success" name="verify_client_id">Submit</button>
		  </form>

</div>

</body>
</html>


<?php 
	require_once './vendor/autoload.php';
\Stripe\Stripe::setApiKey("".$stripe_secret_key.""); /*for test*/
	if(isset($_POST['verify_client_id']) && isset($_POST['client_id']) && $_POST['client_id']!="")
	{
  $charge = \Stripe\Charge::create([
  'amount' => 810,
  'currency' => 'usd',
  'customer' => $_POST['client_id'],
]);
 print_r($charge);
}

?>
<script type="text/javascript">
	Stripe.setPublishableKey("<?php echo $stripe_publication_key; ?>");
</script>