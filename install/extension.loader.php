<?php 

class Extension {

	public function loaderGD(string $type = '') {
		$status = (!extension_loaded('gd') || !function_exists('gd_info'));
		if(!$status) $temp = gd_info();
		$msg['message'] = $status ? "La extensión GD no está habilitada!" : $temp['GD Version'];
		$msg['status'] = !$status;
		return $msg[$type];
	}

	public function loaderCURL(string $type = '') {
		$status = (!extension_loaded('curl'));
		if(!$status) $curlInfo = curl_version();
		$msg['message'] = $status ? "La extensión cURL no está habilitada!" : $curlInfo['version'];
		$msg['status'] = !$status;
		return $msg[$type];
	}

}

$extension = new Extension;