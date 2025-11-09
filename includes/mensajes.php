<?php
    function obtenerMensajeBienvenida($nombre) { // se le pasa el nombre del usuario
        $hora = (int)date('H'); // se obtiene la hora actual 
        
        if ($hora >= 6 && $hora <= 11) { // si es de 6 a 11:59 se le dice buenos dias
            return "¡Buenos días " . htmlspecialchars($nombre);
        } elseif ($hora >= 12 && $hora <= 15) { // si es de 12 a 15:59 se le dice hola solo
            return "¡Hola " . htmlspecialchars($nombre);
        } elseif ($hora >= 16 && $hora <= 19) { // si es de 16 a 19:59 se le dice buenas tardes
            return "¡Buenas tardes " . htmlspecialchars($nombre);
        } else { // y si es de 20 a 5:59 se le dice buenas noches
            return "¡Buenas noches " . htmlspecialchars($nombre);
        }
    }
?>
