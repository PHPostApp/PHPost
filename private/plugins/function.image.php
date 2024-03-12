<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {image type='post|portada' src='' class='opcional' alt='opcional'} 
 * Enlace: #
 * Fecha: Dic 20, 2023
 * Nombre: image
 * Proposito: Crear la etiqueta <img>
 * Tipo: function
 * Version: 1.0
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
		"class" => "image " . $params['class'],
		"style" => $params['style'],
		"caption" => $params['caption']
	];

	if(filter_var($params['src'], FILTER_VALIDATE_URL)) {
		// Al ponerlo así usa la url en caso de que sea un enlace
	} elseif($params['type'] === 'post' OR $params['type'] === 'portada') {
		$cover = $tsCore->settings['portada'] . "/c0v3rlvl";
		$default = [
			"attr" => [
				"data-src" => "{$cover}1_{$params['src']}", 
				"srcset" => "{$cover}3_{$params['src']} 320w, {$cover}2_{$params['src']} 480w, {$cover}1_{$params['src']} 800w"
			]
		];
		//
		if(empty($params['src'])) {
			$params['src'] = "{$tsCore->settings['public']}/images/sin_portada.png";
			$default = [
				"attr" => [
					"data-src" => $params['src'], 
					"srcset" => "{$params['src']} 320w, {$params['src']} 480w, {$params['src']} 800w"
				]
			];
		}
	} 

	$default['alt'] = isset($params['alt']) ? $params['alt'] : $default['alt'];

	foreach ($default['attr'] as $key => $attr) $default['attr'][$key] = "$key=\"$attr\"";
	$sources = join(' ', $default['attr']);

	foreach ($default as $key => $attr) 
		if($key !== 'attr') $attrs[$key] = "$key=\"$attr\"";
	$otherattr = join(' ', $attrs);

	$caption = ($default['caption'] === true) ? "<figcaption>{$default['alt']}</figcaption>" : '';

	$image = "<figure><img loading=\"lazy\" $sources $otherattr>$caption</figure>";
	
	return $image;
}