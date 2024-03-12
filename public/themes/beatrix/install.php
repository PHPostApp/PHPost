<?php
/**
 * Información del tema
 * Adaptado por Miguel92 para PHPost 23
 * @name install.php
 * @filesource /nombre_del_tema/install.php
*/
/*
 * -------------------------------------------------------------------
 *  Nombre del Tema
 * -------------------------------------------------------------------
 */
$tema['nombre'] = pathinfo(__DIR__)["basename"];
/*
 * -------------------------------------------------------------------
 *  Dirección URL del tema | Esta linea no debe ser modificada.
 * -------------------------------------------------------------------
 */
$tema['url'] = $tsCore->settings['url'].'/themes/'.$tema_path;
/*
 * -------------------------------------------------------------------
 *  Copyright
 * -------------------------------------------------------------------
 */
$tema['copy'] = 'Miguel92 &copy; ' . date('Y');
