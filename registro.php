<?php
require_once 'session_manager.php';
// Si el usuario ya está logueado, lo sacamos de aquí.
if ($is_logged_in) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - La Cancha TV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: url('img/fondo-login.jpg') no-repeat center center;
            background-size: cover;
        }
        .auth-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 1rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="card auth-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <img src="img/logo.png" alt="Logo" width="80">
                <h2 class="h4 fw-bold mt-3">Crea tu Cuenta</h2>
            </div>
            
            <div id="error-message" class="alert alert-danger d-none"></div>

            <form id="registro-form">
                <input type="hidden" name="accion" value="registro">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Tu Nombre de Usuario" required>
                    <label for="nombre_usuario">Nombre de Usuario</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com" required>
                    <label for="email">Correo Electrónico</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password">Contraseña</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirmar Contraseña" required>
                    <label for="password_confirm">Confirmar Contraseña</label>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Crear Cuenta</button>
                </div>
                <p class="text-center small">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>.</p>
            </form>
        </div>
    </div>
    <script src="auth.js"></script>
</body>
</html>