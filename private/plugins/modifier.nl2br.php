<?php

/**
 * Autor: Monte Ohrt <monte at ohrt dot com>
 * Ejemplo: {$text|nl2br} = texto\r\n => texto<br>
 * Enlace: https://www.php.net/manual/es/function.nl2br.php
 * Fecha: Feb 26, 2003
 * Nombre: nl2br
 * Proposito: Convert \r\n, \r or \n to <br>
 * Tipo: modifier
 * Version: 1.0
*/

function smarty_modifier_nl2br($string)
{
    return nl2br($string);
}
