<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $title="Respuesta folleto";
    $acceder = "Mi perfil";
    $css="css/respuesta_folleto.css";
   
    require_once 'includes/funciones.php';

    $solicitud_registrada = false; // variable para saber si se ha registrado bien la solicitud
    $error_mensaje = ''; // todas las variables que se van a usar que son las del form
    $texto_adicional = '';
    $nombre_folleto = '';
    $email_folleto = '';
    $calle_folleto = '';
    $numCalle_folleto = '';
    $cp_folleto = '';
    $localidad_folleto = '';
    $provincia_folleto = '';
    $tel_folleto = '';
    $color_folleto = '';
    $numCopias_folleto = 0;
    $resolucion_folleto = 0;
    $anuncio_folleto = 0;
    $fecha_folleto = '';
    $impresion_color = '';
    $impresion_precio = '';
    $precioFinal = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // se comprueba si ha habido un post y se le sacan todos los datos

        if (empty($_POST['nombre_folleto']) || empty($_POST['email_folleto']) || empty($_POST['calle_folleto']) || empty($_POST['anuncio_folleto'])){ // si falta alguno de los campos obligatorios se manda error
         
            header('Location: ' . $_SERVER['HTTP_REFERER']."?error");
            exit; 
        } else { // si estan todos los datos entonces se sigue
       
            $texto_adicional = $_POST['texto_adicional_folleto'] ?? ''; // si hay texto adicional y no es null entonces se saca el valor, si no entonces se pone texto vacio
            $nombre_folleto = $_POST['nombre_folleto'];
            $email_folleto = $_POST['email_folleto'];
            $calle_folleto = $_POST['calle_folleto'];
            $numCalle_folleto = $_POST['numCalle_folleto'] ?? '';
            $cp_folleto = $_POST['cp_folleto'] ?? '';
            $localidad_folleto = $_POST['localidad_folleto'] ?? '';
            $provincia_folleto = $_POST['provincia_folleto'] ?? '';
            $tel_folleto = $_POST['tel_folleto'] ?? '';
            $color_folleto = $_POST['color_folleto'] ?? '';
            $numCopias_folleto = intval($_POST['numCopias_folleto']);
            $resolucion_folleto = intval($_POST['resolucion_folleto']);
            $anuncio_folleto = intval($_POST['anuncio_folleto']);
            $fecha_folleto = $_POST['fecha_folleto'] ?? '';
            $impresion_color = $_POST['impresion_color'] ?? '';
            $impresion_precio = $_POST['impresion_precio'] ?? '';

            // como la direccion se guarda en una columna solo entonces la junto toda para guardarla bien
            $direccion = $calle_folleto . ', ' . $numCalle_folleto . ', ' . $cp_folleto . ' ' . $localidad_folleto . ', ' . $provincia_folleto;

            // hago el calculo del precio final como lo hice en las practicas anteriores
            $numero_fotos = $numCopias_folleto * 3; // por cada folleto hay 3 fotos
            $color = ($impresion_color == "si") ? 1 : 0; // se convierte a bool lo de imprimir en color o no
            $iprecio = ($impresion_precio == "si") ? 1 : 0; // y lo del precio tambien
            $precioFinal = calcularPrecio($numCopias_folleto, $numero_fotos, (bool)$color, $resolucion_folleto); // llamo a la funcion para calcular los precios

            $stmt = $db->prepare(
                "INSERT INTO Solicitudes (Anuncio, Texto, Nombre, Email, Direccion, Telefono, Color, Copias, Resolucion, Fecha, IColor, IPrecio, Coste) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" // preparo la query para hacer el post 
            );

            if (!$stmt) { // por si por lo que sea hay algun error se muestra
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else { // si no entonces se sigue
                $stmt->bind_param( // se vinculan todos los parametros con i para int, s para string y d para double
                    'issssssiisiid',
                    $anuncio_folleto,
                    $texto_adicional,
                    $nombre_folleto,
                    $email_folleto,
                    $direccion,
                    $tel_folleto,
                    $color_folleto,
                    $numCopias_folleto,
                    $resolucion_folleto,
                    $fecha_folleto,
                    $color,
                    $iprecio,
                    $precioFinal
                );

                if (!$stmt->execute()) { // si no se puede ejecutar se manda el error
                    $error_mensaje = 'Error al registrar la solicitud: ' . $stmt->error;
                } else { // si no entonces se pone que se ha registrado bien
                    $solicitud_registrada = true;
                }

                $stmt->close(); // se cierra el insert
            }
        }
    }

    include 'includes/header.php';
?>

        <h1>Respuesta solicitud folleto</h1>

        <?php
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section class="error">';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '</section>';
            } elseif ($solicitud_registrada) { // si no hay error entonces se muestra la respuesta con todos los datos que se han metido
                echo '<section class="exito">';
                echo '<p>Solicitud registrada con éxito, aquí hay un resumen de su pedido:</p>';
        ?>
                <p>
                    <strong>Nombre:</strong> <?php echo htmlspecialchars($nombre_folleto); ?>
                </p>    
                <p>
                    <strong>Correo electrónico:</strong> <?php echo htmlspecialchars($email_folleto); ?> 
                </p>
                <p>
                    <strong>Texto adicional:</strong> <?php echo htmlspecialchars($texto_adicional); ?> 
                </p>
                <p>
                    <strong>Dirección:</strong> <?php echo htmlspecialchars($calle_folleto) . ', ' . htmlspecialchars($numCalle_folleto) . ', ' . htmlspecialchars($cp_folleto) . ' ' . htmlspecialchars($localidad_folleto) . ', ' . htmlspecialchars($provincia_folleto); ?> 
                </p>
                <p>
                    <strong>Teléfono:</strong> <?php echo htmlspecialchars($tel_folleto); ?> 
                </p>
                <p>
                    <strong>Color de la portada:</strong> <input type="color" disabled value="<?php echo htmlspecialchars($color_folleto); ?>"> 
                </p>
                <p>
                    <strong>Número de páginas:</strong> <?php echo htmlspecialchars($numCopias_folleto); ?>  
                </p>
                <p>
                    <strong>Resolución de las fotos:</strong> <?php echo htmlspecialchars($resolucion_folleto); ?> dpi
                </p>
                <p>
                    <strong>Fecha de recepción:</strong> <?php echo htmlspecialchars($fecha_folleto); ?> 
                </p>
                <p>
                    <strong>Impresión a color:</strong> <?php echo ($impresion_color == 'si') ? 'Sí' : 'No'; ?> 
                </p>
                <p>
                    <strong>Impresión del precio:</strong> <?php echo ($impresion_precio == 'si') ? 'Sí' : 'No'; ?>
                </p>
                <p>
                    <strong>Precio total:</strong> <?php echo htmlspecialchars($precioFinal); ?> €
                </p>
                <nav>
                    <a href="solicitar_folleto.php" class="boton">Volver</a>
                </nav>
        <?php
                echo '</section>';
            } else {
                echo '<p>No hay datos para procesar.</p>';
            }
        ?>
   
<?php
    include 'includes/footer.php';
?>