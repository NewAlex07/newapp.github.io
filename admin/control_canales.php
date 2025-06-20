<?php
require_once '../session_manager.php';
require_once 'check_admin.php'; // Seguridad primero.
require_once '../conexion.php'; // Conexión a la BD.

// Indicamos que la respuesta será en formato JSON.
header('Content-Type: application/json');

// Recibimos la acción a realizar. Usamos POST para la mayoría de acciones.
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
$response = ['status' => 'error', 'message' => 'Acción no válida.'];

try {
    switch ($accion) {
        // --- OBTENER UN CANAL (PARA EDITAR) ---
        case 'obtener_uno':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("SELECT * FROM canales WHERE id = ?");
            $stmt->execute([$id]);
            $canal = $stmt->fetch();
            if ($canal) {
                $response = ['status' => 'success', 'canal' => $canal];
            } else {
                $response['message'] = 'Canal no encontrado.';
            }
            break;

        // --- CREAR UN NUEVO CANAL ---
        case 'crear':
            $sql = "INSERT INTO canales (nombre, descripcion, url_stream, url_logo, id_categoria, es_premium, activo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['nombre'], $_POST['descripcion'], $_POST['url_stream'], $_POST['url_logo'],
                $_POST['id_categoria'], isset($_POST['es_premium']) ? 1 : 0, isset($_POST['activo']) ? 1 : 0
            ]);
            $response = ['status' => 'success', 'message' => 'Canal creado exitosamente.'];
            break;

        // --- ACTUALIZAR UN CANAL EXISTENTE ---
        case 'actualizar':
            $sql = "UPDATE canales SET nombre = ?, descripcion = ?, url_stream = ?, url_logo = ?, id_categoria = ?, es_premium = ?, activo = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['nombre'], $_POST['descripcion'], $_POST['url_stream'], $_POST['url_logo'],
                $_POST['id_categoria'], isset($_POST['es_premium']) ? 1 : 0, isset($_POST['activo']) ? 1 : 0, $_POST['id']
            ]);
            $response = ['status' => 'success', 'message' => 'Canal actualizado exitosamente.'];
            break;

        // --- ELIMINAR UN CANAL ---
        case 'eliminar':
            $id = $_POST['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM canales WHERE id = ?");
            $stmt->execute([$id]);
            $response = ['status' => 'success', 'message' => 'Canal eliminado exitosamente.'];
            break;
    }
} catch (PDOException $e) {
    // En caso de un error de base de datos, lo capturamos.
    $response['message'] = 'Error de base de datos: ' . $e->getMessage();
}

// Imprimimos la respuesta en formato JSON.
echo json_encode($response);
?>