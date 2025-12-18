<?php
// IMPORTANTE ////////////////////////////////////////////////////////
// LOS ENLACES PARA PONER LA FEED EN LA EXTENSION SON LOS SIGUIENTES:
// EL RSS:
// http://localhost/pisosInmbueles/feed.php?formato=rss
// EL ATOM:
// http://localhost/pisosInmbueles/feed.php?formato=atom

require_once 'includes/iniciarDB.php'; // primero se conecta a la base de datos para poder sacar los anuncios

$formato = isset($_GET['formato']) ? strtolower($_GET['formato']) : 'rss'; // se saca el formato que se quiere, que puede ser rss o atom dependiendo de lo que se pase por la url o si va vacio 

if ($formato !== 'rss' && $formato !== 'atom') { // si el formato no es valido se pone rss por defecto
    $formato = 'rss';
}

// se sacan los ultimos cinco anuncios haciendo el select con el join porque asi se pueden sacar los nombres de los paises y tipos en vez de los ids para mostrarlos con nombres y no con ids 
$query = "SELECT 
            a.IdAnuncio,
            a.Titulo,
            a.Texto,
            a.Precio,
            a.Ciudad,
            a.FRegistro,
            ta.NomTAnuncio,
            tv.NomTVivienda,
            p.NomPais,
            u.NomUsuario
          FROM Anuncios a
          LEFT JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
          LEFT JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
          LEFT JOIN Paises p ON a.Pais = p.IdPais
          LEFT JOIN Usuarios u ON a.Usuario = u.IdUsuario
          ORDER BY a.FRegistro DESC
          LIMIT 5";

$resultado = $db->query($query);  // se ejecuta la consula

if (!$resultado) { // si por lo que sea la consulta falla se muestra el error
    die('Error en la consulta: ' . $db->error);
}

$anuncios = []; // me creo un array para guardar los anuncios
while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) { // se van guardando los anuncios en el array
    $anuncios[] = $fila;
}

$sitio_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); // se obtiene la url del sitio
$sitio_titulo = 'Pisos Inmuebles - Últimos Anuncios'; // este es el titulo que va a salir en el feed
$sitio_descripcion = 'Los últimos 5 anuncios de pisos e inmuebles publicados'; // esta es la descripcion que va a salir en el feed

if ($formato === 'rss') { // si el formato es rss entonces se genera el rss con la funcion
    generarRSS($anuncios, $sitio_url, $sitio_titulo, $sitio_descripcion);
} else { // si no entonces se crea con atom
    generarAtom($anuncios, $sitio_url, $sitio_titulo, $sitio_descripcion);
}

///////////////////////////////////////////////////// LO DE GENEARAR RSS ///////////////////////////////////////////////////////////////////////
function generarRSS($anuncios, $sitio_url, $sitio_titulo, $sitio_descripcion) { // funcion para generar el rss que se le pasan los anuncios que se han sacado de la base de datos, la url, el titulo y la descripcion del sitio
    $doc = new DOMDocument('1.0', 'UTF-8'); // primer se crea el documento dom
    $doc->formatOutput = true; // y se le pone lo del formato para que se vea bien 
    
    $rss = $doc->createElement('rss'); // se crea el elemento rss que es el raiz como en el html
    $rss->setAttribute('version', '2.0'); // y se le pone el atributo de version
    $doc->appendChild($rss); // y se le hace el appenchild para meterlo en el documento
    
    $channel = $doc->createElement('channel'); // ahora va la etiqueta del canal
    $rss->appendChild($channel); // y se mete igual 
    
    $title = $doc->createElement('title'); // la etiqueta del titulo
    $title->appendChild($doc->createTextNode($sitio_titulo)); // y con el createtextnode se le pone el texto que es el titulo del sitio
    $channel->appendChild($title);
    
    $link = $doc->createElement('link'); // lo mismo con el link
    $link->appendChild($doc->createTextNode($sitio_url));
    $channel->appendChild($link);
    
    $description = $doc->createElement('description'); // la descripcion del canal
    $description->appendChild($doc->createTextNode($sitio_descripcion));
    $channel->appendChild($description);
    
    $language = $doc->createElement('language', 'es-ES'); // el idioma del canal
    $channel->appendChild($language);
    
    $lastBuildDate = $doc->createElement('lastBuildDate'); // la fecha de ultima actualizacion del canal para ver cuando se actualizo por ultima vez para que el usuario se de cuenta si esta desactualizado o no
    $lastBuildDate->appendChild($doc->createTextNode(date('r')));
    $channel->appendChild($lastBuildDate);
    
    foreach ($anuncios as $anuncio) { // ahora me creo un item por cada anuncio
        $item = $doc->createElement('item'); 
        
        $itemTitle = $doc->createElement('title'); // se le pone el titulo al item (que le he puesto el nombre del anuncio y el precio para que se vea mejor y no tener tantos datos en la descripcion)
        $tituloTexto = $anuncio['Titulo'] . ' - ' . number_format($anuncio['Precio'], 0, ',', '.') . '€';
        $itemTitle->appendChild($doc->createTextNode($tituloTexto));
        $item->appendChild($itemTitle);
        
        $itemLink = $doc->createElement('link'); // se le mete el link del anuncio para poder acceder al mismo anuncio desde la feed IMPORTANTE PONERLO CON ANUNCIO.PHP PORQUE SI NO SE LIA UN POQUITO
        $urlAnuncio = $sitio_url . '/anuncio.php?idAnuncio=' . $anuncio['IdAnuncio'];
        $itemLink->appendChild($doc->createTextNode($urlAnuncio));
        $item->appendChild($itemLink);
        
        $itemDescription = $doc->createElement('description'); // para la descripcion le pongo el tipo de anuncio, tipo de vivienda, ciudad, pais y un trozo del texto del anuncio
        $descripcionTexto = $anuncio['NomTAnuncio'] . ' de ' . $anuncio['NomTVivienda'] . 
                           ' en ' . $anuncio['Ciudad'] . ', ' . $anuncio['NomPais'] . '. ' . 
                           substr($anuncio['Texto'], 0, 200) . '...';
        $itemDescription->appendChild($doc->createTextNode($descripcionTexto));
        $item->appendChild($itemDescription);
        
        $itemPubDate = $doc->createElement('pubDate'); // la fecha de publicacion del anuncio tamben
        $fecha = strtotime($anuncio['FRegistro']);
        $itemPubDate->appendChild($doc->createTextNode(date('r', $fecha)));
        $item->appendChild($itemPubDate);
        
        $itemGuid = $doc->createElement('guid'); // el guid es como el identificador unico del anuncio, le pongo la url del anuncio mas la fecha de registro para que no haya dos iguales
        $guidUnico = $urlAnuncio . '#' . $anuncio['FRegistro'];
        $itemGuid->appendChild($doc->createTextNode($guidUnico));
        $itemGuid->setAttribute('isPermaLink', 'false');
        $item->appendChild($itemGuid);
        
        $channel->appendChild($item); // por ultimo se le mete el item al channel
    }
    
    header('Content-Type: application/rss+xml; charset=UTF-8'); // se envian las cabeceras y se muestra el xml
    echo $doc->saveXML(); // se guarda y se muestra el xml
}

///////////////////////////////////////////////////// LO DE GENEARAR ATOM ///////////////////////////////////////////////////////////////////////
function generarAtom($anuncios, $sitio_url, $sitio_titulo, $sitio_descripcion) { // lo mismo que el rss pero las etiquetas son diferentes
    $doc = new DOMDocument('1.0', 'UTF-8'); // se crea el doc dom
    $doc->formatOutput = true;
    
    $feed = $doc->createElement('feed'); // se crea el elemento feed que es el raiz
    $feed->setAttribute('xmlns', 'http://www.w3.org/2005/Atom'); // y se le pone el atributo del namespace
    $doc->appendChild($feed);
    
    $title = $doc->createElement('title'); // se crea el elemento title para el titulo del feed
    $title->appendChild($doc->createTextNode($sitio_titulo));
    $feed->appendChild($title);
    
    $subtitle = $doc->createElement('subtitle'); // el siubtitle es lo mismo que la descripcion en rss
    $subtitle->appendChild($doc->createTextNode($sitio_descripcion));
    $feed->appendChild($subtitle);
    
    $linkSelf = $doc->createElement('link'); // link a si mismo
    $linkSelf->setAttribute('href', $sitio_url . '/feed.php?formato=atom');
    $linkSelf->setAttribute('rel', 'self');
    $feed->appendChild($linkSelf);
    
    $linkAlternate = $doc->createElement('link'); // link alternativo al sitio
    $linkAlternate->setAttribute('href', $sitio_url);
    $linkAlternate->setAttribute('rel', 'alternate');
    $feed->appendChild($linkAlternate);
    
    $id = $doc->createElement('id'); // ahora el id del feed, que es como la url del feed
    $id->appendChild($doc->createTextNode($sitio_url . '/feed.php?formato=atom'));
    $feed->appendChild($id);
    
    $updated = $doc->createElement('updated'); // la fecha de actualizacion del feed
    $updated->appendChild($doc->createTextNode(date('c')));
    $feed->appendChild($updated);
    
    foreach ($anuncios as $anuncio) { // ahora se crean las entry por cada anuncio como los item en rss
        $entry = $doc->createElement('entry');
        
        $entryTitle = $doc->createElement('title'); // el titulo igual que en rss
        $tituloTexto = $anuncio['Titulo'] . ' - ' . number_format($anuncio['Precio'], 0, ',', '.') . '€';
        $entryTitle->appendChild($doc->createTextNode($tituloTexto));
        $entry->appendChild($entryTitle);
        
        $entryLink = $doc->createElement('link'); // link al anuncio
        $urlAnuncio = $sitio_url . '/anuncio.php?idAnuncio=' . $anuncio['IdAnuncio'];
        $entryLink->setAttribute('href', $urlAnuncio);
        $entryLink->setAttribute('rel', 'alternate');
        $entry->appendChild($entryLink);
        
        $entryId = $doc->createElement('id'); // igual que el guid en rss
        $idUnico = $urlAnuncio . '#' . $anuncio['FRegistro'];
        $entryId->appendChild($doc->createTextNode($idUnico));
        $entry->appendChild($entryId);
        
        $entryUpdated = $doc->createElement('updated'); // la fecha de actualizacion del anuncio
        $fecha = strtotime($anuncio['FRegistro']);
        $entryUpdated->appendChild($doc->createTextNode(date('c', $fecha)));
        $entry->appendChild($entryUpdated);
        
        $entrySummary = $doc->createElement('summary'); // resumen del anuncio como la descripcion en rss
        $resumenTexto = $anuncio['NomTAnuncio'] . ' de ' . $anuncio['NomTVivienda'] . 
                       ' en ' . $anuncio['Ciudad'] . ', ' . $anuncio['NomPais'] . '. ' . 
                       substr($anuncio['Texto'], 0, 200) . '...';
        $entrySummary->appendChild($doc->createTextNode($resumenTexto));
        $entry->appendChild($entrySummary);
        
        $feed->appendChild($entry); // se anyade la entry al feed
    }
    
    header('Content-Type: application/atom+xml; charset=UTF-8'); // se evian las cabeceras y se muestra el xml
    echo $doc->saveXML(); // se guarda y se muestra el xml
}

$db->close(); // se cierra la conexion a la base de datos
?>
