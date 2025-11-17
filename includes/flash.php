<?php
if (!defined('FLASH_INCLUDED')) define('FLASH_INCLUDED', true);

function flash_set(string $type, string $message): void {
    if (session_status() !== PHP_SESSION_ACTIVE) return;
    if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
        $_SESSION['_flash'] = [];
    }
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function flash_has(): bool {
    return session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['_flash']);
}

function flash_get_all(): array {
    if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['_flash'])) return [];
    $all = $_SESSION['_flash'];
    unset($_SESSION['_flash']); // se consumen al leer
    return $all;
}

/**
 * Muestra los mensajes flash con un markup simple.
 * Adapta las clases CSS a tu propio estilo.
 */
function flash_display(): void {
    $messages = flash_get_all();
    if (empty($messages)) return;
    foreach ($messages as $m) {
        $type = isset($m['type']) ? htmlspecialchars($m['type']) : 'info';
        $txt  = isset($m['message']) ? htmlspecialchars($m['message']) : '';
        echo "<div class=\"flash flash-{$type}\">{$txt}</div>";
    }
}

// --- flash data helpers (guardar/leer datos complejos una sola vez) ---
function flash_set_data(string $key, $value): void {
    if (session_status() !== PHP_SESSION_ACTIVE) return;
    if (!isset($_SESSION['_flash_data']) || !is_array($_SESSION['_flash_data'])) {
        $_SESSION['_flash_data'] = [];
    }
    // almacenar el valor tal cual (puede ser array), no lo escapamos aquí
    $_SESSION['_flash_data'][$key] = $value;
}

function flash_has_data(string $key): bool {
    return session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['_flash_data']) && array_key_exists($key, $_SESSION['_flash_data']);
}

/**
 * Recupera y consume el valor guardado en flash data.
 * Devuelve null si no existe.
 */
function flash_get_data(string $key) {
    if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['_flash_data'])) return null;
    if (!array_key_exists($key, $_SESSION['_flash_data'])) return null;
    $val = $_SESSION['_flash_data'][$key];
    unset($_SESSION['_flash_data'][$key]);
    // si no quedan datos, eliminar la estructura
    if (empty($_SESSION['_flash_data'])) unset($_SESSION['_flash_data']);
    return $val;
}

function flash_get_all_data(): array {
    if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['_flash_data'])) return [];
    $all = $_SESSION['_flash_data'];
    unset($_SESSION['_flash_data']);
    return $all;
}

?>
