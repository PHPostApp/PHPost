<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.feed.php
 * @author  Miguel92 & PHPost.es
*/

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.live.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	 //
	$code = [
		'w' => $tsCore->settings['titulo'], 
		's' => $tsCore->settings['slogan'], 
		'u' => str_replace(['http://','https://'], '', $tsCore->settings['url']), 
		'v' => $tsCore->settings['version_code'], 
		'a' => $tsUser->nick, 
		'i' => $tsUser->uid
	];
	$key = base64_encode(serialize($code));
	$conexion = "https://phpost.es/feed/";
	// CODIGO
	switch($action){
		case 'feed-support':
			//<--- CONSULTAR ACTUALIZACIONES OFICIALES Y VERIFICAR VERSIÓN ACTUAL DE ESTE SCRIPT
				$json = $tsCore->getUrlContent($conexion . 'index.php?from=PHPost&type=support&key=' . $key);
				echo $json;
			//--->
		break;
		case 'feed-version':
			/**
			 * Versión a 03 de septiembre de 2023 *
			 * PHPost Risus 1.3.0.005 *
			*/
			$time = time();
			$version_now = 'Risus 1.3.0.006';
			$version_code = str_replace([' ', '.'], '_', strtolower($version_now));
			# ACTUALIZAR VERSIÓN
			if($tsCore->settings['version'] != $version_now){
				db_exec([__FILE__, __LINE__], 'query', "UPDATE w_configuracion SET version = '$version_now', version_code = '$version_code' WHERE tscript_id = 1 LIMIT 1");
				db_exec([__FILE__, __LINE__], 'query', "UPDATE `w_stats` SET stats_time_upgrade = $time WHERE stats_no = 1 LIMIT 1");
			}
			//<---
			$json = $tsCore->getUrlContent($conexion . 'index.php?from=PHPost&type=version&key=' . $key);
			echo $json;
			//--->
		break;
		  default:
				die('0: Este archivo no existe.');
		  break;
	}