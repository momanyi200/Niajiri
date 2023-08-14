<?php
Class Escrow 
{
	public function __construct($config = array()) {
		$this->config = $config;
		$this->validation();
	}
	public function escrow_fetch($id){
		$init = array();
		$init['url']= "https://api.escrow.com/2017-09-01/transaction/$id";			
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $init['url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_USERPWD, $this->secret_key . ":" . "");
		
		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = array();
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$result = curl_error($ch);
		}
		curl_close ($ch);
		return $result;
	}
	
	
}
?>