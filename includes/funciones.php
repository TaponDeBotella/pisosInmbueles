<?php
    function costePorPagina($paginas){ // esta funcion calcula el precio total de las paginas
            $precio=0;
            // como va por tramos hay que ir almacenando el precio
            if ($paginas < 5){ // si son menos de 5 paginas no hay problema con los tramos
                $precio=$paginas*2.0;
            }elseif ($paginas <= 10){ // si son entre 5 y 10 entonces hay que sumarle el precio de las primeras 4 paginas al precio anterior mas las paginas restantes al precio del tramo actual
                $precio=(4*2.0) + (($paginas - 4 ) * 1.8);
            }else { // lo mismo pero ahora con 3 tramos
                $precio = (4*2.0)+(6*1.8)+(($paginas-10) * 1.6);
            }
            return $precio;
        }


    function calcularPrecio($paginas,$fotos,$color,$dpi){ // esta funcion es la que se usa para calcular el precio que se va a mostrar en cada una de las celdas de la tabla 
        $precioEnvio=10.00; // esto es fijo
        $precioPag=costePorPagina($paginas); // se saca el precio de las paginas

        $precioFotoColor= $color ? 0.5 : 0.0; // ahora si es a color vale 0.5 y si no 0

        $precioResolucion = ($dpi > 300) ? 0.2 : 0.0; // si el dpi es mayor que 300 vale 0.2 y si no vale 0

        $precioFotos = $fotos * ($precioFotoColor+$precioResolucion); // ahora se saca el precio de las fotos teniendo en cuenta las dos variables que acabamos de sacar

        return $precioEnvio + $precioPag + $precioFotos; // y se suma todo

    }
?>