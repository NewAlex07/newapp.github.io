<?php
// Herramienta de prueba de sesión y redirección para La Cancha TV

// Iniciamos la sesión para poder manipularla
session_start();

// 1. Limpiamos cualquier sesión anterior para empezar de cero
session_unset();
session_destroy();
session_start();

// 2. Leemos los parámetros de la URL
// ----------------------------------------------------
// ?tipo=... (puede ser 'visitante', 'gratis' o 'premium')
// ?id=... (el ID del canal que queremos ver)

$tipo_usuario = $_GET['tipo'] ?? 'visitante';
$id_canal_a_ver = $_GET['id'] ?? null;

// 3. Configuramos la sesión según el tipo de usuario
// ----------------------------------------------------
switch ($tipo_usuario) {
    case 'premium':
        // Simulamos un usuario PREMIUM que ha iniciado sesión
        $_SESSION['usuario_id'] = 99; // ID de usuario de prueba
        $_SESSION['nombre_usuario'] = 'Usuario Premium de Prueba';
        $_SESSION['es_miembro_activo'] = 1; // ¡La clave! Es miembro.
        echo "<h1>Sesión establecida como: USUARIO PREMIUM</h1>";
        break;

    case 'gratis':
        // Simulamos un usuario GRATIS que ha iniciado sesión
        $_SESSION['usuario_id'] = 50; // ID de usuario de prueba
        $_SESSION['nombre_usuario'] = 'Usuario Gratis de Prueba';
        $_SESSION['es_miembro_activo'] = 0; // No es miembro.
        echo "<h1>Sesión establecida como: USUARIO REGISTRADO (GRATIS)</h1>";
        break;

    case 'visitante':
    default:
        // No establecemos ninguna variable de sesión, simulando un visitante.
        echo "<h1>Sesión establecida como: VISITANTE (SIN LOGIN)</h1>";
        break;
}

// 4. Redirigimos al canal especificado después de 2 segundos
// ----------------------------------------------------
if ($id_canal_a_ver !== null && is_numeric($id_canal_a_ver)) {
    $url_destino = "vistas/ver_canal.php?id=" . $id_canal_a_ver;
    echo "<p>Redirigiendo a <strong>" . htmlspecialchars($url_destino) . "</strong> en 2 segundos...</p>";
    // La meta refresh nos permite ver el mensaje antes de redirigir
    header("refresh:2;url=" . $url_destino);
    exit();
} else {
    echo "<p><strong>Error:</strong> Debes especificar un ID de canal válido en la URL. Ejemplo: <code>?tipo=premium&id=3</code></p>";
}