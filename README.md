# Stripe

ACH Stripe Payment Using Plaid And Without Plaid (e-check derivative)

Stripe ACH can be done in 2 Ways:

1) Using 2 step verification

2) Using Third party like Plaid to verify bank account.

<h3>Using 2 step verification:</h3>
   
   This process does not verify account instantly, it takes time to do that. Stripe sends two small amount into account. When user enter those amount only then account will be verified and payment will be made

<h3>Using Third party like Plaid to verify bank account:</h3>

  This verify the amount instanty and process the payment. For this process you have to use third party process. I used Plaid to verify bank account.
  Before doing this you have to create the plaid account and connect it with your stripe account from the url below.
  https://dashboard.plaid.com/account/integrations.
  It can be setup very easily but unfortunately you have you give live details to connect it with stripe. You can not setup it with stripe using sandbox details or dummy details.

  There will be extra charges to use Plaid or other third party process.
