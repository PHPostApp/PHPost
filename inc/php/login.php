<?php
/**
 * Registro
 * -------------------------------------------------------------
 * File:     registro.php
 * Name:     registro
 * Purpose:  Control del registro
 * @link:    https://phpost.net/foro/
 * @link:    https://phpost.es/
 * @author:  Miguel92
 * @version: 1.0
 * -------------------------------------------------------------
*/

/**
 * Nombre asignado para el archivo .tpl
*/
$tsPage = "login";

/**
 * Nivel de acceso a esta página
 * 0 - todos | 1 - visitantes | 2 - miembros | 3 - moderadores | 4 - administradores
*/
$tsLevel = 1; 

/**
 * Tipo de respuesta
*/
$tsAjax = empty($_GET['ajax']) ? 0 : 1;

/**
 * En caso de problemas la variable cambia
*/
$tsContinue = true;  // CONTINUAR EL SCRIPT

include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

/**
 * Incluimos el título a la página
*/
$tsTitle = "Inica sesión en {$tsCore->settings['titulo']}";

/**
 * Verificamos el nivel de acceso
*/
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if ($tsLevelMsg != 1) {
   $tsPage = 'aviso';
   $tsAjax = 0;
   $smarty->assign("tsAviso", $tsLevelMsg);
   $tsContinue = false;
}

if($tsUser->is_member) header("Location: ./");

/**
 * Si no hay problemas, continuamos
*/
if ($tsContinue) {
      
}

if(empty($tsAjax)) {	
	$smarty->assign("tsTitle", $tsTitle);
	include TS_ROOT . "footer.php";
}