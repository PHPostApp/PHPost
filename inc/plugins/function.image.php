<?php 

/**
 * Smarty plugin para incluir archivos CSS y JS de forma independiente.
 *
 * Uso: Solo require el nombre del archivo
 *  {meta facebook=true twitter=false} 
 *
 * @param array $params 
 * @param Smarty_Internal_Template $smarty Instancia del objeto Smarty.
 * @return string Código HTML generado por la función.
*/

function smarty_function_image($params, &$smarty) {
	global $tsCore;

	$default = [
		"alt" => "{$tsCore->settings['titulo']} {$tsCore->settings['slogan']}",
		"attr" => [
			"src" => $tsCore->settings['public'] . '/images/portada-redes.png', 
			"data-src" => $params['src'], 
			"srcset" => "{$params['src']} 320w, {$params['src']} 480w, {$params['src']} 800w", 
			"sizes" => "(max-width: 320px) 280px, (max-width: 480px) 440px, 800px"
		],
		"class" => "image " . $params['class']
	];

	$default['alt'] = isset($params['alt']) ? $params['alt'] : $default['alt'];

	foreach ($default['attr'] as $key => $attr) $default['attr'][$key] = "$key=\"$attr\"";
	$sources = join(' ', $default['attr']);

	foreach ($default as $key => $attr) 
		if($key !== 'attr') $attrs[$key] = "$key=\"$attr\"";
	$otherattr = join(' ', $attrs);

	$image = "<figure><img loading=\"lazy\" $sources $otherattr><figcaption>{$default['alt']}</figcaption></figure>";
	
	return $image;
}