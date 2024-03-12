<?php
/**
 * Resuelve para la home
 *
 * @name    index.php
 * @author  PHPost Team
 */

/*
 * -------------------------------------------------------------------
 *  Validamos que mostrar home/mi
 * -------------------------------------------------------------------
 */

 // Incluimos header
include_once realpath(__DIR__) . DIRECTORY_SEPARATOR . 'header.php';

// Checamos...
if($tsCore->settings['c_allow_portal'] == 1 && $tsUser->is_member == true && $_GET['do'] == 'portal') {
   // Portal/mi
   include TS_PHP . 'portal.php';
} else {
   // Home
   include TS_PHP . 'posts.php';
}