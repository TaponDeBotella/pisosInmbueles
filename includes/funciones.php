<?php
    function costePorPagina($paginas){
            $precio=0;

            if ($paginas < 5){
                $precio=$paginas*2.0;
            }elseif ($paginas <= 10){
                $precio=(4*2.0) + (($paginas - 4 ) * 1.8);

            }else {
                $precio = (4*2.0)+(6*1.8)+(($paginas-10) * 1.6);
            }
            return $precio;
        }


        function calcularPrecio($paginas,$fotos,$color,$dpi){
            $precioEnvio=10.00;
            $precioPag=costePorPagina($paginas);

            $precioFotoColor= $color ? 0.5 : 0.0;

            $precioResolucion = ($dpi > 300) ? 0.2 : 0.0;

            $precioFotos = $fotos * ($precioFotoColor+$precioResolucion);

            return $precioEnvio + $precioPag + $precioFotos;

        }
