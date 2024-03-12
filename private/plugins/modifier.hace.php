<?php

/**
 * Autor: Ivan Molina Pavana
 * Ejemplo: {$date|hace:true} = Hace 1 minuto | Hace 3 días
 * Enlace: #
 * Fecha: Feb 24, 2010
 * Nombre: hace
 * Proposito: Hace cuanto se realizo
 * Tipo: modifier
 * Version: 2.0
*/

function smarty_modifier_hace($fecha, $show = false){
    $ahora = time();
    $tiempo = $ahora - $fecha; 
    $dias = round($tiempo / 86400);

    if($dias <= 0) {
        $horas = round($tiempo / 3600);
        if($horas <= 0){ 
            $minutos = round($tiempo / 60);
            return ($minutos <= 0) ? "Hace unos segundos" : 'Hace '. $minutos . ($minutos == 1 ? " minuto" : " minutos"); 
        } else return 'Hace '. $horas . ($horas == 1 ? " hora" : " horas");     
    } else if($dias <= 30){
        return ($dias < 2) ? 'Ayer' : 'Hace '.$dias.' días';
    } else{
        $meses = round($tiempo / 2592000);
        if($meses == 1) return 'Más de 1 mes';
        elseif($meses < 12) return 'Más de '.$meses.' meses';
        else {
            $anos = round($tiempo / 31536000);
            return 'Más de '. ($anos == 1 ? "un año" : $anos.' años');
        }
    }
}