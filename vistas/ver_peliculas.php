<?php
// Usamos el gestor de sesión centralizado
require_once '../session_manager.php';
require_once '../conexion.php';

// 1. OBTENER Y VALIDAR EL ID DE LA PELÍCULA
// Este es el paso que causaba el bucle antes. Ahora está en el archivo correcto.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Si no hay ID o no es un número, lo mandamos a la galería de películas.
    header('Location: peliculas.php');
    exit();
}
$id_pelicula = intval($_GET['id']);

// 2. BUSCAR LA PELÍCULA EN LA BASE DE DATOS USANDO UNA CONSULTA PREPARADA
$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM peliculas p 
        LEFT JOIN categorias c ON p.id_categoria = c.id
        WHERE p.id = ? AND p.activo = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_pelicula]);
$pelicula = $stmt->fetch();

// 3. VERIFICAR SI LA PELÍCULA EXISTE
if (!$pelicula) {
    // Si no se encontró el ID en la base de datos, mostramos un error 404.
    http_response_code(404);
    // Podrías crear una página de error más bonita si quieres.
    echo "<h1>Error 404: Película no encontrada</h1><p>La película que buscas no existe o no está disponible.</p><a href='peliculas.php'>Volver al catálogo</a>";
    exit();
}

// 4. LÓGICA DE MEMBRESÍA
// Verificamos si la película es premium y si el usuario tiene permiso.
if ($pelicula['es_premium']) {
    // Usamos las variables de nuestro session_manager.php para un código más limpio.
    if (!$is_logged_in || !$is_premium_member) {
        // Si no está logueado o no es premium, mostramos la página de bloqueo.
        http_response_code(403); // 403 Forbidden
        // Incluimos un poco de estilo para que no se vea tan simple.
        echo '
        <head>
            <title>Acceso Denegado</title>
            <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
            <style>body{font-family: sans-serif; text-align: center; padding-top: 50px; background: #181818; color: #fff;} h1{font-size: 2rem;} a{color: #0d6efd;}</style>
        </head>
        <body>
            <h1><i class="uil uil-lock"></i> Contenido Premium</h1>
            <p>Esta película es exclusiva para miembros de La Cancha TV.</p>
            <p><a href="../membresia.php">¡Hazte miembro ahora!</a> o <a href="../login.php">Inicia sesión</a> para ver este contenido.</p>
        </body>';
        exit();
    }
}

// 5. CONSTRUIR LA URL DE INSERCIÓN DE GOOGLE DRIVE
// Si el script llega hasta aquí, el usuario tiene acceso.
$embed_url = "https://drive.google.com/file/d/" . htmlspecialchars($pelicula['google_drive_id']) . "/preview";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viendo: <?php echo htmlspecialchars($pelicula['titulo']); ?> - La Cancha TV</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background-color: #111; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .player-wrapper {
            width: 100%;
            background-color: #000;
        }
        .player-container {
            position: relative;
            width: 100%;
            max-width: 1280px; /* Ancho máximo para el reproductor */
            margin: 0 auto;
            padding-top: 56.25%; /* Proporción 16:9 para video responsivo */
        }
        .player-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        .info-container {
            padding: 2rem 1rem;
            max-width: 900px;
            margin: auto;
        }
        .info-container h1 { margin-top: 0; font-weight: 700; }
        .details { color: #aaa; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .details span { margin-right: 20px; }
        .sinopsis { line-height: 1.6; }
        .back-link { color: #fff; text-decoration: none; display: inline-block; margin-top: 2rem; }
        .back-link:hover { color: #ccc; }
    </style>
</head>
<body>

    <div class="player-wrapper">
        <div class="player-container">
            <!-- El iframe se construye dinámicamente con la URL de Google Drive -->
            <iframe src="<?php echo $embed_url; ?>" allow="autoplay" allowfullscreen></iframe>
        </div>
    </div>

    <div class="info-container">
        <h1><?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
        <div class="details">
            <span><strong>Año:</strong> <?php echo htmlspecialchars($pelicula['ano_lanzamiento']); ?></span>
            <span><strong>Duración:</strong> <?php echo htmlspecialchars($pelicula['duracion_minutos']); ?> min</span>
            <span><strong>Categoría:</strong> <?php echo htmlspecialchars($pelicula['categoria_nombre'] ?? 'N/A'); ?></span>
        </div>
        <p class="sinopsis"><?php echo nl2br(htmlspecialchars($pelicula['sinopsis'])); ?></p>
        <hr class="my-4">
        <a href="peliculas.php" class="back-link">← Volver al catálogo de películas</a>
    </div>

</body>
</html>