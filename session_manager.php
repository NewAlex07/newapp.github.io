<?php
// --- GESTOR DE SESIONES CENTRALIZADO ---

// Iniciar la sesión si no está ya iniciada.
// session_status() es más seguro que !isset($_SESSION)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variables booleanas para facilitar las comprobaciones en las vistas.
// Estas variables estarán disponibles en cualquier archivo que incluya este gestor.
$is_logged_in = isset($_SESSION['id']);
$is_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$is_premium_member = (isset($_SESSION['es_miembro_activo']) && $_SESSION['es_miembro_activo'] == 1);

// Variables con los datos del usuario (o valores por defecto si no está logueado).
$user_id = $_SESSION['id'] ?? 0;
$user_name = $_SESSION['nombre_usuario'] ?? 'Invitado';
?>