<?php
// Iniciar sesión para verificar si el usuario es miembro
require_once './session_manager.php';

require_once '../conexion.php';

// 1. OBTENER Y VALIDAR EL ID DEL CANAL
// -----------------------------------------------------------------
// Si no hay 'id' en la URL o si no es un número, redirigimos por seguridad.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: canales.php');
    exit();
}
$id_canal = intval($_GET['id']);

// 2. BUSCAR EL CANAL EN LA BASE DE DATOS
// -----------------------------------------------------------------
// Usamos una consulta preparada para evitar inyección SQL. ¡Es más seguro!
$sql = "SELECT * FROM canales WHERE id = ? AND activo = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_canal]);
$canal = $stmt->fetch();

// 3. VERIFICAR SI EL CANAL EXISTE
// -----------------------------------------------------------------
if (!$canal) {
    // Si no se encontró el canal, mostramos un error 404.
    http_response_code(404);
    echo "<h1>Error 404: Canal no encontrado</h1>";
    echo "<p>El canal que buscas no existe o no está disponible.</p>";
    echo "<a href='canales.php'>Volver a la lista de canales</a>";
    exit();
}

// 4. LÓGICA DE MEMBRESÍA (¡LA PARTE MÁS IMPORTANTE!)
// -----------------------------------------------------------------
if ($canal['es_premium']) {
    $acceso_permitido = false;
    
    // Verificamos si el usuario ha iniciado sesión Y si su membresía está activa.
    // Supondremos que guardas 'es_miembro_activo' en la sesión al hacer login.
    if (isset($_SESSION['usuario_id']) && isset($_SESSION['es_miembro_activo']) && $_SESSION['es_miembro_activo'] == 1) {
        $acceso_permitido = true;
    }

    if (!$acceso_permitido) {
        // Si no tiene acceso, mostramos una página de bloqueo.
        // Aquí podrías poner un diseño más bonito.
        http_response_code(403); // Código de 'Acceso Prohibido'
        echo "<h1><i class='uil uil-lock'></i> Contenido Premium</h1>";
        echo "<p>Este canal es exclusivo para miembros de La Cancha TV.</p>";
        echo "<a href='../membresia.php'>¡Hazte miembro ahora!</a> o <a href='../login.php'>Inicia sesión</a>";
        exit();
    }
}

// Si llegamos hasta aquí, el usuario tiene permiso para ver el canal.

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- El título de la página es dinámico -->
    <title>Viendo: <?php echo htmlspecialchars($canal['nombre']); ?> - La Cancha TV</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Estilos específicos para esta página */
        body { background-color: #181818; color: #fff; }
        .player-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* Proporción 16:9 para el video */
            background-color: #000;
        }
        .player-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none; /* Quitamos el borde del iframe */
        }
        .info-container {
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }
    </style>
</head>
<body>

    <div class="player-container">
        <?php
            // 5. MOSTRAR EL IFRAME
            // -----------------------------------------------------------------
            // Imprimimos directamente el código guardado en la base de datos.
            // NO usamos htmlspecialchars aquí porque queremos que el HTML del iframe se renderice.
            // Esto es seguro porque solo tú (el admin) puedes poner iframes en la BD.
            echo $canal['url_stream'];
        ?>
    </div>

    <div class="info-container">
        <h1><?php echo htmlspecialchars($canal['nombre']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($canal['descripcion'])); ?></p>
        <hr>
        <a href="canales.php">← Volver a todos los canales</a>
    </div>

</body>
</html>