# Stripe

<h3>ACH Stripe Payment Using Plaid And Without Plaid (e-check derivative)</h3>

Stripe <b>ACH</b> can be done in <b>2 Ways</b>:

1) Using 2 step verification

2) Using 3rd party like Plaid to verify bank account.

<h3>Using 2 step verification:</h3>
   
   This process does not verify account instantly, it takes time to do that. Stripe sends two small amount into account. When the user enters those amount only then account will be verified and payment will be made

<h3>Using 3rd party like Plaid to verify bank account:</h3>

This verifies the amount instantly and processes the payment. For this process, you have to use a third party process. I used Plaid to verify the bank account.

Before doing this you have to create the plaid account and connect it with your stripe account from the URL below. https://dashboard.plaid.com/account/integrations.

It can be set up very easily but unfortunately, you have to give live details to connect it with stripe. You can not setup it with stripe using dummy details. If you will not connect it with stripe then on creating payment it will throw an error.

There will be extra charges to use Plaid or another third-party process
