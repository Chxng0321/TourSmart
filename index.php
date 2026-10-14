
<?php
session_start();

require_once __DIR__ . "/config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($correo === "" || $password === "") {
        $mensaje = "Completa todos los campos.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id, nombre, correo, password
             FROM usuarios
             WHERE correo = ?
             LIMIT 1"
        );

        $consulta->execute([$correo]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (
            $usuario &&
            password_verify($password, $usuario["password"])
        ) {

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];
            $_SESSION["usuario_correo"] = $usuario["correo"];

            header("Location: inicio.php");
            exit;

        } else {
            $mensaje = "Correo o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | PlayasPerú</title>

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

            <h2>Iniciar sesión</h2>

            <p class="subtitulo">
                Ingresa a tu cuenta para continuar
            </p>

            <?php if ($mensaje !== ""): ?>
                <div class="alerta error">
                    <?php echo htmlspecialchars(
                        $mensaje,
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">

                <div class="campo">
                    <i class="fa-regular fa-user"></i>
                    <input
                        type="email"
                        name="correo"
                        placeholder="Correo electrónico"
                        autocomplete="email"
                        required>
                </div>

                <div class="campo">
                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Contraseña"
                        autocomplete="current-password"
                        required>

                    <button type="button"
                            class="ver-password"
                            id="togglePassword">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <div class="opciones">
                    <label>
                        <input type="checkbox" name="recordarme">
                        Recordarme
                    </label>

                    <a href="#" id="recuperarPassword">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit" class="btn-ingresar">
                    Ingresar
                </button>

                <p id="mensajeLogin" class="mensaje-login"></p>
            </form>

            <div class="separador">
                <span>o</span>
            </div>

            <button type="button" class="btn-google" id="btnGoogle">
                <i class="fa-brands fa-google"></i>
                Continuar con Google
            </button>

            <p class="registro">
                ¿No tienes una cuenta?
                <a href="registro.php">Regístrate</a>
            </p>

        </div>
    </section>

</main>

<script src="js/app.js"></script>

</body>
</html>
