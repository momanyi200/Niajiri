<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');
use Twilio\Rest\Client;
	
Class Sms{

	public function send_message($mobileno,$message)
	{
		$sid = 'AC65b4d970f8c6dab2d1803e71749a644f';
		$token = 'e8150ab7d383fe6b45b5b0152c1670d6';
		$client = new Client($sid, $token);
		$result=$client->messages->create('+'.$mobileno,array('from' => '+18583305995','body' => $message));
		return $result;
	}

     
		

}
