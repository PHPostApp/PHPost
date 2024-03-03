<?php

/**
 * Autor: Miguel92
 * Ejemplo: {$texto|ucfirst} = hola mundo => Hola Mundo
 * Enlace: https://www.php.net/manual/es/function.ucfirst.php
 * Fecha: May 27, 2022
 * Nombre: ucfirst
 * Proposito: Convierte la primer letra en mayuscula
 * Tipo: modifier
 * Version: 1.0
*/

function smarty_modifier_ucfirst($texto) {
	return ucfirst($texto);
}