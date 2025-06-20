<?php
require_once '../session_manager.php';
require_once 'check_admin.php';
require_once '../conexion.php';

// Cargar datos iniciales (sin cambios en la lógica PHP)
$canales_stmt = $pdo->query("SELECT c.*, cat.nombre as categoria_nombre FROM canales c LEFT JOIN categorias cat ON c.id_categoria = cat.id ORDER BY c.id DESC");
$canales = $canales_stmt->fetchAll();
$categorias_stmt = $pdo->query("SELECT id, nombre FROM categorias WHERE tipo = 'canal' ORDER BY nombre");
$categorias = $categorias_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - La Cancha TV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Enlazamos nuestro nuevo CSS -->
    <style>
        /* admin/admin_style.css */

/* --- Variables de Color (Inspiradas en tu sitio principal) --- */
:root {
    --theme-primary: #0d6efd;
    --theme-secondary: #6c757d;
    --theme-light-bg: #f8f9fa; /* Un poco más claro para el admin */
    --theme-card-bg: #ffffff;
    --theme-text: #212529;
    --theme-border-color: #dee2e6;
    --theme-header-bg: #343a40; /* Un encabezado oscuro para el panel */
}

/* --- Estilo General del Panel --- */
body {
    background-color: var(--theme-light-bg);
    font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--theme-text);
}

.container {
    max-width: 1200px;
}

.panel-header {
    background-color: var(--theme-card-bg);
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    border-left: 5px solid var(--theme-primary);
}

.table {
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.table thead {
    background-color: #f1f5f9; /* Un gris muy claro para la cabecera */
    color: #475569;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--theme-border-color);
}

.table td {
    vertical-align: middle;
}

/* --- Estilos para el Modal (Formulario) --- */
.modal-content {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

.modal-header {
    background-color: var(--theme-light-bg);
    border-bottom: 1px solid var(--theme-border-color);
    padding: 1rem 1.5rem;
}

.modal-title {
    color: var(--theme-primary);
    font-weight: 600;
}

.modal-body {
    padding: 2rem;
}

/* Estilo "Tailwind" para los campos del formulario */
.form-group-custom {
    margin-bottom: 1.5rem;
}

.form-group-custom label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.form-group-custom .form-control,
.form-group-custom .form-select {
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.75rem 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group-custom .form-control:focus,
.form-group-custom .form-select:focus {
    border-color: var(--theme-primary);
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    outline: none;
}

.form-check-label {
    font-weight: 500;
}

.modal-footer {
    background-color: var(--theme-light-bg);
    border-top: 1px solid var(--theme-border-color);
    padding: 1rem 1.5rem;
}
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Encabezado del Panel -->
        <div class="panel-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Administración de Canales</h1>
                    <p class="text-muted mb-0">Crea, edita y gestiona todos los canales de la plataforma.</p>
                </div>
                <div>
                    <button id="btn-nuevo-canal" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Añadir Nuevo Canal</button>
                    <a href="../index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Volver al Sitio</a>
                </div>
            </div>
        </div>

        <!-- Tabla de Canales (con un thead más estilizado) -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Logo</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Premium</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="canales-tabla-body">
                <?php foreach ($canales as $canal): ?>
                <tr data-id="<?php echo $canal['id']; ?>">
                    <td class="fw-bold"><?php echo $canal['id']; ?></td>
                    <td><img src="../<?php echo htmlspecialchars($canal['url_logo']); ?>" alt="logo" width="100" class="rounded"></td>
                    <td><?php echo htmlspecialchars($canal['nombre']); ?></td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary-emphasis fw-normal"><?php echo htmlspecialchars($canal['categoria_nombre'] ?? 'N/A'); ?></span></td>
                    <td><?php echo $canal['es_premium'] ? '<span class="badge rounded-pill bg-warning text-dark">Sí</span>' : '<span class="badge rounded-pill bg-success bg-opacity-10 text-success-emphasis">No</span>'; ?></td>
                    <td><?php echo $canal['activo'] ? '<span class="badge rounded-pill bg-success text-white">Sí</span>' : '<span class="badge rounded-pill bg-danger bg-opacity-10 text-danger-emphasis">No</span>'; ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning btn-editar" data-id="<?php echo $canal['id']; ?>"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="<?php echo $canal['id']; ?>"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal con el formulario estilizado -->
    <div class="modal fade" id="canal-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-titulo"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="canal-form">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="canal-id">
                        <input type="hidden" name="accion" id="canal-accion">
                        
                        <div class="form-group-custom">
                            <label for="canal-nombre">Nombre del Canal</label>
                            <input type="text" class="form-control" id="canal-nombre" name="nombre" placeholder="Ej: La Cancha Deportes HD" required>
                        </div>
                        <div class="form-group-custom">
                            <label for="canal-descripcion">Descripción</label>
                            <textarea class="form-control" id="canal-descripcion" name="descripcion" rows="3" placeholder="Una breve descripción del contenido del canal..."></textarea>
                        </div>
                        <div class="form-group-custom">
                            <label for="canal-stream">Código Iframe del Stream</label>
                            <textarea class="form-control" id="canal-stream" name="url_stream" rows="4" placeholder="Pega el código iframe completo aquí..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="canal-logo">Ruta del Logo</label>
                                    <input type="text" class="form-control" id="canal-logo" name="url_logo" placeholder="Ej: img/canales/nombre.jpg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="canal-categoria">Categoría</label>
                                    <select class="form-select" id="canal-categoria" name="id_categoria">
                                        <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="canal-premium" name="es_premium" value="1">
                                    <label class="form-check-label" for="canal-premium">Contenido Premium</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="canal-activo" name="activo" value="1" checked>
                                    <label class="form-check-label" for="canal-activo">Canal Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btn-guardar"><i class="fas fa-save me-2"></i>Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
</body>
</html>