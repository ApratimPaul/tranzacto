<?php 
/* 
 * PayPal and database configuration 
 */ 
  
// PayPal configuration 
define('PAYPAL_ID', 'tranzacto@business.com'); 
define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE 
 
define('PAYPAL_RETURN_URL', 'https://tranzacto.000webhostapp.com/success.php'); 
define('PAYPAL_CANCEL_URL', 'https://tranzacto.000webhostapp.com/cancel.php'); 
define('PAYPAL_CURRENCY', 'USD');
 
// Change not required 
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");