<?php

/**
 * Autor: Ivan Molina Pavana
 * Ejemplo: {$text|quot} = &#039;Este texto&#039; => 'Este texto'
 * Enlace: #
 * Fecha: Feb 24, 2010
 * Nombre: quot
 * Proposito: Reemplaza &amp; por &, &#039; por ' y agrega saltos
 * Tipo: modifier
 * Version: 1.0
*/

function smarty_modifier_quot($texto){
    // MINI HACK
    $texto = str_replace("&amp;",'&',$texto);
    $texto = str_replace("&#039;","'",$texto);
    $texto = nl2br($texto);
    //
    return $texto;
}

/* vim: set expandtab: */

?>
