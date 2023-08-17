<?php

/**
 * Smarty plugin para 
 */

function smarty_function_global($params, &$smarty) {

	$variables = $smarty->tpl_vars;
  	$tsConfig = $variables['tsConfig']->value;

	$output .= "<script id=\"app".substr(uniqid(), -10)."\">\n";
	$newdata = [
		"domain" 	=> $tsConfig['domain'],
		"fotoid" 	=> (int)$variables['tsFoto']->value['foto_id'],
		"img" 		=> $tsConfig['images'],
		"postid" 	=> (int)$variables['tsPost']->value['post_id'],
		"s_slogan" 	=> $tsConfig['slogan'],
		"s_title" 	=> $tsConfig['titulo'],
		"theme" 		=> $tsConfig['tema']['t_url'],
		"url" 		=> $tsConfig['url'],
		"user_key" 	=> (int)$variables['tsUser']->value->info['user_id']
	];
	// Solo lo añade si esta en la página de registro
	if($variables['tsPage']->value === 'registro') $newdata["public_key"] = $tsConfig['pkey'];
	// Solo lo añade si esta activo
	if((int)$tsConfig['inactividad']['activo'] === 1) {
		foreach($tsConfig['inactividad'] as $k => $val) $newdata['inactividad_'.$k] = $val;
	}
	$quitar = ['fotoid', 'postid', 'user_key'];
	foreach($quitar as $k => $item) if($newdata[$item] == 0) unset($newdata[$item]);
	//
	foreach ($newdata as $key => $val) $agregar[$key] = "\t$key: ".(is_numeric($val) ? $val : "'$val'");
	$output .= "var global_data = {\n" .join(",\n", $agregar)."\n};\n";
	//
	$tsNots = (int)$variables['tsNots']->value;
	$tsAvisos = (int)$variables['tsAvisos']->value;
	$tsMPs = (int)$variables['tsMPs']->value;
	//
	if($tsNots > 0 OR $tsMPs > 0 AND $tsAction != 'leer') {
		$output .= <<< PHPOST_SCRIPT_ADD
		\t\$(document).ready(() => {
		\t\tnotifica.popup({$tsNots});
		\t\tmensaje.popup({$tsMPs});
		\t}\n
		PHPOST_SCRIPT_ADD;
	}
	$output .= "</script>";

  	return trim($output);
}