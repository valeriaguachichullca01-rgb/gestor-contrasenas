<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_logueado = $_SESSION['usuario'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass_actual = $_POST['pass_actual'];
    $pass_nueva = $_POST['pass_nueva'];
    $pass_confirmar = $_POST['pass_confirmar'];

    if (!empty($pass_actual) && !empty($pass_nueva) && !empty($pass_confirmar)) {
        $sql = "SELECT password FROM usuarios WHERE correo = '$usuario_logueado'";
        $resultado = $conn->query($sql);
        $row = $resultado->fetch_assoc();

        if (password_verify($pass_actual, $row['password'])) {
            if ($pass_nueva === $pass_confirmar) {
                $pass_encriptada = password_hash($pass_nueva, PASSWORD_DEFAULT);
                $update = "UPDATE usuarios SET password = '$pass_encriptada' WHERE correo = '$usuario_logueado'";
                
                if ($conn->query($update)) {
                    $mensaje = "<p style='color: green;'>¡Contraseña actualizada con éxito!</p>";
                } else {
                    $mensaje = "<p style='color: red;'>Error al actualizar la contraseña.</p>";
                }
            } else {
                $mensaje = "<p style='color: red;'>La nueva contraseña y la confirmación no coinciden.</p>";
            }
        } else {
            $mensaje = "<p style='color: red;'>La contraseña actual es incorrecta.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>Todos los campos son obligatorios.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña - Gestor de Contraseñas</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 360px; }
        h3 { text-align: center; color: #333; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #555; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Cambiar Contraseña</h3>
        <?php echo $mensaje; ?>
        <form method="POST">
            <input type="password" name="pass_actual" placeholder="Contraseña Actual" required>
            <input type="password" name="pass_nueva" placeholder="Nueva Contraseña" required>
            <input type="password" name="pass_confirmar" placeholder="Confirmar Nueva Contraseña" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
        <a href="perfil.php" class="back-link">← Volver al Perfil</a>
    </div>
</body>
</html>