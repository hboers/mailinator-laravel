<?php
class Mailinator {
	private $token;
	private $apiEndpoint = "https://api.mailinator.com/api/";
	private $inboxCount = 0;
	
	public function __construct($token) {
		$this->token = $token;
	}
	
	private function call($method,$params) {
		$params['token'] = $this->token;
		$params_str = $this->paramsToString($params);
		$url = $this->apiEndpoint.$method."?".$params_str;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$exec = curl_exec($ch);
		$info = curl_getinfo($ch);
		if($info["http_code"] == 200) {
			curl_close($ch);
			return json_decode($exec,true);
		} else {
			die("An error happened");
		}
	}
	
	private function paramsToString($params = array()) {
		$str = "";
		foreach($params as $key=>$val) {
			$str .= $key."=".$val."&";
		}
		return rtrim($str, "&");
	}
	
	public function fetchInbox($inbox) {
		$query = $this->call('inbox',array('to' => $inbox));
		$mailbox = $query["messages"];
		$this->inboxCount = count($mailbox);
		return $mailbox;
	}
	
	public function fetchMail($msgId) {
		$query = $this->call('email',array('id' => $msgId));
		$message = $query["data"];
		var_dump($message);
	}
}

?>