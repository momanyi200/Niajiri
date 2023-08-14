<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->ci 	 =	& get_instance();
$this->ci->load->helper("url");

$ci =& get_instance();
$ci->db->where('status',1);
$result=$ci->db->get('mpesa_payment_gateway')->row_array();

//Live -  https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest replace to https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest
// credentials
$config['ConsumerKey']			=	'PXvEFt6T2GOV2bIs6yOGuapcEf7dqZ8y';
$config['ConsumerSecret']		=	'u0jSeNSIbBFS1IYN';
$config["token_link"]			=	'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$config["initiator"]			=	'testapi';
$config["credential"]			=	'byi7+MpP4vAbjiMPKPlaIOcrAGQ1xZHr7IHQ9GoGmMdgY1j1m';
$config["partyA_shortcode"]		=	'600426';
$config["partyB_shortcode"]		= 	'';
$config["test_msisdn"]			=	'';
$config["test_link"]			=	site_url();

//Mpesa Checkout
$config['checkout_processlink']	=	'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; 
$config['checkout_querylink']	=	'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
$config['checkout_shortcode']	=	'174379';
$config['checkout_passkey']		=	'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$config['checkout_rcallbackurl']=	"https://craftesty-niajiri.dreamguystech.com/home/return_call_back";
$config['checkout_qcallbackurl']=	site_url("QueryStkCallback");

// Mpesa Reversal
$config["reversal_link"]		=	'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
$config["reversal_resultUrl"]	=	site_url("ReversalCallback");
$config["reversal_timeoutURL"]	=	site_url("ReversalCallback");

// Mpesa Balance
$config["balance_link"]			=	'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
$config["balance_timeoutUrl"]	=	site_url("AccountBalCallback");
$config["balance_resultUrl"]	=	site_url("AccountBalCallback");

// Mpesa C2B 
$config["c2b_regiterUrl"]		=  	'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
$config["c2b_transactionUrl"]	=	'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
$config["c2b_confirmationUrl"]	= 	"https://craftesty-niajiri.dreamguystech.com/home/confirmation_return";
$config["c2b_validationUrl"]	= 	"https://craftesty-niajiri.dreamguystech.com/home/validation_return";
$config["c2b_shortcode"]		=	$config["partyA_shortcode"];//'';

// Mpesa Transaction Status
$config["transtat_link"]		=	'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
$config["transtat_resultURL"]	=	site_url("TransStatCallback");
$config["transtat_timeoutURL"]	=	site_url("TransStatCallback");

// Mpesa B2B
$config["b2b_link"]				= 	'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
$config["b2b_timeoutURL"]		=	site_url("B2BCallback");
$config["b2b_resultURL"]		=	site_url("B2BCallback");

// Mpesa B2C
$config["b2c_link"]				 =	'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
$config["b2c_timeoutURL"]		=	site_url("B2CCallback");
$config["b2c_resultURL"]		=	site_url("B2CCallback");
