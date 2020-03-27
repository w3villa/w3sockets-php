<?php
	include 'credentials.php';
	
	function init() {
		$ch = curl_init();
		$data = http_build_query( array("client_id"=> $GLOBALS['public_key'], "client_secret"=> $GLOBALS['secret_key'], "grant_type"=> "client_credentials"));
		curl_setopt($ch,CURLOPT_URL, 'https://www.w3sockets.com/oauth/token');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		$newresult = curl_exec($ch);
		curl_close($ch);
		file_put_contents('./access_token.json', $newresult);
		return json_decode($newresult, true);
	}

	function push($channel, $event, $message) {
		$token_data = json_decode(file_get_contents('./access_token.json'),true);

		$time_of_expire = strtotime(date('D j M Y G:i:s T',($token_data['expires_in']+$token_data['created_at'])));
		$unixTimestamp = strtotime(date('D j M Y G:i:s T'));

		if($unixTimestamp > $time_of_expire) {
			$token_data = init();
		}

		$ch = curl_init();

		$data = http_build_query( array("access_token"=> $token_data['access_token'], "data"=> array("channel" => $GLOBALS['public_key'].'-'.$channel , "event" => $event , 'message' => $message)));

		curl_setopt($ch,CURLOPT_URL, 'https://www.w3sockets.com/api/v1/push/notify');
		curl_setopt($ch,CURLOPT_POST, 4);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$newresult = curl_exec($ch);
		curl_close($ch);
	}
?>