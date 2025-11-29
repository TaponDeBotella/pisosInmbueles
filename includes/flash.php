<?php
// Para evitar declarar todo dos veces
if (defined('FLASH_INCLUDED')) { // si la constante FLASH_INCLUDED ya existe no se hace nada
    return;
}
define('FLASH_INCLUDED', true); // se define la constante FLASH_INCLUDED como true

// --- flash data helpers (guardar/leer datos complejos una sola vez) ---
function flash_set_data(string $key, $value): void {
    if (session_status() !== PHP_SESSION_ACTIVE)  // se comprueba que hay sesion
        return;
    if (!isset($_SESSION['_flash_data']) || !is_array($_SESSION['_flash_data'])) // si no hay _flash_data o este no es un array
        $_SESSION['_flash_data'] = []; // lo inicializo como array vacio
    
    $_SESSION['_flash_data'][$key] = $value; // almaceno el valor en $_SESSION['_flash_data'][$key]
}

function flash_has_data(string $key): bool {
    return session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['_flash_data']) && array_key_exists($key, $_SESSION['_flash_data']); // compruebo que la sesion esta iniciada Y esta tiene _flash_data Y si este flash_data tiene la key que se pasa por parametro. Devuelve true si todo se cumple
}

/**
 * Recupera y consume el valor guardado en flash data.
 * Devuelve null si no existe.
 */
function flash_get_data(string $key, $default = null) {
    // Usamos flash_has_data() para las comprobaciones iniciales; esta función
    // ya verifica el estado de la sesión y la existencia de la clave.
    if (!flash_has_data($key)) {
        return $default;
    }

    $val = $_SESSION['_flash_data'][$key]; // guardo la info de flash_data en $val
    unset($_SESSION['_flash_data'][$key]); // elimino los datos de la sesion

    // si no quedan datos, eliminar la estructura
    if (empty($_SESSION['_flash_data'])) 
        unset($_SESSION['_flash_data']);
    return $val;
}

function flash_get_all_data(): array {
    if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['_flash_data']))  // si no hay sesion o no hay flash_data
        return [];
    $all = $_SESSION['_flash_data']; // guardo toda la informacion en $all
    unset($_SESSION['_flash_data']); // quito flash_data de la sesion
    return $all;
}

?>
