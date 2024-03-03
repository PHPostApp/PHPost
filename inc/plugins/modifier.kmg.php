<?php

/**
 * Autor: Kmario19
 * Ejemplo: {$numeros|kmg} = 34692 => 35K
 * Enlace: #
 * Fecha: Jun 11, 2014
 * Nombre: kmg
 * Proposito: Convert 10000 => 1K, 1000000 => 1M
 * Tipo: modifier
 * Version: 1.0
*/

function smarty_modifier_kmg($number, $decimal = 0){
  $pre = 'KMG';
  if ($number >= 1000) {
    for ($i=-1; $number>=1000; ++$i) { $number /= 1000; }
    return round($number,$decimal).$pre[$i];
  } else return $number;
}