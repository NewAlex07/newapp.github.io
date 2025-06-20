<?php
// Inicia la sesión para poder usar variables como $_SESSION['usuario_id'] más adelante
require_once '../session_manager.php';

// Incluimos la conexión a la base de datos. La ruta es '../' porque estamos un nivel adentro.
require_once '../conexion.php';

// --- CONSULTA PARA OBTENER LOS CANALES ACTIVOS ---
// Usamos un JOIN para traer también el nombre de la categoría, lo que es más eficiente.
$sql_canales = "SELECT canales.*, categorias.nombre as categoria_nombre 
                FROM canales 
                LEFT JOIN categorias ON canales.id_categoria = categorias.id 
                WHERE canales.activo = 1 
                ORDER BY canales.nombre ASC";
$stmt_canales = $pdo->query($sql_canales);
$canales = $stmt_canales->fetchAll();

// --- CONSULTA PARA OBTENER LAS CATEGORÍAS DE TIPO 'canal' ---
$sql_categorias = "SELECT * FROM categorias WHERE tipo = 'canal' ORDER BY nombre ASC";
$stmt_categorias = $pdo->query($sql_categorias);
$categorias = $stmt_categorias->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Canales en Vivo - La Cancha TV</title>
    <!-- Linking Unicons For Icons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
    <!-- La ruta al CSS es ../style.css porque estamos en la carpeta /vistas -->
    <link rel="stylesheet" href="../style.css" />
    <style>
        .thumbnail {
            background-color: #2c2c2c;
        }

        /* Etiqueta EN VIVO sobre la miniatura */
        .live-badge {
            background-color: #ff0000;
            /* Rojo brillante */
            color: white;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Etiqueta PREMIUM sobre la miniatura */
        .premium-badge-on-thumb {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #ffd700;
            /* Dorado */
            font-size: 1.2rem;
            padding: 2px 8px;
            border-radius: 5px;
            line-height: 1;
            pointer-events: none;
            /* Para que no interfiera con el clic */
        }

        /* Botones de Login/Registro en la barra de navegación */
        .nav-right .btn-nav-login,
        .nav-right .btn-nav-registro {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            transition: background-color 0.2s;
            white-space: nowrap;
        }

        .nav-right .btn-nav-login {
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            margin-right: 8px;
        }

        .nav-right .btn-nav-login:hover {
            background-color: var(--primary-color-light);
        }

        .nav-right .btn-nav-registro {
            background-color: #0d6efd;
            color: #fff;
            border: 1px solid transparent;
        }

        .nav-right .btn-nav-registro:hover {
            background-color: #0b5ed7;
        }

        /* Para que el enlace activo en el sidebar se vea resaltado */
        .sidebar .link-item.active {
            background-color: var(--primary-color-light);
            font-weight: 500;
            color: var(--primary-color);
        }
    </style>
</head>

<body class="sidebar-hidden">
    <div class="container">
        <!-- Header / Navbar -->
        <header>
            <nav class="navbar">
                <div class="nav-section nav-left">
                    <button class="nav-button menu-button"><i class="uil uil-bars"></i></button>
                    <!-- LOGO ADAPTADO -->
                    <a href="../index.php" class="nav-logo">
                        <img src="../img/logo.png" alt="Logo La Cancha TV" class="logo-image" />
                        <h2 class="logo-text">LaCanchaTV</h2>
                    </a>
                </div>

                <div class="nav-section nav-center">
                    <form action="#" class="search-form">
                        <input type="search" placeholder="Buscar canal..." class="search-input" required />
                        <button class="nav-button search-button"><i class="uil uil-search"></i></button>
                    </form>
                    <button class="nav-button mic-button"><i class="uil uil-microphone"></i></button>
                </div>

                <div class="nav-section nav-right">
                    <!-- Lógica de Sesión: Muestra usuario o botones de login/registro -->
                    <button class="nav-button theme-button"><i class="uil uil-moon"></i></button>
                    <?php if (isset($_SESSION['id'])): ?>

                        <img src="../img/user.png" alt="User Image" class="user-image" />
                    <?php else: ?>
                        <a href="../login.php" class="btn-nav-login">Iniciar Sesión</a>

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
                        <a href="canales.php" class="link-item active"><i class="uil uil-tv-retro"></i> Canales</a>
                        <a href="peliculas.php" class="link-item"><i class="uil uil-film"></i> Películas</a>
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
                <!-- Lista de Categorías DINÁMICA -->
                <div class="category-list">
                    <button class="category-button active" data-category-id="all">Todos</button>
                    <?php foreach ($categorias as $categoria): ?>
                        <button class="category-button" data-category-id="<?php echo $categoria['id']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Lista de Canales DINÁMICA -->
                <div class="video-list">
                    <?php if (empty($canales)): ?>
                        <p class="text-center p-5">No hay canales disponibles en este momento.</p>
                    <?php else: ?>
                        <?php foreach ($canales as $canal): ?>
                            <a href="ver_canal.php?id=<?php echo $canal['id']; ?>" class="video-card" data-category-id="<?php echo $canal['id_categoria']; ?>">
                                <div class="thumbnail-container">
                                    <img src="../<?php echo htmlspecialchars($canal['url_logo']); ?>" alt="Logo <?php echo htmlspecialchars($canal['nombre']); ?>" class="thumbnail" />
                                    <p class="duration live-badge">EN VIVO</p>
                                    <?php if ($canal['es_premium']): ?>
                                        <p class="premium-badge-on-thumb">⭐</p>
                                    <?php endif; ?>
                                </div>
                                <div class="video-info">
                                    <div class="video-details">
                                        <h2 class="title"><?php echo htmlspecialchars($canal['nombre']); ?></h2>
                                        <p class="channel-name"><?php echo htmlspecialchars($canal['categoria_nombre'] ?? 'Sin categoría'); ?></p>
                                        <p class="views"></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- El script.js original mantiene la funcionalidad del menú, etc. -->
    <script src="../script.js"></script>

    <!-- Script adicional para el filtrado de categorías -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryButtons = document.querySelectorAll('.category-button');
            const videoCards = document.querySelectorAll('.video-card');

            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    const selectedCategoryId = button.dataset.categoryId;

                    videoCards.forEach(card => {
                        if (selectedCategoryId === 'all' || card.dataset.categoryId === selectedCategoryId) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>