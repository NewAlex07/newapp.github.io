<?php
// =================================================================
// ARCHIVO DE CONEXIÓN A LA BASE DE DATOS (PDO)
// =================================================================

$host = 'localhost';            // O la IP del servidor de BD, ej: 127.0.0.1
$db_name = 'la_cancha_tv_db';   // El nombre de la base de datos que creaste.
$username = 'root';             // El usuario de la BD (en XAMPP es 'root' por defecto).
$password = '';                 // La contraseña de la BD (en XAMPP es vacía por defecto).
$charset = 'utf8mb4';           // El set de caracteres para soportar acentos, emojis, etc.

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];


try {

    $pdo = new PDO($dsn, $username, $password, $options);
    
} catch (\PDOException $e) {
  
    error_log("Error de conexión a la BD: " . $e->getMessage()); // Guarda el error real para ti
    die("¡Ups! Parece que hay un problema técnico. Por favor, inténtalo más tarde."); // Mensaje para el usuario
}


?>