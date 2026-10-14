
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/config/conexion.php";

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if (
        $nombre === "" ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL)
    ) {
        $mensaje = "Ingresa un nombre y correo válido.";
        $tipoMensaje = "error";

    } elseif (strlen($password) < 8) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres.";
        $tipoMensaje = "error";

    } elseif ($password !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipoMensaje = "error";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id FROM usuarios WHERE correo = ?"
        );
        $consulta->execute([$correo]);

        if ($consulta->fetch()) {
            $mensaje = "Este correo ya está registrado.";
            $tipoMensaje = "error";

        } else {

            $passwordSegura = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $guardar = $conexion->prepare(
                "INSERT INTO usuarios (nombre, correo, password)
                 VALUES (?, ?, ?)"
            );

            $guardar->execute([
                $nombre,
                $correo,
                $passwordSegura
            ]);

            $mensaje = "¡Cuenta creada correctamente! Ya puedes iniciar sesión.";
            $tipoMensaje = "exito";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse | PlayasPerú</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<main class="login-container">

    <section class="login-banner">
        <div class="banner-contenido">
            <div class="logo">
                <i class="fa-solid fa-umbrella-beach"></i>
                <h1>PlayasPerú</h1>
            </div>

            <p>
                Descubre, explora y vive<br>
                las mejores playas del Perú
            </p>
        </div>
    </section>

    <section class="login-formulario">

        <div class="formulario-contenido">

            <h2>Crear cuenta</h2>
            <p class="subtitulo">
                Regístrate y comienza a explorar el Perú
            </p>

            <?php if ($mensaje !== ""): ?>
                <div class="alerta <?php echo $tipoMensaje; ?>">
                    <?php echo htmlspecialchars(
                        $mensaje,
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="registro.php">

                <div class="campo">
                    <i class="fa-regular fa-user"></i>
                    <input
                        type="text"
                        name="nombre"
                        placeholder="Nombre completo"
                        autocomplete="name"
                        maxlength="100"
                        required>
                </div>

                <div class="campo">
                    <i class="fa-regular fa-envelope"></i>
                    <input
                        type="email"
                        name="correo"
                        placeholder="Correo electrónico"
                        autocomplete="email"
                        maxlength="150"
                        required>
                </div>

                <div class="campo">
                    <i class="fa-solid fa-lock"></i>
                    <input
                        type="password"
                        name="password"
                        placeholder="Contraseña (mínimo 8 caracteres)"
                        autocomplete="new-password"
                        minlength="8"
                        required>
                </div>

                <div class="campo">
                    <i class="fa-solid fa-lock"></i>
                    <input
                        type="password"
                        name="confirmar"
                        placeholder="Confirmar contraseña"
                        autocomplete="new-password"
                        minlength="8"
                        required>
                </div>

                <button type="submit" class="btn-ingresar">
                    Crear cuenta
                </button>

            </form>

            <p class="registro">
                ¿Ya tienes una cuenta?
                <a href="index.php">Inicia sesión</a>
            </p>

        </div>

    </section>

</main>

</body>
</html>
