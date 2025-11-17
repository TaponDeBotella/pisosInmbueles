<?php
    session_start();

    // se verifica que el usuario esta logueado
    if(!isset($_SESSION['logueado'])) {
        header('Location: login.php?error=no_logueado');
        exit;
    }
    
    // se llama a la base de datos
    include 'includes/iniciarDB.php';
    
    $resultado = null;
    
    // como hay que hacer un post se pone antes de header.php porque si no se lia un poco
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estilo'])) {
        $estilo_seleccionado = $_POST['estilo'];
        
        // obtener los estilos de la base de datos para validar
        $resultado_validar = $db->query("SELECT IdEstilo, Fichero FROM Estilos WHERE Fichero = '" . $estilo_seleccionado . "'");
        
        if (!$resultado_validar) { // si hay error se para y se lanza el error
            die('Error: ' . $db->error);
        }
        
        // se verifica que el estilo existe en la base de datos y saca su id
        if ($resultado_validar->num_rows > 0) {
            $estilo_valido = $resultado_validar->fetch_array(MYSQLI_ASSOC);
            $id_estilo = $estilo_valido['IdEstilo'];
            
            // le pone al usuario el estilo que ha seleccionado con un update
            $query = "UPDATE Usuarios SET Estilo = " . $id_estilo . " WHERE IdUsuario = " . $_SESSION['id_usuario'];
            $resultado = $db->query($query);
            
            if (!$resultado) { // si hay error se para y se lanza el error tambien
                die('Error: ' . $db->error);
            }
            
            // actualizar el session para cambiar el estilo
            $_SESSION['estilo'] = $estilo_seleccionado;
            
            // y redirige para que se cambie el estilo en la pagina sin tener que irse a otra
            header('Location: configurar.php');
            exit;
        }
    }
    
    $title = "Configurar";
    $acceder = "Acceder";
    $css = "css/configurar.css";
    include 'includes/header.php';
    
    // obtener los estilos de la base de datos
    $resultado = $db->query("SELECT IdEstilo, Nombre, Descripcion, Fichero FROM Estilos");
    
    if (!$resultado) {
        die('Error: ' . $db->error);
    }
    
    $estilos = []; // se crea el array de los estilos
    while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
        $estilos[] = [
            'id' => $fila['IdEstilo'],
            'nombre' => $fila['Nombre'],
            'descripcion' => $fila['Descripcion'],
            'fichero' => $fila['Fichero']
        ];
    }
    
    // saca el estilo actual del usuario para ver si hay uno y si no pone el predeterminado
    $estilo_actual = isset($_SESSION['estilo']) ? $_SESSION['estilo'] : 'predeterminado.css';
?>

    <h1>Configurar</h1>
    
    <section id="sectionConfiguracion">
        <h2>Selecciona tu estilo preferido</h2>
        
        <ul id="ul_estilos"> <!-- se muestran todos los estilos disponibles de la basde de datos-->
            <?php foreach ($estilos as $s): ?> <!-- se reorren todos los estilos -->
                <li>
                    <?php
                        $clase = '';
                        if ($s['fichero'] === $estilo_actual) { // si el estilo es el actual se le pone la clase seleccionado
                            $clase = 'seleccionado';
                        }
                    ?>
                    <article class="tarjeta_estilo <?= $clase ?>">
                        <form method="POST" class="form_estilo"> <!-- se crea el formulario con post para seleccionar el estilo -->
                            <input type="hidden" name="estilo" value="<?= $s['fichero'] ?>"> <!-- para cada estilo cambia el fichero -->
                            <h3><?= $s['nombre'] ?></h3> <!-- se muestra el nombre del estilo -->
                            <p class="descripcion"><?= $s['descripcion'] ?></p> <!-- y la descripcion -->
                            <?php
                                $boton_clase = 'boton-inactivo'; // por defecto el boton se pone inactivo
                                $boton_texto = 'Seleccionar'; 
                                if ($s['fichero'] === $estilo_actual) { // si es el estilo actual se pone el boton como activo y se le cambia el tetxo 
                                    $boton_clase = 'boton-activo';
                                    $boton_texto = 'Seleccionado';
                                }
                            ?>
                            <button type="submit" class="boton <?= $boton_clase ?>"> <!-- dependiendo del estilo se pone el boton activo o inactivo -->
                                <?= $boton_texto ?>
                            </button>
                        </form>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

<?php
    include 'includes/footer.php';
?>
