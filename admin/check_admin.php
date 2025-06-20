<?php
// Iniciamos la sesión para poder acceder a las variables de sesión.
require_once '../session_manager.php';



// Verificamos si el usuario ha iniciado sesión Y si su rol es 'admin'.
// Si alguna de estas condiciones no se cumple, lo expulsamos a la página de login.
if (!isset($_SESSION['id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Guardamos un mensaje de error para mostrarlo en la página de login.
    $_SESSION['error_mensaje'] = "Acceso denegado. Debes ser administrador.";
    header('Location: ../login.php'); // Redirige a la página de login en la raíz.
    exit();
}

// Si el script llega hasta aquí, significa que el usuario es un admin verificado.
?>