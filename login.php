<?php
session_start();
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    if (!empty($correo) && !empty($pass)) {
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $datos = $resultado->fetch_assoc();
            
            if (password_verify($pass, $datos['password'])) {
                $_SESSION['usuario'] = $datos['correo'];
                header("Location: perfil.php");
                exit();
            } else {
                $mensaje = "<p style='color: red;'>Contraseña incorrecta.</p>";
            }
        } else {
            $mensaje = "<p style='color: red;'>El correo electrónico no está registrado.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>Por favor, completa todos los campos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Gestor de Contraseñas</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 360px; }
        h3 { text-align: center; color: #333; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #0056b3; }
        p { text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Iniciar Sesión</h3>
        <?php echo $mensaje; ?>
        <form method="POST">
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>