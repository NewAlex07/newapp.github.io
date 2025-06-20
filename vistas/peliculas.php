<?php
// Inicia la sesión

require_once '../session_manager.php'; // Usamos el gestor central
require_once '../conexion.php';

// --- CONSULTA PARA OBTENER LAS PELÍCULAS ACTIVAS ---
$sql_peliculas = "SELECT peliculas.*, categorias.nombre as categoria_nombre 
                  FROM peliculas 
                  LEFT JOIN categorias ON peliculas.id_categoria = categorias.id 
                  WHERE peliculas.activo = 1 
                  ORDER BY peliculas.titulo ASC";
$stmt_peliculas = $pdo->query($sql_peliculas);
$peliculas = $stmt_peliculas->fetchAll();

// --- CONSULTA PARA OBTENER LAS CATEGORÍAS DE TIPO 'pelicula' ---
$sql_categorias = "SELECT * FROM categorias WHERE tipo = 'pelicula' ORDER BY nombre ASC";
$stmt_categorias = $pdo->query($sql_categorias);
$categorias = $stmt_categorias->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Películas - La Cancha TV</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
    <link rel="stylesheet" href="../style.css" />
    <style>
        .sidebar .link-item.active {
            background-color: var(--primary-color-light);
            font-weight: 500;
            color: var(--primary-color);
        }
    </style>
</head>
<body class="sidebar-hidden">
    <div class="container">
        <!-- Header / Navbar (sin cambios) -->
        <header>
            <nav class="navbar">
                <div class="nav-section nav-left">
                    <button class="nav-button menu-button"><i class="uil uil-bars"></i></button>
                    <a href="../index.php" class="nav-logo">
                        <img src="../img/logo.png" alt="Logo La Cancha TV" class="logo-image" />
                        <h2 class="logo-text">LaCanchaTV</h2>
                    </a>
                </div>
               <div class="nav-section nav-center">
                    <form action="#" class="search-form">
                        <input type="search" placeholder="Buscar Pelicula..." class="search-input" required />
                        <button class="nav-button search-button"><i class="uil uil-search"></i></button>
                    </form>
                    <button class="nav-button mic-button"><i class="uil uil-microphone"></i></button>
                </div>

                <div class="nav-section nav-right">
                    <?php if ($is_logged_in): ?>
                        <button class="nav-button theme-button"><i class="uil uil-moon"></i></button>
                        <img src="../img/user.png" alt="User Image" class="user-image" />
                    <?php else: ?>
                        <a href="../login.php" class="btn-nav-login">Iniciar Sesión</a>
                        <a href="../registro.php" class="btn-nav-registro">Registrarse</a>
                    <?php endif; ?>
                </div>
            </nav>
        </header>

        <!-- Main Layout -->
        <main class="main-layout">
            <div class="screen-overlay"></div>
          <!-- Sidebar ADAPTADA -->
            <aside class="sidebar">
                <div class="nav-section nav-left">
                    <button class="nav-button menu-button"><i class="uil uil-bars"></i></button>
                    <a href="../index.php" class="nav-logo">
                        <img src="../img/logo.png" alt="Logo" class="logo-image" />
                        <h2 class="logo-text">LaCanchaTV</h2>
                    </a>
                </div>

                <div class="links-container">
                    <div class="link-section">
                        <a href="../index.php" class="link-item"><i class="uil uil-estate"></i> Inicio</a>
                        <a href="canales.php" class="link-item "><i class="uil uil-tv-retro"></i> Canales</a>
                        <a href="peliculas.php" class="link-item active"><i class="uil uil-film"></i> Películas</a>
                    </div>
                    <div class="section-separator"></div>

                    <div class="link-section">
                        <h4 class="section-title">Tu Cuenta</h4>
                        <a href="#" class="link-item"><i class="uil uil-user-square"></i> Mi Perfil</a>
                        <a href="#" class="link-item"><i class="uil uil-history"></i> Historial</a>
                        <a href="#" class="link-item"><i class="uil uil-star"></i> Mi Membresía</a>
                    </div>
                    <!-- Puedes agregar más secciones si lo necesitas -->
                </div>
            </aside>
            <div class="content-wrapper">
                <!-- Lista de Categorías de Películas -->
                <div class="category-list">
                    <button class="category-button active" data-category-id="all">Todas</button>
                    <?php foreach($categorias as $categoria): ?>
                        <button class="category-button" data-category-id="<?php echo $categoria['id']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Lista de Películas -->
                <div class="video-list">
                    <?php foreach ($peliculas as $pelicula): ?>
                        <!-- ¡ENLACE CORREGIDO! Apunta a ver_pelicula.php -->
                        <a href="ver_pelicula.php?id=<?php echo $pelicula['id']; ?>" class="video-card" data-category-id="<?php echo $pelicula['id_categoria']; ?>">
                            <div class="thumbnail-container">
                                <img src="../<?php echo htmlspecialchars($pelicula['url_poster']); ?>" alt="Póster" class="thumbnail" />
                                <p class="duration"><?php echo htmlspecialchars($pelicula['duracion_minutos']); ?> min</p>
                                
                            </div>
                            <div class="video-info">
                                <div class="video-details">
                                    <?php if ($pelicula['es_premium']): ?>
                                    <p class="premium-badge-on-thumb">⭐</p>
                                <?php endif; ?>
                                    <h2 class="title"><?php echo htmlspecialchars($pelicula['titulo']); ?></h2>
                                    <p class="channel-name"><?php echo htmlspecialchars($pelicula['categoria_nombre'] ?? 'Sin categoría'); ?></p>
                                    <p class="views"><?php echo htmlspecialchars($pelicula['ano_lanzamiento']); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="../script.js"></script>
    <!-- Tu script de filtrado -->
</body>
</html>