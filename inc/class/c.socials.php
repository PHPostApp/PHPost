<?php

if (!defined('TS_HEADER'))
	 exit('No se permite el acceso directo al script');
/**
 * Modelo para la adminitración
 *
 * @name    c.socials.php
 * @author  PHPost Team
 */
class tsSocials {

	public function getSocials() {
		global $tsCore;
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT social_id, social_name, social_client_id, social_client_secret, social_scope, social_state, social_redirect_uri FROM w_social'));
		foreach($data as $key => $social) {
			$data[$key]['social_redirect_uri'] = $tsCore->settings['url'] . '/' . $social['social_name'] . '.php';
		}
		return $data;
	}

	public function newSocial() {
		global $tsCore;
		foreach($_POST = (isset($_POST['save']) ? array_slice($_POST, 0, -1) : $_POST) as $key => $val) $_POST[$key] = is_numeric($val) ? (int)$val : $tsCore->setSecure($val);
		// Guardamos
		$name = $tsCore->setSecure($_POST["social_name"]);
		if(insertInto([__FILE__, __LINE__], 'w_social', [
			'name' => $name,
			'client_id' => $tsCore->setSecure($_POST["social_client_id"]),
			'client_secret' => $tsCore->setSecure($_POST["social_client_secret"]),
			'scope' => $tsCore->setSecure($_POST["social_scope"]),
			'state' => strtolower($tsCore->settings['titulo']).date('y'),
			'redirect_uri' => "{$tsCore->settings['url']}/" . strtolower($name) . ".php"
		], 'social_')) return true;
	}

}