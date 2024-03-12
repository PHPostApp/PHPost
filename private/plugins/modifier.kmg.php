<?php

/**
 * Autor: Kmario19
 * Ejemplo: {$numeros|kmg} = 34692 => 35K
 * Enlace: #
 * Fecha: Jun 11, 2014
 * Nombre: kmg
 * Proposito: Convert 10000 => 1K, 1000000 => 1M
 * Tipo: modifier
 * Version: 2.0
*/

function smarty_modifier_kmg($number, $decimal = 0){
	$units = ['', 'K', 'M', 'G', 'T', 'P'];
	$power = ($number > 0) ? floor(log($number, 1000)) : 0;
	return number_format($number / pow(1000, $power), $decimal) . $units[$power];
}