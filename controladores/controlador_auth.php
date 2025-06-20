<?php
// Este controlador no inicia la sesión por sí mismo, espera que el gestor lo haga.
require_once '../conexion.php';
require_once '../session_manager.php'; // Incluimos para manipular la sesión

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'Acción no válida.'];

// La acción viene del formulario (login o registro)
$accion = $_POST['accion'] ?? '';

try {
    switch ($accion) {
        // --- CASO DE LOGIN ---
        case 'login':
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $response['message'] = "Por favor, completa todos los campos.";
            } else {
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $usuario = $stmt->fetch();

                if ($usuario && password_verify($password, $usuario['password_hash'])) {
                    // Login exitoso, poblamos la sesión
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                    $_SESSION['rol'] = $usuario['rol'];
                    $_SESSION['es_miembro_activo'] = $usuario['es_miembro_activo'];
                    
                    // Definimos a dónde redirigir después del login
                    $redirect_url = ($usuario['rol'] === 'admin') ? 'index.php' : 'index.php';
                    $response = ['status' => 'success', 'message' => 'Login exitoso. Redirigiendo...', 'redirect' => $redirect_url];
                } else {
                    $response['message'] = "Email o contraseña incorrectos.";
                }
            }
            break;

        // --- CASO DE REGISTRO ---
        case 'registro':
            $nombre_usuario = $_POST['nombre_usuario'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            if (empty($nombre_usuario) || empty($email) || empty($password)) {
                $response['message'] = "Todos los campos son obligatorios.";
            } elseif ($password !== $password_confirm) {
                $response['message'] = "Las contraseñas no coinciden.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['message'] = "El formato del email no es válido.";
            } else {
                // Verificar si el email o usuario ya existen
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? OR nombre_usuario = ?");
                $stmt->execute([$email, $nombre_usuario]);
                if ($stmt->fetch()) {
                    $response['message'] = "El email o nombre de usuario ya está en uso.";
                } else {
                    // Todo correcto, creamos el usuario
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO usuarios (nombre_usuario, email, password_hash) VALUES (?, ?, ?)";
                    $stmt_insert = $pdo->prepare($sql);
                    $stmt_insert->execute([$nombre_usuario, $email, $password_hash]);
                    
                    $response = ['status' => 'success', 'message' => '¡Registro exitoso! Ahora puedes iniciar sesión.', 'redirect' => 'login.php'];
                }
            }
            break;
    }
} catch (PDOException $e) {
    $response['message'] = 'Error de base de datos: ' . $e->getMessage();
}

echo json_encode($response);
?>