<?php

/**
 * Autor: Monte Ohrt <monte at ohrt dot com>
 * Ejemplo: {$texto|trim} =     hola    => hola
 * Enlace: https://www.php.net/manual/es/function.trim.php
 * Fecha: Feb 26, 2003
 * Nombre: nl2br
 * Proposito: Eliminar los espacios de derecha e izquierda
 * Tipo: modifier
 * Version: 1.0
*/

function smarty_modifier_trim($string) {
   return trim($string);
}