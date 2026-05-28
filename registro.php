<?php
include 'conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    if (!empty($cedula) && !empty($nombre) && !empty($correo) && !empty($pass)) {
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $buscar = "SELECT * FROM usuarios WHERE correo = '$correo'";
            $resultado = $conn->query($buscar);

            if ($resultado->num_rows > 0) {
                $mensaje = "<p style='color: red;'>El correo ya está registrado.</p>";
            } else {
                $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuarios (cedula, nombre, correo, password) VALUES ('$cedula', '$nombre', '$correo', '$pass_encriptada')";

                if ($conn->query($sql)) {
                    $mensaje = "<p style='color: green;'>¡Registro exitoso! <a href='login.php'>Inicia sesión aquí</a></p>";
                } else {
                    $mensaje = "<p style='color: red;'>Error al registrar: " . $conn->error . "</p>";
                }
            }
        } else {
            $mensaje = "<p style='color: red;'>Ingresa un correo electrónico válido.</p>";
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
    <title>Registro - Gestor de Contraseñas</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 360px; }
        h3 { text-align: center; color: #333; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #218838; }
        p { text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Crear Cuenta</h3>
        <?php echo $mensaje; ?>
        <form method="POST">
            <input type="text" name="cedula" placeholder="Número de Cédula" required>
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrar Usuario</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>