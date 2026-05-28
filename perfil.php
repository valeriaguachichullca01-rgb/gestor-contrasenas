<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_logueado = $_SESSION['usuario'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_datos'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_correo = $_POST['correo'];

    if (!empty($nuevo_nombre) && filter_var($nuevo_correo, FILTER_VALIDATE_EMAIL)) {
        $update = "UPDATE usuarios SET nombre = '$nuevo_nombre', correo = '$nuevo_correo' WHERE correo = '$usuario_logueado'";
        
        if ($conn->query($update)) {
            $_SESSION['usuario'] = $nuevo_correo;
            $usuario_logueado = $nuevo_correo;
            $mensaje = "<p style='color: green;'>¡Datos actualizados con éxito!</p>";
        } else {
            $mensaje = "<p style='color: red;'>Error al actualizar los datos.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>Por favor, ingresa datos válidos.</p>";
    }
}

$sql = "SELECT * FROM usuarios WHERE correo = '$usuario_logueado'";
$resultado = $conn->query($sql);
$datos = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Gestor de Contraseñas</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { font-weight: bold; color: #555; font-size: 14px; }
        input { width: 100%; padding: 10px; margin: 8px 0 18px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 15px; }
        input:disabled { background: #f9f9f9; color: #888; }
        .btn-update { background: #28a745; color: white; border: none; padding: 12px; width: 100%; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .btn-update:hover { background: #218838; }
        .btn-pass { display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none; font-weight: bold; }
        .btn-pass:hover { text-decoration: underline; }
        .logout-link { display: block; text-align: center; margin-top: 15px; color: red; text-decoration: none; font-size: 15px; }
        .logout-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Mis Datos</h2>
        <?php echo $mensaje; ?>
        <form method="POST">
            <label>Cédula (No editable):</label>
            <input type="text" value="<?php echo $datos['cedula']; ?>" disabled>
            
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
            
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" value="<?php echo $datos['correo']; ?>" required>
            
            <button type="submit" name="actualizar_datos" class="btn-update">Guardar Cambios</button>
        </form>
        
        <a href="cambiar_password.php" class="btn-pass">Seguridad: Cambiar Contraseña</a>
        <hr style="border: 0; border-top: 1px solid #eee; margin-top: 20px;">
        <a href="logout.php" class="logout-link">Cerrar Sesión</a>
    </div>
</body>
</html>