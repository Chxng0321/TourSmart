<?php


/* =========================
   CONFIGURACION BD
========================= */


define("DB_SERVIDOR", "localhost");
define("DB_NOMBRE", "playasperu");
define("DB_USUARIO", "root");
define("DB_PASSWORD", "");



try {


    /* =========================
       CONEXION PDO
    ========================= */


    $conexion = new PDO(

        "mysql:host=".DB_SERVIDOR.
        ";dbname=".DB_NOMBRE.
        ";charset=utf8mb4",

        DB_USUARIO,

        DB_PASSWORD

    );



    /* =========================
       CONFIGURACION PDO
    ========================= */


    $conexion->setAttribute(

        PDO::ATTR_ERRMODE,

        PDO::ERRMODE_EXCEPTION

    );



    $conexion->setAttribute(

        PDO::ATTR_DEFAULT_FETCH_MODE,

        PDO::FETCH_ASSOC

    );



    $conexion->setAttribute(

        PDO::ATTR_EMULATE_PREPARES,

        false

    );



} catch(PDOException $e){



    /* GUARDAR ERROR EN LOG */

    error_log(

        "Error BD: ".$e->getMessage()

    );



    http_response_code(500);



    exit(

        "Error interno del servidor."

    );


}

?>