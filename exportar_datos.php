<?php
session_start(); // se inicia la sesion para poder sacar el id del usuario

if (!isset($_SESSION['id_usuario'])) { // primero compruebo si el usuario esta logueado porque si no no hay datos para exportar
    header('Location: login.php');
    exit;
}

require_once 'includes/iniciarDB.php'; // se conecta a la base de datos

$idUsuario = $_SESSION['id_usuario']; // le saco el id al usuario de la sesion

// saco todos los datos del usuario con sus anuncios, fotos, mensajes y solicitudes de folletos 
$queryUsuario = "SELECT 
                    u.IdUsuario,
                    u.NomUsuario,
                    u.Clave,
                    u.Email,
                    u.Sexo,
                    u.FNacimiento,
                    u.Ciudad AS CiudadUsuario,
                    p.NomPais AS PaisUsuario,
                    u.Foto,
                    u.FRegistro AS FRegistroUsuario,
                    e.Nombre AS NombreEstilo
                FROM Usuarios u
                LEFT JOIN Paises p ON u.Pais = p.IdPais
                LEFT JOIN Estilos e ON u.Estilo = e.IdEstilo
                WHERE u.IdUsuario = ?";

$stmt = $db->prepare($queryUsuario); // se prepara la query
$stmt->bind_param('i', $idUsuario); // se le vincula el parametro que es un int porque es el id del usuario
$stmt->execute(); // y se ejecuta
$resultadoUsuario = $stmt->get_result(); // le saco los resultados
$usuario = $resultadoUsuario->fetch_assoc(); // y lo guardo en un array asociativo

if (!$usuario) { // si hay un error en la consulta se muestra un error y se para la ejecucion
    die('Error: Usuario no encontrado');
}

// arhora se sacan todos los anuncios del usuario con todos sus datos relacionados
$queryAnuncios = "SELECT 
                    a.IdAnuncio,
                    ta.NomTAnuncio,
                    tv.NomTVivienda,
                    a.FPrincipal,
                    a.Alternativo,
                    a.Titulo,
                    a.Precio,
                    a.Texto,
                    a.Ciudad,
                    p.NomPais,
                    a.Superficie,
                    a.NHabitaciones,
                    a.NBanyos,
                    a.Planta,
                    a.Anyo,
                    a.FRegistro
                FROM Anuncios a
                LEFT JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
                LEFT JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
                LEFT JOIN Paises p ON a.Pais = p.IdPais
                WHERE a.Usuario = ?
                ORDER BY a.FRegistro DESC";

$stmt = $db->prepare($queryAnuncios);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultadoAnuncios = $stmt->get_result();

$anuncios = []; // me creo un array para guardar los anuncios
while ($fila = $resultadoAnuncios->fetch_assoc()) { // y se guardan todos los que haya
    $anuncios[] = $fila;
}

// arhora se sacan todas las fotos de cada anuncio igual
$fotosPorAnuncio = [];
foreach ($anuncios as $anuncio) {
    $queryFotos = "SELECT IdFoto, Titulo, Foto, Alternativo 
                   FROM Fotos 
                   WHERE Anuncio = ?";
    
    $stmt = $db->prepare($queryFotos);
    $stmt->bind_param('i', $anuncio['IdAnuncio']);
    $stmt->execute();
    $resultadoFotos = $stmt->get_result();
    
    $fotos = []; 
    while ($foto = $resultadoFotos->fetch_assoc()) { // me guardo las fotos
        $fotos[] = $foto;
    }
    
    $fotosPorAnuncio[$anuncio['IdAnuncio']] = $fotos; // y las asigno al anuncio correspondiente
}

// arhora se sacan todos los mensajes enviados del usuario tambien
$queryMensajesEnviados = "SELECT 
                            m.IdMensaje,
                            tm.NomTMensaje,
                            m.Texto,
                            m.Anuncio,
                            uDestino.NomUsuario AS UsuarioDestino,
                            m.FRegistro
                        FROM Mensajes m
                        LEFT JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje
                        LEFT JOIN Usuarios uDestino ON m.UsuDestino = uDestino.IdUsuario
                        WHERE m.UsuOrigen = ?
                        ORDER BY m.FRegistro DESC";

$stmt = $db->prepare($queryMensajesEnviados);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultadoMensajesEnviados = $stmt->get_result();

$mensajesEnviados = [];
while ($mensaje = $resultadoMensajesEnviados->fetch_assoc()) { // me los guardo todos
    $mensajesEnviados[] = $mensaje;
}

// se sacan todos los mensajes recibidos del usuario tambien
$queryMensajesRecibidos = "SELECT 
                            m.IdMensaje,
                            tm.NomTMensaje,
                            m.Texto,
                            m.Anuncio,
                            uOrigen.NomUsuario AS UsuarioOrigen,
                            m.FRegistro
                        FROM Mensajes m
                        LEFT JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje
                        LEFT JOIN Usuarios uOrigen ON m.UsuOrigen = uOrigen.IdUsuario
                        WHERE m.UsuDestino = ?
                        ORDER BY m.FRegistro DESC";

$stmt = $db->prepare($queryMensajesRecibidos);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultadoMensajesRecibidos = $stmt->get_result();

$mensajesRecibidos = [];
while ($mensaje = $resultadoMensajesRecibidos->fetch_assoc()) {
    $mensajesRecibidos[] = $mensaje;
}

// se sacan todas las solicitudes de folleto con todos los datos 
$querySolicitudes = "SELECT 
                        s.IdSolicitud,
                        s.Anuncio,
                        s.Texto,
                        s.Nombre,
                        s.Email,
                        s.Direccion,
                        s.Telefono,
                        s.Color,
                        s.Copias,
                        s.Resolucion,
                        s.Fecha,
                        s.IColor,
                        s.IPrecio,
                        s.FRegistro,
                        s.Coste
                    FROM Solicitudes s
                    INNER JOIN Anuncios a ON s.Anuncio = a.IdAnuncio
                    WHERE a.Usuario = ?
                    ORDER BY s.FRegistro DESC";

$stmt = $db->prepare($querySolicitudes);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultadoSolicitudes = $stmt->get_result();

$solicitudes = [];
while ($solicitud = $resultadoSolicitudes->fetch_assoc()) {
    $solicitudes[] = $solicitud;
}

////////////////////////// AQUI SE GENERA EL XML /////////////////////////////////////
generarXMLExportacion($usuario, $anuncios, $fotosPorAnuncio, $mensajesEnviados, $mensajesRecibidos, $solicitudes);

$db->close();

function generarXMLExportacion($usuario, $anuncios, $fotosPorAnuncio, $mensajesEnviados, $mensajesRecibidos, $solicitudes) { // le paso todos los datos necesarios para generar el xml
    // basicamente esto es lo mismo que el rss y el atom pero me puedo invernar mis propias etiquetas y estructura porque es un xml personalizado para exportar los datos del usuario
    $doc = new DOMDocument('1.0', 'UTF-8'); // se crea el dom
    $doc->formatOutput = true;
    
    $raiz = $doc->createElement('ExportacionPisosInmuebles'); // se crea el elemento raiz
    $doc->appendChild($raiz);
    
    $usuarioElement = $doc->createElement('Usuario'); // se crea el elemento usuario que es el que contiene todos los datos del usuario, es como el canal en rss o atom osea todo lo demas va dentro de este
    $usuarioElement->setAttribute('IdUsuario', $usuario['IdUsuario']);
    $raiz->appendChild($usuarioElement);
    
    $nomUsuario = $doc->createElement('NomUsuario'); // se pone el nombre de usuario
    $nomUsuario->appendChild($doc->createTextNode($usuario['NomUsuario']));
    $usuarioElement->appendChild($nomUsuario);
    
    $clave = $doc->createElement('Clave'); // la contrasenya
    $clave->appendChild($doc->createTextNode($usuario['Clave']));
    $usuarioElement->appendChild($clave);
    
    $email = $doc->createElement('Email'); // el email
    $email->appendChild($doc->createTextNode($usuario['Email']));
    $usuarioElement->appendChild($email);
    
    $sexo = $doc->createElement('Sexo'); // el sexo
    $sexo->appendChild($doc->createTextNode($usuario['Sexo'] ?? ''));
    $usuarioElement->appendChild($sexo);
    
    $fNacimiento = $doc->createElement('FechaNacimiento'); // la fecha de nacimiento
    $fNacimiento->appendChild($doc->createTextNode($usuario['FNacimiento'] ?? ''));
    $usuarioElement->appendChild($fNacimiento);
    
    $ciudad = $doc->createElement('Ciudad'); // la ciudad
    $ciudad->appendChild($doc->createTextNode($usuario['CiudadUsuario'] ?? ''));
    $usuarioElement->appendChild($ciudad);
    
    $pais = $doc->createElement('Pais'); // el pais
    $pais->appendChild($doc->createTextNode($usuario['PaisUsuario'] ?? ''));
    $usuarioElement->appendChild($pais);
    
    $foto = $doc->createElement('Foto'); // la foto de perfil
    $foto->appendChild($doc->createTextNode($usuario['Foto'] ?? ''));
    $usuarioElement->appendChild($foto);
    
    $fRegistro = $doc->createElement('FechaRegistro'); // la fecha de registro
    $fRegistro->appendChild($doc->createTextNode($usuario['FRegistroUsuario']));
    $usuarioElement->appendChild($fRegistro);
    
    $estilo = $doc->createElement('Estilo'); // el estilo seleccionado
    $estilo->appendChild($doc->createTextNode($usuario['NombreEstilo'] ?? ''));
    $usuarioElement->appendChild($estilo);
    
    $anunciosElement = $doc->createElement('Anuncios'); // se crea el elemento anuncios que contiene todos los anuncios del usuario
    $usuarioElement->appendChild($anunciosElement);
    
    foreach ($anuncios as $anuncio) { // se recorren todos los anuncios para crear sus elementos
        $anuncioElement = $doc->createElement('Anuncio'); // se crea el elemento anuncio
        $anuncioElement->setAttribute('IdAnuncio', $anuncio['IdAnuncio']); // se le pone el id como atributo
        $anunciosElement->appendChild($anuncioElement); 
        
        // ahora van todos los datos del anuncio que se anyaden como elementos creados con la funcion para simplificar el codigo porque si no se queda muy largo
        agregarElementoTexto($doc, $anuncioElement, 'TipoAnuncio', $anuncio['NomTAnuncio'] ?? ''); // tipo de anuncio
        agregarElementoTexto($doc, $anuncioElement, 'TipoVivienda', $anuncio['NomTVivienda'] ?? ''); // tipo de vivienda
        agregarElementoTexto($doc, $anuncioElement, 'Titulo', $anuncio['Titulo']); // titulo
        agregarElementoTexto($doc, $anuncioElement, 'Precio', $anuncio['Precio']); // precio
        agregarElementoTexto($doc, $anuncioElement, 'Descripcion', $anuncio['Texto']); // descripcion
        agregarElementoTexto($doc, $anuncioElement, 'Ciudad', $anuncio['Ciudad']); // ciudad
        agregarElementoTexto($doc, $anuncioElement, 'Pais', $anuncio['NomPais'] ?? ''); // pais
        agregarElementoTexto($doc, $anuncioElement, 'Superficie', $anuncio['Superficie'] ?? ''); // superficie
        agregarElementoTexto($doc, $anuncioElement, 'NumHabitaciones', $anuncio['NHabitaciones'] ?? ''); // numero de habitaciones
        agregarElementoTexto($doc, $anuncioElement, 'NumBanyos', $anuncio['NBanyos'] ?? ''); // numero de banyos 
        agregarElementoTexto($doc, $anuncioElement, 'Planta', $anuncio['Planta'] ?? ''); // planta
        agregarElementoTexto($doc, $anuncioElement, 'AnyoConstruccion', $anuncio['Anyo'] ?? ''); // anyo de construccion
        agregarElementoTexto($doc, $anuncioElement, 'FotoPrincipal', $anuncio['FPrincipal'] ?? ''); // foto principal
        agregarElementoTexto($doc, $anuncioElement, 'TextoAlternativo', $anuncio['Alternativo'] ?? ''); // texto alternativo de la foto principal
        agregarElementoTexto($doc, $anuncioElement, 'FechaPublicacion', $anuncio['FRegistro']); // fecha de publicacion
        
        // ahora las fotos del anuncio
        if (isset($fotosPorAnuncio[$anuncio['IdAnuncio']]) && count($fotosPorAnuncio[$anuncio['IdAnuncio']]) > 0) { // si hay fotos para este anuncio entonces se crean los elementos
            $fotosElement = $doc->createElement('Fotos');
            $anuncioElement->appendChild($fotosElement);
            
            foreach ($fotosPorAnuncio[$anuncio['IdAnuncio']] as $foto) { // se recorren todas las fotos del anuncio
                $fotoElement = $doc->createElement('Foto'); // se crea el elemento foto
                $fotoElement->setAttribute('IdFoto', $foto['IdFoto']); // y se le pone el id como atributo
                $fotosElement->appendChild($fotoElement);
                
                agregarElementoTexto($doc, $fotoElement, 'Titulo', $foto['Titulo'] ?? ''); // titulo de la foto
                agregarElementoTexto($doc, $fotoElement, 'RutaFoto', $foto['Foto']); // ruta de la foto
                agregarElementoTexto($doc, $fotoElement, 'TextoAlternativo', $foto['Alternativo'] ?? ''); // el texto alternativo de la foto
            }
        }
    }
    
    $mensajesElement = $doc->createElement('Mensajes'); // se crea el elemento mensajes que contiene todos los mensajes enviados y recibidos
    $usuarioElement->appendChild($mensajesElement);
    
    $mensajesEnviadosElement = $doc->createElement('MensajesEnviados');
    $mensajesElement->appendChild($mensajesEnviadosElement);
    
    foreach ($mensajesEnviados as $mensaje) { // se recorren todos los mensajes enviados 
        $mensajeElement = $doc->createElement('Mensaje');
        $mensajeElement->setAttribute('IdMensaje', $mensaje['IdMensaje']); // se crea el elemento mensaje y se le pone el id como atributo
        $mensajesEnviadosElement->appendChild($mensajeElement);
        
        agregarElementoTexto($doc, $mensajeElement, 'TipoMensaje', $mensaje['NomTMensaje'] ?? ''); // tipo de mensaje 
        agregarElementoTexto($doc, $mensajeElement, 'Texto', $mensaje['Texto']); // texto del mensaje
        agregarElementoTexto($doc, $mensajeElement, 'IdAnuncioRelacionado', $mensaje['Anuncio'] ?? ''); // id del anuncio relacionado
        agregarElementoTexto($doc, $mensajeElement, 'UsuarioDestino', $mensaje['UsuarioDestino'] ?? ''); // usuario destino
        agregarElementoTexto($doc, $mensajeElement, 'FechaEnvio', $mensaje['FRegistro']); // fecha de envio
    }
    
    // los mensajes recibidos son iguales que los enviados
    $mensajesRecibidosElement = $doc->createElement('MensajesRecibidos');
    $mensajesElement->appendChild($mensajesRecibidosElement);
    
    foreach ($mensajesRecibidos as $mensaje) { // se recorren todos los mensajes recibidos
        $mensajeElement = $doc->createElement('Mensaje');
        $mensajeElement->setAttribute('IdMensaje', $mensaje['IdMensaje']); // se crea el elemento mensaje y se le pone el id como atributo
        $mensajesRecibidosElement->appendChild($mensajeElement);
        
        agregarElementoTexto($doc, $mensajeElement, 'TipoMensaje', $mensaje['NomTMensaje'] ?? ''); // tipo de mensaje
        agregarElementoTexto($doc, $mensajeElement, 'Texto', $mensaje['Texto']); // texto del mensaje
        agregarElementoTexto($doc, $mensajeElement, 'IdAnuncioRelacionado', $mensaje['Anuncio'] ?? ''); // id del anuncio relacionado
        agregarElementoTexto($doc, $mensajeElement, 'UsuarioOrigen', $mensaje['UsuarioOrigen'] ?? ''); // usuario origen
        agregarElementoTexto($doc, $mensajeElement, 'FechaRecepcion', $mensaje['FRegistro']); // fecha de recepcion
    }
    
    // ahoara las solicitudes de folletos
    if (count($solicitudes) > 0) { // si hay solicitudes entonces se crean los elementos
        $solicitudesElement = $doc->createElement('SolicitudesFolletos');
        $usuarioElement->appendChild($solicitudesElement);
        
        foreach ($solicitudes as $solicitud) { // se recorren todas las solicitudes
            $solicitudElement = $doc->createElement('Solicitud');
            $solicitudElement->setAttribute('IdSolicitud', $solicitud['IdSolicitud']);
            $solicitudesElement->appendChild($solicitudElement);
            
            agregarElementoTexto($doc, $solicitudElement, 'IdAnuncio', $solicitud['Anuncio']); // id del anuncio 
            agregarElementoTexto($doc, $solicitudElement, 'Texto', $solicitud['Texto'] ?? ''); // texto adicional
            agregarElementoTexto($doc, $solicitudElement, 'NombreSolicitante', $solicitud['Nombre']); // nombre del solicitante
            agregarElementoTexto($doc, $solicitudElement, 'Email', $solicitud['Email']); // email del solicitante
            agregarElementoTexto($doc, $solicitudElement, 'Direccion', $solicitud['Direccion'] ?? ''); // direccion del solicitante
            agregarElementoTexto($doc, $solicitudElement, 'Telefono', $solicitud['Telefono'] ?? ''); // telefono del solicitante
            agregarElementoTexto($doc, $solicitudElement, 'Color', $solicitud['Color'] ?? ''); // color solicitado
            agregarElementoTexto($doc, $solicitudElement, 'Copias', $solicitud['Copias'] ?? '');
            agregarElementoTexto($doc, $solicitudElement, 'Resolucion', $solicitud['Resolucion'] ?? ''); // resolucion solicitada
            agregarElementoTexto($doc, $solicitudElement, 'Fecha', $solicitud['Fecha'] ?? '');
            agregarElementoTexto($doc, $solicitudElement, 'ImprimirColor', $solicitud['IColor'] ? 'Si' : 'No'); // si quiere imprimir en color o no
            agregarElementoTexto($doc, $solicitudElement, 'ImprimirPrecio', $solicitud['IPrecio'] ? 'Si' : 'No'); // si quiere imprimir el precio o no
            agregarElementoTexto($doc, $solicitudElement, 'FechaSolicitud', $solicitud['FRegistro']);
            agregarElementoTexto($doc, $solicitudElement, 'Coste', $solicitud['Coste'] ?? '');
        }
    }
    
    $xmlContent = $doc->saveXML(); // se guarda el xml en una variable
    
    header('Content-Type: application/xml; charset=UTF-8'); // se envian las cabeceras para descargar el archivo
    header('Content-Disposition: attachment; filename="exportacion_' . $usuario['NomUsuario'] . '_' . date('Y-m-d') . '.xml"'); // se le pone un nombre al archivo
    header('Content-Length: ' . strlen($xmlContent)); // se pone la longitud del contenido
    
    echo $xmlContent; // se muestra el contenido del xml para que se descargue
}

// como tengo que repetir mucho codigo para crear elementos con texto he hecho esta funcion para simplificarlo.
function agregarElementoTexto($doc, $padre, $nombre, $valor) { // simplemente hace un elemento con texto y lo anyade al padre que le pases

    $elemento = $doc->createElement($nombre);
    $elemento->appendChild($doc->createTextNode($valor ?? ''));
    $padre->appendChild($elemento);
}
?>
