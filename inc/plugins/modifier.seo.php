<?php

/**
 * Autor: Miguel92
 * Ejemplo: {$texto|seo} = Este es el texto => este-es-el-texto
 * Enlace: #
 * Fecha: Ene 22, 2022
 * Nombre: seo
 * Proposito: Convierte en texto en minusculas añadiendo un -
 * Tipo: modifier
 * Version: 1.0
*/

function smarty_modifier_seo($string){
	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
	$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
	$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	$string = preg_replace('~[^0-9a-z]+~i', '-', $string);
	$string = strtolower(trim($string, '-'));
	return $string;
}