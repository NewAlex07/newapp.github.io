<?php
// Incluimos el gestor para asegurarnos de que la sesión está iniciada
require_once 'session_manager.php';

// session_start() ya está en session_manager.php

// 1. Limpiamos todas las variables de sesión
session_unset();

// 2. Destruimos la sesión por completo
session_destroy();

// 3. Redirigimos al usuario a la página de inicio
header('Location: index.php');
exit();
?>