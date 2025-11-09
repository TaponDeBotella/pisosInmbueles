<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Respuesta folleto";
    $acceder = "Mi perfil";
    $css="css/respuesta_folleto.css";
   
    require_once 'includes/funciones.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['nombre_folleto']) || empty($_POST['email_folleto']) || empty($_POST['calle_folleto'])){
         
            header('Location: ' . $_SERVER['HTTP_REFERER']."?error");
            exit; 
        }else{
       
            $texto_adicional= $_POST['texto_adicional_folleto'];
            $nombre_folleto= $_POST['nombre_folleto'];
            $email_folleto= $_POST['email_folleto'];
            $calle_folleto= $_POST['calle_folleto'];
            $numCalle_folleto= $_POST['numCalle_folleto'];
            $cp_folleto= $_POST['cp_folleto'];
            $localidad_folleto= $_POST['localidad_folleto'];
            $provincia_folleto= $_POST['provincia_folleto'];
            $tel_folleto= $_POST['tel_folleto'];
            $color_folleto= $_POST['color_folleto'];
            $numCopias_folleto= intval($_POST['numCopias_folleto']);
            $resolucion_folleto= intval($_POST['resolucion_folleto']);
            $anuncio_folleto= $_POST['anuncio_folleto'];
            $fecha_folleto= $_POST['fecha_folleto'];
            $impresion_color= $_POST['impresion_color'];
            $impresion_precio= $_POST['impresion_precio'];


            $numero_fotos=$numCopias_folleto * 3; 
            $color=false;
            if ($impresion_color=="si"){
                $color=true;
            }
            $precioFinal= number_format(calcularPrecio($numCopias_folleto, $numero_fotos,$color,$resolucion_folleto),2);
        }
    }

 include 'includes/header.php';


?>



        <h1>Respuesta solicitud folleto</h1>

        <section>
            <p>Solicitud registrada con éxito, aquí hay un resumen de su pedido:</p> 
            <p>
                <strong>Nombre:</strong> <?php echo $nombre_folleto; ?>
            </p>    
            <p>
                </strong>Correo electrónico:</strong> <?php echo $email_folleto; ?> 
            </p>
            <p>
                <strong>Texto adicional:</strong> <?php echo $texto_adicional; ?> 
            </p>
            <p>
                <strong>Dirección:</strong> <?php echo $calle_folleto .", " .$numCalle_folleto. ", " . $cp_folleto . " " .$localidad_folleto . ", " .$provincia_folleto; ?> 
            </p>
            <p>
                <strong>Teléfono:</strong> <?php echo $tel_folleto; ?> 
            </p>
            <p>
                <strong>Color de la portada:</strong><input type="color" id="color_folleto" name="color_folleto" value= <?php echo $color_folleto; ?>" disabled> 
            </p>
            <p>
                <strong>Número de copias:</strong> <?php echo $numCopias_folleto; ?>  
            </p>
            <p>
                <strong>Resolución de las fotos:</strong> <?php echo $resolucion_folleto; ?> 
            </p>
            <p>
                <strong>Anuncio empleado:</strong> <?php echo $anuncio_folleto; ?> 
            </p>
            <p>
                <strong>Fecha de recepción:</strong> <?php echo $fecha_folleto; ?> 
            </p>
            <p>
                <strong>Impresión a color:</strong> <?php echo $impresion_color; ?> 
            <p>
                <strong>Impresión del precio:</strong> <?php echo $impresion_precio; ?>
            </p>
            <p>
                <strong>Precio total:</strong> <?php echo $precioFinal; ?> €
            </p>
        </section>        
   
        
        
<?php
    include 'includes/footer.php';
?>