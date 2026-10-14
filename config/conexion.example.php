
<?php

/* =========================
   CONFIGURACIÓN DE BASE DE DATOS
   TOURSMART - EJEMPLO
========================= */

$servidor = "localhost";
$base_datos = "playasperu";
$usuario = "TU_USUARIO";
$contrasena = "TU_CONTRASENA";

try {

    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$base_datos;charset=utf8mb4",
        $usuario,
        $contrasena,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);

    exit("Error al conectar con la base de datos.");

}

