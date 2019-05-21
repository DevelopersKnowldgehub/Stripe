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
            <div id="account_verification_section" class="col-md-6" <?php if(isset($renters_client_id)){ echo 'style="display:none"'; } ?>>
              <h2>ACCOUNT VERIFICATION FORM</h2>
              <div id="ach_warning" class="alert alert-danger">Using ACH will delay processing by upto 4 business days.</div>
              <form  action="#" id="account_verification_form" accept-charset="UTF-8" class="require-validation" data-cc-on-file="false" method="post">       
<div class="form-group">
   <div class="col-md-12">
<button type="button" class="form-control btn btn-primary" id='linkButton'>Verify Bank Account</button>
</div>
</div>

              <div id="detail_section">  
             <div class="form-group">
              <div class="col-md-12 card required">
                <label class="control-label">Name On Account</label>
                <input name="account_holder_name" disabled="disabled" placeholder="Enter Name" onblur="this.placeholder = 'Enter Name'" onfocus="this.placeholder = ''"  autocomplete="off" class="form-control card-holder-name" type="text">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12 card required">
                <label class="control-label">Routing Number</label>
                <input name="routing_number" placeholder="Enter Routing Number" disabled="disabled" onblur="this.placeholder = 'Enter Routing Number'" onfocus="this.placeholder = ''"  autocomplete="off" class="form-control routing" type="text">
              </div>
             
            </div>
            
             <div class="form-group">

              <div class="col-md-12 card required">
                <label class="control-label">Account Number (last 4 digits)</label>
                <input name="account_number" placeholder="Enter Account Number" disabled="disabled" onblur="this.placeholder = 'Enter Account Number'" onfocus="this.placeholder = ''"  autocomplete="off" class="form-control" id="#" type="text">
              </div>
            </div>

      <div class="col-md-6 cvc required">
      <label class="control-label">Amount to Pay</label>
      <div class="left-inner-addon">
      <span style="background: #fbfbfb;border: 1px solid #808080;">$</span>
      <input type="text" disabled="disabled" class="form-control"  id="amount_ach" name="amount_ach" placeholder="Price"  value="" />
      </div>
      </div>

      <div class="clearfix"></div>

<div id="error_ach" style="display: none;" class="payment-errors"></div>
      <div class="col-md-12" style="display: inline-block; margin-top: 14px;">

<button id="pay_now_ach" class="form-control btn btn-primary" type="button">Pay Now</button>

      </div>

</div>
        </form>
            </div>
<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
<script>
var linkHandler = Plaid.create({
  env: 'sandbox',
  clientName: 'Stripe/Plaid Test',
  key: "<?php echo $plaid_secret; ?>",
  product: ['auth'],
  selectAccount: true,
  onSuccess: function(public_token, metadata) {
    // Send the public_token and account ID to your app server.
    console.log('public_token: ' + public_token);
    console.log('account ID: ' + metadata.account_id);
    var post_data=[];
        post_data.push({
          name: "action",
          value: 'get_client'
        });        
        post_data.push({
          name: "token",
          value: public_token
        });        
        post_data.push({
          name: "account_id",
          value: metadata.account_id
        });
    $.ajax({
type: "POST",
url: "ach_ajax.php",
data: post_data,
cache: false,
dataType: "json",
success: function(result){
 console.log(result);
$("#error_ach").html('');
$("#error_ach").hide();
$("#detail_section").show();
 $("#account_verification_form").find("input[name='account_holder_name']").attr('value',result.account_holder_name);
 $("#account_verification_form").find("input[name='routing_number']").attr('value',result.routing_no);
 $("#account_verification_form").find("input[name='account_number']").attr('value',result.acount_no);
}
});
  },
  onExit: function(err, metadata) {
    // The user exited the Link flow.
    if (err != null) {
      // The user encountered a Plaid API error prior to exiting.
    }
  },
});

// Trigger the Link UI
document.getElementById('linkButton').onclick = function() {
  linkHandler.open();
};
$(function(){
    $("#pay_now_ach").click(function(){
alert('After verifying Bank account Save client ID in Session or Hidden field (Not Secured) and send via ajax from here and create charge');
    
    });
});
</script>

</body>
</html>
<script type="text/javascript">
	/*Stripe.setPublishableKey('pk_test_59L8zNeT5Bwdes0gvnkQ9zAs');my*/
	Stripe.setPublishableKey("<?php echo $stripe_publication_key; ?>");
</script>