<?php

class reCaptcha {

	// ...
	public function __construct() {
		
	}

	// Función para comprobar reCaptcha v3
	public function verify(string $response) {
		global $tsCore;
		if (empty($response)) return false;
		// Obtener IP
		$ipuser = $tsCore->getIP();
		$api = "https://www.google.com/recaptcha/api/siteverify";
		//
		$curlOptions = [
			CURLOPT_URL => $api,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query([
				'secret' => $tsCore->settings['skey'],
				'response' => $response,
				'remoteip' => $ipuser
			]),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 10 // Set a reasonable timeout
		];
		
		$init = curl_init();
		curl_setopt_array($init, $curlOptions);
		$response = curl_exec($init);
		if (curl_errno($init)) {
			// Handle curl error if needed
			curl_close($init);
			return false;
		}
		curl_close($init);
		$responseData = json_decode($response, true);
		if (!is_array($responseData)) return false;
		return $responseData['success'] ?? false;
	}
}