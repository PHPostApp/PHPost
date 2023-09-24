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
$tsPage = "registro";

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
$tsTitle = "Crea tu cuenta en {$tsCore->settings['titulo']}";

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
   // SOLO MENORES DE 84 AÑOS xD Y MAYORES DE...
   $now_year = date("Y", time());
   // 100años - 16años = 84años
   $edad = (int)$tsCore->settings['c_allow_edad'];
   $max_year = 100 - $edad;
   $start_year = (int)$now_year - (int)$max_year;
   $end_year = (int)$now_year - (int)$tsCore->settings['c_allow_edad'];
   //
   $smarty->assign("tsMax", (int)$max_year);
   $smarty->assign("tsMaxY", (int)$start_year);
   $smarty->assign("tsEndY", (int)$end_year);

   // Registro abierto
   $smarty->assign('tsAbierto', (int)$tsCore->settings["c_reg_active"]);
      
}


if(empty($tsAjax)) {	
	$smarty->assign("tsTitle", $tsTitle);
	include TS_ROOT . "footer.php";
}