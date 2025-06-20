<?php
require_once 'session_manager.php';
// Si el usuario ya está logueado, lo sacamos de aquí para evitar bucles.
if ($is_logged_in) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - La Cancha TV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Reutilizamos tu style.css principal -->
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: url('../img/fondo-login.jpg') no-repeat center center;
            background-size: cover;
            
        }
        .auth-card {
            background-color: rgb(255, 255, 255);
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
                <h2 class="h4 fw-bold mt-3">Bienvenido de Vuelta</h2>
            </div>
            
            <div id="error-message" class="alert alert-danger d-none"></div>

            <form id="login-form">
                <input type="hidden" name="accion" value="login">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com" required>
                    <label for="email">Correo Electrónico</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password">Contraseña</label>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                </div>
                <p class="text-center small">¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
            </form>
        </div>
    </div>
    <script src="auth.js"></script>
</body>
</html>