<?php

/**
 * Smarty modifier
 * @package Smarty
 * @subpackage plugins
 * Smarty CheckArray modifier plugin
 *
 * Type:     function<br>
 * Name:     CheckArray<br>
 * Date:     Dic 31, 2023
 * Purpose:  Confirma si es un arreglo o no.
 * Input:    true/false
 * Example:  {$array|CheckArray}
 * @link https://www.php.net/manual/en/function.is-array.php 
 *          (PHP online manual)
 * @author   Joel Miguel Valente
 * @version 1.0
 * @param array
 * @param Smarty_Internal_Template $smarty Instancia del objeto Smarty.
 * @return bool
 */
function smarty_modifier_CheckArray($array) {
	return is_array( $array );
}